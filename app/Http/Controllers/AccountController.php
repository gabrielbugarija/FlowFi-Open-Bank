<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAccountRequest;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;


class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accounts = Account::withCount('transactions')
            ->where('user_id', auth()->id())
            ->get();

        return view('accounts.index', compact('accounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('accounts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccountRequest $request)
    {
        $request->user()->accounts()->create($request->validated());

        return redirect()->route('accounts.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Account $account)
    {
        abort_unless($account->user_id === auth()->id(), 403);

        $transactions = $account->transactions()
            ->orderBy('date')
            ->get();

        return view('accounts.show', compact('account', 'transactions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Account $account)
    {
        abort_unless($account->user_id === Auth::id(), 403);

        return view('accounts.edit', compact('account'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreAccountRequest $request, Account $account)
    {
        abort_unless($account->user_id === $request->user()->id, 403);

        $account->update($request->validated());

        return redirect()->route('accounts.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        abort_unless($account->user_id === Auth::id(), 403);

        $account->delete();

        return redirect()->route('accounts.index');
    }
}
