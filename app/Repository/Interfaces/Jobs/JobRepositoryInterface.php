<?php

namespace App\Repository\Interfaces\Jobs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface JobRepositoryInterface {

    public function getJob($classname, $column, $value);
}
