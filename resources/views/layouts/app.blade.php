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

        /* Custom select styling */
        select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%238b949e' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
            background-size: 1rem;
            padding-right: 2.5rem;
            appearance: none;
        }

        /* Pagination styles */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
        }

        .pagination .page-item {
            list-style: none;
        }

        .pagination .page-link {
            display: inline-block;
            padding: 0.5rem 0.75rem;
            margin: 0 0.125rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background-color: var(--surface);
            color: var(--text);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .pagination .page-link:hover {
            background-color: rgba(255, 255, 255, 0.05);
            border-color: var(--accent);
            color: var(--accent);
        }

        .pagination .page-item.active .page-link {
            background-color: var(--accent);
            border-color: var(--accent);
            color: white;
        }

        .pagination .page-item.disabled .page-link {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* Responsive pagination */
        @media (max-width: 640px) {
            .pagination {
                flex-wrap: wrap;
                gap: 0.25rem;
                justify-content: center;
            }

            .pagination .page-link {
                padding: 0.375rem 0.5rem;
                font-size: 0.75rem;
                min-width: 2rem;
                text-align: center;
            }

            /* Hide ellipsis (...) on mobile for cleaner look */
            .pagination .page-item.disabled span.page-link {
                display: none;
            }

            /* Show only essential navigation on mobile */
            .pagination .page-item:nth-child(n+6):nth-last-child(n+3) {
                display: none;
            }

            /* Add "..." indicator for hidden pages */
            .pagination::after {
                content: "...";
                display: inline-block;
                margin: 0 0.5rem;
                color: var(--muted);
                font-size: 0.875rem;
            }
        }

        /* Responsive filter buttons */
        @media (max-width: 640px) {
            .filter-buttons {
                flex-direction: column;
                align-items: stretch;
                gap: 0.75rem;
            }

            .filter-buttons .filter-btn,
            .filter-buttons .clear-btn {
                justify-content: center;
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
            }

            .filter-buttons .clear-btn {
                order: -1; /* Clear button appears first on mobile */
            }
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
