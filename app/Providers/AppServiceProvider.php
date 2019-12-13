<?php

namespace App\Providers;

use App\Model\User;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
<<<<<<< HEAD
=======
        //
        Validator::extend('check_password', function ($attribute, $value,$parameters, $validator) {
            if($value==null){
                return true;
            }else{
                if(strlen($value)>16){
                    return false;
                }
                return preg_match('/(a-z|[^a-z]){6,16}/',$value);
            }
        });

        Validator::extend('check_code', function ($attribute, $value,$parameters, $validator) {
            if($value=='-1'||$value=='0'){
                return true;
            }else{
                return false;
            }
        });

        Validator::extend('check_state', function ($attribute, $value,$parameters, $validator) {
            if($value=='1'||$value=='0'){
                return true;
            }else{
                return false;
            }
        });
>>>>>>> myxy99/master

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
