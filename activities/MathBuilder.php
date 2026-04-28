<?php
require_once __DIR__ . '/../core/Atividade.php';

class MathBuilder extends Atividade {
    public function __construct(array $itens, SessionManager $session) {
        // $itens pode ser usado para armazenar contas pré-definidas, mas aqui deixaremos livre
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
        } // outros operadores podem ser adicionados

        if ($certo) {
            $this->addPoints(15);
            $this->advance();       // passa para a próxima conta (se houver lista)
            $this->session->setFeedback('Great math! +15 points');
        } else {
            $this->session->setFeedback('Oops! Check your calculation.');
        }
    }
}