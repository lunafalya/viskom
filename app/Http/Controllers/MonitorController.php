<?php

namespace App\Http\Controllers;

use App\Models\MonitoringSession;
use Illuminate\Http\Request;

class MonitorController extends Controller
{
    /**
     * Start a new monitoring session.
     * Called via AJAX when user clicks "Start Monitoring".
     */
    public function start(Request $request)
    {
        $session = MonitoringSession::create([
            'user_id' => $request->user()->id,
            'started_at' => now(),
            'status' => 'active',
        ]);

        return response()->json([
            'session_id' => $session->id,
            'started_at' => $session->started_at->toIso8601String(),
        ]);
    }

    /**
     * Stop a monitoring session.
     * Called via AJAX when user clicks "Stop Monitoring" or on page unload.
     */
    public function stop(Request $request)
    {
        $request->validate([
            'session_id' => 'required|integer',
            'duration_seconds' => 'required|integer|min:0',
            'microsleep_count' => 'required|integer|min:0',
            'perclos_alerts' => 'required|integer|min:0',
            'yawn_count' => 'required|integer|min:0',
        ]);

        $session = MonitoringSession::where('id', $request->session_id)
            ->where('user_id', $request->user()->id)
            ->where('status', 'active')
            ->first();

        if (!$session) {
            return response()->json(['error' => 'Session not found'], 404);
        }

        $session->update([
            'ended_at' => now(),
            'duration_seconds' => $request->duration_seconds,
            'microsleep_count' => $request->microsleep_count,
            'perclos_alerts' => $request->perclos_alerts,
            'yawn_count' => $request->yawn_count,
            'status' => 'completed',
        ]);

        return response()->json(['status' => 'ok']);
    }
}
