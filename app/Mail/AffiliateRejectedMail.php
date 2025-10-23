<?php

namespace App\Mail;

use App\Models\AffiliateApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AffiliateRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $adminNotes;

    /**
     * Create a new message instance.
     */
    public function __construct(AffiliateApplication $application, $adminNotes = null)
    {
        $this->application = $application;
        $this->adminNotes = $adminNotes;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Affiliate Application Update - ' . config('app.name'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.affiliate-rejected',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
