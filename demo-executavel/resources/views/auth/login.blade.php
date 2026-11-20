@extends('layouts.guest', ['title' => 'Entrar'])

@section('content')
    <div class="login-heading">
        <span class="eyebrow">Acesso ao ambiente</span>
        <h2>Bem-vindo ao Core One</h2>
        <p>Use a credencial fictícia indicada no README do projeto.</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
    @endif

    <form class="form-stack" method="POST" action="{{ route('login.store') }}">
        @csrf
        <label class="field">
            <span>E-mail ou documento fictício</span>
            <input name="login" type="text" value="{{ old('login') }}" required autofocus autocomplete="username" placeholder="Informe seu acesso">
        </label>
        <label class="field">
            <span>Senha</span>
            <input name="password" type="password" required autocomplete="current-password" placeholder="Digite sua senha">
        </label>
        <label class="checkbox-field">
            <input name="remember" type="checkbox" value="1">
            <span>Manter acesso neste navegador</span>
        </label>
        <button class="button button-primary button-block" type="submit">Entrar no ambiente</button>
    </form>

    <div class="data-notice">
        <strong>Ambiente controlado</strong>
        <p>Não há conexão com produção, serviços externos ou registros empresariais.</p>
    </div>
@endsection
