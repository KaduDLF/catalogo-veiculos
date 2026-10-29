<article class="vehicle-card" data-veiculo-id="{{ $veiculo->id }}">
    <div class="vehicle-card__image {{ $veiculo->foto ? '' : 'vehicle-card__image--placeholder' }}">
        @if ($veiculo->foto)
            <img src="{{ asset($veiculo->foto) }}" alt="{{ $veiculo->nome }}" loading="lazy">
        @else
            <span>FOTO DO VEÍCULO</span>
        @endif
        @if ($veiculo->promocao_ativa)
            <span class="vehicle-card__badge">PROMOÇÃO</span>
        @endif
    </div>
    <div class="vehicle-card__content">
        <h3><a href="{{ route('veiculos.show', $veiculo) }}">{{ $veiculo->nome }}</a></h3>
        <p>{{ $veiculo->ano }} <span>•</span> {{ number_format($veiculo->km, 0, ',', '.') }} km <span>•</span> {{ $veiculo->cambio }}</p>
    </div>
    <div class="vehicle-card__price">
        @if ($veiculo->promocao_ativa)
            <del class="vehicle-card__old-price">De R$ {{ number_format($veiculo->preco, 2, ',', '.') }}</del>
            <span>Por R$ {{ number_format($veiculo->preco_promocional, 2, ',', '.') }}</span>
        @else
            <span>R$ {{ number_format($veiculo->preco, 2, ',', '.') }}</span>
        @endif
    </div>
</article>
