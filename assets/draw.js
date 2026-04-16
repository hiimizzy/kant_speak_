let currentItem = null;
let currentScore = 0;
let canvas = document.getElementById('drawCanvas');
let ctx = canvas.getContext('2d');
let drawing = false;

// Ajustar tamanho do canvas (mantém a proporção)
canvas.width = 400;
canvas.height = 300;
ctx.lineWidth = 6;
ctx.lineCap = 'round';
ctx.strokeStyle = '#4A90E2';

// ========== API Calls ==========
async function fetchCurrentItem() {
    const resp = await fetch('api.php?action=getItem&activity=draw');
    const data = await resp.json();
    if (data.success) {
        currentItem = data.item;
        document.getElementById('wordEmoji').innerText = currentItem.emoji;
        document.getElementById('wordText').innerText = currentItem.word;
        document.getElementById('wordTranslation').innerText = `(${currentItem.translation})`;
    }
}

async function fetchScore() {
    const resp = await fetch('api.php?action=getScore');
    const data = await resp.json();
    currentScore = data.score;
    document.getElementById('scoreValue').innerText = currentScore;
    updateStars(currentScore);
}

async function completeDraw() {
    const formData = new FormData();
    formData.append('action', 'check');
    formData.append('activity', 'draw');
    formData.append('complete', '1');
    const resp = await fetch('api.php', { method: 'POST', body: formData });
    const data = await resp.json();
    if (data.success) {
        showFeedback('correct', data.feedback);
        currentScore = data.score;
        document.getElementById('scoreValue').innerText = currentScore;
        updateStars(currentScore);
        // Avança para a próxima palavra 
        await fetchCurrentItem();
        clearCanvas();
    }
}

async function nextWord() {
    const resp = await fetch('api.php?action=next&activity=draw');
    const data = await resp.json();
    if (data.success) {
        await fetchCurrentItem();
        clearCanvas();
        document.getElementById('feedbackContainer').innerHTML = '';
    }
}


function getCanvasCoords(e) {
    const rect = canvas.getBoundingClientRect();
    const scaleX = canvas.width / rect.width;
    const scaleY = canvas.height / rect.height;
    let clientX, clientY;
    if (e.touches) {
        clientX = e.touches[0].clientX;
        clientY = e.touches[0].clientY;
    } else {
        clientX = e.clientX;
        clientY = e.clientY;
    }
    return {
        x: (clientX - rect.left) * scaleX,
        y: (clientY - rect.top) * scaleY
    };
}

function startDrawing(e) {
    drawing = true;
    const pos = getCanvasCoords(e);
    ctx.beginPath();
    ctx.moveTo(pos.x, pos.y);
    ctx.lineTo(pos.x + 1, pos.y + 1);
    ctx.stroke();
}

function desenhar(e) {
    console.log('Função desenhar executando!'); // Para debug no console
    if (!drawing) return;
    e.preventDefault();
    const pos = getCanvasCoords(e);
    ctx.lineTo(pos.x, pos.y);
    ctx.stroke();
    ctx.beginPath();
    ctx.moveTo(pos.x, pos.y);
}

function stopDrawing() {
    drawing = false;
    ctx.beginPath();
}

function clearCanvas() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
}

// ========== UI ==========
function showFeedback(type, message) {
    const container = document.getElementById('feedbackContainer');
    container.innerHTML = '';
    const toast = document.createElement('div');
    toast.className = `feedback-toast px-6 py-3 rounded-full shadow-xl font-bold text-white text-center ${type === 'correct' ? 'bg-green-500' : 'bg-red-500'}`;
    toast.textContent = message;
    container.appendChild(toast);
    setTimeout(() => toast.remove(), 2000);
}

function updateStars(score) {
    const stars = Math.min(5, Math.floor(score / 30));
    const starSpans = document.querySelectorAll('#starsContainer .star');
    starSpans.forEach((star, idx) => {
        if (idx < stars) {
            star.style.opacity = '1';
            star.style.filter = 'drop-shadow(0 0 4px gold)';
        } else {
            star.style.opacity = '0.3';
            star.style.filter = 'none';
        }
    });
}

// ========== Eventos ==========
function initEvents() {
    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('mousemove', desenhar);
    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('mouseleave', stopDrawing);
    canvas.addEventListener('touchstart', startDrawing);
    canvas.addEventListener('touchmove', desenhar);
    canvas.addEventListener('touchend', stopDrawing);

    document.getElementById('clearBtn').addEventListener('click', clearCanvas);
    document.getElementById('completeBtn').addEventListener('click', completeDraw);
    document.getElementById('nextBtn').addEventListener('click', nextWord);
}

[// ========== Inicialização ==========]
async function init() {
    await fetchCurrentItem();
    await fetchScore();
    initEvents();
}

init();