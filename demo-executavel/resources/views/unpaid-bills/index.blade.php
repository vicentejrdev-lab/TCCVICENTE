@extends('layouts.app', ['title' => 'Boletos pendentes'])

@section('content')
    <section class="section-heading">
        <div><span class="eyebrow">Carteira de acompanhamento</span><h2>Boletos e situações</h2><p>Casos fictícios preservados para demonstrar consulta, filtro e rastreabilidade operacional.</p></div>
    </section>

    <section class="summary-strip">
        <div><small>Registros filtrados</small><strong>{{ number_format($summary['count'], 0, ',', '.') }}</strong></div>
        <div><small>Valor total</small><strong>R$ {{ number_format($summary['amount'], 2, ',', '.') }}</strong></div>
        <div><small>Documentos disponíveis</small><strong>{{ $summary['withPdf'] }}</strong></div>
    </section>

    <section class="panel">
        <form class="filter-bar" method="GET" action="{{ route('unpaid-bills.index') }}">
            <label class="search-field grow"><span class="sr-only">Pesquisar</span><input name="q" value="{{ $search }}" placeholder="Pesquisar associado, consultor ou referência"><span>⌕</span></label>
            <label class="select-field"><span class="sr-only">Situação</span><select name="status"><option value="">Todas as situações</option><option value="pending" @selected($status === 'pending')>Pendente</option><option value="sent" @selected($status === 'sent')>Enviado</option><option value="paid" @selected($status === 'paid')>Pago</option></select></label>
            <button class="button button-primary button-small" type="submit">Aplicar</button>
        </form>
        <div class="table-wrap" role="region" aria-label="Lista de boletos" tabindex="0">
            <table>
                <caption class="sr-only">Boletos fictícios para acompanhamento</caption>
                <thead><tr><th scope="col">Referência</th><th scope="col">Associado</th><th scope="col">Consultor</th><th scope="col">Vencimento</th><th scope="col" class="align-right">Valor</th><th scope="col">Documento</th><th scope="col">Situação</th></tr></thead>
                <tbody>
                    @forelse ($bills as $bill)
                        <tr>
                            <td><strong>{{ $bill->reference }}</strong></td>
                            <td><div class="stacked-cell"><strong>{{ $bill->associate->full_name }}</strong><small>{{ $bill->associate->external_code }}</small></div></td>
                            <td>{{ $bill->consultant->full_name }}</td>
                            <td>{{ $bill->due_date->format('d/m/Y') }}</td>
                            <td class="align-right money-cell">R$ {{ number_format($bill->amount, 2, ',', '.') }}</td>
                            <td>@if ($bill->has_pdf)<span class="document-chip">PDF disponível</span>@else<span class="muted">Sem PDF</span>@endif</td>
                            <td><x-status-badge :status="$bill->status" /></td>
                        </tr>
                    @empty
                        <tr><td colspan="7"><div class="empty-state compact">Nenhum boleto encontrado.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <x-pagination :paginator="$bills" />
    </section>
@endsection
