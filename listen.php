<?php
class ListenGame {
    private $sessionKey = 'listen_game';
    private $words = [];
    private $scoreKey = 'total_score';
    
    public function __construct() {
        session_start();
        $this->initWords();
        $this->initSession();
    }
    
    private function initWords() {
        $this->words = [
            ['word' => 'APPLE', 'image' => '🍎', 'translation' => 'maçã'],
            ['word' => 'DOG',   'image' => '🐕', 'translation' => 'cachorro'],
            ['word' => 'SUN',   'image' => '☀️', 'translation' => 'sol'],
            ['word' => 'HOUSE', 'image' => '🏠', 'translation' => 'casa'],
            ['word' => 'CAT',   'image' => '🐱', 'translation' => 'gato'],
            ['word' => 'CAR',   'image' => '🚗', 'translation' => 'carro'],
            ['word' => 'BIRD',  'image' => '🐦', 'translation' => 'pássaro'],
            ['word' => 'FISH',  'image' => '🐠', 'translation' => 'peixe'],
            ['word' => 'BOOK',  'image' => '📚', 'translation' => 'livro'],
            ['word' => 'STAR',  'image' => '⭐', 'translation' => 'estrela']
        ];
    }
    
    private function initSession() {
        if (!isset($_SESSION[$this->sessionKey])) {
            $_SESSION[$this->sessionKey] = [
                'currentIndex' => 0,
                'score' => 0
            ];
        }
        if (!isset($_SESSION[$this->scoreKey])) {
            $_SESSION[$this->scoreKey] = 0;
        }
    }
    
    public function getCurrentIndex() {
        return $_SESSION[$this->sessionKey]['currentIndex'];
    }
    
    public function getCurrentWord() {
        $idx = $this->getCurrentIndex();
        return $this->words[$idx];
    }
    
    public function getScore() {
        return $_SESSION[$this->scoreKey];
    }
    
    public function addPoints($points) {
        $newScore = $this->getScore() + $points;
        $_SESSION[$this->scoreKey] = $newScore;
        $_SESSION[$this->sessionKey]['score'] = $newScore;
        return $newScore;
    }
    
    public function nextWord() {
        $idx = ($this->getCurrentIndex() + 1) % count($this->words);
        $_SESSION[$this->sessionKey]['currentIndex'] = $idx;
        return $idx;
    }
    
    public function getStars() {
        $score = $this->getScore();
        return min(5, floor($score / 30));
    }
    
