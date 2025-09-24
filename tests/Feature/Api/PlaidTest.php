<?php

use App\Models\User;
use App\Services\PlaidService;
use Illuminate\Support\Facades\Crypt;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\mock;

it('requires authentication to create a link token', function () {
    $this->getJson('/api/plaid/link-token')->assertUnauthorized();
});

it('returns a link token for authenticated users', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    mock(PlaidService::class)
        ->shouldReceive('createLinkToken')
        ->once()
        ->withArgs(fn ($userId) => (int) $userId === $user->id)
        ->andReturn('link-sandbox-token');

    $this->getJson('/api/plaid/link-token')
        ->assertOk()
        ->assertJson(['link_token' => 'link-sandbox-token']);
});

it('requires authentication to exchange a public token', function () {
    $this->postJson('/api/plaid/exchange', ['public_token' => 'public-token'])
        ->assertUnauthorized();
});

it('stores the Plaid access token during exchange', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    mock(PlaidService::class)
        ->shouldReceive('exchangePublicToken')
        ->once()
        ->with('public-token')
        ->andReturn([
            'access_token' => 'access-token',
            'item_id' => 'item-id',
        ]);

    $this->postJson('/api/plaid/exchange', ['public_token' => 'public-token'])
        ->assertNoContent();

    $freshUser = $user->fresh();

    expect($freshUser->plaid_access_token)->not->toBeNull();
    expect(Crypt::decryptString($freshUser->plaid_access_token))->toBe('access-token');
});

it('rejects webhook payloads without a signature header', function () {
    config(['plaid.webhook_secret' => 'test-secret']);

    $this->postJson('/api/plaid/webhook', [
        'webhook_type' => 'TRANSACTIONS',
        'webhook_code' => 'INITIAL_UPDATE',
    ])->assertStatus(401);
});

it('rejects webhook payloads with an invalid signature', function () {
    config(['plaid.webhook_secret' => 'test-secret']);

    $this->withHeaders(['Plaid-Webhook-Signature' => 'v1=invalid'])
        ->postJson('/api/plaid/webhook', [
            'webhook_type' => 'TRANSACTIONS',
            'webhook_code' => 'INITIAL_UPDATE',
        ])
        ->assertStatus(403);
});

it('rejects webhook payloads with unsupported types', function () {
    config(['plaid.webhook_secret' => 'test-secret']);

    $payload = [
        'webhook_type' => 'UNKNOWN',
        'webhook_code' => 'SOMETHING',
    ];

    $json = json_encode($payload, JSON_THROW_ON_ERROR);
    $signature = hash_hmac('sha256', $json, 'test-secret');

    $this->call(
        'POST',
        '/api/plaid/webhook',
        [],
        [],
        [],
        [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT' => 'application/json',
            'HTTP_PLAID_WEBHOOK_SIGNATURE' => "v1={$signature}",
        ],
        $json
    )->assertStatus(400);
});

it('accepts webhook payloads with valid signatures and supported types', function () {
    config(['plaid.webhook_secret' => 'test-secret']);

    $payload = [
        'webhook_type' => 'TRANSACTIONS',
        'webhook_code' => 'INITIAL_UPDATE',
    ];

    $json = json_encode($payload, JSON_THROW_ON_ERROR);
    $signature = hash_hmac('sha256', $json, 'test-secret');

    $this->call(
        'POST',
        '/api/plaid/webhook',
        [],
        [],
        [],
        [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT' => 'application/json',
            'HTTP_PLAID_WEBHOOK_SIGNATURE' => "v1={$signature}",
        ],
        $json
    )->assertNoContent();
});
