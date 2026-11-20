@extends('layouts.app', ['title' => 'Lote '.$lot->code])

@section('content')
    <nav class="breadcrumbs"><a href="{{ route('periods.index') }}">Competências</a><span>/</span><a href="{{ route('periods.show', $lot->period) }}">{{ $lot->period->label }}</a><span>/</span><strong>{{ $lot->code }}</strong></nav>

    <section class="lot-header">
        <div><span class="eyebrow">Fechamento de premiações</span><h2>{{ $lot->code }}</h2><p>{{ $lot->item_count }} consultores e {{ $lot->production_count }} produções consolidadas na competência {{ $lot->period->label }}.</p></div>
        <x-status-badge :status="$lot->status" class="badge-large" />
    </section>

    <section class="financial-cards">
        <article><small>Valor bruto</small><strong>R$ {{ number_format($lot->gross_amount, 2, ',', '.') }}</strong><span>produção consolidada</span></article>
        <article><small>Bonificações</small><strong class="positive">R$ {{ number_format($lot->bonus_amount, 2, ',', '.') }}</strong><span>regras do cenário</span></article>
        <article><small>Ajustes</small><strong class="{{ $lot->adjustment_amount < 0 ? 'negative' : 'positive' }}">R$ {{ number_format($lot->adjustment_amount, 2, ',', '.') }}</strong><span>correções registradas</span></article>
        <article class="official-card"><small>Valor oficial</small><strong>R$ {{ number_format($lot->official_amount, 2, ',', '.') }}</strong><span>resultado do lote</span></article>
    </section>

    <div class="content-grid lot-content-grid">
        <section class="panel">
            <div class="panel-heading"><div><span class="eyebrow">Composição</span><h3>Valores por consultor</h3></div><span class="muted">{{ $lot->items->count() }} itens</span></div>
            <div class="table-wrap" role="region" aria-label="Composição do lote por consultor" tabindex="0">
                <table>
                    <caption class="sr-only">Valores do lote por consultor</caption>
                    <thead><tr><th scope="col">Consultor</th><th scope="col" class="align-right">Produções</th><th scope="col" class="align-right">Bruto</th><th scope="col" class="align-right">Bônus</th><th scope="col" class="align-right">Ajuste</th><th scope="col" class="align-right">Oficial</th><th scope="col">Situação</th></tr></thead>
                    <tbody>
                        @foreach ($lot->items as $item)
                            <tr>
                                <td><div class="stacked-cell"><strong>{{ $item->consultant->full_name }}</strong><small>{{ $item->consultant->external_code }}</small></div></td>
                                <td class="align-right number-cell">{{ $item->production_count }}</td>
                                <td class="align-right money-cell">R$ {{ number_format($item->gross_amount, 2, ',', '.') }}</td>
                                <td class="align-right money-cell positive">R$ {{ number_format($item->bonus_amount, 2, ',', '.') }}</td>
                                <td class="align-right money-cell {{ $item->adjustment_amount < 0 ? 'negative' : '' }}">R$ {{ number_format($item->adjustment_amount, 2, ',', '.') }}</td>
                                <td class="align-right money-cell emphasis">R$ {{ number_format($item->official_amount, 2, ',', '.') }}</td>
                                <td><x-status-badge :status="$item->status" /></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <aside class="lot-side">
            @if ($lot->status === 'open')
                <section class="action-panel">
                    <span class="eyebrow">Ação disponível</span>
                    <h3>Fechar este lote</h3>
                    <p>O fechamento preservará os valores, identificará o responsável e registrará o evento de auditoria.</p>
                    <form method="POST" action="{{ route('lots.close', $lot) }}" data-confirm="Confirma o fechamento deste lote fictício?">
                        @csrf
                        <label class="field"><span>Motivo do fechamento</span><textarea name="reason" rows="4" required minlength="10" maxlength="500" placeholder="Ex.: Valores conferidos no cenário acadêmico.">{{ old('reason', 'Valores conferidos no cenário acadêmico.') }}</textarea></label>
                        <button class="button button-primary button-block" type="submit">Confirmar fechamento</button>
                    </form>
                </section>
            @else
                <section class="completion-panel vertical"><span class="completion-icon">✓</span><div><strong>Lote fechado</strong><p>Responsável: {{ $lot->closedBy?->name }}</p><p>{{ $lot->closed_at?->format('d/m/Y H:i') }}</p></div></section>
                <section class="detail-list">
                    <div><small>Motivo</small><strong>{{ $lot->close_reason }}</strong></div>
                    <div><small>Versão</small><strong>{{ $lot->lock_version }}</strong></div>
                    <div><small>Identificador de integridade (checksum)</small><code title="{{ $lot->checksum }}">{{ \Illuminate\Support\Str::limit($lot->checksum, 20) }}</code></div>
                </section>
            @endif

            <section class="panel audit-panel">
                <div class="panel-heading"><div><span class="eyebrow">Histórico</span><h3>Auditoria</h3></div></div>
                <div class="timeline">
                    @forelse ($lot->events as $event)
                        <article class="timeline-item"><span class="timeline-dot"></span><div><strong>{{ $event->description }}</strong><small>{{ $event->user?->name ?? 'Sistema' }} · {{ $event->created_at->format('d/m/Y H:i') }}</small><span>{{ ['open' => 'Em aberto', 'closed' => 'Fechado'][$event->from_status] ?? ucfirst($event->from_status) }} → {{ ['open' => 'Em aberto', 'closed' => 'Fechado'][$event->to_status] ?? ucfirst($event->to_status) }}</span></div></article>
                    @empty
                        <div class="empty-state compact">Nenhum evento registrado. O histórico será criado no fechamento.</div>
                    @endforelse
                </div>
            </section>
        </aside>
    </div>
@endsection
