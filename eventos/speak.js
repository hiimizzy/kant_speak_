const words = [
  { word: "CAT", emoji: "🐱" }, 
  { word: "DOG", emoji: "🐕" }, 
  { word: "BIRD", emoji: "🐦" },
  { word: "FISH", emoji: "🐠" }, 
  { word: "APPLE", emoji: "🍎" }, 
  { word: "STAR", emoji: "⭐" },
  { word: "SUN", emoji: "☀️" }, 
  { word: "BOOK", emoji: "📚" }, 
  { word: "HOUSE", emoji: "🏠" },
  { word: "FLOWER", emoji: "🌺" }, 
  {word: "GNARLY", emoji: "🤙"},
  { word: "LION", emoji: "🦁", translation: "leão" },
  { word: "TIGER", emoji: "🐯", translation: "tigre" },
  { word: "KOALA", emoji: "🐨", translation: "coala" },
  { word: "BEE", emoji: "🐝", translation: "abelha" },
  { word: "ANT", emoji: "🐜", translation: "formiga" },
  { word: "BEAR", emoji: "🐻", translation: "urso" },
  { word: "SNAIL", emoji: "🐌", translation: "caracol" },
  { word: "RABBIT", emoji: "🐰", translation: "coelho" },
  { word: "JELLYFISH", emoji: "🪼", translation: "água-viva" },
  { word: "SHARK", emoji: "🦈", translation: "tubarão" }
];
let currentIndex = 0;
let score = parseInt(localStorage.getItem('totalScore') || '0');
let isListening = false;
let recognition = null;

if ('SpeechRecognition' in window || 'webkitSpeechRecognition' in window) {
  const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
  recognition = new SpeechRecognition();
  recognition.lang = 'en-US';
  recognition.interimResults = false;
}

const scoreSpan = document.getElementById('scoreValue');
const starsContainer = document.getElementById('starsContainer');
const wordEmojiSpan = document.getElementById('wordEmoji');
const recognizedContainer = document.getElementById('recognizedContainer');
const recognizedTextSpan = document.getElementById('recognizedText');
const speakBtn = document.getElementById('speakBtn');
const hintBtn = document.getElementById('hintBtn');
const nextBtn = document.getElementById('nextBtn');
const micVisual = document.getElementById('micVisual');
const feedbackContainer = document.getElementById('feedbackContainer');

function updateScoreUI() {
  scoreSpan.textContent = score;
  localStorage.setItem('totalScore', score);
  const stars = Math.min(5, Math.floor(score / 30));
  const starSpans = starsContainer.querySelectorAll('.star');
  starSpans.forEach((star, idx) => {
    star.style.opacity = idx < stars ? '1' : '0.3';
    star.style.filter = idx < stars ? 'drop-shadow(0 0 4px gold)' : 'none';
  });
}

function addPoints(points) {
  score += points;
  updateScoreUI();
  showFeedback('correct', `🎉 +${points} pontos!`);
}

function showFeedback(type, message) {
  feedbackContainer.innerHTML = '';
  const toast = document.createElement('div');
  toast.className = `feedback-toast px-6 py-3 rounded-full shadow-xl font-bold text-white text-center ${type === 'correct' ? 'bg-green-500' : 'bg-red-500'}`;
  toast.textContent = message;
  feedbackContainer.appendChild(toast);
  setTimeout(() => toast.remove(), 2000);
}

function playHint() {
  if (!('speechSynthesis' in window)) return showFeedback('incorrect', 'Áudio não suportado.');
  const utterance = new SpeechSynthesisUtterance(words[currentIndex].word);
  utterance.lang = 'en-US';
  utterance.rate = 0.8;
  window.speechSynthesis.cancel();
  window.speechSynthesis.speak(utterance);
}

function startListening() {
  if (!recognition) {
    showFeedback('incorrect', 'Reconhecimento de voz não suportado. Use Chrome ou Edge.');
    return;
  }
  if (isListening) return;
  isListening = true;
  speakBtn.disabled = true;
  speakBtn.innerHTML = '<span class="animate-pulse">🔴</span> Ouvindo...';
  micVisual.classList.add('listening');
  recognition.start();

  recognition.onresult = (event) => {
    const recognized = event.results[0][0].transcript.trim();
    recognizedTextSpan.textContent = recognized;
    recognizedContainer.classList.remove('hidden');
    recognizedContainer.classList.add('bounce-in');
    const currentWord = words[currentIndex].word;
    if (recognized.toLowerCase() === currentWord.toLowerCase()) {
      addPoints(15);
      showFeedback('correct', `🎉 Correto! +15 pontos`);
    } else {
      showFeedback('incorrect', `❌ Você disse "${recognized}". A palavra correta é ${currentWord}.`);
    }
    isListening = false;
    speakBtn.disabled = false;
    speakBtn.innerHTML = '🎤 Falar';
    micVisual.classList.remove('listening');
  };
  recognition.onerror = () => {
    showFeedback('incorrect', 'Erro ao capturar voz. Tente novamente.');
    isListening = false;
    speakBtn.disabled = false;
    speakBtn.innerHTML = '🎤 Falar';
    micVisual.classList.remove('listening');
  };
  recognition.onend = () => {
    if (isListening) {
      isListening = false;
      speakBtn.disabled = false;
      speakBtn.innerHTML = '🎤 Falar';
      micVisual.classList.remove('listening');
    }
  };
}

function nextWord() {
  currentIndex = (currentIndex + 1) % words.length;
  wordEmojiSpan.textContent = words[currentIndex].emoji;
  recognizedContainer.classList.add('hidden');
  feedbackContainer.innerHTML = '';
}

speakBtn.addEventListener('click', startListening);
hintBtn.addEventListener('click', playHint);
nextBtn.addEventListener('click', nextWord);
updateScoreUI();