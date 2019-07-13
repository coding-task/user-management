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
        $this->app->bind(
            'UM\Repositories\Contracts\UserRepositoryInterface',
            'UM\Repositories\Eloquent\UserRepository'
        );
        $this->app->bind(
            'UM\Repositories\Contracts\GroupRepositoryInterface',
            'UM\Repositories\Eloquent\GroupRepository'
        );
    }
}
