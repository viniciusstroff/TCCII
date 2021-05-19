<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Lighthouse;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\ReportPendingRequest;
use App\Models\Report;
use App\Repository\Interfaces\Report\ReportRepositoryInterface;
use Illuminate\Http\Request;
use Spatie\Async\Pool;
use Throwable;

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

    public function audit(Request $request)
    {
        $data = $request->only('report_id');
        $report_id = $data['report_id'];
    
        $report = $this->reportRepository->find($report_id);
    
        if(!$report)
            return $this->sendError("ERRO", "Relatório Pendente não encontrado");

        $sites[] = $report->site;

        $pool = Pool::create();
    
        foreach ($sites as $site) {
            $lighthouse = new Lighthouse($site);
            $pool->add(function () use($lighthouse) {

                $lighthouse->setCategories(['accessibility', 'performance']);
                $lighthouse->audit();
            })->then(function () use($lighthouse, $pool){

            })->catch(function( Throwable $exception){
                return $this->sendError("ERRO", $exception->getMessage());
            });
        }

        $results = await($pool);
        // if(!$this->lighthouse->isRunning()){

        // while():
       
        
        $fileExists = $this->reportFileExists($report);

        if($fileExists)
        {
           $this->reportRepository->updateFlagReportPending($report, $status = 1);
        }

        return $this->sendResponse("resultado pendente", "A audição está sendo executada...");
        // }


    }
    
    public function finishedReport($report_id){
        try{
            $report = $this->reportRepository->find($report_id);
            $fileExists = $this->reportFileExists($report);
            $message = $fileExists ? "Finalizado" : "pendente";
            return $this->sendResponse($fileExists, "Resultado $message");

        } catch( \Exception $e) {
            dd($e->getMessage());
        }
        
    }

    private function reportFileExists(Report $report)
    {
        $basePath = base_path('app/Console/outputs/');
        $fileExists = file_exists("{$basePath}{$report->file_fake_name}");

        return $fileExists;
    }

    
}
