<?php

namespace App\Http\Controllers\Api;

use App\Factories\GenericFactory;
use App\Helpers\Lighthouse;
use App\Http\Requests\ReportPendingRequest;
use App\Http\Requests\ReportRequest;
use App\Http\Requests\TesteRequest;
use App\Jobs\ProcessAuditReports;
use App\Models\Report;
use App\Repository\Interfaces\Report\ReportRepositoryInterface;
use App\VOs\Filters;
use Illuminate\Http\Request;
use Spatie\Async\Pool;
use Throwable;

class ReportController extends BaseApiController
{
    private $reportRepository;
    private $genericFactory;
    // private $processReports;

    public function  __construct(ReportRepositoryInterface $reportRepository, GenericFactory $genericFactory)//ProcessReportsPending $processReports
    {
        $this->reportRepository = $reportRepository;
        $this->genericFactory = $genericFactory;
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
            
            $request = $request->only(['sites']);
            $reportPending = $this->reportRepository->saveReport($request);
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


    public function update(ReportPendingRequest $request, $id)
    {
        $request = $request->only(['site', 'tool_name']);
        $reportPending = $this->reportRepository->updateReport($request, $id);
        dd($request, $id);
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

        try {
            $job = (new ProcessAuditReports($this->reportRepository, $reports))->delay(5);
            $this->dispatch($job);
        
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), "Ocorreu algum erro ao utilizar a fila");
        }
        

        return $this->sendResponse([], "Audição sendo executada em segundo plano");

    }
}
