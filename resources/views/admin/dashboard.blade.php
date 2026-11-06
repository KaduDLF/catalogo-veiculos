@extends('admin.layout')
@section('titulo','Dashboard')
@section('content')
<header><h1>Dashboard</h1><a class="btn" href="{{ route('admin.veiculos.create') }}">+ Novo veículo</a></header>
<div class="stats">
@foreach(['estoque'=>['Em estoque',$estoque],'preparacao'=>['Em preparação',$preparacao],'vendido'=>['Vendidos no mês',$vendidos]] as $k=>[$label,$count])
<section class="surface"><a href="{{ route('admin.veiculos.index',array_filter(['status'=>$k,'mes'=>$k==='vendido'?$mes:null])) }}">{{ $label }}<strong class="count">{{ $count }}</strong></a>
@if($k==='vendido')<form class="compact"><input type="hidden" name="periodo" value="{{ $periodo }}"><input type="month" name="mes" value="{{ $mes }}" aria-label="Mês das vendas" required><button>Ver</button></form>@endif</section>@endforeach</div>
<section class="surface"><header><h2>Anúncios mais vistos</h2><form class="compact"><input type="hidden" name="mes" value="{{ $mes }}"><select aria-label="Período das visitas" name="periodo">@foreach(['7'=>'7 dias','30'=>'30 dias','tudo'=>'Todo o período'] as $k=>$v)<option value="{{ $k }}" @selected($periodo==(string)$k)>{{ $v }}</option>@endforeach</select><button>Ver</button></form></header>
@forelse($ranking as $v)<div class="row"><a href="{{ route('admin.veiculos.edit',$v) }}">{{ $v->nome }}</a><strong>{{ $v->visitas_count }} {{ $v->visitas_count==1?'visualização':'visualizações' }}</strong></div>@empty<p>Nenhuma visita registrada no período.</p>@endforelse</section>
<section class="surface"><h2>Mensagens do site</h2><a href="{{ route('admin.mensagens') }}">{{ $mensagens }} por ler · Abrir mensagens →</a>
@foreach($ultimasMensagens as $mensagem)<div class="row"><span><strong>{{ $mensagem->nome }}</strong> · {{ $mensagem->assunto }}</span><time>{{ \Carbon\Carbon::parse($mensagem->created_at)->timezone('America/Sao_Paulo')->format('d/m/Y') }}</time></div>@endforeach</section>
<section class="surface"><h2>Atualizados recentemente</h2>@forelse($recentes as $v)<div class="row"><a href="{{ route('admin.veiculos.edit',$v) }}">{{ $v->nome }}</a><span>{{ $v->status }}</span></div>@empty<p>Nenhum veículo cadastrado.</p>@endforelse</section>
@endsection
