<?php
class SpeakGame {
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
        if (!isset($_SESSION['speak_current_index'])) {
            $_SESSION['speak_current_index'] = 0;
        }
    }
    
    public function getCurrentIndex() {
        return $_SESSION['speak_current_index'];
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
        $_SESSION['speak_current_index'] = $idx;
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
            <title>Kant Speak - Speak</title>
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
                    animation: bounceIn 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
                }
                @keyframes bounceIn {
                    0% { transform: scale(0.8); opacity: 0; }
                    80% { transform: scale(1.05); }
                    100% { transform: scale(1); opacity: 1; }
                }
                .pulse-red {
                    animation: pulse 1s infinite;
                }
                @keyframes pulse {
                    0% { opacity: 1; text-shadow: 0 0 0 rgba(255,0,0,0); }
                    50% { opacity: 0.7; text-shadow: 0 0 8px rgba(255,0,0,0.5); }
                    100% { opacity: 1; text-shadow: 0 0 0 rgba(255,0,0,0); }
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
                
                <h2 class="text-3xl font-bold text-center text-gray-700">🎤 Fale em inglês!</h2>
                
                <!-- Cartão principal -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6 flex flex-col items-center gap-6">
                    <div class="text-8xl"><?php echo $current['image']; ?></div>
                    <p class="text-2xl text-gray-500">Diga o nome em inglês!</p>
                    
                    <!-- Área de texto reconhecido -->
                    <div id="recognizedContainer" class="hidden bg-gray-100 rounded-xl px-6 py-3 w-full text-center">
                        <p class="text-xl text-gray-700">
                            Você disse: <strong id="recognizedText" class="text-blue-600"></strong>
                        </p>
                    </div>
                    
                    <div class="flex gap-4 flex-wrap justify-center">
                        <button id="speakBtn" class="bg-blue-600 text-white px-8 py-4 rounded-xl text-xl font-bold hover:bg-blue-700 transition-all shadow-md flex items-center gap-2">
                            🎤 Falar
                        </button>
                        <button id="hintBtn" class="bg-amber-500 text-white px-8 py-4 rounded-xl text-xl font-bold hover:bg-amber-600 transition-all shadow-md">
                            💡 Dica
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
                // Dados do PHP
                const currentWord = <?php echo json_encode($current['word']); ?>;
                let currentScore = <?php echo $score; ?>;
                let isListening = false;
                
                // Elementos
                const scoreSpan = document.getElementById('scoreValue');
                const starsContainer = document.getElementById('starsContainer');
                const speakBtn = document.getElementById('speakBtn');
                const hintBtn = document.getElementById('hintBtn');
                const nextBtn = document.getElementById('nextBtn');
                const feedbackContainer = document.getElementById('feedbackContainer');
                const recognizedContainer = document.getElementById('recognizedContainer');
                const recognizedTextSpan = document.getElementById('recognizedText');
                
                // Suporte a reconhecimento de voz
                const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                let recognition = null;
                if (SpeechRecognition) {
                    recognition = new SpeechRecognition();
                    recognition.lang = 'en-US';
                    recognition.interimResults = false;
                    recognition.maxAlternatives = 1;
                }
                
                // Atualizar UI da pontuação
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
                
                function startListening() {
                    if (!recognition) {
                        showFeedback('incorrect', 'Seu navegador não suporta reconhecimento de voz. Use Chrome ou Edge.');
                        return;
                    }
                    if (isListening) return;
                    isListening = true;
                    speakBtn.innerHTML = '<span class="animate-pulse">🔴</span> Ouvindo...';
                    speakBtn.disabled = true;
                    
                    recognition.start();
                    
                    recognition.onresult = (event) => {
                        const transcript = event.results[0][0].transcript;
                        const recognized = transcript.trim();
                        recognizedTextSpan.textContent = recognized;
                        recognizedContainer.classList.remove('hidden');
                        recognizedContainer.classList.add('bounce-in');
                        
                        // Verificar se está correto
                        if (recognized.toLowerCase() === currentWord.toLowerCase()) {
                            addPoints(15);
                            showFeedback('correct', '🎉 Correto! +15 pontos');
                        } else {
                            showFeedback('incorrect', `❌ Incorreto. Você disse "${recognized}". A palavra correta é ${currentWord}.`);
                        }
                        isListening = false;
                        speakBtn.innerHTML = '🎤 Falar';
                        speakBtn.disabled = false;
                    };
                    
                    recognition.onerror = (event) => {
                        console.error('Erro no reconhecimento:', event.error);
                        showFeedback('incorrect', 'Erro ao capturar voz. Tente novamente.');
                        isListening = false;
                        speakBtn.innerHTML = '🎤 Falar';
                        speakBtn.disabled = false;
                    };
                    
                    recognition.onend = () => {
                        if (isListening) {
                            // Se terminou sem resultado (ex: usuário não falou)
                            isListening = false;
                            speakBtn.innerHTML = '🎤 Falar';
                            speakBtn.disabled = false;
                        }
                    };
                }
                
                // Eventos
                speakBtn.addEventListener('click', startListening);
                hintBtn.addEventListener('click', playHint);
                nextBtn.addEventListener('click', nextWord);
            </script>
        </body>
        </html>
        <?php
    }
}

$game = new SpeakGame();
$game->handleAjax();
$game->render();
?>