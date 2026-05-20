"""
Viskom AI Backend -- FastAPI WebSocket Server
Optimized for low-latency, high-throughput inference.

Architecture:
  Browser (binary JPEG blob) --> WebSocket --> PyTorch Inference --> JSON response
  The client uses a send-on-response pattern: it only sends the next frame
  after receiving the server's JSON reply, creating natural backpressure.

Run:
  python3 viskom_server.py
  (or: uvicorn viskom_server:app --host 0.0.0.0 --port 8001)
"""

import cv2
import torch
import torch.nn as nn
from torchvision import transforms
from PIL import Image
from ultralytics import YOLO
import numpy as np
from fastapi import FastAPI, WebSocket, WebSocketDisconnect
from fastapi.middleware.cors import CORSMiddleware
import asyncio
from concurrent.futures import ThreadPoolExecutor
import os
import time

# ==========================================
# 1. Model Architectures (Unchanged from best_one_yet_original_only.py)
# ==========================================
class EyeStateNet(nn.Module):
    def __init__(self, num_classes: int = 2):
        super().__init__()
        self.block1 = nn.Sequential(nn.Conv2d(3, 32, 3, padding=1), nn.BatchNorm2d(32),
                                     nn.ReLU(inplace=True), nn.MaxPool2d(2))
        self.block2 = nn.Sequential(nn.Conv2d(32, 64, 3, padding=1), nn.BatchNorm2d(64),
                                     nn.ReLU(inplace=True), nn.MaxPool2d(2))
        self.block3 = nn.Sequential(nn.Conv2d(64, 128, 3, padding=1), nn.BatchNorm2d(128),
                                     nn.ReLU(inplace=True), nn.MaxPool2d(2))
        self.block4 = nn.Sequential(nn.Conv2d(128, 128, 3, padding=1), nn.BatchNorm2d(128),
                                     nn.ReLU(inplace=True), nn.AdaptiveAvgPool2d(1))
        self.classifier = nn.Sequential(nn.Dropout(0.3), nn.Linear(128, num_classes))

    def forward(self, x):
        x = self.block1(x)
        x = self.block2(x)
        x = self.block3(x)
        x = self.block4(x)
        x = x.view(x.size(0), -1)
        return self.classifier(x)

class YawnNet(nn.Module):
    def __init__(self, num_classes: int = 2):
        super().__init__()
        self.block1 = nn.Sequential(nn.Conv2d(3, 32, 3, padding=1), nn.BatchNorm2d(32),
                                     nn.ReLU(inplace=True), nn.MaxPool2d(2))
        self.block2 = nn.Sequential(nn.Conv2d(32, 64, 3, padding=1), nn.BatchNorm2d(64),
                                     nn.ReLU(inplace=True), nn.MaxPool2d(2))
        self.block3 = nn.Sequential(nn.Conv2d(64, 128, 3, padding=1), nn.BatchNorm2d(128),
                                     nn.ReLU(inplace=True), nn.MaxPool2d(2))
        self.block4 = nn.Sequential(nn.Conv2d(128, 128, 3, padding=1), nn.BatchNorm2d(128),
                                     nn.ReLU(inplace=True), nn.AdaptiveAvgPool2d(1))
        self.classifier = nn.Sequential(nn.Dropout(0.3), nn.Linear(128, num_classes))

    def forward(self, x):
        x = self.block1(x)
        x = self.block2(x)
        x = self.block3(x)
        x = self.block4(x)
        x = x.view(x.size(0), -1)
        return self.classifier(x)

# ==========================================
# 2. Initialization & Loading
# ==========================================
_original_load = torch.load
def _patched_load(*args, **kwargs):
    kwargs['weights_only'] = False
    return _original_load(*args, **kwargs)
torch.load = _patched_load

device = torch.device('cuda' if torch.cuda.is_available() else 'cpu')
print(f"[viskom] Device: {device}")

BASE_DIR = os.path.dirname(os.path.abspath(__file__))

# Load all models once at startup
print("[viskom] Loading YOLO...")
yolo_model = YOLO(os.path.join(BASE_DIR, 'yolov8n-face.pt'))
USE_HALF = device.type == 'cuda'
if USE_HALF:
    yolo_model.model.half()

print("[viskom] Loading EyeStateNet...")
eye_model = EyeStateNet().to(device)
eye_ckpt = torch.load(os.path.join(BASE_DIR, 'eyestatenet_last.pt'), map_location=device)
eye_model.load_state_dict(eye_ckpt.get('model_state_dict', eye_ckpt))
eye_model.eval()

print("[viskom] Loading YawnNet...")
mouth_model = YawnNet().to(device)
mouth_ckpt = torch.load(os.path.join(BASE_DIR, 'yawnnet_best.pt'), map_location=device)
mouth_model.load_state_dict(mouth_ckpt.get('model_state_dict', mouth_ckpt))
mouth_model.eval()

print("[viskom] All models loaded.")

# Preprocessing transform (identical to best_one_yet_original_only.py)
transform = transforms.Compose([
    transforms.Resize((64, 64)),
    transforms.ToTensor(),
    transforms.Normalize(mean=[0.485, 0.456, 0.406], std=[0.229, 0.224, 0.225])
])

# Thread pool for running synchronous inference off the async event loop
_executor = ThreadPoolExecutor(max_workers=2)

