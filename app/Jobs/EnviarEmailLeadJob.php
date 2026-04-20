<?php

namespace App\Jobs;

use App\Mail\LeadProspeccaoMail;
use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class EnviarEmailLeadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(public Lead $lead) {}

    public function handle(): void
    {
        if (empty($this->lead->email)) return;

        if (empty($this->lead->token)) {
            $this->lead->update(['token' => \Illuminate\Support\Str::uuid()]);
            $this->lead->refresh();
        }

        Mail::to($this->lead->email)->send(new LeadProspeccaoMail($this->lead));

        $this->lead->update(['email_enviado_em' => now()]);
    }
}
