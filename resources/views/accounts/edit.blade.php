<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      Edit Account
    </h2>
  </x-slot>
  <div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
        <form method="POST" action="{{ route('accounts.update', $account) }}" class="space-y-6">
          @csrf
          @method('PUT')
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
            <input type="text" name="name" value="{{ old('name', $account->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
            <select name="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
              <option value="checking" {{ old('type', $account->type) === 'checking' ? 'selected' : '' }}>Checking</option>
              <option value="savings" {{ old('type', $account->type) === 'savings' ? 'selected' : '' }}>Savings</option>
              <option value="credit" {{ old('type', $account->type) === 'credit' ? 'selected' : '' }}>Credit Card</option>
              <option value="cash" {{ old('type', $account->type) === 'cash' ? 'selected' : '' }}>Cash</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Balance</label>
            <input type="number" step="0.01" name="balance" value="{{ old('balance', $account->balance) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
          </div>
          <div class="flex justify-end">
            <x-primary-button>Update</x-primary-button>
          </div>
        </form>
      </div>
    </div>
  </div>
</x-app-layout>
