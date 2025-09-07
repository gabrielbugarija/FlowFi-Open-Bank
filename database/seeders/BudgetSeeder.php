<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
class BudgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        $user->budgets()->createMany([
            [
                'goal_amount' => 1000.00,
                'period' => 'monthly',
            ],
            [
                'goal_amount' => 3000.00,
                'period' => 'quarterly',
            ],
        ]);
    }
}
