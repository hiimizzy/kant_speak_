<?php
$rootDir = dirname(__DIR__);
require_once $rootDir . '/core/Atividade.php';
require_once $rootDir . '/core/SessionManager.php';

if (!class_exists('Write')) {
  class Write extends Atividade {
    public function __construct(array $itens, SessionManager $session) {
      parent::__construct('Write', $itens, 'write_index', $session);
    }

    public function process(array $post): void {
      $resposta = strtolower(trim($post['resposta'] ?? ''));
      $correta = strtolower($this->getCurrentItem()['word']);

      if ($resposta === $correta) {
        $this->session->setFeedback('Correto!');
        $this->addPoints(10);
        $this->advance();
      } else {
        $this->session->setFeedback('Tente novamente');
      }
    }
  }
}
?>
