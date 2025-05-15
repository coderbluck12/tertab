<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $title;
    public $message;
    public $button_text;
    public $button_url;

    public function __construct($title, $message, $button_text = null, $button_url = null)
    {
        $this->title = $title;
        $this->message = $message;
        $this->button_text = $button_text;
        $this->button_url = $button_url;
    }

    public function build()
    {
        return $this->subject($this->title)
            ->markdown('emails.notification')
            ->with([
                'title' => $this->title,
                'message' => $this->message,
                'button_text' => $this->button_text,
                'button_url' => $this->button_url,
            ]);
    }
}
