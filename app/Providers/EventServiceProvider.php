<?php

namespace App\Providers;

use App\Events\TransactionsChanged;
use App\Listeners\RecalculateDashboardAggregates;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     */
    protected $listen = [
        TransactionsChanged::class => [
            RecalculateDashboardAggregates::class,
        ],
    ];
}
