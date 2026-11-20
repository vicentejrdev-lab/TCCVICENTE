@extends('layouts.app', ['title' => 'Produções'])

@section('content')
    <section class="section-heading">
        <div><span class="eyebrow">Base de cálculo</span><h2>Produções da competência</h2><p>Registros fictícios relacionados aos consultores e utilizados na composição dos valores.</p></div>
    </section>

    <section class="summary-strip">
        <div><small>Registros filtrados</small><strong>{{ number_format($summary['count'], 0, ',', '.') }}</strong></div>
        <div><small>Valor bruto</small><strong>R$ {{ number_format($summary['base'], 2, ',', '.') }}</strong></div>
        <div><small>Bonificações</small><strong>R$ {{ number_format($summary['bonus'], 2, ',', '.') }}</strong></div>
    </section>

    <section class="panel">
        <form class="filter-bar" method="GET" action="{{ route('productions.index') }}">
            <label class="search-field grow"><span class="sr-only">Pesquisar</span><input name="q" value="{{ $search }}" placeholder="Pesquisar veículo, consultor ou código"><span>⌕</span></label>
            <button class="button button-primary button-small" type="submit">Pesquisar</button>
            @if ($search !== '')<a class="button button-ghost button-small" href="{{ route('productions.index') }}">Limpar</a>@endif
        </form>
        <div class="table-wrap" role="region" aria-label="Lista de produções" tabindex="0">
            <table>
                <caption class="sr-only">Produções utilizadas na composição dos valores</caption>
                <thead><tr><th scope="col">Registro</th><th scope="col">Contrato</th><th scope="col">Consultor</th><th scope="col">Competência</th><th scope="col" class="align-right">Valor bruto</th><th scope="col" class="align-right">Bônus</th><th scope="col">Situação</th></tr></thead>
                <tbody>
                    @forelse ($productions as $production)
                        <tr>
                            <td><strong>{{ $production->external_code }}</strong></td>
                            <td>{{ $production->contract_code }}</td>
                            <td><div class="stacked-cell"><strong>{{ $production->consultant->full_name }}</strong><small>{{ $production->consultant->external_code }}</small></div></td>
                            <td>{{ $production->competence }}</td>
                            <td class="align-right money-cell">R$ {{ number_format($production->gross_amount, 2, ',', '.') }}</td>
                            <td class="align-right money-cell positive">R$ {{ number_format($production->bonus_amount, 2, ',', '.') }}</td>
                            <td><x-status-badge :status="$production->status" /></td>
                        </tr>
                    @empty
                        <tr><td colspan="7"><div class="empty-state compact">Nenhuma produção encontrada.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <x-pagination :paginator="$productions" />
    </section>
@endsection
