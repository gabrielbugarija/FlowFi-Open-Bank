<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $budgets = $request->user()->budgets()->get();
        return response()->json($budgets);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'goal_amount' => ['required', 'numeric', 'min:0'],
            'period' => ['required', 'in:monthly,quarterly'],
        ]);

        $budget = $request->user()->budgets()->create($data);

        return response()->json($budget, 201);
    }
}