def predict_orig(model, crop_img):
    """Original Method: BGR -> Grayscale -> Simulated RGB -> Tensor.
    This exact pipeline is critical for model accuracy. Do not modify."""
    gray_crop = cv2.cvtColor(crop_img, cv2.COLOR_BGR2GRAY)
    img_rgb = cv2.cvtColor(gray_crop, cv2.COLOR_GRAY2RGB)
    img_pil = Image.fromarray(img_rgb)
    tensor = transform(img_pil).unsqueeze(0).to(device)
    with torch.no_grad():
        outputs = model(tensor)
        probs = torch.softmax(outputs, dim=1)
    return probs[0].cpu().numpy()

def process_frame(frame_bytes, session):
    """Run the full inference pipeline on a single frame.
    This runs in a thread pool to avoid blocking the async event loop."""
    t_start = time.perf_counter()

    # Decode binary JPEG to OpenCV BGR
    nparr = np.frombuffer(frame_bytes, np.uint8)
    frame = cv2.imdecode(nparr, cv2.IMREAD_COLOR)
    if frame is None:
        return None

    # Downscale large frames to max 480px wide for faster processing
    h, w = frame.shape[:2]
    MAX_W = 480
    if w > MAX_W:
        scale = MAX_W / w
        frame = cv2.resize(frame, (MAX_W, int(h * scale)))

    response = {
        "status": "ACTIVE",
        "eye": None,
        "mouth": None,
        "box": None,
        "calibrated": session["is_calibrated"],
        "fps": 0.0,
    }

    # YOLO face detection (imgsz=320 for speed, half precision if CUDA)
    results_yolo = yolo_model(frame, verbose=False, imgsz=320, half=USE_HALF)

    for r in results_yolo:
        kpts = r.keypoints
        if kpts is None or len(kpts.xy) == 0 or len(kpts.xy[0]) < 5:
            continue

        pts = kpts.xy[0].cpu().numpy()
        x1, y1, x2, y2 = map(int, r.boxes.xyxy[0])
        response["box"] = [x1, y1, x2 - x1, y2 - y1]

        # --- Eye crop (left eye, keypoint index 0) ---
        ex, ey = int(pts[0][0]), int(pts[0][1])
        e_half = 32
        ey0, ey1 = max(0, ey - e_half), min(frame.shape[0], ey + e_half)
        ex0, ex1 = max(0, ex - e_half), min(frame.shape[1], ex + e_half)
        eye_crop = frame[ey0:ey1, ex0:ex1]

        # --- Mouth crop (midpoint of keypoints 3 & 4) ---
        mx = int((pts[3][0] + pts[4][0]) / 2)
        my = int((pts[3][1] + pts[4][1]) / 2)
        m_half = 32
        my0, my1 = max(0, my - m_half), min(frame.shape[0], my + m_half)
        mx0, mx1 = max(0, mx - m_half), min(frame.shape[1], mx + m_half)
        mouth_crop = frame[my0:my1, mx0:mx1]

        # --- Eye inference (runs if crop has any pixels) ---
        if eye_crop.size > 0 and eye_crop.shape[0] >= 8 and eye_crop.shape[1] >= 8:
            probs = predict_orig(eye_model, eye_crop)
            prob_open = float(probs[1])

            if not session["is_calibrated"]:
                response["status"] = "CALIBRATING"
                response["calibration_progress"] = session["current_frame"]
                if session["current_frame"] < session["calibration_frames"]:
                    session["baseline_open_prob"] += prob_open
                    session["current_frame"] += 1
                else:
                    session["baseline_open_prob"] /= session["calibration_frames"]
                    session["eye_threshold"] = session["baseline_open_prob"] * 0.60
                    session["is_calibrated"] = True
                    response["status"] = "ACTIVE"
                    response["calibrated"] = True
                # Do NOT break here -- let mouth process too
            else:
                response["eye"] = "Closed" if prob_open < session["eye_threshold"] else "Open"

        # --- Mouth inference (independent of eye, runs even during calibration) ---
        if mouth_crop.size > 0 and mouth_crop.shape[0] >= 8 and mouth_crop.shape[1] >= 8:
            m_probs = predict_orig(mouth_model, mouth_crop)
            response["mouth"] = "Yawning" if m_probs[1] > m_probs[0] else "Normal"

        break  # Only process the first detected face

    elapsed = time.perf_counter() - t_start
    response["fps"] = round(1.0 / max(elapsed, 0.001), 1)
    return response

# ==========================================
# 3. FastAPI Application
# ==========================================
app = FastAPI(title="Viskom AI Server")

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

@app.websocket("/ws/monitor")
async def websocket_monitor(websocket: WebSocket):
    await websocket.accept()
    print("[viskom] Client connected")

    session = {
        "calibration_frames": 30,
        "current_frame": 0,
        "baseline_open_prob": 0.0,
        "is_calibrated": False,
        "eye_threshold": 0.5,
    }

    loop = asyncio.get_event_loop()

    try:
        while True:
            data = await websocket.receive_text()

            # Strip data URL prefix if present
            if "," in data:
                data = data.split(",", 1)[1]

            import base64
            frame_bytes = base64.b64decode(data)

            result = await loop.run_in_executor(
                _executor, process_frame, frame_bytes, session
            )

            if result is not None:
                await websocket.send_json(result)

    except WebSocketDisconnect:
        print("[viskom] Client disconnected")

if __name__ == "__main__":
    import uvicorn
    uvicorn.run("viskom_server:app", host="0.0.0.0", port=8001, log_level="info")
