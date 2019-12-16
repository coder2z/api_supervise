<?php

namespace App\Jobs\FeedBack;

use http\Env\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Model\FeedBack;
use Illuminate\Support\Facades\Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $user;
    protected $content;

    /**
     * Create a new job instance.
     *
     * @param $user
     */
    public function __construct($user,$content)
    {
        //
        $this->user = $user;
        $this->content = $content;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
            $user = $this->user;
            $content = $this->content;
        Mail::send('feedback',['content'=>$content,],function($message) use ($user){
            $message ->to($user)->subject('反馈信息');
        });


    }
}
