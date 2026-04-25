const numbers = [
  { word: "ZERO", emoji: "0️⃣", translation: "zero" },
  { word: "ONE", emoji: "1️⃣", translation: "um" },
  { word: "TWO", emoji: "2️⃣", translation: "dois" },
  { word: "THREE", emoji: "3️⃣", translation: "três" },
  { word: "FOUR", emoji: "4️⃣", translation: "quatro" },
  { word: "FIVE", emoji: "5️⃣", translation: "cinco" },
  { word: "SIX", emoji: "6️⃣", translation: "seis" },
  { word: "SEVEN", emoji: "7️⃣", translation: "sete" },
  { word: "EIGTH", emoji: "8️⃣", translation: "oito" },
  { word: "NINE", emoji: "9️⃣", translation: "nove" },
  { word: "TEN", emoji: "🔟", translation: "dez" },
  { word: "ELEVEN", emoji: "1️⃣1️⃣", translation: "onze" },
  { word: "TWELVE", emoji: "1️⃣2️⃣", translation: "doze" },
  { word: "THIRTEEN", emoji: "1️⃣3️⃣", translation: "treze" },
  { word: "FOURTEEN", emoji: "1️⃣4️⃣", translation: "quartoze" },
  { word: "FIFTEEN", emoji: "1️⃣5️⃣", translation: "quinze" },
  { word: "SIXTEEN", emoji: "1️⃣6️⃣", translation: "dezesseis" },
  { word: "SEVENTEEN", emoji: "1️⃣7️⃣", translation: "dezessete" },
  { word: "EIGHTEEN", emoji: "1️⃣8️⃣", translation: "dezoito" },
  { word: "NINETEEN", emoji: "1️⃣9️⃣", translation: "dezenove" },
  { word: "TWINTY", emoji: "2️⃣0️⃣", translation: "vinte" },
  { word: "TWINTY ONE", emoji: "2️⃣1️⃣", translation: "vinte-um" },
  { word: "TWINTY TWO", emoji: "2️⃣2️⃣", translation: "vinte-dois" },
  { word: "TWINTY THREE", emoji: "2️⃣3️⃣", translation: "vinte-três" },
  { word: "TWINTY FOUR", emoji: "2️⃣4️⃣", translation: "vinte-quatro" },
  { word: "TWINTY FIVE", emoji: "2️⃣5️⃣", translation: "vinte-cinco" },
  { word: "TWINTY SIX", emoji: "2️⃣6️⃣", translation: "vinte-seis" },
  { word: "TWINTY SEVEN", emoji: "2️⃣7️⃣", translation: "vinte-sete" },
  { word: "TWINTY EIGHT", emoji: "2️⃣8️⃣", translation: "vinte-oito" },
  { word: "TWINTY NINE", emoji: "2️⃣9️⃣", translation: "vinte-nove" },
  { word: "THIRTY", emoji: "3️⃣0️⃣", translation: "trinta" },
  { word: "THIRTY ONE", emoji: "3️⃣1️⃣", translation: "trinta-um" },
  { word: "THIRTY TWO", emoji: "3️⃣2️⃣", translation: "trinta-dois" },
  { word: "THIRTY THREE", emoji: "3️⃣3️⃣", translation: "trinta-três" },
  { word: "THIRTY FOUR", emoji: "3️⃣4️⃣", translation: "trinta-quatro" },
  { word: "THIRTY FIVE", emoji: "3️⃣5️⃣", translation: "trinta-cinco" },
  { word: "THIRTY SIX", emoji: "3️⃣6️⃣", translation: "trinta-seis" },
  { word: "THIRTY SEVEN", emoji: "3️⃣7️⃣", translation: "trinta-sete" },
  { word: "THINTY EIGHT", emoji: "3️⃣8️⃣", translation: "trinta-oito" },
  { word: "THINTY NINE", emoji: "3️⃣9️⃣", translation: "trinta-nove" },
  { word: "FORTY", emoji: "4️⃣0️⃣", translation: "quarenta" },
  { word: "FORTY ONE", emoji: "4️⃣1️⃣", translation: "quarenta-um" },
  { word: "FORTY TWO", emoji: "4️⃣2️⃣", translation: "quarenta-dois" },
  { word: "FORTY THREE", emoji: "4️⃣3️⃣", translation: "quarenta-três" },
  { word: "FORTY FOUR", emoji: "4️⃣4️⃣", translation: "quarenta-quatro" },
  { word: "FORTY FIVE", emoji: "4️⃣5️⃣", translation: "quarenta-cinco" },
  { word: "FORTY SIX", emoji: "4️⃣6️⃣", translation: "quarenta-seis" },
  { word: "FORTY SEVEN", emoji: "4️⃣7️⃣", translation: "quarenta-sete" },
  { word: "FORTY EIGHT", emoji: "4️⃣8️⃣", translation: "quarenta-oito" },
  { word: "FORTY NINE", emoji: "4️⃣9️⃣", translation: "quarenta-nove" },
  { word: "FIFTY", emoji: "5️⃣0️⃣", translation: "cinquenta" }
];
let currentIndex = 0;
let score = parseInt(localStorage.getItem('totalScore') || '0');

const scoreSpan = document.getElementById('scoreValue');
const starsContainer = document.getElementById('starsContainer');
const numberEmojiSpan = document.getElementById('numberEmoji');
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
  const utterance = new SpeechSynthesisUtterance(numbers[currentIndex].word);
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
  const currentWord = numbers[currentIndex].word;
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
  currentIndex = (currentIndex + 1) % numbers.length;
  const current = numbers[currentIndex];
  numberEmojiSpan.textContent = current.emoji;
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