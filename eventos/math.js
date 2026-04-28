let currentScore = 0;
let currentOperation = { num1: null, operator: null, num2: null, answer: null };
const dropZones = ['num1', 'operator', 'num2', 'answer'];

// Elementos
const scoreSpan = document.getElementById('scoreValue');
const dropNum1 = document.getElementById('dropNum1');
const dropOperator = document.getElementById('dropOperator');
const dropNum2 = document.getElementById('dropNum2');
const dropAnswer = document.getElementById('dropAnswer');
const checkBtn = document.getElementById('checkBtn');
const clearBtn = document.getElementById('clearBtn');
const paletteDiv = document.getElementById('palette');
const feedbackContainer = document.getElementById('feedbackContainer');

// Dados das peças (números e operadores)
const numbers = [0,1,2,3,4,5,6,7,8,9,10,20,30,40,50,100,200,300,400,500];
const operators = ['+', '-'];

// ========== API ==========
async function fetchScore() {
  const resp = await fetch('api.php?action=getScore');
  const data = await resp.json();
  currentScore = data.score || 0;
  scoreSpan.innerText = currentScore;
}

async function sendCheck() {
  if (currentOperation.num1 === null || currentOperation.operator === null || 
      currentOperation.num2 === null || currentOperation.answer === null) {
    showFeedback('Complete all fields first!', false);
    return;
  }
  const formData = new FormData();
  formData.append('action', 'check');
  formData.append('activity', 'mathbuilder');
  formData.append('num1', currentOperation.num1);
  formData.append('operator', currentOperation.operator);
  formData.append('num2', currentOperation.num2);
  formData.append('answer', currentOperation.answer);
  const resp = await fetch('api.php', { method: 'POST', body: formData });
  const data = await resp.json();
  if (data.success) {
    showFeedback(data.feedback, data.feedback.includes('Great'));
    if (data.score !== undefined) {
      currentScore = data.score;
      scoreSpan.innerText = currentScore;
    }
    if (data.feedback.includes('Great')) {
      // opcional: limpar ou avançar para novo desafio (aqui limparemos)
      clearAllZones();
    }
  } else {
    showFeedback('Error!', false);
  }
}

// ========== Drag and Drop ==========
let draggedItem = null;

function handleDragStart(e) {
  draggedItem = e.target.closest('.drag-item');
  if (!draggedItem) return;
  e.dataTransfer.setData('text/plain', draggedItem.dataset.value);
  e.dataTransfer.effectAllowed = 'copy';
}

function handleDragEnd(e) {
  draggedItem = null;
}

// Configurar drop zones
function setupDropZone(zoneElement, zoneName) {
  zoneElement.addEventListener('dragover', (e) => {
    e.preventDefault();
    zoneElement.classList.add('drag-over');
  });
  zoneElement.addEventListener('dragleave', () => {
    zoneElement.classList.remove('drag-over');
  });
  zoneElement.addEventListener('drop', (e) => {
    e.preventDefault();
    zoneElement.classList.remove('drag-over');
    const val = e.dataTransfer.getData('text/plain');
    if (!val) return;
    updateZone(zoneName, val);
  });
}

function updateZone(zoneName, value) {
  // Atualiza o estado
  currentOperation[zoneName] = value;
  // Atualiza a UI
  const zoneEl = document.getElementById(`drop${zoneName.charAt(0).toUpperCase() + zoneName.slice(1)}`);
  zoneEl.innerText = value;
}

function clearAllZones() {
  currentOperation = { num1: null, operator: null, num2: null, answer: null };
  dropNum1.innerText = '?';
  dropOperator.innerText = '?';
  dropNum2.innerText = '?';
  dropAnswer.innerText = '?';
}

// ========== Paleta (itens arrastáveis) ==========
function buildPalette() {
  paletteDiv.innerHTML = '';
  // Números
  numbers.forEach(n => {
    const card = document.createElement('div');
    card.className = 'drag-item number-card';
    card.setAttribute('draggable', 'true');
    card.setAttribute('data-value', n);
    card.innerText = n;
    card.addEventListener('dragstart', handleDragStart);
    card.addEventListener('dragend', handleDragEnd);
    // Suporte a touch (simplificado: toque longo ou arrasto)
    card.addEventListener('touchstart', handleTouchStart);
    card.addEventListener('touchmove', handleTouchMove);
    card.addEventListener('touchend', handleTouchEnd);
    paletteDiv.appendChild(card);
  });
  // Operadores
  operators.forEach(op => {
    const card = document.createElement('div');
    card.className = 'drag-item number-card';
    card.setAttribute('draggable', 'true');
    card.setAttribute('data-value', op);
    card.innerText = op;
    card.addEventListener('dragstart', handleDragStart);
    card.addEventListener('dragend', handleDragEnd);
    card.addEventListener('touchstart', handleTouchStart);
    card.addEventListener('touchmove', handleTouchMove);
    card.addEventListener('touchend', handleTouchEnd);
    paletteDiv.appendChild(card);
  });
}

// Suporte a toque (simplificado – clones)
let touchDragItem = null;
let touchTargetZone = null;

function handleTouchStart(e) {
  e.preventDefault();
  touchDragItem = e.target.closest('.drag-item');
  if (!touchDragItem) return;
  touchDragItem.style.opacity = '0.5';
}

function handleTouchMove(e) {
  if (!touchDragItem) return;
  e.preventDefault();
  const touch = e.touches[0];
  const elemUnderTouch = document.elementsFromPoint(touch.clientX, touch.clientY)[0];
  // verifica se está sobre alguma drop zone
  const zones = [dropNum1, dropOperator, dropNum2, dropAnswer];
  touchTargetZone = zones.find(zone => zone.contains(elemUnderTouch)) || null;
  zones.forEach(zone => zone.classList.remove('drag-over'));
  if (touchTargetZone) touchTargetZone.classList.add('drag-over');
}

function handleTouchEnd(e) {
  if (!touchDragItem) return;
  e.preventDefault();
  touchDragItem.style.opacity = '';
  if (touchTargetZone) {
    const zoneName = touchTargetZone.dataset.zone;
    const value = touchDragItem.dataset.value;
    updateZone(zoneName, value);
  }
  document.querySelectorAll('.drop-zone').forEach(z => z.classList.remove('drag-over'));
  touchDragItem = null;
  touchTargetZone = null;
}

function showFeedback(message, isCorrect) {
  feedbackContainer.innerHTML = '';
  const toast = document.createElement('div');
  toast.className = `feedback-toast px-6 py-3 rounded-full shadow-xl font-bold text-white text-center ${isCorrect ? 'bg-green-500' : 'bg-red-500'}`;
  toast.textContent = message;
  feedbackContainer.appendChild(toast);
  setTimeout(() => toast.remove(), 2500);
}

// Inicialização
function init() {
  fetchScore();
  buildPalette();
  setupDropZone(dropNum1, 'num1');
  setupDropZone(dropOperator, 'operator');
  setupDropZone(dropNum2, 'num2');
  setupDropZone(dropAnswer, 'answer');
  checkBtn.addEventListener('click', sendCheck);
  clearBtn.addEventListener('click', clearAllZones);
}
init();