<?php

namespace App\Repository\Interfaces\ReportPending;


interface ReportPendingRepositoryInterface {


    public function all();
    public function save(Array $request);
}
