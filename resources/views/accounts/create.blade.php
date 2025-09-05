<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      Create Account
    </h2>
  </x-slot>
  <div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
        <form method="POST" action="{{ route('accounts.store') }}" class="space-y-6">
          @csrf
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
            <input type="text" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
            <select name="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
              <option value="checking">Checking</option>
              <option value="savings">Savings</option>
              <option value="credit">Credit Card</option>
              <option value="cash">Cash</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Balance</label>
            <input type="number" step="0.01" name="balance" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
          </div>
          <div class="flex justify-end">
            <x-primary-button>Save</x-primary-button>
          </div>
        </form>
      </div>
    </div>
  </div>
</x-app-layout>
