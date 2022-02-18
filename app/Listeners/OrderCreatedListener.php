<?php

declare(strict_types=1);

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class OrderCreatedListener implements ShouldQueue
{
    public function __construct()
    {
    }

    public function handle($event)
    {
        Log::info('Order created on estore:' . json_encode($event));
    }
}
