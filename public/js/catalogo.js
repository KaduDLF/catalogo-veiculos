document.querySelectorAll('[data-view]').forEach(button => button.addEventListener('click', () => {
    const mode = button.dataset.view;
    document.querySelector('.catalog-cards').dataset.mode = mode;
    document.querySelector('#view-mode').value = mode;
    document.querySelectorAll('[data-view]').forEach(item => item.setAttribute('aria-pressed', String(item === button)));
    const url = new URL(location.href);
    url.searchParams.set('visualizacao', mode);
    history.replaceState(null, '', url);
    document.querySelectorAll('.catalog-pagination a').forEach(link => {
        const target = new URL(link.href);
        target.searchParams.set('visualizacao', mode);
        link.href = target;
    });
}));
document.querySelector('#ordem')?.addEventListener('change', () => document.querySelector('#catalog-filters').requestSubmit());
document.querySelector('#marca')?.addEventListener('change', () => { document.querySelector('#modelo').value = ''; });
const photos = [...document.querySelectorAll('[data-gallery-image]')];
const thumbnails = [...document.querySelectorAll('[data-photo]')];
let currentPhoto = 0;
function showPhoto(index) {
    if (!photos.length) return;
    currentPhoto = (index + photos.length) % photos.length;
    photos.forEach((photo, i) => { photo.hidden = i !== currentPhoto; });
    thumbnails.forEach((button, i) => button.setAttribute('aria-pressed', String(i === currentPhoto)));
}
thumbnails.forEach(button => button.addEventListener('click', () => showPhoto(Number(button.dataset.photo))));
document.querySelector('.gallery-prev')?.addEventListener('click', () => showPhoto(currentPhoto - 1));
document.querySelector('.gallery-next')?.addEventListener('click', () => showPhoto(currentPhoto + 1));

const navigation = document.querySelector('.main-nav');
const menuToggle = document.querySelector('.nav-toggle');
const menuLinks = document.querySelector('#main-menu');
if (navigation && menuToggle && menuLinks) {
    const compactNav = window.matchMedia('(max-width: 1100px)');
    const setMenuOpen = open => {
        menuToggle.setAttribute('aria-expanded', String(open));
        menuToggle.setAttribute('aria-label', open ? 'Fechar menu' : 'Abrir menu');
        menuLinks.classList.toggle('is-open', open);
    };
    menuToggle.addEventListener('click', () => setMenuOpen(menuToggle.getAttribute('aria-expanded') !== 'true'));
    menuLinks.querySelectorAll('a').forEach(link => link.addEventListener('click', () => setMenuOpen(false)));
    document.addEventListener('click', event => {
        if (!navigation.contains(event.target)) setMenuOpen(false);
    });
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && menuToggle.getAttribute('aria-expanded') === 'true') {
            setMenuOpen(false);
            menuToggle.focus();
        }
    });
    compactNav.addEventListener('change', () => {
        if (compactNav.matches && menuLinks.contains(document.activeElement)) menuToggle.focus();
        setMenuOpen(false);
    });
}
