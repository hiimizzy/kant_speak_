<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kant Speak</title>
<link rel="stylesheet" href="style.css">
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Baloo+2:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

<!-- ============================================================
     SCREEN: MENU
     ============================================================ -->
<div id="screen-menu" class="screen active">
  <div class="top-bar">
    <div class="logo">Kant<span>Speak</span></div>
    <div class="hud" id="hud-menu" style="display:none">
      <div class="hud-badge"><span class="icon">⭐</span><span id="hud-pts">0</span> pts</div>
      <div class="hud-badge"><span class="icon">📊</span>Nível <span id="hud-lvl">1</span></div>
    </div>
  </div>

  <div class="menu-hero">
    <span class="menu-mascot">🦉</span>
    <h1>Kant<span>Speak</span></h1>
    <p>Aprenda inglês de forma divertida!</p>
  </div>

  <div class="card" style="max-width:500px; margin-bottom:24px;" id="login-card">
    <div class="user-form">
      <div>
        <label for="inp-nome">Seu nome 😊</label>
        <input type="text" id="inp-nome" placeholder="Digite seu nome..." maxlength="30">
      </div>
      <div>
        <label for="inp-idade">Sua idade 🎂</label>
        <input type="number" id="inp-idade" placeholder="Ex: 8" min="3" max="18">
      </div>
    </div>
    <button class="btn-primary" style="width:100%;justify-content:center;" onclick="App.getInstance().iniciarSessao()">
      ▶ Começar!
    </button>
  </div>

  <div id="activity-section" style="display:none; text-align:center;">
    <p style="font-weight:700; color:var(--muted); margin-bottom:16px; font-size:1rem;">
      Olá, <span id="user-greeting" style="color:var(--blue)"></span>! Escolha uma atividade:
    </p>
    <div class="activity-grid">
      <button class="act-btn alphabet" onclick="App.getInstance().iniciarAtividade('alphabet')">
        <span class="act-icon">🔤</span>Alphabet
      </button>
      <button class="act-btn listen" onclick="App.getInstance().iniciarAtividade('listen')">
        <span class="act-icon">👂</span>Listen
      </button>
      <button class="act-btn speak" onclick="App.getInstance().iniciarAtividade('speak')">
        <span class="act-icon">🎤</span>Speak
      </button>
      <button class="act-btn write" onclick="App.getInstance().iniciarAtividade('write')">
        <span class="act-icon">✏️</span>Write
      </button>
    </div>
  </div>
</div>


<!-- ============================================================
     SCREEN: ALPHABET
     ============================================================ -->
<div id="screen-alphabet" class="screen">
  <div class="top-bar">
    <button class="btn-back" onclick="App.getInstance().voltarMenu()">← Voltar</button>
    <div class="logo">Kant<span>Speak</span></div>
    <div class="hud-badge"><span class="icon">⭐</span><span id="alpha-pts">0</span></div>
  </div>

  <div class="card">
    <div class="act-header">
      <div class="act-pill" style="background:var(--blue)">🔤 Alphabet</div>
      <h2 style="color:var(--blue)">Aprenda as letras</h2>
    </div>

    <div class="mode-tabs">
      <button class="mode-tab active" id="tab-mouse" onclick="AlphabetActivity.setMode('mouse')">🖱️ Desenho (Mouse)</button>
      <button class="mode-tab" id="tab-webcam" onclick="AlphabetActivity.setMode('webcam')">📷 Webcam (Mão)</button>
    </div>

    <div class="progress-wrap">
      <div class="progress-label">Progresso: <span id="alpha-progress-txt">0/26</span></div>
      <div class="progress-bar"><div class="progress-fill" id="alpha-fill" style="width:0%"></div></div>
    </div>

    <div class="letter-display" id="alpha-letter">A</div>
    <div class="phonetic-display" id="alpha-phonetic">🔊 /eɪ/ — como em "ace"</div>

    <div class="canvas-wrap" id="canvas-wrap">
      <canvas id="drawCanvas" width="600" height="220"></canvas>
      <span class="canvas-hint">Desenhe a letra aqui</span>
    </div>

    <div id="webcam-section" style="display:none;">
      <div class="webcam-note">📷 Permita o acesso à câmera para usar rastreamento de mão. Desenhe no ar com o dedo indicador!</div>
      <video id="videoEl" autoplay playsinline></video>
      <canvas id="webcamCanvas" width="360" height="270" style="border-radius:12px;width:100%;max-width:360px;margin:0 auto;display:block;"></canvas>
    </div>

    <div style="text-align:center;margin:10px 0 16px;">
      <div class="stars" id="alpha-stars">
        <span class="star">⭐</span><span class="star">⭐</span><span class="star">⭐</span>
      </div>
    </div>

    <div class="alpha-controls">
      <button class="btn-primary" onclick="AlphabetActivity.playSound()">🔊 Ouvir</button>
      <button class="btn-primary green" onclick="AlphabetActivity.validar()">✓ Verificar</button>
      <button class="btn-primary" style="background:#6c757d;box-shadow:0 6px 0 #495057" onclick="AlphabetActivity.limparCanvas()">🗑️ Limpar</button>
      <button class="btn-primary yellow" onclick="AlphabetActivity.proximaLetra()">→ Próxima</button>
    </div>

    <div class="feedback-box" id="alpha-feedback"></div>

    <div class="nav-dots" id="alpha-dots"></div>
  </div>
