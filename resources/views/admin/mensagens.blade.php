@extends('admin.layout')
@section('titulo','Mensagens')
@section('content')
<header><h1>Mensagens do site</h1></header>
@forelse($mensagens as $m)<article class="surface"><header><h2>{{ $m->assunto }}</h2><span class="badge">{{ $m->lida_em?'Lida':'Nova' }}</span></header><strong>{{ $m->nome }}</strong> · {{ $m->telefone }} · <a href="mailto:{{ $m->email }}">{{ $m->email }}</a><p>{{ \Carbon\Carbon::parse($m->created_at)->timezone('America/Sao_Paulo')->format('d/m/Y H:i') }}</p><p class="message">{{ $m->mensagem }}</p>@unless($m->lida_em)<form method="post" action="{{ route('admin.ler',$m->id) }}">@csrf<button>Marcar como lida</button></form>@endunless</article>@empty<section class="surface">Nenhuma mensagem recebida.</section>@endforelse
@include('admin.paginacao',['lista'=>$mensagens])
@endsection
