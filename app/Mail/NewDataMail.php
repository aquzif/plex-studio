<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewDataMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public $results) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nowości w Plex Studio',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-data',

        );
    }

    public function attachments(): array
    {
        return [];
    }
}
