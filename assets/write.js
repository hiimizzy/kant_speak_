const words = [
  { word: "DOG", emoji: "🐕", translation: "cachorro" },
  { word: "CAT", emoji: "🐱", translation: "gato" },
  { word: "SUN", emoji: "☀️", translation: "sol" },
  { word: "APPLE", emoji: "🍎", translation: "maçã" },
  { word: "BOOK", emoji: "📚", translation: "livro" },
  { word: "STAR", emoji: "⭐", translation: "estrela" },
  { word: "FISH", emoji: "🐠", translation: "peixe" },
  { word: "HOUSE", emoji: "🏠", translation: "casa" },
  { word: "BIRD", emoji: "🐦", translation: "pássaro" },
  { word: "FLOWER", emoji: "🌺", translation: "flor" }
];
let currentIndex = 0;
let score = parseInt(localStorage.getItem('totalScore') || '0');

const scoreSpan = document.getElementById('scoreValue');
const starsContainer = document.getElementById('starsContainer');
const wordEmojiSpan = document.getElementById('wordEmoji');
const translationSpan = document.getElementById('translationText');
const wordInput = document.getElementById('wordInput');
const checkBtn = document.getElementById('checkBtn');
const hintBtn = document.getElementById('hintBtn');
const nextBtn = document.getElementById('nextBtn');
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

function handleCheck() {
  const userInput = wordInput.value.trim();
  if (userInput === "") {
    showFeedback('incorrect', 'Digite uma palavra!');
    return;
  }
  const currentWord = words[currentIndex].word;
  if (userInput.toLowerCase() === currentWord.toLowerCase()) {
    addPoints(10);
    showFeedback('correct', '🎉 Correto! +10 pontos');
    checkBtn.disabled = true;
    wordInput.disabled = true;
  } else {
    showFeedback('incorrect', `❌ Incorreto. A palavra correta é ${currentWord}.`);
  }
}

function nextWord() {
  currentIndex = (currentIndex + 1) % words.length;
  const current = words[currentIndex];
  wordEmojiSpan.textContent = current.emoji;
  translationSpan.textContent = `(${current.translation})`;
  wordInput.value = '';
  wordInput.disabled = false;
  checkBtn.disabled = false;
  feedbackContainer.innerHTML = '';
}

checkBtn.addEventListener('click', handleCheck);
hintBtn.addEventListener('click', playHint);
nextBtn.addEventListener('click', nextWord);
wordInput.addEventListener('keydown', (e) => { if (e.key === 'Enter') handleCheck(); });
wordInput.addEventListener('input', () => { checkBtn.disabled = (wordInput.value.trim() === ""); });
updateScoreUI();