// ========== INSTRUMENTATION ==========
const SESSION_ID = localStorage.getItem('kant_session') || Date.now().toString();
localStorage.setItem('kant_session', SESSION_ID);

function logEvent(eventType, payload) {
    fetch('./instrument.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            session: SESSION_ID,
            activity: 'buildword',
            event: eventType,
            timestamp: Date.now() / 1000,
            data: payload
        })
    }).catch(e => console.warn('Logging failed:', e));
}

// ========== GAME STATE ==========
let currentWordObj = null;
let currentScore = 0;
let currentSyllables = [];
let userOrder = [];
let startTime = null;
let attempts = 0;

// Elementos DOM
const scoreSpan = document.getElementById('scoreValue');
const wordImage = document.getElementById('wordImage');
const buildArea = document.getElementById('buildArea');
const syllablesContainer = document.getElementById('syllablesContainer');
const checkBtn = document.getElementById('checkBtn');
const resetBtn = document.getElementById('resetBtn');
const feedbackContainer = document.getElementById('feedbackContainer');

// ========== API Calls ==========
async function fetchScore() {
    try {
        const resp = await fetch('./api.php?action=getScore');
        const data = await resp.json();
        currentScore = data.score || 0;
        scoreSpan.textContent = currentScore;
    } catch (err) { console.error('fetchScore:', err); }
}

async function fetchCurrentItem() {
    try {
        const resp = await fetch('./api.php?action=getItem&activity=buildword');
        const data = await resp.json();
        if (data.success) {
            currentWordObj = data.item;
            wordImage.innerText = currentWordObj.emoji;
            currentSyllables = [...currentWordObj.syllables];
            renderBuildArea();
            renderSyllables();
            startTime = performance.now();
            logEvent('session_start', {
                word: currentWordObj.word,
                syllables: currentSyllables,
                level: localStorage.getItem('buildword_level') || 1
            });
        }
    } catch (err) {
        console.error('fetchCurrentItem:', err);
        showFeedback('Error loading word', false);
    }
}

async function sendAnswer(wordFormed) {
    const reactionTime = (performance.now() - startTime) / 1000;
    const formData = new FormData();
    formData.append('action', 'check');
    formData.append('activity', 'buildword');
    formData.append('resposta', wordFormed);
    try {
        const resp = await fetch('./api.php', { method: 'POST', body: formData });
        const data = await resp.json();
        if (data.success) {
            const isCorrect = data.feedback.includes('Great');
            showFeedback(data.feedback, isCorrect);
            // Log da verificação
            logEvent('check_manual', {
                userAnswer: wordFormed,
                correct: isCorrect,
                pointsEarned: isCorrect ? 10 : 0,
                reactionTime: reactionTime,
                attempts: attempts
            });
            if (data.score !== undefined) {
                currentScore = data.score;
                scoreSpan.textContent = currentScore;
            }
            if (isCorrect) {
                logEvent('word_complete', {
                    word: currentWordObj.word,
                    correct: true,
                    totalTime: reactionTime,
                    attempts: attempts + 1
                });
                await fetchCurrentItem(); // próxima palavra
            }
        } else {
            showFeedback('Error verifying', false);
        }
    } catch (err) {
        console.error('sendAnswer:', err);
        showFeedback('Connection error', false);
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
    logEvent('syllable_click', { syllable, timeFromStart: (performance.now() - startTime) / 1000 });
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
        logEvent('drop', { syllable: originalText, slotIndex, correct: false, reason: 'slot_occupied', timeFromStart: (performance.now() - startTime) / 1000 });
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

        // Log do drop
        logEvent('drop', {
            syllable: originalText,
            slotIndex,
            correct: true,
            timeFromStart: (performance.now() - startTime) / 1000
        });

        if (userOrder.every(v => v !== null)) {
            const formedWord = userOrder.join('');
            attempts++;
            sendAnswer(formedWord);
        }
    } else {
        showFeedback('This syllable has already been used!', false);
        logEvent('drop', { syllable: originalText, slotIndex, correct: false, reason: 'already_used', timeFromStart: (performance.now() - startTime) / 1000 });
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
            logEvent('drag_start', { syllable: syl, timeFromStart: (performance.now() - startTime) / 1000 });
        });
        card.addEventListener('dragend', () => {
            card.classList.remove('dragging');
        });
        syllablesContainer.appendChild(card);
    });
}

function resetCurrentWord() {
    userOrder = new Array(currentSyllables.length).fill(null);
    const slots = document.querySelectorAll('.drop-zone');
    slots.forEach((slot) => {
        slot.innerText = '?';
        slot.classList.remove('filled');
    });
    const cards = document.querySelectorAll('.syllable-card');
    cards.forEach(card => {
        card.classList.remove('used');
        card.style.opacity = '1';
        card.setAttribute('draggable', 'true');
        card.style.cursor = 'grab';
    });
    showFeedback('Word reset! Try again.', false);
    logEvent('reset', { reason: 'user', timeFromStart: (performance.now() - startTime) / 1000 });
}

function manualCheck() {
    if (userOrder.some(v => v === null)) {
        showFeedback('Complete all slots first!', false);
        logEvent('check_manual', { userAnswer: 'incomplete', correct: false, reason: 'incomplete_slots' });
        return;
    }
    const formedWord = userOrder.join('');
    attempts++;
    sendAnswer(formedWord);
}

// ========== Inicialização ==========
async function init() {
    await fetchScore();
    await fetchCurrentItem();
    checkBtn.addEventListener('click', manualCheck);
    resetBtn.addEventListener('click', resetCurrentWord);
    logEvent('instrumentation_ready', { version: '2.0' });
}

init();

