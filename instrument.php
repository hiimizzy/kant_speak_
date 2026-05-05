<?php
require_once 'core/Logger.php';
session_start();
$logger = new Logger(session_id());
$data = json_decode(file_get_contents('php://input'), true);
$logger->logEvent($data['activity'], $data['event'], $data['payload']);
echo json_encode(['status' => 'logged']);