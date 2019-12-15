<?php

namespace App\Observers;

use App\Model\InterfaceTable;
use App\Model\LogTable;
use App\Mail\OAuth\Welcome;
use App\Model\User;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\Providers\Auth;

class InterfaceObserver
{
    public function created(InterfaceTable $interfaceTable)
    {
        try {
            $id = Auth::id();
            $interface = InterfaceTable::where('user_id', $id)->orderby('id', 'desc')->first();
            $user = User::where('id', $id)->first();
            $res = new LogTable();
            $res->user_id = $id;
            $res->operation_type = '增加';
            $res->operation_object = $interface->interface_name;
            $res->content = $user->name . '增加' . $interface->interface_name . '接口';
            $res->save();
        } catch (\Exception $e) {
        }
    }

    public function updated(InterfaceTable $interfaceTable)
    {
        try {
            $id=Auth::id();
            $interface = InterfaceTable::where('user_id', $id)->orderby('id', 'desc')->first();
            $user = User::where('id', $id)->first();
            $res = new LogTable();
            $res->user_id = $id;
            $res->operation_type = '更新';
            $res->operation_object = $interface->interface_name;
            $res->content = $user->name . '更新' . $interface->interface_name . '接口';
            $res->save();
        } catch (\Exception $e) {

        }
    }


    public function deleted(InterfaceTable $interfaceTable)
    {
        try {
            $id=Auth::id();
            $interface = InterfaceTable::where('user_id', $id)->orderby('id', 'desc')->first();
            $user = User::where('id', $id)->first();
            $res = new LogTable();
            $res->user_id = $id;
            $res->operation_type = '删除';
            $res->operation_object = $interface->interface_name;
            $res->content = $user->name . '删除' . $interface->interface_name . '接口';
            $res->save();
        } catch (\Exception $e) {
        }
    }
}

