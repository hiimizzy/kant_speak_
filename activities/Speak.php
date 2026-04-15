<?php
require_once __DIR__ . '/../core/Atividade.php';\nrequire_once __DIR__ . '/../core/SessionManager.php';

class Speak extends Atividade {
    public function __construct($itens, $session) {
        parent::__construct('Speak', $itens, 'speak_index', $session);
    }

    public function process(array $post): void {
        $resposta = strtolower(trim($post['resposta'] ?? ''));
        $correta = strtolower($this->getCurrentItem()['word']);

        similar_text($resposta, $correta, $percentual);

        if ($percentual >= 80) {
            $this->session->setFeedback('Pronúncia muito boa!');
            $this->addPoints(10);
            $this->advance();
        } else {
            $this->session->setFeedback('Tente novamente');
        }
    }
}