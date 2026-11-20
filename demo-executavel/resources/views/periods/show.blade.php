@extends('layouts.app', ['title' => 'Competência '.$period->label])

@section('content')
    <nav class="breadcrumbs"><a href="{{ route('periods.index') }}">Competências</a><span>/</span><strong>{{ $period->label }}</strong></nav>

    <section class="hero-panel compact-hero">
        <div><span class="eyebrow">Competência financeira</span><h2>{{ $period->label }}</h2><p>Período de {{ $period->starts_on->format('d/m/Y') }} a {{ $period->ends_on->format('d/m/Y') }}.</p></div>
        <x-status-badge :status="$period->status" />
    </section>

    <section class="panel">
        <div class="panel-heading"><div><span class="eyebrow">Processamento</span><h3>Lotes da competência</h3></div></div>
        <div class="table-wrap" role="region" aria-label="Lotes da competência" tabindex="0">
            <table>
                <caption class="sr-only">Lotes financeiros da competência {{ $period->label }}</caption>
                <thead><tr><th scope="col">Lote</th><th scope="col">Composição</th><th scope="col" class="align-right">Bruto</th><th scope="col" class="align-right">Bônus</th><th scope="col" class="align-right">Ajustes</th><th scope="col" class="align-right">Valor oficial</th><th scope="col">Situação</th><th scope="col"><span class="sr-only">Ações</span></th></tr></thead>
                <tbody>
                    @foreach ($period->lots as $lot)
                        <tr>
                            <td><div class="stacked-cell"><strong>{{ $lot->code }}</strong><small>Versão {{ $lot->lock_version }}</small></div></td>
                            <td>{{ $lot->items_count }} consultores · {{ $lot->production_count }} produções</td>
                            <td class="align-right money-cell">R$ {{ number_format($lot->gross_amount, 2, ',', '.') }}</td>
                            <td class="align-right money-cell positive">R$ {{ number_format($lot->bonus_amount, 2, ',', '.') }}</td>
                            <td class="align-right money-cell {{ $lot->adjustment_amount < 0 ? 'negative' : 'positive' }}">R$ {{ number_format($lot->adjustment_amount, 2, ',', '.') }}</td>
                            <td class="align-right money-cell emphasis">R$ {{ number_format($lot->official_amount, 2, ',', '.') }}</td>
                            <td><x-status-badge :status="$lot->status" /></td>
                            <td class="align-right"><a class="button button-ghost button-small" href="{{ route('lots.show', $lot) }}">Conferir</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    @if ($period->status === 'closed')
        <section class="completion-panel"><span class="completion-icon">✓</span><div><strong>Competência encerrada</strong><p>Fechada por {{ $period->closedBy?->name ?? 'Sistema' }} em {{ $period->closed_at?->format('d/m/Y H:i') }}.</p></div></section>
    @endif
@endsection
