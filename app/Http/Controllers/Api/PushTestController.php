<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FirebasePushService;
use Illuminate\Http\Request;

class PushTestController extends Controller
{
    /**
     * Envia um push de teste via FCM HTTP v1.
     */
    public function send(Request $request, FirebasePushService $pushService)
    {
        // Validate required payload fields.
        $validated = $request->validate([
            'token' => 'required|string',
            'title' => 'required|string',
            'body' => 'required|string',
            'data' => 'sometimes|array',
        ]);

        // Delegate sending to the service and return a consistent JSON response.
        $result = $pushService->sendToToken(
            $validated['token'],
            $validated['title'],
            $validated['body'],
            $validated['data'] ?? []
        );

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'firebase' => $result['response'],
            ], 200);
        }

        return response()->json([
            'success' => false,
            'error' => $result['error'] ?? 'Firebase request failed',
        ], 502);
    }
}
