<?php
$rootDir = dirname(__DIR__);
require_once $rootDir . '/core/Atividade.php';
require_once $rootDir . '/core/SessionManager.php';

if (!class_exists('Listen')) {
  class Listen extends Atividade {
    public function __construct(array $itens, SessionManager $session) {
      parent::__construct('Listen', $itens, 'listen_index', $session);
    }

    public function process(array $post): void {
      if (isset($post['resposta']) && $post['resposta'] === 'reveal') {
        $this->session->setFeedback('Palavra revelada! +5 pontos');
        $this->addPoints(5);
      }
    }
  }
}
?>
