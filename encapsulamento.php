<?php
session_start();

// ---------- Dados ----------
$palavras = [
    ["word" => "Apple", "emoji" => "🍎", "audio" => "Apple"],
    ["word" => "Ball",  "emoji" => "⚽",  "audio" => "Ball"],
    ["word" => "Dog",   "emoji" => "🐶",  "audio" => "Dog"],
];
$letras = ["A", "B", "C"];

// ---------- Gerenciador de Sessão (encapsulamento) ----------
class SessionManager {
    public function get(string $key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }

    public function set(string $key, $value): void {
        $_SESSION[$key] = $value;
    }

    public function incrementScore(int $points): void {
        $_SESSION['score'] = ($_SESSION['score'] ?? 0) + $points;
    }

    public function getScore(): int {
        return $_SESSION['score'] ?? 0;
    }

    public function setFeedback(string $msg): void {
        $_SESSION['feedback'] = $msg;
    }

    public function getFeedbackAndClear(): ?string {
        $msg = $_SESSION['feedback'] ?? null;
        unset($_SESSION['feedback']);
        return $msg;
    }
}

// ---------- Classe abstrata ----------
abstract class Atividade {
    protected string $nome;
    protected SessionManager $session;
    protected array $itens;          // palavras ou letras
    protected string $indexKey;      // chave na sessão para guardar o índice atual

    public function __construct(string $nome, array $itens, string $indexKey, SessionManager $session) {
        $this->nome = $nome;
        $this->itens = $itens;
        $this->indexKey = $indexKey;
        $this->session = $session;
    }

    protected function getCurrentIndex(): int {
        return $this->session->get($this->indexKey, 0);
    }

    protected function setCurrentIndex(int $index): void {
        $this->session->set($this->indexKey, $index);
    }

    protected function getCurrentItem() {
        $idx = $this->getCurrentIndex();
        return $this->itens[$idx] ?? null;
    }

    protected function advanceToNextItem(): void {
        $next = ($this->getCurrentIndex() + 1) % count($this->itens);
        $this->setCurrentIndex($next);
    }

    protected function addPoints(int $value): void {
        $this->session->incrementScore($value);
    }

    abstract public function render(): void;

    // Processa a resposta do usuário (POST)
    abstract public function process(array $postData): void;
}

// ---------- Subclasse Listen (somente renderização, sem validação) ----------
class Listen extends Atividade {
    public function __construct(array $itens, SessionManager $session) {
        parent::__construct('Listen', $itens, 'listen_index', $session);
    }

    public function render(): void {
        $item = $this->getCurrentItem();
        if (!$item) return;
        echo "<div class='card'>
                <h2>Listen</h2>
                <div class='emoji'>{$item['emoji']}</div>
                <p>Ouça e repita:</p>
                <button onclick=\"falar('{$item['audio']}')\">🔊 Ouvir</button>
              </div>";
    }

    public function process(array $postData): void {
        // Listen não processa envio do usuário (apenas áudio)
    }
}

// ---------- Subclasse Write ----------
class Write extends Atividade {
    public function __construct(array $itens, SessionManager $session) {
        parent::__construct('Write', $itens, 'write_index', $session);
    }

    public function render(): void {
        $item = $this->getCurrentItem();
        echo "<div class='card'>
                <h2>Write</h2>
                <div class='emoji'>{$item['emoji']}</div>
                <form method='POST'>
                    <input type='hidden' name='action' value='write'>
                    <input type='text' name='resposta' placeholder='Digite a palavra'>
                    <button type='submit'>Enviar</button>
                </form>
              </div>";
    }

    public function process(array $postData): void {
        $resposta = strtolower(trim($postData['resposta'] ?? ''));
        $correta = strtolower($this->getCurrentItem()['word']);
        if ($resposta === $correta) {
            $this->session->setFeedback('Correto!');
            $this->addPoints(10);
            $this->advanceToNextItem();
        } else {
            $this->session->setFeedback('Tente novamente');
        }
    }
}

// ---------- Subclasse Speak ----------
class Speak extends Atividade {
    public function __construct(array $itens, SessionManager $session) {
        parent::__construct('Speak', $itens, 'speak_index', $session);
    }

    public function render(): void {
        $item = $this->getCurrentItem();
        echo "<div class='card'>
                <h2>Speak</h2>
                <div class='emoji'>{$item['emoji']}</div>
                <p>Fale a palavra em inglês:</p>
                <button type='button' onclick='iniciarReconhecimento()'>🎤 Falar</button>
                <form method='POST' id='formSpeak'>
                    <input type='hidden' name='action' value='speak'>
                    <input type='hidden' name='resposta' id='falaCapturada'>
                </form>
              </div>";
    }

