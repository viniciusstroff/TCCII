<?php

namespace App\Http\Controllers\Api;


use App\Http\Requests\ReportPendingRequest;
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

    public function store(ReportPendingRequest $request)
    {
        try
        {
            $request = $request->only(['sites', 'tool_name']);

            $reportPending = $this->reportRepository->save($request);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse("success", "Relatório salvo com sucesso");
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {

    }


    public function update(ReportPendingRequest $request, $id)
    {

    }

    public function destroy($id)
    {
    }
}
