<?php

namespace App\Jobs;

use App\Mail\MyMassEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMassEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Lista fixa de e-mails de teste
        $testEmails = [
            'rgyr2010@hotmail.com',
            'gerro121@hotmail.com',
            'rgdogalo10@gmail.com',
            'sample1@example.com',
            'sample2@example.com',
            'sample3@example.com',
            'sample4@example.com',
            'sample5@example.com'
        ];


        foreach ($testEmails as $email) {
            Mail::to($email)->send(new MyMassEmail());
        }
    }
}
