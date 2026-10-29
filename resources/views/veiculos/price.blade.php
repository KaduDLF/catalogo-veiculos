<div class="catalog-price">
    @if($veiculo->promocao_ativa)<del>De R$ {{ number_format($veiculo->preco, 2, ',', '.') }}</del>@endif
    <strong>R$ {{ number_format($veiculo->promocao_ativa ? $veiculo->preco_promocional : $veiculo->preco, 2, ',', '.') }}</strong>
</div>
