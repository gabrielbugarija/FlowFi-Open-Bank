<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Expenses;


class BudgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        {
        $user = User::first();
        $food = Expenses::where('name', 'Food')->first();
        $transport = Expenses::where('name', 'Transport')->first();

        $user->budgets()->createMany([
            [
                'expenses_id' => $food->id,
                'amount' => 400.00,
                'period' => 'monthly',
            ],
            [
                'expenses_id' => $transport->id,
                'amount' => 150.00,
                'period' => 'monthly',
            ],
        ]);

        
    }
}

}
