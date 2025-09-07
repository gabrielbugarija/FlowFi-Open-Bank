<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      Transactions
    </h2>
  </x-slot>

  <div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
        <div class="p-6 space-y-6">
          <div>
            <canvas id="transactionsChart" class="w-full h-64"></canvas>
          </div>
          <div class="overflow-x-auto">
            <table class="min-w-full table-auto divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gray-50 dark:bg-gray-900/40">
                <tr>
                  <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Date</th>
                  <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Description</th>
                  <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Account</th>
                  <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700 dark:text-gray-200">Amount</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($transactions as $tx)
                  <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-100 dark:odd:bg-gray-800 dark:even:bg-gray-900/30 dark:hover:bg-gray-900/50">
                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                      {{ optional($tx->date)->format('Y-m-d') ?? (is_string($tx->date) ? $tx->date : '') }}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                      {{ $tx->description }}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                      {{ $tx->account->name ?? $tx->account_name ?? '—' }}
                    </td>
                    <td class="px-4 py-3 text-sm text-right {{ ($tx->amount ?? 0) < 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                      ${{ number_format(abs($tx->amount ?? 0), 2) }}
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" class="px-4 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                      No transactions yet.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

      <div class="mt-6 flex gap-3">
        <a href="{{ route('accounts.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-gray-700 bg-gray-100 border border-transparent hover:bg-gray-200 dark:text-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
          Back to Accounts
        </a>
            @if (Route::has('transactions.create'))
              <a href="{{ route('transactions.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                Add Transaction
              </a>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const txCtx = document.getElementById('transactionsChart').getContext('2d');
    const txChart = new Chart(txCtx, {
      type: 'bar',
      data: {
        labels: @json($transactions->pluck('date')->map(fn($d) => optional($d)->format('Y-m-d'))),
        datasets: [{
          label: 'Amount',
          data: @json($transactions->pluck('amount')),
          backgroundColor: '#4f46e5'
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
