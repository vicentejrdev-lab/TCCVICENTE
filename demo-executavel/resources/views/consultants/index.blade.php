@extends('layouts.app', ['title' => 'Consultores'])

@section('content')
    <section class="section-heading">
        <div><span class="eyebrow">Rede comercial fictícia</span><h2>Consultores e estruturas</h2><p>Os 20 registros reproduzem os perfis de consultor, liderança e gestão utilizados no treinamento.</p></div>
    </section>

    <section class="panel">
        <form class="filter-bar" method="GET" action="{{ route('consultants.index') }}">
            <label class="search-field"><span class="sr-only">Pesquisar</span><input name="q" value="{{ $search }}" placeholder="Pesquisar por nome ou código"><span>⌕</span></label>
            <label class="select-field"><span class="sr-only">Perfil</span><select name="profile"><option value="">Todos os perfis</option><option value="consultant" @selected($profile === 'consultant')>Consultor</option><option value="leader" @selected($profile === 'leader')>Líder</option><option value="manager" @selected($profile === 'manager')>Gestor</option></select></label>
            <button class="button button-primary button-small" type="submit">Aplicar filtros</button>
            @if ($search !== '' || $profile !== '')<a class="button button-ghost button-small" href="{{ route('consultants.index') }}">Limpar</a>@endif
        </form>

        <div class="table-wrap" role="region" aria-label="Lista de consultores" tabindex="0">
            <table>
                <caption class="sr-only">Consultores da rede comercial fictícia</caption>
                <thead><tr><th scope="col">Consultor</th><th scope="col">Perfil</th><th scope="col">Regional</th><th scope="col" class="align-right">Produções</th><th scope="col" class="align-right">Associados</th><th scope="col" class="align-right">Premiação</th><th scope="col">Situação</th></tr></thead>
                <tbody>
                    @forelse ($consultants as $consultant)
                        <tr>
                            <td><div class="identity-cell"><span class="mini-avatar">{{ mb_substr($consultant->full_name, 0, 1) }}</span><div><strong>{{ $consultant->full_name }}</strong><small>{{ $consultant->external_code }}</small></div></div></td>
                            <td>{{ ['consultant' => 'Consultor', 'leader' => 'Líder', 'manager' => 'Gestor'][$consultant->profile] ?? ucfirst($consultant->profile) }}</td>
                            <td>{{ $consultant->regional }}</td>
                            <td class="align-right number-cell">{{ $consultant->productions_count }}</td>
                            <td class="align-right number-cell">{{ $consultant->associates_count }}</td>
                            <td class="align-right money-cell">R$ {{ number_format($consultant->payment_total ?? 0, 2, ',', '.') }}</td>
                            <td><x-status-badge :status="$consultant->active ? 'active' : 'inactive'" /></td>
                        </tr>
                    @empty
                        <tr><td colspan="7"><div class="empty-state compact">Nenhum consultor encontrado para os filtros informados.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <x-pagination :paginator="$consultants" />
    </section>
@endsection
