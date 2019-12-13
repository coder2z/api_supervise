<?php

namespace App\Providers;

use App\Model\InterfaceTable;
use App\Model\Project;
use App\Model\User;
use App\Observers\InterfaceObserver;
use App\Observers\ProjectObservers;
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

        //魏子超的部分
        InterfaceTable::observe(InterfaceObserver::class);
        Project::observe(ProjectObservers::class);
    }
}
