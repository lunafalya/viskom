@extends('layouts.app')

@section('content')
<style>
.history-page { padding: 10px; }
.stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 28px; }
.stat-card-h { background: var(--bg-card); border-radius: 14px; padding: 20px 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.04); border: 1px solid var(--border-card); }
.stat-card-h .stat-label { font-size: 11px; letter-spacing: 1.5px; color: var(--text-secondary); font-weight: 600; text-transform: uppercase; }
.stat-card-h h2 { font-size: 32px; font-weight: 700; margin: 8px 0 4px 0; color: var(--text-heading); }
.stat-card-h .stat-info { font-size: 13px; color: var(--text-secondary); }
.history-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
.history-header h2 { font-size: 20px; font-weight: 700; color: var(--text-heading); margin: 0; }
.history-actions { display: flex; gap: 8px; }
.btn-export { padding: 10px 20px; border-radius: 10px; border: 1px solid var(--border-color); background: var(--bg-card); color: var(--text-primary); font-size: 14px; font-weight: 500; cursor: pointer; text-decoration: none; }
.btn-export:hover { background: var(--bg-input); }
.session-list { display: flex; flex-direction: column; gap: 12px; }
.session-card-h { background: var(--bg-card); border-radius: 14px; padding: 20px 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.03); border: 1px solid var(--border-card); display: flex; justify-content: space-between; align-items: center; }
.session-card-h .session-info h3 { font-size: 16px; font-weight: 600; color: var(--text-heading); margin: 0 0 4px 0; }
.session-card-h .session-info small { color: var(--text-secondary); font-size: 13px; }
.session-meta-h { display: flex; gap: 12px; align-items: center; }
.badge-h { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
.badge-h.safe { background: #dcfce7; color: #15803d; }
.badge-h.warning { background: #fef3c7; color: #92400e; }
.badge-h.danger { background: #fee2e2; color: #991b1b; }
.badge-h.neutral { background: #f3f4f6; color: #374151; }
.duration-text { font-size: 14px; color: var(--text-primary); font-weight: 500; }
.empty-state { text-align: center; padding: 60px 20px; color: var(--text-secondary); }
.empty-state h3 { color: var(--text-primary); margin-bottom: 8px; }
.pagination-wrap { margin-top: 24px; display: flex; justify-content: center; }
@media (max-width: 900px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } .session-card-h { flex-direction: column; align-items: flex-start; gap: 12px; } }
</style>

<div class="history-page">

    <div class="stats-grid">
        <div class="stat-card-h">
            <span class="stat-label">Total Drives</span>
            <h2>{{ $totalDrives }}</h2>
            <p class="stat-info">All recorded sessions</p>
        </div>
        <div class="stat-card-h">
            <span class="stat-label">Total Alerts</span>
            <h2>{{ $totalAlerts }}</h2>
            <p class="stat-info">Microsleep + PERCLOS + Yawn</p>
        </div>
        <div class="stat-card-h">
            <span class="stat-label">Safe Time</span>
            <h2>{{ $safeTime }}%</h2>
            <p class="stat-info">Sessions with zero alerts</p>
        </div>
        <div class="stat-card-h">
            <span class="stat-label">Hours Monitored</span>
            <h2>{{ $totalHours }}</h2>
            <p class="stat-info">Across all sessions</p>
        </div>
    </div>

    <div class="history-header">
        <h2>Session History</h2>
        <div class="history-actions">
            <a href="{{ route('history.export') }}" class="btn-export">Export CSV</a>
        </div>
    </div>

    <div class="session-list">
        @forelse ($sessions as $session)
            @php
                $alerts = $session->microsleep_count + $session->perclos_alerts + $session->yawn_count;
                $duration = $session->duration_seconds;
                $hours = floor($duration / 3600);
                $mins = floor(($duration % 3600) / 60);
                $secs = $duration % 60;
                $durationStr = $hours > 0 ? "{$hours}h {$mins}m" : ($mins > 0 ? "{$mins}m {$secs}s" : "{$secs}s");

                if ($alerts === 0) {
                    $badgeClass = 'safe';
                    $badgeText = 'Safe';
                } elseif ($session->microsleep_count > 0) {
                    $badgeClass = 'danger';
                    $badgeText = $alerts . ' Critical';
                } else {
                    $badgeClass = 'warning';
                    $badgeText = $alerts . ' Alerts';
                }
            @endphp
            <div class="session-card-h">
                <div class="session-info">
                    <h3>Monitoring Session</h3>
                    <small>{{ $session->started_at->format('M d, Y \a\t H:i') }}</small>
                </div>
                <div class="session-meta-h">
                    <span class="duration-text">{{ $durationStr }}</span>
                    <span class="badge-h {{ $badgeClass }}">{{ $badgeText }}</span>
                    @if($session->microsleep_count > 0)
                        <span class="badge-h danger">{{ $session->microsleep_count }} Microsleep</span>
                    @endif
                    @if($session->yawn_count > 0)
                        <span class="badge-h neutral">{{ $session->yawn_count }} Yawns</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="empty-state">
                <h3>No Sessions Yet</h3>
                <p>Start your first monitoring session from the dashboard.</p>
            </div>
        @endforelse
    </div>

    @if($sessions->hasPages())
    <div class="pagination-wrap">
        {{ $sessions->links() }}
    </div>
    @endif

</div>
@endsection