<?php
require_once 'core/SessionManager.php';
require_once 'activities/Listen.php';
require_once 'activities/Write.php';
require_once 'activities/Speak.php';
require_once 'activities/Alphabet.php';
$session = new SessionManager();
$score = $session->getScore();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Kant Speak | Aprenda inglês brincando</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        /* Seus estilos existentes, ou importe de style.css */
        body { background: linear-gradient(135deg, #f6f9fc 0%, #eef2f7 100%); }
        .btn-module {
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            gap: 0.75rem; padding: 1.25rem; border-radius: 1.5rem; transition: all 0.2s;
            width: 100%; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            background: white; border: 1px solid rgba(0,0,0,0.05);
        }
        .btn-module:hover { transform: translateY(-4px); box-shadow: 0 20px 25px -12px rgba(0,0,0,0.15); }
        .btn-alphabet { background-color: #4A90E2; color: white; }
        .btn-listen   { background-color: #2ecc71; color: white; }
        .btn-speak    { background-color: #f1c40f; color: #1f2937; }
        .btn-write    { background-color: #9b59b6; color: white; }
        .btn-karaoke  { background-color: #e74c3c; color: white; }
        .btn-draw     { background-color: #397774; color: #eef2f7; }
        .animate-float { animation: float 3s ease-in-out infinite; }
        @keyframes float { 0% { transform: translateY(0px); } 50% { transform: translateY(-10px); } 100% { transform: translateY(0px); } }
        .btn-module i { font-size: 2.5rem; margin-bottom: 0.25rem; }
        .btn-module span { font-weight: 700; font-size: 1.25rem; }
        @media (max-width: 480px) { .btn-module i { font-size: 2rem; } .btn-module span { font-size: 1rem; } }
    </style>
</head>
<body class="antialiased">
    <div class="min-h-screen flex flex-col items-center justify-center p-6 gap-8">
        <div class="flex flex-col items-center gap-3 text-center">
            <div class="animate-float drop-shadow-4xl bg-white/40 rounded-full p-3 backdrop-blur-sm">
                <img src="img/mascote.png" alt="mascote" class="w-48 h-48 object-contain">
            </div>
            <h1 class="text-5xl md:text-6xl font-extrabold tracking-tight text-gray-800">Kant <span class="text-blue-600">Speak</span></h1>
            <p class="text-xl text-gray-500 max-w-md">Aprenda inglês de um jeito divertido!</p>
        </div>
        <div class="grid grid-cols-2 gap-5 w-full max-w-md">
            <a href="alphabet.html" class="btn-module btn-alphabet"><i class="fas fa-pen-fancy"></i><span>Alphabet</span></a>
            <a href="listen.html" class="btn-module btn-listen"><i class="fas fa-ear-listen"></i><span>Listen</span></a>
            <a href="speak.html" class="btn-module btn-speak"><i class="fas fa-microphone-alt"></i><span>Speak</span></a>
            <a href="write.html" class="btn-module btn-write"><i class="fas fa-book-open"></i><span>Write</span></a>
            <a href="karaoke.html" class="btn-module btn-karaoke"><i class="fas fa-music"></i><span>Karaoke Fun</span></a>
            <a href="draw.html" class="btn-module btn-draw"><i class="fas fa-brush"></i><span>Draw</span></a>
        </div>
        <p class="text-xs text-gray-600 mt-6 text-center">˗ˏˋ Escolha uma atividade e comece a praticar! ˎˊ˗</p>
    </div>
</body>
</html>