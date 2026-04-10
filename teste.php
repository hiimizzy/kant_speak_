<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kant Speak - Demonstração</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f7ff;
            margin: 20px;
            padding: 0;
            color: #1e2a3e;
        }
        .container {
            max-width: 1000px;
            margin: auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            padding: 20px;
        }
        h1 {
            background: #2c3e66;
            color: white;
            padding: 20px;
            margin: -20px -20px 20px -20px;
            text-align: center;
        }
        h2 {
            border-left: 5px solid #ff8c42;
            padding-left: 15px;
            margin-top: 30px;
            color: #2c3e66;
        }
        .card {
            background: #f9f9fc;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #ff8c42;
            font-family: monospace;
            white-space: pre-wrap;
            font-size: 14px;
            line-height: 1.5;
        }
        .log {
            background: #1e2a3e;
            color: #c9d1d9;
            padding: 15px;
            border-radius: 12px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            overflow-x: auto;
        }
        footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>🐻 Kant Speak - Sistema de Aprendizado de Inglês</h1>
    <p>Demonstração das classes com atividades de <strong>Listen</strong>, <strong>Write</strong> (visão computacional) e <strong>Speak</strong>.</p>

    <?php
    // ============================================
    // DEFINIÇÃO DAS CLASSES (igual ao código anterior)
    // ============================================

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

    class Crianca extends Usuario {
        public int $id;
        public int $nivel;
        public function __construct(string $nome, int $idade, int $id, int $nivel) {
            parent::__construct($nome, $idade);
            $this->id = $id;
            $this->nivel = $nivel;
        }
        public function iniciarSessao(): void {
            echo "<div class='log'>[Criança] {$this->nome} (ID: {$this->id}) iniciou uma nova sessão de aprendizado.</div>";
        }
        public function escolherAtividade(Atividade $atividade): void {
            echo "<div class='log'>[Criança] {$this->nome} escolheu a atividade '{$atividade->nome}' (nível {$atividade->nivelDificuldade}).</div>";
            $atividade->iniciar();
        }
        public function iniciar(): void {
            echo "<div class='log'>[Criança] {$this->nome} está pronta para aprender!</div>";
        }
        public function avaliar(): void {
            echo "<div class='log'>[Criança] Avaliando o progresso de {$this->nome} - Nível atual: {$this->nivel}</div>";
        }
    }

    class Responsavel extends Usuario {
        public int $id;
        public int $nivel;
        public function __construct(string $nome, int $idade, int $id, int $nivel) {
            parent::__construct($nome, $idade);
            $this->id = $id;
            $this->nivel = $nivel;
        }
        public function iniciarSessao(): void {
            echo "<div class='log'>[Responsável] {$this->nome} (ID: {$this->id}) acessou o painel de controle.</div>";
        }
        public function escolherAtividade(Atividade $atividade): void {
            echo "<div class='log'>[Responsável] {$this->nome} selecionou a atividade '{$atividade->nome}' para a criança.</div>";
        }
        public function monitorarDesempenho(Crianca $crianca): void {
            echo "<div class='log'>[Responsável] Monitorando desempenho de {$crianca->nome} (nível {$crianca->nivel}).</div>";
        }
        public function iniciar(): void {
            echo "<div class='log'>[Responsável] {$this->nome} está configurando o ambiente de aprendizado.</div>";
        }
        public function avaliar(): void {
            echo "<div class='log'>[Responsável] Avaliando relatórios gerais do sistema.</div>";
        }
    }

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

    class Listen extends Atividade {
        public string $palavra;
        public function __construct(string $nome, int $nivelDificuldade, string $palavra) {
            parent::__construct($nome, $nivelDificuldade);
            $this->palavra = $palavra;
        }
        public function ouvirPalavra(): void {
            echo "<div class='log'>🔊 [Listen] Reproduzindo áudio da palavra: '<strong>{$this->palavra}</strong>' (pronúncia correta).</div>";
        }
        public function compreenderPalavra(): void {
            echo "<div class='log'>🧠 [Listen] Aluno deve identificar a palavra entre opções. Compreensão testada.</div>";
        }
        public function iniciar(): void {
            echo "<div class='log' style='background:#eef2ff;'>🎧 --- Iniciando Atividade Listen: {$this->nome} (nível {$this->nivelDificuldade}) ---</div>";
            $this->ouvirPalavra();
            $this->compreenderPalavra();
        }
        public function avaliar(): void {
            echo "<div class='log'>✅ [Listen] Avaliando acertos na compreensão auditiva da palavra '{$this->palavra}'.</div>";
        }
    }

    class Write extends Atividade {
        public string $letra;
        public function __construct(string $nome, int $nivelDificuldade, string $letra) {
            parent::__construct($nome, $nivelDificuldade);
            $this->letra = $letra;
        }
        public function digitarPalavra(): void {
            echo "<div class='log'>✍️ [Write] Letra tracejada aparece na tela: '<strong>{$this->letra}</strong>'. Aluno desenha a letra no ar (visão computacional).</div>";
        }
        public function validarPalavra(): void {
            echo "<div class='log'>🤖 [Write] Sistema reconhece o gesto e valida se a letra foi desenhada corretamente.</div>";
        }
        public function iniciar(): void {
            echo "<div class='log' style='background:#eef2ff;'>✏️ --- Iniciando Atividade Write: {$this->nome} (nível {$this->nivelDificuldade}) ---</div>";
            $this->digitarPalavra();
            $this->validarPalavra();
        }
        public function avaliar(): void {
            echo "<div class='log'>✅ [Write] Avaliando precisão do traçado da letra '{$this->letra}'.</div>";
        }
    }

    class Speak extends Atividade {
        public string $palavra;
        public string $imagem;
        public function __construct(string $nome, int $nivelDificuldade, string $palavra, string $imagem) {
            parent::__construct($nome, $nivelDificuldade);
            $this->palavra = $palavra;
            $this->imagem = $imagem;
        }
        public function gravarAudio(): void {
            echo "<div class='log'>🎙️ [Speak] Iniciando gravação de áudio do aluno.</div>";
        }
        public function capturarFala(): void {
            echo "<div class='log'>🖼️ [Speak] Aluno vê o desenho de <strong>{$this->imagem}</strong> e diz a palavra '<strong>{$this->palavra}</strong>'.</div>";
        }
        public function avaliarFala(): void {
            echo "<div class='log'>📊 [Speak] Sistema compara a pronúncia com o modelo e gera feedback.</div>";
        }
        public function iniciar(): void {
            echo "<div class='log' style='background:#eef2ff;'>🗣️ --- Iniciando Atividade Speak: {$this->nome} (nível {$this->nivelDificuldade}) ---</div>";
            $this->gravarAudio();
            $this->capturarFala();
            $this->avaliarFala();
        }
        public function avaliar(): void {
            echo "<div class='log'>✅ [Speak] Avaliando fluência e precisão da fala para '{$this->palavra}'.</div>";
        }
    }

    // ============================================
    // DEMONSTRAÇÃO (CRIAÇÃO DE OBJETOS E EXECUÇÃO)
    // ============================================
    echo "<h2>👩‍🎓 Ações da Criança</h2>";
    $crianca = new Crianca("Lucas", 7, 101, 1);
    $crianca->iniciar();
    $crianca->iniciarSessao();
    $crianca->escolherAtividade(new Write("Tracing Letter A", 1, "A"));
    $crianca->avaliar();

    echo "<h2>👨‍👩‍👦 Ações do Responsável</h2>";
    $responsavel = new Responsavel("Ana", 35, 201, 2);
    $responsavel->iniciar();
    $responsavel->iniciarSessao();
    $responsavel->escolherAtividade(new Listen("Alphabet Listen", 1, "Apple"));
    $responsavel->monitorarDesempenho($crianca);
    $responsavel->avaliar();

    echo "<h2>📚 Execução direta das atividades</h2>";
    $listen = new Listen("Fruit Listen", 1, "Orange");
    $listen->iniciar();
    $listen->avaliar();

    $write = new Write("Letter B", 2, "B");
    $write->iniciar();
    $write->avaliar();

    $speak = new Speak("Animal Speak", 1, "Cat", "🐱 Gato");
    $speak->iniciar();
    $speak->avaliar();
    ?>

    <footer>
        <p>✅ Sistema Kant Speak - Demonstração funcional das classes com suporte a HTML/CSS.</p>
        <p>🧪 As mensagens simulam a integração com visão computacional (Write) e reconhecimento de fala (Speak).</p>
    </footer>
</div>
</body>
</html>

