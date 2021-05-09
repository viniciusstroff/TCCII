<?php

namespace App\Repository\Interfaces\Report;


interface ReportRepositoryInterface {


    public function all();
    public function save(Array $request);
}
