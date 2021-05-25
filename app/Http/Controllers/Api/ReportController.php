<?php

namespace App\Http\Controllers\Api;

use App\Factories\GenericFactory;
use App\Helpers\Lighthouse;
use App\Helpers\QueueHelper;
use App\Http\Requests\ReportPendingRequest;
use App\Http\Requests\ReportRequest;
use App\Http\Requests\TesteRequest;
use App\Jobs\ProcessAuditReports;
use App\Models\Job;
use App\Models\Report;
use App\Repository\Interfaces\Jobs\JobRepositoryInterface;
use App\Repository\Interfaces\Report\ReportRepositoryInterface;
use App\VOs\Filters;
use Illuminate\Bus\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Bus;
use Spatie\Async\Pool;
use Throwable;

class ReportController extends BaseApiController
{
    private $reportRepository;
    private $genericFactory;
    // private $processReports;

    private $jobRepository;

    public function  __construct(
        ReportRepositoryInterface $reportRepository, 
        GenericFactory $genericFactory,
        JobRepositoryInterface $jobRepository)//ProcessReportsPending $processReports
    {
        $this->reportRepository = $reportRepository;
        $this->genericFactory = $genericFactory;
        $this->jobRepository = $jobRepository;
        // $this->processReports = $processReports;
    }

    public function index()
    {
        try
        {
            $reports = $this->reportRepository->all();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($reports, "Lista de relatórios");

    }

    public function create()
    {

    }

    public function store(ReportRequest $request)
    {
        try
        {
            $request = $request->only(['reports']);
            $reports = $request['reports'];
            foreach($reports as $report){
                $report = $this->reportRepository->saveReport($report);
                $queueName = QueueHelper::getQueueName('audit', $report->id);
                // ProcessAuditReports::dispatch($this->reportRepository, $report)->onQueue('audits');
                // $job = (new ProcessAuditReports($this->reportRepository, $report))->onQueue('audits');
                // $this->dispatch($job);

                $batch = Bus::batch([
                    (new ProcessAuditReports($this->reportRepository, $report))
                ])->then(function (Batch $batch) {
                    // All jobs completed successfully...
                })->name('audits')->dispatch();
            }

           

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse("success", "Relatório salvo com sucesso");
    }


    public function show($id)
    {
        try{
            $report = $this->reportRepository->find($id);
            
        } catch (\Exception $e){
            return $this->sendResponse("Erro", "{$e->getMessage()}");
        }
        return $this->sendResponse($report, "Dados do relatório carregados com sucesso");
    }


    public function edit($id)
    {
        
    }


    public function update(ReportRequest $request, $id)
    {
        $request = $request->only(['site', 'tool_name']);

        try{
            $report = $this->reportRepository->updateReport($request, $id);
        } catch ( \Exception $e) {
            $this->sendError("ERRO", "Erro ao tentar atualizar o relatorio de id {$id}, {$e->getMessage()}");
        }
        $this->sendResponse($report, "Relatório alterado com sucesso");
    }

    public function destroy($id)
    {
        try
        {
            $report = $this->reportRepository->find($id);
            $report->reportPending()->delete();
            $report->delete();
            
        } catch (\Exception $e){
            return $this->sendResponse("Erro", "{$e->getMessage()}");
        }
        return $this->sendResponse("Sucesso", "Relatório removido com sucesso");
    }

    public function search(Request $request)
    {
        $filters = ($request->has('filters')) ? $request->input('filters') : [];
        try {
            $filters = $this->genericFactory->getInstance(Filters::class, $filters);
            $reports = $this->reportRepository->searchByFilters($filters, $paginate = true);
        } catch (\Exception $e) {
            return $this->sendError("Erro", "{$e->getMessage()}");
        }
        return $this->sendResponse($reports, "Filtro realizado com sucesso" );
    }

    public function audit(Request $request) {
        $data = $request->only('reports');
        $reports = $data['reports'];
        $job = Job::find(1);
        // dd(Job::find(1));

        dd(Bus::findBatch('9382e9bd-fb96-4b34-9419-58693b40f999')->progress);
        exit;
        // shell_exec('php artisan queue:work --once');
        try {
            foreach($reports as $report){
                $job = (new ProcessAuditReports($this->reportRepository, $report));
                $this->dispatch($job);
            }
        
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), "Ocorreu algum erro ao utilizar a fila");
        }
        

        return $this->sendResponse([], "Audição sendo executada em segundo plano");

    }
}
