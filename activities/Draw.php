<?php
require_once __DIR__ . '/../core/Atividade.php';

class Draw extends Atividade {
    public function __construct(array $itens, SessionManager $session) {
        parent::__construct('Draw', $itens, 'draw_index', $session);
    }

    public function process(array $post): void {
        // Ação de completar o desenho (vinda do botão "Completei")
        if (isset($post['complete'])) {
            $this->addPoints(15);
            $this->advance();
            $this->session->setFeedback('Desenho incrível! +15 pontos');
        }
    }
}