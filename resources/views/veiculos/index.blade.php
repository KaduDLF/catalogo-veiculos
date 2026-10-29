@extends('layouts.app')
@section('title', 'Estoque | L2 Veículos')
@section('content')
<main class="container catalog">
    <form class="catalog-filters" method="get" action="{{ route('veiculos.index') }}" id="catalog-filters">
        <input type="hidden" name="q" value="{{ $filters['q'] ?? '' }}">
        <input type="hidden" name="visualizacao" value="{{ $filters['visualizacao'] ?? 'grade' }}" id="view-mode">
        <label class="sr-only" for="filtro">Filtros</label>
        <select name="filtro" id="filtro"><option value="">Todos os veículos</option><option value="promocoes" @selected(($filters['filtro'] ?? '') === 'promocoes')>Em promoção</option></select>
        <label class="sr-only" for="marca">Marca</label>
        <select name="marca" id="marca"><option value="">Marcas</option>@foreach($marcas as $marca)<option @selected(($filters['marca'] ?? '') === $marca)>{{ $marca }}</option>@endforeach</select>
        <label class="sr-only" for="modelo">Modelo</label>
        <select name="modelo" id="modelo"><option value="">Modelos</option>@foreach($modelos as $modelo)<option @selected(($filters['modelo'] ?? '') === $modelo)>{{ $modelo }}</option>@endforeach</select>
        <button class="action-button" type="submit">Aplicar filtros</button>
        <a href="{{ route('veiculos.index') }}">Limpar filtros</a>
    </form>
    <section class="catalog-results" aria-label="Veículos disponíveis">
        <div class="catalog-toolbar">
            <h1>MOSTRANDO {{ $veiculos->firstItem() ?? 0 }} - {{ $veiculos->lastItem() ?? 0 }} DE {{ $veiculos->total() }} VEÍCULOS.</h1>
            <label class="sr-only" for="ordem">Ordenar por</label>
            <select name="ordem" id="ordem" form="catalog-filters"><option value="recentes">Mais recentes</option><option value="menor" @selected(($filters['ordem'] ?? '') === 'menor')>Menor preço</option><option value="maior" @selected(($filters['ordem'] ?? '') === 'maior')>Maior preço</option></select>
            <div class="view-buttons">
                <button type="button" data-view="lista" aria-label="Visualizar em lista" aria-pressed="{{ ($filters['visualizacao'] ?? '') === 'lista' ? 'true' : 'false' }}"><span class="view-icon view-icon--list" aria-hidden="true">☷</span></button>
                <button type="button" data-view="grade" aria-label="Visualizar em grade" aria-pressed="{{ ($filters['visualizacao'] ?? 'grade') === 'grade' ? 'true' : 'false' }}"><span class="view-icon" aria-hidden="true">▦</span></button>
            </div>
        </div>
        <div class="catalog-cards" data-mode="{{ $filters['visualizacao'] ?? 'grade' }}">
            @forelse($veiculos as $veiculo)
                <article class="catalog-card">
                    <a class="catalog-photo" href="{{ route('veiculos.show', $veiculo) }}" aria-label="Ver {{ $veiculo->nome }}">
                        @if($veiculo->foto)<img src="{{ asset($veiculo->foto) }}" alt="{{ $veiculo->nome }}" loading="lazy">@else<span>FOTO DO VEÍCULO</span>@endif
                        @if($veiculo->promocao_ativa)<span class="vehicle-card__badge">PROMOÇÃO</span>@endif
                    </a>
                    <div class="catalog-card-body">
                        <h2>{{ $veiculo->nome }}</h2>
                        <p class="vehicle-version">{{ $veiculo->versao }}</p>
                        <p class="vehicle-summary">{{ $veiculo->ano }} • {{ number_format($veiculo->km, 0, ',', '.') }} km • {{ $veiculo->cambio }}</p>
                        @include('veiculos.price')
                        <dl class="vehicle-specs">@foreach(['Ano' => $veiculo->ano, 'km' => number_format($veiculo->km, 0, ',', '.'), 'Cor' => $veiculo->cor ?? 'Não informada', 'Câmbio' => $veiculo->cambio] as $label => $value)<div><dt>{{ $label }}</dt><dd>{{ $value }}</dd></div>@endforeach</dl>
                        <div class="vehicle-actions"><a class="action-button" href="{{ route('veiculos.show', $veiculo) }}">＋ MAIS DETALHES</a><a class="action-button action-button--interest" href="{{ $veiculo->whatsapp_url }}" target="_blank" rel="noopener noreferrer"><img src="{{ asset('images/icons/icon-wpp.png') }}" alt=""> TENHO INTERESSE</a></div>
                    </div>
                </article>
            @empty
                <p>Nenhum veículo encontrado. Experimente outros filtros.</p>
            @endforelse
        </div>
        <nav class="catalog-pagination" aria-label="Paginação">
            @if($veiculos->previousPageUrl())<a href="{{ $veiculos->previousPageUrl() }}">← Anterior</a>@endif
            @if($veiculos->hasPages())<span>Página {{ $veiculos->currentPage() }} de {{ $veiculos->lastPage() }}</span>@endif
            @if($veiculos->nextPageUrl())<a href="{{ $veiculos->nextPageUrl() }}">Próxima →</a>@endif
        </nav>
    </section>
</main>
@endsection
