<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>L2 Veículos</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>
<body>
    <header class="top-header">
        <div class="container top-header__inner">
            <div class="brand">
                
                    <img src="{{ asset('images/logo/LogoL2.png') }}" alt="Logo L" width="341" height="165">
                
            </div>

            <div class="top-header__info">
                <div class="info-block info-block--address">
                    <strong>Endereço</strong>
                    <span>Rua Frutuoso Alves Guimarães</span>
                    <span>Centro - Rialma - GO</span>
                </div>

                <div class="top-header__divider"></div>

                <div class="info-block">
                    <strong>Telefone</strong>
                    <a class="phone-row"
                    href="https://wa.me/5562985350837"
                    target="_blank"
                    rel="noopener noreferrer">
                        <img src="{{ asset('images/icons/icon-wpp.png') }}" alt="WhatsApp">
                        <span>(62) 98535-0837</span>
                    </a>

                    <a class="phone-row"
                    href="https://wa.me/5562996413235"
                    target="_blank"
                    rel="noopener noreferrer">
                        <img src="{{ asset('images/icons/icon-wpp.png') }}" alt="WhatsApp">
                        <span>(62) 99641-3235</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <nav class="main-nav">
        <div class="container main-nav__inner">
            <div class="main-nav__links">
                <a href="#" class="nav-link nav-link--active">
                    <img src="{{ asset('images/icons/icon-home.png') }}" alt="Home" class="nav-icon">
                    <span>Home</span>
                </a>

                <span class="nav-separator"></span>

                <a href="#veiculos" class="nav-link">
                    <img src="{{ asset('images/icons/icon-car.png') }}" alt="Veículos" class="nav-icon">
                    <span>Veículos</span>
                </a>

                <span class="nav-separator"></span>

                <a href="#empresa" class="nav-link">
                    <img src="{{ asset('images/icons/icon-location.png') }}" alt="Empresa" class="nav-icon">
                    <span>Empresa</span>
                </a>

                <span class="nav-separator"></span>

                <a href="#contato" class="nav-link">
                    <img src="{{ asset('images/icons/icon-mail.png') }}" alt="Contato" class="nav-icon">
                    <span>Contato</span>
                </a>
            </div>

            <form class="quick-search" action="#" method="get">
                <input type="search" name="q" placeholder="Busca Rápida de Veículos" aria-label="Busca rápida de veículos">
                <button type="submit" aria-label="Pesquisar">⌕</button>
            </form>
        </div>
    </nav>

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
                    <a href="#" class="stock-button">Veja todo nosso estoque <span>⌕</span></a>
                </div>
            </div>
        </section>
    </main>

    <footer id="contato">
        <div class="footer-highlight">
            <div class="container footer-highlight__inner">

                <div class="footer-highlight__item">
                    <img
                        src="{{ asset('images/icons/icon-location.png') }}"
                        alt="Localização"
                        class="footer-highlight__icon"
                    >

                    <div>
                        <strong>Rua Frutuoso Alves Guimarães</strong>
                        <strong>Centro - Rialma - GO</strong>
                    </div>
                </div>

                <div class="footer-highlight__item">
                    <img
                        src="{{ asset('images/icons/icon-clock.png') }}"
                        alt="Horário"
                        class="footer-highlight__icon"
                    >

                    <div>
                        <strong>Segunda à Sexta: 8hs às 18hs</strong>
                        <strong>Sábado: 8hs às 12:00</strong>
                    </div>
                </div>

            </div>
        </div>

        <div class="footer-main">
            <div class="container footer-grid">
                <div class="footer-column">
                    <h3>Mapa do Site</h3>
                    <span class="footer-line"></span>
                    <a href="#">› Home</a>
                    <a href="#veiculos">› Veículos</a>
                    <a href="#empresa">› Empresa</a>
                    <a href="#contato">› Contato</a>
                </div>

                <div class="footer-column">
                    <h3>Endereço</h3>
                    <span class="footer-line"></span>
                    <p>Rua Frutuoso Alves Guimarães<br>Centro - Rialma - GO</p>
                    <h3 class="footer-column__subheading">Horário</h3>
                    <span class="footer-line"></span>
                    <p>Segunda à Sexta: 8hs às 18hs<br>Sábado: 8hs às 12:00</p>
                </div>

                <div class="footer-column">
                    <h3>Contato</h3>
                    <span class="footer-line"></span>
                    <p>(62) 98535-0837<br>(62) 98535-0837<br>l2veiculos@gmail.com</p>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>Desenvolvido por <span>Carlos Eduardo</span></p>
        </div>
    </footer>
</body>
</html>
