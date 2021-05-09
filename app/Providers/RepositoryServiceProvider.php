<?php

namespace App\Providers;

use App\Repository\Eloquent\BaseRepository;
use App\Repository\Eloquent\Report\ReportRepository;
use App\Repository\Eloquent\ReportPending\ReportPendingRepository;
use App\Repository\Eloquent\ScoreRepository;
use App\Repository\EloquentRepositoryInterface;
use App\Repository\Interfaces\Report\ReportRepositoryInterface;
use App\Repository\Interfaces\ReportPending\ReportPendingRepositoryInterface;
use App\Repository\ScoreRepositoryInteface;
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
        $this->app->bind(EloquentRepositoryInterface::class, BaseRepository::class);

        $this->app->bind(ReportRepositoryInterface::class, ReportRepository::class);

        $this->app->bind(ScoreRepositoryInteface::class, ScoreRepository::class);

        $this->app->bind(ReportPendingRepositoryInterface::class, ReportPendingRepository::class);

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
