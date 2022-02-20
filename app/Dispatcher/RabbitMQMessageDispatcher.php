<?php

declare(strict_types=1);

namespace App\Dispatcher;

use App\Events\EventStored;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageDispatcher;
use EventSauce\EventSourcing\Serialization\MessageSerializer;
use Illuminate\Contracts\Events\Dispatcher;

class RabbitMQMessageDispatcher implements MessageDispatcher
{
    public function __construct(private Dispatcher $dispatcher, private MessageSerializer $messageSerializer)
    {
    }

    public function dispatch(Message ...$messages): void
    {
        foreach ($messages as $message) {
            $this->dispatcher->dispatch(new EventStored($this->messageSerializer->serializeMessage($message)));
        }
    }
}
