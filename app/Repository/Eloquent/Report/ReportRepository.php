<?php

namespace App\Repository\Eloquent\Report;

use App\Factories\GenericFactory;
use App\Helpers\CreateRegisterHelper;
use App\Helpers\Lighthouse;
use App\Helpers\QueueHelper;
use App\Helpers\UrlHelper;
use App\Jobs\ProcessAuditReports;
use App\Models\Report;
use App\Models\ReportDocument;
use App\Repository\Eloquent\BaseRepository;
use App\Repository\Interfaces\Report\ReportRepositoryInterface;
use App\VOs\Filters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ReportRepository implements ReportRepositoryInterface {

    protected Model $report;
    protected Model $reportDocument;
    protected $factory;

    public function __construct(GenericFactory $factory)
    {
       
       $this->factory = $factory;  
       $this->report = $this->factory->getInstance(Report::class);  
       $this->reportDocument = $this->factory->getInstance(ReportDocument::class);
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
        try{
            $report = $this->find($id);
            // $report->reportPending()->delete();
            $report->delete();
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function saveReport(Array $request)
    {
        try{
            $siteName = UrlHelper::getOnlySiteName($request['site']);

            $isEditing = CreateRegisterHelper::isEditing($request, 'id');
            if($isEditing){
                $report = $this->find($request['id']);
                $lighthouse = new Lighthouse($report->site);
                $lighthouse->setCategories(['accessibility', 'performance']);
                dd($lighthouse->audit());
                $report->update($request);
            } else {
                $report = $this->report->create($request);
            }
           
            $queueName = QueueHelper::getQueueName('audit', $report->id);
            ProcessAuditReports::dispatch($report)->onQueue($queueName);
            
        } catch (\Exception $e) {
            dd($e);
            throw new \Exception("Problemas ao salvar um relatorio, {$e->getMessage()}");
        }
        return $this->report;
    }

    public function saveReportDocuments(Array $request) {
        try {
            $report = $this->reportDocument->create($request);
            //$this->report->create($request->all());
            // $this->report->tool_name = $report['tool_name'];
            // $this->report->site = $report['url'];
            // $this->report->file_format = 'json';
            // $this->report->file_fake_name = $siteName;
            // $this->report->file_name = uniqid ();
            // $this->report->save();
            
                $queueName = QueueHelper::getQueueName('audit', $report->id);
                ProcessAuditReports::dispatch($report)->onQueue($queueName);
        } catch (\Exception $e) {
            throw new \Exception("Problemas ao salvar um relatorio, {$e->getMessage()}");
        }
        return $report;
    }

    public function searchByFilters(Filters $filters, $paginate = false, $perPage = 15)
    {
        $reportTable = Report::TABLE_NAME;
        $reportDocuments = ReportDocument::TABLE_NAME;

        
        $search = Report::query()
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

        $reportsPending = Report::select('*')
                            ->where("status", "=", Report::PENDING_STATUS)    
                            ->orderBy("status", "asc")->get();
        return $reportsPending->toArray();
    }

}
