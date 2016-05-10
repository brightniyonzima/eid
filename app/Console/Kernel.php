<?php

namespace EID\Console;

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
        \EID\Console\Commands\Inspire::class,
       
        \EID\Console\Commands\FetchFile::class,
		
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $logfile = storage_path('app/backups/backuplog.txt');
        $schedule->command('backup:run --only-db --suffix=".sql" ')
                 ->daily()
                 ->appendOutputTo($logfile);
    }
}
