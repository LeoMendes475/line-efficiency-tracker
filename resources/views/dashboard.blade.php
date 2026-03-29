@extends('layouts.app')

@section('content')

<!-- Loading Overlay -->
<div id="loading-overlay" class="fixed inset-0 bg-gray-950 bg-opacity-80 flex items-center justify-center z-50 hidden">
    <div class="w-10 h-10 border-4 border-border border-t-accent rounded-full animate-spin"></div>
</div>

<form method="GET" action="{{ route('dashboard') }}" class="mb-6 flex gap-4 items-center">
    <input type="text" name="search" value="{{ request('search', '') }}" placeholder="Buscar linha específica..." class="px-4 py-2 rounded-full border border-border bg-surface text-text text-sm outline-none min-w-56">
    <button type="submit" class="px-5 py-2 bg-surface border border-border text-muted rounded-full text-sm font-medium cursor-pointer transition-all duration-200 hover:border-accent hover:text-accent">Buscar</button>
    @if(request('search') || request('linha') !== 'todas')
        <a href="{{ route('dashboard') }}" class="px-5 py-2 bg-transparent border border-muted text-muted rounded-full text-sm font-medium cursor-pointer transition-all duration-200 hover:border-red-500 hover:text-red-500 hover:bg-red-500 hover:bg-opacity-10">Limpar filtros</a>
    @endif
</form>

<div class="flex items-center gap-4 mb-8 flex-wrap">
    <span class="text-sm text-muted font-medium whitespace-nowrap">Filtrar por linha:</span>
    <div class="flex gap-2 flex-wrap">
        <a href="{{ route('dashboard', ['linha' => 'todas']) }}"
           class="bg-surface border border-border text-muted px-4 py-1.5 rounded-full text-sm font-medium cursor-pointer transition-all duration-200 no-underline inline-block hover:border-accent hover:text-accent {{ $linhaSelecionada ?? '' === 'todas' ? 'bg-accent border-accent text-white' : '' }}">
            Todas as linhas
        </a>
        @foreach ($linhas ?? [] as $linha)
            <a href="{{ ($linhaSelecionada ?? '') === $linha ? route('dashboard', ['linha' => 'todas']) : route('dashboard', ['linha' => $linha]) }}"
            class="bg-surface border border-border text-muted px-4 py-1.5 rounded-full text-sm font-medium cursor-pointer transition-all duration-200 no-underline inline-block hover:border-accent hover:text-accent {{ ($linhaSelecionada ?? '') === $linha ? 'bg-accent border-accent text-white' : '' }}">
                {{ $linha }}
                @if(($linhaSelecionada ?? '') === $linha)
                    <span>&times;</span>
                @endif
            </a>
        @endforeach
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8" id="cards-container">
    <div class="bg-surface border border-border rounded-lg p-5 relative overflow-hidden transition-all duration-200 hover:-translate-y-0.5 hover:border-gray-600 before:content-[''] before:absolute before:top-0 before:left-0 before:right-0 before:h-0.5 before:bg-orange-500">
        <div class="text-2xl mb-2 opacity-70">🏭</div>
        <div class="text-2xl font-bold leading-none mb-1 font-mono -tracking-wider">{{ $linhaSelecionada === 'todas' ? 'Todas' : $linhaSelecionada }}</div>
        <div class="text-xs text-muted font-medium tracking-wider uppercase">Linha do produto</div>
    </div>

    <div class="bg-surface border border-border rounded-lg p-5 relative overflow-hidden transition-all duration-200 hover:-translate-y-0.5 hover:border-gray-600 before:content-[''] before:absolute before:top-0 before:left-0 before:right-0 before:h-0.5 before:bg-blue-500">
        <div class="text-2xl mb-2 opacity-70">📦</div>
        <div class="text-2xl font-bold leading-none mb-1 font-mono -tracking-wider">{{ number_format($totais['total_produzido'] ?? 0, 0, ',', '.') }}</div>
        <div class="text-xs text-muted font-medium tracking-wider uppercase">Quantidade produzida</div>
    </div>

    <div class="bg-surface border border-border rounded-lg p-5 relative overflow-hidden transition-all duration-200 hover:-translate-y-0.5 hover:border-gray-600 before:content-[''] before:absolute before:top-0 before:left-0 before:right-0 before:h-0.5 before:bg-red-500">
        <div class="text-2xl mb-2 opacity-70">⚠️</div>
        <div class="text-2xl font-bold leading-none mb-1 font-mono -tracking-wider">{{ number_format($totais['total_defeitos'] ?? 0, 0, ',', '.') }}</div>
        <div class="text-xs text-muted font-medium tracking-wider uppercase">Quantidade de defeitos</div>
    </div>

    @php
        $eficiencia = $totais['eficiencia_geral'] ?? 0;
        $corEficiencia = $eficiencia >= 95 ? 'green-500' : ($eficiencia >= 85 ? 'yellow-500' : 'red-500');
    @endphp
    <div class="bg-surface border border-border rounded-lg p-5 relative overflow-hidden transition-all duration-200 hover:-translate-y-0.5 hover:border-gray-600 before:content-[''] before:absolute before:top-0 before:left-0 before:right-0 before:h-0.5 before:bg-{{ $corEficiencia }}">
        <div class="text-2xl mb-2 opacity-70">📈</div>
        <div class="text-2xl font-bold leading-none mb-1 font-mono -tracking-wider text-{{ $corEficiencia }}">
            {{ $eficiencia }}%
        </div>
        <div class="text-xs text-muted font-medium tracking-wider uppercase">Eficiência (%)</div>
    </div>
