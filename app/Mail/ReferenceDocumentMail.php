<?php

namespace App\Mail;

use App\Models\Reference;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class ReferenceDocumentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reference;

    public function __construct(Reference $reference)
    {
//        dd(storage_path('app/public/' . $reference->document_path));

        $this->reference = $reference;
    }

    public function build()
    {
        return $this->subject('Reference Document from Lecturer')
            ->view('emails.reference_document')
            ->attach(storage_path('app/public/' . $this->reference->document_path));
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            replyTo: [
                new Address($this->reference->lecturer->email, $this->reference->lecturer->name)
            ],
            subject: 'Reference Document Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reference_document',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            storage_path('app/public/' . $this->reference->document_path),
        ];
    }
}
