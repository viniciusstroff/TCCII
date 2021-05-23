<?php

namespace App\Jobs;

use App\Helpers\Lighthouse;
use App\Models\Report;
use App\Repository\Interfaces\Report\ReportRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessReportsPending implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;
    protected $report;
    protected $reportRepository;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ReportRepositoryInterface $reportRepository, Report $report)
    {
        $this->report = $report;
        $this->reportRepository = $reportRepository;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
    }
}
