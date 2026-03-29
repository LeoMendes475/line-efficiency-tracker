<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>LG Production Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f4f4; }
        .navbar { background-color: #a50034; } /* Vermelho LG */
        .card-header { background-color: #f0f0f0; font-weight: bold; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark mb-4">
        <div class="container">
            <span class="navbar-brand">LG | Dashboard de Produção</span>
        </div>
    </nav>

    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header">Relatório de Linhas de Produção</div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Linha</th>
                            <th>Produzido</th>
                            <th>Defeitos</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                        <tr>
                            <td>{{ $report->productLine }}</td>
                            <td>{{ $report->quantityProduced }}</td>
                            <td>{{ $report->quantityDefects }}</td>
                            <td>{{ $report->productionDate }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Nenhum dado encontrado no banco.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
