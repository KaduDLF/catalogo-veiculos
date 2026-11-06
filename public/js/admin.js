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
    let temporary = [];
    function renderGallery() {
        temporary.forEach(url => URL.revokeObjectURL(url));
        temporary = Array.from(fields['imagens[]'].files, file => URL.createObjectURL(file));
        gallery.replaceChildren();
        [...existing, ...temporary].forEach((url, index) => {
            const label = document.createElement('label');
            const photo = document.createElement('img');
            photo.src = url;
            photo.alt = 'Foto ' + (index + 1);
            const radio = document.createElement('input');
            radio.type = 'radio';
            radio.name = 'capa';
            radio.value = index;
            radio.checked = index === 0;
            label.append(photo, radio, ' Capa');
            gallery.append(label);
        });
        if (!gallery.childElementCount) gallery.textContent = 'Nenhuma foto adicionada.';
    }
    fields['imagens[]'].addEventListener('change', renderGallery);
    renderGallery();

    const preview = document.querySelector('#preview');
    const notice = document.querySelector('#copy-status');
    function feedback(message, isError = false) {
        notice.classList.toggle('notice-error', isError);
        notice.textContent = message;
    }
    document.querySelector('#generate').addEventListener('click', () => {
        const price = fields.preco_promocional.disabled ? fields.preco : fields.preco_promocional;
        if (!fields.nome.value.trim() || !Number(price.value)) {
            feedback('Preencha o nome do anúncio e o preço.', true);
            return;
        }
        const money = Number(price.value).toLocaleString('pt-BR',{style:'currency',currency:'BRL'});
        const details = [
            ['Ano', [fields.fabricacao.value, fields.ano_modelo.value].filter(Boolean).join('/')],
            ['Km', fields.km.value && Number(fields.km.value).toLocaleString('pt-BR')],
            ['Combustível', fields.combustivel.value], ['Câmbio', fields.cambio.value]
        ].filter(pair => pair[1]).map(([key,value]) => '✅ ' + key + ': ' + value);
        const extras = [...form.querySelectorAll('[name="itens_confirmados[]"]:checked')].map(input => '✅ ' + input.value);
        preview.value = ['VENDE-SE ' + fields.nome.value.toUpperCase() + '\n💰 ' + money, details.join('\n'), extras.join('\n'), form.dataset.condicoes].filter(Boolean).join('\n\n');
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
