<?php

declare(strict_types=1);

namespace App\Providers;

use App\Aggregates\Project\ProjectAggregateRoot;
use App\Dispatcher\RabbitMQMessageDispatcher;
use App\Listeners\Consumer\ProjectConsumer;
use App\Listeners\EventStoredListener;
use App\Repositories\MessageRepository;
use App\Repositories\ProjectAggregateRootRepository;
use EventSauce\EventSourcing\EventSourcedAggregateRootRepository;
use EventSauce\EventSourcing\Serialization\ConstructingMessageSerializer;
use EventSauce\EventSourcing\Serialization\MessageSerializer;
use EventSauce\EventSourcing\SynchronousMessageDispatcher;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;

class EventSauceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(MessageSerializer::class, function (): ConstructingMessageSerializer {
            return new ConstructingMessageSerializer();
        });

        $this->app->bind('event_sourcing.message_dispatcher.queue', function () {
            return new RabbitMQMessageDispatcher(
                $this->app->get(Dispatcher::class),
                $this->app->get(MessageSerializer::class)
            );
        });

        $this->app->bind('event_sourcing.message_dispatcher.sync', function () {
            return new SynchronousMessageDispatcher(
                new ProjectConsumer()
            );
        });

        $this->app->bind(EventStoredListener::class, function () {
            return new EventStoredListener(
                $this->app->get(MessageSerializer::class),
                $this->app->get('event_sourcing.message_dispatcher.sync')
            );
        });

        $this->app->bind(
            ProjectAggregateRootRepository::class,
            function (): ProjectAggregateRootRepository {
                return new ProjectAggregateRootRepository(
                    new EventSourcedAggregateRootRepository(
                        ProjectAggregateRoot::class,
                        new MessageRepository($this->app->get(MessageSerializer::class)),
                        $this->app->get('event_sourcing.message_dispatcher.queue')
                    )
                );
            }
        );
    }
}
