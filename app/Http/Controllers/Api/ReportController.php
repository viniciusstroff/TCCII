<?php

namespace App\Http\Controllers\Api;

use App\Factories\GenericFactory;
use App\Http\Requests\ReportRequest;
use App\Repository\Interfaces\Jobs\JobRepositoryInterface;
use App\Repository\Interfaces\Report\ReportRepositoryInterface;
use App\VOs\Filters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ReportController extends BaseApiController
{
    private $reportRepository;
    private $genericFactory;

    public function  __construct(
        ReportRepositoryInterface $reportRepository, 
        GenericFactory $genericFactory)
    {
        $this->reportRepository = $reportRepository;
        $this->genericFactory = $genericFactory;
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
            $request = $request->only(['site', 'tool_name']);
            $report = $this->reportRepository->saveReport($request);

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($report, "Relatório salvo com sucesso");
    }

    public function update(ReportRequest $request)
    {
        try
        {
            $request = $request->only(['site', 'tool_name', 'id']);
            $report = $this->reportRepository->saveReport($request);

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($report, "Relatório atualizado com sucesso");
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

    public function destroy($id)
    {
        try
        {
            $report = $this->reportRepository->delete($id);
            
        } catch (\Exception $e){
            return $this->sendResponse("Erro", "{$e->getMessage()}");
        }
        return $this->sendResponse($report, "Relatório removido com sucesso");
    }

    public function search(Request $request)
    {
        $filters = ($request->has('filters')) ? $request->input('filters') : [];
        
        try {
            $filters = $this->genericFactory->getInstance(Filters::class, ['filters' => $filters]);
            $reports = $this->reportRepository->searchByFilters($filters, $paginate = true);
        } catch (\Exception $e) {
            return $this->sendError("Erro", "{$e->getMessage()}");
        }
        return $this->sendResponse($reports, "Filtro realizado com sucesso" );
    }

    public function audit(Request $request) {
        $data = $request->only('reports');
        $listOfReportsId = $data['reports'];
        try {
            
            // foreach($listOfReportsId as $key => $reportId){
            //     $queueName = QueueHelper::getQueueName('audit', $reportId);
                // Artisan::call('queue:work', ['--queue' => 'audit_21', '--max-jobs' => 2, '--timeout' => 120]);
                Artisan::call('queue:work', ['--max-jobs' => 2, '--timeout' => 120]);
            // }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), "Ocorreu algum erro ao utilizar a fila");
        }
        

        return $this->sendResponse([], "Audição sendo executada em segundo plano");
    }
}
