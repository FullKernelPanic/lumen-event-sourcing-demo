<?php

declare(strict_types=1);

namespace App\Aggregates\Project;

use App\Aggregates\Project\Events\ProjectCreated;
use App\Aggregates\EventId;
use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviour;

final class ProjectRoot implements AggregateRoot
{
    use AggregateRootBehaviour;

    private string $name;

    public static function createProject(string $name): self
    {
        $projectRoot = new self(new ProjectId());

        $projectRoot->recordThat(new ProjectCreated(new EventId(), $name));

        return $projectRoot;
    }

    public function applyProjectCreated(ProjectCreated $projectCreated): void
    {
        $this->name = $projectCreated->name();
    }
}
