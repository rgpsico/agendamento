<?php

namespace App\Mail;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LeadAcessoTrialMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Lead $lead,
        public string $senhaClear
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Seu acesso de teste está pronto!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.leads.acesso-trial',
        );
    }
}
