<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
       // Simulation
        $this->app->bind(
            \App\Repositories\SimulationRepositoryInterface::class,
            \App\Repositories\SimulationRepository::class
        );
    }
}
