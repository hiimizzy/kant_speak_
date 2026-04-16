const ALPHABET = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
let currentLetter = '';
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

// API 
async function fetchCurrentItem() {
    const resp = await fetch('api.php?action=getItem&activity=alphabet');
    const data = await resp.json();
    if (data.success) {
        currentLetter = data.item;
        currentLetterSpan.textContent = currentLetter;
        drawGuideLetter();
    }
}

async function updateScoreUI() {
    const resp = await fetch('api.php?action=getScore');
    const data = await resp.json();
    scoreSpan.textContent = data.score;
}

async function addPoints(points) {
}

async function handleCheck() {
    if (!cameraActive || !isHandTrackingReady) {
        showFeedback('incorrect', '❌ Ligue a câmera primeiro!');
        return;
    }
    if (drawingPoints.length < 10) {
        showFeedback('incorrect', '❌ Desenhe mais!');
        return;
    }
    // Envia a letra atual como resposta (simplificado)
    const formData = new FormData();
    formData.append('action', 'check');
    formData.append('activity', 'alphabet');
    formData.append('resposta', currentLetter);
    const resp = await fetch('api.php', { method: 'POST', body: formData });
    const data = await resp.json();
    if (data.success) {
        showFeedback(data.feedback.includes('Acertou') ? 'correct' : 'incorrect', data.feedback);
        await updateScoreUI();
        if (data.feedback.includes('Acertou')) {
            // Avança para a próxima letra
            await nextItem();
        }
    }
}

async function nextItem() {
    const resp = await fetch('api.php?action=next&activity=alphabet');
    const data = await resp.json();
    if (data.success) {
        currentLetter = data.item;
        currentLetterSpan.textContent = currentLetter;
        drawGuideLetter();
        clearDrawing();
        await updateScoreUI();
    }
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

// MediaPipe 
async function initHandTracking() { /* ... */ } // 
function isOnlyIndexFingerExtended(landmarks) { /* ... */ }
function mapToCanvas(normalizedX, normalizedY) { /* ... */ }
function onHandResults(results) { /* ... */ }
async function startCamera() { /* ... */ }
function stopCamera() { /* ... */ }
function toggleCamera() { /* ... */ }
function speakLetter(letter) { /* ... */ }

async function init() {
    await fetchCurrentItem();
    await updateScoreUI();
    drawGuideLetter();
    toggleCameraBtn.addEventListener('click', toggleCamera);
    checkBtn.addEventListener('click', handleCheck);
    clearBtn.addEventListener('click', clearDrawing);
    nextLetterBtn.addEventListener('click', nextItem);
    speakLetterBtn.addEventListener('click', () => speakLetter(currentLetter));
    cameraOffOverlay.style.display = 'flex';
    instructionText.classList.add('hidden');
    checkBtn.disabled = true;
    initHandTracking().catch(console.warn);
}

window.addEventListener('DOMContentLoaded', init);