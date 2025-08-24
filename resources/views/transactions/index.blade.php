<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      Transactions
    </h2>
  </x-slot>

  <div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
        <div class="p-6">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
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
                  <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/30">
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
            <a href="{{ route('accounts.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium border rounded-md hover:bg-gray-50 dark:hover:bg-gray-900">
              Back to Accounts
            </a>
            @if (Route::has('transactions.create'))
              <a href="{{ route('transactions.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium border rounded-md hover:bg-gray-50 dark:hover:bg-gray-900">
                Add Transaction
              </a>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>