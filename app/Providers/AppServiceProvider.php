<?php

namespace App\Providers;

use App\Domains\Booking\V1\Interfaces\IOrder;
use App\Domains\Booking\V1\Repositories\OrderRepository;
use App\Domains\Trip\V1\Interfaces\ILine;
use App\Domains\Trip\V1\Interfaces\ISeat;
use App\Domains\Trip\V1\Repositories\LineRepository;
use App\Domains\Trip\V1\Repositories\SeatRepository;
use App\Domains\Auth\V1\Interfaces\IAuth;
use App\Domains\Auth\V1\Repositories\AuthRepository;
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
        $this->app->singleton(IAuth::class, AuthRepository::class);
        $this->app->singleton(ILine::class, LineRepository::class);
        $this->app->singleton(ISeat::class, SeatRepository::class);
        $this->app->singleton(IOrder::class, OrderRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
