<?php
require_once 'core/AdaptiveEngine.php';
require_once 'core/Logger.php';
session_start();
$logger = new Logger(session_id());
$engine = new AdaptiveEngine($logger, ['alphabet', 'listen', 'speak', 'write', 'sorting']);
// Recupera histórico da sessão
$history = $_SESSION['activity_history'] ?? [];
$next = $engine->selectNextActivity($history);
echo json_encode(['next_activity' => $next]);