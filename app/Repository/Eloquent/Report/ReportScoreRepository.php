<?php

namespace App\Repository\Eloquent\Report;

use App\DTOs\Reports\ReportScoreDTO;
use App\Factories\GenericFactory;
use App\Helpers\CreateRegisterHelper;
use App\Helpers\Lighthouse;
use App\Helpers\QueueHelper;
use App\Helpers\UrlHelper;
use App\Jobs\ProcessAuditReports;
use App\Models\Report;
use App\Models\ReportDocument;
use App\Models\ReportScore;
use App\Repository\Interfaces\Report\ReportScoreRepositoryInterface;
use App\VOs\Filters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReportScoreRepository implements ReportScoreRepositoryInterface {

    protected Model $report;
    protected Model $reportDocument;
    protected Model $reportScore;
    protected $factory;

    public function __construct(GenericFactory $factory)
    {
       
       $this->factory = $factory;  
       $this->report = $this->factory->getInstance(Report::class);  
       $this->reportDocument = $this->factory->getInstance(ReportDocument::class);
       $this->reportScore = $this->factory->getInstance(ReportScore::class);
    //    parent::__construct($report);
    }

    public function find($id) {
        $report = $this->reportScore->find($id);
        return $report;
    }

    public function all()
    {
        $reports = Report::all();

        return $reports->toArray();
    }

    public function delete($id)
    {
        try
        {
            $report = $this->find($id);
            // $report->reportPending()->delete();
            $report->delete();
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function findByReport($reportId)
    {
        
        $reportTable = Report::TABLE_NAME;
        $reportScoreTable = ReportScore::TABLE_NAME;

        try
        {
            $report = $this->reportScore
                                        ->join("{$reportTable}", "{$reportTable}.id", "=", "{$reportScoreTable}.report_id")
                                        ->where("{$reportScoreTable}.report_id", "=", $reportId)
                                        ->addSelect("{$reportScoreTable}.*")
                                        ->addSelect("{$reportTable}.tool_name")
                                        ->addSelect("{$reportTable}.site")
                                        ->addSelect("{$reportScoreTable}.created_at")
                                        ->get();
            return $report->toArray();
        } catch (\Exception $e) {
            Log::error("[ReportScoreRepository - findByReport] message: {$e->getMessage()}");
            throw $e;
        }
    }
}