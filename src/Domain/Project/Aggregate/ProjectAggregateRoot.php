<?php

declare(strict_types=1);

namespace Domain\Project\Aggregate;

use EventSourcing\EventId;
use Domain\Project\Aggregate\Events\ProjectCreated;
use Domain\Project\Aggregate\Events\ProjectDeleted;
use Domain\Project\Aggregate\Events\ProjectRenamed;
use Domain\Project\Exception\ProjectNameNotUnique;
use Domain\Project\ProjectId;
use EventSauce\EventSourcing\AggregateRootBehaviourWithRequiredHistory;
use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Snapshotting\AggregateRootWithSnapshotting;
use EventSauce\EventSourcing\Snapshotting\SnapshottingBehaviour;
use Exception;

final class ProjectAggregateRoot implements AggregateRootWithSnapshotting
{
    use AggregateRootBehaviourWithRequiredHistory {
        AggregateRootBehaviourWithRequiredHistory::__construct as private __aggregateConstruct;
    }
    use SnapshottingBehaviour;

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
            throw new ProjectNameNotUnique('Project cannot be modified since it is deleted.');
        }
    }

    /**
     * @return array<string, mixed>
     */
    protected function createSnapshotState(): array
    {
        return [
            'name' => $this->name,
            'isDeleted' => $this->isDeleted,
        ];
    }

    protected static function reconstituteFromSnapshotState(AggregateRootId $id, $state): AggregateRootWithSnapshotting
    {
        /** @var ProjectId $id */
        $process = new self($id);

        $process->name = $state['name'];
        $process->isDeleted = $state['isDeleted'];

        return $process;
    }
}
