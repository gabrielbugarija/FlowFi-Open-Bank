<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      Accounts
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($accounts as $acc)
          <div class="p-6 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg flex flex-col justify-between">
            <div>
              <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $acc->name }}</h3>
              <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ ucfirst(str_replace('_', ' ', $acc->type)) }}</p>
            </div>
            <div class="mt-4 text-right text-xl font-bold {{ $acc->balance < 0 ? 'text-red-600 dark:text-red-400' : 'text-indigo-600 dark:text-indigo-400' }}">
              ${{ number_format($acc->balance, 2) }}
            </div>
          </div>
        @endforeach
      </div>
      <div class="mt-8 flex gap-3">
        <a href="{{ route('accounts.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium border rounded-md hover:bg-gray-50 dark:hover:bg-gray-900">Add Account</a>
        <a href="{{ route('transactions.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium border rounded-md hover:bg-gray-50 dark:hover:bg-gray-900">View Transactions</a>
        {{-- add more buttons here --}}
      </div>
    </div>
  </div>
</x-app-layout>
