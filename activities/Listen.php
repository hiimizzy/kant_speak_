<?php
require_once __DIR__ . '/../core/Atividade.php';\nrequire_once __DIR__ . '/../core/SessionManager.php';

class Listen extends Atividade {
    public function __construct(array $itens, SessionManager $session) {
        parent::__construct('Listen', $itens, 'listen_index', $session);
    }

    public function process(array $post): void {
        // Ação especial "reveal" para mostrar a palavra e ganhar pontos
        if (isset($post['resposta']) && $post['resposta'] === 'reveal') {
            $this->session->setFeedback('Palavra revelada! +5 pontos');
            $this->addPoints(5);
            // Não avança automaticamente, apenas revela
        }
    }
}