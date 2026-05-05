<?php
class Logger {
    private string $sessionId;
    private string $logDir;

    public function __construct(string $sessionId) {
        $this->sessionId = $sessionId;
        $this->logDir = __DIR__ . '/../data/sessions/';
        if (!is_dir($this->logDir)) mkdir($this->logDir, 0777, true);
    }

    public function logEvent(string $activity, string $eventType, array $data): void {
        $entry = [
            'timestamp' => microtime(true),
            'session' => $this->sessionId,
            'activity' => $activity,
            'event' => $eventType,
            'data' => $data
        ];
        $file = $this->logDir . $this->sessionId . '.json';
        $current = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
        $current[] = $entry;
        file_put_contents($file, json_encode($current, JSON_PRETTY_PRINT));
    }
}