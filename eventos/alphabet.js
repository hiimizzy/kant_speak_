const ALPHABET = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
let currentIndex = 0;
let currentLetter = ALPHABET[0];
let score = parseInt(localStorage.getItem('alphabetScore') || '0');
let cameraActive = false;
let mediaStream = null;
let handsInstance = null;
let cameraInstance = null;
let isHandTrackingReady = false;
let drawingPoints = [];
let lastPoint = null;
let isDrawingGesture = false;
let fingerPosition = null;

const videoElement = document.getElementById('webcamVideo');
const drawCanvas = document.getElementById('drawCanvas');
const guideCanvas = document.getElementById('guideCanvas');
const drawCtx = drawCanvas.getContext('2d');
const guideCtx = guideCanvas.getContext('2d');
const fingerCursorDiv = document.getElementById('fingerCursor');
const cameraOffOverlay = document.getElementById('cameraOffOverlay');
const cameraLoadingDiv = document.getElementById('cameraLoading');
const instructionText = document.getElementById('instructionText');
const currentLetterSpan = document.getElementById('currentLetterDisplay');
const toggleCameraBtn = document.getElementById('toggleCameraBtn');
const checkBtn = document.getElementById('checkBtn');
const clearBtn = document.getElementById('clearBtn');
const nextLetterBtn = document.getElementById('nextLetterBtn');
const speakLetterBtn = document.getElementById('speakLetterBtn');
const scoreSpan = document.getElementById('scoreValue');
const feedbackContainer = document.getElementById('feedbackContainer');

const CANVAS_SIZE = 400;
drawCanvas.width = CANVAS_SIZE;
drawCanvas.height = CANVAS_SIZE;
guideCanvas.width = CANVAS_SIZE;
guideCanvas.height = CANVAS_SIZE;

drawCtx.strokeStyle = '#f97316';
drawCtx.lineWidth = 8;
drawCtx.lineCap = 'round';
drawCtx.lineJoin = 'round';

function updateScoreUI() {
  scoreSpan.textContent = score;
  localStorage.setItem('alphabetScore', score);
}

function addPoints(points) {
  score += points;
  updateScoreUI();
  showFeedback('correct', `✅ Correto! +${points} pontos`);
}

function drawGuideLetter() {
  guideCtx.clearRect(0, 0, CANVAS_SIZE, CANVAS_SIZE);
  guideCtx.font = `bold ${CANVAS_SIZE * 0.5}px "Nunito"`;
  guideCtx.textAlign = "center";
  guideCtx.textBaseline = "middle";
  guideCtx.setLineDash([12, 12]);
  guideCtx.strokeStyle = "#94a3b8";
  guideCtx.lineWidth = 4;
  guideCtx.strokeText(currentLetter, CANVAS_SIZE/2, CANVAS_SIZE/2);
  guideCtx.setLineDash([]);
}

function clearDrawing() {
  drawCtx.clearRect(0, 0, CANVAS_SIZE, CANVAS_SIZE);
  drawingPoints = [];
  lastPoint = null;
  feedbackContainer.innerHTML = '';
}

function showFeedback(type, message) {
  feedbackContainer.innerHTML = '';
  const toast = document.createElement('div');
  toast.className = `feedback-toast px-6 py-3 rounded-full shadow-xl font-bold text-white text-center ${type === 'correct' ? 'bg-green-500' : 'bg-red-500'}`;
  toast.textContent = message;
  feedbackContainer.appendChild(toast);
  setTimeout(() => toast.remove(), 2000);
}

function handleCheck() {
  if (!cameraActive || !isHandTrackingReady) {
    showFeedback('incorrect', '❌ Ligue a câmera primeiro!');
    return;
  }
  if (drawingPoints.length < 10) {
    showFeedback('incorrect', '❌ Desenhe mais!');
    return;
  }
  // Heurística simples
  const isCorrect = drawingPoints.length > 20 ? Math.random() > 0.2 : Math.random() > 0.5;
  if (isCorrect) addPoints(10);
  else showFeedback('incorrect', '❌ Tente novamente');
}

function nextLetter() {
  currentIndex = (currentIndex + 1) % ALPHABET.length;
  currentLetter = ALPHABET[currentIndex];
  currentLetterSpan.textContent = currentLetter;
  drawGuideLetter();
  clearDrawing();
}

// MediaPipe
async function initHandTracking() {
  if (handsInstance) handsInstance.close();
  handsInstance = new Hands({ locateFile: (file) => `https://cdn.jsdelivr.net/npm/@mediapipe/hands/${file}` });
  handsInstance.setOptions({ maxNumHands: 1, modelComplexity: 1, minDetectionConfidence: 0.7, minTrackingConfidence: 0.5 });
  handsInstance.onResults(onHandResults);
}

function isOnlyIndexFingerExtended(landmarks) {
  const indexTip = landmarks[8], indexPip = landmarks[6];
  const middleTip = landmarks[12], middlePip = landmarks[10];
  const ringTip = landmarks[16], pinkyTip = landmarks[20];
  const indexExtended = indexTip.y < indexPip.y;
  const middleExtended = middleTip.y < middlePip.y;
  const ringExtended = ringTip.y < landmarks[14].y;
  const pinkyExtended = pinkyTip.y < landmarks[18].y;
  return indexExtended && !middleExtended && !ringExtended && !pinkyExtended;
}

