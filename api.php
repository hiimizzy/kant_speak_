<?php
// Caminhos absolutos
require_once __DIR__ . '/core/SessionManager.php';
require_once __DIR__ . '/core/Atividade.php';
require_once __DIR__ . '/activities/Alphabet.php';
require_once __DIR__ . '/activities/Listen.php';
require_once __DIR__ . '/activities/Speak.php';
require_once __DIR__ . '/activities/Write.php';
// require_once __DIR__ . '/activities/Karaoke.php';
require_once __DIR__ . '/activities/Draw.php'; 
require_once __DIR__ . '/activities/ISpy.php';

$session = new SessionManager();

// Dados PRIMEIRO
$palavras = [
    // food
    ["word" => "APPLE", "emoji" => "🍎", "translation" => "maçã"],
    ["word" => "PINEAPPLE", "emoji" => "🍍", "translation" => "abacaxi"],
    ["word" => "GRAPE", "emoji" => "🍇", "translation" => "uva"],
    ["word" => "STRAWBERRY", "emoji" => "🍓", "translation" => "morango"],
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
    ["word" => "SNAIL",  "emoji" => "🐌", "translation" => "caracol"], 

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
    ["word" => "RAINBOW", "emoji" => "🌈", "translation" => "arco iris"],
    ["word" => "DOOR", "emoji" => "🚪", "translation" => "porta"],
    ["word" => "WINDOW", "emoji" => "🪟", "translation" => "janela"],
    ["word" => "TABLE", "emoji" => "🪵", "translation" => "mesa"],
    ["word" => "KEY", "emoji" => "🔑", "translation" => "chave"],
    ["word" => "PHONE", "emoji" => "📱", "translation" => "telefone"],
    ["word" => "COMPUTER", "emoji" => "💻", "translation" => "computador"],
    ["word" => "STAR",  "emoji" => "⭐", "translation" => "estrela"]
];

$spyItems = [
    ["letter" => "A", "word" => "Apple", "hint" => "starts with A, it's a red fruit"],
    ["letter" => "B", "word" => "Ball", "hint" => "starts with B, you can kick it"],
    ["letter" => "C", "word" => "Cat", "hint" => "starts with C, it says meow"],
    ["letter" => "D", "word" => "Dog", "hint" => "starts with D, it barks"],
    ["letter" => "E", "word" => "Elephant", "hint" => "starts with E, it has a trunk"],  
    ["letter" => "F", "word" => "Fish", "hint" => "starts with F, it swims"],
    ["letter" => "G", "word" => "Giraffe", "hint" => "starts with G, it has a long neck"],
    ["letter" => "I", "word" => "Ice Cream", "hint" => "starts with I, it's cold and sweet"],
    ["letter" => "H", "word" => "House", "hint" => "starts with H, it's where you live"],
    ["letter" => "W", "word" => "Water", "hint" => "starts with W, you drink it"],
    ["letter" => "M", "word" => "Moon", "hint" => "starts with M, it shines at night"],
    ["letter" => "R", "word" => "Rainbow", "hint" => "starts with R, it has many colors"],
    ["letter" => "K", "word" => "Key", "hint" => "starts with K, it opens doors"],
    ["letter" => "P", "word" => "Phone", "hint" => "starts with P, you use it to call"],
    ["letter" => "R", "word" => "Robot", "hint" => "starts with R, you use it to work and play"],
    ["letter" => "S", "word" => "Sun", "hint" => "starts with S, it shines in the sky"],
    ["letter" => "T", "word" => "Tree", "hint" => "starts with T, it has leaves"],
    ["letter" => "U", "word" => "Umbrella", "hint" => "starts with U, you use it when it rains"],
    ["letter" => "V", "word" => "Violin", "hint" => "starts with V, it's a musical instrument"],
    ["letter" => "X", "word" => "Xylophone", "hint" => "starts with X, it's a musical instrument with bars"],
    ["letter" => "Y", "word" => "Yak", "hint" => "starts with Y, it's a large animal with long hair"],
    ["letter" => "Z", "word" => "Zebra", "hint" => "starts with Z, it has black and white stripes"]
];

$letras = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];

// $musicas = []; 

$activities = [
    'alphabet' => new Alphabet($letras, $session),
    'listen'   => new Listen($palavras, $session),
    'speak'    => new Speak($palavras, $session),
    'write'    => new Write($palavras, $session),
    // 'karaoke'  => new Karaoke($musicas, $session),
    'draw'     => new Draw($palavras, $session),
    'ispy'     => new ISpy($spyItems, $session),
];

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$activity = $_POST['activity'] ?? $_GET['activity'] ?? '';

try {
    if (!$action || !isset($activities[$activity])) {
        throw new Exception('Ação ou atividade inválida: ' . $action . ' / ' . $activity);
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
        case 'next':
            $act->advance();
            echo json_encode(['success' => true, 'item' => $act->getCurrentItem()]);
            break;
        case 'getScore':
            echo json_encode(['score' => $session->getScore()]);
            break;
        default:
            throw new Exception('Ação desconhecida: ' . $action);
    }
} catch (Throwable $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}
?>

