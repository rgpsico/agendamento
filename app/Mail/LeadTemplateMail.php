<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LeadTemplateMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $assuntoRendered;
    public string $corpoRendered;

    public function __construct(public Lead $lead, public EmailTemplate $template)
    {
        $this->assuntoRendered = $template->renderAssunto($lead);
        $this->corpoRendered   = $template->renderCorpo($lead);
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->assuntoRendered);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.leads.template');
    }
}
