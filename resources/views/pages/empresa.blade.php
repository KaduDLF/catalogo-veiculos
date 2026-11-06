@extends('layouts.app')
@section('title', 'Empresa | L2 Veículos')
@section('content')
<main class="container institutional company-page">
    <h1 class="institutional-title">Quem <strong>Somos</strong></h1>
    <div class="company-copy">
        <p class="company-intro">REVENDA DE VEÍCULOS EM RIALMA. ATENDENDO TODA A REGIÃO COM QUALIDADE, CONFIANÇA E PROCEDÊNCIA.</p>
        <p>Na {{ $loja['nome'] }}, ajudamos você a encontrar o veículo ideal para o seu dia a dia. Localizada em Rialma–GO, nossa loja atende toda a região com um atendimento próximo e transparente, oferecendo veículos selecionados e condições que combinam com você.</p>
        <p>Venha conhecer nosso estoque, tirar suas dúvidas e conferir nossas ofertas. Nossa equipe está pronta para acompanhar você em cada etapa da compra!</p>
        <p>📍 {{ $loja['endereco'] }} — {{ $loja['cidade'] }}<br>📲 WhatsApp: <a href="{{ \App\Support\Loja::whatsapp() }}" target="_blank" rel="noopener noreferrer">{{ $loja['telefone'] }}</a> | <a href="{{ \App\Support\Loja::whatsapp('telefone2') }}" target="_blank" rel="noopener noreferrer">{{ $loja['telefone2'] }}</a></p>
    </div>
    @if(config('empresa.fotos'))
        <div class="company-gallery" aria-label="Nossa loja e entregas">
            @foreach(config('empresa.fotos') as $foto)
                <img src="{{ asset($foto['src']) }}" alt="{{ $foto['alt'] }}" loading="lazy">
            @endforeach
        </div>
    @endif
</main>
@endsection
