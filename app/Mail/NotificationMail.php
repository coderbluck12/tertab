<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $title;
    public $body;
    public $button_text;
    public $button_url;

    public function __construct($title, $body, $button_text = null, $button_url = null)
    {
        $this->title = $title;
        $this->body = $body;
        $this->button_text = $button_text;
        $this->button_url = $button_url;
    }

    public function build()
    {
        return $this->subject($this->title)
            ->view('emails.notification')
            ->with([
                'title' => $this->title,
                'body' => $this->body,
                'button_text' => $this->button_text,
                'button_url' => $this->button_url,
            ]);
    }
}
