<?php
$rootDir = dirname(__DIR__);
require_once $rootDir . '/core/Atividade.php';
require_once $rootDir . '/core/SessionManager.php';

if (!class_exists('Draw')) {
  class Draw extends Atividade {
    public function __construct(array $itens, SessionManager $session) {
        parent::__construct('Draw', $itens, 'draw_index', $session);
    }

    public function process(array $post): void {
        if (isset($post['complete'])) {
            $this->addPoints(15);
            $this->advance();
            $this->session->setFeedback('Desenho incrível! +15 pontos');
        }
    }
  }
}
?>
