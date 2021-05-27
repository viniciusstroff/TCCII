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
    public $timeout = 120;
    protected $report;
    protected $reportRepository;
    protected $request;
    /**
     * Create a new job instance.
     *
     * @return void
     */

    // 
    public function __construct($report)
    {
        // $this->onQueue('audits');
        $this->report = $report;
        // $this->report = $report;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ReportRepositoryInterface $reportRepository, Lighthouse $lighthouse)
    {       
            $documentsData = [];
        // foreach ($this->reports as $report) {
            $lighthouse->setSite($this->report->site);
            $lighthouse->setCategories(['accessibility', 'performance']);
            $lighthouse->setTimeOut($this->timeout);
            $lighthouse->audit();

            if($lighthouse->hasFinished()){
                $documentsData['file_fake_name'] = $lighthouse->getSite();
                $documentsData['file_format'] = $lighthouse->getOutputFormat();
                $documentsData['file_name'] = $lighthouse->getOutputFile();
                $documentsData['report_id'] = $this->report->id;
                // $this->reportRepository->saveReportDocuments($documentsData);
            }
        // }
    }
}
