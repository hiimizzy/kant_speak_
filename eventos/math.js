let currentScore = 0;
let currentOperation = { num1: null, operator: null, num2: null };

// Elementos DOM
const scoreSpan = document.getElementById('scoreValue');
const starsContainer = document.getElementById('starsContainer');
const dropNum1 = document.getElementById('dropNum1');
const dropOperator = document.getElementById('dropOperator');
const dropNum2 = document.getElementById('dropNum2');
const answerInput = document.getElementById('answerInput');
const checkBtn = document.getElementById('checkBtn');
const speakEqBtn = document.getElementById('speakEqBtn');
const clearBtn = document.getElementById('clearBtn');
const paletteDiv = document.getElementById('palette');
const feedbackContainer = document.getElementById('feedbackContainer');

if (!answerInput) console.error('Elemento "answerInput" não encontrado!');

// Números disponíveis na paleta
const numbers = [
  0,1,2,3,4,5,6,7,8,9,
  10,11,12,13,14,15,16,17,18,19,20,
  30,40,50,60,70,80,90,
  100,200,300,400,500,600,700,800,900,
  240, 399, 639
];
const operators = ['+', '-'];

// ========== Conversão número para palavra (0-999) ==========
function numberToWords(num) {
  if (num === 0) return "zero";
  const ones = ["", "one", "two", "three", "four", "five", "six", "seven", "eight", "nine"];
  const teens = ["ten", "eleven", "twelve", "thirteen", "fourteen", "fifteen", "sixteen", "seventeen", "eighteen", "nineteen"];
  const tens = ["", "", "twenty", "thirty", "forty", "fifty", "sixty", "seventy", "eighty", "ninety"];

  let words = "";
  if (num >= 100) {
    words += ones[Math.floor(num / 100)] + " hundred ";
    num %= 100;
    if (num > 0) words += "and ";
  }
  if (num >= 20) {
    words += tens[Math.floor(num / 10)];
    if (num % 10 > 0) words += " " + ones[num % 10];
  } else if (num >= 10) {
    words += teens[num - 10];
  } else if (num > 0) {
    words += ones[num];
  }
  return words.trim();
}

// Pronunciar uma equação (recebendo os parâmetros)
function speakEquation(num1, operator, num2, result) {
  if (!('speechSynthesis' in window)) return;
  const wordsNum1 = numberToWords(num1);
  const wordsNum2 = numberToWords(num2);
  const wordsResult = numberToWords(result);
  const opWord = operator === '+' ? 'plus' : 'minus';
  const sentence = `${wordsNum1} ${opWord} ${wordsNum2} equals ${wordsResult}`;
  window.speechSynthesis.cancel();
  const utterance = new SpeechSynthesisUtterance(sentence);
  utterance.lang = 'en-US';
  utterance.rate = 0.85;
  window.speechSynthesis.speak(utterance);
}

// Função para falar a equação atual (sem verificar resposta)
function speakCurrentEquation() {
  if (currentOperation.num1 === null || currentOperation.operator === null || currentOperation.num2 === null) {
    showFeedback('Please drag numbers and an operator first!', false);
    return;
  }
  let correctResult;
  if (currentOperation.operator === '+') {
    correctResult = currentOperation.num1 + currentOperation.num2;
  } else {
    correctResult = currentOperation.num1 - currentOperation.num2;
  }
  speakEquation(currentOperation.num1, currentOperation.operator, currentOperation.num2, correctResult);
}

// ========== API ==========
async function fetchScore() {
  try {
    const resp = await fetch('api.php?action=getScore');
    const data = await resp.json();
    currentScore = data.score || 0;
    scoreSpan.innerText = currentScore;
    updateStars(currentScore);
  } catch (err) {
    console.error('fetchScore:', err);
  }
}

function updateStars(score) {
  const stars = Math.min(5, Math.floor(score / 30));
  const starSpans = starsContainer.querySelectorAll('.star');
  starSpans.forEach((star, idx) => {
    if (idx < stars) star.classList.add('lit');
    else star.classList.remove('lit');
  });
}

