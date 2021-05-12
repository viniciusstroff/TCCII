<?php

namespace App\Repository\Interfaces\Report;


interface ReportRepositoryInterface {


    public function all();
    public function save(Array $request);
    public function getPendingReports();    
    public function find($id);
}
