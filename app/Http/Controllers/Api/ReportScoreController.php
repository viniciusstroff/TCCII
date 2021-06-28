<?php

namespace App\Http\Controllers\Api;

use App\Factories\GenericFactory;
use App\Http\Requests\ReportRequest;
use App\Repository\Interfaces\Jobs\JobRepositoryInterface;
use App\Repository\Interfaces\Report\ReportRepositoryInterface;
use App\Repository\Interfaces\Report\ReportScoreRepositoryInterface;
use App\VOs\Filters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ReportScoreController extends BaseApiController
{
    private $reportScoreRepository;
    private $genericFactory;

    public function  __construct(
        ReportScoreRepositoryInterface $reportScoreRepository, 
        GenericFactory $genericFactory)
    {
        $this->reportScoreRepository = $reportScoreRepository;
        $this->genericFactory = $genericFactory;
    }

    public function index()
    {
        try
        {
            $reports = $this->reportScoreRepository->all();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($reports, "Lista de relatórios");

    }

    public function show($reportId)
    {
        try{
            $reportScore = $this->reportScoreRepository->findByReport($reportId);
        } catch (\Exception $e){
            return $this->sendResponse([], "{$e->getMessage()}");
        }
        return $this->sendResponse($reportScore, "Dados da pontuações do relatório carregados com sucesso");
    }

    public function search(Request $request)
    {
        
        $this->reportRepository->getFinishedReports();
        
        try {
            $filters = $this->genericFactory->getInstance(Filters::class, ['filters' => $filters]);
            $reports = $this->reportRepository->searchByFilters($filters, $paginate = true);
        } catch (\Exception $e) {
            return $this->sendError("Erro", "{$e->getMessage()}");
        }
        return $this->sendResponse($reports, "Filtro realizado com sucesso" );
    }
}
