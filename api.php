<?php
// Caminhos absolutos
require_once __DIR__ . '/core/SessionManager.php';
require_once __DIR__ . '/core/Atividade.php';
require_once __DIR__ . '/activities/Alphabet.php';
require_once __DIR__ . '/activities/Listen.php';
require_once __DIR__ . '/activities/Speak.php';
require_once __DIR__ . '/activities/Write.php';
require_once __DIR__ . '/activities/Karaoke.php';
require_once __DIR__ . '/activities/Draw.php'; //
$session = new SessionManager();

// Dados - podem vir de um arquivo de configuração ou banco
$palavras = [
    // food
    ["word" => "APPLE", "emoji" => "🍎", "translation" => "maçã"],
    // animals
    ["word" => "DOG",   "emoji" => "🐕", "translation" => "cachorro"],
    ["word" => "CAT",   "emoji" => "🐱", "translation" => "gato"],
    ["word" => "BIRD",  "emoji" => "🐦", "translation" => "pássaro"],
    ["word" => "FISH",  "emoji" => "🐠", "translation" => "peixe"],  
    ["word" => "LION",  "emoji" => "🦁", "translation" => "Leão"],
    ["word" => "DUCK",  "emoji" => "🦆", "translation" => "Pato"],
    ["word" => "TURTLE",  "emoji" => "🐢", "translation" => "Tartaruga"],
    ["word" => "TIGER",  "emoji" => "🐅", "translation" => "tigre"],
    ["word" => "BEAR",  "emoji" => "🐻", "translation" => "Urso"],
    ["word" => "POLAR BEAR",  "emoji" => "🐻‍❄️", "translation" => "Urso Polar"],
    ["word" => "KOALA",  "emoji" => "🐨", "translation" => "Coala"],
    ["word" => "BEE",  "emoji" => "🐝", "translation" => "Abelha"],
    ["word" => "ANT",  "emoji" => "🐜", "translation" => "Formiga"],
    ["word" => "SNAIL",  "emoji" => "🐌", "translation" => "livro"],

    ["word" => "SUN",   "emoji" => "☀️", "translation" => "sol"],
    ["word" => "HOUSE", "emoji" => "🏠", "translation" => "casa"],
    ["word" => "CAR",   "emoji" => "🚗", "translation" => "carro"],
    ["word" => "TREE", "emoji" => "🌳", "translation" => "árvore"],
    ["word" => "WATER", "emoji" => "💧", "translation" => "água"],
    ["word" => "FIRE", "emoji" => "🔥", "translation" => "fogo"],
    ["word" => "AIR", "emoji" => "💨", "translation" => "ar"],
    ["word" => "EARTH", "emoji" => "🌍", "translation" => "terra"],
    ["word" => "MOON", "emoji" => "🌙", "translation" => "lua"],
    ["word" => "FLOWER", "emoji" => "🌸", "translation" => "flor"],
    ["word" => "BALL", "emoji" => "⚽", "translation" => "bola"],
    ["word" => "HAT", "emoji" => "🎩", "translation" => "chapéu"],
    ["word" => "SHOE", "emoji" => "👟", "translation" => "sapato"],
    ["word" => "BED", "emoji" => "🛏️", "translation" => "cama"],
    ["word" => "CHAIR", "emoji" => "🪑", "translation" => "cadeira"],
    ["word" => "TABLE", "emoji" => "🪵", "translation" => "mesa"],
    ["word" => "DOOR", "emoji" => "🚪", "translation" => "porta"],
    ["word" => "WINDOW", "emoji" => "🪟", "translation" => "janela"],
    ["word" => "BED", "emoji" => "🛏️", "translation" => "cama"],
    ["word" => "CHAIR", "emoji" => "🪑", "translation" => "cadeira"],
    ["word" => "TABLE", "emoji" => "🪵", "translation" => "mesa"],
    ["word" => "DOOR", "emoji" => "🚪", "translation" => "porta"],
    ["word" => "KEY", "emoji" => "🔑", "translation" => "chave"],
    ["word" => "PHONE", "emoji" => "📱", "translation" => "telefone"],
    ["word" => "COMPUTER", "emoji" => "💻", "translation" => "computador"],
    ["word" => "STAR",  "emoji" => "⭐", "translation" => "estrela"]
];

$letras = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];

$musicas = [
    ["titulo" => "Twinkle Twinkle Little Star", "letra" => "Twinkle twinkle little star\nHow I wonder what you are\nUp above the world so high\nLike a diamond in the sky"],
    ["titulo" => "Old MacDonald Had a Farm", "letra" => "Old MacDonald had a farm\nE-I-E-I-O\nAnd on his farm he had a cow\nE-I-E-I-O"],
];

$activities = [
    'alphabet' => new Alphabet($letras, $session),
    'listen'   => new Listen($palavras, $session),
    'speak'    => new Speak($palavras, $session),
    'write'    => new Write($palavras, $session),
    'karaoke'  => new Karaoke($musicas, $session),
    'draw'     => new Draw($palavras, $session)
];

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$activity = $_POST['activity'] ?? $_GET['activity'] ?? '';
try {
    $action = $_POST['action'] ?? $_GET['action'] ?? '';
    $activity = $_POST['activity'] ?? $_GET['activity'] ?? '';

    if (!$action || !isset($activities[$activity])) {
        throw new Exception('Ação ou atividade inválida');
    }

    $act = $activities[$activity];

    switch ($action) {
        case 'getItem':
            echo json_encode(['success' => true, 'item' => $act->getCurrentItem()]);
            break;
        case 'check':
    $act->process($_POST);
    $feedback = $session->getFeedbackAndClear();
    $score = $session->getScore(); 
    echo json_encode([
        'success' => true, 
        'feedback' => $feedback, 
        'score' => $score
    ]);
    break;
            $act->advance();
            echo json_encode(['success' => true, 'item' => $act->getCurrentItem()]);
            break;
        case 'getScore':
            echo json_encode(['score' => $session->getScore()]);
            break;
        default:
            throw new Exception('Ação desconhecida');
    }
} catch (Throwable $e) {
    echo json_encode(['error' => $e->getMessage()]);
}