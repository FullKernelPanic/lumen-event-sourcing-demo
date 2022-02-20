<?php

namespace App\Providers;

use App\Events\EventStored;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        EventStored::class => [
            \App\Listeners\EventStoredListener::class,
        ],
    ];
}
