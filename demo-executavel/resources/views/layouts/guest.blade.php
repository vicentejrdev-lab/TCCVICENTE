<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Acesso' }} · Core One</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="stylesheet" href="{{ asset('css/coreone.css') }}?v={{ filemtime(public_path('css/coreone.css')) }}">
</head>
<body class="guest-body">
    <main class="guest-shell">
        <section class="guest-intro">
            <div class="brand brand-large">
                <span class="brand-mark" aria-hidden="true">C1</span>
                <span class="brand-copy"><strong>CORE ONE</strong><small>Demonstração acadêmica</small></span>
            </div>
            <div>
                <span class="eyebrow eyebrow-light">Engenharia de Software</span>
                <h1>Processos financeiros explicáveis, rastreáveis e centralizados.</h1>
                <p>Explore um cenário integralmente fictício, acompanhe a competência e realize o fechamento de um lote em um ambiente isolado.</p>
            </div>
            <div class="guest-metrics">
                <div><strong>20</strong><span>consultores</span></div>
                <div><strong>170</strong><span>produções</span></div>
                <div><strong>300</strong><span>associados</span></div>
            </div>
        </section>
        <section class="guest-card">
            @yield('content')
        </section>
    </main>
</body>
</html>
