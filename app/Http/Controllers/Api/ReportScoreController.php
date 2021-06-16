<?php

namespace App\Http\Controllers\Api;

use App\Factories\GenericFactory;
use App\Http\Requests\ReportRequest;
use App\Repository\Interfaces\Jobs\JobRepositoryInterface;
use App\Repository\Interfaces\Report\ReportRepositoryInterface;
use App\VOs\Filters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ReportScoreController extends BaseApiController
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
    
    public function show($id)
    {
        try{
            $report = $this->reportRepository->find($id);
            
        } catch (\Exception $e){
            return $this->sendResponse("Erro", "{$e->getMessage()}");
        }
        return $this->sendResponse($report, "Dados do relatório carregados com sucesso");
    }

    public function search(Request $request)
    {
        
        $this->reportRepository->getFinishedReports();
        $filters = ($request->has('filters')) ? $request->input('filters') : [];
        
        try {
            $filters = $this->genericFactory->getInstance(Filters::class, ['filters' => $filters]);
            $reports = $this->reportRepository->searchByFilters($filters, $paginate = true);
        } catch (\Exception $e) {
            return $this->sendError("Erro", "{$e->getMessage()}");
        }
        return $this->sendResponse($reports, "Filtro realizado com sucesso" );
    }
}
