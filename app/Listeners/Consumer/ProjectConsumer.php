<?php

declare(strict_types=1);

namespace App\Listeners\Consumer;

use App\Aggregates\Project\Events\ProjectCreated;
use App\Aggregates\Project\Events\ProjectDeleted;
use App\Aggregates\Project\Events\ProjectRenamed;
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

    public function handleProjectDeleted(ProjectDeleted $event, Message $message): void
    {
        Project::findByUuid($message->aggregateRootId()->toString())->delete();
    }

    public function handleProjectRenamed(ProjectRenamed $event, Message $message): void
    {
        Project::findByUuid($message->aggregateRootId()->toString())->update(['name' => $event->name()]);
    }
}
