<?php

namespace App\Providers;

use App\Model\User;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;

class ModelListenerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //User模型的观察者
        User::observe(UserObserver::class);
    }
}
