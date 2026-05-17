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
    <video id="webcam" class="monitor-camera" autoplay playsinline></video>
    <canvas id="canvas" style="display:none;"></canvas>

    <div class="monitor-overlay">

        {{-- TOP STATUS --}}
        <div class="status-box">
            <div class="status-item status-blue">● Neural Processing Active</div>
            <div class="status-item status-dark">☁ Cloud Sync Enabled</div>
        </div>

        {{-- BOTTOM --}}
        <div class="bottom-panel">

            <div class="stats">

                <div class="stat-card" id="card-kondisi">
                    <h4>STATUS KONDISI</h4>
                    <h2 id="ui-kondisi">AMAN</h2>
                    <small id="ui-pesan">Fokus Optimal</small>
                </div>

                <div class="stat-card">
                    <h4>MATA</h4>
                    <h2 id="ui-mata">Terbuka</h2>
                    <small>Normal</small>
                </div>

                <div class="stat-card">
                    <h4>MULUT</h4>
                    <h2 id="ui-mulut">Tertutup</h2>
                    <small>Normal</small>
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

<script>
    const video = document.getElementById('webcam');
    const canvas = document.getElementById('canvas');
    
    // Ambil elemen UI untuk diupdate
    const uiKondisi = document.getElementById('ui-kondisi');
    const uiPesan = document.getElementById('ui-pesan');
    const uiMata = document.getElementById('ui-mata');
    const uiMulut = document.getElementById('ui-mulut');
    const cardKondisi = document.getElementById('card-kondisi');
    const stopBtn = document.querySelector('.stop-btn'); 

    const API_URL = "https://relieve-mutate-dilation.ngrok-free.dev/predict";

    let localStream = null;
    let detectionInterval = null;
    let isMonitoring = true;

    // 1. Fungsi untuk Menyalakan Kamera (Dibuat fungsi agar bisa dipanggil ulang)
    function startCamera() {
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(function(stream) {
                    localStream = stream;
                    video.srcObject = stream;
                    
                    // Reset UI ke mode awas
                    uiKondisi.innerText = "MENUNGGU DATA";
                    uiPesan.innerText = "Menganalisis...";
                    
                    detectionInterval = setInterval(captureAndPredict, 1000);
                })
                .catch(function(err) {
                    console.error("Kamera tidak dapat diakses: ", err);
                    alert("Harap izinkan akses kamera!");
                    // Kembalikan status jika gagal
                    isMonitoring = false;
                    stopBtn.innerText = "▶ Start Recording";
                });
        }
    }

    // Panggil kamera pertama kali saat halaman dimuat
    startCamera();

    // 2. Fungsi Ambil Frame dan Kirim ke API
    function captureAndPredict() {
        if (!video.videoWidth || !isMonitoring) return;

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        
        const context = canvas.getContext('2d');
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        canvas.toBlob(function(blob) {
            const formData = new FormData();
            formData.append('image', blob, 'frame.jpg');

            fetch(API_URL, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // UPDATE MATA & MULUT (Disesuaikan dengan output Python)
                uiMata.innerText = (data.eye === "Open") ? "Terbuka" : "Terpejam";
                uiMulut.innerText = (data.mouth === "Normal") ? "Tertutup" : "Menguap";

                // UPDATE STATUS
                if(data.drowsy === true) {
                    uiKondisi.innerText = "BAHAYA";
                    uiPesan.innerText = "Anda Mengantuk!";
                    cardKondisi.style.backgroundColor = "rgba(217, 4, 41, 0.7)"; 
                    cardKondisi.style.border = "2px solid red";
                } else {
                    uiKondisi.innerText = "AMAN";
                    uiPesan.innerText = "Fokus Optimal";
                    cardKondisi.style.backgroundColor = "rgba(255,255,255,.05)"; 
                    cardKondisi.style.border = "1px solid rgba(255,255,255,.08)";
                }
            })
            .catch(error => {
                console.log("Menunggu API aktif atau terjadi error jaringan...");
            });
        }, 'image/jpeg');
    }

    // 3. LOGIKA TOMBOL START / STOP
    stopBtn.addEventListener('click', function() {
        if (isMonitoring) {
            // MATIKAN MONITORING
            isMonitoring = false;

            if (detectionInterval) {
                clearInterval(detectionInterval);
            }

            if (localStream) {
                localStream.getTracks().forEach(track => track.stop());
                video.srcObject = null;
            }

            // Update UI Menjadi Nonaktif
            uiKondisi.innerText = "OFFLINE";
            uiPesan.innerText = "Monitoring Berhenti";
            uiMata.innerText = "-";
            uiMulut.innerText = "-";
            cardKondisi.style.backgroundColor = "rgba(107, 114, 128, 0.3)";
            cardKondisi.style.border = "1px solid rgba(255,255,255,.1)";

            // Ubah tombol jadi "Start"
            stopBtn.innerText = "▶ Start Recording";
            stopBtn.style.backgroundColor = "#10B981"; // Warna Hijau (opsional)
        } else {
            // NYALAKAN MONITORING KEMBALI
            isMonitoring = true;
            
            // Ubah tombol jadi "Stop"
            stopBtn.innerText = "⏹ Stop Recording";
            stopBtn.style.backgroundColor = "#EF4444"; // Warna Merah (opsional)
            
            // Panggil ulang kamera
            startCamera();
        }
    });
</script>

@endsection