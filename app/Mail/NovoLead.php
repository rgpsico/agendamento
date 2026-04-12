<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NovoLead extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public array $data, public string $ip) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎯 Novo lead: ' . $this->data['nome'],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.novo-lead',
        );
    }
}