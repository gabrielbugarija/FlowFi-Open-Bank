<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text black leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-2xl font-semibold text-black mb-6">Welcome, {{ $user->name }}</h1>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                <section class="bg-white shadow-sm sm:rounded-lg p-6 lg:col-span-2">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-4">
                        <div class="flex items-center gap-2 text-gray-800">
                            <svg class="h-5 w-5 text-gray-600" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/>
                            </svg>
                            <h3 class="text-lg font-semibold">Accounts</h3>
                        </div>
                        <button
                            id="connect-bank-button"
                            type="button"
                            class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                        >
                            Connect Bank
                        </button>
                    </div>
                    @forelse($accounts as $account)
                        <div class="flex justify-between py-2 border-b last:border-b-0">
                            <div>
                                <div class="font-medium text-gray-900">{{ $account->name }}</div>
                                <div class="text-sm text-gray-500">{{ $account->type }}</div>
                            </div>
                            <div class="font-semibold text-gray-900">${{ number_format($account->balance, 2) }}</div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No accounts yet. Connect a bank to get started.</p>
                    @endforelse
                </section>

                <section class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4 flex items-center gap-2 text-gray-800">
                        <svg class="h-5 w-5 text-gray-600" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                        </svg>
                        Budgets
                    </h3>
                    @foreach($budgets as $budget)
                        <div class="mb-4">
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-medium text-gray-900">{{ $budget->expense->name ?? 'No category' }}</span>
                                <span class="font-semibold text-gray-900">${{ number_format($budget->amount, 2) }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: {{ min(100, ($budget->amount / max($budget->amount, 1000)) * 100) }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </section>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <section class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">Monthly Totals</h3>
                    <canvas id="monthlyTotalsChart"></canvas>
                </section>
                <section class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">Category Totals</h3>
                    <canvas id="categoryTotalsChart"></canvas>
                </section>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.plaid.com/link/v2/stable/link-initialize.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const connectButton = document.getElementById('connect-bank-button');

            if (connectButton) {
                connectButton.addEventListener('click', async () => {
                    connectButton.disabled = true;
                    const originalLabel = connectButton.textContent;
                    connectButton.textContent = 'Connecting?';

                    try {
                        const tokenResponse = await fetch('/api/plaid/link-token');
                        if (!tokenResponse.ok) {
                            throw new Error('Failed to create link token');
                        }

                        const { link_token: linkToken } = await tokenResponse.json();

                        if (!window.Plaid || !window.Plaid.create) {
                            alert('Plaid Link script did not load. Please check your network connection.');
                            return;
                        }

                        const handler = Plaid.create({
                            token: linkToken,
                            onSuccess: async (publicToken) => {
                                await fetch('/api/plaid/exchange', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                    },
                                    body: JSON.stringify({ public_token: publicToken }),
                                });

                                window.location.reload();
                            },
                            onExit: () => {
                                connectButton.disabled = false;
                                connectButton.textContent = originalLabel;
                            },
                        });

                        handler.open();
                    } catch (error) {
                        console.error(error);
                        alert('Could not start Plaid Link. Please try again.');
                        connectButton.disabled = false;
                        connectButton.textContent = originalLabel;
                    }
                });
            }

            fetch('/api/dashboard/monthly-totals')
                .then(r => r.json())
                .then(data => {
                    const labels = data.map(item => item.month);
                    const totals = data.map(item => item.total);
                    new Chart(document.getElementById('monthlyTotalsChart'), {
                        type: 'bar',
                        data: {
                            labels,
                            datasets: [{
                                label: 'Total',
                                data: totals,
                                backgroundColor: 'rgba(59, 130, 246, 0.5)',
                            }]
                        },
                    });
                });

            fetch('/api/dashboard/category-totals')
                .then(r => r.json())
                .then(data => {
                    const labels = data.map(item => item.category);
                    const totals = data.map(item => item.total);
                    new Chart(document.getElementById('categoryTotalsChart'), {
                        type: 'doughnut',
                        data: {
                            labels,
                            datasets: [{
                                data: totals,
                            }]
                        },
                    });
                });
        });
    </script>
</x-app-layout>
