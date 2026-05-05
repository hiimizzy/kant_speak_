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
    protected function getCurrentIndex(): int {
        return $this->session->get($this->indexKey, 0);
    }
    protected function setCurrentIndex(int $index): void {
        $this->session->set($this->indexKey, $index);
    }
    public function getCurrentItem() {
        return $this->itens[$this->getCurrentIndex()] ?? null;
    }
    public function advance(): void {
        $next = ($this->getCurrentIndex() + 1) % count($this->itens);
        $this->setCurrentIndex($next);
    }
    protected function addPoints(int $value): void {
        $this->session->incrementScore($value);
    }
    abstract public function process(array $post): void;
}