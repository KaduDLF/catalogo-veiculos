@extends('admin.layout')
@section('titulo','Login administrativo')
@section('body','login')
@section('content')
<form class="surface login-card" method="post" action="{{ route('admin.entrar') }}">
@csrf
<a class="login-brand" href="{{ route('home') }}" aria-label="Ir para a página inicial da loja">
    <img src="{{ asset('images/logo/LogoL2.png') }}" alt="L2 Veículos">
</a>
<h1>Painel da Loja</h1><p>Entre para gerenciar seu estoque.</p>
<label>E-mail<input type="email" name="email" value="{{ old('email') }}" placeholder="seu@email.com" autocomplete="username" required></label>
<label>Senha<input type="password" name="password" placeholder="••••••••" autocomplete="current-password" required></label>
<button>Entrar</button>
</form>
@endsection
