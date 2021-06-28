<?php

namespace App\Providers;

use App\Repository\Eloquent\BaseRepository;
use App\Repository\Eloquent\Jobs\JobRepository;
use App\Repository\Eloquent\Report\ReportRepository;
use App\Repository\Eloquent\Report\ReportScoreRepository;
use App\Repository\Eloquent\ReportPending\ReportPendingRepository;
use App\Repository\EloquentRepositoryInterface;
use App\Repository\Interfaces\Jobs\JobRepositoryInterface;
use App\Repository\Interfaces\Report\ReportRepositoryInterface;
use App\Repository\Interfaces\Report\ReportScoreRepositoryInterface;
use App\Repository\Interfaces\ReportPending\ReportPendingRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        
        $this->app->bind(ReportRepositoryInterface::class, ReportRepository::class);

        
        $this->app->bind(EloquentRepositoryInterface::class, BaseRepository::class);


        $this->app->bind(ReportScoreRepositoryInterface::class, ReportScoreRepository::class);

        $this->app->bind(JobRepositoryInterface::class, JobRepository::class);

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
