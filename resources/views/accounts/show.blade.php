<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ $account->name }} Details
    </h2>
  </x-slot>

  <div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
      <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 flex justify-between items-start">
        <div>
          <p class="text-sm text-gray-500 dark:text-gray-400">Type</p>
          <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ ucfirst(str_replace('_', ' ', $account->type)) }}</p>
          <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">Created</p>
          <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $account->created_at?->format('Y-m-d') }}</p>
        </div>
        <div class="text-right">
          <p class="text-sm text-gray-500 dark:text-gray-400">Balance</p>
          <p class="text-3xl font-bold {{ $account->balance < 0 ? 'text-red-600 dark:text-red-400' : 'text-indigo-600 dark:text-indigo-400' }}">${{ number_format($account->balance, 2) }}</p>
          <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">Transactions</p>
          <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $transactions->count() }}</p>
        </div>
      </div>

      <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Transaction History</h3>
        <canvas id="accountChart" class="w-full h-64"></canvas>
      </div>

      <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
        <div class="overflow-x-auto">
          <table class="min-w-full table-auto divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900/40">
              <tr>
                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Date</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Description</th>
                <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700 dark:text-gray-200">Amount</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
              @forelse($transactions as $tx)
                <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-100 dark:odd:bg-gray-800 dark:even:bg-gray-900/30 dark:hover:bg-gray-900/50">
                  <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ optional($tx->date)->format('Y-m-d') ?? (is_string($tx->date) ? $tx->date : '') }}</td>
                  <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $tx->description }}</td>
                  <td class="px-4 py-3 text-sm text-right {{ ($tx->amount ?? 0) < 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">${{ number_format(abs($tx->amount ?? 0), 2) }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="3" class="px-4 py-10 text-center text-sm text-gray-500 dark:text-gray-400">No transactions yet.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div class="mt-6">
          <a href="{{ route('accounts.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 dark:text-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">Back to Accounts</a>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const ctx = document.getElementById('accountChart').getContext('2d');
    const chart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: @json($transactions->pluck('date')->map(fn($d) => optional($d)->format('Y-m-d'))),
        datasets: [{
          label: 'Amount',
          data: @json($transactions->pluck('amount')),
          borderColor: '#4f46e5',
          backgroundColor: 'rgba(99,102,241,0.3)',
          tension: 0.4
        }]
      },
      options: {
        plugins: { legend: { display: false } },
        scales: {
          y: { beginAtZero: true }
        }
      }
    });
  </script>
</x-app-layout>
