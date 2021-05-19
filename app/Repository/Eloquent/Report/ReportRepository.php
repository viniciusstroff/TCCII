<?php

namespace App\Repository\Eloquent\Report;

use App\Factories\GenericFactory;
use App\Helpers\UrlHelper;
use App\Models\Report;
use App\Models\ReportPending;
use App\Repository\Eloquent\BaseRepository;
use App\Repository\Interfaces\Report\ReportRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

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


    public function saveReport(Array $request)
    {
        try{
            $sites = $request['sites'];
            
            foreach ($sites as $site)
            {
                $siteName = UrlHelper::getOnlySiteName($site['url']);
                $this->report = $this->factory->getInstance(Report::class);
                //$this->report->create($request->all());
                $this->report->tool_name = $site['tool_name'];
                $this->report->site = $site['url'];
                $this->report->file_format = 'json';
                $this->report->file_fake_name = "{$siteName}.{$this->report->file_format}";
                $this->report->file_name = uniqid ();
                $this->report->save();

                $this->reportPending = $this->factory->getInstance(ReportPending::class);
                $this->reportPending->report()->associate($this->report);
                $this->reportPending->save();
            }
        } catch (\Exception $e) {
            dd($e->getMessage(), $e);
        }
    }

    public function updateReport(Array $request, $id)
    {
        
        try{
            $siteName = UrlHelper::getOnlySiteName($request['site']);
            $this->report = $this->find($id);
            $this->report->update($request);
        } catch (\Exception $e) {
            dd($e->getMessage(), $e);
        }
    }

    public function all()
    {
        $reports = $this->report->all();

        return $reports->toArray();
    }


    public function getPendingReports()
    {
        $reportsPending = $this->reportPending->where('is_finished', 0)->get();
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

    // public function changeReportFlagFinished($id, $flag = 0)
    // {
    //     $report = Report::find($id);

    // }
}
