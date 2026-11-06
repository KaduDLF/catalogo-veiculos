@extends('admin.layout')
@section('titulo',$veiculo->exists?'Editar veículo':'Cadastrar veículo')
@section('content')
@php
    $anos = explode('/', $veiculo->ano ?? '');
    $fotos = array_values(array_unique(array_filter([$veiculo->foto,...($veiculo->fotos ?? [])])));
    $status = old('status', $veiculo->disponivel?'estoque':($veiculo->data_venda?'vendido':'preparacao'));
@endphp
<header><h1>@yield('titulo')</h1></header>
<form class="surface vehicle-form" id="vehicle-form" enctype="multipart/form-data" method="post" action="{{ $veiculo->exists?route('admin.veiculos.update',$veiculo):route('admin.veiculos.store') }}" data-condicoes="{{ \App\Support\Loja::dados()['condicoes'] }}" data-hoje="{{ now('America/Sao_Paulo')->toDateString() }}">
@csrf @if($veiculo->exists) @method('PUT') @endif
<h2>Informações principais</h2>
<div class="grid">
@foreach(['marca'=>'Marca','modelo'=>'Modelo','versao'=>'Versão','fabricacao'=>'Ano fabricação','ano_modelo'=>'Ano modelo','preco'=>'Preço (R$)','km'=>'Quilometragem','cambio'=>'Câmbio','combustivel'=>'Combustível','cor'=>'Cor','status'=>'Status','nome'=>'Nome do anúncio'] as $campo=>$label)
<label>{{ $label }}
@if($campo==='status')
<select name="status">@foreach(['estoque'=>'Disponível no catálogo','preparacao'=>'Em preparação','vendido'=>'Vendido'] as $k=>$v)<option value="{{ $k }}" @selected($k===$status)>{{ $v }}</option>@endforeach</select>
@elseif($campo==='combustivel')
<select name="combustivel">
    <option value="">Selecione o combustível</option>
    @foreach($veiculo->combustiveisPermitidos() as $tipo)
        <option value="{{ $tipo }}" @selected(old('combustivel',$veiculo->combustivel)===$tipo)>{{ $tipo==='Etanol'?'Etanol (álcool)':$tipo }}</option>
    @endforeach
</select>
@else
<input name="{{ $campo }}" placeholder="{{ $campo==='nome'?'Ex.: Honda Civic EXL':'Preencher '.mb_strtolower($label) }}" value="{{ old($campo,match($campo){'fabricacao'=>$anos[0],'ano_modelo'=>$anos[1]??$anos[0],default=>$veiculo->$campo}) }}" @if(in_array($campo,['km','preco','fabricacao','ano_modelo'])) type="number" min="{{ $campo==='preco'?'0.01':'0' }}" step="{{ $campo==='preco'?'0.01':'1' }}" @endif @required(!in_array($campo,['cor','versao','combustivel']))>
@endif</label>@endforeach
<label id="sale-date">Data da venda<input type="date" name="data_venda" max="{{ now('America/Sao_Paulo')->toDateString() }}" value="{{ old('data_venda',$veiculo->data_venda) }}"></label>
</div><label>Descrição<textarea name="descricao" rows="5" placeholder="Descrição e detalhes do veículo...">{{ old('descricao',$veiculo->descricao) }}</textarea></label>
<label>Opcionais (separados por vírgula)<input name="opcionais" value="{{ old('opcionais',implode(', ',$veiculo->opcionais??[])) }}"></label>
<h2>Fotos do veículo</h2><label class="upload">Adicionar fotos<input type="file" name="imagens[]" multiple accept="image/jpeg,image/png,image/webp,image/heic,image/heif,.heic,.heif"><small>Até 15 fotos, com até 5 MB cada. Fotos HEIC/HEIF do iPhone são convertidas para JPEG.</small></label>
<h3>Galeria do veículo</h3><p class="gallery-help">Toque na lixeira sobre a foto e depois em Salvar veículo para apagá-la.</p><div id="removed-photos"></div><div id="gallery" data-existing='@json(array_map(fn ($p)=>asset($p),$fotos))' data-trash-icon="{{ asset('images/icons/admin.svg').'?v='.filemtime(public_path('images/icons/admin.svg')).'#trash' }}"></div>
<h2>Publicação e promoção</h2><div class="grid"><div class="offer-field"><span>Oferta no catálogo</span><label class="check"><input type="checkbox" name="em_promocao" value="1" @checked(old('status') ? old('em_promocao') : $veiculo->em_promocao)> Colocar em promoção</label></div>
<label>Preço promocional (R$)<input name="preco_promocional" type="number" step=".01" min=".01" value="{{ old('preco_promocional',$veiculo->preco_promocional) }}"><small>Informe um valor menor que o preço normal.</small></label></div>
<h2>Anúncio para Instagram</h2><h3>Itens confirmados neste veículo</h3>
@php($itensSelecionados = old('status') ? old('itens_confirmados', []) : ($veiculo->itens_confirmados ?? []))
<div class="actions wrap" id="ad-items">@foreach(array_unique([...\App\Models\Veiculo::ITENS, ...$itensSelecionados]) as $item)<label class="check"><input name="itens_confirmados[]" value="{{ $item }}" type="checkbox" @checked(in_array($item,$itensSelecionados))>{{ $item }}</label>@endforeach</div>
<label for="custom-ad-item">Adicionar outro item</label>
<div class="actions wrap">
    <input id="custom-ad-item" maxlength="100" placeholder="Ex.: Película instalada">
    <button type="button" id="add-ad-item">+ Adicionar item</button>
</div>
<p id="ad-item-status" role="status"></p>
<div class="actions wrap"><button type="button" id="generate">@include('admin.icon',['icon'=>'share']) Gerar texto do anúncio</button></div>
<label>Prévia do texto<textarea id="preview" rows="10"></textarea></label><button class="secondary" type="button" id="copy">@include('admin.icon',['icon'=>'copy']) Copiar texto</button><span id="copy-status" role="status"></span>
<div class="save"><button>Salvar veículo</button></div></form>
@endsection
