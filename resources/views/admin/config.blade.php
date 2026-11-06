@extends('admin.layout')
@section('titulo','Configurações')
@section('content')
<header><h1>Configurações</h1></header>
@php($abas=['loja'=>'Dados da loja','canais'=>'Canais de contato','anuncio'=>'Modelo de anúncio','conta'=>'Minha conta'])
@php($aba=array_key_exists((string)request('aba'),$abas)?request('aba'):'loja')
<nav class="tabs">@foreach($abas as $id=>$txt)<a href="{{ route('admin.config',['aba'=>$id]) }}" @if($id===$aba) aria-current="page" @endif>{{ $txt }}</a>@endforeach</nav>
<form class="surface" method="post" action="{{ route('admin.config.salvar',$aba) }}">@csrf @method('PUT')
<h2>{{ $abas[$aba] }}</h2>
@if($aba==='loja' || $aba==='canais')
@foreach(($aba==='loja'?['nome'=>'Nome da loja','endereco'=>'Rua e número','cidade'=>'Bairro, cidade e UF','semana'=>'Horário durante a semana','sabado'=>'Horário de sábado']:['telefone'=>'WhatsApp principal','telefone2'=>'Segundo WhatsApp','email'=>'E-mail público','instagram'=>'Instagram']) as $campo=>$rotulo)
<label>{{ $rotulo }}<input required name="{{ $campo }}" value="{{ old($campo,$loja[$campo]) }}" @if(str_starts_with($campo,'telefone')) placeholder="(62) 99999-9999" @endif></label>@endforeach
@elseif($aba==='anuncio')<label>Condições comerciais<textarea name="condicoes" rows="5" required>{{ old('condicoes',$loja['condicoes']) }}</textarea></label><h3>Prévia</h3><p class="message" id="previa-condicoes">{{ old('condicoes',$loja['condicoes']) }}</p>
@else
<label>Nome<input name="name" required value="{{ old('name',auth()->user()->name) }}"></label><label>E-mail de acesso<input type="email" name="email" required value="{{ old('email',auth()->user()->email) }}"></label>
<label>Senha atual<input type="password" autocomplete="current-password" name="current_password" required></label>
<label>Nova senha (opcional)<input type="password" autocomplete="new-password" name="password" minlength="12"></label>
<label>Confirmar nova senha<input type="password" autocomplete="new-password" name="password_confirmation"></label>
@endif<button>Salvar alterações</button></form>
@endsection
