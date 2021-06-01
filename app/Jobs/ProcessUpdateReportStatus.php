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
use Illuminate\Support\Facades\Storage;
use Spatie\Async\Pool;

class ProcessUpdateReportStatus implements ShouldQueue
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
    public function __construct()
    {
        // $this->onQueue('audits');
        // $this->report = $report;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ReportRepositoryInterface $reportRepository)
    {       
        $this->reportRepository = $reportRepository;
            try{
                $pendingReports = $this->reportRepository->getPendingReports();
                foreach($pendingReports as $report){
                    $reportsStorage = storage_path("reports{$report['file_name']}.{$report['file_format']}");
                    if(Storage::disk()->exists($reportsStorage)){
                        $this->reportRepository->updateReportStatus($report['id'], $status = 1);
                    }

                    if(Storage::disk()->missing($reportsStorage)){

                    }
                }
            // }
                
        //
            }catch(\Exception $e){
                dd($e);
            }
    }
}