    public function handleAjax() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            if (isset($_POST['action']) && $_POST['action'] === 'add_points') {
                $points = (int)$_POST['points'];
                $newScore = $this->addPoints($points);
                echo json_encode(['success' => true, 'newScore' => $newScore]);
                exit;
            }
            if (isset($_POST['action']) && $_POST['action'] === 'next_word') {
                $newIndex = $this->nextWord();
                echo json_encode(['success' => true, 'newIndex' => $newIndex]);
                exit;
            }
        }
    }
    
    public function render() {
        $current = $this->getCurrentWord();
        $score = $this->getScore();
        $stars = $this->getStars();
        $currentIndex = $this->getCurrentIndex();
        $totalWords = count($this->words);
        ?>
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
            <title>Kant Speak - Listen</title>
            <script src="https://cdn.tailwindcss.com"></script>
            <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
            <style>
                * { font-family: 'Nunito', sans-serif; }
                @keyframes fadeOut {
                    0% { opacity: 1; transform: translateY(0); }
                    100% { opacity: 0; transform: translateY(-20px); }
                }
                .feedback-toast {
                    animation: fadeOut 2s forwards;
                }
                .bounce-in {
                    animation: bounceIn 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
                }
                @keyframes bounceIn {
                    0% { transform: scale(0.3); opacity: 0; }
                    80% { transform: scale(1.1); }
                    100% { transform: scale(1); opacity: 1; }
                }
            </style>
        </head>
        <body class="bg-gradient-to-br from-sky-50 to-indigo-50 min-h-screen p-4 md:p-6">
            <div class="max-w-lg mx-auto flex flex-col gap-6">
                <!-- Cabeçalho -->
                <div class="flex items-center justify-between bg-white/80 backdrop-blur-sm p-3 rounded-2xl shadow-sm">
                    <a href="home.html" class="bg-gray-100 hover:bg-gray-200 transition-colors p-2 rounded-full w-10 h-10 flex items-center justify-center text-2xl">←</a>
                    <div class="flex items-center gap-3 bg-amber-50 px-4 py-2 rounded-full">
                        <span class="text-yellow-500 text-xl">⭐</span>
                        <span id="scoreValue" class="font-bold text-gray-800 text-xl"><?php echo $score; ?></span>
                    </div>
                </div>
                
                <h2 class="text-3xl font-bold text-center text-gray-700">👂 Ouça e aprenda!</h2>
                
                <!-- Cartão principal -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6 flex flex-col items-center gap-6">
                    <div class="text-8xl" id="wordEmoji"><?php echo $current['image']; ?></div>
                    
                    <div id="revealArea" class="text-center hidden">
                        <p class="text-4xl font-bold text-blue-600" id="revealWord"><?php echo $current['word']; ?></p>
                        <p class="text-xl text-gray-500" id="revealTranslation">(<?php echo $current['translation']; ?>)</p>
                    </div>
                    
                    <div class="flex gap-4 flex-wrap justify-center">
                        <button id="listenBtn" class="bg-blue-600 text-white px-8 py-4 rounded-xl text-xl font-bold hover:bg-blue-700 transition-all flex items-center gap-2 shadow-md">
                            🔊 Ouvir
                        </button>
                        <button id="revealBtn" class="bg-green-500 text-white px-8 py-4 rounded-xl text-xl font-bold hover:bg-green-600 transition-all shadow-md">
                            👀 Ver palavra
                        </button>
                    </div>
                </div>
                
                <!-- Feedback toast container -->
                <div id="feedbackContainer" class="fixed bottom-5 left-1/2 transform -translate-x-1/2 z-50 pointer-events-none"></div>
                
                <!-- Estrelas -->
                <div class="bg-white/70 rounded-2xl p-3 flex justify-center gap-2 shadow-sm" id="starsContainer">
                    <?php for($i=0;$i<5;$i++): ?>
                        <span class="text-3xl star <?php echo $i < $stars ? 'lit' : ''; ?>" style="<?php echo $i < $stars ? 'opacity:1; filter:drop-shadow(0 0 4px gold);' : 'opacity:0.3;'; ?>">⭐</span>
                    <?php endfor; ?>
                </div>
                
                <!-- Botão próxima palavra -->
                <button id="nextBtn" class="bg-amber-500 text-white font-bold py-3 rounded-xl text-xl shadow-md hover:shadow-xl transition-all mx-auto w-full max-w-xs">
                    Próxima palavra →
                </button>
            </div>
            
            <script>
                // Dados iniciais do PHP
                const initialScore = <?php echo $score; ?>;
                let currentScore = initialScore;
                let currentWord = <?php echo json_encode($current['word']); ?>;
                let currentEmoji = <?php echo json_encode($current['image']); ?>;
                let currentTranslation = <?php echo json_encode($current['translation']); ?>;
                let currentIndex = <?php echo $currentIndex; ?>;
                const totalWords = <?php echo $totalWords; ?>;
                let revealed = false;
                let isPlaying = false;
                
                // Elementos DOM
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
                
                // Atualizar interface de pontuação e estrelas
                function updateScoreUI() {
                    scoreSpan.textContent = currentScore;
                    const stars = Math.min(5, Math.floor(currentScore / 30));
                    const starSpans = starsContainer.querySelectorAll('.star');
                    starSpans.forEach((star, idx) => {
                        if (idx < stars) {
                            star.style.opacity = '1';
                            star.style.filter = 'drop-shadow(0 0 4px gold)';
                        } else {
                            star.style.opacity = '0.3';
                            star.style.filter = 'none';
                        }
                    });
                }
                
                // Mostrar feedback (toast)
                function showFeedback(type, message) {
                    const toast = document.createElement('div');
                    toast.className = `feedback-toast px-6 py-3 rounded-full shadow-xl font-bold text-white text-center ${type === 'correct' ? 'bg-green-500' : 'bg-red-500'}`;
                    toast.textContent = message;
                    feedbackContainer.innerHTML = '';
                    feedbackContainer.appendChild(toast);
                    setTimeout(() => toast.remove(), 2000);
                }
                
                // Adicionar pontos via AJAX
                async function addPoints(points) {
                    try {
                        const response = await fetch(window.location.href, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: `action=add_points&points=${points}`
                        });
                        const data = await response.json();
                        if (data.newScore !== undefined) {
                            currentScore = data.newScore;
                            updateScoreUI();
                        }
                    } catch(e) {
                        console.warn("Erro ao salvar pontos:", e);
                        // fallback local
                        currentScore += points;
                        updateScoreUI();
                    }
                }
                
                // Avançar para próxima palavra via AJAX (opcional, mas mantém sincronia)
                async function goToNextWord() {
                    try {
                        const response = await fetch(window.location.href, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: 'action=next_word'
                        });
                        const data = await response.json();
                        if (data.newIndex !== undefined) {
                            currentIndex = data.newIndex;
                            // Recarregar a página para obter nova palavra (ou atualizar dinamicamente)
                            window.location.reload();
                        } else {
                            window.location.reload();
                        }
                    } catch(e) {
                        window.location.reload();
                    }
                }
                
                // Reproduzir palavra em inglês
                function playWord() {
                    if (isPlaying) return;
                    if (!('speechSynthesis' in window)) {
                        showFeedback('incorrect', 'Seu navegador não suporta áudio.');
                        return;
                    }
                    isPlaying = true;
                    listenBtn.innerHTML = '🔊 Tocando...';
                    listenBtn.disabled = true;
                    
                    const utterance = new SpeechSynthesisUtterance(currentWord);
                    utterance.lang = 'en-US';
                    utterance.rate = 0.8;
                    utterance.pitch = 1.0;
                    utterance.onend = () => {
                        isPlaying = false;
                        listenBtn.innerHTML = '🔊 Ouvir';
                        listenBtn.disabled = false;
                    };
                    utterance.onerror = () => {
                        isPlaying = false;
                        listenBtn.innerHTML = '🔊 Ouvir';
                        listenBtn.disabled = false;
                        showFeedback('incorrect', 'Erro ao reproduzir áudio.');
                    };
                    window.speechSynthesis.cancel();
                    window.speechSynthesis.speak(utterance);
                }
                
                // Revelar palavra e ganhar pontos
                function revealWord() {
                    if (revealed) return;
                    revealed = true;
                    revealArea.classList.remove('hidden');
                    revealArea.classList.add('bounce-in');
                    addPoints(5);
                    showFeedback('correct', '🎉 +5 pontos! Palavra revelada.');
                    revealBtn.disabled = true;
                    revealBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
                
                // Eventos
                listenBtn.addEventListener('click', playWord);
                revealBtn.addEventListener('click', revealWord);
                nextBtn.addEventListener('click', goToNextWord);
                
                // Inicializar estado (caso já tenha sido revelado anteriormente? Não há persistência de revelação, então começa oculto)
                revealArea.classList.add('hidden');
                revealed = false;
                revealBtn.disabled = false;
                revealBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            </script>
        </body>
        </html>
        <?php
    }
}

// Execução
$game = new ListenGame();
$game->handleAjax();
$game->render();
?>