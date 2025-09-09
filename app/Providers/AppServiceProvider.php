<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Cache;
use App\Events\TransactionsChanged;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(TransactionsChanged::class, function (TransactionsChanged $event) {
            Cache::forget("dashboard:monthlyTotals:{$event->userId}");
            Cache::forget("dashboard:categoryTotals:{$event->userId}");
        });
    }
}
