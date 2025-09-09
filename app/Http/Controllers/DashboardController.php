<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Expenses;
use Illuminate\Http\Request;
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
        $categories = $user->expenses;

        return view('dashboard', compact('user', 'accounts', 'budgets', 'categories'));
    }

    /**
     * Return aggregated monthly transaction totals for the authenticated user.
     */
    public function monthlyTotals(Request $request)
    {
        $userId = auth()->id();

        $query = Transaction::select(
                DB::raw("DATE_FORMAT(date, '%Y-%m') as month"),
                DB::raw('SUM(amount) as total')
            )
            ->whereHas('account', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            });

        if ($request->filled('start')) {
            $query->whereDate('date', '>=', $request->input('start'));
        }

        if ($request->filled('end')) {
            $query->whereDate('date', '<=', $request->input('end'));
        }

        if ($request->filled('account')) {
            $query->where('account_id', $request->input('account'));
        }

        if ($request->filled('category')) {
            $query->whereHas('expenses', function ($q) use ($request) {
                $q->where('expenses.id', $request->input('category'));
            });
        }

        $totals = $query->groupBy('month')
            ->orderBy('month')
            ->get();

        return response()->json($totals);
    }

    /**
     * Return aggregated totals grouped by expense category for the authenticated user.
     */
    public function categoryTotals(Request $request)
    {
        $userId = auth()->id();

        $query = Expenses::select(
                'expenses.name as category',
                DB::raw('SUM(transactions.amount) as total')
            )
            ->join('expense_type', 'expenses.id', '=', 'expense_type.expenses_id')
            ->join('transactions', 'expense_type.transaction_id', '=', 'transactions.id')
            ->join('accounts', 'transactions.account_id', '=', 'accounts.id')
            ->where('expenses.user_id', $userId)
            ->where('accounts.user_id', $userId);

        if ($request->filled('start')) {
            $query->whereDate('transactions.date', '>=', $request->input('start'));
        }

        if ($request->filled('end')) {
            $query->whereDate('transactions.date', '<=', $request->input('end'));
        }

        if ($request->filled('account')) {
            $query->where('accounts.id', $request->input('account'));
        }

        if ($request->filled('category')) {
            $query->where('expenses.id', $request->input('category'));
        }

        $totals = $query->groupBy('expenses.name')
            ->get();

        return response()->json($totals);
    }

    /**
     * Return aggregated weekly transaction totals for the authenticated user.
     */
    public function weeklyTotals(Request $request)
    {
        $userId = auth()->id();

        $query = Transaction::select(
                DB::raw("DATE_FORMAT(date, '%x-%v') as week"),
                DB::raw('SUM(amount) as total')
            )
            ->whereHas('account', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            });

        if ($request->filled('start')) {
            $query->whereDate('date', '>=', $request->input('start'));
        }

        if ($request->filled('end')) {
            $query->whereDate('date', '<=', $request->input('end'));
        }

        if ($request->filled('account')) {
            $query->where('account_id', $request->input('account'));
        }

        if ($request->filled('category')) {
            $query->whereHas('expenses', function ($q) use ($request) {
                $q->where('expenses.id', $request->input('category'));
            });
        }

        $totals = $query->groupBy('week')
            ->orderBy('week')
            ->get();

        return response()->json($totals);
    }

    /**
     * Return aggregated daily transaction totals for the authenticated user.
     */
    public function dailyTotals(Request $request)
    {
        $userId = auth()->id();

        $query = Transaction::select(
                DB::raw('DATE(date) as day'),
                DB::raw('SUM(amount) as total')
            )
            ->whereHas('account', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            });

        if ($request->filled('start')) {
            $query->whereDate('date', '>=', $request->input('start'));
        }

        if ($request->filled('end')) {
            $query->whereDate('date', '<=', $request->input('end'));
        }

        if ($request->filled('account')) {
            $query->where('account_id', $request->input('account'));
        }

        if ($request->filled('category')) {
            $query->whereHas('expenses', function ($q) use ($request) {
                $q->where('expenses.id', $request->input('category'));
            });
        }

        $totals = $query->groupBy('day')
            ->orderBy('day')
            ->get();

        return response()->json($totals);
    }
}

