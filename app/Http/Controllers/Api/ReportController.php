<?php

namespace App\Http\Controllers\Api;


use App\Http\Requests\ReportPendingRequest;
use App\Http\Requests\ReportRequest;
use App\Repository\Interfaces\Report\ReportRepositoryInterface;
use App\Repository\Interfaces\ReportPending\ReportPendingRepositoryInterface;
use Illuminate\Http\Request;

class ReportController extends BaseApiController
{
    private $reportRepository;
    private $reportPendingRepository;

    public function  __construct(ReportRepositoryInterface $reportRepository, ReportPendingRepositoryInterface $reportPendingRepository)
    {
        $this->reportRepository = $reportRepository;
        $this->reportPendingRepository = $reportPendingRepository;
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
        return $this->sendResponse($report, "Relatório removido com sucesso");
    }
}
