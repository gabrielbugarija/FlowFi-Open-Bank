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
          <x-input-label for="account_id" :value="__('Account')" />
          <select id="account_id" name="account_id" class="mt-1 block w-full" required>
            @foreach($accounts as $account)
              <option value="{{ $account->id }}" @selected(old('account_id') == $account->id)>{{ $account->name }}</option>
            @endforeach
          </select>
          <x-input-error :messages="$errors->get('account_id')" class="mt-2" />
        </div>
        <div>
          <x-input-label for="type" :value="__('Type')" />
          <select id="type" name="type" class="mt-1 block w-full" required>
            @foreach($types as $type)
              <option value="{{ $type }}" @selected(old('type') === $type)>{{ ucfirst($type) }}</option>
            @endforeach
          </select>
          <x-input-error :messages="$errors->get('type')" class="mt-2" />
        </div>
        <div>
          <x-input-label for="description" :value="__('Description')" />
          <x-text-input id="description" name="description" type="text" class="mt-1 block w-full" :value="old('description')" />
          <x-input-error :messages="$errors->get('description')" class="mt-2" />
        </div>
        <div>
          <x-input-label for="amount" :value="__('Amount')" />
          <x-text-input id="amount" name="amount" type="number" step="0.01" class="mt-1 block w-full" :value="old('amount')" required />
          <x-input-error :messages="$errors->get('amount')" class="mt-2" />
        </div>
        <div>
          <x-input-label for="date" :value="__('Date')" />
          <x-text-input id="date" name="date" type="date" class="mt-1 block w-full" :value="old('date')" required />
          <x-input-error :messages="$errors->get('date')" class="mt-2" />
        </div>
        <div>
          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
        </div>
      </form>
    </div>
  </div>
</x-app-layout>