async function sendCheck() {
  const answerInputEl = document.getElementById('answerInput');
  if (!answerInputEl) {
    showFeedback('Error: answer field not found!', false);
    return;
  }
  const answerValue = answerInputEl.value.trim();
  if (currentOperation.num1 === null || currentOperation.operator === null ||
      currentOperation.num2 === null || answerValue === '') {
    showFeedback('Complete all fields and type the answer!', false);
    return;
  }
  const answerNum = parseInt(answerValue, 10);
  if (isNaN(answerNum)) {
    showFeedback('Please enter a valid number!', false);
    return;
  }
  let correctResult;
  if (currentOperation.operator === '+') {
    correctResult = currentOperation.num1 + currentOperation.num2;
  } else {
    correctResult = currentOperation.num1 - currentOperation.num2;
  }

  const formData = new FormData();
  formData.append('action', 'check');
  formData.append('activity', 'mathbuilder');
  formData.append('num1', currentOperation.num1);
  formData.append('operator', currentOperation.operator);
  formData.append('num2', currentOperation.num2);
  formData.append('answer', answerNum);

  try {
    const resp = await fetch('api.php', { method: 'POST', body: formData });
    const data = await resp.json();
    if (data.success) {
      const isCorrect = data.feedback.includes('Correct');
      showFeedback(data.feedback, isCorrect);
      if (data.score !== undefined) {
        currentScore = data.score;
        scoreSpan.innerText = currentScore;
        updateStars(currentScore);
      }
      // Sempre fala a equação correta (didático)
      speakEquation(currentOperation.num1, currentOperation.operator, currentOperation.num2, correctResult);
      if (isCorrect) {
        clearAllZones();
      }
    } else {
      showFeedback('Error!', false);
    }
  } catch (err) {
    console.error(err);
    showFeedback('Connection error!', false);
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

function handleDragEnd() {
  draggedItem = null;
}

function setupDropZone(zoneElement, zoneName) {
  if (!zoneElement) return;
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
  if (zoneName === 'answer') return;
  currentOperation[zoneName] = isNaN(value) ? value : Number(value);
  const zoneEl = document.getElementById(`drop${zoneName.charAt(0).toUpperCase() + zoneName.slice(1)}`);
  if (zoneEl) zoneEl.innerText = value;
}

function clearAllZones() {
  currentOperation = { num1: null, operator: null, num2: null };
  dropNum1.innerText = '?';
  dropOperator.innerText = '?';
  dropNum2.innerText = '?';
  const answerInputEl = document.getElementById('answerInput');
  if (answerInputEl) answerInputEl.value = '';
}

// ========== Paleta ==========
function buildPalette() {
  paletteDiv.innerHTML = '';
  numbers.forEach(n => {
    const card = document.createElement('div');
    card.className = 'drag-item number-card';
    card.setAttribute('draggable', 'true');
    card.setAttribute('data-value', n);
    card.innerText = n;
    card.addEventListener('dragstart', handleDragStart);
    card.addEventListener('dragend', handleDragEnd);
    card.addEventListener('touchstart', handleTouchStart);
    card.addEventListener('touchmove', handleTouchMove);
    card.addEventListener('touchend', handleTouchEnd);
    paletteDiv.appendChild(card);
  });
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

// ========== Suporte toque ==========
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
  const zones = [dropNum1, dropOperator, dropNum2];
  touchTargetZone = zones.find(zone => zone && zone.contains(elemUnderTouch)) || null;
  zones.forEach(zone => zone && zone.classList.remove('drag-over'));
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

// ========== Feedback visual ==========
function showFeedback(message, isCorrect) {
  feedbackContainer.innerHTML = '';
  const toast = document.createElement('div');
  toast.className = `feedback-toast px-6 py-3 rounded-full shadow-xl font-bold text-white text-center ${isCorrect ? 'bg-green-500' : 'bg-red-500'}`;
  toast.textContent = message;
  feedbackContainer.appendChild(toast);
  setTimeout(() => toast.remove(), 2500);
}

// ========== Inicialização ==========
function init() {
  fetchScore();
  buildPalette();
  setupDropZone(dropNum1, 'num1');
  setupDropZone(dropOperator, 'operator');
  setupDropZone(dropNum2, 'num2');
  if (checkBtn) checkBtn.addEventListener('click', sendCheck);
  if (speakEqBtn) speakEqBtn.addEventListener('click', speakCurrentEquation);
  if (clearBtn) clearBtn.addEventListener('click', clearAllZones);
}
init();