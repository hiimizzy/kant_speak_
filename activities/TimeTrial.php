<?php
require_once __DIR__ . '/../core/Atividade.php';

class TimeTrial extends Atividade {
    public function __construct(array $itens, SessionManager $session) {
        parent::__construct('Time Trial', $itens, 'timetrial_index', $session);
    }
    public function process(array $post): void {
        $resposta = strtoupper(trim($post['resposta'] ?? ''));
        $correta = strtoupper($this->getCurrentItem()['word']);
        if ($resposta === $correta) {
            $this->addPoints(10);
            $this->advance();
            $this->session->setFeedback('Correct! +10 points');
        } else {
            $this->session->setFeedback('Wrong! Try again.');
        }
    }
}