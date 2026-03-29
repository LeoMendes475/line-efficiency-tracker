<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LG Production System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'surface': '#0d1117',
                        'border': '#30363d',
                        'muted': '#8b949e',
                        'accent': '#a50034',
                        'text': '#c9d1d9'
                    },
                    fontFamily: {
                        'sans': ['DM Sans', 'sans-serif'],
                        'mono': ['DM Mono', 'monospace']
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --surface: #0d1117;
            --border: #30363d;
            --muted: #8b949e;
            --accent: #a50034;
            --text: #c9d1d9;
            --radius: 8px;
        }

        @keyframes skeleton-loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        .skeleton {
            background: linear-gradient(90deg, var(--border) 25%, rgba(48, 54, 61, 0.8) 50%, var(--border) 75%);
            background-size: 200% 100%;
            animation: skeleton-loading 1.5s infinite;
            border-radius: 4px;
        }

        @media (max-width: 768px) {
            .responsive-table thead {
                display: none;
            }
            .responsive-table tbody tr {
                display: block;
                border: 1px solid var(--border);
                margin-bottom: 1rem;
                padding: 1rem;
                border-radius: 8px;
            }
            .responsive-table tbody td {
                display: block;
                text-align: left;
                padding: 0.5rem 0;
                border: none;
            }
            .responsive-table tbody td:before {
                content: attr(data-label) ": ";
                font-weight: bold;
                color: var(--muted);
            }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-950 text-text font-sans m-0 p-8">
    <div class="container mx-auto">
        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>
