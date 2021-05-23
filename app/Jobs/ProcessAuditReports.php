<?php

namespace App\Jobs;

use App\Helpers\Lighthouse;
use App\Models\Report;
use App\Repository\Interfaces\Report\ReportRepositoryInterface;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\Async\Pool;

class ProcessAuditReports implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;
    protected $reports;
    protected $reportRepository;
    protected $request;
    /**
     * Create a new job instance.
     *
     * @return void
     */

    // 
    public function __construct(ReportRepositoryInterface $reportRepository, $reports)
    {
        // $this->report = $report;
        $this->reportRepository = $reportRepository;
        $this->reports = $reports;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Lighthouse $lighthouse)
    {
        foreach ($this->reports as $report) {
            
            $lighthouse->setSite($report['site']);
            $lighthouse->setCategories(['accessibility', 'performance']);
            $lighthouse->audit();
        }
    }
}
