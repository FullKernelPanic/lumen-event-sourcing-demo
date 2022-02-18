<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Order;

class OrderCreated extends Event
{
    public $order;
    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}
