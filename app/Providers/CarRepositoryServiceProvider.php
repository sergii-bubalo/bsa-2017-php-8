<?php

namespace App\Providers;

use App\Repositories\CarRepository;
use App\Repositories\Contracts\CarRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class CarRepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CarRepositoryInterface::class, CarRepository::class);
    }
}
