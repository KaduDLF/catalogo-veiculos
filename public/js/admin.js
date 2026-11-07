document.querySelectorAll('.scroll').forEach(scroller => {
    let origin = null;
    let dragged = false;
    scroller.addEventListener('pointerdown', event => {
        origin = { x: event.clientX, y: event.clientY };
        dragged = false;
    }, { passive: true });
    scroller.addEventListener('pointermove', event => {
        if (origin && Math.hypot(event.clientX - origin.x, event.clientY - origin.y) > 10) dragged = true;
    }, { passive: true });
    scroller.addEventListener('pointercancel', () => { dragged = true; origin = null; }, { passive: true });
    scroller.addEventListener('pointerup', () => { origin = null; }, { passive: true });
    scroller.addEventListener('scroll', () => { dragged = true; }, { passive: true });
    scroller.addEventListener('click', event => {
        if (dragged && event.detail !== 0) {
            event.preventDefault();
            event.stopPropagation();
        }
    }, true);
});

document.querySelectorAll('.stock-row').forEach(row => {
    row.addEventListener('click', event => {
        if (event.target.closest('a,button,input,select,textarea,form') || event.defaultPrevented) return;
        row.querySelector('.stock-row-link')?.click();
    });
});

document.querySelectorAll('.more-actions').forEach(trigger => {
    const popup = trigger.popoverTargetElement;
    if (!popup) return;
    function position() {
        const anchor = trigger.getBoundingClientRect();
        popup.style.left = Math.max(8, Math.min(innerWidth - popup.offsetWidth - 8, anchor.right - popup.offsetWidth)) + 'px';
        const below = anchor.bottom + 4;
        popup.style.top = (below + popup.offsetHeight <= innerHeight ? below : Math.max(8, anchor.top - popup.offsetHeight - 4)) + 'px';
    }
    popup.addEventListener('toggle', () => {
        if (popup.matches(':popover-open')) {
            position();
            window.addEventListener('scroll', position, true);
            window.addEventListener('resize', position);
        } else {
            window.removeEventListener('scroll', position, true);
            window.removeEventListener('resize', position);
        }
    });
});

const toggle = document.querySelector('#admin-menu-toggle');
if (toggle) {
    const sidebar = toggle.closest('aside');
    sidebar.classList.add('menu-ready');
    function setMenu(open) {
        toggle.setAttribute('aria-expanded', String(open));
        toggle.setAttribute('aria-label', open ? 'Fechar menu' : 'Abrir menu');
        sidebar.classList.toggle('menu-open', open);
    }
    toggle.addEventListener('click', () => setMenu(!sidebar.classList.contains('menu-open')));
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && sidebar.classList.contains('menu-open')) {
            setMenu(false);
            toggle.focus();
        }
    });
    document.addEventListener('click', event => {
        if (!sidebar.contains(event.target)) setMenu(false);
    });
}
document.querySelectorAll('[data-excluir]').forEach(form => {
    form.addEventListener('submit', event => {
        if (!confirm('Excluir este veículo e suas fotos?')) event.preventDefault();
    });
});
document.querySelector('[name="condicoes"]')?.addEventListener('input', event => {
    document.querySelector('#previa-condicoes').textContent = event.target.value;
});

