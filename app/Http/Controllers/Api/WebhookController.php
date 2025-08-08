<?php

namespace App\Http\Controllers\Api;

use Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class WebhookController extends Controller
{
    public function receiver(Request $request)
    {
        // Log or process the webhook payload
        Log::info('Webhook received:', $request->all());

        // You can validate or act on the payload here

        return response()->json(['status' => 'received'], 200);
    }
    public function subscribe(Request $request)
    {
        $accountcodes = [
            "40202140-20-72-1",
            // Paste all your other codes here...
        ];

        $callbackUrl = 'http://192.168.61.145/api/webhook/receiver';

        foreach ($accountcodes as $accountcode) {
            $subscribeUrl = "http://192.168.9.150/webhook/subscribe";
            $response = Http::get($subscribeUrl, [
                'accountcode' => $accountcode,
                'callbackurl' => $callbackUrl
            ]);

            // check for failed requests
            if (!$response->successful()) {
                Log::warning("Subscription failed for accountcode: {$accountcode}", [
                    'response' => $response->body()
                ]);
            }
        }

        return response()->json(['status' => 'subscribed'], 200);
    }
}
