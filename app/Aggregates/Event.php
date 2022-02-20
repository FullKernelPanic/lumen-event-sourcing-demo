<?php

declare(strict_types=1);

namespace App\Aggregates;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

abstract class Event implements SerializablePayload
{
    public function __construct(private EventId $id)
    {
    }

    public function id(): EventId
    {
        return $this->id;
    }

    abstract public function toPayload(): array;

    abstract static function fromPayload(array $payload): SerializablePayload;
}
