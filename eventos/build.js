let currentWordObj = null;
let currentScore = 0;
let currentSyllables = [];
let userOrder = [];
let syllableCards = [];

// Elementos DOM
const scoreSpan = document.getElementById('scoreValue');
const wordImage = document.getElementById('wordImage');
const buildArea = document.getElementById('buildArea');
const syllablesContainer = document.getElementById('syllablesContainer');
const checkBtn = document.getElementById('checkBtn');
const resetBtn = document.getElementById('resetBtn');
const feedbackContainer = document.getElementById('feedbackContainer');

// API Endpoints
async function fetchScore() {
  try {
    const resp = await fetch('api.php?action=getScore');
    const data = await resp.json();
    currentScore = data.score || 0;
    scoreSpan.textContent = currentScore;
  } catch (err) {
    console.error('fetchScore:', err);
  }
}

async function fetchCurrentItem() {
  try {
    const resp = await fetch('api.php?action=getItem&activity=buildword');
    const data = await resp.json();
    if (data.success) {
      currentWordObj = data.item;
      wordImage.innerText = currentWordObj.emoji;
      currentSyllables = [...currentWordObj.syllables];
      renderBuildArea();
      renderSyllables();
    }
  } catch (err) {
    console.error('fetchCurrentItem:', err);
    showFeedback('Erro ao carregar palavra', false);
  }
}

async function sendAnswer(wordFormed) {
  const formData = new FormData();
  formData.append('action', 'check');
  formData.append('activity', 'buildword');
  formData.append('resposta', wordFormed);
  try {
    const resp = await fetch('api.php', { method: 'POST', body: formData });
    const data = await resp.json();
    if (data.success) {
      showFeedback(data.feedback, data.feedback.includes('Great'));
      if (data.score !== undefined) {
        currentScore = data.score;
        scoreSpan.textContent = currentScore;
      }
      if (data.feedback.includes('Great')) {
        // Palavra correta: avança para a próxima
        await fetchCurrentItem(); // já avançou no backend
      }
    } else {
      showFeedback('Erro ao verificar', false);
    }
  } catch (err) {
    console.error('sendAnswer:', err);
    showFeedback('Erro de conexão', false);
  }
}

// ========== UI Functions ==========
function showFeedback(message, isCorrect) {
  feedbackContainer.innerHTML = '';
  const toast = document.createElement('div');
  toast.className = `feedback-toast px-6 py-3 rounded-full shadow-xl font-bold text-white text-center ${isCorrect ? 'bg-green-500' : 'bg-red-500'}`;
  toast.textContent = message;
  feedbackContainer.appendChild(toast);
  setTimeout(() => toast.remove(), 2500);
}

function speakSyllable(syllable) {
  if (!('speechSynthesis' in window)) return;
  const utterance = new SpeechSynthesisUtterance(syllable.toLowerCase());
  utterance.lang = 'en-US';
  utterance.rate = 0.9;
  window.speechSynthesis.cancel();
  window.speechSynthesis.speak(utterance);
}

function renderBuildArea() {
  buildArea.innerHTML = '';
  userOrder = new Array(currentSyllables.length).fill(null);
  for (let i = 0; i < currentSyllables.length; i++) {
    const slot = document.createElement('div');
    slot.className = 'drop-zone w-28 h-28 flex items-center justify-center text-2xl font-bold';
    slot.setAttribute('data-slot', i);
    slot.innerText = '?';
    slot.addEventListener('dragover', (e) => {
      e.preventDefault();
      slot.classList.add('drag-over');
    });
    slot.addEventListener('dragleave', () => {
      slot.classList.remove('drag-over');
    });
    slot.addEventListener('drop', handleDrop);
    buildArea.appendChild(slot);
  }
}

function handleDrop(e) {
  e.preventDefault();
  const slot = e.target.closest('.drop-zone');
  if (!slot) return;
  slot.classList.remove('drag-over');
  const draggedData = e.dataTransfer.getData('text/plain');
  if (!draggedData) return;
  const { syllableId, originalText } = JSON.parse(draggedData);
  const slotIndex = parseInt(slot.getAttribute('data-slot'));
  if (userOrder[slotIndex] !== null) {
    showFeedback('This slot is already filled!', false);
    return;
  }
  const draggedCard = document.querySelector(`.syllable-card[data-id='${syllableId}']`);
  if (draggedCard && !draggedCard.classList.contains('used')) {
    draggedCard.classList.add('used');
    draggedCard.style.opacity = '0.4';
    draggedCard.setAttribute('draggable', 'false');
    draggedCard.style.cursor = 'default';
    slot.innerText = originalText;
    slot.classList.add('filled');
    userOrder[slotIndex] = originalText;
    if (userOrder.every(v => v !== null)) {
      // Todos os slots preenchidos – verifica automaticamente
      const formedWord = userOrder.join('');
      sendAnswer(formedWord);
    }
  } else {
    showFeedback('This syllable has already been used!', false);
  }
}

function renderSyllables() {
  syllablesContainer.innerHTML = '';
  const shuffled = [...currentSyllables];
  for (let i = shuffled.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [shuffled[i], shuffled[j]] = [shuffled[j], shuffled[i]];
  }
  shuffled.forEach((syl, idx) => {
    const card = document.createElement('div');
    card.className = 'syllable-card';
    card.setAttribute('data-id', `syl-${Date.now()}-${idx}`);
    card.setAttribute('data-syllable', syl);
    card.setAttribute('draggable', 'true');
    card.innerText = syl;
    card.addEventListener('click', (e) => {
      e.stopPropagation();
      speakSyllable(syl);
    });
    card.addEventListener('dragstart', (e) => {
      e.dataTransfer.setData('text/plain', JSON.stringify({
        syllableId: card.getAttribute('data-id'),
        originalText: syl
      }));
      e.dataTransfer.effectAllowed = 'move';
      card.classList.add('dragging');
    });
    card.addEventListener('dragend', () => {
      card.classList.remove('dragging');
    });
    syllablesContainer.appendChild(card);
  });
}

function resetCurrentWord() {
  // Limpa slots
  userOrder = new Array(currentSyllables.length).fill(null);
  const slots = document.querySelectorAll('.drop-zone');
  slots.forEach((slot) => {
    slot.innerText = '?';
    slot.classList.remove('filled');
  });
  // Restaura sílabas
  const cards = document.querySelectorAll('.syllable-card');
  cards.forEach(card => {
    card.classList.remove('used');
    card.style.opacity = '1';
    card.setAttribute('draggable', 'true');
    card.style.cursor = 'grab';
  });
  showFeedback('Reset! Try again.', false);
}

function manualCheck() {
  if (userOrder.some(v => v === null)) {
    showFeedback('Complete all slots first!', false);
    return;
  }
  const formedWord = userOrder.join('');
  sendAnswer(formedWord);
}

// ========== Inicialização ==========
async function init() {
  await fetchScore();
  await fetchCurrentItem();
  checkBtn.addEventListener('click', manualCheck);
  resetBtn.addEventListener('click', resetCurrentWord);
}

init();