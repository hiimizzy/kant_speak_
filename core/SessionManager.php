<?php
class SessionManager {
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

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