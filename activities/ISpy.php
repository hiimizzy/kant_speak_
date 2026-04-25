<?php
require_once __DIR__ . '/../core/Atividade.php';

class ISpy extends Atividade {
    public function __construct(array $itens, SessionManager $session) {
        parent::__construct('I Spy', $itens, 'ispy_index', $session);
    }

    public function process(array $post): void {
        $resposta = strtolower(trim($post['resposta'] ?? ''));
        $correta = strtolower($this->getCurrentItem()['word']);

        if ($resposta === $correta) {
            $this->addPoints(10);
            $this->advance();
            $this->session->setFeedback('Great! You found it! +10 points');
        } else {
            $this->session->setFeedback('Try again! Listen to the hint.');
        }
    }
}