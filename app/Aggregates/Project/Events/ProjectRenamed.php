<?php

declare(strict_types=1);

namespace App\Aggregates\Project\Events;

use App\Aggregates\Event;
use App\Aggregates\EventId;
use EventSauce\EventSourcing\Serialization\SerializablePayload;

class ProjectRenamed extends Event
{
    public function __construct(EventId $id, private string $name)
    {
        parent::__construct($id);
    }

    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return array<string, string>
     */
    public function toPayload(): array
    {
        return [
            'id' => $this->id()->toString(),
            'name' => $this->name,
        ];
    }

    /**
     * @param array<string, string> $payload
     *
     * @return SerializablePayload
     */
    public static function fromPayload(array $payload): SerializablePayload
    {
        return new static(EventId::fromString($payload['id']), $payload['name']);
    }
}
