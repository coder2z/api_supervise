<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
class SendEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;


    /**
     * Create a new job instance.
     *
     * @param $toss
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return SendEmail
     */
    public function build()
    {
        return $this->view('test')->subject('接口改动');
    }
}