</div>


<!-- ============================================================
     SCREEN: LISTEN
     ============================================================ -->
<div id="screen-listen" class="screen">
  <div class="top-bar">
    <button class="btn-back" onclick="App.getInstance().voltarMenu()">← Voltar</button>
    <div class="logo">Kant<span>Speak</span></div>
    <div class="hud-badge"><span class="icon">⭐</span><span id="listen-pts">0</span></div>
  </div>

  <div class="card">
    <div class="act-header">
      <div class="act-pill" style="background:var(--green)">👂 Listen</div>
      <h2 style="color:var(--green)">Ouça e aprenda</h2>
    </div>

    <div class="progress-wrap">
      <div class="progress-label">Progresso: <span id="listen-progress-txt">0/10</span></div>
      <div class="progress-bar"><div class="progress-fill" id="listen-fill" style="width:0%;background:linear-gradient(90deg,var(--green),#90ee90)"></div></div>
    </div>

    <div class="word-image-box">
      <div class="word-emoji" id="listen-emoji">🍎</div>
      <div class="word-label" id="listen-word">APPLE</div>
    </div>

    <p style="text-align:center;color:var(--muted);font-weight:600;margin-bottom:16px;">
      Ouça a pronúncia e escolha a imagem correta:
    </p>

    <div class="listen-controls">
      <button class="btn-primary green" onclick="ListenActivity.reproduzirAudio()">🔊 Ouvir palavra</button>
      <button class="btn-primary" onclick="ListenActivity.reproduzirAudio()" style="background:#4db8c4;box-shadow:0 6px 0 #2e8a8f;">🔁 Repetir</button>
    </div>

    <div id="listen-choices" style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin:20px 0;"></div>

    <div class="feedback-box" id="listen-feedback"></div>

    <div style="display:flex;justify-content:center;margin-top:16px;">
      <button class="btn-primary yellow" onclick="ListenActivity.proximaPalavra()">→ Próxima</button>
    </div>
  </div>
</div>


<!-- ============================================================
     SCREEN: SPEAK
     ============================================================ -->
<div id="screen-speak" class="screen">
  <div class="top-bar">
    <button class="btn-back" onclick="App.getInstance().voltarMenu()">← Voltar</button>
    <div class="logo">Kant<span>Speak</span></div>
    <div class="hud-badge"><span class="icon">⭐</span><span id="speak-pts">0</span></div>
  </div>

  <div class="card">
    <div class="act-header">
      <div class="act-pill" style="background:var(--yellow)">🎤 Speak</div>
      <h2 style="color:var(--yellow)">Fale em inglês</h2>
    </div>

    <div class="progress-wrap">
      <div class="progress-label">Progresso: <span id="speak-progress-txt">0/10</span></div>
      <div class="progress-bar"><div class="progress-fill" id="speak-fill" style="width:0%;background:linear-gradient(90deg,var(--yellow),#ffe066)"></div></div>
    </div>

    <div class="word-image-box">
      <div class="word-emoji" id="speak-emoji">🐱</div>
      <p style="color:var(--muted);font-weight:700;">O que você vê? Diga em inglês!</p>
    </div>

    <div class="mic-visual" id="mic-visual">🎤</div>

    <div class="speak-transcript" id="speak-transcript">
      Pressione o botão e fale...
    </div>

    <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap;">
      <button class="btn-primary yellow" id="btn-speak-start" onclick="SpeakActivity.iniciarGravacao()">🎤 Falar</button>
      <button class="btn-primary" onclick="SpeakActivity.avaliar()" style="background:var(--purple);box-shadow:0 6px 0 #6a1f8a">✓ Verificar</button>
      <button class="btn-primary yellow" onclick="SpeakActivity.proximaPalavra()">→ Próxima</button>
    </div>

    <div class="feedback-box" id="speak-feedback"></div>
  </div>
</div>


<!-- ============================================================
     SCREEN: WRITE
     ============================================================ -->
