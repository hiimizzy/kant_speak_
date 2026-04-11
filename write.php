<?php
class WriteGame {
    private $scoreKey = 'total_score';
    private $words = [];
    
    public function __construct() {
        session_start();
        $this->initWords();
        $this->initSession();
    }
    
    private function initWords() {
        $this->words = [
            ['word' => 'APPLE',   'image' => '🍎', 'translation' => 'maçã'],
            ['word' => 'DOG',     'image' => '🐕', 'translation' => 'cachorro'],
            ['word' => 'SUN',     'image' => '☀️', 'translation' => 'sol'],
            ['word' => 'HOUSE',   'image' => '🏠', 'translation' => 'casa'],
            ['word' => 'CAT',     'image' => '🐱', 'translation' => 'gato'],
            ['word' => 'CAR',     'image' => '🚗', 'translation' => 'carro'],
            ['word' => 'BIRD',    'image' => '🐦', 'translation' => 'pássaro'],
            ['word' => 'FISH',    'image' => '🐠', 'translation' => 'peixe'],
            ['word' => 'BOOK',    'image' => '📚', 'translation' => 'livro'],
            ['word' => 'STAR',    'image' => '⭐', 'translation' => 'estrela']
        ];
    }
    
    private function initSession() {
        if (!isset($_SESSION[$this->scoreKey])) {
            $_SESSION[$this->scoreKey] = 0;
        }
        if (!isset($_SESSION['write_current_index'])) {
            $_SESSION['write_current_index'] = 0;
        }
    }
    
    public function getCurrentIndex() {
        return $_SESSION['write_current_index'];
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
        return $newScore;
    }
    
    public function nextWord() {
        $idx = ($this->getCurrentIndex() + 1) % count($this->words);
        $_SESSION['write_current_index'] = $idx;
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
        ?>
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
            <title>Kant Speak - Write</title>
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
                input:focus {
                    outline: none;
                    ring: 2px solid #4A90E2;
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
                
                <h2 class="text-3xl font-bold text-center text-gray-700">📝 Escreva em inglês!</h2>
                
                <!-- Cartão principal -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6 flex flex-col items-center gap-6">
                    <div class="text-8xl"><?php echo $current['image']; ?></div>
                    <p class="text-2xl text-gray-500">(<?php echo $current['translation']; ?>)</p>
                    
                    <input type="text" id="wordInput" placeholder="Digite a palavra..." 
                           class="w-full text-2xl text-center p-4 rounded-xl border-2 border-gray-200 bg-gray-50
                                  font-bold focus:border-blue-500 transition-all shadow-sm">
                    
                    <div class="flex gap-4 flex-wrap justify-center">
                        <button id="checkBtn" class="bg-blue-600 text-white px-8 py-4 rounded-xl text-xl font-bold hover:bg-blue-700 transition-all shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                            ✅ Verificar
                        </button>
                        <button id="hintBtn" class="bg-amber-500 text-white px-8 py-4 rounded-xl text-xl font-bold hover:bg-amber-600 transition-all shadow-md">
                            🔊 Ouvir
                        </button>
                    </div>
                </div>
                
                <!-- Feedback toast -->
                <div id="feedbackContainer" class="fixed bottom-5 left-1/2 transform -translate-x-1/2 z-50 pointer-events-none"></div>
                
                <!-- Estrelas -->
                <div class="bg-white/70 rounded-2xl p-3 flex justify-center gap-2 shadow-sm" id="starsContainer">
                    <?php for($i=0;$i<5;$i++): ?>
                        <span class="text-3xl star <?php echo $i < $stars ? 'lit' : ''; ?>" style="<?php echo $i < $stars ? 'opacity:1; filter:drop-shadow(0 0 4px gold);' : 'opacity:0.3;'; ?>">⭐</span>
                    <?php endfor; ?>
                </div>
                
                <button id="nextBtn" class="bg-amber-500 text-white font-bold py-3 rounded-xl text-xl shadow-md hover:shadow-xl transition-all mx-auto w-full max-w-xs">
                    Próxima palavra →
                </button>
            </div>
            
            <script>
                // Dados vindos do PHP
                const currentWord = <?php echo json_encode($current['word']); ?>;
                let currentScore = <?php echo $score; ?>;
                
                // Elementos
                const scoreSpan = document.getElementById('scoreValue');
                const starsContainer = document.getElementById('starsContainer');
                const wordInput = document.getElementById('wordInput');
                const checkBtn = document.getElementById('checkBtn');
                const hintBtn = document.getElementById('hintBtn');
                const nextBtn = document.getElementById('nextBtn');
                const feedbackContainer = document.getElementById('feedbackContainer');
                
                // Atualizar UI da pontuação e estrelas
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
                
                function showFeedback(type, message) {
                    const toast = document.createElement('div');
                    toast.className = `feedback-toast px-6 py-3 rounded-full shadow-xl font-bold text-white text-center ${type === 'correct' ? 'bg-green-500' : 'bg-red-500'}`;
                    toast.textContent = message;
                    feedbackContainer.innerHTML = '';
                    feedbackContainer.appendChild(toast);
                    setTimeout(() => toast.remove(), 2000);
                }
                
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
                        console.warn(e);
                        currentScore += points;
                        updateScoreUI();
                    }
                }
                
                async function nextWord() {
                    try {
                        const response = await fetch(window.location.href, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: 'action=next_word'
                        });
                        const data = await response.json();
                        if (data.newIndex !== undefined) {
                            window.location.reload();
                        } else {
                            window.location.reload();
                        }
                    } catch(e) {
                        window.location.reload();
                    }
                }
                
                function playHint() {
                    if (!('speechSynthesis' in window)) {
                        showFeedback('incorrect', 'Seu navegador não suporta áudio.');
                        return;
                    }
                    const utterance = new SpeechSynthesisUtterance(currentWord);
                    utterance.lang = 'en-US';
                    utterance.rate = 0.8;
                    utterance.pitch = 1.0;
                    window.speechSynthesis.cancel();
                    window.speechSynthesis.speak(utterance);
                }
                
                function handleCheck() {
                    const userInput = wordInput.value.trim();
                    if (userInput === "") {
                        showFeedback('incorrect', 'Digite uma palavra!');
                        return;
                    }
                    if (userInput.toLowerCase() === currentWord.toLowerCase()) {
                        addPoints(10);
                        showFeedback('correct', '🎉 Correto! +10 pontos');
                        checkBtn.disabled = true;
                        wordInput.disabled = true;
                    } else {
                        showFeedback('incorrect', `❌ Incorreto. A palavra correta é ${currentWord}.`);
                    }
                }
                
                // Eventos
                checkBtn.addEventListener('click', handleCheck);
                hintBtn.addEventListener('click', playHint);
                nextBtn.addEventListener('click', nextWord);
                wordInput.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') handleCheck();
                });
                
                // Habilitar/desabilitar botão de verificar conforme entrada
                wordInput.addEventListener('input', () => {
                    checkBtn.disabled = (wordInput.value.trim() === "");
                });
                checkBtn.disabled = true; // inicialmente desabilitado
            </script>
        </body>
        </html>
        <?php
    }
}

$game = new WriteGame();
$game->handleAjax();
$game->render();
?>