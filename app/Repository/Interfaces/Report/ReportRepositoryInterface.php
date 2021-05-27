<?php

namespace App\Repository\Interfaces\Report;

use App\Models\Report;
use App\VOs\Filters;
use Illuminate\Http\Request;

interface ReportRepositoryInterface {

    public function find($id);
    public function all();
    public function delete($id);
    public function saveReport(Array $request);
    public function saveReportDocuments(Array $report);
    public function searchByFilters(Filters $filters, $paginate = false, $perPage = 15);
    public function getPendingReports();    
}
