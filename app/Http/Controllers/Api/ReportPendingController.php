<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\ReportPendingRequest;
use App\Repository\Interfaces\ReportPending\ReportPendingRepositoryInterface;
use Illuminate\Http\Request;

class ReportPendingController extends BaseApiController
{
    private $reportRepository;

    public function  __construct(ReportPendingRepositoryInterface $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    // public function index()
    // {



    // }

    // public function create()
    // {

    // }

    public function store(ReportPendingRequest $request)
    {
        dd('aqui');
        try
        {

            $request = $request->only(['sites', 'tool_name']);
            $reportPending = $this->reportRepository->save($request);
        } catch (\Exception $e) {
            dd($e);
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse("success", "A audição foi salva e está pendente para ser executada em segundo plano");
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
