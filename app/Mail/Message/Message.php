<?php

namespace App\Mail\Message;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Message extends Mailable
{
    use Queueable, SerializesModels;
    protected $content;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($content)
    {
        //
        $this->content=$content;
    }

    /**
     * Build the message.
     *s
     * @return $this
     */
    public function build()
    {
        return $this->view('Email.email')->subject("问题反馈")->with("content",$this->content);//默认使用config的发送邮箱
    }


}
