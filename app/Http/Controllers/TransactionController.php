<?php

namespace App\Http\Controllers;

use App\Events\TransactionsChanged;
use App\Http\Requests\StoreTransactionRequest;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Transaction::whereHas('account', function ($query) {
            $query->where('user_id', auth()->id());
        })->with('account', 'expenses')->latest();

        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->string('search') . '%');
        }

        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->date('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->date('end_date'));
        }

        $transactions = $query->paginate(25)->withQueryString();

        return view('transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $accounts = Account::where('user_id', Auth::id())->get();

        return view('transactions.create', compact('accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        $data = $request->validated();

        $userId = $request->user()?->id;

        abort_if(is_null($userId), 403);

        abort_unless(
            Account::where('user_id', $userId)->whereKey($data['account_id'])->exists(),
            403
        );

        DB::transaction(function () use ($data) {
            Transaction::create($data);

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
        $accounts = Account::where('user_id', Auth::id())->get();

        return view('transactions.edit', compact('transaction', 'accounts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreTransactionRequest $request, Transaction $transaction)
    {
        abort_unless($transaction->account?->user_id === $request->user()?->id, 403);

        $data = $request->validated();

        $userId = $request->user()?->id;

        abort_if(is_null($userId), 403);

        abort_unless(
            Account::where('user_id', $userId)->whereKey($data['account_id'])->exists(),
            403
        );

        DB::transaction(function () use ($transaction, $data) {
            $transaction->update($data);

            DB::afterCommit(fn () => TransactionsChanged::dispatch(Auth::id()));
        });

        return redirect()->route('transactions.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        abort_unless($transaction->account?->user_id === Auth::id(), 403);

        DB::transaction(function () use ($transaction) {
            $transaction->delete();

            DB::afterCommit(fn () => TransactionsChanged::dispatch(Auth::id()));
        });

        return redirect()->route('transactions.index');
    }
}
