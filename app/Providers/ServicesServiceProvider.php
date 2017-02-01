<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Services\User as UserService;
use App\Services\Contracts\UserService as UserServiceContract;
use App\Services\Item as ItemService;
use App\Services\Contracts\ItemService as ItemServiceContract;

class ServicesServiceProvider extends ServiceProvider
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
        $this->app->singleton(UserServiceContract::class, UserService::class);
        $this->app->singleton(ItemServiceContract::class, ItemService::class);
    }
}
