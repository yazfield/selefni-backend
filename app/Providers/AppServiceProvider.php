<?php

namespace App\Providers;

use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Hasher::class, function ($app) {
            return $app['hash'];
        });
    }
}
