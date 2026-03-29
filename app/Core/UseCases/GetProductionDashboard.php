<?php

namespace App\Core\UseCases;

use App\Core\Repositories\ProductionRepositoryInterface;

class GetProductionDashboard
{
    private $repository;

    public function __construct(ProductionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute($selectedLine = 'todas'): array
    {
        $allReports = $this->repository->getAllProduction();
        $reports = $allReports;

        $todasLinhas = [];
        foreach ($allReports as $item) {
            $todasLinhas[$item->product_line] = true;
        }

        if ($selectedLine !== 'todas' && !is_array($selectedLine)) {
            $reports = array_filter($reports, function($report) use ($selectedLine) {
                return $report->product_line === $selectedLine;
            });
        } elseif (is_array($selectedLine) && !in_array('todas', $selectedLine)) {
            $reports = array_filter($reports, function($report) use ($selectedLine) {
                return in_array($report->product_line, $selectedLine);
            });
        }

        $totalProduzido = 0;
        $totalDefeitos = 0;
        $somaEficiencia = 0;
        $linhasUnicas = [];

        foreach ($reports as $item) {
            $totalProduzido += $item->quantity_produced;
            $totalDefeitos += $item->quantity_defects;
            $somaEficiencia += $item->efficiency;
            $linhasUnicas[$item->product_line] = true;
        }

        $count = count($reports);

        $dadosTabela = $this->formatForChart($reports);
        $dadosGrafico = $this->formatForChart($allReports);
        return [
            'dados' => $dadosTabela,
            'totais' => [
                'total_linhas' => count($linhasUnicas),
                'total_produzido' => $totalProduzido,
                'total_defeitos' => $totalDefeitos,
                'eficiencia_geral' => $count > 0 ? round($somaEficiencia / $count, 2) : 0,
            ],
            'dadosGrafico' => $dadosGrafico,
            'linhaSelecionada' => $selectedLine,
            'linhas' => array_keys($todasLinhas)
        ];
    }

    private function formatForChart($reports): array
    {
        $agrupado = [];
        foreach ($reports as $item) {
            if (!isset($agrupado[$item->product_line])) {
                $agrupado[$item->product_line] = [
                    'linha' => $item->product_line,
                    'produzido' => $item->quantity_produced,
                    'defeitos' => $item->quantity_defects,
                    'eficiencia' => $item->efficiency,
                    'count' => 0
                ];
            }
            $agrupado[$item->product_line]['produzido'] += $item->quantity_produced;
            $agrupado[$item->product_line]['defeitos'] += $item->quantity_defects;
            $agrupado[$item->product_line]['eficiencia'] += $item->efficiency;
            $agrupado[$item->product_line]['count']++;
        }

        return array_map(function($item) {
            $item['eficiencia'] = round($item['eficiencia'] / $item['count'], 2);
            return $item;
        }, array_values($agrupado));
    }
}
