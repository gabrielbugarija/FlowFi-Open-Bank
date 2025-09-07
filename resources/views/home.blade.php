<x-marketing-layout>
  {{-- HERO SECTION --}}
  <section class="relative min-h-[60vh] flex items-center">
    {{-- Background gradient --}}
    <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 via-white to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-indigo-900"></div>
    
    <div class="relative max-w-7xl mx-auto px-6 py-16 md:py-24">
      <div class="max-w-4xl">
        <div class="backdrop-blur-sm bg-white/90 dark:bg-gray-900/80 rounded-2xl shadow-xl ring-1 ring-gray-200/50 dark:ring-gray-700/50 p-8 md:p-12">
          <h1 class="text-5xl md:text-6xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent leading-tight">
            Take control of your money
          </h1>
          <p class="mt-6 text-xl text-gray-700 dark:text-gray-300 leading-relaxed">
            Track accounts, monitor transactions, and stay ready for open banking in Canada. 
            Your complete financial overview in one secure place.
          </p>
          
          {{-- CTA Buttons --}}
          <div class="mt-10 flex flex-col sm:flex-row gap-4">
            @guest
              <a href="{{ route('login') }}"
                 class="group inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-white bg-gradient-to-r from-indigo-600 to-indigo-700 rounded-xl hover:from-indigo-700 hover:to-indigo-800 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                <span>Get Started</span>
                <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
              </a>
              
              @if (Route::has('register'))
                <a href="{{ route('register') }}"
                   class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-gray-900 dark:text-white bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 shadow-md hover:shadow-lg">
                  Create Account
                </a>
              @endif
            @else
              <a href="{{ route('accounts.index') }}"
                 class="group inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-white bg-gradient-to-r from-indigo-600 to-indigo-700 rounded-xl hover:from-indigo-700 hover:to-indigo-800 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                <span>View Accounts</span>
                <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
              </a>
              
              <a href="{{ route('transactions.index') }}"
                 class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-gray-900 dark:text-white bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 shadow-md hover:shadow-lg">
                View Transactions
              </a>
            @endguest
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- FEATURES SECTION --}}
  <section class="py-20 bg-white dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center mb-16">
        <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
          Everything you need to manage your finances
        </h2>
        <p class="text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
          Powerful features designed to give you complete visibility and control over your financial life.
        </p>
      </div>
      
      <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
        <article class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-700 p-8 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
          <div class="text-5xl mb-6 group-hover:scale-110 transition-transform duration-300">💳</div>
          <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
            Accounts at a glance
          </h3>
          <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
            Clean, intuitive dashboard with real-time balances and account types. 
            Negative balances are automatically highlighted for quick attention.
          </p>
          <div class="absolute top-4 right-4 w-2 h-2 bg-indigo-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        </article>

        <article class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-green-50 to-emerald-50 dark:from-gray-800 dark:to-gray-700 p-8 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
          <div class="text-5xl mb-6 group-hover:scale-110 transition-transform duration-300">📈</div>
          <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
            Smart transaction tracking
          </h3>
          <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
            Advanced filtering and search capabilities with instant transaction entry. 
            Visual debit/credit indicators help you understand cash flow at a glance.
          </p>
          <div class="absolute top-4 right-4 w-2 h-2 bg-green-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        </article>

        <article class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-50 to-pink-50 dark:from-gray-800 dark:to-gray-700 p-8 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 md:col-span-2 lg:col-span-1">
          <div class="text-5xl mb-6 group-hover:scale-110 transition-transform duration-300">🔐</div>
          <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
            Secure & modern
          </h3>
          <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
            Built on Laravel Breeze with enterprise-grade security. 
            Dark mode support and lightning-fast performance with Vite.
          </p>
          <div class="absolute top-4 right-4 w-2 h-2 bg-purple-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        </article>
      </div>
    </div>
  </section>

  {{-- STATS SECTION --}}
  <section class="py-20 bg-gray-50 dark:bg-gray-800">
    <div class="max-w-7xl mx-auto px-6">
      <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
        <div class="text-center">
          <div class="text-3xl md:text-4xl font-bold text-indigo-600 dark:text-indigo-400 mb-2">100%</div>
          <div class="text-sm md:text-base text-gray-600 dark:text-gray-400">Secure</div>
        </div>
        <div class="text-center">
          <div class="text-3xl md:text-4xl font-bold text-indigo-600 dark:text-indigo-400 mb-2">24/7</div>
          <div class="text-sm md:text-base text-gray-600 dark:text-gray-400">Access</div>
        </div>
        <div class="text-center">
          <div class="text-3xl md:text-4xl font-bold text-indigo-600 dark:text-indigo-400 mb-2">Real-time</div>
          <div class="text-sm md:text-base text-gray-600 dark:text-gray-400">Updates</div>
        </div>
        <div class="text-center">
          <div class="text-3xl md:text-4xl font-bold text-indigo-600 dark:text-indigo-400 mb-2">Open Banking</div>
          <div class="text-sm md:text-base text-gray-600 dark:text-gray-400">Ready</div>
        </div>
      </div>
    </div>
  </section>

  {{-- CTA SECTION --}}
  <section class="py-20">
    <div class="max-w-4xl mx-auto px-6 text-center">
      <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-purple-600 to-indigo-800 p-12 md:p-16 shadow-2xl">
        {{-- Background pattern --}}
        <div class="absolute inset-0 opacity-10">
          <svg class="w-full h-full" viewBox="0 0 100 100" fill="none">
            <defs>
              <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                <path d="M 10 0 L 0 0 0 10" fill="none" stroke="currentColor" stroke-width="0.5"/>
              </pattern>
            </defs>
            <rect width="100" height="100" fill="url(#grid)"/>
          </svg>
        </div>
        
        <div class="relative">
          <h3 class="text-3xl md:text-4xl font-bold text-white mb-4">
            Ready to take control?
          </h3>
          <p class="text-xl text-indigo-100 mb-10 max-w-2xl mx-auto">
            Join thousands who have transformed their financial management. 
            Start tracking, planning, and growing your wealth today.
          </p>
          
          @guest
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
              <a href="{{ route('register') }}" 
                 class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-indigo-700 bg-white rounded-xl hover:bg-gray-50 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                Start Free Today
              </a>
              <a href="{{ route('login') }}" 
                 class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-white border-2 border-white rounded-xl hover:bg-white hover:text-indigo-700 transition-all duration-200">
                Sign In
              </a>
            </div>
          @else
            <a href="{{ route('accounts.index') }}" 
               class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-indigo-700 bg-white rounded-xl hover:bg-gray-50 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
              Go to Dashboard
            </a>
          @endguest
        </div>
      </div>
    </div>
  </section>
</x-marketing-layout>
