@extends('layouts.app')

@section('content')

<div id="loading-overlay" class="fixed inset-0 bg-gray-950 bg-opacity-80 flex items-center justify-center z-50 hidden">
    <div class="w-10 h-10 border-4 border-border border-t-accent rounded-full animate-spin"></div>
</div>

<div id="filter-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-40 hidden">
    <div class="bg-surface border border-border rounded-lg p-6 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-text">Filtros</h3>
            <button id="close-modal" class="text-muted hover:text-text text-xl">&times;</button>
        </div>

        <form id="filter-form" method="GET" action="{{ route('dashboard') }}" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-muted mb-2">Buscar por linha</label>
                <input type="text" name="search" value="{{ request('search', '') }}" placeholder="Digite o nome da linha..." class="w-full px-4 py-2 rounded-lg border border-border bg-gray-950 text-text text-sm outline-none focus:border-accent focus:ring-1 focus:ring-accent">
            </div>

            <div>
                <label class="block text-sm font-medium text-muted mb-2">Selecionar linhas</label>
                <div class="space-y-2 max-h-40 overflow-y-auto">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="linha[]" value="todas" {{ in_array('todas', request('linha', [])) || !request('linha') ? 'checked' : '' }} class="rounded border-border bg-gray-950 text-accent focus:ring-accent focus:ring-1">
                        <span class="text-sm text-text">Todas as linhas</span>
                    </label>
                    @foreach ($linhas ?? [] as $linha)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="linha[]" value="{{ $linha }}" {{ in_array($linha, request('linha', [])) ? 'checked' : '' }} class="rounded border-border bg-gray-950 text-accent focus:ring-accent focus:ring-1">
                            <span class="text-sm text-text">{{ $linha }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 px-4 py-2 bg-accent border border-accent text-white rounded-lg text-sm font-medium cursor-pointer transition-all duration-200 hover:bg-red-600 hover:border-red-600">
                    Aplicar filtros
                </button>
                <a href="{{ route('dashboard') }}" id="clear-modal-filters" class="px-4 py-2 bg-transparent border border-muted text-muted rounded-lg text-sm font-medium cursor-pointer transition-all duration-200 hover:border-red-500 hover:text-red-500 hover:bg-red-500 hover:bg-opacity-10">
                    Limpar
                </a>
            </div>
        </form>
    </div>
</div>

<div class="mb-6 flex items-center gap-3 filter-buttons">
    <button id="open-filter-modal" class="filter-btn px-5 py-2 bg-surface border border-border text-muted rounded-full text-sm font-medium cursor-pointer transition-all duration-200 hover:border-accent hover:text-accent flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
        Filtros
    </button>

    @if(request('search') || (request('linha') && (!in_array('todas', request('linha', [])) || count(request('linha', [])) > 1)))
        <a href="{{ route('dashboard') }}" id="clear-filters-main" class="clear-btn px-4 py-2 bg-red-600 border border-red-600 text-white rounded-full text-sm font-medium cursor-pointer transition-all duration-200 hover:bg-red-700 hover:border-red-700 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            Limpar filtros
        </a>
    @endif
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8" id="cards-container">
    <div class="bg-surface border border-border rounded-lg p-5 relative overflow-hidden transition-all duration-200 hover:-translate-y-0.5 hover:border-gray-600 before:content-[''] before:absolute before:top-0 before:left-0 before:right-0 before:h-0.5 before:bg-orange-500">
        <div class="text-2xl mb-2 opacity-70">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
        </div>
        <div class="text-2xl font-bold leading-none mb-1 font-mono -tracking-wider">
            @if($linhaSelecionada === 'todas')
                Todas
            @elseif(is_array($linhaSelecionada))
                {{ count($linhaSelecionada) }} linha{{ count($linhaSelecionada) > 1 ? 's' : '' }}
            @else
                {{ $linhaSelecionada }}
            @endif
        </div>
        <div class="text-xs text-muted font-medium tracking-wider uppercase">Linha do produto</div>
    </div>

    <div class="bg-surface border border-border rounded-lg p-5 relative overflow-hidden transition-all duration-200 hover:-translate-y-0.5 hover:border-gray-600 before:content-[''] before:absolute before:top-0 before:left-0 before:right-0 before:h-0.5 before:bg-blue-500">
        <div class="text-2xl mb-2 opacity-70">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
        </div>
        <div class="text-2xl font-bold leading-none mb-1 font-mono -tracking-wider">{{ number_format($totais['total_produzido'] ?? 0, 0, ',', '.') }}</div>
        <div class="text-xs text-muted font-medium tracking-wider uppercase">Quantidade produzida</div>
    </div>

    <div class="bg-surface border border-border rounded-lg p-5 relative overflow-hidden transition-all duration-200 hover:-translate-y-0.5 hover:border-gray-600 before:content-[''] before:absolute before:top-0 before:left-0 before:right-0 before:h-0.5 before:bg-red-500">
        <div class="text-2xl mb-2 opacity-70">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
        </div>
        <div class="text-2xl font-bold leading-none mb-1 font-mono -tracking-wider">{{ number_format($totais['total_defeitos'] ?? 0, 0, ',', '.') }}</div>
        <div class="text-xs text-muted font-medium tracking-wider uppercase">Quantidade de defeitos</div>
    </div>

    @php
        $eficiencia = $totais['eficiencia_geral'] ?? 0;
        $corEficiencia = $eficiencia >= 95 ? 'green-500' : ($eficiencia >= 85 ? 'yellow-500' : 'red-500');
    @endphp
    <div class="bg-surface border border-border rounded-lg p-5 relative overflow-hidden transition-all duration-200 hover:-translate-y-0.5 hover:border-gray-600 before:content-[''] before:absolute before:top-0 before:left-0 before:right-0 before:h-0.5 before:bg-{{ $corEficiencia }}">
        <div class="text-2xl mb-2 opacity-70">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
            </svg>
        </div>
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
            ●
            @if($linhaSelecionada === 'todas')
                Todas as linhas
            @elseif(is_array($linhaSelecionada))
                {{ count($linhaSelecionada) }} linha{{ count($linhaSelecionada) > 1 ? 's' : '' }} selecionada{{ count($linhaSelecionada) > 1 ? 's' : '' }}
            @else
                {{ $linhaSelecionada }}
            @endif
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
                                    <span class="font-mono text-sm font-semibold min-w-12 text-right text-{{ $eff >= 95 ? 'green' : ($eff >= 85 ? 'yellow' : 'red') }}-500">{{ $eff }}%</span>
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
function openModal() {
    document.getElementById('filter-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('filter-modal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function showLoading() {
    document.getElementById('loading-overlay').classList.remove('hidden');
}

function hideLoading() {
    document.getElementById('loading-overlay').classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', function() {
    const openBtn = document.getElementById('open-filter-modal');
    const closeBtn = document.getElementById('close-modal');
    const modal = document.getElementById('filter-modal');

    if (openBtn) {
        openBtn.addEventListener('click', openModal);
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', closeModal);
    }

    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });

    const clearFiltersLink = document.getElementById('clear-filters');
    if (clearFiltersLink) {
        clearFiltersLink.addEventListener('click', function(e) {
            showLoading();
        });
    }

    const clearMainFiltersLink = document.getElementById('clear-filters-main');
    if (clearMainFiltersLink) {
        clearMainFiltersLink.addEventListener('click', function(e) {
            showLoading();
        });
    }

    const clearModalFiltersLink = document.getElementById('clear-modal-filters');
    if (clearModalFiltersLink) {
        clearModalFiltersLink.addEventListener('click', function(e) {
            closeModal();
            showLoading();
        });
    }

    const filterForm = document.getElementById('filter-form');
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            closeModal();
            showLoading();
        });
    }

    const lineCheckboxes = document.querySelectorAll('input[name="linha[]"]');
    lineCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.value === 'todas' && this.checked) {
                lineCheckboxes.forEach(cb => {
                    if (cb.value !== 'todas') {
                        cb.checked = false;
                    }
                });
            } else if (this.value !== 'todas' && this.checked) {
                const todasCheckbox = document.querySelector('input[name="linha[]"][value="todas"]');
                if (todasCheckbox) {
                    todasCheckbox.checked = false;
                }
            }
        });
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('.pagination .page-link')) {
            showLoading();
        }
    });

    window.addEventListener('load', function() {
        hideLoading();
    });

    setTimeout(hideLoading, 10000);
});
</script>

@endsection
