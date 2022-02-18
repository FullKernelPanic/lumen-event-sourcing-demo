<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Events\OrderCreated;
use App\Models\Order;
use Illuminate\Console\Command;

class CreateOrder extends Command
{
    protected $signature = 'order:create';

    protected $description = 'Create an order';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        /** @var Order $order */
        $order = Order::with('user')->inRandomOrder()->first();

        event(new OrderCreated($order));

        return 0;
    }
}
