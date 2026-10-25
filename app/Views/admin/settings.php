<?php

use App\Core\View;

/** @var array $settings */
/** @var array $groups */
/** @var array $toggles */
/** @var array $media */

View::share('pageTitle', 'Ajustes');

$sections = [
    'general' => ['Generales', 'El nombre de la marca y cómo se comporta la tienda.'],
    'contact' => ['Datos de contacto', 'Aparecen en la página de contacto y en las páginas legales.'],
    'shop'    => ['Tienda', 'Moneda y condiciones de venta.'],
    'payment' => ['Cobros', 'Cómo te pagan las clientas.'],
    'social'  => ['Redes sociales', 'Si dejas una en blanco, no se muestra su icono.'],
    'seo'     => ['Buscadores', 'Cómo aparece la tienda en Google y en las redes.'],
    'home'    => ['Imágenes de la portada', 'Rutas de la sección «Imágenes».'],
    'privacy' => ['Privacidad', 'Aviso de cookies.'],
];

$labels = [
    'site_name'             => ['Nombre de la marca', ''],
    'site_tagline'          => ['Lema', 'Acompaña al logotipo.'],
    'default_lang'          => ['Idioma principal', 'El que ve quien entra sin elegir.'],
    'coming_soon'           => ['Modo Coming Soon', 'Con esto activo sólo se ve la página de presentación: no se puede comprar.'],
    'low_stock_threshold'   => ['Aviso de pocas existencias', 'A partir de cuántas unidades te avisamos.'],
    'products_per_page'     => ['Productos por página', ''],

    'contact_email'         => ['Correo de contacto', 'Aquí llegan los mensajes y los avisos de pedido.'],
    'contact_phone'         => ['Teléfono', ''],
    'contact_address'       => ['Dirección', ''],
    'company_legal_name'    => ['Razón social', 'Para las páginas legales y las facturas.'],
    'company_vat'           => ['NIF / CIF', ''],

    'currency'              => ['Moneda', 'Código de tres letras: EUR, GBP…'],
    'currency_symbol'       => ['Símbolo', ''],
    'free_shipping_from'    => ['Envío gratuito a partir de', 'Importe del pedido. Déjalo en 0 para desactivarlo.'],

    'payment_bank_transfer' => ['Aceptar transferencia bancaria', 'Funciona sin contratar nada.'],
    'bank_holder'           => ['Titular de la cuenta', ''],
    'bank_name'             => ['Banco', ''],
    'bank_iban'             => ['IBAN', 'Se envía a la clienta al confirmar el pedido.'],
    'bank_swift'            => ['BIC / SWIFT', ''],
    'payment_stripe'        => ['Aceptar tarjeta con Stripe', 'Necesita las dos claves de abajo.'],
    'stripe_public_key'     => ['Clave pública de Stripe', 'Empieza por pk_'],
    'stripe_secret_key'     => ['Clave secreta de Stripe', 'Empieza por sk_. No la compartas con nadie.'],

    'instagram_url'         => ['Instagram', ''],
    'facebook_url'          => ['Facebook', ''],
    'tiktok_url'            => ['TikTok', ''],
    'pinterest_url'         => ['Pinterest', ''],

    'meta_title_suffix'     => ['Texto que acompaña a cada título', 'Se añade al final del título de cada página.'],
    'og_image'              => ['Imagen al compartir', 'La que se ve al pegar un enlace en redes.'],
    'analytics_id'          => ['Identificador de Google Analytics', 'Déjalo en blanco si no usas analítica.'],
    'robots_index'          => ['Permitir que Google indexe la web', 'Desactívalo mientras la tienda no esté lista.'],

    'cookie_banner'         => ['Mostrar el aviso de cookies', 'Obligatorio en la Unión Europea si usas analítica.'],

    'home_hero_image'       => ['Fotografía principal', 'La imagen grande de la portada.'],
    'home_story_image'      => ['Fotografía de «Nuestra historia»', ''],
    'home_craft_image'      => ['Fotografía de «Fabricación italiana»', ''],
    'home_manifesto_image'  => ['Fotografía del manifiesto', ''],
];

