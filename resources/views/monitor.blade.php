@extends('layouts.app')

@section('content')

<style>
.monitor-wrapper { position:relative; width:100%; height:calc(100vh - 90px); border-radius:18px; overflow:hidden; background:#000; }
.monitor-camera { width:100%; height:100%; object-fit:cover; transform:scaleX(-1); }
.monitor-overlay-canvas { position:absolute; top:0; left:0; width:100%; height:100%; z-index:5; pointer-events:none; transform:scaleX(-1); }
#capture-canvas { display:none; }
.monitor-hud { position:absolute; inset:0; padding:24px; z-index:10; pointer-events:none; }
.monitor-hud .clickable { pointer-events:auto; }
.hud-top { display:flex; justify-content:space-between; align-items:flex-start; }
.hud-top-left { display:flex; flex-direction:column; gap:6px; }
.connection-badge { display:inline-flex; align-items:center; gap:8px; padding:8px 16px; border-radius:10px; background:rgba(0,0,0,0.55); backdrop-filter:blur(12px); color:#fff; font-size:13px; font-weight:500; border:1px solid rgba(255,255,255,0.06); }
.connection-dot { width:8px; height:8px; border-radius:50%; background:#555; transition:background 0.3s; }
.connection-dot.connected { background:#34d399; box-shadow:0 0 8px #34d39966; }
.connection-dot.calibrating { background:#fbbf24; box-shadow:0 0 8px #fbbf2466; animation:pulse-dot 1s ease-in-out infinite; }
.connection-dot.error { background:#f87171; box-shadow:0 0 8px #f8717166; }
@keyframes pulse-dot { 0%,100%{opacity:1;} 50%{opacity:0.4;} }
.fps-badge { display:inline-flex; align-items:center; gap:8px; padding:8px 16px; border-radius:10px; background:rgba(0,0,0,0.55); backdrop-filter:blur(12px); color:#94a3b8; font-size:12px; font-family:monospace; border:1px solid rgba(255,255,255,0.06); }
.hud-top-right { display:flex; gap:8px; }
.dev-badge { padding:8px 14px; border-radius:10px; background:rgba(139,92,246,0.3); backdrop-filter:blur(12px); color:#c4b5fd; font-size:12px; font-weight:600; letter-spacing:1px; border:1px solid rgba(139,92,246,0.3); }
.calibration-overlay { position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); text-align:center; z-index:15; pointer-events:none; opacity:0; transition:opacity 0.4s; }
.calibration-overlay.visible { opacity:1; }
.calibration-ring { width:160px; height:160px; border-radius:50%; border:3px solid rgba(251,191,36,0.4); display:flex; align-items:center; justify-content:center; margin:0 auto 16px; position:relative; }
.calibration-ring svg { position:absolute; top:-3px; left:-3px; width:166px; height:166px; transform:rotate(-90deg); }
.calibration-ring svg circle { fill:none; stroke:#fbbf24; stroke-width:3; stroke-linecap:round; stroke-dasharray:502; stroke-dashoffset:502; transition:stroke-dashoffset 0.15s linear; }
.calibration-text { color:#fbbf24; font-size:28px; font-weight:700; }
.calibration-sub { color:rgba(255,255,255,0.7); font-size:14px; margin-top:4px; }
.hud-bottom { position:absolute; bottom:24px; left:24px; right:24px; display:flex; justify-content:space-between; align-items:flex-end; gap:16px; }
.stat-cards { display:flex; gap:12px; }
.stat-card { min-width:170px; padding:16px 20px; border-radius:14px; background:rgba(0,0,0,0.55); backdrop-filter:blur(14px); color:white; border:1px solid rgba(255,255,255,0.06); transition:background 0.4s, border-color 0.4s; }
.stat-card h4 { font-size:11px; letter-spacing:1.5px; color:#94a3b8; margin:0 0 8px 0; font-weight:600; }
.stat-card .stat-value { font-size:24px; font-weight:700; margin:0; transition:color 0.3s; }
.stat-card .stat-sub { font-size:12px; margin-top:4px; transition:color 0.3s; color:#34d399; }
.stat-sub.warning { color:#f87171 !important; }
.stat-card.danger { background:rgba(220,38,38,0.5); border-color:rgba(248,113,113,0.4); }
.stat-card.calibrating { background:rgba(180,130,0,0.35); border-color:rgba(251,191,36,0.3); }
.control-btn { padding:14px 32px; border-radius:14px; font-size:16px; font-weight:600; cursor:pointer; border:none; transition:background 0.3s, transform 0.15s; pointer-events:auto; flex-shrink:0; }
.control-btn:active { transform:scale(0.96); }
.control-btn.stop { background:#dc2626; color:white; }
.control-btn.stop:hover { background:#b91c1c; }
.control-btn.start { background:#059669; color:white; }
.control-btn.start:hover { background:#047857; }
@media(max-width:900px){ .hud-bottom{flex-direction:column;align-items:stretch;} .stat-cards{flex-wrap:wrap;} .stat-card{min-width:140px;flex:1;} }
</style>

<div class="monitor-wrapper">
    <video id="webcam" class="monitor-camera" autoplay playsinline muted></video>
    <canvas id="capture-canvas"></canvas>
    <canvas id="overlay-canvas" class="monitor-overlay-canvas"></canvas>

    <div class="monitor-hud">
        <div class="hud-top">
            <div class="hud-top-left">
                <div class="connection-badge">
                    <span class="connection-dot" id="conn-dot"></span>
                    <span id="conn-text">Memulai...</span>
                </div>
                <div class="fps-badge" id="fps-badge" style="display:none;">
                    <span id="fps-value">-- FPS</span>
                </div>
            </div>
            <div class="hud-top-right" id="dev-area"></div>
        </div>

        <div class="calibration-overlay" id="calibration-overlay">
            <div class="calibration-ring">
                <svg viewBox="0 0 166 166"><circle id="cal-progress" cx="83" cy="83" r="80"></circle></svg>
                <span class="calibration-text" id="cal-text">0%</span>
            </div>
            <div class="calibration-sub">Tahan wajah Anda tetap diam...</div>
        </div>

        <div class="hud-bottom">
            <div class="stat-cards">
                <div class="stat-card" id="card-status">
                    <h4>STATUS</h4>
                    <div class="stat-value" id="ui-status">OFFLINE</div>
                    <div class="stat-sub" id="ui-status-sub">Menunggu koneksi</div>
                </div>
                <div class="stat-card">
                    <h4>MATA</h4>
                    <div class="stat-value" id="ui-eye">--</div>
                    <div class="stat-sub" id="ui-eye-sub">--</div>
                </div>
                <div class="stat-card">
                    <h4>MULUT</h4>
                    <div class="stat-value" id="ui-mouth">--</div>
                    <div class="stat-sub" id="ui-mouth-sub">--</div>
                </div>
            </div>
            <button class="control-btn stop clickable" id="control-btn">Stop Monitoring</button>
        </div>
    </div>
</div>

{{-- Visual warning overlay --}}
<div id="warning-overlay" style="
    position:fixed;top:0;left:0;width:100%;height:100%;
    background:rgba(220,38,38,0.35);z-index:9999;
    pointer-events:none;opacity:0;transition:opacity 0.15s;
"></div>

<script>
    // === CONFIG (from user settings) ===
    var WS_URL = "ws://localhost:8001/ws/monitor";
    var INTERVAL = 200;
    var isDevMode = @json($settings->dev_mode ?? false);
    var alarmEnabled = @json($settings->alarm_enabled ?? true);
    var visualWarning = @json($settings->visual_warning ?? true);
    var warningOverlay = document.getElementById('warning-overlay');

    // === DOM ===
    var video = document.getElementById("webcam");
    var capCanvas = document.getElementById("capture-canvas");
    var capCtx = capCanvas.getContext("2d");
    var overlay = document.getElementById("overlay-canvas");
    var overlayCtx = overlay.getContext("2d");
    var connDot = document.getElementById("conn-dot");
    var connText = document.getElementById("conn-text");
    var fpsBadge = document.getElementById("fps-badge");
    var fpsValue = document.getElementById("fps-value");
    var calOverlay = document.getElementById("calibration-overlay");
    var calProgress = document.getElementById("cal-progress");
    var calText = document.getElementById("cal-text");
    var cardStatus = document.getElementById("card-status");
    var uiStatus = document.getElementById("ui-status");
    var uiStatusSub = document.getElementById("ui-status-sub");
    var uiEye = document.getElementById("ui-eye");
    var uiEyeSub = document.getElementById("ui-eye-sub");
    var uiMouth = document.getElementById("ui-mouth");
    var uiMouthSub = document.getElementById("ui-mouth-sub");
    var controlBtn = document.getElementById("control-btn");
    var devArea = document.getElementById("dev-area");

    if (isDevMode) devArea.innerHTML = '<div class="dev-badge">DEV MODE</div>';

    // === STATE ===
    var ws = null, localStream = null, isMonitoring = false, tid = null, lastT = 0;
    // Academic vigilance state
    var eyeHist = [], contClosed = 0, yawnTs = [], wasYawn = false, lastYawnTime = 0;
    var YAWN_COOLDOWN = 4000;

    // Session persistence state
    var sessionId = null;
    var sessionStartTime = null;
    var alertCounts = { microsleep: 0, perclos: 0, yawn: 0 };
    var csrfToken = document.querySelector('meta[name="csrf-token"]');
    var csrf = csrfToken ? csrfToken.getAttribute('content') : '';

    // === WEB AUDIO API ALARM ===
    var audioCtx = null;
    var activeOsc = null;
    var activeGain = null;
    var alarmLevel = 0;
    var alarmInterval = null;

    function getAudioCtx() {
        if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        return audioCtx;
    }

    function stopAlarm() {
        alarmLevel = 0;
        if (alarmInterval) { clearTimeout(alarmInterval); clearInterval(alarmInterval); alarmInterval = null; }
        if (activeOsc) { try { activeOsc.stop(0); } catch(e){} activeOsc = null; }
        if (activeGain) { try { activeGain.disconnect(); } catch(e){} activeGain = null; }
        // Kill the audio context entirely to stop any scheduled oscillators
        if (audioCtx) { try { audioCtx.close(); } catch(e){} audioCtx = null; }
    }

    // Level 1: 3 short beeps at 800Hz (WASPADA)
    function playAlarmLevel1() {
        if (!alarmEnabled || alarmLevel >= 1) return;
        stopAlarm();
        alarmLevel = 1;
        var ctx = getAudioCtx();
        var beepCount = 0;
        alarmInterval = setInterval(function() {
            if (beepCount >= 3) { clearInterval(alarmInterval); alarmInterval = null; return; }
            var osc = ctx.createOscillator();
            var gain = ctx.createGain();
            osc.type = 'sine'; osc.frequency.value = 800;
            gain.gain.value = 0.3;
            osc.connect(gain); gain.connect(ctx.destination);
            osc.start(); osc.stop(ctx.currentTime + 0.2);
            beepCount++;
        }, 300);
    }

    // Level 2: Pulsing 1000Hz tone (BAHAYA - microsleep)
    function playAlarmLevel2() {
        if (!alarmEnabled || alarmLevel >= 2) return;
        stopAlarm();
        alarmLevel = 2;
        var ctx = getAudioCtx();
        var on = true;
        function pulse() {
            if (alarmLevel !== 2) return;
            if (on) {
                activeOsc = ctx.createOscillator();
                activeGain = ctx.createGain();
                activeOsc.type = 'sine'; activeOsc.frequency.value = 1000;
                activeGain.gain.value = 0.5;
                activeOsc.connect(activeGain); activeGain.connect(ctx.destination);
                activeOsc.start(); activeOsc.stop(ctx.currentTime + 0.5);
            }
            on = !on;
            alarmInterval = setTimeout(pulse, on ? 200 : 500);
        }
        pulse();
    }

    // Level 3: Rapid 1200Hz alarm (BAHAYA - sustained microsleep >=3s)
    function playAlarmLevel3() {
        if (!alarmEnabled || alarmLevel >= 3) return;
        stopAlarm();
        alarmLevel = 3;
        var ctx = getAudioCtx();
        var on = true;
        function rapidPulse() {
            if (alarmLevel !== 3) return;
            if (on) {
                activeOsc = ctx.createOscillator();
                activeGain = ctx.createGain();
                activeOsc.type = 'square'; activeOsc.frequency.value = 1200;
                activeGain.gain.value = 0.6;
                activeOsc.connect(activeGain); activeGain.connect(ctx.destination);
                activeOsc.start(); activeOsc.stop(ctx.currentTime + 0.15);
            }
            on = !on;
            alarmInterval = setTimeout(rapidPulse, 200);
        }
        rapidPulse();
    }

    // Visual warning flash
    var flashTimer = null;
    function flashWarning() {
        if (!visualWarning) return;
        warningOverlay.style.opacity = '1';
        if (flashTimer) clearTimeout(flashTimer);
        flashTimer = setTimeout(function() { warningOverlay.style.opacity = '0'; }, 400);
    }

    function setConn(s, t) {
        connDot.className = "connection-dot " + s;
        connText.textContent = t;
    }

    function resetAll() {
        eyeHist = []; contClosed = 0; yawnTs = []; wasYawn = false; lastT = 0;
        uiStatus.textContent = "OFFLINE";
        uiStatusSub.textContent = "Menunggu koneksi";
        uiEye.textContent = "--"; uiEyeSub.textContent = "--"; uiEyeSub.className = "stat-sub";
        uiMouth.textContent = "--"; uiMouthSub.textContent = "--"; uiMouthSub.className = "stat-sub";
        cardStatus.className = "stat-card";
        calOverlay.classList.remove("visible");
        fpsBadge.style.display = "none";
    }

    // === FRAME SENDER (setInterval -- proven reliable) ===
    function sendFrame() {
        if (!video.videoWidth || !ws || ws.readyState !== 1) return;
        capCanvas.width = video.videoWidth;
        capCanvas.height = video.videoHeight;
        capCtx.drawImage(video, 0, 0);
        ws.send(capCanvas.toDataURL("image/jpeg", 0.7));
    }

    // === WEBSOCKET ===
    function connectWS() {
        setConn("", "Menghubungkan...");
        ws = new WebSocket(WS_URL);

        ws.onopen = function() {
            setConn("connected", "Terhubung");
            fpsBadge.style.display = "flex";
        };

        ws.onmessage = function(ev) {
            var d = JSON.parse(ev.data);
            var now = performance.now();
            var dt = lastT > 0 ? now - lastT : INTERVAL;
            lastT = now;

            if (d.fps) fpsValue.textContent = d.fps + " FPS";
            overlayCtx.clearRect(0, 0, overlay.width, overlay.height);

            // --- CALIBRATION ---
            if (d.status === "CALIBRATING") {
                var p = (d.calibration_progress || 0) / 30;
                var pct = Math.round(p * 100);
                calProgress.style.strokeDashoffset = 502 - (502 * p);
                calText.textContent = pct + "%";
                calOverlay.classList.add("visible");
                setConn("calibrating", "Kalibrasi...");
                cardStatus.className = "stat-card calibrating";
                uiStatus.textContent = "KALIBRASI";
                uiStatusSub.textContent = "Tahan posisi (" + pct + "%)";
                if (isDevMode && d.box) drawBox(d.box, "#fbbf24");
                return;
            }

            calOverlay.classList.remove("visible");
            setConn("connected", "Terhubung");

            // --- EYE: PERCLOS + MICROSLEEP ---
            var closed = d.eye === "Closed";
            var perclos = 0, micro = false;

            if (d.eye !== null) {
                eyeHist.push({ t: now, c: closed });
                while (eyeHist.length && (now - eyeHist[0].t) > 60000) eyeHist.shift();

                if (eyeHist.length > 1) {
                    var ct = 0;
                    for (var i = 1; i < eyeHist.length; i++) {
                        if (eyeHist[i].c) ct += eyeHist[i].t - eyeHist[i - 1].t;
                    }
                    var wt = now - eyeHist[0].t;
                    perclos = wt > 0 ? ct / wt : 0;
                }

                if (closed) { contClosed += dt; } else { contClosed = 0; }
                micro = contClosed >= 1000;
                if (micro && contClosed - dt < 1000) alertCounts.microsleep++;

                uiEye.textContent = closed ? "Terpejam" : "Terbuka";
                if (micro) {
                    uiEyeSub.textContent = "Microsleep! (" + (contClosed / 1000).toFixed(1) + "s)";
                    uiEyeSub.className = "stat-sub warning";
                } else if (perclos >= 0.15) {
                    uiEyeSub.textContent = "PERCLOS " + (perclos * 100).toFixed(0) + "%";
                    uiEyeSub.className = "stat-sub warning";
                } else {
                    uiEyeSub.textContent = "Normal (" + (perclos * 100).toFixed(0) + "%)";
                    uiEyeSub.className = "stat-sub";
                }
            }

            // --- MOUTH: YAWN PER MINUTE ---
            var ypm = 0;
            if (d.mouth !== null) {
                if (d.mouth === "Yawning") {
                    // Only register a new yawn if cooldown has passed since the last one
                    if (!wasYawn && (now - lastYawnTime) > YAWN_COOLDOWN) {
                        yawnTs.push(now);
                        lastYawnTime = now;
                        alertCounts.yawn++;
                    }
                    wasYawn = true;
                } else { wasYawn = false; }

                while (yawnTs.length && (now - yawnTs[0]) > 60000) yawnTs.shift();
                ypm = yawnTs.length;

                uiMouth.textContent = d.mouth === "Yawning" ? "Menguap" : "Tertutup";
                uiMouthSub.textContent = ypm + " kali/menit";
                uiMouthSub.className = ypm > 1 ? "stat-sub warning" : "stat-sub";
            }

            // --- MAIN STATUS + ALARM ---
            if (micro) {
                cardStatus.className = "stat-card danger";
                uiStatus.textContent = "BAHAYA";
                uiStatusSub.textContent = "Microsleep! (" + (contClosed / 1000).toFixed(1) + "s)";
                uiStatusSub.className = "stat-sub warning";
                if (contClosed >= 3000) { playAlarmLevel3(); flashWarning(); }
                else { playAlarmLevel2(); flashWarning(); }
            } else if (perclos >= 0.15) {
                cardStatus.className = "stat-card danger";
                uiStatus.textContent = "BAHAYA";
                uiStatusSub.textContent = "PERCLOS " + (perclos * 100).toFixed(0) + "%";
                uiStatusSub.className = "stat-sub warning";
                playAlarmLevel1();
                alertCounts.perclos++;
            } else if (ypm > 1) {
                cardStatus.className = "stat-card danger";
                uiStatus.textContent = "WASPADA";
                uiStatusSub.textContent = "Menguap " + ypm + "x/menit";
                uiStatusSub.className = "stat-sub warning";
                playAlarmLevel1();
            } else {
                cardStatus.className = "stat-card";
                uiStatus.textContent = "AMAN";
                uiStatusSub.textContent = "Fokus Optimal";
                uiStatusSub.className = "stat-sub";
                stopAlarm();
                warningOverlay.style.opacity = '0';
            }

            // --- DEV MODE ---
            if (isDevMode && d.box) {
                drawBox(d.box, micro || perclos >= 0.15 ? "#f87171" : "#3b82f6");
            }
        };

        ws.onclose = function() {
            setConn("error", "Terputus");
            fpsBadge.style.display = "none";
            if (isMonitoring) setTimeout(connectWS, 2000);
        };

        ws.onerror = function() {
            setConn("error", "Error koneksi");
        };
    }

    function drawBox(b, c) {
        var sx = overlay.width / video.videoWidth;
        var sy = overlay.height / video.videoHeight;
        overlayCtx.strokeStyle = c;
        overlayCtx.lineWidth = 2;
        overlayCtx.setLineDash([6, 4]);
        overlayCtx.strokeRect(b[0] * sx, b[1] * sy, b[2] * sx, b[3] * sy);
        overlayCtx.setLineDash([]);
    }

    // === CAMERA ===
    function startCamera() {
        navigator.mediaDevices.getUserMedia({ video: true })
        .then(function(stream) {
            localStream = stream;
            video.srcObject = stream;
            isMonitoring = true;
            controlBtn.textContent = "Stop Monitoring";
            controlBtn.className = "control-btn stop clickable";
            resetAll();
            alertCounts = { microsleep: 0, perclos: 0, yawn: 0 };

            // Create session in DB
            fetch('/api/monitor/start', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
            }).then(function(r) { return r.json(); }).then(function(d) {
                sessionId = d.session_id;
                sessionStartTime = Date.now();
                console.log('[viskom] Session started:', sessionId);
            }).catch(function(e) { console.error('Session start error:', e); });

            connectWS();
            tid = setInterval(sendFrame, INTERVAL);

            video.onloadedmetadata = function() {
                overlay.width = video.videoWidth;
                overlay.height = video.videoHeight;
            };
        })
        .catch(function(err) {
            console.error("Camera error:", err);
            setConn("error", "Kamera: " + err.message);
        });
    }

    function saveSession() {
        if (!sessionId || !sessionStartTime) return;
        var duration = Math.round((Date.now() - sessionStartTime) / 1000);
        fetch('/api/monitor/stop', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify({
                session_id: sessionId,
                duration_seconds: duration,
                microsleep_count: alertCounts.microsleep,
                perclos_alerts: alertCounts.perclos,
                yawn_count: alertCounts.yawn,
            }),
            keepalive: true,  // Ensures request completes even on page unload
        });
        console.log('[viskom] Session saved:', sessionId, duration + 's');
        sessionId = null;
        sessionStartTime = null;
    }

    function stopAll() {
        isMonitoring = false;
        if (tid) { clearInterval(tid); tid = null; }
        if (ws) { ws.close(); ws = null; }
        if (localStream) {
            localStream.getTracks().forEach(function(t) { t.stop(); });
            video.srcObject = null;
            localStream = null;
        }
        stopAlarm();
        warningOverlay.style.opacity = '0';
        saveSession();
        resetAll();
        controlBtn.textContent = "Start Monitoring";
        controlBtn.className = "control-btn start clickable";
    }

    controlBtn.addEventListener("click", function() {
        if (isMonitoring) stopAll(); else startCamera();
    });

    // Save on page close/navigate away
    window.addEventListener('beforeunload', function() {
        if (isMonitoring) saveSession();
    });

    // Auto-start
    startCamera();
</script>

@endsection