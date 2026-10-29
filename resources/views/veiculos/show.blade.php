@extends('layouts.app')
@section('title', $veiculo->nome.' | L2 Veículos')
@section('content')
<main class="container vehicle-detail">
    <a class="back-link" href="{{ route('veiculos.index') }}">← Voltar ao estoque</a>
    <div class="detail-heading"><h1>{{ $veiculo->nome }} {{ $veiculo->ano }}</h1>@include('veiculos.price')</div>
    <div class="detail-grid">
        <div>
            <section class="gallery" aria-label="Fotos do veículo">
                <div class="gallery-main">
                    @forelse($fotos as $foto)<img src="{{ asset($foto) }}" alt="{{ $veiculo->nome }} — foto {{ $loop->iteration }}" data-gallery-image @if(!$loop->first) hidden @endif>@empty<span>FOTO PRINCIPAL DO VEÍCULO</span>@endforelse
                    @if($fotos->count() > 1)<button type="button" class="gallery-prev" aria-label="Foto anterior">←</button><button type="button" class="gallery-next" aria-label="Próxima foto">→</button>@endif
                </div>
                @if($fotos->isNotEmpty())<div class="gallery-thumbnails">@foreach($fotos as $foto)<button type="button" data-photo="{{ $loop->index }}" aria-label="Mostrar foto {{ $loop->iteration }}" aria-pressed="{{ $loop->first ? 'true' : 'false' }}"><img src="{{ asset($foto) }}" alt="" loading="lazy"></button>@endforeach</div>@endif
            </section>
            <section class="detail-panel"><h2>Descrição</h2><p class="description">{{ $veiculo->descricao ?: 'Entre em contato para mais informações sobre este veículo.' }}</p></section>
            <section class="detail-panel"><h2>Opcionais</h2><ul class="option-tags">@forelse($veiculo->opcionais ?? [] as $opcional)<li>{{ $opcional }}</li>@empty<li>Não informados</li>@endforelse</ul></section>
        </div>
        <aside class="detail-panel vehicle-information"><h2>Informações do veículo</h2>
            <dl>@foreach(['Ano' => $veiculo->ano, 'Quilometragem' => number_format($veiculo->km, 0, ',', '.').' km', 'Câmbio' => $veiculo->cambio, 'Combustível' => $veiculo->combustivel ?? 'Não informado', 'Cor' => $veiculo->cor ?? 'Não informada'] as $label => $value)<div><dt>{{ $label }}</dt><dd>{{ $value }}</dd></div>@endforeach</dl>
            <a class="action-button action-button--interest" href="{{ $veiculo->whatsapp_url }}" target="_blank" rel="noopener noreferrer"><img src="{{ asset('images/icons/icon-wpp.png') }}" alt=""> Tenho interesse</a>
        </aside>
    </div>
</main>
@endsection
