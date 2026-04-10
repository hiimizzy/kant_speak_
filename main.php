<?php
// Classe Abstrata Usuario

abstract class Usuario {
    public string $nome;
    public int $idade;

    public function __construct(string $nome, int $idade) {
        $this->nome = $nome;
        $this->idade = $idade;
    }

    abstract public function iniciar(): void;
    abstract public function avaliar(): void;
}

// Classe Criança

class Crianca extends Usuario {
    public int $id;
    public int $nivel;

    public function __construct(string $nome, int $idade, int $id, int $nivel) {
        parent::__construct($nome, $idade);
        $this->id = $id;
        $this->nivel = $nivel;
    }

    public function iniciarSessao(): void {
        echo "[Criança] {$this->nome} (ID: {$this->id}) iniciou uma nova sessão de aprendizado.\n";
    }

    public function escolherAtividade(Atividade $atividade): void {
        echo "[Criança] {$this->nome} escolheu a atividade '{$atividade->nome}' (nível {$atividade->nivelDificuldade}).\n";
        $atividade->iniciar();
    }

    public function iniciar(): void {
        echo "[Criança] {$this->nome} está pronta para aprender!\n";
    }

    public function avaliar(): void {
        echo "[Criança] Avaliando o progresso de {$this->nome} - Nível atual: {$this->nivel}\n";
    }
}

// Classe Responsável

class Responsavel extends Usuario {
    public int $id;
    public int $nivel;

    public function __construct(string $nome, int $idade, int $id, int $nivel) {
        parent::__construct($nome, $idade);
        $this->id = $id;
        $this->nivel = $nivel;
    }

    public function iniciarSessao(): void {
        echo "[Responsável] {$this->nome} (ID: {$this->id}) acessou o painel de controle.\n";
    }

    public function escolherAtividade(Atividade $atividade): void {
        echo "[Responsável] {$this->nome} selecionou a atividade '{$atividade->nome}' para a criança.\n";
    }

    public function monitorarDesempenho(Crianca $crianca): void {
        echo "[Responsável] Monitorando desempenho de {$crianca->nome} (nível {$crianca->nivel}).\n";
    }

    public function iniciar(): void {
        echo "[Responsável] {$this->nome} está configurando o ambiente de aprendizado.\n";
    }

    public function avaliar(): void {
        echo "[Responsável] Avaliando relatórios gerais do sistema.\n";
    }
}

// Classe Abstrata Atividade

abstract class Atividade {
    public string $nome;
    public int $nivelDificuldade;

    public function __construct(string $nome, int $nivelDificuldade) {
        $this->nome = $nome;
        $this->nivelDificuldade = $nivelDificuldade;
    }

    abstract public function iniciar(): void;
    abstract public function avaliar(): void;
}

// Subclasse Listen

class Listen extends Atividade {
    public string $palavra;

    public function __construct(string $nome, int $nivelDificuldade, string $palavra) {
        parent::__construct($nome, $nivelDificuldade);
        $this->palavra = $palavra;
    }

    public function ouvirPalavra(): void {
        echo "[Listen] Reproduzindo áudio da palavra: '{$this->palavra}' (pronúncia correta).\n";
    }

    public function compreenderPalavra(): void {
        echo "[Listen] Aluno deve identificar a palavra entre opções. Compreensão testada.\n";
    }

    public function iniciar(): void {
        echo "\n--- Iniciando Atividade Listen: {$this->nome} (nível {$this->nivelDificuldade}) ---\n";
        $this->ouvirPalavra();
        $this->compreenderPalavra();
    }

    public function avaliar(): void {
        echo "[Listen] Avaliando acertos na compreensão auditiva da palavra '{$this->palavra}'.\n";
    }
}

// Subclasse Write

class Write extends Atividade {
    public string $letra;

    public function __construct(string $nome, int $nivelDificuldade, string $letra) {
        parent::__construct($nome, $nivelDificuldade);
        $this->letra = $letra;
    }

    public function digitarPalavra(): void {
        echo "[Write] Letra tracejada aparece na tela: '{$this->letra}'. Aluno desenha a letra no ar (visão computacional).\n";
    }

    public function validarPalavra(): void {
        echo "[Write] Sistema reconhece o gesto e valida se a letra foi desenhada corretamente.\n";
    }

    public function iniciar(): void {
        echo "\n--- Iniciando Atividade Write: {$this->nome} (nível {$this->nivelDificuldade}) ---\n";
        $this->digitarPalavra();
        $this->validarPalavra();
    }

    public function avaliar(): void {
        echo "[Write] Avaliando precisão do traçado da letra '{$this->letra}'.\n";
    }
}

// Subclasse Speak

class Speak extends Atividade {
    public string $palavra;
    public string $imagem;

    public function __construct(string $nome, int $nivelDificuldade, string $palavra, string $imagem) {
        parent::__construct($nome, $nivelDificuldade);
        $this->palavra = $palavra;
        $this->imagem = $imagem;
    }

    public function gravarAudio(): void {
        echo "[Speak] Iniciando gravação de áudio do aluno.\n";
    }

    public function capturarFala(): void {
        echo "[Speak] Aluno vê o desenho de {$this->imagem} e diz a palavra '{$this->palavra}'.\n";
    }

    public function avaliarFala(): void {
        echo "[Speak] Sistema compara a pronúncia com o modelo e gera feedback.\n";
    }

    public function iniciar(): void {
        echo "\n--- Iniciando Atividade Speak: {$this->nome} (nível {$this->nivelDificuldade}) ---\n";
        $this->gravarAudio();
        $this->capturarFala();
        $this->avaliarFala();
    }

    public function avaliar(): void {
        echo "[Speak] Avaliando fluência e precisão da fala para '{$this->palavra}'.\n";
    }
}


// Demonstração: Criando objetos e executando funcionalidades

echo "===== Sistema Kant Speak - Demonstração =====\n";

// 1. Criar instâncias de usuários
$crianca = new Crianca("Lucas", 7, 101, 1);
$responsavel = new Responsavel("Ana", 35, 201, 2);

// 2. Criar instâncias de atividades
$listenAtv = new Listen("Alphabet Listen", 1, "Apple");
$writeAtv = new Write("Tracing Letter A", 1, "A");
$speakAtv = new Speak("Apple Speak", 1, "Apple", "Maçã");

// 3. Usar métodos das classes
echo "\n--- Ações da Criança ---\n";
$crianca->iniciar();
$crianca->iniciarSessao();
$crianca->escolherAtividade($writeAtv);   // Atividade de escrever letra tracejada
$crianca->avaliar();

echo "\n--- Ações do Responsável ---\n";
$responsavel->iniciar();
$responsavel->iniciarSessao();
$responsavel->escolherAtividade($listenAtv);
$responsavel->monitorarDesempenho($crianca);
$responsavel->avaliar();

echo "\n--- Execução direta das atividades ---\n";
$listenAtv->iniciar();
$listenAtv->avaliar();

$writeAtv->iniciar();
$writeAtv->avaliar();

$speakAtv->iniciar();
$speakAtv->avaliar();

echo "\n=== Fim da demonstração ===\n";

?>