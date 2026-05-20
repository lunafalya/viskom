<?php

namespace App\Http\Controllers;

use App\Models\MonitoringSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class HistoryController extends Controller
{
    /**
     * Display the history page with aggregated stats and paginated sessions.
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $sessions = MonitoringSession::where('user_id', $userId)
            ->where('status', 'completed')
            ->orderByDesc('started_at')
            ->paginate(10);

        $allSessions = MonitoringSession::where('user_id', $userId)
            ->where('status', 'completed');

        $totalDrives = $allSessions->count();
        $totalAlerts = $allSessions->sum('microsleep_count')
            + $allSessions->sum('perclos_alerts')
            + $allSessions->sum('yawn_count');
        $safeSessions = (clone $allSessions)
            ->whereRaw('microsleep_count + perclos_alerts + yawn_count = 0')
            ->count();
        $safeTime = $totalDrives > 0 ? round(($safeSessions / $totalDrives) * 100, 1) : 0;
        $totalHours = round($allSessions->sum('duration_seconds') / 3600, 1);

        return view('history', compact(
            'sessions', 'totalDrives', 'totalAlerts', 'safeTime', 'totalHours'
        ));
    }

    /**
     * Export sessions as CSV.
     */
    public function export(Request $request)
    {
        $sessions = MonitoringSession::where('user_id', $request->user()->id)
            ->where('status', 'completed')
            ->orderByDesc('started_at')
            ->get();

        $csv = "Date,Duration (min),Microsleep,PERCLOS Alerts,Yawns,Total Alerts,Status\n";
        foreach ($sessions as $s) {
            $date = $s->started_at->format('Y-m-d H:i');
            $duration = round($s->duration_seconds / 60, 1);
            $total = $s->microsleep_count + $s->perclos_alerts + $s->yawn_count;
            $csv .= "{$date},{$duration},{$s->microsleep_count},{$s->perclos_alerts},{$s->yawn_count},{$total},{$s->status}\n";
        }

        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="session_history_' . date('Y-m-d') . '.csv"',
        ]);
    }
}
