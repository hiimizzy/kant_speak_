  // ========== Dados do jogo ==========
  const categories = {
    animals: { name: 'animals', label: 'Animals' },
    food: { name: 'food', label: 'Food' },
    clothes: { name: 'clothes', label: 'Clothes' },
    transportation: { name: 'transportation', label: 'Transportation' }
  };

  const items = [
    { id: 1, name: 'Dog', emoji: '🐕', category: 'animals' },
    { id: 2, name: 'Cat', emoji: '🐱', category: 'animals' },
    { id: 3, name: 'Apple', emoji: '🍎', category: 'food' },
    { id: 4, name: 'Pizza', emoji: '🍕', category: 'food' },
    { id: 5, name: 'Shirt', emoji: '👕', category: 'clothes' },
    { id: 6, name: 'Shoes', emoji: '👟', category: 'clothes' },
    { id: 7, name: 'Car', emoji: '🚗', category: 'transportation' },
    { id: 8, name: 'Airplane', emoji: '✈️', category: 'transportation' }
  ];

  let currentScore = 0;
  let sortedItems = []; // ids das cartas já classificadas

  // Elementos DOM
  const scoreSpan = document.getElementById('scoreValue');
  const cardsContainer = document.getElementById('cardsContainer');
  const resetBtn = document.getElementById('resetBtn');
  const feedbackContainer = document.getElementById('feedbackContainer');
  
  // Referências das zonas de drop
  const dropZones = {
    animals: document.getElementById('category-animals'),
    food: document.getElementById('category-food'),
    clothes: document.getElementById('category-clothes'),
    transportation: document.getElementById('category-transportation')
  };

  // ========== Funções auxiliares ==========
  function showFeedback(message, isCorrect) {
    feedbackContainer.innerHTML = '';
    const toast = document.createElement('div');
    toast.className = `feedback-toast px-6 py-3 rounded-full shadow-xl font-bold text-white text-center ${isCorrect ? 'bg-green-500' : 'bg-red-500'}`;
    toast.textContent = message;
    feedbackContainer.appendChild(toast);
    setTimeout(() => toast.remove(), 2500);
  }

  function speakItemCategory(itemName, categoryLabel) {
    if (!('speechSynthesis' in window)) return;
    const utterance = new SpeechSynthesisUtterance(`${itemName} is ${categoryLabel}`);
    utterance.lang = 'en-US';
    utterance.rate = 0.9;
    window.speechSynthesis.cancel();
    window.speechSynthesis.speak(utterance);
  }

  function updateScoreUI() {
    scoreSpan.textContent = currentScore;
    localStorage.setItem('sortingScore', currentScore);
  }

  function addPoints(points) {
    currentScore += points;
    updateScoreUI();
    // Opcional: enviar para api.php (descomente se quiser integrar)
    // fetch('api.php', { method: 'POST', body: new URLSearchParams({ action: 'add_points', points }) });
  }

  function checkGameComplete() {
    if (sortedItems.length === items.length) {
      showFeedback('🎉 Congratulations! You sorted all items! 🎉', true);
      addPoints(20); // bônus por completar
    }
  }

  // ========== Lógica de drag and drop ==========
  let draggedCard = null;
  let draggedItemId = null;

  function handleDragStart(e) {
    draggedCard = e.target.closest('.drag-card');
    if (!draggedCard) return;
    draggedItemId = parseInt(draggedCard.getAttribute('data-id'));
    // Verifica se a carta já foi classificada (não deveria ser arrastável)
    if (sortedItems.includes(draggedItemId)) {
      e.preventDefault();
      return false;
    }
    e.dataTransfer.setData('text/plain', draggedItemId);
    e.dataTransfer.effectAllowed = 'move';
    draggedCard.classList.add('dragging');
  }

  function handleDragEnd(e) {
    if (draggedCard) draggedCard.classList.remove('dragging');
    draggedCard = null;
    draggedItemId = null;
    // Remove classe drag-over de todas as zonas
    Object.values(dropZones).forEach(zone => zone.classList.remove('drag-over'));
  }

  function handleDragOver(e) {
    e.preventDefault();
    e.dataTransfer.dropEffect = 'move';
  }

  function handleDragEnter(e) {
    e.preventDefault();
    const zone = e.target.closest('.category-zone');
    if (zone) zone.classList.add('drag-over');
  }

  function handleDragLeave(e) {
    const zone = e.target.closest('.category-zone');
    if (zone) zone.classList.remove('drag-over');
  }

  async function handleDrop(e) {
    e.preventDefault();
    const zone = e.target.closest('.category-zone');
    if (!zone) return;
    zone.classList.remove('drag-over');
    const category = zone.getAttribute('data-category');
    const itemId = draggedItemId;
    if (!itemId) return;

    const item = items.find(i => i.id === itemId);
    if (!item) return;

    // Verifica se a carta já foi classificada
    if (sortedItems.includes(itemId)) {
      showFeedback('This card is already sorted!', false);
      return;
    }

    // Verifica se a categoria está correta
    if (item.category === category) {
      // Classificação correta
      sortedItems.push(itemId);
      // Remove a carta da área de cartas arrastáveis
      const cardElement = document.querySelector(`.drag-card[data-id='${itemId}']`);
      if (cardElement) {
        cardElement.remove();
        // Adiciona a carta dentro da zona de categoria (como item já classificado)
        const placedCard = document.createElement('div');
        placedCard.className = 'drag-card card-placed bg-white/80 p-2 rounded-lg text-center cursor-default';
        placedCard.innerHTML = `<div class="text-3xl">${item.emoji}</div><div class="font-bold">${item.name}</div>`;
        placedCard.setAttribute('data-id', itemId);
        zone.appendChild(placedCard);
        // Remove atributo draggable
        placedCard.setAttribute('draggable', 'false');
      }
      // Adiciona pontos
      addPoints(5);
      // Feedback visual e sonoro
      const categoryLabel = categories[category].label;
      speakItemCategory(item.name, categoryLabel);
      showFeedback(`✅ Correct! ${item.name} is ${categoryLabel}`, true);
      // Verifica se completou
      checkGameComplete();
    } else {
      // Categoria errada
      const expectedCat = categories[item.category].label;
      showFeedback(`❌ Wrong! ${item.name} is not ${categories[category].label}. Try again!`, false);
      speakItemCategory(item.name, expectedCat);
    }
  }

  // Configurar eventos das zonas de drop
  function setupDropZones() {
    Object.values(dropZones).forEach(zone => {
      zone.addEventListener('dragover', handleDragOver);
      zone.addEventListener('dragenter', handleDragEnter);
      zone.addEventListener('dragleave', handleDragLeave);
      zone.addEventListener('drop', handleDrop);
    });
  }

  // ========== Renderizar as cartas iniciais ==========
  function renderCards() {
    cardsContainer.innerHTML = '';
    const unsortedItems = items.filter(i => !sortedItems.includes(i.id));
    unsortedItems.forEach(item => {
      const card = document.createElement('div');
      card.className = 'drag-card w-24 h-28 flex flex-col items-center justify-center';
      card.setAttribute('draggable', 'true');
      card.setAttribute('data-id', item.id);
      card.innerHTML = `<div class="text-4xl">${item.emoji}</div><div class="font-bold mt-1">${item.name}</div>`;
      card.addEventListener('dragstart', handleDragStart);
      card.addEventListener('dragend', handleDragEnd);
      cardsContainer.appendChild(card);
    });
  }

  // ========== Limpar zonas de categoria (remover cartas inseridas) ==========
  function clearCategoryZones() {
    Object.values(dropZones).forEach(zone => {
      zone.innerHTML = ''; // remove todas as cartas colocadas
    });
  }

  // ========== Reiniciar o jogo ==========
  function resetGame() {
    // Limpa dados
    sortedItems = [];
    currentScore = 0;
    updateScoreUI();
    // Limpa zonas
    clearCategoryZones();
    // Recria cartas
    renderCards();
    showFeedback('Game restarted!', true);
  }

  // ========== Inicialização ==========
  function init() {
    // Recupera pontuação do localStorage (opcional)
    const savedScore = localStorage.getItem('sortingScore');
    if (savedScore) currentScore = parseInt(savedScore) || 0;
    updateScoreUI();
    setupDropZones();
    renderCards();
    resetBtn.addEventListener('click', resetGame);
  }

  init();