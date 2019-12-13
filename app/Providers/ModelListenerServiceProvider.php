<?php

namespace App\Providers;

use App\Model\InterfaceTable;
use App\Model\Position;
use App\Model\User;
use App\Observers\InterfaceTableObserver;
use App\Observers\PositionObserver;
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
        User::observe(UserObserver::class);
        InterfaceTable::observe(InterfaceTableObserver::class);
    }
}
