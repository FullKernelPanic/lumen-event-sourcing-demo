<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\EventStored;
use EventSauce\EventSourcing\MessageDispatcher;
use EventSauce\EventSourcing\Serialization\MessageSerializer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class EventStoredListener implements ShouldQueue
{
    public function __construct(
        private MessageSerializer $messageSerializer,
        private MessageDispatcher $messageDispatcher
    ) {
    }

    public function handle(EventStored $event)
    {
        $message = $this->messageSerializer->unserializePayload($event->data());

        $this->messageDispatcher->dispatch($message);

        Log::info('Event stored: ' . $message->aggregateRootType());
    }
}
