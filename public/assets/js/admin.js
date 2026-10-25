/* =====================================================================
   AMÉLIE — Panel de administración
   Sin librerías. Nada de alert/confirm/prompt: la confirmación es un
   modal propio con el estilo del panel.
   ===================================================================== */

(function () {
    'use strict';

    const $  = (selector, scope) => (scope || document).querySelector(selector);
    const $$ = (selector, scope) => Array.prototype.slice.call((scope || document).querySelectorAll(selector));

    /* ---------------------------------------------------------------
       Avisos flotantes
       --------------------------------------------------------------- */

    const toastLayer = (() => {
        const layer = document.createElement('div');
        layer.className = 'toasts';
        layer.setAttribute('role', 'status');
        layer.setAttribute('aria-live', 'polite');
        document.body.appendChild(layer);
        return layer;
    })();

    function toast(message, type) {
        if (!message) {
            return;
        }

        const box = document.createElement('div');
        box.className = 'toast toast--' + (type || 'info');

        const label = document.createElement('span');
        label.textContent = message;
        box.appendChild(label);

        const close = document.createElement('button');
        close.type = 'button';
        close.className = 'toast__close';
        close.setAttribute('aria-label', 'Cerrar');
        close.textContent = '×';
        box.appendChild(close);

        const dismiss = () => {
            box.classList.add('is-leaving');
            window.setTimeout(() => box.remove(), 300);
        };

        close.addEventListener('click', dismiss);
        toastLayer.appendChild(box);
        window.setTimeout(dismiss, 6000);
    }

    $$('[data-flash]').forEach((node) => {
        toast(node.getAttribute('data-flash-message'), node.getAttribute('data-flash'));
        node.remove();
    });

    /* ---------------------------------------------------------------
       Barra lateral en móvil
       --------------------------------------------------------------- */

    const sideToggle = $('[data-side-toggle]');

    if (sideToggle) {
        sideToggle.addEventListener('click', (event) => {
            event.preventDefault();
            document.body.classList.toggle('side-open');
        });

        document.addEventListener('click', (event) => {
            if (document.body.classList.contains('side-open')
                && !event.target.closest('.side')
                && !event.target.closest('[data-side-toggle]')) {
                document.body.classList.remove('side-open');
            }
        });
    }

    /* ---------------------------------------------------------------
       Pestañas de idioma
       --------------------------------------------------------------- */

    $$('[data-tabs]').forEach((group) => {
        const buttons = $$('button[data-tab]', group);
        const panels  = $$('[data-tab-panel]', group.parentElement || document);

        buttons.forEach((button) => {
            button.addEventListener('click', (event) => {
                event.preventDefault();

                const target = button.getAttribute('data-tab');

                buttons.forEach((other) =>
                    other.setAttribute('aria-selected', other === button ? 'true' : 'false'));

                panels.forEach((panel) => {
                    panel.hidden = panel.getAttribute('data-tab-panel') !== target;
                });
            });
        });
    });

    /* ---------------------------------------------------------------
       Confirmación antes de una acción irreversible
       --------------------------------------------------------------- */

    const dialog = buildDialog();

    function buildDialog() {
        const modal = document.createElement('div');
        modal.className = 'modal';
        modal.setAttribute('role', 'dialog');
        modal.setAttribute('aria-modal', 'true');
        modal.hidden = true;

        const panel = document.createElement('div');
        panel.className = 'modal__panel';
        panel.style.maxWidth = '48rem';

        const title = document.createElement('h2');
        title.textContent = '¿Seguro?';
        panel.appendChild(title);

        const body = document.createElement('p');
        body.className = 'lede';
        panel.appendChild(body);

        const actions = document.createElement('div');
        actions.style.display = 'flex';
        actions.style.gap = '.8rem';
        actions.style.flexWrap = 'wrap';
        actions.style.marginTop = '2.4rem';

        const cancel = document.createElement('button');
        cancel.type = 'button';
        cancel.className = 'abtn abtn--ghost';
        cancel.textContent = 'Cancelar';

        const confirm = document.createElement('button');
        confirm.type = 'button';
        confirm.className = 'abtn abtn--danger';
        confirm.textContent = 'Sí, continuar';

        actions.appendChild(cancel);
        actions.appendChild(confirm);
        panel.appendChild(actions);
        modal.appendChild(panel);
        document.body.appendChild(modal);

        let onAccept = null;

        const close = () => {
            modal.hidden = true;
            document.body.classList.remove('is-locked');
            onAccept = null;
        };

        cancel.addEventListener('click', close);

        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                close();
            }
        });

        confirm.addEventListener('click', () => {
            const action = onAccept;
            close();
            if (action) {
                action();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !modal.hidden) {
                close();
            }
        });

        return {
            open(message, label, accept) {
                body.textContent = message;
                confirm.textContent = label || 'Sí, continuar';
                onAccept = accept;
                modal.hidden = false;
                document.body.classList.add('is-locked');
                cancel.focus();
            }
        };
    }

    $$('[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (form.dataset.confirmed === 'yes') {
                return;
            }

            event.preventDefault();

            dialog.open(form.getAttribute('data-confirm'), form.getAttribute('data-confirm-label'), () => {
                form.dataset.confirmed = 'yes';
                form.submit();
            });
        });
    });

    /* ---------------------------------------------------------------
       Avisar de cambios sin guardar
       --------------------------------------------------------------- */

    $$('form[data-dirty-guard]').forEach((form) => {
        let dirty = false;

        form.addEventListener('input', () => { dirty = true; });
        form.addEventListener('change', () => { dirty = true; });
        form.addEventListener('submit', () => { dirty = false; });

        window.addEventListener('beforeunload', (event) => {
            if (!dirty) {
                return;
            }
            event.preventDefault();
            event.returnValue = '';
        });
    });

    /* ---------------------------------------------------------------
       Generar el enlace amigable a partir del nombre
       --------------------------------------------------------------- */

    const slugSource = $('[data-slug-source]');
    const slugTarget = $('[data-slug-target]');

    if (slugSource && slugTarget) {
        slugSource.addEventListener('input', () => {
            if (slugTarget.dataset.touched === 'yes') {
                return;
            }

            slugTarget.value = slugSource.value
                .toLowerCase()
                .normalize('NFD')
                .replace(/[̀-ͯ]/g, '')
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
        });

        slugTarget.addEventListener('input', () => { slugTarget.dataset.touched = 'yes'; });
    }

    /* ---------------------------------------------------------------
       Copiar la ruta de una imagen al portapapeles
       --------------------------------------------------------------- */

    $$('[data-copy]').forEach((button) => {
        button.addEventListener('click', (event) => {
            event.preventDefault();

            const value = button.getAttribute('data-copy');
            const original = button.textContent;

            const done = () => {
                button.textContent = 'Copiado';
                toast('Ruta copiada: ' + value, 'ok');
                window.setTimeout(() => { button.textContent = original; }, 1600);
            };

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(value).then(done).catch(() => fallbackCopy(value, done));
                return;
            }

            fallbackCopy(value, done);
        });
    });

    function fallbackCopy(value, done) {
        const helper = document.createElement('textarea');
        helper.value = value;
        helper.setAttribute('readonly', 'readonly');
        helper.style.position = 'fixed';
        helper.style.opacity = '0';
        document.body.appendChild(helper);
        helper.select();

        try {
            document.execCommand('copy');
            done();
        } catch (error) {
            toast('No se ha podido copiar. Selecciona el texto a mano.', 'error');
        }

        helper.remove();
    }

    /* ---------------------------------------------------------------
       Vista previa de la imagen antes de subirla
       --------------------------------------------------------------- */

    $$('[data-image-input]').forEach((input) => {
        input.addEventListener('change', () => {
            const preview = document.getElementById(input.getAttribute('data-image-input'));

            if (!preview || !input.files || !input.files[0]) {
                return;
            }

            const file = input.files[0];

            if (file.size > 6291456) {
                toast('Esa imagen pesa más de 6 MB. Reduce su tamaño antes de subirla.', 'error');
                input.value = '';
                return;
            }

            preview.src = URL.createObjectURL(file);
            preview.hidden = false;
        });
    });

    /* ---------------------------------------------------------------
       Buscador dentro de una tabla
       --------------------------------------------------------------- */

    $$('[data-table-filter]').forEach((input) => {
        input.addEventListener('input', () => {
            const table = document.getElementById(input.getAttribute('data-table-filter'));

            if (!table) {
                return;
            }

            const needle = input.value.trim().toLowerCase();

            $$('tbody tr', table).forEach((row) => {
                row.hidden = needle !== '' && row.textContent.toLowerCase().indexOf(needle) === -1;
            });
        });
    });
}());
