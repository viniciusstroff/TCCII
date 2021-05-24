<?php

namespace App\Repository\Eloquent\Report;

use App\Factories\GenericFactory;
use App\Helpers\UrlHelper;
use App\Models\Report;
use App\Models\ReportPending;
use App\Repository\Eloquent\BaseRepository;
use App\Repository\Interfaces\Report\ReportRepositoryInterface;
use App\VOs\Filters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ReportRepository extends BaseRepository implements ReportRepositoryInterface {

    protected Model $report;
    protected Model $reportPending;
    protected $factory;

    public function __construct(Report $report, ReportPending $reportPending, GenericFactory $factory)
    {
       $this->report = $report;
       $this->reportPending = $reportPending;
       $this->factory = $factory;
       parent::__construct($report);
    }


    public function saveReport(Array $report)
    {
        try{
            
            $siteName = UrlHelper::getOnlySiteName($report['url']);
            $this->report = $this->factory->getInstance(Report::class);
            //$this->report->create($request->all());
            $this->report->tool_name = $report['tool_name'];
            $this->report->site = $report['url'];
            $this->report->file_format = 'json';
            $this->report->file_fake_name = $siteName;
            $this->report->file_name = uniqid ();
            $this->report->save();

            $this->reportPending = $this->factory->getInstance(ReportPending::class);
            $this->reportPending->report()->associate($this->report);
            $this->reportPending->save();
            
        } catch (\Exception $e) {
            throw new \Exception("Problemas ao salvar um relatorio, {$e->getMessage()}");
        }
        return $this->report;
    }

    public function updateReport(Array $request, $id)
    {
        
        try{
            $siteName = UrlHelper::getOnlySiteName($request['site']);
            $request['file_fake_name'] = $siteName;
            $this->report = $this->find($id);
            $this->report->update($request);
        } catch (\Exception $e) {
            dd($e->getMessage(), $e);
        }
    }

    public function searchByFilters(Filters $filters, $paginate = false, $perPage = 15)
    {
        $reportTable = Report::TABLE_NAME;
        $reportPendingTable = ReportPending::TABLE_NAME;
        $search = Report::query()
                        ->join($reportPendingTable, "{$reportPendingTable}.report_id", "=", "{$reportTable}.id")
                        ->addSelect("{$reportTable}.*")
                        ->addSelect("{$reportPendingTable}.is_finished")
                        ->orderBy("{$reportTable}.id", 'DESC'); 

        if($filters->has('site'))
            $search->where("{$reportTable}.site", 'like', '%'.$filters->getFilter('site').'%');

        if($filters->has('is_finished'))
            $search->where("{$reportPendingTable}.is_finished", "=", $filters->getFilter('is_finished'));
    
        if($filters->has('tool_name'))
            $search->where('tool_name', $filters->getFilter('tool_name'));

        if($filters->has('tool_name'))
            $search->where('tool_name', $filters->getFilter('tool_name'));

        return ($paginate) ? $search->paginate($perPage)->toArray() : $search->get()->toArray();
    }

    public function all()
    {
        $reports = Report::all();

        return $reports->toArray();
    }


    public function getPendingReports()
    {
        $reportsPending = ReportPending::select('*')->orderBy("is_finished", "asc")->get();
        return $reportsPending->toArray();
    }

    public function getReportNotFinished($id)
    {   
        $report = Report::where("reports.id", $id)
                        ->where('reports_pending.is_finished', 0)
                        ->join('reports_pending', 'reports.id', '=', 'reports_pending.report_id')
                        ->addSelect('reports.*')
                        ->addSelect('reports_pending.*')->first();

        return $report;
    }

    public function updateFlagReportPending(Report $report, $status = 1)
    {
        $reportPending = $report->reportPending()->first();

        $reportPending->is_finished = $status;
        $reportPending->save();
    }
    
    public function searchPendingReportByFilters(Array $filters, $paginate = false, $perPage = 15)
    {
        $is_finished = $filters['is_finished'];
        $search = $this->reportPending->newQuery();

        if($filters['is_finished'] === 0 || $filters['is_finished'] === 1) {
            $search->where('is_finished', $filters['is_finished']);
        }

        $search = $search->get();
        return $search->toArray();
    }
}
