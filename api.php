<?php
require_once 'core/SessionManager.php';
require_once 'activities/Alphabet.php';
require_once 'activities/Listen.php';
require_once 'activities/Speak.php';
require_once 'activities/Write.php';
require_once 'activities/Karaoke.php';

$session = new SessionManager();

// Dados - podem vir de um arquivo de configuração ou banco
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
];

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$activity = $_POST['activity'] ?? $_GET['activity'] ?? '';

if (!$action || !isset($activities[$activity])) {
    echo json_encode(['error' => 'Ação ou atividade inválida']);
    exit;
}

$act = $activities[$activity];

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
        $score = $session->getScore();
        echo json_encode(['success' => true, 'item' => $newItem, 'score' => $score]);
        break;

    case 'getScore':
        echo json_encode(['score' => $session->getScore()]);
        break;

    case 'complete' : // específico para karaoke
        $act->process(['complete' => 1]);
        $feedback = $session->getFeedbackAndClear();
        $score = $session->getScore();
        $newItem = $act->getCurrentItem();
        echo json_encode(['success' => true, 'feedback' => $feedback, 'score' => $score, 'item' => $newItem]);
        break;

    default:
        echo json_encode(['error' => 'Ação desconhecida']);
}