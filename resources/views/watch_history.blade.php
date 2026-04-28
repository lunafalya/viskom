@extends('layouts.app')

@section('content')

<style>
.history-page{
    padding: 10px;
}

.top-summary{
    display: grid;
    grid-template-columns: repeat(6,1fr);
    gap: 18px;
    margin-bottom: 24px;
}

.summary-card{
    background: #fff;
    padding: 22px;
    border-radius: 18px;
    border: 1px solid #eef2f7;
    box-shadow: 0 4px 15px rgba(0,0,0,.04);
}

.summary-card small{
    display:block;
    color:#6b7280;
    margin-bottom:8px;
    font-size:13px;
}

.summary-card h2{
    font-size:34px;
    color:#111827;
}

.summary-btn{
    display:flex;
    align-items:center;
    justify-content:center;
    text-decoration:none;
    font-weight:600;
    font-size:16px;
}

.btn-blue{
    background:#1d4ed8;
    color:white;
}

.main-grid{
    display:grid;
    grid-template-columns: 2fr 1fr;
    gap:24px;
}

.video-card,
.side-card{
    background:#fff;
    border-radius:18px;
    border:1px solid #eef2f7;
    box-shadow:0 6px 18px rgba(0,0,0,.04);
    overflow:hidden;
}

.video-preview{
    width:100%;
    height:420px;
    object-fit:cover;
}

.video-info{
    padding:20px;
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:16px;
}

.metric{
    padding:18px;
    border-radius:14px;
    background:#f8fafc;
    border-left:4px solid #1d4ed8;
}

.metric h4{
    color:#64748b;
    font-size:14px;
    margin-bottom:8px;
}

.metric h2{
    font-size:34px;
}

.side-head{
    padding:22px;
    border-bottom:1px solid #eef2f7;
    font-size:22px;
    font-weight:700;
}

.timeline{
    padding:18px;
    display:flex;
    flex-direction:column;
    gap:16px;
}

.event{
    padding:18px;
    border-radius:16px;
    background:#f8fafc;
    border:1px solid #e5e7eb;
}

.event.red{
    background:#fff1f2;
    border-color:#ef4444;
}

.event h5{
    color:#dc2626;
    font-size:14px;
    margin-bottom:10px;
}

.event p{
    color:#334155;
    line-height:1.6;
    margin-bottom:8px;
}

.event span{
    font-size:13px;
    color:#64748b;
}

.notes{
    margin-top:8px;
    padding:18px;
    background:#eff6ff;
    border-radius:16px;
    color:#334155;
    font-size:14px;
}

.pdf-btn{
    display:block;
    text-align:center;
    margin:18px;
    padding:16px;
    border-radius:14px;
    text-decoration:none;
    background:#1d4ed8;
    color:white;
    font-weight:600;
}

@media(max-width:1100px){

    .top-summary{
        grid-template-columns:repeat(2,1fr);
    }

    .main-grid{
        grid-template-columns:1fr;
    }

    .video-info{
        grid-template-columns:1fr;
    }
}
</style>

<div class="history-page">

    {{-- TOP SUMMARY --}}
    <div class="top-summary">

        <div class="summary-card">
            <small>SESSION START</small>
            <h2>08:15 AM</h2>
        </div>

        <div class="summary-card">
            <small>SESSION END</small>
            <h2>11:42 AM</h2>
        </div>

        <div class="summary-card">
            <small>DURATION</small>
            <h2>3h 27m</h2>
        </div>

        <div class="summary-card">
            <small>TOTAL ALERTS</small>
            <h2 style="color:#dc2626;">4 ⚠</h2>
        </div>

        <a href="#" class="summary-card summary-btn">
            ⬇ Download Logs
        </a>

        <a href="#" class="summary-card summary-btn btn-blue">
            🔗 Share Report
        </a>

    </div>


    {{-- MAIN GRID --}}
    <div class="main-grid">

        {{-- LEFT --}}
        <div>

            <div class="video-card">
                <img src="{{ asset('assets/img/road.jpg') }}" class="video-preview">

                <div class="video-info">

                    <div class="metric">
                        <h4>G-FORCE</h4>
                        <h2>1.2 G</h2>
                    </div>

                    <div class="metric" style="border-color:#16a34a;">
                        <h4>SMOOTHNESS</h4>
                        <h2>94%</h2>
                    </div>

                    <div class="metric">
                        <h4>ROUTE PROGRESS</h4>
                        <h2>42 mi</h2>
                    </div>

                </div>
            </div>

        </div>


        {{-- RIGHT --}}
        <div class="side-card">

            <div class="side-head">
                📅 Event Timeline
            </div>

            <div class="timeline">

                <div class="event red">
                    <h5>CRITICAL DROWSINESS • 02:15</h5>
                    <p>Eyes closed for more than 2 seconds. Alarm triggered.</p>
                    <span>Jump to clip</span>
                </div>

                <div class="event">
                    <h5>MINOR DROWSINESS • 00:42</h5>
                    <p>Increased blink frequency detected.</p>
                    <span>View clip</span>
                </div>

                <div class="event">
                    <h5>DISTRACTION • 03:05</h5>
                    <p>Gaze diverted from road for 3.5 seconds.</p>
                    <span>View clip</span>
                </div>

                <div class="event">
                    <h5>SUDDEN BRAKING • 03:52</h5>
                    <p>High deceleration force detected.</p>
                    <span>View clip</span>
                </div>

                <div class="notes">
                    <strong>Session Notes</strong><br><br>
                    Night drive segment. Rain conditions increased visibility difficulty.
                    Driver remained within safety thresholds for 98% of duration.
                </div>

            </div>

            <a href="#" class="pdf-btn">
                📄 Generate PDF Analysis
            </a>

        </div>

    </div>

</div>

@endsection