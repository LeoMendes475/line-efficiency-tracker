@extends('layouts.app')

@push('styles')
<style>
/* ── Filter bar ── */
.filter-bar {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
}

.filter-label {
    font-size: 13px;
    color: var(--muted);
    font-weight: 500;
    white-space: nowrap;
}

.filter-pills {
    display: flex;
    gap: .5rem;
    flex-wrap: wrap;
}

.filter-btn {
    background: var(--surface);
    border: 1px solid var(--border);
    color: var(--muted);
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
    font-family: 'DM Sans', sans-serif;
    cursor: pointer;
    transition: all .2s;
    text-decoration: none;
    display: inline-block;
}

.filter-btn:hover {
    border-color: var(--accent);
    color: var(--accent);
}

.filter-btn.active {
    background: var(--accent);
    border-color: var(--accent);
    color: #fff;
}

/* ── Clear button ── */
.clear-btn:hover {
    border-color: #ef4444 !important;
    color: #ef4444 !important;
    background: rgba(239, 68, 68, 0.1) !important;
}

/* ── Summary cards ── */
.cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 1.25rem 1.5rem;
    position: relative;
    overflow: hidden;
    transition: transform .2s, border-color .2s;
}

.card:hover {
    transform: translateY(-2px);
    border-color: #30363d;
}

.card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: var(--card-accent, var(--accent));
}

.card-icon {
    font-size: 1.5rem;
    margin-bottom: .5rem;
    opacity: .7;
}

.card-value {
    font-size: 2rem;
    font-weight: 700;
    letter-spacing: -.03em;
    line-height: 1;
    margin-bottom: .25rem;
    font-family: 'DM Mono', monospace;
}

.card-label {
    font-size: 12px;
    color: var(--muted);
    font-weight: 500;
    letter-spacing: .04em;
    text-transform: uppercase;
}

/* ── Content grid ── */
.content-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

@media (max-width: 900px) {
    .content-grid { grid-template-columns: 1fr; }
}

/* ── Panel ── */
.panel {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
}

.panel-header {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.panel-title {
    font-size: 13px;
    font-weight: 600;
    letter-spacing: .04em;
    text-transform: uppercase;
    color: var(--muted);
}

.panel-body {
    padding: 1.25rem 1.5rem;
}

/* ── Table ── */
.table-wrap {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

thead th {
    padding: .6rem 1rem;
    text-align: left;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .06em;
    text-transform: uppercase;
    color: var(--muted);
    border-bottom: 1px solid var(--border);
    white-space: nowrap;
}

tbody td {
    padding: .85rem 1rem;
    border-bottom: 1px solid rgba(33,38,45,.6);
    vertical-align: middle;
}

tbody tr:last-child td { border-bottom: none; }

tbody tr:hover td {
    background: rgba(255,255,255,.02);
}

.td-linha {
    font-weight: 600;
    color: var(--text);
}

.td-num {
    font-family: 'DM Mono', monospace;
    font-size: 13px;
}

/* ── Progress bar ── */
.eff-bar {
    display: flex;
    align-items: center;
    gap: .75rem;
}

.bar-track {
    flex: 1;
    height: 6px;
    background: var(--border);
    border-radius: 3px;
    overflow: hidden;
}

.bar-fill {
    height: 100%;
    border-radius: 3px;
    transition: width .6s ease;
}

.bar-val {
    font-family: 'DM Mono', monospace;
    font-size: 13px;
    font-weight: 600;
    min-width: 52px;
    text-align: right;
}

/* ── Empty state ── */
.empty {
    padding: 3rem;
    text-align: center;
    color: var(--muted);
}

/* ── Chart container ── */
.chart-container {
    position: relative;
    height: 260px;
}

/* ── Footer line ── */
.page-footer {
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border);
    text-align: center;
    font-size: 12px;
    color: var(--muted);
}

/* ── Responsive table ── */
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
    .eff-bar {
        margin-top: 0.5rem;
    }
}

/* ── Pagination ── */
.pagination {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    margin: 2rem 0 1rem 0;
    font-family: 'DM Sans', sans-serif;
}
.pagination .page-item {
    list-style: none;
}
.pagination .page-link {
    display: inline-block;
    min-width: 36px;
    padding: 6px 12px;
    margin: 0 2px;
    border: 1px solid var(--border);
    border-radius: 6px;
    background: var(--surface);
    color: var(--muted);
    font-size: 15px;
    font-weight: 500;
    text-align: center;
    text-decoration: none;
    transition: all .2s;
    cursor: pointer;
}
.pagination .page-link:hover {
    border-color: var(--accent);
    color: var(--accent);
}
.pagination .active .page-link {
    background: var(--accent);
    border-color: var(--accent);
    color: #fff;
    font-weight: 700;
}
.pagination .disabled .page-link {
    color: var(--border);
    cursor: not-allowed;
    background: var(--surface);
    border-color: var(--border);
}
</style>
@endpush

@section('content')

