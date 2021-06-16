<?php

namespace App\Console\Commands;

use App\Repository\Interfaces\Report\ReportRepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessUpdateReportStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:is_finished';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verificar se os arquivos da auditoria estão prontos para consumir';

    
    
    protected $report;
    protected $reportRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(ReportRepositoryInterface $reportRepository)
    {
        $this->reportRepository = $reportRepository;
       
        try{
            $pendingReports = $this->reportRepository->getPendingReports();
            foreach($pendingReports as $report){
                $reportsStorage = "reports".$report['file_name'];
                if(Storage::disk()->exists($reportsStorage))
                    $this->reportRepository->updateReportStatus($report['id'], $status = 1);

                if(Storage::disk()->missing($reportsStorage))
                    Log::notice("[ProcessUpdateReportStatus] Arquivo {$reportsStorage} não existe", $report);
            }
        // }
            
    //
        }catch(\Exception $e){
            dd($e);
        }
    }
}
