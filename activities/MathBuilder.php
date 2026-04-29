<?php
require_once __DIR__ . '/../core/Atividade.php';

class MathBuilder extends Atividade {
    public function __construct(array $itens, SessionManager $session) {
        parent::__construct('Math Builder', $itens, 'math_index', $session);
    }

    public function process(array $post): void {
        $num1 = (int)($post['num1'] ?? 0);
        $operador = $post['operator'] ?? '';
        $num2 = (int)($post['num2'] ?? 0);
        $resposta = (int)($post['answer'] ?? 0);

        $certo = false;
        if ($operador === '+') {
            $certo = ($num1 + $num2 === $resposta);
        } elseif ($operador === '-') {
            $certo = ($num1 - $num2 === $resposta);
        }

        if ($certo) {
            $this->addPoints(15);
            // Não usamos $this->advance() porque a atividade é livre (a criança escolhe os números)
            $this->session->setFeedback('🎉 Correct! +15 points');
        } else {
            $this->session->setFeedback('❌ Oops! Try again.');
        }
    }
}