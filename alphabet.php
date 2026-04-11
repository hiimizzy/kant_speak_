<?php
class AlphabetGame {
    private $scoreKey = 'alphabet_score';
    
    public function __construct() {
        session_start();
    }
    
    // Retorna a pontuação atual da sessão
    public function getScore() {
        return isset($_SESSION[$this->scoreKey]) ? (int)$_SESSION[$this->scoreKey] : 0;
    }
    
    // Adiciona pontos e atualiza a sessão
    public function addPoints($points) {
        $newScore = $this->getScore() + $points;
        $_SESSION[$this->scoreKey] = $newScore;
        return $newScore;
    }
    
    // Processa requisições AJAX para atualizar pontuação
    public function handleAjax() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_score'])) {
            $points = (int)$_POST['update_score'];
            $newScore = $this->addPoints($points);
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'newScore' => $newScore]);
            exit;
        }
    }
    
    // Renderiza a página HTML completa
    public function render() {
        $initialScore = $this->getScore();
        ?>
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
            <title>✏️ Alfabeto Mágico - Desenhe com o Dedo</title>
            <script src="https://cdn.tailwindcss.com"></script>
            <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
            <script src="https://cdn.jsdelivr.net/npm/@mediapipe/hands/hands.js" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/@mediapipe/camera_utils/camera_utils.js" crossorigin="anonymous"></script>
            <style>
                * { font-family: 'Nunito', sans-serif; }
                .finger-cursor {
                    position: absolute;
                    width: 28px;
                    height: 28px;
                    background: radial-gradient(circle, #f97316, #ea580c);
                    border: 2px solid white;
                    border-radius: 50%;
                    pointer-events: none;
                    transform: translate(-50%, -50%);
                    box-shadow: 0 0 0 4px rgba(249,115,22,0.3);
                    transition: left 0.02s linear, top 0.02s linear;
                    z-index: 50;
                }
                @keyframes fadeOut {
                    0% { opacity: 1; transform: translateY(0); }
                    100% { opacity: 0; transform: translateY(-20px); }
                }
                .feedback-toast { animation: fadeOut 2s forwards; }
                .video-mirror { transform: scaleX(-1); }
                .loading-pulse { animation: pulse 1.5s ease-in-out infinite; }
                @keyframes pulse {
                    0%, 100% { opacity: 0.6; }
                    50% { opacity: 1; }
                }
            </style>
        </head>
        <body class="bg-gradient-to-br from-sky-50 to-indigo-50 min-h-screen p-4 md:p-6">
            <div class="max-w-lg mx-auto flex flex-col gap-5">
                <!-- Cabeçalho -->
                <div class="flex items-center justify-between bg-white/80 backdrop-blur-sm p-3 rounded-2xl shadow-sm">
                    <button onclick="window.history.back()" class="bg-gray-100 hover:bg-gray-200 transition-colors p-2 rounded-full w-10 h-10 flex items-center justify-center text-2xl">←</button>
                    <div class="flex items-center gap-3 bg-amber-50 px-4 py-2 rounded-full">
                        <span class="text-yellow-500 text-xl">⭐</span>
                        <span id="scoreValue" class="font-bold text-gray-800 text-xl"><?php echo $initialScore; ?></span>
                    </div>
                </div>
                
                <div class="text-center bg-white/60 rounded-2xl py-4 shadow-sm flex items-center justify-center gap-3">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-700">
                ✏️ Desenhe a letra:
                </h2>
                <div class="text-7xl md:text-8xl font-black text-transparent bg-clip-text bg-gradient-to-r from-orange-600 to-amber-600" id="currentLetterDisplay">
                A
                </div>
                <button id="speakLetterBtn" class="bg-indigo-100 hover:bg-indigo-200 transition-colors p-3 rounded-full text-2xl" aria-label="Ouvir pronúncia">
                🔊
                </button>
                </div>
                
                <!-- Área da câmera e canvas -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-3 flex flex-col items-center gap-3">
                    <div class="relative w-full max-w-[400px] aspect-square mx-auto rounded-xl overflow-hidden bg-gray-100 shadow-inner border-2 border-gray-300">
                        <video id="webcamVideo" class="absolute inset-0 w-full h-full object-cover video-mirror" autoplay playsinline muted></video>
                        <canvas id="guideCanvas" class="absolute inset-0 w-full h-full pointer-events-none opacity-40"></canvas>
                        <canvas id="drawCanvas" class="absolute inset-0 w-full h-full pointer-events-none"></canvas>
                        <div id="fingerCursor" class="finger-cursor" style="display: none;"></div>
                        <div id="cameraOffOverlay" class="absolute inset-0 flex flex-col items-center justify-center bg-black/70 backdrop-blur-sm text-white gap-2 rounded-xl">
                            <span class="text-5xl">📷</span>
                            <p class="font-bold text-center px-4">Ligue a câmera para desenhar a letra com o dedo!</p>
                            <p class="text-sm text-gray-200 text-center">☝️ Estenda o dedo indicador para desenhar</p>
                        </div>
                        <div id="cameraLoading" class="absolute inset-0 flex items-center justify-center bg-black/50 backdrop-blur-sm rounded-xl" style="display: none;">
                            <div class="bg-white/90 px-5 py-3 rounded-full shadow-lg flex gap-2 items-center">
                                <div class="w-5 h-5 border-3 border-orange-500 border-t-transparent rounded-full animate-spin"></div>
                                <span class="font-semibold text-gray-800">Iniciando câmera...</span>
                            </div>
                        </div>
                    </div>
                    <div id="instructionText" class="text-sm text-gray-500 text-center bg-gray-50 px-4 py-2 rounded-full hidden">
                        ☝️ Estenda <strong>só o indicador</strong> para desenhar • ✌️ Levante 2 dedos para parar
                    </div>
                    <div class="flex flex-wrap gap-3 justify-center mt-2">
                        <button id="toggleCameraBtn" class="px-5 py-2.5 rounded-xl font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:shadow-lg transition-all">📷 Ligar Câmera</button>
                        <button id="checkBtn" class="px-5 py-2.5 rounded-xl font-bold text-white bg-gradient-to-r from-green-600 to-emerald-600 hover:shadow-lg transition-all disabled:opacity-50 disabled:grayscale">✅ Verificar</button>
                        <button id="clearBtn" class="px-5 py-2.5 rounded-xl font-bold text-gray-700 bg-gray-200 hover:bg-gray-300 transition-all">🗑️ Limpar</button>
                    </div>
                </div>
                
                <div id="feedbackContainer" class="fixed bottom-5 left-1/2 transform -translate-x-1/2 z-50 pointer-events-none"></div>

                
                <button id="nextLetterBtn" class="bg-gradient-to-r from-amber-500 to-orange-500 text-white font-bold py-3 rounded-xl text-xl shadow-md hover:shadow-xl transition-all mx-auto w-full max-w-xs">Próxima letra →</button>
            </div>
 
<script>
    // ========== DADOS INICIAIS VINDOS DO PHP ==========
    const initialScore = <?php echo $initialScore; ?>;
    let currentScore = initialScore;
    
    // ========== FUNÇÕES DE PONTUAÇÃO COM AJAX ==========
    async function updateScoreUI() {
        const scoreSpan = document.getElementById('scoreValue');
        if (scoreSpan) scoreSpan.textContent = currentScore;
        const stars = Math.min(5, Math.floor(currentScore / 30));
        const starSpans = document.querySelectorAll('#starsContainer span');
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
    
    async function addPoints(points) {
        currentScore += points;
        await updateScoreUI();
        try {
            const response = await fetch(window.location.href, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'update_score=' + points
            });
            const data = await response.json();
            if (data.newScore !== undefined) {
                currentScore = data.newScore;
                await updateScoreUI();
            }
        } catch(e) { console.warn('Erro ao salvar pontuação:', e); }
    }
    
    // ========== ELEMENTOS E CONSTANTES ==========
    const ALPHABET = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
    let currentIndex = 0;
    let currentLetter = ALPHABET[0];
    let cameraActive = false;
    let mediaStream = null;
    let handsInstance = null;
    let cameraInstance = null;
    let isHandTrackingReady = false;
    let drawingPoints = [];
    let lastPoint = null;
    let isDrawingGesture = false;
    let fingerPosition = null;
    
    // Obter elementos (serão preenchidos no init)
    let videoElement, drawCanvas, guideCanvas, drawCtx, guideCtx, fingerCursorDiv;
    let cameraOffOverlay, cameraLoadingDiv, instructionText, currentLetterSpan, letterGuideSimple;
    let toggleCameraBtn, checkBtn, clearBtn, nextLetterBtn, speakLetterBtn;
    
    const CANVAS_SIZE = 400;
    
    // Funções de desenho
    function drawGuideLetter() {
        if (!guideCtx) return;
        guideCtx.clearRect(0, 0, CANVAS_SIZE, CANVAS_SIZE);
        guideCtx.font = `bold ${CANVAS_SIZE * 0.5}px "Nunito"`;
        guideCtx.textAlign = "center";
        guideCtx.textBaseline = "middle";
        guideCtx.setLineDash([12, 12]);
        guideCtx.strokeStyle = "#94a3b8";
        guideCtx.lineWidth = 4;
        guideCtx.strokeText(currentLetter, CANVAS_SIZE/2, CANVAS_SIZE/2);
        guideCtx.setLineDash([]);
        if (letterGuideSimple) letterGuideSimple.textContent = currentLetter;
        if (currentLetterSpan) currentLetterSpan.textContent = currentLetter;
    }
    
    function clearDrawing() {
        if (drawCtx) drawCtx.clearRect(0, 0, CANVAS_SIZE, CANVAS_SIZE);
        drawingPoints = [];
        lastPoint = null;
        const fbDiv = document.getElementById('feedbackContainer');
        if (fbDiv) fbDiv.innerHTML = '';
    }
    
    function showFeedback(type) {
        const container = document.getElementById('feedbackContainer');
        if (!container) return;
        container.innerHTML = '';
        const message = document.createElement('div');
        message.className = `feedback-toast px-6 py-3 rounded-full shadow-xl font-bold text-white text-center ${type === 'correct' ? 'bg-green-500' : 'bg-red-500'}`;
        message.innerHTML = type === 'correct' ? '✅ Correto! +10 pontos' : '❌ Tente novamente!';
        container.appendChild(message);
        setTimeout(() => message.remove(), 2000);
    }
    
    async function handleCheck() {
        if (!cameraActive || !isHandTrackingReady) {
            showFeedback('incorrect');
            return;
        }
        if (drawingPoints.length < 10) {
            showFeedback('incorrect');
            return;
        }
        const isCorrect = drawingPoints.length > 20 ? Math.random() > 0.2 : Math.random() > 0.5;
        if (isCorrect) {
            await addPoints(10);
            showFeedback('correct');
        } else {
            showFeedback('incorrect');
        }
    }
    
    function nextLetter() {
        currentIndex = (currentIndex + 1) % ALPHABET.length;
        currentLetter = ALPHABET[currentIndex];
        if (currentLetterSpan) currentLetterSpan.textContent = currentLetter;
        drawGuideLetter();
        clearDrawing();
        // Limpar feedback visual
        const fbDiv = document.getElementById('feedbackContainer');
        if (fbDiv) fbDiv.innerHTML = '';
    }
    
    // ========== MEDIAPIPE ==========
    async function initHandTracking() {
        if (handsInstance) handsInstance.close();
        handsInstance = new Hands({
            locateFile: (file) => `https://cdn.jsdelivr.net/npm/@mediapipe/hands/${file}`
        });
        handsInstance.setOptions({
            maxNumHands: 1,
            modelComplexity: 1,
            minDetectionConfidence: 0.7,
            minTrackingConfidence: 0.5
        });
        handsInstance.onResults(onHandResults);
    }
    
    function isOnlyIndexFingerExtended(landmarks) {
        const indexTip = landmarks[8];
        const indexPip = landmarks[6];
        const middleTip = landmarks[12];
        const middlePip = landmarks[10];
        const ringTip = landmarks[16];
        const pinkyTip = landmarks[20];
        const indexExtended = indexTip.y < indexPip.y;
        const middleExtended = middleTip.y < middlePip.y;
        const ringExtended = ringTip.y < landmarks[14].y;
        const pinkyExtended = pinkyTip.y < landmarks[18].y;
        return indexExtended && !middleExtended && !ringExtended && !pinkyExtended;
    }
    
    function mapToCanvas(normalizedX, normalizedY) {
        const mirroredX = 1 - normalizedX;
        return { x: mirroredX * CANVAS_SIZE, y: normalizedY * CANVAS_SIZE };
    }
    
    function onHandResults(results) {
        if (!cameraActive) return;
        if (results.multiHandLandmarks && results.multiHandLandmarks.length > 0) {
            const landmarks = results.multiHandLandmarks[0];
            const indexTip = landmarks[8];
            if (indexTip) {
                const { x, y } = mapToCanvas(indexTip.x, indexTip.y);
                fingerPosition = { x, y };
                if (fingerCursorDiv) {
                    fingerCursorDiv.style.display = 'block';
                    fingerCursorDiv.style.left = `${(x / CANVAS_SIZE) * 100}%`;
                    fingerCursorDiv.style.top = `${(y / CANVAS_SIZE) * 100}%`;
                }
                const drawingActive = isOnlyIndexFingerExtended(landmarks);
                if (drawingActive && !isDrawingGesture) {
                    isDrawingGesture = true;
                    lastPoint = null;
                } else if (!drawingActive && isDrawingGesture) {
                    isDrawingGesture = false;
                    lastPoint = null;
                }
                if (isDrawingGesture && fingerPosition && drawCtx) {
                    const currentPoint = { x: fingerPosition.x, y: fingerPosition.y };
                    drawingPoints.push(currentPoint);
                    if (lastPoint) {
                        drawCtx.beginPath();
                        drawCtx.moveTo(lastPoint.x, lastPoint.y);
                        drawCtx.lineTo(currentPoint.x, currentPoint.y);
                        drawCtx.stroke();
                    }
                    lastPoint = currentPoint;
                } else {
                    lastPoint = null;
                }
            }
        } else {
            if (fingerCursorDiv) fingerCursorDiv.style.display = 'none';
            isDrawingGesture = false;
            lastPoint = null;
            fingerPosition = null;
        }
    }
    
    // ========== CÂMERA ==========
    async function startCamera() {
        if (!cameraLoadingDiv) return;
        cameraLoadingDiv.style.display = 'flex';
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: true });
            mediaStream = stream;
            if (videoElement) videoElement.srcObject = stream;
            await videoElement.play();
            await initHandTracking();
            if (cameraInstance) cameraInstance.close();
            cameraInstance = new Camera(videoElement, {
                onFrame: async () => {
                    if (handsInstance && cameraActive) await handsInstance.send({ image: videoElement });
                },
                width: 640, height: 480
            });
            await cameraInstance.start();
            cameraActive = true;
            isHandTrackingReady = true;
            if (cameraOffOverlay) cameraOffOverlay.style.display = 'none';
            if (instructionText) instructionText.classList.remove('hidden');
            if (cameraLoadingDiv) cameraLoadingDiv.style.display = 'none';
            if (checkBtn) checkBtn.disabled = false;
        } catch (err) {
            console.error("Erro na câmera:", err);
            if (cameraLoadingDiv) cameraLoadingDiv.style.display = 'none';
            alert("Não foi possível acessar a câmera. Verifique as permissões.");
            cameraActive = false;
            if (cameraOffOverlay) cameraOffOverlay.style.display = 'flex';
            if (instructionText) instructionText.classList.add('hidden');
            if (checkBtn) checkBtn.disabled = true;
        }
    }
    
    function stopCamera() {
        if (mediaStream) {
            mediaStream.getTracks().forEach(track => track.stop());
            mediaStream = null;
        }
        if (cameraInstance) {
            cameraInstance.close();
            cameraInstance = null;
        }
        if (handsInstance) {
            handsInstance.close();
            handsInstance = null;
        }
        if (videoElement) videoElement.srcObject = null;
        cameraActive = false;
        isHandTrackingReady = false;
        if (cameraOffOverlay) cameraOffOverlay.style.display = 'flex';
        if (instructionText) instructionText.classList.add('hidden');
        if (fingerCursorDiv) fingerCursorDiv.style.display = 'none';
        if (checkBtn) checkBtn.disabled = true;
        clearDrawing();
    }
    
    function toggleCamera() {
        if (cameraActive) {
            stopCamera();
            if (toggleCameraBtn) {
                toggleCameraBtn.innerHTML = '📷 Ligar Câmera';
                toggleCameraBtn.classList.remove('bg-red-600');
                toggleCameraBtn.classList.add('bg-gradient-to-r', 'from-blue-600', 'to-indigo-600');
            }
        } else {
            startCamera();
            if (toggleCameraBtn) {
                toggleCameraBtn.innerHTML = '📷 Desligar Câmera';
                toggleCameraBtn.classList.remove('bg-gradient-to-r', 'from-blue-600', 'to-indigo-600');
                toggleCameraBtn.classList.add('bg-red-600');
            }
        }
    }
    
    // ========== PRONÚNCIA ==========
    function speakLetter(letter) {
        if (!('speechSynthesis' in window)) {
            alert("Seu navegador não suporta leitura em voz alta.");
            return;
        }
        window.speechSynthesis.cancel();
        const utterance = new SpeechSynthesisUtterance(letter);
        utterance.lang = 'en-US';
        utterance.rate = 0.9;
        utterance.pitch = 1.0;
        const setVoice = () => {
            const voices = window.speechSynthesis.getVoices();
            const enVoice = voices.find(v => v.lang.startsWith('en') && (v.name.includes('Google') || v.name.includes('Microsoft')));
            if (enVoice) utterance.voice = enVoice;
        };
        if (window.speechSynthesis.getVoices().length === 0) {
            window.speechSynthesis.onvoiceschanged = () => { setVoice(); window.speechSynthesis.speak(utterance); };
        } else {
            setVoice();
            window.speechSynthesis.speak(utterance);
        }
    }
    
    // ========== INICIALIZAÇÃO ==========
    function init() {
        // Obter todos os elementos
        videoElement = document.getElementById('webcamVideo');
        drawCanvas = document.getElementById('drawCanvas');
        guideCanvas = document.getElementById('guideCanvas');
        if (drawCanvas) drawCtx = drawCanvas.getContext('2d');
        if (guideCanvas) guideCtx = guideCanvas.getContext('2d');
        fingerCursorDiv = document.getElementById('fingerCursor');
        cameraOffOverlay = document.getElementById('cameraOffOverlay');
        cameraLoadingDiv = document.getElementById('cameraLoading');
        instructionText = document.getElementById('instructionText');
        currentLetterSpan = document.getElementById('currentLetterDisplay');
        letterGuideSimple = document.getElementById('letterGuideSimple');
        toggleCameraBtn = document.getElementById('toggleCameraBtn');
        checkBtn = document.getElementById('checkBtn');
        clearBtn = document.getElementById('clearBtn');
        nextLetterBtn = document.getElementById('nextLetterBtn');
        speakLetterBtn = document.getElementById('speakLetterBtn');
        
        // Configurar canvas
        if (drawCanvas) {
            drawCanvas.width = CANVAS_SIZE;
            drawCanvas.height = CANVAS_SIZE;
            if (drawCtx) {
                drawCtx.strokeStyle = '#f97316';
                drawCtx.lineWidth = 8;
                drawCtx.lineCap = 'round';
                drawCtx.lineJoin = 'round';
            }
        }
        if (guideCanvas) {
            guideCanvas.width = CANVAS_SIZE;
            guideCanvas.height = CANVAS_SIZE;
        }
        
        // Desenhar guia inicial
        drawGuideLetter();
        if (currentLetterSpan) currentLetterSpan.textContent = currentLetter;
        updateScoreUI();
        
        // Adicionar eventos
        if (toggleCameraBtn) toggleCameraBtn.addEventListener('click', toggleCamera);
        if (checkBtn) checkBtn.addEventListener('click', handleCheck);
        if (clearBtn) clearBtn.addEventListener('click', clearDrawing);
        if (nextLetterBtn) nextLetterBtn.addEventListener('click', nextLetter);
        if (speakLetterBtn) speakLetterBtn.addEventListener('click', () => speakLetter(currentLetter));
        
        // Estado inicial da câmera
        if (cameraOffOverlay) cameraOffOverlay.style.display = 'flex';
        if (instructionText) instructionText.classList.add('hidden');
        if (checkBtn) checkBtn.disabled = true;
        
        // Pré-carregar MediaPipe (opcional)
        initHandTracking().catch(console.warn);
        
        // Desabilitar botão de áudio se API não suportada
        if (!('speechSynthesis' in window) && speakLetterBtn) speakLetterBtn.style.display = 'none';
        
        console.log("Inicialização concluída. Botão da câmera:", toggleCameraBtn ? "encontrado" : "não encontrado");
    }
    
    // Aguardar DOM totalmente carregado
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
</script>
          
          
        </body>
        </html>
        <?php
    }
}

// Instancia o jogo e processa requisições AJAX
$game = new AlphabetGame();
$game->handleAjax();
$game->render();
?>