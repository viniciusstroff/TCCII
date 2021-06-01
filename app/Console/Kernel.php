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
        //
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
        $logPath = storage_path("logs/kernel/kernel".date('Y-m-d-H-i-s').".log");
        $schedule->command('queue:work', ['--queue' => 'audits', '--max-jobs' => 1 , '--timeout' => 120])
                            ->everyTwoMinutes()
                            ->appendOutputTo($logPath);


        ProcessUpdateReportStatus::dispatchSync();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
