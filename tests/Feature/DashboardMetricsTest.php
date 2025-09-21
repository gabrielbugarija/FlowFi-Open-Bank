<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Expenses;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DashboardMetricsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    public function test_monthly_totals_groups_transactions_by_month(): void
    {
        $user = User::factory()->create();
        $account = Account::create([
            'user_id' => $user->id,
            'name' => 'checking',
            'type' => 'checking',
            'balance' => 0,
        ]);

        Transaction::create([
            'account_id' => $account->id,
            'type' => 'income',
            'description' => 'January pay',
            'amount' => 200,
            'date' => '2024-01-10',
        ]);

        Transaction::create([
            'account_id' => $account->id,
            'type' => 'income',
            'description' => 'January bonus',
            'amount' => 100,
            'date' => '2024-01-20',
        ]);

        Transaction::create([
            'account_id' => $account->id,
            'type' => 'expense',
            'description' => 'February rent',
            'amount' => -50,
            'date' => '2024-02-01',
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/dashboard/monthly-totals');

        $response->assertOk();

        $data = $response->json();

        $this->assertCount(2, $data);
        $this->assertSame('2024-01', $data[0]['month']);
        $this->assertEquals(300, (int) round($data[0]['total']));
        $this->assertSame('2024-02', $data[1]['month']);
        $this->assertEquals(-50, (int) round($data[1]['total']));
    }

    public function test_category_totals_groups_transactions_by_expense(): void
    {
        $user = User::factory()->create();
        $account = Account::create([
            'user_id' => $user->id,
            'name' => 'checking',
            'type' => 'checking',
            'balance' => 0,
        ]);

        $rentCategory = Expenses::create([
            'user_id' => $user->id,
            'name' => 'Rent',
        ]);

        $groceriesCategory = Expenses::create([
            'user_id' => $user->id,
            'name' => 'Groceries',
        ]);

        $rent = Transaction::create([
            'account_id' => $account->id,
            'type' => 'expense',
            'description' => 'Rent',
            'amount' => -1200,
            'date' => '2024-03-01',
        ]);

        $groceries = Transaction::create([
            'account_id' => $account->id,
            'type' => 'expense',
            'description' => 'Groceries week 1',
            'amount' => -150,
            'date' => '2024-03-02',
        ]);

        DB::table('expense_type')->insert([
            ['transaction_id' => $rent->id, 'expenses_id' => $rentCategory->id],
            ['transaction_id' => $groceries->id, 'expenses_id' => $groceriesCategory->id],
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/dashboard/category-totals');

        $response->assertOk();

        $data = collect($response->json())->keyBy('category');

        $this->assertEquals(-1200, (int) round($data['Rent']['total'] ?? 0));
        $this->assertEquals(-150, (int) round($data['Groceries']['total'] ?? 0));
    }
}
