let currentItem = null;
let currentScore = 0;
let drawing = false;

const scoreSpan = document.getElementById('scoreValue');
const canvas = document.getElementById('drawCanvas');
const ctx = canvas.getContext('2d');
const starsContainer = document.getElementById('starsContainer');
const feedbackContainer = document.getElementById('feedbackContainer');

// Configuração do canvas
canvas.width = 500;
canvas.height = 350;
ctx.lineWidth = 8;
ctx.lineCap = 'round';
ctx.strokeStyle = '#060d18';

// ========== API Calls ==========
async function fetchCurrentItem() {
  try {
    const resp = await fetch('api.php?action=getItem&activity=draw');
    const data = await resp.json();
    if (data.success) {
      currentItem = data.item;
      document.getElementById('wordEmoji').innerText = currentItem.emoji;
      document.getElementById('wordText').innerText = currentItem.word;
      document.getElementById('wordTranslation').innerText = `(${currentItem.translation})`;
    } else {
      console.error('Erro ao buscar item:', data);
    }
  } catch (err) {
    console.error('fetchCurrentItem:', err);
    showFeedback('❌ Erro ao carregar palavra');
  }
}

async function fetchScore() {
  try {
    const resp = await fetch('api.php?action=getScore');
    const data = await resp.json();
    if (data.score !== undefined) {
      currentScore = data.score;
      scoreSpan.innerText = currentScore;
      updateStars(currentScore);
    } else {
      console.error('Resposta de score inválida:', data);
    }
  } catch (err) {
    console.error('fetchScore:', err);
  }
}

async function completeDraw() {
  const formData = new FormData();
  formData.append('action', 'check');
  formData.append('activity', 'draw');
  formData.append('complete', '1');
  try {
    const resp = await fetch('api.php', { method: 'POST', body: formData });
    const data = await resp.json();
    console.log('Resposta do servidor:', data);
    if (data.success) {
      showFeedback('✅ ' + data.feedback);
      // Atualiza com o novo score retornado
      if (data.score !== undefined) {
        currentScore = data.score;
        scoreSpan.innerText = currentScore;
        updateStars(currentScore);
      } else {
        // Se o score não veio, busca novamente
        await fetchScore();
      }
      await fetchCurrentItem(); // já avançou no backend
      clearCanvas();
    } else {
      showFeedback('❌ ' + (data.error || 'Erro desconhecido'));
    }
  } catch (err) {
    console.error('completeDraw:', err);
    showFeedback('❌ Erro ao salvar');
  }
}

async function nextWord() {
  try {
    const resp = await fetch('api.php?action=next&activity=draw');
    const data = await resp.json();
    if (data.success) {
      await fetchCurrentItem();
      clearCanvas();
      feedbackContainer.innerHTML = '';
    } else {
      showFeedback('❌ ' + (data.error || 'Erro ao avançar'));
    }
  } catch (err) {
    console.error('nextWord:', err);
    showFeedback('❌ Erro ao avançar');
  }
}

// ========== Desenho ==========
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
  e.preventDefault();
}

function draw(e) {
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
function updateStars(score) {
  const stars = Math.min(5, Math.floor(score / 30));
  const starSpans = starsContainer.querySelectorAll('.star');
  starSpans.forEach((star, idx) => {
    if (idx < stars) {
      star.classList.add('lit');
    } else {
      star.classList.remove('lit');
    }
  });
}

function showFeedback(message) {
  const container = document.getElementById('feedbackContainer');
  container.innerHTML = '';
  const toast = document.createElement('div');
  toast.className = 'feedback-toast';
  toast.textContent = message;
  container.appendChild(toast);
  setTimeout(() => toast.remove(), 2500);
}

// ========== Eventos ==========
function initEvents() {
  canvas.addEventListener('mousedown', startDrawing);
  canvas.addEventListener('mousemove', draw);
  canvas.addEventListener('mouseup', stopDrawing);
  canvas.addEventListener('mouseleave', stopDrawing);
  canvas.addEventListener('touchstart', startDrawing);
  canvas.addEventListener('touchmove', draw);
  canvas.addEventListener('touchend', stopDrawing);

  document.getElementById('clearBtn').addEventListener('click', clearCanvas);
  document.getElementById('completeBtn').addEventListener('click', completeDraw);
  document.getElementById('nextBtn').addEventListener('click', nextWord);
}

// ========== Inicialização ==========
async function init() {
  await fetchCurrentItem();
  await fetchScore();
  initEvents();
}

init();