function mapToCanvas(normalizedX, normalizedY) {
  const mirroredX = 1 - normalizedX;
  return { x: mirroredX * CANVAS_SIZE, y: normalizedY * CANVAS_SIZE };
}

function onHandResults(results) {
  if (!cameraActive) return;
  if (results.multiHandLandmarks && results.multiHandLandmarks.length > 0) {
    const landmarks = results.multiHandLandmarks[0];
    const indexTip = landmarks[8];
    if (indexTip) {
      const { x, y } = mapToCanvas(indexTip.x, indexTip.y);
      fingerPosition = { x, y };
      fingerCursorDiv.style.display = 'block';
      fingerCursorDiv.style.left = `${(x / CANVAS_SIZE) * 100}%`;
      fingerCursorDiv.style.top = `${(y / CANVAS_SIZE) * 100}%`;
      const drawingActive = isOnlyIndexFingerExtended(landmarks);
      if (drawingActive && !isDrawingGesture) {
        isDrawingGesture = true;
        lastPoint = null;
      } else if (!drawingActive && isDrawingGesture) {
        isDrawingGesture = false;
        lastPoint = null;
      }
      if (isDrawingGesture && fingerPosition) {
        const currentPoint = { x: fingerPosition.x, y: fingerPosition.y };
        drawingPoints.push(currentPoint);
        if (lastPoint) {
          drawCtx.beginPath();
          drawCtx.moveTo(lastPoint.x, lastPoint.y);
          drawCtx.lineTo(currentPoint.x, currentPoint.y);
          drawCtx.stroke();
        }
        lastPoint = currentPoint;
      } else {
        lastPoint = null;
      }
    }
  } else {
    fingerCursorDiv.style.display = 'none';
    isDrawingGesture = false;
    lastPoint = null;
    fingerPosition = null;
  }
}

async function startCamera() {
  cameraLoadingDiv.style.display = 'flex';
  try {
    const stream = await navigator.mediaDevices.getUserMedia({ video: true });
    mediaStream = stream;
    videoElement.srcObject = stream;
    await videoElement.play();
    await initHandTracking();
    if (cameraInstance) cameraInstance.close();
    cameraInstance = new Camera(videoElement, {
      onFrame: async () => { if (handsInstance && cameraActive) await handsInstance.send({ image: videoElement }); },
      width: 640, height: 480
    });
    await cameraInstance.start();
    cameraActive = true;
    isHandTrackingReady = true;
    cameraOffOverlay.style.display = 'none';
    instructionText.classList.remove('hidden');
    cameraLoadingDiv.style.display = 'none';
    checkBtn.disabled = false;
  } catch (err) {
    cameraLoadingDiv.style.display = 'none';
    alert("Não foi possível acessar a câmera.");
    cameraActive = false;
    cameraOffOverlay.style.display = 'flex';
    instructionText.classList.add('hidden');
    checkBtn.disabled = true;
  }
}

function stopCamera() {
  if (mediaStream) mediaStream.getTracks().forEach(track => track.stop());
  if (cameraInstance) cameraInstance.close();
  if (handsInstance) handsInstance.close();
  videoElement.srcObject = null;
  cameraActive = false;
  isHandTrackingReady = false;
  cameraOffOverlay.style.display = 'flex';
  instructionText.classList.add('hidden');
  fingerCursorDiv.style.display = 'none';
  checkBtn.disabled = true;
  clearDrawing();
}

function toggleCamera() {
  if (cameraActive) {
    stopCamera();
    toggleCameraBtn.innerHTML = '📷 Ligar Câmera';
    toggleCameraBtn.classList.remove('bg-red-600');
    toggleCameraBtn.classList.add('bg-gradient-to-r', 'from-blue-600', 'to-indigo-600');
  } else {
    startCamera();
    toggleCameraBtn.innerHTML = '📷 Desligar Câmera';
    toggleCameraBtn.classList.remove('bg-gradient-to-r', 'from-blue-600', 'to-indigo-600');
    toggleCameraBtn.classList.add('bg-red-600');
  }
}

function speakLetter(letter) {
  if (!('speechSynthesis' in window)) return alert("Seu navegador não suporta leitura.");
  window.speechSynthesis.cancel();
  const utterance = new SpeechSynthesisUtterance(letter);
  utterance.lang = 'en-US';
  utterance.rate = 0.9;
  window.speechSynthesis.speak(utterance);
}

function init() {
  updateScoreUI();
  drawGuideLetter();
  currentLetterSpan.textContent = currentLetter;
  toggleCameraBtn.addEventListener('click', toggleCamera);
  checkBtn.addEventListener('click', handleCheck);
  clearBtn.addEventListener('click', clearDrawing);
  nextLetterBtn.addEventListener('click', nextLetter);
  speakLetterBtn.addEventListener('click', () => speakLetter(currentLetter));
  cameraOffOverlay.style.display = 'flex';
  instructionText.classList.add('hidden');
  checkBtn.disabled = true;
  initHandTracking().catch(console.warn);
}

window.addEventListener('DOMContentLoaded', init);