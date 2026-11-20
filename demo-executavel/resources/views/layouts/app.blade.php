<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Core One' }} · Demonstração acadêmica</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="stylesheet" href="{{ asset('css/coreone.css') }}?v={{ filemtime(public_path('css/coreone.css')) }}">
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar" id="sidebar" aria-label="Navegação principal">
            <div class="brand">
                <span class="brand-mark" aria-hidden="true">C1</span>
                <span class="brand-copy">
                    <strong>CORE ONE</strong>
                    <small>Demonstração acadêmica</small>
                </span>
            </div>

            <nav class="sidebar-nav">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}" href="{{ route('dashboard') }}">
                    <span class="nav-icon" aria-hidden="true">⌂</span><span>Painel</span>
                </a>
                <a class="nav-link {{ request()->routeIs('periods.*', 'lots.*') ? 'is-active' : '' }}" href="{{ route('periods.index') }}">
                    <span class="nav-icon" aria-hidden="true">▣</span><span>Fechamento</span>
                </a>
                <a class="nav-link {{ request()->routeIs('consultants.*') ? 'is-active' : '' }}" href="{{ route('consultants.index') }}">
                    <span class="nav-icon" aria-hidden="true">◎</span><span>Consultores</span>
                </a>
                <a class="nav-link {{ request()->routeIs('productions.*') ? 'is-active' : '' }}" href="{{ route('productions.index') }}">
                    <span class="nav-icon" aria-hidden="true">◇</span><span>Produções</span>
                </a>
                <a class="nav-link {{ request()->routeIs('unpaid-bills.*') ? 'is-active' : '' }}" href="{{ route('unpaid-bills.index') }}">
                    <span class="nav-icon" aria-hidden="true">▤</span><span>Boletos pendentes</span>
                </a>
            </nav>

            <div class="sidebar-note">
                <span class="status-dot"></span>
                <div><strong>Ambiente fictício</strong><small>Dados isolados para avaliação</small></div>
            </div>
        </aside>
        <button class="sidebar-backdrop" type="button" data-sidebar-backdrop aria-label="Fechar menu"></button>

        <div class="page-shell">
            <header class="topbar">
                <button class="sidebar-toggle" type="button" data-sidebar-toggle aria-controls="sidebar" aria-expanded="false" aria-label="Abrir menu">☰</button>
                <div class="topbar-title">
                    <span class="eyebrow">Core One</span>
                    <h1>{{ $title ?? 'Painel' }}</h1>
                </div>
                <div class="user-menu">
                    <span class="user-avatar">{{ mb_substr(auth()->user()->name, 0, 1) }}</span>
                    <div class="user-copy"><strong>{{ auth()->user()->name }}</strong><small>Master de demonstração</small></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="button button-ghost button-small" type="submit">Sair</button>
                    </form>
                </div>
            </header>

            <main class="page-content">
                @if (session('success'))
                    <div class="alert alert-success" role="status">
                        <strong>Concluído.</strong> {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <strong>Não foi possível concluir.</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>

            <footer class="page-footer">
                <span>Core One · recorte acadêmico executável</span>
                <span>Todos os nomes, registros e valores são fictícios.</span>
            </footer>
        </div>
    </div>
    <script src="{{ asset('js/coreone.js') }}?v={{ filemtime(public_path('js/coreone.js')) }}" defer></script>
    @stack('scripts')
</body>
</html>
