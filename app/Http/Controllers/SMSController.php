<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SMSController extends Controller
{
    /**
     * SMS send API endpoint
     * Triggered by system events to notify students via SIM800L.
     */
    public function send(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'message' => 'required',
        ]);

        // Logic to queue or dispatch to ESP32 gateway
        return response()->json(['status' => 'queued', 'message' => 'SMS sent to gateway']);
    }
}
