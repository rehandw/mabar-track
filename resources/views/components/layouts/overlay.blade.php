<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OBS Live Overlay - MabarTrack</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800;900&family=JetBrains+Mono:wght@600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: transparent !important;
            overflow: hidden;
        }
        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }
        /* Custom neon glow for stream overlay */
        .glow-cyan {
            box-shadow: 0 0 25px rgba(6, 182, 212, 0.45);
        }
        .glow-emerald {
            box-shadow: 0 0 25px rgba(16, 185, 129, 0.45);
        }
    </style>
</head>
<body class="bg-transparent text-white antialiased">
    {{ $slot }}
</body>
</html>

