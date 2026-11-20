@props(['paginator'])
@if ($paginator->hasPages())
    <nav class="pagination" aria-label="Paginação">
        <span>Página {{ $paginator->currentPage() }} de {{ $paginator->lastPage() }} · {{ $paginator->total() }} registros</span>
        <div>
            @if ($paginator->onFirstPage())
                <span class="button button-ghost button-small is-disabled">Anterior</span>
            @else
                <a class="button button-ghost button-small" href="{{ $paginator->previousPageUrl() }}">Anterior</a>
            @endif
            @if ($paginator->hasMorePages())
                <a class="button button-ghost button-small" href="{{ $paginator->nextPageUrl() }}">Próxima</a>
            @else
                <span class="button button-ghost button-small is-disabled">Próxima</span>
            @endif
        </div>
    </nav>
@endif
