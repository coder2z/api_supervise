<?php


namespace App\Observers;
use App\Http\Controllers\Controller;
use App\Jobs\SendEmail;
use App\Mail\OAuth\Welcome;
use App\Model\InterfaceTable;
use App\Utils\Logs;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use http\Env\Request;

class InterfaceTableObserver
{
    public function created(InterfaceTable $interfaceTable){
        try{
            $to=InterfaceTable::join('mutuals as M','interface_tables.id','M.interface_id')
                ->join('users as U','M.front_user_id','U.id')
                ->where('interface_tables.id',$interfaceTable->id)
                ->select('email')
                ->get()->toArray()[0];
                $da=$to['email'];
            Mail::to($da)->later(env('MAIL_TIME'), new SendEmail());
        }catch (\Exception $e){
        }

    }
    public function updated(InterfaceTable $interfaceTable){
        try{
            $to=InterfaceTable::join('mutuals as M','interface_tables.id','M.interface_id')
                ->join('users as U','M.front_user_id','U.id')
                ->where('interface_tables.id',$interfaceTable->id)
                ->select('email')
                ->get()->toArray()[0];
            $da=$to['email'];
            Mail::to($da)->later(env('MAIL_TIME'), new SendEmail());
        }catch (\Exception $e){

        }
    }
    public function deleted(InterfaceTable $interfaceTable){
        try{
            $to=InterfaceTable::join('mutuals as M','interface_tables.id','M.interface_id')
                ->join('users as U','M.front_user_id','U.id')
                ->where('interface_tables.id',$interfaceTable->id)
                ->select('email')
                ->get()->toArray()[0];
            $da=$to['email'];
            Mail::to($da)->later(env('MAIL_TIME'), new SendEmail());
        }catch (\Exception $e){
        }
    }
}