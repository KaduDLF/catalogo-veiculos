<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'L2 Veículos')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo/LogoL2.png') }}">
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
                    <span>{{ $loja['endereco'] }}</span>
                    <span>{{ $loja['cidade'] }}</span>
                </div>

                <div class="top-header__divider"></div>

                <div class="info-block">
                    <strong>Telefone</strong>
                    <a class="phone-row"
                    href="{{ \App\Support\Loja::whatsapp() }}"
                    target="_blank"
                    rel="noopener noreferrer">
                        <img src="{{ asset('images/icons/icon-wpp.png') }}" alt="WhatsApp">
                        <span>{{ $loja['telefone'] }}</span>
                    </a>

                    <a class="phone-row"
                    href="{{ \App\Support\Loja::whatsapp('telefone2') }}"
                    target="_blank"
                    rel="noopener noreferrer">
                        <img src="{{ asset('images/icons/icon-wpp.png') }}" alt="WhatsApp">
                        <span>{{ $loja['telefone2'] }}</span>
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

                <a href="{{ route('empresa') }}" class="nav-link {{ request()->routeIs('empresa') ? 'nav-link--active' : '' }}">
                    <img src="{{ asset('images/icons/icon-location.png') }}" alt="Empresa" class="nav-icon">
                    <span>Empresa</span>
                </a>

                <span class="nav-separator"></span>

                <a href="{{ route('contato') }}" class="nav-link {{ request()->routeIs('contato') ? 'nav-link--active' : '' }}">
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
                        <strong>{{ $loja['endereco'] }}</strong>
                        <strong>{{ $loja['cidade'] }}</strong>
                    </div>
                </div>

                <div class="footer-highlight__item">
                    <img
                        src="{{ asset('images/icons/icon-clock.png') }}"
                        alt="Horário"
                        class="footer-highlight__icon"
                    >

                    <div>
                        <strong>{{ $loja['semana'] }}</strong>
                        <strong>{{ $loja['sabado'] }}</strong>
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
                    <a href="{{ route('empresa') }}">› Empresa</a>
                    <a href="{{ route('contato') }}">› Contato</a>
                </div>

                <div class="footer-column" id="empresa">
                    <h3>Endereço</h3>
                    <span class="footer-line"></span>
                    <p>{{ $loja['endereco'] }}<br>{{ $loja['cidade'] }}</p>
                    <h3 class="footer-column__subheading">Horário</h3>
                    <span class="footer-line"></span>
                    <p>{{ $loja['semana'] }}<br>{{ $loja['sabado'] }}</p>
                </div>

                <div class="footer-column" id="contato">
                    <h3>Contato</h3>
                    <span class="footer-line"></span>
                    <p>{{ $loja['telefone'] }}<br>{{ $loja['telefone2'] }}<br>{{ $loja['email'] }}</p>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>Desenvolvido por <span>Carlos Eduardo</span></p>
        </div>
    </footer>
    <nav class="social-float" aria-label="Redes sociais e atendimento">
        <a class="social-float__link social-float__link--instagram" href="{{ $loja['instagram'] }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram da L2 Veículos (abre em nova aba)" title="Siga a L2 no Instagram">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true" focusable="false">
                <rect x="3" y="3" width="18" height="18" rx="5" />
                <circle cx="12" cy="12" r="4" />
                <circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none" />
            </svg>
        </a>
        <a class="social-float__link social-float__link--whatsapp" href="{{ \App\Support\Loja::whatsapp() }}" target="_blank" rel="noopener noreferrer" aria-label="Conversar com a L2 Veículos no WhatsApp (abre em nova aba)" title="Converse pelo WhatsApp">
            <img src="{{ asset('images/icons/icon-wpp.png') }}" alt="" width="32" height="32">
        </a>
    </nav>
</body>
</html>
