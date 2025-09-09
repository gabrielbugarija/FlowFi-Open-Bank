<?php

use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use function Pest\Laravel\artisan;
use Illuminate\Support\Carbon;

it('keeps account balance in sync via transaction events', function () {
    $user = User::factory()->create();
    $account = Account::create([
        'user_id' => $user->id,
        'name' => 'Checking',
        'type' => 'checking',
        'balance' => 0,
    ]);

    $transaction = Transaction::create([
        'account_id' => $account->id,
        'type' => 'income',
        'description' => 'Deposit',
        'amount' => 100,
        'date' => Carbon::now()->toDateString(),
    ]);

    $account->refresh();
    expect((float) $account->balance)->toBe(100.0);

    $transaction->update(['amount' => 50]);
    $account->refresh();
    expect((float) $account->balance)->toBe(50.0);

    $transaction->delete();
    $account->refresh();
    expect((float) $account->balance)->toBe(0.0);
});

it('recalculates balances using the artisan command', function () {
    $user = User::factory()->create();
    $account = Account::create([
        'user_id' => $user->id,
        'name' => 'Cash',
        'type' => 'cash',
        'balance' => 0,
    ]);

    Transaction::create([
        'account_id' => $account->id,
        'type' => 'income',
        'description' => 'Deposit',
        'amount' => 100,
        'date' => Carbon::now()->toDateString(),
    ]);

    Transaction::create([
        'account_id' => $account->id,
        'type' => 'expense',
        'description' => 'Withdrawal',
        'amount' => -40,
        'date' => Carbon::now()->toDateString(),
    ]);

    $account->update(['balance' => 999]);

    artisan('account:recalc')->assertExitCode(0);

    $account->refresh();
    expect((float) $account->balance)->toBe(60.0);
});

