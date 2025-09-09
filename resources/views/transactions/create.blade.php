<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      Create Transaction
    </h2>
  </x-slot>
  <div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      @php($types = ['income', 'expense', 'transfer'])
      <form method="POST" action="{{ route('transactions.store') }}" class="space-y-4">
        @csrf
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Account</label>
          <select name="account_id" class="mt-1 block w-full">
            @foreach($accounts as $account)
              <option value="{{ $account->id }}">{{ $account->name }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
          <select name="type" class="mt-1 block w-full">
            @foreach($types as $type)
              <option value="{{ $type }}" @selected(old('type') === $type)>{{ ucfirst($type) }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
          <input type="text" name="description" class="mt-1 block w-full" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount</label>
          <input type="number" step="0.01" name="amount" class="mt-1 block w-full" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date</label>
          <input type="date" name="date" class="mt-1 block w-full" />
        </div>
        <div>
          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
        </div>
      </form>
    </div>
  </div>
</x-app-layout>
