<?php

use App\Models\Account;
use App\Models\User;

it('allows storing transactions for owned accounts', function () {
    $user = User::factory()->create();
    $account = Account::create([
        'user_id' => $user->id,
        'name' => 'Checking',
        'type' => 'checking',
        'balance' => 0,
    ]);

    $payload = [
        'account_id' => $account->id,
        'type' => 'income',
        'description' => 'Salary deposit',
        'amount' => 1200,
        'date' => now()->toDateString(),
    ];

    $response = $this->actingAs($user)->post(route('transactions.store'), $payload);

    $response->assertRedirect(route('transactions.index', absolute: false));

    $this->assertDatabaseHas('transactions', [
        'account_id' => $account->id,
        'description' => 'Salary deposit',
    ]);
});

it('prevents storing transactions for accounts owned by other users', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $otherAccount = Account::create([
        'user_id' => $otherUser->id,
        'name' => 'Savings',
        'type' => 'savings',
        'balance' => 0,
    ]);

    $payload = [
        'account_id' => $otherAccount->id,
        'type' => 'income',
        'description' => 'Unauthorized deposit',
        'amount' => 50,
        'date' => now()->toDateString(),
    ];

    $response = $this->actingAs($user)
        ->from(route('transactions.create'))
        ->post(route('transactions.store'), $payload);

    $response->assertRedirect(route('transactions.create', absolute: false));
    $response->assertSessionHasErrors('account_id');

    $this->assertDatabaseMissing('transactions', [
        'description' => 'Unauthorized deposit',
    ]);
});
