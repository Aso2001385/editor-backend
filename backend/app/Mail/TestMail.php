<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $title;
    protected $text;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name,$text)
    {
        $this->title=sprintf('%sさんへ',$name);
        $this->text= $text;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
        ->subject($this->title)
        ->view('emails.test_content')
        ->with([
            'text'=>$this->text,
        ]);
    }
}
