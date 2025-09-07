<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      Accounts
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($accounts as $acc)
          <a href="{{ route('accounts.show', $acc) }}" class="p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm flex flex-col justify-between transition transform hover:shadow-lg hover:-translate-y-1">
            <div>
              <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $acc->name }}</h3>
              <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ ucfirst(str_replace('_', ' ', $acc->type)) }}</p>
              <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">{{ $acc->transactions_count }} transactions</p>
            </div>
            <div class="mt-4 text-right text-xl font-bold {{ $acc->balance < 0 ? 'text-red-600 dark:text-red-400' : 'text-indigo-600 dark:text-indigo-400' }}">
              ${{ number_format($acc->balance, 2) }}
            </div>
          </a>
        @endforeach
      </div>
      <div class="mt-8 flex gap-3">
        <a href="{{ route('accounts.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">Add Account</a>
        <a href="{{ route('transactions.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-gray-700 bg-gray-100 border border-transparent hover:bg-gray-200 dark:text-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">View Transactions</a>
        {{-- add more buttons here --}}
      </div>
    </div>
  </div>
</x-app-layout>
