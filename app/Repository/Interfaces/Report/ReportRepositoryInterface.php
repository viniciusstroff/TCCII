<?php

namespace App\Repository\Interfaces\Report;

use App\DTOs\Reports\ReportScoreDTO;
use App\VOs\Filters;

interface ReportRepositoryInterface {

    public function find($id);
    public function all();
    public function delete($id);
    public function saveReport(Array $request);
    public function saveReportDocuments(Array $report);
    public function searchByFilters(Filters $filters, $paginate = false, $perPage = 15);
    public function getPendingReports();    
    public function updateReportStatus($id, $status = 1);
    public function getFinishedReports();
    public function saveReportScore(ReportScoreDTO $reportScoreDTO);
    public function getReportScores(Filters $filters, $paginate = false, $perPage = 15);
    public function getReportScoresByReportId($reportId);
}
