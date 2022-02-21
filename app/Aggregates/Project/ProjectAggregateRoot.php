<?php

declare(strict_types=1);

namespace App\Aggregates\Project;

use App\Aggregates\EventId;
use App\Aggregates\Project\Events\ProjectCreated;
use App\Aggregates\Project\Events\ProjectDeleted;
use App\Aggregates\Project\Events\ProjectRenamed;
use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviourWithRequiredHistory;
use Exception;

final class ProjectAggregateRoot implements AggregateRoot
{
    use AggregateRootBehaviourWithRequiredHistory {
        AggregateRootBehaviourWithRequiredHistory::__construct as private __aggregateConstruct;
    }

    private string $name;
    private bool $isDeleted = false;

    public function __construct(ProjectId $projectId)
    {
        $this->__aggregateConstruct($projectId);
    }

    public function create(string $name): void
    {
        $this->recordThat(new ProjectCreated(new EventId(), $name));
    }

    /**
     * @throws Exception
     */
    public function delete(): void
    {
        $this->assureNotDeleted();

        $this->recordThat(new ProjectDeleted(new EventId()));
    }

    /**
     * @throws Exception
     */
    public function rename(string $name): void
    {
        $this->assureNotDeleted();

        $this->recordThat(new ProjectRenamed(new EventId(), $name));
    }

    protected function applyProjectCreated(ProjectCreated $event): void
    {
        $this->name = $event->name();
    }

    protected function applyProjectDeleted(ProjectDeleted $event): void
    {
        $this->isDeleted = true;
    }

    protected function applyProjectRenamed(ProjectRenamed $event): void
    {
        $this->name = $event->name();
    }

    /**
     * @throws Exception
     */
    private function assureNotDeleted(): void
    {
        if ($this->isDeleted) {
            throw new Exception('Project cannot be modified since it is deleted.');
        }
    }
}
