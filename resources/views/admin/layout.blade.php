<!doctype html>
<html lang="pt-BR"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>@yield('titulo') | L2 Veículos</title>
<link rel="icon" href="{{ asset('images/logo/LogoL2.png') }}">
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
<script defer src="{{ asset('js/admin.js') }}"></script>
</head><body class="@yield('body')">
@unless(request()->routeIs('login'))
<aside class="admin-sidebar"><a href="{{ route('home') }}" aria-label="Ir para a página inicial da loja"><img class="logo" src="{{ asset('images/logo/LogoL2.png') }}" alt="L2 Veículos"></a>
<button type="button" id="admin-menu-toggle" aria-label="Abrir menu" aria-controls="admin-menu" aria-expanded="false">@include('admin.icon',['icon'=>'menu'])</button>
<div id="admin-menu"><nav aria-label="Menu administrativo">@foreach(['dashboard'=>'Dashboard','veiculos.index'=>'Veículos','mensagens'=>'Mensagens','config'=>'Configurações'] as $rota=>$label)
<a href="{{ route('admin.'.$rota) }}" @if(request()->routeIs($rota==='veiculos.index'?'admin.veiculos.*':'admin.'.$rota)) aria-current="page" @endif>@include('admin.icon',['icon'=>explode('.', $rota)[0]]) {{ $label }}</a>
@endforeach</nav>
<form method="post" action="{{ route('admin.sair') }}">@csrf<button class="logout">Sair</button></form></div></aside>
@endunless
<main>
@if(session('ok'))<p class="success" role="status">{{ session('ok') }}</p>@endif
@if($errors->any())<div class="errors" role="alert">@foreach($errors->all() as $erro)<div>{{ $erro }}</div>@endforeach</div>@endif
@yield('content')</main></body></html>
