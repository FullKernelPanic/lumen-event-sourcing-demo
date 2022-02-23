<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Snapshot as SnapshotModel;
use EventSauce\Clock\Clock;
use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Snapshotting\Snapshot;
use EventSauce\EventSourcing\Snapshotting\SnapshotRepository;

class MySQLSnapshotRepository implements SnapshotRepository
{
    public function __construct(private Clock $clock)
    {
    }

    public function persist(Snapshot $snapshot): void
    {
        (new SnapshotModel([
            'aggregate_root_id' => $snapshot->aggregateRootId()->toBinary(),
            'version' => $snapshot->aggregateRootVersion(),
            'state' => json_encode($snapshot->state()),
            'recorded_at' => $this->clock->now()->format('Y-m-d H:i:s.u'),
        ]))->save();
    }

    public function retrieve(AggregateRootId $id): ?Snapshot
    {
        $snapshots = SnapshotModel::findByUuid($id->toString(), 'aggregate_root_id')->get();

        if ($snapshots->isEmpty()) {
            return null;
        }

        $snapshot = $snapshots->last();

        return new Snapshot($id, $snapshot->version, json_decode($snapshot->state, true));
    }
}
