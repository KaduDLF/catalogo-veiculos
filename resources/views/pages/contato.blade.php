@extends('layouts.app')
@section('title', 'Contato | L2 Veículos')
@section('content')
<main class="container institutional contact-page">
    <section aria-labelledby="contact-title">
        <h1 class="institutional-title" id="contact-title">Contato</h1>
        <div class="contact-card">
            <section><h2><img src="{{ asset('images/icons/icon-location.png') }}" alt=""> Endereço</h2><p>Rua Frutuoso Alves Guimarães<br>Centro — Rialma — GO</p></section>
            <section><h2><img src="{{ asset('images/icons/icon-wpp.png') }}" alt=""> Telefone e WhatsApp</h2><p><a href="https://wa.me/5562985350837" target="_blank" rel="noopener noreferrer">(62) 98535-0837</a><br><a href="https://wa.me/5562996413235" target="_blank" rel="noopener noreferrer">(62) 99641-3235</a></p></section>
            <section><h2><img src="{{ asset('images/icons/icon-clock.png') }}" alt=""> Horário de atendimento</h2><p>Segunda a sexta: 8h às 18h<br>Sábado: 8h às 12h</p></section>
        </div>
    </section>
    <section aria-labelledby="form-title">
        <h2 class="institutional-title" id="form-title">Fale conosco</h2>
        <p class="contact-lead">Tire suas dúvidas ou saiba mais sobre nossos veículos.</p>
        @if(session('status'))<p class="form-success" role="status">{{ session('status') }}</p>@endif
        @if($errors->any())<div class="form-errors" role="alert"><strong>Confira os campos abaixo:</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        <form class="contact-form" method="post" action="{{ route('contato.store') }}">
            @csrf
            <label>Nome completo<input name="nome" autocomplete="name" placeholder="Como podemos chamar você?" value="{{ old('nome') }}" maxlength="120" required></label>
            <div class="contact-form-row">
                <label>E-mail<input type="email" name="email" autocomplete="email" placeholder="seuemail@exemplo.com" value="{{ old('email') }}" maxlength="254" required></label>
                <label>Telefone / WhatsApp<input type="tel" name="telefone" autocomplete="tel" placeholder="(62) 99999-9999" value="{{ old('telefone') }}" maxlength="25" required></label>
            </div>
            <label>Assunto<select name="assunto" required><option value="">Selecione o assunto</option>@foreach(['Interesse em um veículo', 'Troca ou avaliação', 'Financiamento', 'Outros assuntos'] as $assunto)<option @selected(old('assunto') === $assunto)>{{ $assunto }}</option>@endforeach</select></label>
            <label>Mensagem<textarea name="mensagem" placeholder="Escreva sua mensagem..." rows="5" minlength="10" maxlength="5000" required>{{ old('mensagem') }}</textarea></label>
            <button class="action-button contact-submit" type="submit">Enviar mensagem</button>
        </form>
        <div class="contact-direct"><p>Prefere um atendimento direto?</p><a href="https://wa.me/5562985350837" class="whatsapp-outline" target="_blank" rel="noopener noreferrer"><img src="{{ asset('images/icons/icon-wpp.png') }}" alt=""> Conversar pelo WhatsApp</a></div>
    </section>
</main>
@endsection
