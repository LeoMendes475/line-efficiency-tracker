<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LG Production System</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --surface: #0d1117;
            --border: #30363d;
            --muted: #8b949e;
            --accent: #a50034; /* Vermelho LG */
            --text: #c9d1d9;
            --radius: 8px;
        }
        body {
            background: #010409;
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            margin: 0;
            padding: 2rem;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="container">
        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>
