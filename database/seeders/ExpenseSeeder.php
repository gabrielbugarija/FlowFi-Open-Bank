<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;


class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $user = User::first();

        $user->expenses()->createMany([
            ['name' => 'Food'],
            ['name' => 'Rent'],
            ['name' => 'Transport'],
            ['name' => 'Salary'],
        ]);
    }
}
