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
use App\Repository\Eloquent\BaseRepository;
use App\Repository\Interfaces\Report\ReportRepositoryInterface;
use App\VOs\Filters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ReportRepository implements ReportRepositoryInterface {

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
        $report = $this->report->find($id);
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

    public function saveReport(Array $request)
    {
        try
        {
           
            $isEditing = CreateRegisterHelper::isEditing($request, 'id');
            if($isEditing){
                $report = $this->report->where(['id' => $request['id']])->update($request);
            } else {
                $report = $this->report->create($request);

                $queueName = QueueHelper::getQueueName('audit', $report->id);
                ProcessAuditReports::dispatch($report)->onQueue('audits');
            }
            
        } catch (\Exception $e) {
            throw new \Exception("Problemas ao salvar um relatorio, {$e->getMessage()}");
        }
        return $this->report;
    }

    public function saveReportDocuments(Array $request) {
        try 
        {
            $report = $this->reportDocument->create($request);
        } catch (\Exception $e) {
            throw new \Exception("Problemas ao salvar um relatorio, {$e->getMessage()}");
        }
        return $report;
    }

    public function saveReportScore(ReportScoreDTO $reportScoreDTO)
    {
        try
        {

            $report = $this->reportScore->create(
                [
                    'accessibility' => $reportScoreDTO->getAccessibility()->get('score'),
                    'performance' => $reportScoreDTO->getPerformance()->get('score'),
                    'report_id' => $reportScoreDTO->getReportId()
                ]
            );

            return $report;
        } catch (\Exception $e)
        {
            throw new \Exception("Problemas ao salvar um relatorio, {$e->getMessage()}");
        }
    }

    public function updateReportStatus($id, $status = 1)
    {
        $request['status'] = $status;
        $request['id'] = $id;
        $this->saveReport($request);
    }

    public function searchByFilters(Filters $filters, $paginate = false, $perPage = 15)
    {
        $reportTable = Report::TABLE_NAME;
        $reportDocuments = ReportDocument::TABLE_NAME;

        
        $search = Report::query()
                        // ->where("status", "=", Report::PENDING_STATUS)
                        ->addSelect("{$reportTable}.*")
                        ->addSelect("{$reportDocuments}.file_fake_name")
                        ->leftJoin("{$reportDocuments}", "report_id" , "=", "{$reportTable}.id")
                        ->orderBy("{$reportTable}.id", 'DESC'); 

        if($filters->has('site'))
            $search->where("{$reportTable}.site", 'like', '%'.$filters->getFilter('site').'%');

        if($filters->has('status'))
            $search->where("{$reportTable}.status", "=", $filters->getFilter('status'));
    
        if($filters->has('tool_name'))
            $search->where('tool_name', $filters->getFilter('tool_name'));

        return ($paginate) ? $search->paginate($perPage)->toArray() : $search->get()->toArray();
    }

    public function getPendingReports()
    {
        $reportDocumentsTable = ReportDocument::TABLE_NAME;
        $reportTable = Report::TABLE_NAME;
        $reportsPending = Report::where("status", "=", Report::PENDING_STATUS)
                            ->select("{$reportTable}.*", "{$reportDocumentsTable}.file_name", "{$reportDocumentsTable}.file_format", "{$reportDocumentsTable}.file_fake_name")  
                            ->join("{$reportDocumentsTable}", "report_id", "=", "{$reportTable}.id")
                            ->orderBy("status", "asc")->get();
        return $reportsPending->toArray();
    }

    public function getFinishedReports()
    {
        $reportTable = Report::TABLE_NAME;
        $reportDocumentsTable = ReportDocument::TABLE_NAME;

        $reportFinished = Report::where("status", "=", Report::FINISHED_STATUS)
                            ->select("{$reportDocumentsTable}.*")
                            ->join("{$reportDocumentsTable}", "report_id", "=", "{$reportTable}.id")
                            ->orderBy("status", "asc")->get();

        return $reportFinished->toArray();
    }

    public function getReportScores(Filters $filters, $paginate = false, $perPage = 15)
    {
        $reportTable = Report::TABLE_NAME;
        $reportScoreTable = ReportScore::TABLE_NAME;

        $reportScores = $this->reportScore->addSelect("{$reportScoreTable}.*")
                                          ->addSelect("{$reportTable}.tool_name")
                                          ->addSelect("{$reportTable}.site")
                                          ->addSelect("{$reportTable}.tool_name")
                                          ->join("{$reportTable}", "id", "=", "{$reportScoreTable}.id")
                                          ->get();
        return $reportScores->toArray();
    }

    public function getReportScoresByReportId($reportId)
    {
        $reportTable = Report::TABLE_NAME;
        $reportScoreTable = ReportScore::TABLE_NAME;

        $reportScores = $this->reportScore->where("{$reportScoreTable}.report_id", "=", $reportId)
                                          ->addSelect("{$reportScoreTable}.*")
                                          ->addSelect("{$reportTable}.tool_name")
                                          ->addSelect("{$reportTable}.site")
                                          ->addSelect("{$reportTable}.tool_name")
                                          ->join("{$reportTable}", "id", "=", "{$reportScoreTable}.id")
                                          ->first()
                                          ->get();
        return $reportScores->toArray();
    }

}
