<?php

namespace App\Console\Commands;

use App\Models\Account;
use Illuminate\Console\Command;

class RecalculateAccountBalance extends Command
{
    protected $signature = 'account:recalc';

    protected $description = 'Recalculate balances for all accounts based on their transactions';

    public function handle(): int
    {
        Account::all()->each->updateBalance();

        $this->info('Account balances recalculated successfully.');

        return self::SUCCESS;
    }
}