{{-- ── Search bar ── --}}
<form method="GET" action="{{ route('dashboard') }}" class="search-bar" style="margin-bottom: 1.5rem; display: flex; gap: 1rem; align-items: center;">
    <input type="text" name="search" value="{{ request('search', '') }}" placeholder="Buscar linha específica..." class="search-input" style="padding: 8px 16px; border-radius: 20px; border: 1px solid var(--border); background: var(--surface); color: var(--text); font-size: 14px; outline: none; min-width: 220px;">
    <button type="submit" class="filter-btn" style="padding: 8px 20px;">Buscar</button>
    @if(request('search') || request('linha') !== 'todas')
        <a href="{{ route('dashboard') }}" class="filter-btn clear-btn" style="padding: 8px 20px; background: transparent; border-color: var(--muted); color: var(--muted);">Limpar filtros</a>
    @endif
</form>

{{-- ── Filter bar ── --}}
<div class="filter-bar">
    <span class="filter-label">Filtrar por linha:</span>
    <div class="filter-pills">
        <a href="{{ route('dashboard', ['linha' => 'todas']) }}"
           class="filter-btn {{ $linhaSelecionada ?? '' === 'todas' ? 'active' : '' }}">
            Todas as linhas
        </a>
        @foreach ($linhas ?? [] as $linha)
            <a href="{{ ($linhaSelecionada ?? '') === $linha ? route('dashboard', ['linha' => 'todas']) : route('dashboard', ['linha' => $linha]) }}"
            class="filter-btn {{ ($linhaSelecionada ?? '') === $linha ? 'active' : '' }}">
                {{ $linha }}
                @if(($linhaSelecionada ?? '') === $linha)
                    <span class="remove-filter">&times;</span>
                @endif
            </a>
        @endforeach
    </div>
</div>

{{-- ── Summary cards ── --}}
<div class="cards-grid">
    <div class="card" style="--card-accent: #f97316">
        <div class="card-icon">🏭</div>
        <div class="card-value">{{ $linhaSelecionada === 'todas' ? 'Todas' : $linhaSelecionada }}</div>
        <div class="card-label">Linha do produto</div>
    </div>

    <div class="card" style="--card-accent: #3b82f6">
        <div class="card-icon">📦</div>
        <div class="card-value">{{ number_format($totais['total_produzido'] ?? 0, 0, ',', '.') }}</div>
        <div class="card-label">Quantidade produzida</div>
    </div>

    <div class="card" style="--card-accent: #ef4444">
        <div class="card-icon">⚠️</div>
        <div class="card-value">{{ number_format($totais['total_defeitos'] ?? 0, 0, ',', '.') }}</div>
        <div class="card-label">Quantidade de defeitos</div>
    </div>

    @php
        $eficiencia = $totais['eficiencia_geral'] ?? 0;
        $corEficiencia = $eficiencia >= 95 ? '#22c55e' : ($eficiencia >= 85 ? '#eab308' : '#ef4444');
    @endphp
    <div class="card" style="--card-accent: {{ $corEficiencia }}">
        <div class="card-icon">📈</div>
        <div class="card-value" style="color: {{ $corEficiencia }}">
            {{ $eficiencia }}%
        </div>
        <div class="card-label">Eficiência (%)</div>
    </div>
</div>

{{-- ── Content: table ── --}}
<div class="panel">
    <div class="panel-header">
        <span class="panel-title">Eficiência por linha</span>
        <span class="pill">
            ● {{ $linhaSelecionada ?? 'Todas as linhas' }}
        </span>
    </div>
    <div class="table-wrap">
        {{-- Verificamos se a variável existe e não está vazia --}}
        @if (!isset($dados) || empty($dados))
            <div class="empty">Nenhum dado encontrado para a seleção.</div>
        @else
            <table class="responsive-table">
                <thead>
                    <tr>
                        <th>Linha</th>
                        <th>Produzido</th>
                        <th>Defeitos</th>
                        <th>Eficiência</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dados as $row)
                        @php
                            $eff = (float) ($row['eficiencia'] ?? 0);
                            $barColor = $eff >= 95 ? '#22c55e' : ($eff >= 85 ? '#eab308' : '#ef4444');
                        @endphp
                        <tr>
                            {{-- 1. Troque linha_produto por product_line --}}
                            <td class="td-linha" data-label="Linha">{{ $row['linha'] ?? 'N/A' }}</td>

                            {{-- 2. Troque total_produzido por quantity_produced --}}
                            <td class="td-num" data-label="Produzido">{{ number_format($row['produzido'] ?? 0, 0, ',', '.') }}</td>

                            {{-- 3. Troque total_defeitos por quantity_defects --}}
                            <td class="td-num" data-label="Defeitos">
                                <span class="pill" style="color: #ef4444">
                                    {{ number_format($row['defeitos'] ?? 0, 0, ',', '.') }}
                                </span>
                            </td>

                            <td data-label="Eficiência">
                                <div class="eff-bar">
                                    <div class="bar-track">
                                        <div class="bar-fill" style="width: {{ $eff }}%; background: {{ $barColor }};"></div>
                                    </div>
                                    {{-- 4. Troque eficiencia por efficiency --}}
                                    <span class="bar-val" style="color: {{ $barColor }}">{{ $eff }}%</span>
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

<p class="page-footer">Planta A · Dados simulados · {{ now()->format('d/m/Y') }}</p>

@endsection
