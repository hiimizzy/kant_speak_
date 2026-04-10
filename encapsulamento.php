<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Kant Speak - Versão com Encapsulamento e Herança</title>
    <style>
        body { font-family: monospace; background: #f4f4f9; margin: 20px; }
        .log { background: #1e1e2f; color: #d4d4d4; padding: 10px; margin: 5px 0; border-left: 5px solid #ff8c42; white-space: pre-wrap; }
        h2 { color: #2c3e66; }
        pre { background: #fff; padding: 10px; border: 1px solid #ccc; overflow: auto; }
    </style>
</head>
<body>
<h1> Kant Speak - Código com Encapsulamento, Herança e Testes</h1>
<p>✅ Atributos privados, getters/setters, herança testada, reuso de código.</p>

<?php

// 1. CLASSE ABSTRATA (Usuario)

abstract class Usuario {
    private string $nome;
    private int $idade;

    public function __construct(string $nome, int $idade) {
        $this->setNome($nome);
        $this->setIdade($idade);
    }

    // Getters e Setters (encapsulamento)
    public function getNome(): string { return $this->nome; }
    public function setNome(string $nome): void { $this->nome = $nome; }

    public function getIdade(): int { return $this->idade; }
    public function setIdade(int $idade): void { $this->idade = $idade; }

    // Métodos abstratos (serão implementados nas subclasses)
    abstract public function iniciar(): void;
    abstract public function avaliar(): void;
}

// 2. SUBCLASSE CRIANCA

class Crianca extends Usuario {
    private int $id;
    private int $nivel;

    public function __construct(string $nome, int $idade, int $id, int $nivel) {
        parent::__construct($nome, $idade);
        $this->setId($id);
        $this->setNivel($nivel);
    }

    // Encapsulamento
    public function getId(): int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }
    public function getNivel(): int { return $this->nivel; }
    public function setNivel(int $nivel): void { $this->nivel = $nivel; }

    // Métodos específicos
    public function iniciarSessao(): void {
        echo "<div class='log'>[Criança] {$this->getNome()} (ID: {$this->getId()}) iniciou sessão.</div>";
    }

    public function escolherAtividade(Atividade $atividade): void {
        echo "<div class='log'>[Criança] {$this->getNome()} escolheu '{$atividade->getNome()}' (nível {$atividade->getNivelDificuldade()}).</div>";
        $atividade->iniciar();
    }

    // Implementação dos métodos abstratos herdados
    public function iniciar(): void {
        echo "<div class='log'>[Criança] {$this->getNome()} está pronta para aprender!</div>";
    }

    public function avaliar(): void {
        echo "<div class='log'>[Criança] Avaliando {$this->getNome()} - Nível: {$this->getNivel()}</div>";
    }
}

// ------------------------------
// 3. SUBCLASSE RESPONSAVEL
// ------------------------------
class Responsavel extends Usuario {
    private int $id;
    private int $nivel;

    public function __construct(string $nome, int $idade, int $id, int $nivel) {
        parent::__construct($nome, $idade);
        $this->setId($id);
        $this->setNivel($nivel);
    }

    public function getId(): int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }
    public function getNivel(): int { return $this->nivel; }
    public function setNivel(int $nivel): void { $this->nivel = $nivel; }

    public function iniciarSessao(): void {
        echo "<div class='log'>[Responsável] {$this->getNome()} acessou o painel.</div>";
    }

    public function monitorarDesempenho(Crianca $crianca): void {
        echo "<div class='log'>[Responsável] Monitorando {$crianca->getNome()} (nível {$crianca->getNivel()}).</div>";
    }

    public function iniciar(): void {
        echo "<div class='log'>[Responsável] {$this->getNome()} configurando ambiente.</div>";
    }

    public function avaliar(): void {
        echo "<div class='log'>[Responsável] Avaliando relatórios gerais.</div>";
    }
}

// ------------------------------
// 4. CLASSE PAI ATIVIDADE (abstrata)
// ------------------------------
abstract class Atividade {
    private string $nome;
    private int $nivelDificuldade;

    public function __construct(string $nome, int $nivelDificuldade) {
        $this->setNome($nome);
        $this->setNivelDificuldade($nivelDificuldade);
    }

    public function getNome(): string { return $this->nome; }
    public function setNome(string $nome): void { $this->nome = $nome; }
    public function getNivelDificuldade(): int { return $this->nivelDificuldade; }
    public function setNivelDificuldade(int $nivel): void { $this->nivelDificuldade = $nivel; }

    abstract public function iniciar(): void;
    abstract public function avaliar(): void;
}

// ------------------------------
// 5. SUBATIVIDADES
// ------------------------------
class Listen extends Atividade {
    private string $palavra;
    public function __construct(string $nome, int $nivel, string $palavra) {
        parent::__construct($nome, $nivel);
        $this->setPalavra($palavra);
    }
    public function getPalavra(): string { return $this->palavra; }
    public function setPalavra(string $palavra): void { $this->palavra = $palavra; }

    private function ouvirPalavra(): void { echo "<div class='log'>🔊 Listen: ouvindo '{$this->getPalavra()}'</div>"; }
    private function compreenderPalavra(): void { echo "<div class='log'>🧠 Listen: teste de compreensão</div>"; }

    public function iniciar(): void {
        echo "<div class='log'>🎧 --- INICIANDO LISTEN: {$this->getNome()} ---</div>";
        $this->ouvirPalavra();
        $this->compreenderPalavra();
    }
    public function avaliar(): void { echo "<div class='log'>✅ Listen: avaliação de '{$this->getPalavra()}'</div>"; }
}

class Write extends Atividade {
    private string $letra;
    public function __construct(string $nome, int $nivel, string $letra) {
        parent::__construct($nome, $nivel);
        $this->setLetra($letra);
    }
    public function getLetra(): string { return $this->letra; }
    public function setLetra(string $letra): void { $this->letra = $letra; }

    private function digitarLetra(): void { echo "<div class='log'>✍️ Write: desenhar letra '{$this->getLetra()}' no ar (visão computacional)</div>"; }
    private function validarGesto(): void { echo "<div class='log'>🤖 Write: reconhecendo gesto</div>"; }

    public function iniciar(): void {
        echo "<div class='log'>✏️ --- INICIANDO WRITE: {$this->getNome()} ---</div>";
        $this->digitarLetra();
        $this->validarGesto();
    }
    public function avaliar(): void { echo "<div class='log'>✅ Write: avaliação da letra '{$this->getLetra()}'</div>"; }
}

class Speak extends Atividade {
    private string $palavra;
    private string $imagem;
    public function __construct(string $nome, int $nivel, string $palavra, string $imagem) {
        parent::__construct($nome, $nivel);
        $this->setPalavra($palavra);
        $this->setImagem($imagem);
    }
    public function getPalavra(): string { return $this->palavra; }
    public function setPalavra(string $palavra): void { $this->palavra = $palavra; }
    public function getImagem(): string { return $this->imagem; }
    public function setImagem(string $imagem): void { $this->imagem = $imagem; }

    private function gravarAudio(): void { echo "<div class='log'>🎙️ Speak: gravando áudio</div>"; }
    private function exibirImagem(): void { echo "<div class='log'>🖼️ Speak: mostrando {$this->getImagem()}</div>"; }

    public function iniciar(): void {
        echo "<div class='log'>🗣️ --- INICIANDO SPEAK: {$this->getNome()} ---</div>";
        $this->exibirImagem();
        $this->gravarAudio();
        echo "<div class='log'>📢 Aluno diz '{$this->getPalavra()}'</div>";
    }
    public function avaliar(): void { echo "<div class='log'>✅ Speak: avaliação de pronúncia</div>"; }
}

// ------------------------------
// 6. TESTE DE COMPORTAMENTOS HERDADOS E RELAÇÕES
// ------------------------------
function testarHerancaEComportamentos() {
    echo "<h2>🧪 Teste 1: Chamando métodos da classe pai (iniciar/avaliar) via herança</h2>";
    $c = new Crianca("Joana", 8, 102, 2);
    $c->iniciar();      // herdado de Usuario
    $c->avaliar();      // herdado de Usuario

    $r = new Responsavel("Carlos", 40, 202, 1);
    $r->iniciar();
    $r->avaliar();

    echo "<h2>🧪 Teste 2: Polimorfismo (tratar diferentes atividades como Atividade)</h2>";
    $atividades = [
        new Listen("Animal Sounds", 1, "Dog"),
        new Write("Letter C", 1, "C"),
        new Speak("Fruits", 1, "Banana", "🍌 Banana")
    ];
    foreach ($atividades as $atv) {
        echo "<div class='log' style='background:#2a2a3a;'>▶️ Executando: " . $atv->getNome() . "</div>";
        $atv->iniciar();
        $atv->avaliar();
    }

    echo "<h2>🧪 Teste 3: Associação entre Criança e Atividade</h2>";
    $aluno = new Crianca("Rafael", 6, 103, 1);
    $aluno->iniciarSessao();
    $aluno->escolherAtividade(new Write("Letter A", 1, "A"));
}

// Executar os testes
testarHerancaEComportamentos();
?>
</body>
</html>