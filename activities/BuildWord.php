<?php
require_once __DIR__ . '/../core/Atividade.php';

class BuildWord extends Atividade {
    public function __construct(array $itens, SessionManager $session) {
        parent::__construct('Build Word', $itens, 'buildword_index', $session);
    }

    public function process(array $post): void {
        $resposta = strtoupper(trim($post['resposta'] ?? ''));
        $correta = strtoupper($this->getCurrentItem()['word']);

        if ($resposta === $correta) {
            $this->addPoints(10);
            $this->advance();
            $this->session->setFeedback('Great! You built the word! +10 points');
        } else {
            $this->session->setFeedback('Try again! The correct word is ' . $correta);
        }
    }
}