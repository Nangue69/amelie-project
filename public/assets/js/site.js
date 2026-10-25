/* =====================================================================
   AMÉLIE — Parte pública
   Sin librerías. Todo el DOM se construye con createElement.
   ===================================================================== */

(function () {
    'use strict';

    const $  = (selector, scope) => (scope || document).querySelector(selector);
    const $$ = (selector, scope) => Array.prototype.slice.call((scope || document).querySelectorAll(selector));

    const config = (() => {
        const node = $('#app-config');
        if (!node) {
            return {};
        }
        try {
            return JSON.parse(node.textContent);
        } catch (error) {
            return {};
        }
    })();

    const text = (key, fallback) => (config.strings && config.strings[key]) || fallback || '';

    /* ---------------------------------------------------------------
       Avisos flotantes
       --------------------------------------------------------------- */

    const toastLayer = (() => {
        let layer = $('.toasts');
        if (!layer) {
            layer = document.createElement('div');
            layer.className = 'toasts';
            layer.setAttribute('role', 'status');
            layer.setAttribute('aria-live', 'polite');
            document.body.appendChild(layer);
        }
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
        close.setAttribute('aria-label', text('close', 'Cerrar'));
        close.textContent = '×';
        box.appendChild(close);

        const dismiss = () => {
            box.classList.add('is-leaving');
            window.setTimeout(() => box.remove(), 300);
        };

        close.addEventListener('click', dismiss);
        toastLayer.appendChild(box);
        window.setTimeout(dismiss, 5000);
    }

    // Mensajes que vienen del servidor en la carga de la página.
    $$('[data-flash]').forEach((node) => {
        toast(node.getAttribute('data-flash-message'), node.getAttribute('data-flash'));
        node.remove();
    });

    /* ---------------------------------------------------------------
       Utilidades de red
       --------------------------------------------------------------- */

    function postForm(url, data) {
        const body = new FormData();
        body.append('csrf_token', config.csrf || '');

        Object.keys(data || {}).forEach((key) => body.append(key, data[key]));

        return fetch(url, {
            method: 'POST',
            body: body,
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).then((response) => response.json());
    }

    /* ---------------------------------------------------------------
       Cabecera, menú móvil, idiomas y buscador
       --------------------------------------------------------------- */

    const header = $('.header');

    if (header) {
        const onScroll = () => header.classList.toggle('is-stuck', window.scrollY > 10);
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }

    let lastFocused = null;

    function openOverlay(node) {
        if (!node) {
            return;
        }
        lastFocused = document.activeElement;
        node.hidden = false;
        document.body.classList.add('is-locked');

        const focusable = node.querySelector('a, button, input, select, textarea');
        if (focusable) {
            focusable.focus();
        }
    }

    function closeOverlay(node) {
        if (!node || node.hidden) {
            return;
        }
        node.hidden = true;
        document.body.classList.remove('is-locked');

        if (lastFocused && typeof lastFocused.focus === 'function') {
            lastFocused.focus();
        }
    }

    $$('[data-open]').forEach((trigger) => {
        trigger.addEventListener('click', (event) => {
            event.preventDefault();
            openOverlay($('#' + trigger.getAttribute('data-open')));
        });
    });

    $$('[data-close]').forEach((trigger) => {
        trigger.addEventListener('click', (event) => {
            event.preventDefault();
            closeOverlay(trigger.closest('.drawer, .modal'));
        });
    });

    document.addEventListener('keydown', (event) => {
        if (event.key !== 'Escape') {
            return;
        }
        $$('.drawer, .modal').forEach((node) => closeOverlay(node));
        const langMenu = $('.lang__menu');
        if (langMenu && !langMenu.hidden) {
            langMenu.hidden = true;
            const toggle = $('.lang__toggle');
            if (toggle) {
                toggle.setAttribute('aria-expanded', 'false');
            }
        }
    });

    const langToggle = $('.lang__toggle');
    const langMenu = $('.lang__menu');

    if (langToggle && langMenu) {
        langToggle.addEventListener('click', (event) => {
            event.preventDefault();
            const open = langMenu.hidden;
            langMenu.hidden = !open;
            langToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        });

        document.addEventListener('click', (event) => {
            if (!langMenu.hidden && !event.target.closest('.lang')) {
                langMenu.hidden = true;
                langToggle.setAttribute('aria-expanded', 'false');
            }
        });
    }

    const searchToggle = $('[data-search-toggle]');
    const searchBar = $('#searchbar');

    if (searchToggle && searchBar) {
        searchToggle.addEventListener('click', (event) => {
            event.preventDefault();
            searchBar.hidden = !searchBar.hidden;
            searchToggle.setAttribute('aria-expanded', searchBar.hidden ? 'false' : 'true');

            if (!searchBar.hidden) {
                const field = $('input[name="q"]', searchBar);
                if (field) {
                    field.focus();
                }
            }
        });
    }

    /* ---------------------------------------------------------------
       Acordeón
       --------------------------------------------------------------- */

    $$('.accordion__trigger').forEach((trigger) => {
        trigger.addEventListener('click', (event) => {
            event.preventDefault();
            const panel = document.getElementById(trigger.getAttribute('aria-controls'));
            const open = trigger.getAttribute('aria-expanded') === 'true';

            trigger.setAttribute('aria-expanded', open ? 'false' : 'true');
            if (panel) {
                panel.hidden = open;
            }
        });
    });

    /* ---------------------------------------------------------------
       Galería de producto
       --------------------------------------------------------------- */

    const galleryMain = $('[data-gallery-main]');

    if (galleryMain) {
        $$('[data-gallery-thumb]').forEach((thumb) => {
            thumb.addEventListener('click', (event) => {
                event.preventDefault();

                galleryMain.src = thumb.getAttribute('data-src');
                galleryMain.alt = thumb.getAttribute('data-alt') || '';

                $$('[data-gallery-thumb]').forEach((other) => other.setAttribute('aria-current', 'false'));
                thumb.setAttribute('aria-current', 'true');
            });
        });
    }

    /* ---------------------------------------------------------------
       Selección de talla y compra
       --------------------------------------------------------------- */

    const buyForm = $('[data-buy-form]');

    if (buyForm) {
        const variants = (() => {
            const node = $('#variant-data');
            if (!node) {
                return [];
            }
            try {
                return JSON.parse(node.textContent);
            } catch (error) {
                return [];
            }
        })();

        const mode = buyForm.getAttribute('data-size-mode');
        const variantField = $('[data-variant-id]', buyForm);
        const submit = $('[data-add-button]', buyForm);
        const stockLine = $('[data-stock-line]');
        const qtyInput = $('[data-qty]', buyForm);

        const selected = () => ({
            bra: (buyForm.querySelector('input[name="size_bra"]:checked') || {}).value,
            brief: (buyForm.querySelector('input[name="size_brief"]:checked') || {}).value,
            size: (buyForm.querySelector('input[name="size"]:checked') || {}).value
        });

        function matchVariant() {
            const choice = selected();

            return variants.find((variant) => {
                if (mode === 'bra_brief') {
                    return variant.size_bra === choice.bra && variant.size_brief === choice.brief;
                }
                return variant.size === choice.size;
            }) || null;
        }

        // Deshabilita las tallas de tanga sin stock para la copa elegida.
        function refreshAvailability() {
            if (mode !== 'bra_brief') {
                return;
            }

            const choice = selected();

            $$('input[name="size_brief"]', buyForm).forEach((input) => {
                if (!choice.bra) {
                    input.disabled = false;
                    return;
                }

                const match = variants.find(
                    (variant) => variant.size_bra === choice.bra && variant.size_brief === input.value
                );

                input.disabled = !match || match.stock < 1;

                if (input.disabled && input.checked) {
                    input.checked = false;
                }
            });
        }

        function setStockMessage(variant) {
            if (!stockLine) {
                return;
            }

            stockLine.classList.remove('stock-line--low', 'stock-line--out');

            if (!variant) {
                stockLine.textContent = mode === 'bra_brief'
                    ? text('choose_both', '')
                    : text('select_size', '');
                return;
            }

            if (variant.stock < 1) {
                stockLine.textContent = text('out_of_stock', 'Sin stock');
                stockLine.classList.add('stock-line--out');
                return;
            }

            if (variant.stock <= (config.lowStock || 5)) {
                stockLine.textContent = text('low_stock', '') + ' (' + variant.stock + ')';
                stockLine.classList.add('stock-line--low');
                return;
            }

            stockLine.textContent = text('in_stock', '');
        }

        function refresh() {
            refreshAvailability();

            const variant = matchVariant();
            const buyable = Boolean(variant) && variant.stock > 0;

            if (variantField) {
                variantField.value = variant ? variant.id : '';
            }

            if (submit) {
                submit.disabled = !buyable;
                submit.textContent = buyable || !variant
                    ? text('add_to_cart', 'Añadir al carrito')
                    : text('sold_out', 'Agotado');
            }

            if (qtyInput && variant) {
                qtyInput.max = String(Math.max(1, variant.stock));
                if (Number(qtyInput.value) > variant.stock) {
                    qtyInput.value = String(Math.max(1, variant.stock));
                }
            }

            setStockMessage(variant);
        }

        $$('input[name="size"], input[name="size_bra"], input[name="size_brief"]', buyForm)
            .forEach((input) => input.addEventListener('change', refresh));

        $$('[data-qty-step]', buyForm).forEach((button) => {
            button.addEventListener('click', (event) => {
                event.preventDefault();

                if (!qtyInput) {
                    return;
                }

                const step = Number(button.getAttribute('data-qty-step'));
                const max = Number(qtyInput.max) || 10;
                const next = Math.min(Math.max(1, Number(qtyInput.value) + step), max);

                qtyInput.value = String(next);
            });
        });

        buyForm.addEventListener('submit', (event) => {
            event.preventDefault();

            const variant = matchVariant();

            if (!variant) {
                toast(text('select_size_first', ''), 'error');
                return;
            }

            if (submit) {
                submit.disabled = true;
            }

            postForm(buyForm.action, {
                variant_id: variant.id,
                quantity: qtyInput ? qtyInput.value : 1
            })
                .then((response) => {
                    toast(response.message, response.ok ? 'ok' : 'error');

                    if (response.ok) {
                        updateCartCount(response.count);
                        renderCartDrawer(response);
                        openOverlay($('#cart-drawer'));
                    }
                })
                .catch(() => toast(text('error', ''), 'error'))
                .finally(() => refresh());
        });

        refresh();
    }

    /* ---------------------------------------------------------------
       Carrito
       --------------------------------------------------------------- */

    function updateCartCount(count) {
        $$('[data-cart-count]').forEach((node) => {
            node.textContent = String(count);
            node.hidden = Number(count) < 1;
        });
    }

    function renderCartDrawer(payload) {
        const list = $('[data-cart-lines]');

        if (!list) {
            return;
        }

        while (list.firstChild) {
            list.removeChild(list.firstChild);
        }

        if (!payload.items || payload.items.length === 0) {
            const empty = document.createElement('p');
            empty.className = 'empty-state';
            empty.textContent = text('cart_empty', '');
            list.appendChild(empty);
        } else {
            payload.items.forEach((item) => list.appendChild(buildCartLine(item)));
        }

        const subtotal = $('[data-cart-subtotal]');
        if (subtotal) {
            subtotal.textContent = payload.subtotal;
        }

        const footer = $('[data-cart-footer]');
        if (footer) {
            footer.hidden = !payload.items || payload.items.length === 0;
        }
    }

    function buildCartLine(item) {
        const line = document.createElement('article');
        line.className = 'cart-line';

        const mediaLink = document.createElement('a');
        mediaLink.className = 'cart-line__media';
        mediaLink.href = item.url;

        const image = document.createElement('img');
        image.src = item.image;
        image.alt = item.name;
        image.loading = 'lazy';
        mediaLink.appendChild(image);
        line.appendChild(mediaLink);

        const body = document.createElement('div');

        const title = document.createElement('h3');
        title.className = 'cart-line__name';

        const titleLink = document.createElement('a');
        titleLink.href = item.url;
        titleLink.textContent = item.name;
        title.appendChild(titleLink);
        body.appendChild(title);

        const size = document.createElement('p');
        size.className = 'cart-line__size';
        size.textContent = item.size_label;
        body.appendChild(size);

        const actions = document.createElement('div');
        actions.className = 'cart-line__actions';

        const quantity = document.createElement('span');
        quantity.textContent = item.quantity + ' × ' + item.price;
        actions.appendChild(quantity);

        const remove = document.createElement('button');
        remove.type = 'button';
        remove.className = 'cart-line__remove';
        remove.textContent = text('remove', 'Quitar');
        remove.addEventListener('click', (event) => {
            event.preventDefault();

            postForm(config.routes.cartRemove, { variant_id: item.variant_id })
                .then((response) => {
                    toast(response.message, 'ok');
                    updateCartCount(response.count);
                    renderCartDrawer(response);
                })
                .catch(() => toast(text('error', ''), 'error'));
        });
        actions.appendChild(remove);

        body.appendChild(actions);
        line.appendChild(body);

        const total = document.createElement('p');
        total.className = 'cart-line__total';
        total.textContent = item.line_total;
        line.appendChild(total);

        return line;
    }

    // Página de carrito: cantidad y eliminación sin recarga completa.
    $$('[data-cart-page] [data-line-qty]').forEach((input) => {
        input.addEventListener('change', (event) => {
            event.preventDefault();

            postForm(config.routes.cartUpdate, {
                variant_id: input.getAttribute('data-variant'),
                quantity: input.value
            })
                .then(() => window.location.reload())
                .catch(() => toast(text('error', ''), 'error'));
        });
    });

    /* ---------------------------------------------------------------
       Newsletter
       --------------------------------------------------------------- */

    $$('[data-newsletter]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            event.preventDefault();

            const field = $('input[type="email"]', form);
            const button = $('button[type="submit"]', form);

            if (!field || !field.value) {
                toast(text('invalid_email', ''), 'error');
                return;
            }

            if (button) {
                button.disabled = true;
            }

            postForm(form.action, {
                email: field.value,
                source: form.getAttribute('data-newsletter')
            })
                .then((response) => {
                    toast(response.message, response.ok ? 'ok' : 'error');
                    if (response.ok) {
                        form.reset();
                    }
                })
                .catch(() => toast(text('error', ''), 'error'))
                .finally(() => {
                    if (button) {
                        button.disabled = false;
                    }
                });
        });
    });

    /* ---------------------------------------------------------------
       Checkout: recalcular el envío al cambiar de país
       --------------------------------------------------------------- */

    const countrySelect = $('[data-country]');

    if (countrySelect) {
        countrySelect.addEventListener('change', () => {
            const url = new URL(window.location.href);
            url.searchParams.set('country', countrySelect.value);
            window.location.assign(url.toString());
        });
    }

    /* ---------------------------------------------------------------
       Cookies
       --------------------------------------------------------------- */

    const COOKIE_NAME = 'amelie_consent';

    function readConsent() {
        const match = document.cookie.split('; ').find((row) => row.indexOf(COOKIE_NAME + '=') === 0);

        if (!match) {
            return null;
        }

        try {
            return JSON.parse(decodeURIComponent(match.split('=')[1]));
        } catch (error) {
            return null;
        }
    }

    function writeConsent(value) {
        const oneYear = 60 * 60 * 24 * 365;
        document.cookie = COOKIE_NAME + '=' + encodeURIComponent(JSON.stringify(value))
            + '; path=/; max-age=' + oneYear + '; SameSite=Lax';
    }

    const cookieBar = $('#cookie-bar');
    const cookieModal = $('#cookie-modal');

    if (cookieBar && !readConsent()) {
        cookieBar.hidden = false;
    }

    function saveConsent(consent) {
        writeConsent(consent);

        if (cookieBar) {
            cookieBar.hidden = true;
        }

        closeOverlay(cookieModal);
        toast(text('cookies_saved', ''), 'ok');

        if (consent.analytics) {
            loadAnalytics();
        }
    }

    $$('[data-cookie-accept]').forEach((button) => {
        button.addEventListener('click', (event) => {
            event.preventDefault();
            saveConsent({ necessary: true, analytics: true, marketing: true });
        });
    });

    $$('[data-cookie-reject]').forEach((button) => {
        button.addEventListener('click', (event) => {
            event.preventDefault();
            saveConsent({ necessary: true, analytics: false, marketing: false });
        });
    });

    const cookieForm = $('[data-cookie-form]');

    if (cookieForm) {
        cookieForm.addEventListener('submit', (event) => {
            event.preventDefault();

            saveConsent({
                necessary: true,
                analytics: $('input[name="analytics"]', cookieForm).checked,
                marketing: $('input[name="marketing"]', cookieForm).checked
            });
        });
    }

    $$('[data-cookie-settings]').forEach((button) => {
        button.addEventListener('click', (event) => {
            event.preventDefault();

            const consent = readConsent() || { analytics: false, marketing: false };
            const analytics = $('input[name="analytics"]', cookieModal);
            const marketing = $('input[name="marketing"]', cookieModal);

            if (analytics) {
                analytics.checked = Boolean(consent.analytics);
            }
            if (marketing) {
                marketing.checked = Boolean(consent.marketing);
            }

            openOverlay(cookieModal);
        });
    });

    // La analítica sólo se carga con consentimiento explícito.
    let analyticsLoaded = false;

    function loadAnalytics() {
        if (analyticsLoaded || !config.analyticsId) {
            return;
        }

        analyticsLoaded = true;

        const script = document.createElement('script');
        script.async = true;
        script.src = 'https://www.googletagmanager.com/gtag/js?id=' + encodeURIComponent(config.analyticsId);
        document.head.appendChild(script);

        window.dataLayer = window.dataLayer || [];
        function gtag() { window.dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', config.analyticsId, { anonymize_ip: true });
    }

    const storedConsent = readConsent();

    if (storedConsent && storedConsent.analytics) {
        loadAnalytics();
    }
}());
