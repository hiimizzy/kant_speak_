// Dados dos pares (emoji + palavra)
const pairsData = [
  { id: 1, emoji: "🍎", name: "APPLE" },
  { id: 2, emoji: "🐕", name: "DOG" },
  { id: 3, emoji: "🐱", name: "CAT" },
  { id: 4, emoji: "☀️", name: "SUN" },
  { id: 5, emoji: "🚗", name: "CAR" },
  { id: 6, emoji: "⭐", name: "STAR" },
  { id: 7, emoji: "📚", name: "BOOK" },
  { id: 8, emoji: "🐦", name: "BIRD" }
];

// Configuração
let totalPairs = pairsData.length;
let cards = [];
let flippedCards = [];
let matchedPairs = 0;
let lockBoard = false;
let currentScore = 0;

// Elementos DOM
const gameBoard = document.getElementById('gameBoard');
const pairsMatchedSpan = document.getElementById('pairsMatched');
const totalPairsSpan = document.getElementById('totalPairs');
const memoryScoreSpan = document.getElementById('memoryScore');
const resetBtn = document.getElementById('resetBtn');
const feedbackContainer = document.getElementById('feedbackContainer');

// Mostrar feedback
function showFeedback(message, isWin = false) {
  const toast = document.createElement('div');
  toast.className = `feedback-toast px-6 py-3 rounded-full shadow-xl font-bold text-white text-center ${isWin ? 'bg-green-500' : 'bg-amber-500'}`;
  toast.textContent = message;
  feedbackContainer.innerHTML = '';
  feedbackContainer.appendChild(toast);
  setTimeout(() => toast.remove(), 2000);
}

// Atualizar pontuação na tela (pode ser integrada com API depois)
function updateScoreUI() {
  memoryScoreSpan.innerText = currentScore;
}

// Adicionar pontos (chamado ao acertar um par)
function addPoints(points) {
  currentScore += points;
  updateScoreUI();
  showFeedback(`🎉 +${points} pontos!`, false);
}

// Reiniciar o jogo
function resetGame() {
  // Embaralhar cartas
  initGame();
  // Resetar estado
  matchedPairs = 0;
  pairsMatchedSpan.innerText = matchedPairs;
  flippedCards = [];
  lockBoard = false;
  showFeedback("Novo jogo! Boa sorte!", false);
}

// Embaralhar array (Fisher-Yates)
function shuffleArray(arr) {
  for (let i = arr.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [arr[i], arr[j]] = [arr[j], arr[i]];
  }
  return arr;
}

// Inicializar ou reiniciar o jogo
function initGame() {
  // Criar array de cartas (cada par aparece duas vezes)
  let newCards = [];
  pairsData.forEach((pair, idx) => {
    // Carta com conteúdo (emoji + palavra)
    newCards.push({
      id: idx,
      pairId: pair.id,
      content: `${pair.emoji} ${pair.name}`,
      flipped: false,
      matched: false
    });
    newCards.push({
      id: idx + pairsData.length,
      pairId: pair.id,
      content: `${pair.emoji} ${pair.name}`,
      flipped: false,
      matched: false
    });
  });
  // Embaralhar
  cards = shuffleArray(newCards);
  renderBoard();
}

// Renderizar as cartas no grid
function renderBoard() {
  gameBoard.innerHTML = '';
  cards.forEach((card, index) => {
    const cardDiv = document.createElement('div');
    cardDiv.className = 'card';
    if (card.matched) {
      cardDiv.classList.add('matched');
      cardDiv.innerHTML = '✓';
    } else if (card.flipped) {
      cardDiv.classList.add('flipped');
      cardDiv.innerHTML = card.content;
    } else {
      cardDiv.innerHTML = '?';
    }
    cardDiv.addEventListener('click', () => onCardClick(index));
    gameBoard.appendChild(cardDiv);
  });
}

// Lógica ao clicar em uma carta
function onCardClick(index) {
  if (lockBoard) return;
  const card = cards[index];
  if (card.matched) return;
  if (card.flipped) return; // já virada

  // Vira a carta
  card.flipped = true;
  renderBoard();

  // Adiciona ao array de cartas viradas
  flippedCards.push({ index, pairId: card.pairId });

  if (flippedCards.length === 2) {
    // Verifica se é um par
    checkMatch();
  }
}

function checkMatch() {
  const cardA = cards[flippedCards[0].index];
  const cardB = cards[flippedCards[1].index];
  const isMatch = (flippedCards[0].pairId === flippedCards[1].pairId);

  if (isMatch) {
    // Marcar como matched
    cardA.matched = true;
    cardB.matched = true;
    cardA.flipped = false;
    cardB.flipped = false;
    matchedPairs++;
    pairsMatchedSpan.innerText = matchedPairs;
    addPoints(10); // ganha 10 pontos por par
    // Limpar flippedCards
    flippedCards = [];
    renderBoard();
    // Verificar se venceu
    if (matchedPairs === totalPairs) {
      showFeedback("🎉 Parabéns! Você completou o jogo! 🎉", true);
      // Bônus extra? Podemos dar pontos extras
      addPoints(30);
    }
  } else {
    // Errou: trava o board por 1 segundo e desvira
    lockBoard = true;
    setTimeout(() => {
      cardA.flipped = false;
      cardB.flipped = false;
      flippedCards = [];
      renderBoard();
      lockBoard = false;
    }, 800);
    renderBoard(); // mostra as cartas viradas durante o delay
  }
}

// Inicializar o jogo e eventos
function init() {
  totalPairsSpan.innerText = totalPairs;
  initGame();
  resetBtn.addEventListener('click', () => {
    resetGame();
  });
  // Carregar pontuação salva (opcional)
  const savedScore = localStorage.getItem('memoryScore');
  if (savedScore) currentScore = parseInt(savedScore);
  updateScoreUI();
}

// Salvar pontuação ao sair? (opcional)
window.addEventListener('beforeunload', () => {
  localStorage.setItem('memoryScore', currentScore);
});

init();