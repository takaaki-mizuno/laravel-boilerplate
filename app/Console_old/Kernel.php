<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Generators\RepositoryMakeCommand::class,
        \App\Console\Commands\Generators\ModelMakeCommand::class,
        \App\Console\Commands\Generators\ServiceMakeCommand::class,
        \App\Console\Commands\Generators\HelperMakeCommand::class,
        \App\Console\Commands\Generators\AdminCRUDMakeCommand::class,
        \App\Console\Commands\Generators\CreateTableMigrationMakeCommand::class,
        \App\Console\Commands\UpdateAssetHash::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     */
    protected function schedule(Schedule $schedule)
    {
    }
}
