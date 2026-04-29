<?php
ini_set('display_errors', 0); 
error_reporting(E_ALL);
session_start();

require_once __DIR__ . '/core/SessionManager.php';
require_once __DIR__ . '/core/Atividade.php';

// Carrega todas as atividades
require_once __DIR__ . '/activities/Alphabet.php';
require_once __DIR__ . '/activities/Listen.php';
require_once __DIR__ . '/activities/Speak.php';
require_once __DIR__ . '/activities/Write.php';
require_once __DIR__ . '/activities/Karaoke.php';
require_once __DIR__ . '/activities/Draw.php';
require_once __DIR__ . '/activities/ISpy.php';
require_once __DIR__ . '/activities/BuildWord.php';

$session = new SessionManager();

// ========== DADOS ==========
$palavras = [
    ["word" => "APPLE", "emoji" => "🍎", "translation" => "maçã"],
    ["word" => "DOG",   "emoji" => "🐕", "translation" => "cachorro"],
    ["word" => "SUN",   "emoji" => "☀️", "translation" => "sol"],
    ["word" => "HOUSE", "emoji" => "🏠", "translation" => "casa"],
    ["word" => "CAT",   "emoji" => "🐱", "translation" => "gato"],
    ["word" => "CAR",   "emoji" => "🚗", "translation" => "carro"],
    ["word" => "BIRD",  "emoji" => "🐦", "translation" => "pássaro"],
    ["word" => "FISH",  "emoji" => "🐠", "translation" => "peixe"],
    ["word" => "BOOK",  "emoji" => "📚", "translation" => "livro"],
    ["word" => "STAR",  "emoji" => "⭐", "translation" => "estrela"]
];

$letras = ["A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"];

$spyItems = [
    ["letter" => "A", "word" => "Apple", "emoji" => "🍎", "hint" => "starts with A, it's a red fruit"],
    ["letter" => "B", "word" => "Ball",  "emoji" => "⚽", "hint" => "starts with B, you can kick it"],
    ["letter" => "C", "word" => "Cat",   "emoji" => "🐱", "hint" => "starts with C, it says meow"],
    ["letter" => "D", "word" => "Dog",   "emoji" => "🐕", "hint" => "starts with D, it barks"],
    ["letter" => "E", "word" => "Egg",   "emoji" => "🥚", "hint" => "starts with E, it can be scrambled"],
    ["letter" => "F", "word" => "Fish",  "emoji" => "🐠", "hint" => "starts with F, it swims"],
    ["letter" => "G", "word" => "Guitar",  "emoji" => "🎸", "hint" => "starts with G, you can strum it"],
    ["letter" => "H", "word" => "House",  "emoji" => "🏠", "hint" => "starts with H, it's where you live"],
    ["letter" => "I", "word" => "Ice Cream",  "emoji" => "🍦", "hint" => "starts with I, it's a cold treat"],
    ["letter" => "J", "word" => "Juice",  "emoji" => "🧃", "hint" => "starts with J, it's a drink"],
    ["letter" => "K", "word" => "Kite",  "emoji" => "🪁", "hint" => "starts with K, it flies in the sky"],
    ["letter" => "L", "word" => "Lion",  "emoji" => "🦁", "hint" => "starts with L, it's the king of the jungle"],
    ["letter" => "M", "word" => "Moon",  "emoji" => "🌙", "hint" => "starts with M, it shines at night"],
    ["letter" => "N", "word" => "Nose",  "emoji" => "👃", "hint" => "starts with N, it's on your face"]
];