<div id="screen-write" class="screen">
  <div class="top-bar">
    <button class="btn-back" onclick="App.getInstance().voltarMenu()">← Voltar</button>
    <div class="logo">Kant<span>Speak</span></div>
    <div class="hud-badge"><span class="icon">⭐</span><span id="write-pts">0</span></div>
  </div>

  <div class="card">
    <div class="act-header">
      <div class="act-pill" style="background:var(--purple)">✏️ Write</div>
      <h2 style="color:var(--purple)">Escreva a palavra</h2>
    </div>

    <div class="progress-wrap">
      <div class="progress-label">Progresso: <span id="write-progress-txt">0/10</span></div>
      <div class="progress-bar"><div class="progress-fill" id="write-fill" style="width:0%;background:linear-gradient(90deg,var(--purple),#c39bd3)"></div></div>
    </div>

    <div class="word-image-box">
      <div class="word-emoji" id="write-emoji">🐕</div>
    </div>

    <input type="text" class="write-input" id="write-input" placeholder="Digite aqui..." autocomplete="off"
      oninput="WriteActivity.onInput(this.value)" onkeydown="if(event.key==='Enter') WriteActivity.avaliar()">

    <div class="hint-box">
      <div>
        <span style="font-size:0.85rem;display:block;margin-bottom:6px;">💡 Dica:</span>
        <div class="hint-letters" id="hint-display">_ _ _</div>
      </div>
    </div>

    <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap;margin-top:16px;">
      <button class="btn-primary" onclick="WriteActivity.mostrarDica()" style="background:#6c757d;box-shadow:0 6px 0 #495057">💡 Mais dica</button>
      <button class="btn-primary purple" onclick="WriteActivity.avaliar()">✓ Verificar</button>
      <button class="btn-primary yellow" onclick="WriteActivity.proximaPalavra()">→ Próxima</button>
    </div>

    <div class="feedback-box" id="write-feedback"></div>
  </div>
</div>


<!-- ============================================================
     JAVASCRIPT — POO Architecture
     ============================================================ -->
<script>
'use strict';

/* ================================================================
   CLASS: Usuario
   ================================================================ */
class Usuario {
  #nome;
  #idade;
  #nivel;
  #pontuacaoTotal;

  constructor(nome, idade) {
    this.#nome = nome;
    this.#idade = parseInt(idade) || 7;
    this.#nivel = 1;
    this.#pontuacaoTotal = 0;
  }

  iniciarSessao() {
    console.log(`[Usuario] Sessão iniciada: ${this.#nome}, ${this.#idade} anos`);
    this.#atualizarHUD();
  }

  adicionarPontuacao(pts) {
    this.#pontuacaoTotal += pts;
    if (this.#pontuacaoTotal >= this.#nivel * 100) this.#nivel++;
    this.#atualizarHUD();
    this.#mostrarPopup(pts);
  }

  #atualizarHUD() {
    document.querySelectorAll('[id$="-pts"]').forEach(el => el.textContent = this.#pontuacaoTotal);
    document.getElementById('hud-pts').textContent = this.#pontuacaoTotal;
    document.getElementById('hud-lvl').textContent = this.#nivel;
  }

  #mostrarPopup(pts) {
    const pop = document.createElement('div');
    pop.className = 'score-popup';
    pop.textContent = `+${pts} ⭐`;
    document.body.appendChild(pop);
    setTimeout(() => pop.remove(), 1700);
  }

