<?php

namespace App\Jobs\Message;

use App\Utils\Logs;
use App\Mail\Message\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tries = 5;//最大尝试数
    protected $users;
    protected $content;
    public $timeout = 120;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($users, $content)
    {
        $this->users = $users;
        $this->content = $content;
    }

    /**
     * @author niyu
     * Execute the job.
     *
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        try {
            Mail::to(env('MAIL_FROM_ADDRESS', '13890593217@163.com'))->cc($this->users)->send(new Message($this->content));
        } catch (\Exception $exception) {
            Logs::logError('发送邮件过程中出现错误!', [$exception->getMessage()]);
        }
    }

    /**
     * @param \Exception $exception
     * @throws \Exception
     */
    public function failed(\Exception $exception)
    {
        Logs::logError('发送邮件出现错误!', [$exception->getMessage()]);
    }
}
