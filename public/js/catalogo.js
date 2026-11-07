const catalogCards = document.querySelector('.catalog-cards');
if (catalogCards) {
    const mobileCatalog = window.matchMedia('(max-width: 720px)');
    let desktopMode = catalogCards.dataset.mode;
    function applyView() {
        const mode = mobileCatalog.matches ? 'lista' : desktopMode;
        catalogCards.dataset.mode = mode;
        document.querySelector('#view-mode').value = mode;
        document.querySelectorAll('[data-view]').forEach(item => item.setAttribute('aria-pressed', String(item.dataset.view === mode)));
        const url = new URL(location.href);
        url.searchParams.set('visualizacao', mode);
        history.replaceState(null, '', url);
        document.querySelectorAll('.catalog-pagination a').forEach(link => {
            const target = new URL(link.href);
            target.searchParams.set('visualizacao', mode);
            link.href = target;
        });
    }
    document.querySelectorAll('[data-view]').forEach(button => button.addEventListener('click', () => {
        desktopMode = button.dataset.view;
        applyView();
    }));
    mobileCatalog.addEventListener('change', applyView);
    applyView();
}
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
    if (lightbox?.open) renderLightbox();
}
thumbnails.forEach(button => button.addEventListener('click', () => showPhoto(Number(button.dataset.photo))));
document.querySelector('.gallery-prev')?.addEventListener('click', () => showPhoto(currentPhoto - 1));
document.querySelector('.gallery-next')?.addEventListener('click', () => showPhoto(currentPhoto + 1));

const lightbox = document.querySelector('.photo-lightbox');
function renderLightbox() {
    const image = lightbox.querySelector('.lightbox-image');
    image.src = photos[currentPhoto].src;
    image.alt = photos[currentPhoto].alt;
    lightbox.querySelector('.lightbox-counter').textContent = `Foto ${currentPhoto + 1} de ${photos.length}`;
    lightbox.classList.remove('is-zoomed');
    lightbox.querySelector('.lightbox-zoom').setAttribute('aria-pressed', 'false');
    lightbox.querySelector('.lightbox-zoom').textContent = 'Zoom 2×';
    lightbox.querySelector('.lightbox-stage').scrollTo(0, 0);
}
if (lightbox) {
    document.querySelector('.gallery-open').addEventListener('click', () => {
        renderLightbox();
        lightbox.showModal();
        document.documentElement.classList.add('photo-modal-open');
    });
    lightbox.querySelector('.lightbox-close').addEventListener('click', () => lightbox.close());
    lightbox.addEventListener('close', () => document.documentElement.classList.remove('photo-modal-open'));
    lightbox.addEventListener('click', event => {
        const bounds = lightbox.getBoundingClientRect();
        if (event.target === lightbox && (event.clientX < bounds.left || event.clientX > bounds.right || event.clientY < bounds.top || event.clientY > bounds.bottom)) lightbox.close();
    });
    lightbox.querySelector('.lightbox-zoom').addEventListener('click', event => {
        const zoomed = lightbox.classList.toggle('is-zoomed');
        event.currentTarget.setAttribute('aria-pressed', String(zoomed));
        event.currentTarget.textContent = zoomed ? 'Ajustar foto' : 'Zoom 2×';
    });
    lightbox.querySelector('.lightbox-prev')?.addEventListener('click', () => showPhoto(currentPhoto - 1));
    lightbox.querySelector('.lightbox-next')?.addEventListener('click', () => showPhoto(currentPhoto + 1));
    lightbox.addEventListener('keydown', event => {
        if (event.key === 'ArrowLeft' && !lightbox.classList.contains('is-zoomed')) { event.preventDefault(); showPhoto(currentPhoto - 1); }
        if (event.key === 'ArrowRight' && !lightbox.classList.contains('is-zoomed')) { event.preventDefault(); showPhoto(currentPhoto + 1); }
    });
}

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
