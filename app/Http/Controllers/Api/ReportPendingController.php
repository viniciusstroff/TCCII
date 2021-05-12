<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\ReportPendingRequest;
use App\Repository\Interfaces\Report\ReportRepositoryInterface;
use Illuminate\Http\Request;

class ReportPendingController extends BaseApiController
{
    private $reportRepository;

    public function  __construct(ReportRepositoryInterface $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    public function index()
    {

        try
        {
            $reports = $this->reportRepository->getPendingReports();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($reports, "Lista de relatórios");

    }

    // public function create()
    // {

    // }


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
