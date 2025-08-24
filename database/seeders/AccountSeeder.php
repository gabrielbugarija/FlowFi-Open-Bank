<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;


class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        $user->accounts()->createMany([
            [
                'name' => 'Checking Account',
                'type' => 'checking',
                'balance' => 1500.00,
            ],
            [
                'name' => 'Savings Account',
                'type' => 'savings',
                'balance' => 5000.00,
            ],
            [
                'name' => 'Credit Card',
                'type' => 'credit',
                'balance' => -200.00
            ]
        ]);
    }
}
