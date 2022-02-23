<?php

namespace App\Console;

use App\Console\Commands\ProjectCreate;
use App\Console\Commands\ProjectDelete;
use App\Console\Commands\ProjectRename;
use App\Console\Commands\ProjectSnapshot;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        ProjectCreate::class,
        ProjectDelete::class,
        ProjectRename::class,
        ProjectSnapshot::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //
    }
}