$buildWords = [
    ["word" => "CAT", "emoji" => "🐱", "syllables" => ["CA", "T"]],
    ["word" => "DOG", "emoji" => "🐕", "syllables" => ["DO", "G"]],
    ["word" => "SUN", "emoji" => "☀️", "syllables" => ["SU", "N"]],
    ["word" => "FISH", "emoji" => "🐠", "syllables" => ["FI", "SH"]],
    ["word" => "BIRD", "emoji" => "🐦", "syllables" => ["BI", "RD"]],
    ["word" => "APPLE", "emoji" => "🍎", "syllables" => ["AP", "PLE"]],
    ["word" => "STAR", "emoji" => "⭐", "syllables" => ["ST", "AR"]],
    ["word" => "CAR", "emoji" => "🚗", "syllables" => ["CA", "R"]],
    ["word" => "HOUSE", "emoji" => "🏠", "syllables" => ["HO", "USE"]],
    ["word" => "BOOK", "emoji" => "📚", "syllables" => ["BO", "OK"]],
    ["word" => "MOON", "emoji" => "🌙", "syllables" => ["MO", "ON"]],
    ["word" => "LION", "emoji" => "🦁", "syllables" => ["LI", "ON"]],
    ["word" => "GUITAR", "emoji" => "🎸", "syllables" => ["GUI", "TAR"]],
    ["word" => "JUICE", "emoji" => "🧃", "syllables" => ["JU", "ICE"]],
    ["word" => "KITE", "emoji" => "🪁", "syllables" => ["KI", "TE"]],
    ["word" => "HOUSE", "emoji" => "🏠", "syllables" => ["HO", "USE"]],
    ["word" => "EYES", "emoji" => "👀", "syllables" => ["EY", "ES"]],
    ["word" => "NOSE", "emoji" => "👃", "syllables" => ["NO", "SE"]],
    ["word" => "MOUTH", "emoji" => "👄", "syllables" => ["MO", "UTH"]],
    ["word" => "HAND", "emoji" => "✋", "syllables" => ["HA", "ND"]],
    ["word" => "FOOT", "emoji" => "🦶", "syllables" => ["FO", "OT"]],
    ["word" => "EAR", "emoji" => "👂", "syllables" => ["EA", "R"]],
    ["word" => "HAIR", "emoji" => "💇‍♂️", "syllables" => ["HA", "IR"]],
    ["word" => "FACE", "emoji" => "😀", "syllables" => ["FA", "CE"]],
    ["word" => "BODY", "emoji" => "🧍‍♂️", "syllables" => ["BO", "DY"]],
    ["word" => "TREE", "emoji" => "🌳", "syllables" => ["TR", "EE"]],
    ["word" => "FLOWER", "emoji" => "🌸", "syllables" => ["FLO", "WER"]],
    ["word" => "GRASS", "emoji" => "🌿", "syllables" => ["GRA", "SS"]],
    ["word" => "WATER", "emoji" => "💧", "syllables" => ["WA", "TER"]],
    ["word" => "FIRE", "emoji" => "🔥", "syllables" => ["FI", "RE"]],
    ["word" => "EARTH", "emoji" => "🌍", "syllables" => ["EA", "RTH"]],
    ["word" => "AIR", "emoji" => "💨", "syllables" => ["AI", "R"]]
];

$activities = [
    'alphabet'   => new Alphabet($letras, $session),
    'listen'     => new Listen($palavras, $session),
    'speak'      => new Speak($palavras, $session),
    'write'      => new Write($palavras, $session),
    'draw'       => new Draw($palavras, $session),
    'ispy'       => new ISpy($spyItems, $session),
    'buildword'  => new BuildWord($buildWords, $session)
];

header('Content-Type: application/json');

$action   = $_POST['action'] ?? $_GET['action'] ?? '';
$activity = $_POST['activity'] ?? $_GET['activity'] ?? '';

if (!$action || !isset($activities[$activity])) {
    echo json_encode(['error' => 'Ação ou atividade inválida']);
    exit;
}

$act = $activities[$activity];
// Processa a ação solicitada
try {
    switch ($action) {
        case 'getItem':
            $item = $act->getCurrentItem();
            echo json_encode(['success' => true, 'item' => $item]);
            break;
        case 'check':
            $resposta = $_POST['resposta'] ?? '';
            $act->process(['resposta' => $resposta]);
            $feedback = $session->getFeedbackAndClear();
            $score = $session->getScore();
            echo json_encode(['success' => true, 'feedback' => $feedback, 'score' => $score]);
            break;
        case 'next':
            $act->advance();
            $newItem = $act->getCurrentItem();
            echo json_encode(['success' => true, 'item' => $newItem]);
            break;
        case 'getScore':
            echo json_encode(['score' => $session->getScore()]);
            break;
        case 'getAllItems':
            // Usado opcionalmente por algumas atividades
            echo json_encode(['success' => true, 'items' => $act->getAllItems()]);
            break;
        default:
            echo json_encode(['error' => 'Ação desconhecida']);
    }
} catch (Throwable $e) {
    echo json_encode(['error' => $e->getMessage()]);
}