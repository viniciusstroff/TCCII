<?php

namespace App\Repository\Interfaces\Report;

use App\DTOs\Reports\ReportScoreDTO;
use App\VOs\Filters;

interface ReportScoreRepositoryInterface {

    public function find($id);
    public function all();
    public function delete($id);
    public function findByReport($reportId);
}
