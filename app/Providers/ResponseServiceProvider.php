<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('success', function ($code = 200, $msg = 'success', $data = null, $status = 200) {
            $content = array('code' => $code, 'msg' => $msg, 'data' => $data);
            return response()->json($content, $status);
        });
        Response::macro('fail', function ($code = 100, $msg = 'fail', $data = null, $status = 200) {
            $content = array('code' => $code, 'msg' => $msg, 'data' => $data);
            return response()->json($content, $status);
        });
    }
}
