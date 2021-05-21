<?php

namespace App\Repository\Interfaces\Report;

use App\Models\Report;

interface ReportRepositoryInterface {


    public function all();
    public function saveReport(Array $request);
    public function updateReport(Array $request, $id);
    public function getPendingReports();    
    public function find($id);
    public function getReportNotFinished($id);
    public function updateFlagReportPending(Report $report, $status = 1);
    public function searchPendingReportByFilters(Array $filters, $paginate = false, $perPage = 15);
}
