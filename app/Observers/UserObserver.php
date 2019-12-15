<?php

namespace App\Observers;

use App\Mail\OAuth\Welcome;
use App\Model\User;
use Illuminate\Support\Facades\Mail;

class UserObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Model\User $user
     * @return void
     * @throws \Exception
     */
    public function created(User $user)
    {
//        try {
            Mail::to($user->email)->later(env('MAIL_TIME'), new Welcome($user));
            \App\Utils\Logs::logInfo('创建用户成功! 注册邮件发送成功!', [$user]);
//        } catch (\Exception $e) {
//            \App\Utils\Logs::logError('注册邮件发送失败!', [$e->getMessage()]);
//        }
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Model\User $user
     * @return void
     */
    public function updated(User $user)
    {
        //
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param  \App\Model\User $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the user "restored" event.
     *
     * @param  \App\Model\User $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param  \App\Model\User $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
