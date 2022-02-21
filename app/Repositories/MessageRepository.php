<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Aggregates\Event;
use App\Models\EventStore;
use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\Serialization\MessageSerializer;
use Generator;

class MessageRepository implements \EventSauce\EventSourcing\MessageRepository
{
    public function __construct(private MessageSerializer $messageSerializer)
    {
    }

    public function persist(Message ...$messages): void
    {
        foreach ($messages as $message) {
            /** @var Event $event */
            $event = $message->event();

            (new EventStore([
                'event_id' => $event->id()->toBinary(),
                'aggregate_root_id' => $message->aggregateRootId()->toBinary(),
                'version' => $message->aggregateVersion(),
                'payload' => json_encode($this->messageSerializer->serializeMessage($message)),
                'recorded_at' => $message->timeOfRecording(),
            ]))->save();
        }
    }

    public function retrieveAll(AggregateRootId $id): Generator
    {
        $records = EventStore::on('event_store')
            ->where(['aggregate_root_id' => $id->toBinary()])
            ->get();

        foreach ($records as $record) {
            $message = $this->messageSerializer->unserializePayload(json_decode($record->payload, true));

            yield $message;
        }

        return $message ? $message->aggregateVersion() : 0;
    }

    public function retrieveAllAfterVersion(AggregateRootId $id, int $aggregateRootVersion): Generator
    {
        $records = EventStore::where([
                'aggregate_root_id' => $id->toString(),
                ['version', '>=', $aggregateRootVersion],
            ]
        )->get();

        foreach ($records as $record) {
            $message = $this->messageSerializer->unserializePayload($record->payload);

            yield $message;
        }

        return $message ? $message->aggregateVersion() : 0;
    }
}
