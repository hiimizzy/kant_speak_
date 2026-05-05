<?php
class AdaptiveEngine {
    private Logger $logger;
    private array $arms; // atividades ou níveis

    public function __construct(Logger $logger, array $arms) {
        $this->logger = $logger;
        $this->arms = $arms;
    }

    // Thompson Sampling para escolher próxima atividade
    public function selectNextActivity(array $history): string {
        $scores = [];
        foreach ($this->arms as $arm) {
            $successes = $history[$arm]['success'] ?? 0;
            $failures = $history[$arm]['failure'] ?? 0;
            // Amostra da Beta posterior
            $scores[$arm] = random_int(0, $successes + $failures) / ($successes + $failures + 1);
        }
        arsort($scores);
        return array_key_first($scores);
    }

    // Função de recompensa: acerto = +1, erro = 0
    public function updateReward(string $arm, bool $correct): void {
        $this->logger->logEvent('adaptive', 'reward', ['arm' => $arm, 'correct' => $correct]);
    }
}