</div>

<div class="bg-surface border border-border rounded-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-border flex items-center justify-between">
        <span class="text-sm font-semibold tracking-wider uppercase text-muted">Eficiência por linha</span>
        <span class="px-2 py-1 rounded-full text-xs">
            ● {{ $linhaSelecionada ?? 'Todas as linhas' }}
        </span>
    </div>
    <div class="overflow-x-auto" id="table-container">
        @if (!isset($dados) || empty($dados))
            <div class="py-12 text-center text-muted">Nenhum dado encontrado para a seleção.</div>
        @else
            <table class="w-full border-collapse text-sm">
                <thead>
                    <tr>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold tracking-wider uppercase text-muted border-b border-border whitespace-nowrap">Linha</th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold tracking-wider uppercase text-muted border-b border-border whitespace-nowrap">Produzido</th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold tracking-wider uppercase text-muted border-b border-border whitespace-nowrap">Defeitos</th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold tracking-wider uppercase text-muted border-b border-border whitespace-nowrap">Eficiência</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dados as $row)
                        @php
                            $eff = (float) ($row['eficiencia'] ?? 0);
                            $barColorClass = $eff >= 95 ? 'bg-green-500 text-green-500' : ($eff >= 85 ? 'bg-yellow-500 text-yellow-500' : 'bg-red-500 text-red-500');
                        @endphp
                        <tr class="hover:bg-white hover:bg-opacity-5">
                            <td class="px-4 py-3.5 align-middle font-semibold text-text" data-label="Linha">{{ $row['linha'] ?? 'N/A' }}</td>

                            <td class="px-4 py-3.5 align-middle font-mono text-sm" data-label="Produzido">{{ number_format($row['produzido'] ?? 0, 0, ',', '.') }}</td>

                            <td class="px-4 py-3.5 align-middle font-mono text-sm" data-label="Defeitos">
                                <span class="text-red-500">
                                    {{ number_format($row['defeitos'] ?? 0, 0, ',', '.') }}
                                </span>
                            </td>

                            <td class="px-4 py-3.5 align-middle" data-label="Eficiência">
                                <div class="flex items-center gap-3">
                                    <div class="flex-1 h-1.5 bg-border rounded overflow-hidden">
                                        <div class="h-full rounded transition-all duration-700 {{ $barColorClass }}" style="width: {{ $eff }}%;"></div>
                                    </div>
                                    <span class="font-mono text-sm font-semibold min-w-12 text-right {{ $barColorClass }}">{{ $eff }}%</span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $dados->links('vendor.pagination.dashboard') }}
        @endif
    </div>
</div>
</div>

<p class="mt-8 pt-6 border-t border-border text-center text-sm text-muted">Leonardo Mendes · {{ now()->format('d/m/Y') }}</p>

<script>
// Skeleton Loading Functions
function showLoading() {
    document.getElementById('loading-overlay').classList.remove('hidden');
}

function hideLoading() {
    document.getElementById('loading-overlay').classList.add('hidden');
}

// Handle form submissions with loading
document.addEventListener('DOMContentLoaded', function() {
    // Handle search form
    const searchForm = document.querySelector('form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            showLoading();
        });
    }

    // Handle filter links
    const filterLinks = document.querySelectorAll('a[href*="linha="]');
    filterLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            showLoading();
        });
    });

    // Handle pagination links
    document.addEventListener('click', function(e) {
        if (e.target.closest('.pagination .page-link')) {
            showLoading();
        }
    });

    // Hide loading when page is fully loaded
    window.addEventListener('load', function() {
        hideLoading();
    });

    // Hide loading after a timeout as fallback
    setTimeout(hideLoading, 10000);
});
</script>

@endsection
