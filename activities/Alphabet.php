<?php
require_once __DIR__ . '/../core/Atividade.php';\nrequire_once __DIR__ . '/../core/SessionManager.php';

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