$imageKeys   = ['og_image', 'home_hero_image', 'home_story_image', 'home_craft_image', 'home_manifesto_image'];
$numberKeys  = ['low_stock_threshold', 'products_per_page'];
$secretKeys  = ['stripe_secret_key'];
?>
<p class="page-intro">
    La configuración de la tienda. Cada apartado se explica debajo de su campo.
</p>

<form action="<?= e(admin_url('settings')) ?>" method="post" data-dirty-guard>
    <?= csrf_field() ?>

    <?php foreach ($groups as $group => $keys): ?>
        <?php [$sectionTitle, $sectionNote] = $sections[$group] ?? [ucfirst($group), '']; ?>
        <section class="card" style="margin-bottom:2.4rem;">
            <div class="card__head">
                <div><h2><?= e($sectionTitle) ?></h2><p><?= e($sectionNote) ?></p></div>
            </div>

            <div class="adm-grid">
                <?php foreach ($keys as $key): ?>
                    <?php
                    [$label, $hint] = $labels[$key] ?? [$key, ''];
                    $value = (string) ($settings[$key] ?? '');
                    ?>
                    <div class="adm-field<?= in_array($key, $imageKeys, true) ? ' adm-field--full' : '' ?>">
                        <?php if (in_array($key, $toggles, true)): ?>
                            <label class="adm-label" for="<?= e($key) ?>"><?= e($label) ?></label>
                            <input type="hidden" name="<?= e($key) ?>" value="0">
                            <label class="switch">
                                <input type="checkbox" id="<?= e($key) ?>" name="<?= e($key) ?>" value="1"
                                       <?= in_array($value, ['1', 'true', 'on'], true) ? 'checked' : '' ?>>
                                <span class="switch__track"></span>
                                <span><?= e($hint) ?></span>
                            </label>

                        <?php elseif ($key === 'default_lang'): ?>
                            <label for="<?= e($key) ?>"><?= e($label) ?></label>
                            <select class="adm-select" id="<?= e($key) ?>" name="<?= e($key) ?>">
                                <?php foreach ($langs as $code => $language): ?>
                                    <option value="<?= e($code) ?>" <?= $value === $code ? 'selected' : '' ?>>
                                        <?= e($language['native_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <p class="hint"><?= e($hint) ?></p>

                        <?php else: ?>
                            <label for="<?= e($key) ?>"><?= e($label) ?></label>
                            <input class="adm-input"
                                   type="<?= in_array($key, $secretKeys, true) ? 'password' : (in_array($key, $numberKeys, true) ? 'number' : 'text') ?>"
                                   id="<?= e($key) ?>" name="<?= e($key) ?>" value="<?= e($value) ?>"
                                   <?= in_array($key, $secretKeys, true) ? 'autocomplete="off"' : '' ?>>
                            <?php if ($hint !== ''): ?>
                                <p class="hint"><?= e($hint) ?></p>
                            <?php endif; ?>

                            <?php if (in_array($key, $imageKeys, true) && $value !== ''): ?>
                                <img src="<?= e(img($value)) ?>" alt="" loading="lazy"
                                     style="max-width:22rem;border-radius:.6rem;margin-top:.8rem;">
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endforeach; ?>

    <div class="actions-bar">
        <div class="spacer"></div>
        <button class="abtn" type="submit">Guardar ajustes</button>
    </div>
</form>

<?php if ($media !== []): ?>
    <section class="card" style="margin-top:2.4rem;">
        <div class="card__head">
            <div><h2>Rutas de imagen disponibles</h2><p>Cópialas y pégalas en los campos de arriba.</p></div>
        </div>

        <div class="table-wrap">
            <table class="table">
                <tbody>
                    <?php foreach ($media as $item): ?>
                        <tr>
                            <td><code style="font-size:1.2rem;"><?= e((string) $item['path']) ?></code></td>
                            <td class="actions">
                                <button class="abtn abtn--ghost abtn--sm" type="button"
                                        data-copy="<?= e((string) $item['path']) ?>">Copiar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
<?php endif; ?>
