<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      Transactions
    </h2>
  </x-slot>

  <div class="py-6" x-data="transactionsPage()">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
        <div class="p-6 space-y-6">
            <div id="transactionsChartWrapper" x-data="{ loading: true }" class="relative">
              <div x-show="loading" class="absolute inset-0 flex items-center justify-center bg-white/70">
                <div class="loader"></div>
              </div>
              <canvas id="transactionsChart" class="w-full h-64"></canvas>
            </div>

          <!-- Bulk action toolbar -->
          <div x-show="selected.length" class="flex items-center gap-3 p-3 rounded-md bg-gray-100 dark:bg-gray-700">
            <span x-text="selected.length + ' selected'"></span>
            <button @click="bulkDelete" class="px-2 py-1 text-sm text-red-600">Delete</button>
            <input type="text" x-model="bulkType" placeholder="New type" class="px-2 py-1 text-sm rounded" />
            <button @click="bulkCategorize" class="px-2 py-1 text-sm text-indigo-600">Re-categorize</button>
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
                        <button @click="editing = true" class="text-indigo-600">Edit</button>
                      </template>
                      <template x-if="editing">
                        <button @click="save" class="text-green-600">Save</button>
                        <button @click="editing = false" class="text-gray-600">Cancel</button>
                      </template>
                      <button @click="remove" class="text-red-600">Delete</button>
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
      labels: @json($transactions->pluck('date')->map(fn($d) => optional($d)->format('Y-m-d'))),
      datasets: [{
        label: 'Amount',
        data: @json($transactions->pluck('amount')),
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
  document.getElementById('transactionsChartWrapper').__x.$data.loading = false;
  </script>
  <script>
    document.addEventListener('alpine:init', () => {
      Alpine.data('transactionsPage', () => ({
        selected: [],
        bulkType: '',
        toggleAll(checked) {
          this.selected = checked
            ? Array.from(document.querySelectorAll('.row-checkbox')).map(cb => cb.value)
            : [];
        },
        bulkDelete() {
          Promise.all(this.selected.map(id =>
            fetch(`/transactions/${id}`, {
              method: 'DELETE',
              headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
          )).then(() => window.location.reload());
        },
        bulkCategorize() {
          Promise.all(this.selected.map(id =>
            fetch(`/transactions/${id}`, {
              method: 'PUT',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
              },
              body: JSON.stringify({ type: this.bulkType })
            })
          )).then(() => window.location.reload());
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
        touchStart(e) { this.startX = e.touches[0].clientX; },
        touchEnd(e) {
          const diff = e.changedTouches[0].clientX - this.startX;
          if (diff < -40) this.swiped = true;
          if (diff > 40) this.swiped = false;
        },
        save() {
          fetch(`/transactions/${this.id}`, {
            method: 'PUT',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(this.form)
          }).then(() => { this.editing = false; this.swiped = false; });
        },
        remove() {
          fetch(`/transactions/${this.id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
          }).then(() => { this.$el.remove(); });
        }
      }));
    });
  </script>
</x-app-layout>
