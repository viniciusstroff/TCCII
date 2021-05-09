<?php

namespace App\Repository\Eloquent\ReportPending;

use App\Models\ReportPending;
use App\Repository\Eloquent\BaseRepository;
use App\Repository\Interfaces\ReportPending\ReportPendingRepositoryInterface;

class ReportPendingRepository extends BaseRepository implements  ReportPendingRepositoryInterface{

    public function __construct(ReportPending $model)
    {
       parent::__construct($model);
    }

    public function save(Array $request)
    {
        try
        {
            $site = $request['site'];
            $toolName = $request['tool_name'];

            $report = $this->model->create($request);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function all()
    {
        $reports = $this->model::all();

        return $reports->toArray();
    }
}
