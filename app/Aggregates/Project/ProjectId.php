<?php

declare(strict_types=1);

namespace App\Aggregates\Project;

use EventSauce\EventSourcing\AggregateRootId;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class ProjectId implements AggregateRootId
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

    public function __toString()
    {
        return $this->toString();
    }

    public static function fromString(string $aggregateRootId): AggregateRootId
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
