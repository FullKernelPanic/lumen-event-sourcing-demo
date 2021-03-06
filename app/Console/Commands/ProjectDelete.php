<?php

declare(strict_types=1);


namespace App\Console\Commands;

use Domain\Project\Command\Delete;
use Domain\Project\ProjectId;
use Illuminate\Console\Command;

class ProjectDelete extends Command
{
    protected $signature = 'project:delete {uuid : Project uuid}';

    protected $description = 'Delete a project';

    public function __construct(private Delete $deleteCommand)
    {
        parent::__construct();
    }

    public function handle()
    {
        $uuid = $this->input->getArgument('uuid');

        $projectId = ProjectId::fromString($uuid);

        $this->deleteCommand->exec($projectId);
    }
}