    public function process(array $postData): void {
        $resposta = strtolower(trim($postData['resposta'] ?? ''));
        $correta = strtolower($this->getCurrentItem()['word']);
        similar_text($resposta, $correta, $percentual);
        if ($percentual >= 80) {
            $this->session->setFeedback('Pronúncia muito boa!');
            $this->addPoints(10);
            $this->advanceToNextItem();
        } else {
            $this->session->setFeedback('Tente pronunciar novamente');
        }
    }
}

// ---------- Subclasse Alphabet ----------
class Alphabet extends Atividade {
    public function __construct(array $itens, SessionManager $session) {
        parent::__construct('Alphabet', $itens, 'alphabet_index', $session);
    }

    public function render(): void {
        $letra = $this->getCurrentItem();
        echo "<div class='card'>
                <h2>Alphabet</h2>
                <p class='letter'>{$letra}</p>
                <form method='POST'>
                    <input type='hidden' name='action' value='alphabet'>
                    <input type='text' name='resposta' maxlength='1' placeholder='Digite a letra'>
                    <button type='submit'>Enviar</button>
                </form>
              </div>";
    }

    public function process(array $postData): void {
        $resposta = strtoupper(trim($postData['resposta'] ?? ''));
        $correta = $this->getCurrentItem();
        if ($resposta === $correta) {
            $this->session->setFeedback('Acertou a letra!');
            $this->addPoints(10);
            $this->advanceToNextItem();
        } else {
            $this->session->setFeedback('Tente novamente');
        }
    }
}

// ---------- Processamento de requisições POST ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sessionManager = new SessionManager();
    $action = $_POST['action'] ?? '';

    // Mapeamento de ações para classes
    $activities = [
        'write'   => new Write($palavras, $sessionManager),
        'speak'   => new Speak($palavras, $sessionManager),
        'alphabet'=> new Alphabet($letras, $sessionManager),
    ];

    if (isset($activities[$action])) {
        $activities[$action]->process($_POST);
    }

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// ---------- Renderização da página ----------
$sessionManager = new SessionManager();
$feedback = $sessionManager->getFeedbackAndClear();
$score = $sessionManager->getScore();

// // Teste manual
// $session = new SessionManager();
// $write = new Write($palavras, $session);
// echo "Índice inicial: " . $write->getCurrentIndex(); // 0
// $write->advanceToNextItem();
// echo "Após avançar: " . $write->getCurrentIndex(); // 1
// $write->addPoints(5);
// echo "Pontos: " . $session->getScore(); // 5

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kant Speak</title>
<style>
/* (mesmo CSS original, mantido) */
body { font-family: Arial, sans-serif; background: linear-gradient(135deg,#8b5cf6,#3b82f6); color:#fff; margin:0; padding:20px; }
.container { max-width: 900px; margin: auto; }
.header { display:flex; justify-content:space-between; align-items:center; }
.grid { display: grid; grid-template-columns: repeat(2, minmax(220px, 1fr)); gap: 16px; margin-top: 20px; }
@media (max-width: 768px) { .grid { grid-template-columns: 1fr; } }
.card { background: #ffffff20; padding: 18px; border-radius: 18px; backdrop-filter: blur(10px); text-align: center; min-height: 220px; }
.emoji { font-size: 60px; }
.letter { font-size: 56px; font-weight: bold; }
input { padding: 10px; border: none; border-radius: 10px; width: 80%; margin: 8px 0; }
button { padding:12px 20px; border:none; border-radius:10px; cursor:pointer; background:#22c55e; color:#fff; }
.feedback { margin-top:20px; background:#00000030; padding:15px; border-radius:12px; }
</style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Kant Speak Pals</h1>
        <h3>Pontos: <?= $score ?></h3>
    </div>

    <?php if ($feedback): ?>
        <div class="feedback"><?= htmlspecialchars($feedback) ?></div>
    <?php endif; ?>

    <div class="grid">
        <?php (new Listen($palavras, $sessionManager))->render(); ?>
        <?php (new Write($palavras, $sessionManager))->render(); ?>
        <?php (new Speak($palavras, $sessionManager))->render(); ?>
        <?php (new Alphabet($letras, $sessionManager))->render(); ?>
    </div>
</div>

<script>
function falar(texto) {
    const msg = new SpeechSynthesisUtterance(texto);
    msg.lang = 'en-US';
    speechSynthesis.speak(msg);
}

function iniciarReconhecimento() {
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    if (!SpeechRecognition) {
        alert('Seu navegador não suporta reconhecimento de voz.');
        return;
    }
    const recognition = new SpeechRecognition();
    recognition.lang = 'en-US';
    recognition.start();
    recognition.onresult = function(event) {
        const texto = event.results[0][0].transcript;
        document.getElementById('falaCapturada').value = texto;
        document.getElementById('formSpeak').submit();
    };
    recognition.onerror = function() {
        alert('Não foi possível capturar sua fala.');
    };
}
</script>
</body>
</html>