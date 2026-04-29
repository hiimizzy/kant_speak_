 // Banco de palavras e imagens (emoji)
  const vocabulary = [
    { word: "CAT", emoji: "🐱" },
    { word: "DOG", emoji: "🐕" },
    { word: "SUN", emoji: "☀️" },
    { word: "APPLE", emoji: "🍎" },
    { word: "CAR", emoji: "🚗" },
    { word: "BIRD", emoji: "🐦" },
    { word: "FISH", emoji: "🐠" },
    { word: "STAR", emoji: "⭐" },
    { word: "HOUSE", emoji: "🏠" },
    { word: "BOOK", emoji: "📚" },
    { word: "FLOWER", emoji: "🌺" },
    { word: "TREE", emoji: "🌳" },
    { word: "MOON", emoji: "🌙" },
    { word: "CLOUD", emoji: "☁️" }, 
    { word: "RAIN", emoji: "🌧️" },
    { word: "SNOW", emoji: "❄️" },
    { word: "MOUSE", emoji: "🐭" },
    { word: "HORSE", emoji: "🐴" },
    { word: "COW", emoji: "🐮" },
    { word: "FARM", emoji: "🚜" }
  ];

  let currentScore = 0;
  let currentWordObj = null;
  let currentOptions = [];
  let timer = 5;           // segundos
  let timerInterval = null;
  let isTimedMode = true;  // true = cronometrado, false = modo tranquilo
  let gameActive = true;

  // Elementos DOM
  const scoreSpan = document.getElementById('scoreValue');
  const currentWordSpan = document.getElementById('currentWord');
  const optionsDiv = document.getElementById('optionsContainer');
  const skipBtn = document.getElementById('skipBtn');
  const toggleModeBtn = document.getElementById('toggleTimerMode');
  const feedbackContainer = document.getElementById('feedbackContainer');
  const timerTextSpan = document.getElementById('timerText');
  const timerCircle = document.getElementById('timerCircle');

  // Circunferência do círculo (2 * pi * raio ≈ 2 * 3.1416 * 58 ≈ 364.4)
  const CIRCUMFERENCE = 2 * Math.PI * 58;

  function loadScore() {
    const saved = localStorage.getItem('timeTrialScore');
    currentScore = saved ? parseInt(saved) : 0;
    scoreSpan.textContent = currentScore;
  }

  function saveScore() {
    localStorage.setItem('timeTrialScore', currentScore);
    scoreSpan.textContent = currentScore;
  }

  function addPoints(points) {
    currentScore += points;
    saveScore();
    showFeedback(`+${points} points!`, true);
  }

  function showFeedback(msg, isGood) {
    feedbackContainer.innerHTML = '';
    const toast = document.createElement('div');
    toast.className = `feedback-toast px-6 py-3 rounded-full shadow-xl font-bold text-white text-center ${isGood ? 'bg-green-500' : 'bg-red-500'}`;
    toast.textContent = msg;
    feedbackContainer.appendChild(toast);
    setTimeout(() => toast.remove(), 2000);
  }

  // Atualizar timer visual (círculo e texto)
  function updateTimerUI() {
    timerTextSpan.textContent = Math.ceil(timer);
    const offset = CIRCUMFERENCE - (timer / 5) * CIRCUMFERENCE; // máximo 5 segundos
    timerCircle.style.strokeDashoffset = offset;
  }

  // Parar o timer
  function stopTimer() {
    if (timerInterval) {
      clearInterval(timerInterval);
      timerInterval = null;
    }
  }

  // Iniciar timer 
  function startTimer() {
    if (!isTimedMode) return;
    if (timerInterval) stopTimer();
    timer = 5;
    updateTimerUI();
    timerInterval = setInterval(() => {
      if (!gameActive) return;
      if (timer <= 0) {
        // Tempo esgotou
        stopTimer();
        showFeedback("⏰ Time's up! Try the next word.", false);
        nextWord();
      } else {
        timer = Math.max(0, timer - 0.1);
        updateTimerUI();
      }
    }, 100);
  }

  // Gerar novas opções 
  function generateOptions(correctWord) {
    const others = vocabulary.filter(item => item.word !== correctWord.word);
    const shuffled = [...others];
    for (let i = shuffled.length - 1; i > 0; i--) {
      const j = Math.floor(Math.random() * (i + 1));
      [shuffled[i], shuffled[j]] = [shuffled[j], shuffled[i]];
    }
    const opts = [correctWord, shuffled[0], shuffled[1]];
    // Embaralhar ordem
    for (let i = opts.length - 1; i > 0; i--) {
      const j = Math.floor(Math.random() * (i + 1));
      [opts[i], opts[j]] = [opts[j], opts[i]];
    }
    return opts;
  }

  // Carregar nova rodada
  function nextWord() {
    if (!gameActive) return;
    // Escolher palavra aleatória
    const randomIndex = Math.floor(Math.random() * vocabulary.length);
    currentWordObj = vocabulary[randomIndex];
    currentWordSpan.textContent = currentWordObj.word;
    currentOptions = generateOptions(currentWordObj);
    renderOptions();
    // Reiniciar timer
    if (isTimedMode) {
      startTimer();
    }
  }

  function renderOptions() {
    optionsDiv.innerHTML = '';
    currentOptions.forEach(opt => {
      const btn = document.createElement('div');
      btn.className = 'option-card bg-gradient-to-br from-gray-50 to-white rounded-2xl p-4 text-center shadow-md hover:shadow-lg transition';
      btn.innerHTML = `<div class="text-7xl">${opt.emoji}</div><div class="font-bold text-gray-600 mt-2">${opt.word}</div>`;
      btn.addEventListener('click', () => handleAnswer(opt.word));
      optionsDiv.appendChild(btn);
    });
  }

  function handleAnswer(selectedWord) {
    if (!gameActive) return;
    if (selectedWord === currentWordObj.word) {
      // Acerto
      addPoints(10);
      showFeedback("✅ Correct! +10 points", true);
      if (isTimedMode) {
        // Ganha +2 segundos
        timer = Math.min(timer + 2, 5);
        updateTimerUI();
      }
      nextWord();
    } else {
      // Erro
      if (isTimedMode) {
        timer -= 1;
        updateTimerUI();
        if (timer <= 0) {
          stopTimer();
          showFeedback("⏰ Time's up!", false);
          nextWord();
        } else {
          showFeedback(`❌ Wrong! The correct was ${currentWordObj.word}`, false);
        }
      } else {
        showFeedback(`❌ Wrong! Try again. The word is ${currentWordObj.word}`, false);
      }
    }
  }

  function skipWord() {
    if (!gameActive) return;
    showFeedback("Skipped!", false);
    nextWord();
  }

  function toggleTimerMode() {
    isTimedMode = !isTimedMode;
    if (isTimedMode) {
      toggleModeBtn.innerHTML = "⏸️ Modo tranquilo";
      toggleModeBtn.classList.remove("bg-gray-500", "hover:bg-gray-600");
      toggleModeBtn.classList.add("bg-indigo-500", "hover:bg-indigo-600");
      startTimer();
    } else {
      toggleModeBtn.innerHTML = "⚡ Modo cronometrado";
      toggleModeBtn.classList.remove("bg-indigo-500", "hover:bg-indigo-600");
      toggleModeBtn.classList.add("bg-gray-500", "hover:bg-gray-600");
      stopTimer();
      timerTextSpan.textContent = "∞";
      timerCircle.style.strokeDashoffset = "0";
    }
    showFeedback(isTimedMode ? "Timer mode activated!" : "Relax mode activated. No timer.", true);
  }

  function initGame() {
    loadScore();
    gameActive = true;
    nextWord();
    skipBtn.addEventListener('click', skipWord);
    toggleModeBtn.addEventListener('click', toggleTimerMode);
  }

  initGame();