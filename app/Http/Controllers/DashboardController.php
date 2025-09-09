<?php

namespace App\Http\Controllers;

use App\Models\Expenses;
use App\Models\Transaction;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Show the dashboard view.
     */
    public function index()
    {
        $user = auth()->user();
        $accounts = $user->accounts;
        $budgets = $user->budgets()->with('expense')->get();

        return view('dashboard', compact('user', 'accounts', 'budgets'));
    }

    /**
     * Return aggregated monthly transaction totals for the authenticated user.
     */
    public function monthlyTotals()
    {
        $userId = auth()->id();

        $totals = Cache::rememberForever("dashboard.monthly_totals.$userId", function () use ($userId) {
            return Transaction::select(
                DB::raw("DATE_FORMAT(date, '%Y-%m') as month"),
                DB::raw('SUM(amount) as total')
            )
                ->whereHas('account', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                })
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        });

        return response()->json($totals);
    }

    /**
     * Return aggregated totals grouped by expense category for the authenticated user.
     */
    public function categoryTotals()
    {
        $userId = auth()->id();

        $totals = Cache::rememberForever("dashboard.category_totals.$userId", function () use ($userId) {
            return Expenses::select(
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
        });

        return response()->json($totals);
    }
}
