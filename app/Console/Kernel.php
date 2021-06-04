<?php

namespace App\Console;

use App\Jobs\ProcessUpdateReportStatus;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\ProcessUpdateReportStatus::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //php artisan schedule:work
        $logPath = storage_path("logs/kernel/kernel".date('Y-m-d').".log");
        $schedule->command('queue:work', ['--queue' => 'audits', '--max-jobs' => 1 , '--timeout' => 120])
                            ->everyTwoMinutes()
                            ->appendOutputTo($logPath);

        $schedule->command('reports:is_finished')->everyMinute()->appendOutputTo( storage_path("logs/kernel/teste123.log"));        

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        $this->load(__DIR__.'/Commands/ProcessUpdateReportStatus');

        require base_path('routes/console.php');
    }
}
