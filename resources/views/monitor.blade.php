@extends('layouts.app')

@section('content')

<style>
/* ===== MONITOR PAGE ===== */
.monitor-wrapper{
    position: relative;
    width: 100%;
    height: calc(100vh - 90px);
    border-radius: 18px;
    overflow: hidden;
    background: #07111f;
}

/* background image / camera */
.monitor-camera{
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: brightness(.35);
}

/* overlay */
.monitor-overlay{
    position: absolute;
    inset: 0;
    padding: 30px;
}

/* top right status */
.status-box{
    position: absolute;
    right: 30px;
    top: 30px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.status-item{
    padding: 16px 22px;
    border-radius: 14px;
    backdrop-filter: blur(10px);
    color: white;
    font-size: 18px;
    border: 1px solid rgba(255,255,255,.08);
}

.status-blue{
    background: rgba(0,70,255,.18);
}

.status-dark{
    background: rgba(255,255,255,.08);
}

/* center face scan */
.scan-box{
    position: absolute;
    top: 50%;
    left: 50%;
    width: 320px;
    height: 420px;
    transform: translate(-50%,-50%);
    border: 3px solid #0066ff;
    border-radius: 14px;
    box-shadow: 0 0 25px rgba(0,102,255,.3);
}

.scan-label{
    position: absolute;
    top: -35px;
    left: 0;
    color: #0d6efd;
    font-size: 22px;
    font-weight: 600;
}

/* bottom cards */
.bottom-panel{
    position: absolute;
    bottom: 30px;
    left: 25px;
    right: 25px;
    display: flex;
    justify-content: space-between;
    align-items: end;
    gap: 18px;
}

.stats{
    display: flex;
    gap: 18px;
}

.stat-card{
    min-width: 190px;
    padding: 20px;
    border-radius: 16px;
    background: rgba(255,255,255,.05);
    border: 1px solid rgba(255,255,255,.08);
    backdrop-filter: blur(10px);
    color: white;
}

.stat-card h4{
    font-size: 14px;
    letter-spacing: 1px;
    color: #cbd5e1;
    margin-bottom: 10px;
}

.stat-card h2{
    font-size: 30px;
}

.stat-card small{
    color: #67ff9d;
}

.driver-info{
    color: white;
    text-align: center;
    font-size: 22px;
}

.driver-info span{
    font-size: 15px;
    opacity: .8;
}

/* stop button */
.stop-btn{
    background: #d90429;
    color: white;
    border: none;
    padding: 18px 38px;
    border-radius: 16px;
    font-size: 22px;
    cursor: pointer;
    font-weight: 600;
}

.stop-btn:hover{
    background: #b00020;
}

/* responsive */
@media(max-width:1100px){
    .bottom-panel{
        flex-direction: column;
        align-items: stretch;
    }

    .stats{
        flex-wrap: wrap;
    }

    .scan-box{
        width: 250px;
        height: 340px;
    }
}
</style>


<div class="monitor-wrapper">

    {{-- CAMERA --}}
    <img src="{{ asset('assets/img/driver.jpg') }}" class="monitor-camera">

    <div class="monitor-overlay">

        {{-- TOP STATUS --}}
        <div class="status-box">
            <div class="status-item status-blue">● Neural Processing Active</div>
            <div class="status-item status-dark">☁ Cloud Sync Enabled</div>
        </div>

        {{-- FACE DETECTION BOX --}}
        <div class="scan-box">
            <div class="scan-label">FACIAL_ID_LOCKED</div>
        </div>

        {{-- BOTTOM --}}
        <div class="bottom-panel">

            <div class="stats">

                <div class="stat-card">
                    <h4>ATTENTION</h4>
                    <h2>98%</h2>
                    <small>Optimal</small>
                </div>

                <div class="stat-card">
                    <h4>BLINK RATE</h4>
                    <h2>12/m</h2>
                    <small>Normal</small>
                </div>

                <div class="stat-card">
                    <h4>HEAD POSE</h4>
                    <h2>Center</h2>
                    <small>Aligned</small>
                </div>

            </div>

            <div class="driver-info">
                Driver ID : JD-8821 <br>
                <span>Session: 02h 14m</span>
            </div>

            <button class="stop-btn">
                ⏹ Stop Monitoring
            </button>

        </div>

    </div>

</div>

@endsection