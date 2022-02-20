<?php

declare(strict_types=1);

namespace App\Events;

class EventStored extends Event
{
    public function __construct(private array $data)
    {
    }

    public function data(): array
    {
        return $this->data;
    }
}
