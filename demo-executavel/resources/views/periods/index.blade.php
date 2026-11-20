@extends('layouts.app', ['title' => 'Fechamento financeiro'])

@section('content')
    <section class="section-heading">
        <div><span class="eyebrow">Competências</span><h2>Ciclos financeiros</h2><p>Acompanhe a situação, os lotes e os valores consolidados de cada período.</p></div>
    </section>

    <section class="period-grid">
        @forelse ($periods as $period)
            <a class="period-card" href="{{ route('periods.show', $period) }}">
                <div class="period-card-top"><span class="period-calendar">{{ str_pad((string) $period->starts_on->month, 2, '0', STR_PAD_LEFT) }}<small>{{ $period->starts_on->year }}</small></span><x-status-badge :status="$period->status" /></div>
                <div><span class="eyebrow">Competência</span><h3>{{ $period->label }}</h3><p>{{ $period->starts_on->format('d/m/Y') }} a {{ $period->ends_on->format('d/m/Y') }}</p></div>
                <div class="period-card-metrics"><div><small>Lotes</small><strong>{{ $period->lots_count }}</strong></div><div><small>Valor consolidado</small><strong>R$ {{ number_format($period->official_total ?? 0, 2, ',', '.') }}</strong></div></div>
                <span class="period-card-link">Ver detalhes <b>→</b></span>
            </a>
        @empty
            <div class="empty-state">Nenhuma competência cadastrada.</div>
        @endforelse
    </section>
@endsection
