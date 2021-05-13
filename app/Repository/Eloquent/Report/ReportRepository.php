<?php

namespace App\Repository\Eloquent\Report;

use App\Factories\GenericFactory;
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

    public function save(Array $request)
    {
        try
        {
            $this->saveReport($request);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    private function saveReport(Array $request)
    {
        try{
            $sites = $request['sites'];
            
            foreach ($sites as $site)
            {
                $this->report = $this->factory->getInstance(Report::class);
                $this->report->tool_name = $site['tool_name'];
                $this->report->site = $site['url'];
                $this->report->file_format = 'json';
                $this->report->file_fake_name = "{$site['url']}.json";
                $this->report->file_name = uniqid ();
                $this->report->save();

                $this->reportPending = $this->factory->getInstance(ReportPending::class);
                $this->reportPending->report()->associate($this->report);
                $this->reportPending->save();
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function all()
    {
        $reports = $this->report->all();

        return $reports->toArray();
    }


    public function getPendingReports()
    {
        $reportsPending = $this->reportPending->all();

        return $reportsPending->toArray();
    }

    public function findPendingReport($id)
    {   
        $reportPending = $this->reportPending->find($id);

        return $reportPending;
    }

}
