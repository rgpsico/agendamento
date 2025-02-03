<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TwilioService;

class TwilioController extends Controller
{
    public function sendWhatsApp(Request $request, TwilioService $twilioService)
    {
        $request->validate([
            'to' => 'required|string',
            'message' => 'required|string',
        ]);

        $twilioService->sendWhatsAppMessage($request->to, $request->message);

        return response()->json([
            'status' => 'success',
            'message' => 'Mensagem enviada via WhatsApp!'
        ]);
    }
}
