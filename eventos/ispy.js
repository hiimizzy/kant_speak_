let currentItem = null;
let currentScore = 0;
let currentOptions = [];

const scoreSpan = document.getElementById('scoreValue');
const itemEmoji = document.getElementById('itemEmoji');
const listenBtn = document.getElementById('listenHintBtn');
const optionsDiv = document.getElementById('optionsContainer');
const nextBtn = document.getElementById('nextBtn');
const feedbackContainer = document.getElementById('feedbackContainer');


async function fetchCurrentItem() {
  try {
    const resp = await fetch('api.php?action=getItem&activity=ispy');
    const data = await resp.json();
    if (data.success) {
      currentItem = data.item;
      itemEmoji.innerText = currentItem.emoji || '🔍';
      await generateOptions();
      renderOptions();
    }
  } catch (err) {
    console.error(err);
    showFeedback('❌ Erro ao carregar desafio');
  }
}

async function generateOptions() {
  // Palavras possíveis (fixas para simplificar, ou buscar da API)
  const allWords = ['Apple', 'Ball', 'Cat', 'Dog', 'Sun', 'Fish'];
  let others = allWords.filter(w => w !== currentItem.word);
  others = others.sort(() => 0.5 - Math.random()).slice(0, 2); // pega 2 aleatórias
  currentOptions = [currentItem.word, ...others];
  currentOptions.sort(() => 0.5 - Math.random()); // embaralha
}

function renderOptions() {
  optionsDiv.innerHTML = '';
  currentOptions.forEach(word => {
    const btn = document.createElement('button');
    btn.innerText = word;
    btn.className = 'option-btn bg-gray-200 hover:bg-gray-300 px-6 py-3 rounded-full text-lg font-bold shadow-sm';
    btn.onclick = () => checkAnswer(word);
    optionsDiv.appendChild(btn);
  });
}

async function checkAnswer(selected) {
  const formData = new FormData();
  formData.append('action', 'check');
  formData.append('activity', 'ispy');
  formData.append('resposta', selected);
  try {
    const resp = await fetch('api.php', { method: 'POST', body: formData });
    const data = await resp.json();
    if (data.success) {
      showFeedback(data.feedback.includes('Great') ? '✅ ' + data.feedback : '❌ ' + data.feedback);
      if (data.score !== undefined) {
        currentScore = data.score;
        scoreSpan.innerText = currentScore;
      }
      if (data.feedback.includes('Great')) {
        await fetchCurrentItem(); 
      }
    }
  } catch (err) {
    console.error(err);
    showFeedback('❌ Erro ao verificar resposta');
  }
}

function showFeedback(message) {
  feedbackContainer.innerHTML = '';
  const toast = document.createElement('div');
  toast.className = 'feedback-toast bg-green-600 text-white px-6 py-3 rounded-full shadow-xl font-bold text-center';
  toast.textContent = message;
  feedbackContainer.appendChild(toast);
  setTimeout(() => toast.remove(), 2500);
}

function playHint() {
  if (!currentItem) return;
  const utterance = new SpeechSynthesisUtterance(`I spy with my little eye... something that ${currentItem.hint}`);
  utterance.lang = 'en-US';
  utterance.rate = 0.9;
  window.speechSynthesis.cancel();
  window.speechSynthesis.speak(utterance);
}

async function nextChallenge() {
  try {
    await fetch('api.php?action=next&activity=ispy');
    await fetchCurrentItem();
  } catch (err) {
    showFeedback('❌ Erro ao avançar');
  }
}

async function fetchScore() {
  try {
    const resp = await fetch('api.php?action=getScore');
    const data = await resp.json();
    if (data.score !== undefined) {
      currentScore = data.score;
      scoreSpan.innerText = currentScore;
    }
  } catch (err) {}
}

// Eventos
listenBtn.addEventListener('click', playHint);
nextBtn.addEventListener('click', nextChallenge);

async function init() {
  await fetchScore();
  await fetchCurrentItem();
}
init();