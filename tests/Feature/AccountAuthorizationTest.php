<?php

use App\Models\Account;
use App\Models\User;

test('users cannot view the edit form for accounts they do not own', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();

    $account = Account::create([
        'user_id' => $owner->id,
        'name' => 'Owner Checking',
        'type' => 'checking',
        'balance' => 100,
    ]);

    $response = $this
        ->actingAs($otherUser)
        ->get(route('accounts.edit', $account));

    $response->assertForbidden();
});

test('users cannot update accounts they do not own', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();

    $account = Account::create([
        'user_id' => $owner->id,
        'name' => 'Owner Savings',
        'type' => 'savings',
        'balance' => 250,
    ]);

    $response = $this
        ->actingAs($otherUser)
        ->put(route('accounts.update', $account), [
            'name' => 'Updated Name',
            'type' => 'checking',
            'balance' => 500,
        ]);

    $response->assertForbidden();

    $account->refresh();

    expect($account->name)->toBe('Owner Savings');
    expect((float) $account->balance)->toBe(250.0);
    expect($account->type)->toBe('savings');
});

test('users cannot delete accounts they do not own', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();

    $account = Account::create([
        'user_id' => $owner->id,
        'name' => 'Owner Cash',
        'type' => 'cash',
        'balance' => 75,
    ]);

    $response = $this
        ->actingAs($otherUser)
        ->delete(route('accounts.destroy', $account));

    $response->assertForbidden();

    expect(Account::query()->whereKey($account->id)->exists())->toBeTrue();
});