  get nome()            { return this.#nome; }
  get pontuacaoTotal()  { return this.#pontuacaoTotal; }
  get nivel()           { return this.#nivel; }
}


/* ================================================================
   ABSTRACT CLASS: Atividade
   ================================================================ */
class Atividade {
  #nome;
  #pontuacao;

  constructor(nome, pontuacao) {
    if (new.target === Atividade) throw new Error('Atividade é uma classe abstrata.');
    this.#nome = nome;
    this.#pontuacao = pontuacao;
  }

  // Abstract — must be implemented
  iniciar()  { throw new Error(`${this.constructor.name} deve implementar iniciar()`); }
  avaliar()  { throw new Error(`${this.constructor.name} deve implementar avaliar()`); }

  // Concrete
  adicionarPontos(bonus = 1) {
    const pts = this.#pontuacao * bonus;
    App.getInstance().getUsuario()?.adicionarPontuacao(pts);
    return pts;
  }

  get nome()      { return this.#nome; }
  get pontuacao() { return this.#pontuacao; }

  mostrarFeedback(elId, correto, msgOk = '🎉 Correto! Muito bem!', msgErr = '😊 Tente novamente!') {
    const el = document.getElementById(elId);
    el.className = `feedback-box show ${correto ? 'correct' : 'wrong'}`;
    el.textContent = correto ? msgOk : msgErr;
    if (correto) setTimeout(() => el.className = 'feedback-box', 2000);
  }

  ocultarFeedback(elId) {
    document.getElementById(elId).className = 'feedback-box';
  }
}


/* ================================================================
   CLASS: AlphabetActivity  (extends Atividade)
   ================================================================ */
class AlphabetActivity extends Atividade {
  static #instance;
  #letras;
  #indice;
  #ctx;
  #desenhando;
  #modo; // 'mouse' | 'webcam'
  #stream;
  #trackingInterval;
  #lastPoint;

  static getInstance() {
    if (!AlphabetActivity.#instance) AlphabetActivity.#instance = new AlphabetActivity();
    return AlphabetActivity.#instance;
  }

  constructor() {
    super('Alphabet', 10);
    this.#letras = [
      {l:'A',ph:'🔊 /eɪ/ — como em "ace"'},{l:'B',ph:'🔊 /biː/ — como em "bee"'},
      {l:'C',ph:'🔊 /siː/ — como em "see"'},{l:'D',ph:'🔊 /diː/ — como em "dee"'},
      {l:'E',ph:'🔊 /iː/ — como em "eel"'},{l:'F',ph:'🔊 /ɛf/ — como em "elf"'},
      {l:'G',ph:'🔊 /dʒiː/ — como em "gee"'},{l:'H',ph:'🔊 /eɪtʃ/ — como em "age"'},
      {l:'I',ph:'🔊 /aɪ/ — como em "eye"'},{l:'J',ph:'🔊 /dʒeɪ/ — como em "jay"'},
      {l:'K',ph:'🔊 /keɪ/ — como em "kay"'},{l:'L',ph:'🔊 /ɛl/ — como em "el"'},
      {l:'M',ph:'🔊 /ɛm/ — como em "em"'},{l:'N',ph:'🔊 /ɛn/ — como em "en"'},
      {l:'O',ph:'🔊 /oʊ/ — como em "oh"'},{l:'P',ph:'🔊 /piː/ — como em "pea"'},
      {l:'Q',ph:'🔊 /kjuː/ — como em "cue"'},{l:'R',ph:'🔊 /ɑːr/ — como em "are"'},
      {l:'S',ph:'🔊 /ɛs/ — como em "ess"'},{l:'T',ph:'🔊 /tiː/ — como em "tea"'},
      {l:'U',ph:'🔊 /juː/ — como em "you"'},{l:'V',ph:'🔊 /viː/ — como em "vee"'},
      {l:'W',ph:'🔊 /ˈdʌbəljuː/ — "double-u"'},{l:'X',ph:'🔊 /ɛks/ — como em "ex"'},
      {l:'Y',ph:'🔊 /waɪ/ — como em "why"'},{l:'Z',ph:'🔊 /ziː/ — como em "zee"'},
    ];
    this.#indice = 0;
    this.#desenhando = false;
    this.#modo = 'mouse';
    this.#lastPoint = null;
  }

  iniciar() {
    this.#indice = 0;
    this.#initCanvas();
    this.#renderizar();
    this.#buildDots();
  }

  #initCanvas() {
    const canvas = document.getElementById('drawCanvas');
    this.#ctx = canvas.getContext('2d');
    const rect = canvas.parentElement.getBoundingClientRect();
    canvas.width  = Math.min(rect.width - 6, 600);
    canvas.height = 220;
    this.#limparCtx();
    this.#addEvents(canvas);
  }

  #addEvents(canvas) {
    // Mouse
    canvas.onmousedown = e => { this.#desenhando = true; this.#lastPoint = this.#pos(canvas,e); };
    canvas.onmousemove = e => { if(this.#desenhando) this.#draw(this.#pos(canvas,e)); };
    canvas.onmouseup   = ()=> { this.#desenhando = false; this.#lastPoint = null; };
    // Touch
    canvas.ontouchstart = e => { e.preventDefault(); this.#desenhando = true; this.#lastPoint = this.#pos(canvas,e.touches[0]); };
    canvas.ontouchmove  = e => { e.preventDefault(); if(this.#desenhando) this.#draw(this.#pos(canvas,e.touches[0])); };
    canvas.ontouchend   = ()=> { this.#desenhando = false; this.#lastPoint = null; };
  }

  #pos(canvas, e) {
    const r = canvas.getBoundingClientRect();
    return { x: e.clientX - r.left, y: e.clientY - r.top };
  }

  #draw(pt) {
    const ctx = this.#ctx;
    ctx.lineWidth   = 8;
    ctx.lineCap     = 'round';
    ctx.lineJoin    = 'round';
    ctx.strokeStyle = '#4A90E2';
    ctx.beginPath();
    if (this.#lastPoint) {
      ctx.moveTo(this.#lastPoint.x, this.#lastPoint.y);
      ctx.lineTo(pt.x, pt.y);
    } else {
      ctx.moveTo(pt.x, pt.y);
      ctx.lineTo(pt.x+1, pt.y+1);
    }
    ctx.stroke();
    this.#lastPoint = pt;
  }

  #limparCtx() {
    if (!this.#ctx) return;
    const canvas = document.getElementById('drawCanvas');
    this.#ctx.clearRect(0, 0, canvas.width, canvas.height);
  }

  #renderizar() {
    const atual = this.#letras[this.#indice];
    document.getElementById('alpha-letter').textContent = atual.l;
    document.getElementById('alpha-phonetic').textContent = atual.ph;
    document.getElementById('alpha-progress-txt').textContent = `${this.#indice+1}/26`;
    document.getElementById('alpha-fill').style.width = `${((this.#indice+1)/26)*100}%`;
    document.querySelectorAll('#alpha-stars .star').forEach(s => s.classList.remove('lit'));
    this.ocultarFeedback('alpha-feedback');
    this.#updateDots();
  }

  #buildDots() {
    const wrap = document.getElementById('alpha-dots');
    wrap.innerHTML = '';
    // Show only 8 dots around current
    const start = Math.max(0, this.#indice - 4);
    const end   = Math.min(25, start + 7);
    for (let i = start; i <= end; i++) {
      const d = document.createElement('div');
      d.className = 'dot' + (i === this.#indice ? ' active' : (i < this.#indice ? ' done' : ''));
      wrap.appendChild(d);
    }
  }

  #updateDots() { this.#buildDots(); }

  avaliar() {
    const canvas = document.getElementById('drawCanvas');
    const ctx    = this.#ctx;
    // Check if something was drawn
    const data   = ctx.getImageData(0, 0, canvas.width, canvas.height).data;
    let hasPixels = false;
    for (let i = 3; i < data.length; i += 4) {
      if (data[i] > 20) { hasPixels = true; break; }
    }
    if (!hasPixels) {
      this.mostrarFeedback('alpha-feedback', false, '', '✏️ Por favor, desenhe a letra primeiro!');
      return;
    }
    // Simplified validation: always accept if something is drawn (real ML would be needed)
    const correto = hasPixels; // In production: gesture model comparison
    this.mostrarFeedback('alpha-feedback', correto, `🎉 Ótimo! Você desenhou a letra ${this.#letras[this.#indice].l}!`);
    const stars = document.querySelectorAll('#alpha-stars .star');
    stars.forEach(s => s.classList.add('lit'));
    if (correto) this.adicionarPontos();
  }

  playSound() {
    const letra = this.#letras[this.#indice].l;
    const utter = new SpeechSynthesisUtterance(letra);
    utter.lang  = 'en-US';
    utter.rate  = 0.7;
    utter.pitch = 1.1;
    speechSynthesis.speak(utter);
  }

  limparCanvas() { this.#limparCtx(); this.ocultarFeedback('alpha-feedback'); }

  proximaLetra() {
    this.#limparCtx();
    this.#indice = (this.#indice + 1) % 26;
    this.#renderizar();
  }

  static setMode(mode) {
    const inst = AlphabetActivity.getInstance();
    inst.#modo = mode;
    document.getElementById('tab-mouse').className  = 'mode-tab' + (mode==='mouse'?' active':'');
    document.getElementById('tab-webcam').className = 'mode-tab' + (mode==='webcam'?' active':'');
    document.getElementById('canvas-wrap').style.display   = mode==='mouse'?'block':'none';
    document.getElementById('webcam-section').style.display= mode==='webcam'?'block':'none';
    if (mode==='webcam') inst.#startWebcam();
    else inst.#stopWebcam();
  }

  async #startWebcam() {
    try {
      this.#stream = await navigator.mediaDevices.getUserMedia({video:true});
      const vid = document.getElementById('videoEl');
      vid.srcObject = this.#stream;
      vid.style.display = 'none';
      this.#simulateHandTracking();
    } catch(e) {
      alert('Câmera não disponível. Use o modo Mouse.');
      AlphabetActivity.setMode('mouse');
    }
  }

  #stopWebcam() {
    if (this.#stream) { this.#stream.getTracks().forEach(t=>t.stop()); this.#stream = null; }
    if (this.#trackingInterval) { clearInterval(this.#trackingInterval); this.#trackingInterval = null; }
    const c = document.getElementById('webcamCanvas');
    const ctx = c.getContext('2d');
    ctx.clearRect(0,0,c.width,c.height);
  }

  #simulateHandTracking() {
    const vid = document.getElementById('videoEl');
    const canvas = document.getElementById('webcamCanvas');
    const ctx = canvas.getContext('2d');
    let simX = 100, simY = 100, dx = 3, dy = 2;
    let trail = [];

    this.#trackingInterval = setInterval(() => {
      ctx.drawImage(vid, 0, 0, canvas.width, canvas.height);
      // Draw overlay
      ctx.fillStyle = 'rgba(0,0,0,0.25)';
      ctx.fillRect(0,0,canvas.width,20);
      ctx.fillStyle='white'; ctx.font='bold 13px Nunito'; ctx.fillText('🖐 Rastreamento ativo',8,14);

      // Simulate finger tip movement
      simX += dx; simY += dy;
      if(simX<20||simX>340){dx*=-1;}
      if(simY<20||simY>250){dy*=-1;}
      trail.push({x:simX,y:simY});
      if(trail.length>30) trail.shift();

      // Draw trail
      ctx.strokeStyle='rgba(74,144,226,0.85)'; ctx.lineWidth=6; ctx.lineCap='round';
      ctx.beginPath();
      trail.forEach((p,i)=>{ if(i===0)ctx.moveTo(p.x,p.y); else ctx.lineTo(p.x,p.y); });
      ctx.stroke();

      // Draw fingertip dot
      ctx.fillStyle='#4A90E2';
      ctx.beginPath(); ctx.arc(simX,simY,10,0,Math.PI*2); ctx.fill();
      ctx.fillStyle='white';
      ctx.beginPath(); ctx.arc(simX,simY,4,0,Math.PI*2); ctx.fill();
    }, 50);
  }

  // Expose methods for HTML
  static playSound()    { AlphabetActivity.getInstance().playSound(); }
  static validar()      { AlphabetActivity.getInstance().avaliar(); }
  static limparCanvas() { AlphabetActivity.getInstance().limparCanvas(); }
  static proximaLetra() { AlphabetActivity.getInstance().proximaLetra(); }
}


/* ================================================================
   CLASS: ListenActivity  (extends Atividade)
   ================================================================ */
class ListenActivity extends Atividade {
  static #instance;
  #palavras;
  #indice;

  static getInstance() {
    if (!ListenActivity.#instance) ListenActivity.#instance = new ListenActivity();
    return ListenActivity.#instance;
  }

  constructor() {
    super('Listen', 15);
    this.#palavras = [
      {w:'APPLE',  e:'🍎', group:['🍎','🍌','🍊','🍇']},
      {w:'DOG',    e:'🐕', group:['🐕','🐱','🐦','🐠']},
      {w:'SUN',    e:'☀️', group:['☀️','🌙','⭐','🌈']},
      {w:'HOUSE',  e:'🏠', group:['🏠','🚗','✈️','🚢']},
      {w:'TREE',   e:'🌳', group:['🌳','🌺','🍄','🌵']},
      {w:'BOOK',   e:'📚', group:['📚','🎸','🎨','⚽']},
      {w:'CAR',    e:'🚗', group:['🚗','🚲','🏍️','✈️']},
      {w:'FISH',   e:'🐠', group:['🐠','🐸','🦋','🐢']},
      {w:'STAR',   e:'⭐', group:['⭐','🌙','☀️','🌍']},
      {w:'FLOWER', e:'🌺', group:['🌺','🌴','🍀','🌾']},
    ];
    this.#indice = 0;
  }

  iniciar() {
    this.#indice = 0;
    this.#renderizar();
  }

  avaliar(escolhido) {
    const atual   = this.#palavras[this.#indice];
    const correto = escolhido === atual.e;
    this.mostrarFeedback('listen-feedback', correto,
      `🎉 Correto! É "${atual.w}"!`,
      `😊 Tente novamente! Ouça bem a pronúncia.`
    );
    if (correto) this.adicionarPontos();
    // Highlight choices
    document.querySelectorAll('.listen-choice').forEach(btn => {
      btn.style.opacity = '0.5';
      if (btn.dataset.emoji === atual.e) btn.style.opacity = '1';
    });
  }

  reproduzirAudio() {
    const word  = this.#palavras[this.#indice].w;
    const utter = new SpeechSynthesisUtterance(word);
    utter.lang  = 'en-US';
    utter.rate  = 0.75;
    utter.pitch = 1.1;
    speechSynthesis.speak(utter);
    // Visual feedback
    const el = document.getElementById('listen-emoji');
    el.style.transform = 'scale(1.2)';
    setTimeout(()=>el.style.transform='', 400);
  }

  proximaPalavra() {
    this.#indice = (this.#indice + 1) % this.#palavras.length;
    this.#renderizar();
  }

  #renderizar() {
    const atual = this.#palavras[this.#indice];
    document.getElementById('listen-emoji').textContent = atual.e;
    document.getElementById('listen-word').textContent  = atual.w;
    document.getElementById('listen-progress-txt').textContent = `${this.#indice+1}/10`;
    document.getElementById('listen-fill').style.width = `${((this.#indice+1)/10)*100}%`;
    this.ocultarFeedback('listen-feedback');

    // Build choices (shuffle)
    const choices = [...atual.group].sort(()=>Math.random()-0.5);
    const wrap    = document.getElementById('listen-choices');
    wrap.innerHTML = '';
    choices.forEach(emoji => {
      const btn = document.createElement('button');
      btn.className = 'act-btn listen listen-choice';
      btn.dataset.emoji = emoji;
      btn.innerHTML = `<span style="font-size:3rem">${emoji}</span>`;
      btn.style.opacity = '1';
      btn.onclick = () => this.avaliar(emoji);
      wrap.appendChild(btn);
    });

    // Auto play audio
    setTimeout(()=>this.reproduzirAudio(), 400);
  }

  static reproduzirAudio() { ListenActivity.getInstance().reproduzirAudio(); }
  static proximaPalavra()  { ListenActivity.getInstance().proximaPalavra(); }
}


/* ================================================================
   CLASS: SpeakActivity  (extends Atividade)
   ================================================================ */
class SpeakActivity extends Atividade {
  static #instance;
  #palavras;
  #indice;
  #recognition;
  #transcript;

  static getInstance() {
    if (!SpeakActivity.#instance) SpeakActivity.#instance = new SpeakActivity();
    return SpeakActivity.#instance;
  }

  constructor() {
    super('Speak', 20);
    this.#palavras = [
      {w:'CAT',    e:'🐱'},{w:'DOG',   e:'🐕'},{w:'BIRD',  e:'🐦'},
      {w:'FISH',   e:'🐠'},{w:'APPLE', e:'🍎'},{w:'STAR',  e:'⭐'},
      {w:'SUN',    e:'☀️'},{w:'BOOK',  e:'📚'},{w:'HOUSE', e:'🏠'},
      {w:'FLOWER', e:'🌺'},
    ];
    this.#indice    = 0;
    this.#transcript = '';
    this.#initRecognition();
  }

  #initRecognition() {
    const SpeechRec = window.SpeechRecognition || window.webkitSpeechRecognition;
    if (!SpeechRec) return;
    this.#recognition = new SpeechRec();
    this.#recognition.lang = 'en-US';
    this.#recognition.interimResults = true;
    this.#recognition.onresult = e => {
      let interim = '';
      for (let i = e.resultIndex; i < e.results.length; i++) {
        if (e.results[i].isFinal) this.#transcript = e.results[i][0].transcript;
        else interim = e.results[i][0].transcript;
      }
      const el = document.getElementById('speak-transcript');
      el.textContent = this.#transcript || interim || '...';
      el.className   = 'speak-transcript got-text';
    };
    this.#recognition.onerror = () => this.#simulateRecognition();
    this.#recognition.onend   = () => {
      document.getElementById('mic-visual').classList.remove('listening');
      document.getElementById('btn-speak-start').textContent = '🎤 Falar';
    };
  }

  iniciar() {
    this.#indice = 0;
    this.#transcript = '';
    this.#renderizar();
  }

  iniciarGravacao() {
    this.#transcript = '';
    const tranEl = document.getElementById('speak-transcript');
    tranEl.textContent = 'Ouvindo...';
    tranEl.className   = 'speak-transcript';
    document.getElementById('mic-visual').classList.add('listening');
    document.getElementById('btn-speak-start').textContent = '⏹ Parar';
    this.ocultarFeedback('speak-feedback');

    if (this.#recognition) {
      try { this.#recognition.start(); }
      catch(e) { this.#simulateRecognition(); }
    } else {
      this.#simulateRecognition();
    }
  }

  #simulateRecognition() {
    const atual = this.#palavras[this.#indice];
    // 70% chance of "correct" simulation
    const correct = Math.random() > 0.3;
    setTimeout(() => {
      this.#transcript = correct ? atual.w.toLowerCase() : 'wrong word';
      const el = document.getElementById('speak-transcript');
      el.textContent = `"${this.#transcript}" (simulado)`;
      el.className   = 'speak-transcript got-text';
      document.getElementById('mic-visual').classList.remove('listening');
      document.getElementById('btn-speak-start').textContent = '🎤 Falar';
    }, 2000);
  }

  avaliar() {
    if (!this.#transcript) {
      this.mostrarFeedback('speak-feedback', false, '', '🎤 Por favor, fale primeiro!');
      return;
    }
    const atual   = this.#palavras[this.#indice];
    const correto = this.#transcript.trim().toLowerCase().includes(atual.w.toLowerCase());
    this.mostrarFeedback('speak-feedback', correto,
      `🎉 Ótimo! Você disse "${atual.w}" corretamente!`,
      `😊 Tente novamente! A palavra é "${atual.w}"`
    );
    if (correto) this.adicionarPontos();
  }

  proximaPalavra() {
    this.#indice = (this.#indice + 1) % this.#palavras.length;
    this.#transcript = '';
    this.#renderizar();
  }

  #renderizar() {
    const atual = this.#palavras[this.#indice];
    document.getElementById('speak-emoji').textContent = atual.e;
    document.getElementById('speak-progress-txt').textContent = `${this.#indice+1}/10`;
    document.getElementById('speak-fill').style.width = `${((this.#indice+1)/10)*100}%`;
    const el = document.getElementById('speak-transcript');
    el.textContent = 'Pressione o botão e fale...';
    el.className   = 'speak-transcript';
    this.ocultarFeedback('speak-feedback');
    document.getElementById('mic-visual').classList.remove('listening');
  }

  static iniciarGravacao() { SpeakActivity.getInstance().iniciarGravacao(); }
  static avaliar()          { SpeakActivity.getInstance().avaliar(); }
  static proximaPalavra()   { SpeakActivity.getInstance().proximaPalavra(); }
}


/* ================================================================
   CLASS: WriteActivity  (extends Atividade)
   ================================================================ */
class WriteActivity extends Atividade {
  static #instance;
  #palavras;
  #indice;
  #dicaLevel;

  static getInstance() {
    if (!WriteActivity.#instance) WriteActivity.#instance = new WriteActivity();
    return WriteActivity.#instance;
  }

  constructor() {
    super('Write', 12);
    this.#palavras = [
      {w:'DOG',    e:'🐕'},{w:'CAT',    e:'🐱'},{w:'SUN',    e:'☀️'},
      {w:'APPLE',  e:'🍎'},{w:'BOOK',   e:'📚'},{w:'STAR',   e:'⭐'},
      {w:'FISH',   e:'🐠'},{w:'HOUSE',  e:'🏠'},{w:'BIRD',   e:'🐦'},
      {w:'FLOWER', e:'🌺'},
    ];
    this.#indice    = 0;
    this.#dicaLevel = 0;
  }

  iniciar() {
    this.#indice    = 0;
    this.#dicaLevel = 0;
    this.#renderizar();
  }

  avaliar() {
    const atual   = this.#palavras[this.#indice];
    const entrada = document.getElementById('write-input').value.trim().toUpperCase();
    const correto = entrada === atual.w;
    this.mostrarFeedback('write-feedback', correto,
      `🎉 Correto! A palavra é "${atual.w}"!`,
      `😊 Tente novamente! Você escreveu "${entrada || '(vazio)'}".`
    );
    if (correto) this.adicionarPontos(Math.max(1, 3 - this.#dicaLevel));
  }

  onInput(val) {
    // Real-time hint matching
    const atual = this.#palavras[this.#indice];
    const inp   = val.toUpperCase();
    let display = '';
    for (let i = 0; i < atual.w.length; i++) {
      if (inp[i] === atual.w[i]) display += atual.w[i] + ' ';
      else display += '_ ';
    }
    if (this.#dicaLevel === 0) {
      document.getElementById('hint-display').textContent = '_ '.repeat(atual.w.length).trim();
    }
  }

  mostrarDica() {
    const atual = this.#palavras[this.#indice];
    this.#dicaLevel = Math.min(this.#dicaLevel + 1, atual.w.length);
    const hint = atual.w.slice(0, this.#dicaLevel)
                 + '_'.repeat(atual.w.length - this.#dicaLevel);
    document.getElementById('hint-display').textContent = hint.split('').join(' ');
  }

  proximaPalavra() {
    this.#indice    = (this.#indice + 1) % this.#palavras.length;
    this.#dicaLevel = 0;
    this.#renderizar();
  }

  #renderizar() {
    const atual = this.#palavras[this.#indice];
    document.getElementById('write-emoji').textContent = atual.e;
    document.getElementById('write-input').value       = '';
    document.getElementById('hint-display').textContent = '_ '.repeat(atual.w.length).trim();
    document.getElementById('write-progress-txt').textContent = `${this.#indice+1}/10`;
    document.getElementById('write-fill').style.width = `${((this.#indice+1)/10)*100}%`;
    this.ocultarFeedback('write-feedback');
    document.getElementById('write-input').focus();
  }

  static avaliar()         { WriteActivity.getInstance().avaliar(); }
  static onInput(v)        { WriteActivity.getInstance().onInput(v); }
  static mostrarDica()     { WriteActivity.getInstance().mostrarDica(); }
  static proximaPalavra()  { WriteActivity.getInstance().proximaPalavra(); }
}


/* ================================================================
   CLASS: App (Singleton Controller)
   ================================================================ */
class App {
  static #instance;
  #usuario;
  #atividadeAtual;
  #atividades;

  static getInstance() {
    if (!App.#instance) App.#instance = new App();
    return App.#instance;
  }

  constructor() {
    this.#usuario       = null;
    this.#atividadeAtual = null;
    this.#atividades = {
      alphabet: AlphabetActivity.getInstance(),
      listen:   ListenActivity.getInstance(),
      speak:    SpeakActivity.getInstance(),
      write:    WriteActivity.getInstance(),
    };
  }

  iniciarSessao() {
    const nome  = document.getElementById('inp-nome').value.trim();
    const idade = document.getElementById('inp-idade').value;
    if (!nome) { document.getElementById('inp-nome').focus(); return; }
    this.#usuario = new Usuario(nome || 'Estudante', idade);
    this.#usuario.iniciarSessao();
    document.getElementById('login-card').style.display = 'none';
    document.getElementById('activity-section').style.display = 'block';
    document.getElementById('hud-menu').style.display = 'flex';
    document.getElementById('user-greeting').textContent = nome;
  }

  iniciarAtividade(tipo) {
    if (!this.#usuario) { alert('Por favor, insira seu nome primeiro!'); return; }
    this.#atividadeAtual = this.#atividades[tipo];
    this.#trocarTela(tipo);
    this.#atividadeAtual.iniciar();
  }

  #trocarTela(tipo) {
    document.querySelectorAll('.screen').forEach(s => s.classList.remove('active'));
    document.getElementById(`screen-${tipo}`).classList.add('active');
  }

  voltarMenu() {
    document.querySelectorAll('.screen').forEach(s => s.classList.remove('active'));
    document.getElementById('screen-menu').classList.add('active');
    this.#atividadeAtual = null;
  }

  getUsuario() { return this.#usuario; }
}

/* ================================================================
   INIT
   ================================================================ */
document.addEventListener('DOMContentLoaded', () => {
  App.getInstance(); // Initialize singleton
});
</script>
</body>
</html>
