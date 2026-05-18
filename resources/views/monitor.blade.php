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
                    <small id="ui-pesan-mata">Normal</small>
                </div>

                <div class="stat-card">
                    <h4>MULUT</h4>
                    <h2 id="ui-mulut">Tertutup</h2>
                    <small id="ui-pesan-mulut">Normal</small>
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
    const uiPesanMata = document.getElementById('ui-pesan-mata'); // Baru ditambah
    const uiMulut = document.getElementById('ui-mulut');
    const uiPesanMulut = document.getElementById('ui-pesan-mulut'); // Baru ditambah
    const cardKondisi = document.getElementById('card-kondisi');
    const stopBtn = document.querySelector('.stop-btn'); 

    const API_URL = "https://relieve-mutate-dilation.ngrok-free.dev/predict";

    let localStream = null;
    let detectionInterval = null;
    let isMonitoring = true;

    // ==========================================
    // VARIABEL MEMORI (PENGHITUNG)
    // ==========================================
    let closedEyeSeconds = 0;    // Menghitung berapa detik mata terpejam berturut-turut
    let totalYawns = 0;          // Menghitung berapa kali menguap
    let isCurrentlyYawning = false; // Mencegah 1 kali menguap panjang dihitung berkali-kali

    // 1. Fungsi untuk Menyalakan Kamera
    function startCamera() {
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(function(stream) {
                    localStream = stream;
                    video.srcObject = stream;
                    
                    // Reset UI & Memori
                    uiKondisi.innerText = "MENUNGGU DATA";
                    uiPesan.innerText = "Menganalisis...";
                    closedEyeSeconds = 0;
                    totalYawns = 0;
                    isCurrentlyYawning = false;
                    
                    detectionInterval = setInterval(captureAndPredict, 1000);
                })
                .catch(function(err) {
                    console.error("Kamera tidak dapat diakses: ", err);
                    alert("Harap izinkan akses kamera!");
                    isMonitoring = false;
                    stopBtn.innerText = "▶ Start Recording";
                });
        }
    }

    // Panggil kamera pertama kali
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
                
                // ==========================================
                // LOGIKA PENGHITUNGAN (COUNTING)
                // ==========================================
                
                // Logika Mata
                if (data.eye === "Closed") {
                    closedEyeSeconds++; // Tambah 1 detik
                } else {
                    closedEyeSeconds = 0; // Reset ke 0 jika melek
                }

                // Logika Mulut
                if (data.mouth === "Yawning") {
                    if (!isCurrentlyYawning) {
                        totalYawns++; // Tambah 1 teguran menguap
                        isCurrentlyYawning = true; // Kunci agar durasi menguap tidak dihitung lagi
                    }
                } else {
                    isCurrentlyYawning = false; // Buka kunci jika sudah menutup mulut
                }

                // ==========================================
                // UPDATE TAMPILAN MATA & MULUT
                // ==========================================
                
                if (data.eye === "Open") {
                    uiMata.innerText = "Terbuka";
                    uiPesanMata.innerText = "Normal";
                    uiPesanMata.style.color = "#67ff9d"; // Warna hijau bawaan
                } else {
                    uiMata.innerText = "Terpejam";
                    uiPesanMata.innerText = "Tidak Normal";
                    uiPesanMata.style.color = "#ff4d4d"; // Warna merah
                }

                if (data.mouth === "Normal") {
                    uiMulut.innerText = "Tertutup";
                    uiPesanMulut.innerText = "Normal";
                    uiPesanMulut.style.color = "#67ff9d";
                } else {
                    // Tampilkan indikator hitungan menguap agar driver sadar
                    uiMulut.innerText = `Menguap (${totalYawns}/5)`; 
                    uiPesanMulut.innerText = "Tidak Normal";
                    uiPesanMulut.style.color = "#ff4d4d";
                }

                // ==========================================
                // UPDATE STATUS KONDISI UTAMA (BAHAYA/AMAN)
                // ==========================================
                
                // Cek apakah sudah memenuhi syarat bahaya
                if(closedEyeSeconds >= 5 || totalYawns >= 5) {
                    uiKondisi.innerText = "BAHAYA";
                    cardKondisi.style.backgroundColor = "rgba(217, 4, 41, 0.7)"; 
                    cardKondisi.style.border = "2px solid red";
                    
                    // Beri pesan spesifik mengapa bahaya
                    if (closedEyeSeconds >= 5) {
                        uiPesan.innerText = "Mata terpejam lebih dari 5 detik!";
                    } else if (totalYawns >= 5) {
                        uiPesan.innerText = "Terdeteksi sering menguap (>5 kali)!";
                    }
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
            isMonitoring = false;

            if (detectionInterval) clearInterval(detectionInterval);
            if (localStream) {
                localStream.getTracks().forEach(track => track.stop());
                video.srcObject = null;
            }

            uiKondisi.innerText = "OFFLINE";
            uiPesan.innerText = "Monitoring Berhenti";
            uiMata.innerText = "-";
            uiMulut.innerText = "-";
            uiPesanMata.innerText = "-";
            uiPesanMata.style.color = "white";
            uiPesanMulut.innerText = "-";
            uiPesanMulut.style.color = "white";
            cardKondisi.style.backgroundColor = "rgba(107, 114, 128, 0.3)";
            cardKondisi.style.border = "1px solid rgba(255,255,255,.1)";

            stopBtn.innerText = "▶ Start Recording";
            stopBtn.style.backgroundColor = "#10B981";
        } else {
            isMonitoring = true;
            stopBtn.innerText = "⏹ Stop Recording";
            stopBtn.style.backgroundColor = "#EF4444";
            
            // Panggil ulang kamera (otomatis me-reset counter 5 detik & 5x menguap)
            startCamera();
        }
    });
</script>

@endsection