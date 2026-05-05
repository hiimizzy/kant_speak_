<?php
class ExperimentManager {
    private string $expId;
    private array $config;

    public function __construct(string $expId) {
        $this->expId = $expId;
        $configFile = __DIR__ . "/../data/experiments/{$expId}.json";
        $this->config = json_decode(file_get_contents($configFile), true);
    }

    public function assignGroup(string $userId): string {
        $hash = crc32($userId) % 100;
        foreach ($this->config['groups'] as $group => $range) {
            if ($hash >= $range[0] && $hash < $range[1]) return $group;
        }
        return $this->config['default'];
    }

    public function logMetric(string $userId, string $metric, $value): void {
        $logger = new Logger($userId);
        $logger->logEvent('experiment', $metric, ['exp' => $this->expId, 'value' => $value]);
    }
}