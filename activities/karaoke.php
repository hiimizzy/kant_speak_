<?php
require_once __DIR__ . '/../core/Atividade.php';\nrequire_once __DIR__ . '/../core/SessionManager.php';

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