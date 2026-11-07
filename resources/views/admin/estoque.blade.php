@extends('admin.layout')
@section('titulo','Veículos')
@section('content')
<header><h1>Veículos</h1><a class="btn" href="{{ route('admin.veiculos.create') }}">+ Novo veículo</a></header>
<form class="filters"><input type="search" name="q" value="{{ request('q') }}" placeholder="Pesquisar veículo..." aria-label="Pesquisar veículo">
<select name="status" aria-label="Status"><option value="">Todos os status</option>@foreach(['estoque'=>'Disponível','preparacao'=>'Em preparação','vendido'=>'Vendido'] as $k=>$v)<option value="{{ $k }}" @selected(request('status')===$k)>{{ $v }}</option>@endforeach</select>
@if(request('mes'))<input type="month" name="mes" aria-label="Mês da venda" value="{{ request('mes') }}">@endif
<button>Filtrar</button>@if(request()->hasAny(['q','status','mes']))<a href="{{ route('admin.veiculos.index') }}">Limpar</a>@endif</form>
<section class="surface"><h2>Estoque de veículos</h2><div class="scroll"><table><thead><tr>@foreach(['Veículo','Ano','Preço','Status','Visualizações','Ações'] as $label)<th>{{ $label }}</th>@endforeach</tr></thead>
<tbody>@forelse($veiculos as $v)<tr class="stock-row"><td>
    <a class="stock-row-link" href="{{ route('admin.veiculos.edit',$v) }}" aria-label="Editar {{ $v->nome }}">
        <span class="stock-thumbnail" aria-hidden="true">
            @if($v->foto)<img src="{{ asset($v->foto) }}" alt="" width="64" height="48" loading="lazy">
            @else @include('admin.icon',['icon'=>'veiculos']) @endif
        </span>
        <strong>{{ $v->nome }}</strong>
    </a>
</td><td>{{ $v->ano }}</td><td>R$ {{ number_format($v->promocao_ativa?$v->preco_promocional:$v->preco,2,',','.') }}</td><td><span class="badge {{ $v->disponivel?'available':'' }}">{{ $v->status }}</span></td><td>{{ $v->visitas_count }}</td><td>
    <div class="actions">
        <a class="secondary" href="{{ route('admin.veiculos.edit',$v) }}">Editar</a>
        <button type="button" class="secondary more-actions" popovertarget="acoes-{{ $v->id }}" aria-label="Mais ações para {{ $v->nome }}">•••</button>
        <div popover="auto" id="acoes-{{ $v->id }}" class="row-popover">
            @if($v->data_venda === null)
                <form method="post" action="{{ route('admin.veiculos.vender',$v) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="secondary">Marcar como vendido</button>
                </form>
            @endif
            <form method="post" action="{{ route('admin.veiculos.destroy',$v) }}" data-excluir>
                @csrf @method('DELETE')
                <button type="submit" class="danger">Excluir</button>
            </form>
        </div>
    </div>
</td></tr>
@empty<tr><td colspan="6">Nenhum veículo encontrado.</td></tr>@endforelse</tbody></table></div>@include('admin.paginacao',['lista'=>$veiculos])</section>
@endsection
