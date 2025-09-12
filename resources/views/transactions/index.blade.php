<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      Transactions
    </h2>
  </x-slot>

  <div class="py-6" x-data="transactionsPage()">
    <div
      x-show="$store.toast.show"
      x-transition
      class="fixed top-4 right-4 px-4 py-2 rounded text-white shadow"
      :class="$store.toast.type === 'error' ? 'bg-red-600' : 'bg-green-600'"
      x-text="$store.toast.message"></div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
        <div class="p-6 space-y-6">
          <div>
            <canvas id="transactionsChart" class="w-full h-64"></canvas>
          </div>

          <form method="GET" action="{{ route('transactions.index') }}" class="flex flex-wrap items-end gap-2">
            <div>
              <label for="start_date" class="sr-only">Start Date</label>
              <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="rounded-md border-gray-300" />
            </div>
            <div>
              <label for="end_date" class="sr-only">End Date</label>
              <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="rounded-md border-gray-300" />
            </div>
            <div class="flex-1">
              <label for="search" class="sr-only">Search</label>
              <input type="text" name="search" id="search" placeholder="Search description" value="{{ request('search') }}" class="w-full rounded-md border-gray-300" />
            </div>
            <div>
              <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md">Filter</button>
            </div>
          </form>

          <!-- Bulk action toolbar -->
          <div x-show="selected.length" class="flex items-center gap-3 p-3 rounded-md bg-gray-100 dark:bg-gray-700">
            <span x-text="selected.length + ' selected'"></span>
            <button @click="bulkDelete" :disabled="processing" class="px-2 py-1 text-sm text-red-600" :class="{ 'opacity-50 cursor-not-allowed': processing }">Delete</button>
            <input type="text" x-model="bulkType" placeholder="New type" class="px-2 py-1 text-sm rounded" />
            <button @click="bulkCategorize" :disabled="processing" class="px-2 py-1 text-sm text-indigo-600" :class="{ 'opacity-50 cursor-not-allowed': processing }">Re-categorize</button>
          </div>

          <div class="overflow-x-auto">
            <table class="min-w-full table-auto divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gray-50 dark:bg-gray-900/40">
                <tr>
                  <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                    <input type="checkbox" @change="toggleAll($event.target.checked)" />
                  </th>
                  <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Date</th>
                  <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Description</th>
                  <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Account</th>
                  <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700 dark:text-gray-200">Amount</th>
                  <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700 dark:text-gray-200">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($transactions as $tx)
                  <tr
                    x-data="transactionRow({
                        id: {{ $tx->id }},
                        date: @js(optional($tx->date)->format('Y-m-d') ?? ''),
                        description: @js($tx->description),
                        account: @js($tx->account->name ?? $tx->account_name ?? '—'),
                        amount: {{ $tx->amount ?? 0 }},
                        type: @js($tx->type ?? ''),
                        account_id: {{ $tx->account_id ?? 'null' }}
                    })"
                    @touchstart="touchStart($event)"
                    @touchend="touchEnd($event)"
                    class="odd:bg-white even:bg-gray-50 hover:bg-gray-100 dark:odd:bg-gray-800 dark:even:bg-gray-900/30 dark:hover:bg-gray-900/50">
                    <td class="px-4 py-3 text-sm">
                      <input type="checkbox" class="row-checkbox" :value="id" x-model="$root.selected" />
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                      <template x-if="!editing"><span x-text="form.date"></span></template>
                      <template x-if="editing"><input type="date" x-model="form.date" class="w-full" /></template>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                      <template x-if="!editing"><span x-text="form.description"></span></template>
                      <template x-if="editing"><input type="text" x-model="form.description" class="w-full" /></template>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                      <span x-text="account"></span>
                    </td>
                    <td class="px-4 py-3 text-sm text-right" :class="form.amount < 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400'">
                      <template x-if="!editing">
                        <span>$<span x-text="Math.abs(form.amount).toFixed(2)"></span></span>
                      </template>
                      <template x-if="editing">
                        <input type="number" step="0.01" x-model="form.amount" class="w-full text-right" />
                      </template>
                    </td>
                    <td class="px-4 py-3 text-sm text-right space-x-2" x-show="swiped || editing">
                      <template x-if="!editing">
                        <button @click="editing = true" class="text-indigo-600" :disabled="processing" :class="{ 'opacity-50 cursor-not-allowed': processing }">Edit</button>
                      </template>
                      <template x-if="editing">
                        <button @click="save" class="text-green-600" :disabled="processing" :class="{ 'opacity-50 cursor-not-allowed': processing }">Save</button>
                        <button @click="editing = false" class="text-gray-600" :disabled="processing" :class="{ 'opacity-50 cursor-not-allowed': processing }">Cancel</button>
                      </template>
                      <button @click="remove" class="text-red-600" :disabled="processing" :class="{ 'opacity-50 cursor-not-allowed': processing }">Delete</button>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="px-4 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                      No transactions yet.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

        <div class="mt-4">
          {{ $transactions->links() }}
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
        labels: @json($transactions->getCollection()->pluck('date')->map(fn($d) => optional($d)->format('Y-m-d'))),
        datasets: [{
          label: 'Amount',
          data: @json($transactions->getCollection()->pluck('amount')),
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
  <script>
    document.addEventListener('alpine:init', () => {
      Alpine.store('toast', {
        show: false,
        message: '',
        type: 'success',
        showToast(message, type = 'success') {
          this.message = message;
          this.type = type;
          this.show = true;
          setTimeout(() => (this.show = false), 3000);
        }
      });

      Alpine.data('transactionsPage', () => ({
        selected: [],
        bulkType: '',
        processing: false,
        toggleAll(checked) {
          this.selected = checked
            ? Array.from(document.querySelectorAll('.row-checkbox')).map(cb => cb.value)
            : [];
        },
        bulkDelete() {
          this.processing = true;
          Promise.all(this.selected.map(id =>
            fetch(`/transactions/${id}`, {
              method: 'DELETE',
              headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            }).then(res => {
              if (!res.ok) throw new Error();
              document.querySelector(`.row-checkbox[value="${id}"]`)?.closest('tr')?.remove();
            })
          ))
          .then(() => {
            Alpine.store('toast').showToast('Transactions deleted');
            this.selected = [];
          })
          .catch(() => Alpine.store('toast').showToast('Failed to delete', 'error'))
          .finally(() => { this.processing = false; });
        },
        bulkCategorize() {
          this.processing = true;
          Promise.all(this.selected.map(id =>
            fetch(`/transactions/${id}`, {
              method: 'PUT',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
              },
              body: JSON.stringify({ type: this.bulkType })
            }).then(res => {
              if (!res.ok) throw new Error();
              const row = document.querySelector(`.row-checkbox[value="${id}"]`)?.closest('tr');
              if (row) row.__x.$data.form.type = this.bulkType;
            })
          ))
          .then(() => {
            Alpine.store('toast').showToast('Transactions updated');
            this.selected = [];
          })
          .catch(() => Alpine.store('toast').showToast('Failed to update', 'error'))
          .finally(() => { this.processing = false; });
        }
      }));

      Alpine.data('transactionRow', (tx) => ({
        id: tx.id,
        account: tx.account,
        form: {
          date: tx.date,
          description: tx.description,
          amount: tx.amount,
          type: tx.type,
          account_id: tx.account_id,
        },
        editing: false,
        swiped: false,
        startX: 0,
        processing: false,
        touchStart(e) { this.startX = e.touches[0].clientX; },
        touchEnd(e) {
          const diff = e.changedTouches[0].clientX - this.startX;
          if (diff < -40) this.swiped = true;
          if (diff > 40) this.swiped = false;
        },
        save() {
          this.processing = true;
          fetch(`/transactions/${this.id}`, {
            method: 'PUT',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(this.form)
          }).then(res => {
            if (!res.ok) throw new Error();
            this.editing = false;
            this.swiped = false;
            Alpine.store('toast').showToast('Transaction updated');
          }).catch(() => Alpine.store('toast').showToast('Failed to update', 'error'))
            .finally(() => { this.processing = false; });
        },
        remove() {
          this.processing = true;
          fetch(`/transactions/${this.id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
          }).then(res => {
            if (!res.ok) throw new Error();
            this.$el.remove();
            Alpine.store('toast').showToast('Transaction deleted');
          }).catch(() => Alpine.store('toast').showToast('Failed to delete', 'error'))
            .finally(() => { this.processing = false; });
        }
      }));
    });
  </script>
</x-app-layout>
