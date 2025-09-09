<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-2xl font-semibold text-gray-800 mb-6">Welcome, {{ $user->name }}</h1>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                <section class="bg-white shadow-sm sm:rounded-lg p-6 lg:col-span-2">
                    <h3 class="text-lg font-semibold mb-4 flex items-center gap-2 text-gray-800">
                        <svg class="h-5 w-5 text-gray-600" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/>
                        </svg>
                        Accounts
                    </h3>
                    @foreach($accounts as $account)
                        <div class="flex justify-between py-2 border-b last:border-b-0">
                            <div>
                                <div class="font-medium text-gray-900">{{ $account->name }}</div>
                                <div class="text-sm text-gray-500">{{ $account->type }}</div>
                            </div>
                            <div class="font-semibold text-gray-900">${{ number_format($account->balance, 2) }}</div>
                        </div>
                    @endforeach
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
            <div class="bg-white shadow-sm sm:rounded-lg p-4 mt-6 flex flex-wrap gap-4 items-end">
                <div>
                    <label for="filter-start" class="text-sm font-medium text-gray-700">Start</label>
                    <input type="date" id="filter-start" class="mt-1 border-gray-300 rounded-md" />
                </div>
                <div>
                    <label for="filter-end" class="text-sm font-medium text-gray-700">End</label>
                    <input type="date" id="filter-end" class="mt-1 border-gray-300 rounded-md" />
                </div>
                <div>
                    <label for="filter-account" class="text-sm font-medium text-gray-700">Account</label>
                    <select id="filter-account" class="mt-1 border-gray-300 rounded-md">
                        <option value="">All</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="filter-category" class="text-sm font-medium text-gray-700">Category</label>
                    <select id="filter-category" class="mt-1 border-gray-300 rounded-md">
                        <option value="">All</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="filter-period" class="text-sm font-medium text-gray-700">Period</label>
                    <select id="filter-period" class="mt-1 border-gray-300 rounded-md">
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly" selected>Monthly</option>
                    </select>
                </div>
                <button id="applyFilters" class="px-4 py-2 bg-blue-500 text-white rounded">Apply</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <section class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">Totals Over Time</h3>
                    <canvas id="timeTotalsChart"></canvas>
                </section>
                <section class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">Category Totals</h3>
                    <canvas id="categoryTotalsChart"></canvas>
                </section>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.3/dist/echo.iife.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let timeChart;
            let categoryChart;

            function buildParams() {
                const params = new URLSearchParams();
                const start = document.getElementById('filter-start').value;
                if (start) params.append('start', start);
                const end = document.getElementById('filter-end').value;
                if (end) params.append('end', end);
                const account = document.getElementById('filter-account').value;
                if (account) params.append('account', account);
                const category = document.getElementById('filter-category').value;
                if (category) params.append('category', category);
                return params.toString();
            }

            function fetchData() {
                const params = buildParams();
                const period = document.getElementById('filter-period').value;
                fetch(`/api/dashboard/${period}-totals?${params}`)
                    .then(r => r.json())
                    .then(data => {
                        const labelKey = period === 'monthly' ? 'month' : period === 'weekly' ? 'week' : 'day';
                        const labels = data.map(item => item[labelKey]);
                        const totals = data.map(item => item.total);
                        if (timeChart) timeChart.destroy();
                        timeChart = new Chart(document.getElementById('timeTotalsChart'), {
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

                fetch(`/api/dashboard/category-totals?${params}`)
                    .then(r => r.json())
                    .then(data => {
                        const labels = data.map(item => item.category);
                        const totals = data.map(item => item.total);
                        if (categoryChart) categoryChart.destroy();
                        categoryChart = new Chart(document.getElementById('categoryTotalsChart'), {
                            type: 'doughnut',
                            data: {
                                labels,
                                datasets: [{
                                    data: totals,
                                }]
                            },
                        });
                    });
            }

            document.getElementById('applyFilters').addEventListener('click', fetchData);

            fetchData();

            const echo = new Echo({
                broadcaster: 'pusher',
                key: '{{ env('PUSHER_APP_KEY') }}',
                cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
                wsHost: window.location.hostname,
                wsPort: 6001,
                forceTLS: false,
                disableStats: true,
            });

            echo.channel('transactions').listen('TransactionChanged', () => {
                fetchData();
            });
        });
    </script>
</x-app-layout>
