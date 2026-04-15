const words = [
  { word: "APPLE", emoji: "🍎", translation: "maçã" },
  { word: "DOG", emoji: "🐕", translation: "cachorro" },
  { word: "SUN", emoji: "☀️", translation: "sol" },
  { word: "HOUSE", emoji: "🏠", translation: "casa" },
  { word: "CAT", emoji: "🐱", translation: "gato" },
  { word: "CAR", emoji: "🚗", translation: "carro" },
  { word: "BIRD", emoji: "🐦", translation: "pássaro" },
  { word: "FISH", emoji: "🐠", translation: "peixe" },
  { word: "BOOK", emoji: "📚", translation: "livro" },
  { word: "STAR", emoji: "⭐", translation: "estrela" },

  { word: "LION", emoji: "🦁", translation: "leão" },
  { word: "TIGER", emoji: "🐯", translation: "tigre" },
  { word: "KOALA", emoji: "🐨", translation: "coala" },
  { word: "BEE", emoji: "🐝", translation: "abelha" },
  { word: "ANT", emoji: "🐜", translation: "formiga" },
  { word: "BEAR", emoji: "🐻", translation: "urso" },
  { word: "SNAIL", emoji: "🐌", translation: "caracol" },
  { word: "RABBIT", emoji: "🐰", translation: "coelho" },
  { word: "JELLYFISH", emoji: "🪼", translation: "água-viva" },
  { word: "SHARK", emoji: "🦈", translation: "tubarão" },
];
let currentIndex = 0;
let score = parseInt(localStorage.getItem('totalScore') || '0');
let revealed = false;

const scoreSpan = document.getElementById('scoreValue');
const starsContainer = document.getElementById('starsContainer');
const wordEmojiSpan = document.getElementById('wordEmoji');
const revealArea = document.getElementById('revealArea');
const revealWordSpan = document.getElementById('revealWord');
const revealTranslationSpan = document.getElementById('revealTranslation');
const listenBtn = document.getElementById('listenBtn');
const revealBtn = document.getElementById('revealBtn');
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
  showFeedback('correct', `🎉 +${points} pontos! Palavra revelada.`);
}

function showFeedback(type, message) {
  feedbackContainer.innerHTML = '';
  const toast = document.createElement('div');
  toast.className = `feedback-toast px-6 py-3 rounded-full shadow-xl font-bold text-white text-center ${type === 'correct' ? 'bg-green-500' : 'bg-red-500'}`;
  toast.textContent = message;
  feedbackContainer.appendChild(toast);
  setTimeout(() => toast.remove(), 2000);
}

function playWord() {
  if (!('speechSynthesis' in window)) return showFeedback('incorrect', 'Áudio não suportado.');
  const utterance = new SpeechSynthesisUtterance(words[currentIndex].word);
  utterance.lang = 'en-US';
  utterance.rate = 0.8;
  window.speechSynthesis.cancel();
  window.speechSynthesis.speak(utterance);
}

function revealWord() {
  if (revealed) return;
  revealed = true;
  const current = words[currentIndex];
  revealWordSpan.textContent = current.word;
  revealTranslationSpan.textContent = `(${current.translation})`;
  revealArea.classList.remove('hidden');
  revealArea.classList.add('bounce-in');
  addPoints(5);
  revealBtn.disabled = true;
  revealBtn.classList.add('opacity-50', 'cursor-not-allowed');
}

function nextWord() {
  currentIndex = (currentIndex + 1) % words.length;
  const current = words[currentIndex];
  wordEmojiSpan.textContent = current.emoji;
  revealArea.classList.add('hidden');
  revealed = false;
  revealBtn.disabled = false;
  revealBtn.classList.remove('opacity-50', 'cursor-not-allowed');
  feedbackContainer.innerHTML = '';
}

listenBtn.addEventListener('click', playWord);
revealBtn.addEventListener('click', revealWord);
nextBtn.addEventListener('click', nextWord);
updateScoreUI();