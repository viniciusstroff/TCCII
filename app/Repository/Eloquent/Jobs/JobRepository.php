<?php

namespace App\Repository\Eloquent\Jobs;

use App\Models\Job;
use App\Repository\Interfaces\Jobs\JobRepositoryInterface;
use App\Repository\Interfaces\ReportPending\ReportPendingRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class JobRepository implements  JobRepositoryInterface{

    public function __construct()
    {
    }

    public function getJob($classname, $column, $value )
    {
        // SELECT * FROM `jobs` WHERE payload like '%Report%id%i:54%'
        $job = Job::where("payload", 'like', "%{$classname}%{$column}%i:{$value}%")
                    ->select('id')
                    ->first();
        return $job->toArray();
        // $reportsPending = ReportPending::select('*')->orderBy("is_finished", "asc")->get();
        // return $reportsPending->toArray();
    }
}
