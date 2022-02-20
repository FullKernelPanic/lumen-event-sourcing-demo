<?php

declare(strict_types=1);

namespace App\Listeners\Consumer;

use App\Aggregates\Project\Events\ProjectCreated;
use App\Models\Project;
use EventSauce\EventSourcing\EventConsumer;
use EventSauce\EventSourcing\Message;

class ProjectConsumer extends EventConsumer
{
    public function handleProjectCreated(ProjectCreated $event, Message $message): void
    {
        (new Project([
            'uuid' => $message->aggregateRootId()->toBinary(),
            'name' => $event->name(),
        ]))->save();
    }
}
