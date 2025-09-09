<?php

namespace App\Http\Controllers;

use App\Events\TransactionsChanged;
use App\Http\Requests\StoreTransactionRequest;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::whereHas('account', function ($query) {
            $query->where('user_id', auth()->id());
        })->with('account', 'expenses')->latest()->get();

        return view('transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $accounts = Account::all();

        return view('transactions.create', compact('accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        DB::transaction(function () use ($request) {
            Transaction::create($request->validated());

            DB::afterCommit(fn () => TransactionsChanged::dispatch(Auth::id()));
        });

        return redirect()->route('transactions.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        $accounts = Account::all();

        return view('transactions.edit', compact('transaction', 'accounts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreTransactionRequest $request, Transaction $transaction)
    {
        DB::transaction(function () use ($request, $transaction) {
            $transaction->update($request->validated());

            DB::afterCommit(fn () => TransactionsChanged::dispatch(Auth::id()));
        });

        return redirect()->route('transactions.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        DB::transaction(function () use ($transaction) {
            $transaction->delete();

            DB::afterCommit(fn () => TransactionsChanged::dispatch(Auth::id()));
        });

        return redirect()->route('transactions.index');
    }
}
