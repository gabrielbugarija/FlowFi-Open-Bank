<x-layout title="Dashboard">
    <h1 class="page-title">
        <svg class="icon" viewBox="0 0 24 24">
            <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
        </svg>
        Welcome, {{ $user->name }}
    </h1>

    <div class="stats-grid">
        <section class="card">
            <h2 style="font-size: 1.5rem; font-weight: 600; margin-bottom: 1.5rem; color: #2d3748; display: flex; align-items: center; gap: 0.5rem;">
                <svg class="icon" viewBox="0 0 24 24">
                    <path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/>
                </svg>
                Accounts
            </h2>
            @foreach($accounts as $account)
                <div class="account-item">
                    <div class="account-name">{{ $account->name }}</div>
                    <div class="balance">${{ number_format($account->balance, 2) }}</div>
                    <span class="account-type">{{ $account->type }}</span>
                </div>
            @endforeach
        </section>

        <section class="card">
            <h2 style="font-size: 1.5rem; font-weight: 600; margin-bottom: 1.5rem; color: #2d3748; display: flex; align-items: center; gap: 0.5rem;">
                <svg class="icon" viewBox="0 0 24 24">
                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                </svg>
                Budgets
            </h2>
            @foreach($budgets as $budget)
                <div class="budget-item">
                    <div class="budget-card">
                        <div class="budget-name">
                            {{ $budget->expenses->name ?? 'No category' }}
                        </div>
                        <div class="budget-amount">
                            ${{ number_format($budget->amount, 2) }}
                        </div>
                        <div class="budget-progress">
                            <div class="budget-progress-bar" style="width: {{ min(75, ($budget->amount / max($budget->amount, 1000)) * 100) }}%"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </section>
    </div>
</x-layout>
