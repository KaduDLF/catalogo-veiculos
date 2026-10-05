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
                <div class="brand__logo" aria-label="L2 Veículos">
                    <span class="brand__l2">L2</span>
                    <span class="brand__vehicles">VEÍCULOS</span>
                    <small>VEÍCULOS NOVOS E SEMINOVOS</small>
                </div>
            </div>

            <div class="top-header__info">
                <div class="info-block info-block--address">
                    <strong>Endereço</strong>
                    <span>Rua E, Nº 22</span>
                    <span>Centro - Rialma - GO</span>
                </div>

                <div class="top-header__divider"></div>

                <div class="info-block">
                    <strong>Telefone</strong>
                    <span>◉ (62) 98535-0837</span>
                    <span>◉ (62) 99641-3235</span>
                </div>
            </div>
        </div>
    </header>

    <nav class="main-nav">
        <div class="container main-nav__inner">
            <div class="main-nav__links">
                <a href="#" class="nav-link nav-link--active">⌂ <span>Home</span></a>
                <span class="nav-separator"></span>
                <a href="#veiculos" class="nav-link">▣ <span>Veículos</span></a>
                <span class="nav-separator"></span>
                <a href="#empresa" class="nav-link">⌖ <span>Empresa</span></a>
                <span class="nav-separator"></span>
                <a href="#contato" class="nav-link">✉ <span>Contato</span></a>
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
                <h1>ÚLTIMAS OFERTAS</h1>

                @php
                    $veiculos = array_fill(0, 8, [
                        'nome' => 'Honda Civic EXL',
                        'ano' => '2022/2022',
                        'km' => '43.500 km',
                        'cambio' => 'Automático',
                        'preco' => 'R$ 119.900',
                    ]);
                @endphp

                <div class="vehicle-grid">
                    @foreach ($veiculos as $veiculo)
                        <article class="vehicle-card">
                            <div class="vehicle-card__image vehicle-card__image--placeholder">
                                <span>FOTO DO VEÍCULO</span>
                            </div>
                            <div class="vehicle-card__content">
                                <h2>{{ $veiculo['nome'] }}</h2>
                                <p>{{ $veiculo['ano'] }} <span>•</span> {{ $veiculo['km'] }} <span>•</span> {{ $veiculo['cambio'] }}</p>
                            </div>
                            <a href="#" class="vehicle-card__price">{{ $veiculo['preco'] }}</a>
                        </article>
                    @endforeach
                </div>

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
                    <span class="footer-highlight__icon">⌖</span>
                    <div>
                        <strong>Rua E, Nº 22</strong>
                        <strong>Centro - Rialma - GO</strong>
                    </div>
                </div>
                <div class="footer-highlight__item">
                    <span class="footer-highlight__icon">◷</span>
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
                    <p>Rua E, Nº 22<br>Centro - Rialma - GO</p>
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
