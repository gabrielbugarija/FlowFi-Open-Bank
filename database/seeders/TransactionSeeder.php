<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;
use App\Models\Expenses;
use App\Models\Transaction;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $checking = Account::where('name', 'Checking Account')->first();
        $credit = Account::where('name', 'Credit Card')->first();

        $food = Expenses::where('name', 'Food')->first();
        $transport = Expenses::where('name', 'Transport')->first();
        $rent = Expenses::where('name', 'Rent')->first();
        $salary = Expenses::where('name', 'Salary')->first();

        $t1 = $checking->transactions()->create([
            'type' => 'expense',
            'description' => 'Grocery store',
            'amount' => 75.50,
            'date' => now()->subDays(3),
        ]);
        $t1->expenses()->attach($food);

        $t2 = $checking->transactions()->create([
            'type' => 'expense',
            'description' => 'Bus pass',
            'amount' => 50.00,
            'date' => now()->subDays(2),
        ]);
        $t2->expenses()->attach($transport);

        $t3 = $credit->transactions()->create([
            'type' => 'expense',
            'description' => 'Rent payment',
            'amount' => 1200.00,
            'date' => now()->subDays(5),
        ]);
        $t3->expenses()->attach($rent);

        $t4 = $checking->transactions()->create([
            'type' => 'income',
            'description' => 'Monthly Salary',
            'amount' => 3000.00,
            'date' => now()->subDays(10),
        ]);
        $t4->expenses()->attach($salary);
    }
}
