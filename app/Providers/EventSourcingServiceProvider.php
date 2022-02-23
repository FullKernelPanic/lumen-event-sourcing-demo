<?php

declare(strict_types=1);

namespace App\Providers;

use App\Dispatcher\RabbitMQMessageDispatcher;
use App\Listeners\Consumer\ProjectConsumer;
use App\Listeners\EventStoredListener;
use App\Repositories\MySQLMessageRepository;
use App\Repositories\MySQLSnapshotRepository;
use App\Repositories\ProjectRepository;
use Domain\Project\Aggregate\ProjectAggregateRoot;
use Domain\Project\Contract\UniqueProjectNameGuardInterface;
use Domain\Project\ProjectAggregateRootRepository;
use EventSauce\Clock\Clock;
use EventSauce\Clock\SystemClock;
use EventSauce\EventSourcing\EventSourcedAggregateRootRepository;
use EventSauce\EventSourcing\Serialization\ConstructingMessageSerializer;
use EventSauce\EventSourcing\Serialization\MessageSerializer;
use EventSauce\EventSourcing\Snapshotting\ConstructingAggregateRootRepositoryWithSnapshotting;
use EventSauce\EventSourcing\SynchronousMessageDispatcher;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;

class EventSourcingServiceProvider extends ServiceProvider
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
                $messageRepository = new MySQLMessageRepository($this->app->get(MessageSerializer::class));

                return new ProjectAggregateRootRepository(
                    new ConstructingAggregateRootRepositoryWithSnapshotting(
                        ProjectAggregateRoot::class,
                        $messageRepository,
                        $this->app->get(MySQLSnapshotRepository::class),
                        new EventSourcedAggregateRootRepository(
                            ProjectAggregateRoot::class,
                            $messageRepository,
                            $this->app->get('event_sourcing.message_dispatcher.queue')
                        )
                    )
                );
            }
        );

        $this->app->bind(UniqueProjectNameGuardInterface::class, function (): UniqueProjectNameGuardInterface {
            return new ProjectRepository();
        });

        $this->app->bind(Clock::class, function (): Clock {
            return new SystemClock();
        });
    }
}
