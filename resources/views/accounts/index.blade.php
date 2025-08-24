<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      Accounts
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
                  <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Name</th>
                  <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Type</th>
                  <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700 dark:text-gray-200">Balance</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($accounts as $acc)
                  <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/30">
                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $acc->name }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $acc->type }}</td>
                    <td class="px-4 py-3 text-sm text-right {{ $acc->balance < 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-gray-100' }}">
                      ${{ number_format($acc->balance, 2) }}
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          {{-- actions --}}
          <div class="mt-6 flex gap-3">
            <a href="{{ route('transactions.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium border rounded-md hover:bg-gray-50 dark:hover:bg-gray-900">
              View Transactions
            </a>
            {{-- add more buttons here --}}
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