const form = document.querySelector('#vehicle-form');
if (form) {
    const fields = form.elements;
    for (const year of [fields.fabricacao, fields.ano_modelo]) {
        year.addEventListener('input', () => {
            year.value = year.value.replace(/[^0-9]/g, '').slice(0, 4);
        });
    }
    const refresh = () => {
        const sold = fields.status.value === 'vendido';
        document.querySelector('#sale-date').hidden = !sold;
        fields.data_venda.required = sold;
        if (sold && !fields.data_venda.value) fields.data_venda.value = form.dataset.hoje;
        fields.em_promocao.disabled = fields.status.value !== 'estoque';
        fields.preco_promocional.disabled = fields.em_promocao.disabled || !fields.em_promocao.checked;
        fields.preco_promocional.required = !fields.preco_promocional.disabled;
    };
    fields.status.addEventListener('change', refresh);
    fields.em_promocao.addEventListener('change', refresh);
    refresh();

    const gallery = document.querySelector('#gallery');
    const existing = JSON.parse(gallery.dataset.existing);
    const removedPhotos = document.querySelector('#removed-photos');
    const removed = new Set();
    let selectedFiles = [];
    let cover = existing[0];
    let temporary = [];
    function renderGallery() {
        temporary.forEach(url => URL.revokeObjectURL(url));
        temporary = selectedFiles.map(file => URL.createObjectURL(file));
        removedPhotos.replaceChildren(...[...removed].map(index => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'remover_fotos[]';
            input.value = index;
            return input;
        }));
        gallery.replaceChildren();
        const photos = [
            ...existing.map((url, index) => ({ url, index, saved: true })).filter(photo => !removed.has(photo.index)),
            ...temporary.map((url, index) => ({ url, index, saved: false })),
        ];
        const sourceOf = photo => photo.saved ? existing[photo.index] : selectedFiles[photo.index];
        if (!photos.some(photo => sourceOf(photo) === cover)) cover = photos[0] ? sourceOf(photos[0]) : null;
        photos.forEach(({ url, index, saved }, position) => {
            const card = document.createElement('div');
            card.className = 'gallery-card';
            const media = document.createElement('div');
            media.className = 'gallery-media';
            const photo = document.createElement('img');
            photo.src = url;
            photo.alt = 'Foto ' + (position + 1);
            const selectPhoto = document.createElement('label');
            selectPhoto.className = 'gallery-select';
            selectPhoto.htmlFor = 'gallery-cover-' + position;
            selectPhoto.title = 'Definir esta foto como capa';
            selectPhoto.append(photo);
            const label = document.createElement('label');
            label.className = 'gallery-cover';
            const radio = document.createElement('input');
            radio.type = 'radio';
            radio.name = 'capa';
            radio.id = selectPhoto.htmlFor;
            radio.value = position;
            const source = saved ? existing[index] : selectedFiles[index];
            radio.checked = source === cover;
            radio.addEventListener('change', () => { cover = source; });
            const remove = document.createElement('button');
            remove.type = 'button';
            remove.className = 'gallery-remove';
            remove.title = 'Remover foto';
            remove.setAttribute('aria-label', 'Remover foto ' + (position + 1));
            const icon = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
            icon.setAttribute('aria-hidden', 'true');
            const use = document.createElementNS('http://www.w3.org/2000/svg', 'use');
            use.setAttribute('href', gallery.dataset.trashIcon);
            icon.append(use);
            remove.append(icon);
            remove.addEventListener('click', () => {
                if (saved) {
                    removed.add(index);
                } else {
                    selectedFiles.splice(index, 1);
                    const transfer = new DataTransfer();
                    selectedFiles.forEach(file => transfer.items.add(file));
                    fields['imagens[]'].files = transfer.files;
                }
                renderGallery();
            });
            media.append(selectPhoto, remove);
            label.append(radio, ' Definir como capa');
            card.append(media, label);
            gallery.append(card);
        });
        if (!gallery.childElementCount) gallery.textContent = 'Nenhuma foto adicionada.';
    }
    fields['imagens[]'].addEventListener('change', () => {
        selectedFiles = Array.from(fields['imagens[]'].files);
        renderGallery();
    });
    renderGallery();

    const customItem = document.querySelector('#custom-ad-item');
    const itemStatus = document.querySelector('#ad-item-status');
    function addItem() {
        const value = customItem.value.trim();
        itemStatus.classList.remove('notice-error');
        if (!value || value.length > 100) {
            itemStatus.classList.add('notice-error');
            itemStatus.textContent = 'Informe um item com até 100 caracteres.';
            customItem.focus();
            return;
        }
        const inputs = [...form.querySelectorAll('[name="itens_confirmados[]"]')];
        const existingItem = inputs.find(input => input.value.toLocaleLowerCase() === value.toLocaleLowerCase());
        if (inputs.filter(input => input.checked).length >= 30 && !existingItem?.checked) {
            itemStatus.classList.add('notice-error');
            itemStatus.textContent = 'Selecione até 30 itens por anúncio.';
            return;
        }
        if (existingItem) {
            existingItem.checked = true;
        } else {
            const label = document.createElement('label');
            label.className = 'check';
            const input = document.createElement('input');
            input.type = 'checkbox';
            input.name = 'itens_confirmados[]';
            input.value = value;
            input.checked = true;
            label.append(input, document.createTextNode(value));
            document.querySelector('#ad-items').append(label);
        }
        itemStatus.textContent = 'Item adicionado. Salve o veículo para guardar as alterações.';
        customItem.value = '';
        customItem.focus();
    }
    document.querySelector('#add-ad-item').addEventListener('click', addItem);
    customItem.addEventListener('keydown', event => {
        if (event.key === 'Enter') {
            event.preventDefault();
            addItem();
        }
    });

    const preview = document.querySelector('#preview');
    const notice = document.querySelector('#copy-status');
    function feedback(message, isError = false) {
        notice.classList.toggle('notice-error', isError);
        notice.textContent = message;
    }
    document.querySelector('#generate').addEventListener('click', () => {
        const price = Number(fields.preco.value);
        const promotion = !fields.preco_promocional.disabled;
        const offer = Number(fields.preco_promocional.value);
        if (!fields.nome.value.trim() || !Number.isFinite(price) || price <= 0) {
            feedback('Preencha o nome do anúncio e o preço.', true);
            return;
        }
        if (promotion && (!Number.isFinite(offer) || offer <= 0 || offer >= price)) {
            feedback('Informe um preço promocional válido e menor que o preço normal.', true);
            return;
        }
        const money = value => value.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
        const priceText = promotion ? 'De ' + money(price) + '\nPor ' + money(offer) : '💰 ' + money(price);
        const details = [
            ['Ano', [fields.fabricacao.value, fields.ano_modelo.value].filter(Boolean).join('/')],
            ['Km', fields.km.value && Number(fields.km.value).toLocaleString('pt-BR')],
            ['Combustível', fields.combustivel.value], ['Câmbio', fields.cambio.value]
        ].filter(pair => pair[1]).map(([key,value]) => '✅ ' + key + ': ' + value);
        const extras = [...form.querySelectorAll('[name="itens_confirmados[]"]:checked')].map(input => '✅ ' + input.value);
        preview.value = ['VENDE-SE ' + fields.nome.value.toUpperCase() + '\n' + priceText, details.join('\n'), extras.join('\n'), form.dataset.condicoes].filter(Boolean).join('\n\n');
        feedback('Texto gerado.');
    });
    document.querySelector('#copy').addEventListener('click', async () => {
        if (!preview.value) { feedback('Gere o texto primeiro.', true); return; }
        try {
            await navigator.clipboard.writeText(preview.value);
            feedback('Texto copiado.');
        } catch {
            preview.select();
            feedback('Texto selecionado. Use a opção Copiar do seu dispositivo.');
        }
    });
}
