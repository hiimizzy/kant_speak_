<?php
abstract class Atividade {
    protected string $nome;
    protected SessionManager $session;
    protected array $itens;
    protected string $indexKey;

    public function __construct(string $nome, array $itens, string $indexKey, SessionManager $session) {
        $this->nome = $nome;
        $this->itens = $itens;
        $this->indexKey = $indexKey;
        $this->session = $session;
    }

public function getCurrentIndex(): int {
        return $this->session->get($this->indexKey, 0);
    }

    public function setCurrentIndex(int $index): void {
        $this->session->set($this->indexKey, $index);
    }

    public function getCurrentItem() {
        $idx = $this->getCurrentIndex();
        return $this->itens[$idx] ?? null;
    }

    public function advance(): void {
        $total = count($this->itens);
        $this->setCurrentIndex(($this->getCurrentIndex() + 1) % $total);
    }

    protected function addPoints(int $value): void {
        $this->session->incrementScore($value);
    }

    public function getData(): array {
        return [
            'nome' => $this->nome,
            'item' => $this->getCurrentItem()
        ];
    }

    abstract public function process(array $post): void;
}