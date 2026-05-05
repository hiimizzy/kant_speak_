<?php
$logDir = __DIR__ . '/data/sessions/';
if (!is_dir($logDir)) mkdir($logDir, 0777, true);
$input = file_get_contents('php://input');
$data = json_decode($input, true);
if ($data && isset($data['session'])) {
    $file = $logDir . $data['session'] . '.json';
    $current = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
    $current[] = $data;
    file_put_contents($file, json_encode($current, JSON_PRETTY_PRINT));
    echo json_encode(['status' => 'ok']);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid data']);
}