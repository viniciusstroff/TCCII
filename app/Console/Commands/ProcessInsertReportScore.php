<?php

namespace App\Console\Commands;

use App\DTOs\Reports\ReportScoreDTO;
use App\Factories\GenericFactory;
use App\Repository\Interfaces\Report\ReportRepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessInsertReportScore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:insert_score';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inserir na tabela os dados necessários para a pontuação do relatório';

    
    
    protected $report;
    protected $reportRepository;
    protected $genericFactory;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ReportRepositoryInterface $reportRepository, GenericFactory $genericFactory)
    {
        
        $this->reportRepository = $reportRepository;
        $this->genericFactory = $genericFactory;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
       
        try{

            $finishedReports = $this->reportRepository->getFinishedReports();
            foreach($finishedReports as $report)
            {
                $reportsStorage = "reports{$report['file_name']}";
                if(Storage::disk('local')->missing($reportsStorage)){
                    Log::notice("[ProccessInsertReportScore] Arquivo {$reportsStorage} não existe", $report);
                    continue;
                }

                $content = Storage::disk('local')->get($reportsStorage);
                $arrayContent =  json_decode($content, true);
                $reportScoreDTO = $this->genericFactory->getInstance(ReportScoreDTO::class, 
                [
                    'scores' => $arrayContent,
                    'reportId' => $report['report_id']
                ]);
                $this->reportRepository->saveReportScore($reportScoreDTO);
            }
            
        // }
            
    //
        }catch(\Exception $e){
            dd($e);
        }
    }

}
