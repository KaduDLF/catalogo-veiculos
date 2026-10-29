@extends('layouts.app')
@section('content')
    <main>
        <section class="offers" id="veiculos">
            <div class="container">
                @if ($promocoes->isNotEmpty())
                    <section class="offers__section" id="promocoes" aria-labelledby="promocoes-titulo">
                        <h1 id="promocoes-titulo">VEÍCULOS EM PROMOÇÃO</h1>
                        <div class="vehicle-grid">
                            @foreach ($promocoes as $veiculo)
                                @include('home.partials.vehicle-card', ['veiculo' => $veiculo])
                            @endforeach
                        </div>
                    </section>
                @endif

                @if ($recentes->isNotEmpty())
                    <section class="offers__section" id="recentes" aria-labelledby="recentes-titulo">
                        @if ($promocoes->isEmpty())
                            <h1 id="recentes-titulo">VEÍCULOS RECENTES</h1>
                        @else
                            <h2 class="offers__title" id="recentes-titulo">VEÍCULOS RECENTES</h2>
                        @endif
                        <div class="vehicle-grid">
                            @foreach ($recentes as $veiculo)
                                @include('home.partials.vehicle-card', ['veiculo' => $veiculo])
                            @endforeach
                        </div>
                    </section>
                @elseif ($promocoes->isEmpty())
                    <h1>VEÍCULOS RECENTES</h1>
                    <p class="offers__empty">Nenhum veículo disponível no momento. Entre em contato para saber das novidades.</p>
                @endif

                <div class="offers__cta">
                    <a href="{{ route('veiculos.index') }}" class="stock-button">Veja todo nosso estoque <span>⌕</span></a>
                </div>
            </div>
        </section>
    </main>

@endsection
