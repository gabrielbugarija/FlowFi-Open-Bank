<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      Edit Transaction
    </h2>
  </x-slot>
  <div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      @php($types = ['income', 'expense', 'transfer'])
      <form method="POST" action="{{ route('transactions.update', $transaction) }}" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Account</label>
          <select name="account_id" class="mt-1 block w-full">
            @foreach($accounts as $account)
              <option value="{{ $account->id }}" @selected($account->id == old('account_id', $transaction->account_id))>{{ $account->name }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
          <select name="type" class="mt-1 block w-full">
            @foreach($types as $type)
              <option value="{{ $type }}" @selected(old('type', $transaction->type) === $type)>{{ ucfirst($type) }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
          <input type="text" name="description" value="{{ old('description', $transaction->description) }}" class="mt-1 block w-full" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount</label>
          <input type="number" step="0.01" name="amount" value="{{ old('amount', $transaction->amount) }}" class="mt-1 block w-full" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date</label>
          <input type="date" name="date" value="{{ old('date', $transaction->date) }}" class="mt-1 block w-full" />
        </div>
        <div>
          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Update</button>
        </div>
      </form>
    </div>
  </div>
</x-app-layout>
