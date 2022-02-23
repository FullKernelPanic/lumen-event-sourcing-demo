<?php

declare(strict_types=1);


namespace App\Console\Commands;

use Domain\Project\Command\Rename;
use Domain\Project\ProjectId;
use Illuminate\Console\Command;

class ProjectRename extends Command
{
    protected $signature = 'project:rename {uuid : Project uuid} {name : Project new name}';

    protected $description = 'Rename a project';

    public function __construct(private Rename $renameCommand)
    {
        parent::__construct();
    }

    public function handle()
    {
        $uuid = $this->input->getArgument('uuid');
        $name = $this->input->getArgument('name');

        $projectId = ProjectId::fromString($uuid);

        $this->renameCommand->exec($projectId, $name);
    }
}
