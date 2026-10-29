<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'L2 Veículos')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/catalogo.css') }}">
    <script src="{{ asset('js/catalogo.js') }}" defer></script>
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

    <nav class="main-nav" aria-label="Navegação principal">
        <div class="container main-nav__inner">
            <button class="nav-toggle" type="button" aria-controls="main-menu" aria-expanded="false" aria-label="Abrir menu">
                <span></span><span></span><span></span>
            </button>
            <div class="main-nav__links" id="main-menu">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'nav-link--active' : '' }}">
                    <img src="{{ asset('images/icons/icon-home.png') }}" alt="Home" class="nav-icon">
                    <span>Home</span>
                </a>

                <span class="nav-separator"></span>

                <a href="{{ route('veiculos.index') }}" class="nav-link {{ request()->routeIs('veiculos.*') ? 'nav-link--active' : '' }}">
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

            <form class="quick-search" action="{{ route('veiculos.index') }}" method="get">
                <input type="search" name="q" value="{{ request()->string('q') }}" placeholder="Busca Rápida de Veículos" aria-label="Busca rápida de veículos">
                <button type="submit" aria-label="Pesquisar">⌕</button>
            </form>
        </div>
    </nav>

    @yield('content')
    <footer>
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
                    <a href="{{ route('home') }}">› Home</a>
                    <a href="{{ route('veiculos.index') }}">› Veículos</a>
                    <a href="#empresa">› Empresa</a>
                    <a href="#contato">› Contato</a>
                </div>

                <div class="footer-column" id="empresa">
                    <h3>Endereço</h3>
                    <span class="footer-line"></span>
                    <p>Rua Frutuoso Alves Guimarães<br>Centro - Rialma - GO</p>
                    <h3 class="footer-column__subheading">Horário</h3>
                    <span class="footer-line"></span>
                    <p>Segunda à Sexta: 8hs às 18hs<br>Sábado: 8hs às 12:00</p>
                </div>

                <div class="footer-column" id="contato">
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
