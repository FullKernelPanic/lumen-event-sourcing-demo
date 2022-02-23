<?php

declare(strict_types=1);


namespace App\Console\Commands;

use Domain\Project\Command\Snapshot;
use Domain\Project\ProjectId;
use Illuminate\Console\Command;

class ProjectSnapshot extends Command
{
    protected $signature = 'project:snapshot {uuid : Project uuid}';

    protected $description = 'Snapshot project';

    public function __construct(private Snapshot $snapshotCommand)
    {
        parent::__construct();
    }

    public function handle()
    {
        $uuid = $this->input->getArgument('uuid');

        $projectId = ProjectId::fromString($uuid);

        $this->snapshotCommand->exec($projectId);
    }
}
