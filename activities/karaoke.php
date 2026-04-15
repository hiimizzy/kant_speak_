<?php
$rootDir = dirname(__DIR__);
require_once $rootDir . '/core/Atividade.php';
require_once $rootDir . '/core/SessionManager.php';

if (!class_exists('Karaoke')) {
  class Karaoke extends Atividade {
    public function __construct(array $itens, SessionManager $session) {
      parent::__construct('Karaoke', $itens, 'karaoke_index', $session);
    }

    public function process(array $post): void {
      if (isset($post['complete'])) {
        $this->session->setFeedback('Parabéns! Você cantou muito bem!');
        $this->addPoints(20);
        $this->advance();
      }
    }
  }
}
?>
