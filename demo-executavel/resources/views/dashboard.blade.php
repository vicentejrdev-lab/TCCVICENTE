@extends('layouts.app', ['title' => 'Painel operacional'])

@section('content')
    <section class="hero-panel">
        <div>
            <span class="eyebrow">Visão geral</span>
            <h2>Cenário de treinamento pronto para avaliação</h2>
            <p>Acompanhe os dados fictícios, confira a competência e execute o fluxo de fechamento financeiro.</p>
        </div>
        @if ($period)
            <a class="button button-primary" href="{{ route('periods.show', $period) }}">Abrir competência</a>
        @endif
    </section>

    <section class="metric-grid" aria-label="Indicadores do cenário">
        <article class="metric-card"><span class="metric-icon blue">◎</span><div><small>Consultores</small><strong>{{ number_format($counts['consultants'], 0, ',', '.') }}</strong><span>três estruturas fictícias</span></div></article>
        <article class="metric-card"><span class="metric-icon cyan">◇</span><div><small>Produções</small><strong>{{ number_format($counts['productions'], 0, ',', '.') }}</strong><span>registros da competência</span></div></article>
        <article class="metric-card"><span class="metric-icon violet">◉</span><div><small>Associados</small><strong>{{ number_format($counts['associates'], 0, ',', '.') }}</strong><span>cadastros demonstrativos</span></div></article>
        <article class="metric-card"><span class="metric-icon amber">▤</span><div><small>Boletos pendentes</small><strong>{{ number_format($counts['unpaidBills'], 0, ',', '.') }}</strong><span>casos para consulta</span></div></article>
    </section>

    <div class="content-grid content-grid-wide">
        <section class="panel">
            <div class="panel-heading">
                <div><span class="eyebrow">Financeiro</span><h3>Competência atual</h3></div>
                @if ($period)<x-status-badge :status="$period->status" />@endif
            </div>
            @if ($period)
                <div class="period-summary">
                    <div><small>Competência</small><strong>{{ $period->label }}</strong></div>
                    <div><small>Período</small><strong>{{ $period->starts_on->format('d/m/Y') }} a {{ $period->ends_on->format('d/m/Y') }}</strong></div>
                    <div><small>Lotes</small><strong>{{ $period->lots->count() }}</strong></div>
                </div>
                @foreach ($period->lots as $lot)
                    <a class="lot-row" href="{{ route('lots.show', $lot) }}">
                        <div><span class="lot-code">{{ $lot->code }}</span><strong>Fechamento de premiações</strong><small>{{ $lot->item_count }} consultores · {{ $lot->production_count }} produções</small></div>
                        <div class="lot-value"><small>Valor oficial</small><strong>R$ {{ number_format($lot->official_amount, 2, ',', '.') }}</strong></div>
                        <x-status-badge :status="$lot->status" />
                        <span class="row-arrow">→</span>
                    </a>
                @endforeach
            @else
                <div class="empty-state">Nenhuma competência foi encontrada.</div>
            @endif
        </section>

        <section class="panel">
            <div class="panel-heading"><div><span class="eyebrow">Rastreabilidade</span><h3>Atividade recente</h3></div></div>
            <div class="timeline">
                @forelse ($recentEvents as $event)
                    <article class="timeline-item">
                        <span class="timeline-dot"></span>
                        <div><strong>{{ $event->description }}</strong><small>{{ $event->user?->name ?? 'Sistema' }} · {{ $event->created_at->format('d/m/Y H:i') }}</small></div>
                    </article>
                @empty
                    <div class="empty-state compact">O fechamento ainda não foi executado. O primeiro evento aparecerá aqui.</div>
                @endforelse
            </div>
        </section>
    </div>
@endsection
