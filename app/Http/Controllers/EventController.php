<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\StoreUserEvent;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function track(Request $req)
    {
        $data = [
            'user_id' => $req->user()?->id,
            'event_type' => $req->input('event_type'),
            'payload' => $req->input('payload', []),
            'ip' => $req->ip(),
            'user_agent' => $req->header('User-Agent'),
            'source' => $req->input('source', 'web'),
        ];

        // Dispatch to fila para não bloquear
        StoreUserEvent::dispatch($data);

        return response()->json(['status' => 'ok']);
    }
}
