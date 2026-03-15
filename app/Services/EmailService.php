<?php 

namespace App\Services;

use App\Mail\AgendamentoMail;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    public function enviarAgendamento($dados, $emailDestino)
    {
        Mail::to($emailDestino)->send(new AgendamentoMail($dados));
    }
}


?>