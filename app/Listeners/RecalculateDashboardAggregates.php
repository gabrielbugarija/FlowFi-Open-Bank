<?php

namespace App\Listeners;

use App\Events\TransactionsChanged;
use App\Models\Expenses;
use App\Models\Transaction;
use App\Support\Database\DateExpressions;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RecalculateDashboardAggregates
{
    /**
     * Handle the event.
     */
    public function handle(TransactionsChanged $event): void
    {
        $userId = $event->userId;

        // Invalidate caches
        Cache::forget("dashboard.monthly_totals.$userId");
        Cache::forget("dashboard.category_totals.$userId");

        $monthExpression = DateExpressions::monthYear('transactions.date');

        // Recompute monthly totals
        $monthlyTotals = Transaction::selectRaw("$monthExpression as month, SUM(amount) as total")
            ->whereHas('account', fn ($q) => $q->where('user_id', $userId))
            ->groupByRaw($monthExpression)
            ->orderBy('month')
            ->get();

        Cache::forever("dashboard.monthly_totals.$userId", $monthlyTotals);

        // Recompute category totals
        $categoryTotals = Expenses::select(
            'expenses.name as category',
            DB::raw('SUM(transactions.amount) as total')
        )
            ->join('expense_type', 'expenses.id', '=', 'expense_type.expenses_id')
            ->join('transactions', 'expense_type.transaction_id', '=', 'transactions.id')
            ->join('accounts', 'transactions.account_id', '=', 'accounts.id')
            ->where('expenses.user_id', $userId)
            ->where('accounts.user_id', $userId)
            ->groupBy('expenses.name')
            ->get();

        Cache::forever("dashboard.category_totals.$userId", $categoryTotals);
    }
}
