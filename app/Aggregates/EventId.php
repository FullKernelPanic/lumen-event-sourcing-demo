<?php

declare(strict_types=1);

namespace App\Aggregates;

use EventSauce\EventSourcing\AggregateRootId;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class EventId implements AggregateRootId
{
    private UuidInterface $id;

    public function __construct()
    {
        $this->id = Uuid::uuid1();
    }

    public function toString(): string
    {
        return $this->id->toString();
    }

    public static function fromString(string $aggregateRootId): self
    {
        $id = new self();
        $id->id = Uuid::fromString($aggregateRootId);

        return $id;
    }

    public function toBinary(): string
    {
        return $this->id->getBytes();
    }
}
