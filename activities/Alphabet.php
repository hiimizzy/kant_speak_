<?php
$rootDir = dirname(__DIR__);
require_once $rootDir . '/core/Atividade.php';
require_once $rootDir . '/core/SessionManager.php';

if (!class_exists('Alphabet')) {
  class Alphabet extends Atividade {
    public function __construct(array $itens, SessionManager $session) {
      parent::__construct('Alphabet', $itens, 'alphabet_index', $session);
    }

    public function process(array $post): void {
      $resposta = strtoupper(trim($post['resposta'] ?? ''));
      $correta = $this->getCurrentItem();

      if ($resposta === $correta) {
        $this->session->setFeedback('Acertou a letra!');
        $this->addPoints(10);
        $this->advance();
      } else {
        $this->session->setFeedback('Tente novamente');
      }
    }
  }
}
?>
