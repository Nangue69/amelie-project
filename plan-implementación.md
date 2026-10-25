# AMÉLIE — Plan de implementación

> Documento vivo. El estado de cada tarea está en `tareas.md`. El historial de peticiones, en `prompts.md`.

---

## 1. Resumen ejecutivo

Tienda online propia para **AMÉLIE — More Than Lingerie**: lencería y corsetería premium.
Parte pública multiidioma (ES · EN · NL · IT) orientada a conversión y SEO, y panel de
administración privado pensado para una persona **sin perfil técnico**.

Colección inicial, extraída del *Product Development Book* (el PDF de la raíz):

| Ficha | Producto | Categoría | Identidad |
|-------|----------|-----------|-----------|
| 01 | **Blush Floral** | Conjunto de lencería coordinado | Rosa cipria, tul semitransparente, bordado floral |
| 05 | **The Muse** | Corsé / bustier estructurado | Satén chocolate, encaje mínimo, cordonado trasero |

---

## 2. Decisiones de arquitectura (y por qué)

### 2.1 Shopify frente a desarrollo propio — **punto a validar**

`CLAUDE.md` indica Shopify como plataforma preferida, pero al mismo tiempo pide:

- Stack **PHP + MySQL/MariaDB**, con HTML5, CSS3 y JavaScript nativos.
- **Panel de administración propio.**
- **Selector de varios temas visuales.**
- Blog, gestión de stock y de todos los textos e imágenes públicas.
- Cuatro idiomas con contenido traducible.

Ambas cosas no son compatibles: en Shopify el panel es el suyo, no se programa a medida,
y el stack sería Liquid sobre su infraestructura, no PHP sobre Hostinger.

**Decisión aplicada:** se construye la plataforma propia en PHP, porque es lo que fija de
forma explícita el apartado *Stack de tecnología* y es lo único que permite cumplir los
requisitos de panel propio, temas y administración total del contenido.

**Si más adelante se quiere ir a Shopify** (documentado, no implementado):

- El catálogo se exporta a CSV con el formato de importación de Shopify.
- La capa de pago está aislada tras una interfaz, de modo que se puede sustituir por
  *Shopify Buy Button* o *Storefront API* sin tocar el resto de la tienda.

### 2.2 Framework

**Micro-framework propio: MVC ligero, PSR-4, sin dependencias externas.**

Motivo: Laravel o Symfony exigen Composer, permisos de escritura, acceso SSH y versiones
de PHP concretas que en hosting compartido dan problemas. Aquí basta con **subir por FTP**:
sólo requiere PHP 8.0 o superior con PDO y `mod_rewrite`. Sin `composer install`.

### 2.3 Estructura de carpetas

```
amelie-project/
├── app/
│   ├── Core/            Router, Database, Auth, Csrf, Lang, Cart, View…
│   ├── Models/          Acceso a datos
│   ├── Controllers/
│   │   ├── Site/        Parte pública
│   │   └── Admin/       Panel privado
│   └── Views/
│       ├── layouts/     Plantillas base, pública y panel
│       ├── partials/    Componentes reutilizables
│       ├── site/
│       └── admin/
├── config/              Configuración (fuera del document root)
├── database/            schema.sql y seed.sql
├── storage/             Logs y caché
└── public/              ÚNICA carpeta expuesta al navegador
    ├── index.php        Front controller
    ├── assets/
    └── uploads/
```

El código de aplicación y la configuración quedan **fuera** del document root.

### 2.4 Idiomas

- URL con prefijo: `/es/`, `/en/`, `/nl/`, `/it/`.
- `hreflang` recíproco en todas las páginas, más `x-default`.
- Contenido traducible en tablas `*_translations`; los textos de interfaz, en `ui_strings`.
- Idioma por defecto configurable desde el panel. Se entrega con **español activo por
  defecto**, para facilitar la revisión del proyecto, y los otros tres completos. Cambiar
  el idioma principal a inglés es un clic en *Ajustes → Idiomas*.
- El panel de administración está **siempre en español**.

### 2.5 Tallas

`Blush Floral` necesita talla de sujetador y de tanga por separado; `The Muse`, una sola.
Se resuelve con `products.size_mode` (`single` o `bra_brief`) y una tabla
`product_variants` con las columnas `size`, `size_bra` y `size_brief`. El stock vive en la
variante, que es lo que realmente se vende.

### 2.6 Temas visuales

Cada tema es un archivo CSS que **sólo redefine variables de color**; la maquetación es
única para todos. Se eligen desde el panel. Se entregan cuatro: `borgogna` (por defecto),
`avorio`, `noir` y `blush`.

---

## 3. Identidad visual

Extraída de los logotipos de `img/`:

| Token | Color | Uso |
|-------|-------|-----|
| Burdeos | `#651D36` | Color de marca, titulares, footer |
| Marfil | `#F6F0E6` | Fondo principal |
| Oro champán | `#C4A36A` | Detalles, filetes, acentos |
| Chocolate | `#3E2A22` | The Muse, tema oscuro |
| Rosa cipria | `#F2D3D8` | Blush Floral |

Tipografía: *Cormorant Garamond* para titulares y *Jost* para texto, servidas desde Google Fonts.

### Fotografía

El PDF contenía dos láminas compuestas. Se han extraído y recortado **20 imágenes**
—modelo de frente y de espalda, producto suelto, y primeros planos de bordado, encaje,
herrajes y cordonado— a `public/assets/img/products/`.

> Son **referencias conceptuales de la fase de I+D**, no fotografía de producto acabado.
> La ficha de producto lo advierte y el panel permite sustituirlas sin tocar código.

---

## 4. Modelo de datos

26 tablas. El esquema completo está en `database/schema.sql`.

- **Contenido:** `languages`, `ui_strings`, `settings`, `themes`, `pages`,
  `page_translations`, `posts`, `post_translations`, `faqs`, `faq_translations`
- **Catálogo:** `categories`, `category_translations`, `products`,
  `product_translations`, `product_images`, `product_variants`,
  `size_guides`, `size_guide_rows`
- **Venta:** `orders`, `order_items`, `coupons`, `shipping_rates`
- **Relación:** `subscribers`, `messages`
- **Sistema:** `users`, `media`, `activity_log`

El carrito vive en sesión y **se revalida contra la base de datos** en cada render, con el
precio y el stock reales, para que no pueda manipularse desde el cliente.

---

## 5. Fases

| Fase | Contenido |
|------|-----------|
| 0 | Análisis, identidad visual y extracción de imágenes del PDF |
| 1 | Base de datos: esquema y datos iniciales en 4 idiomas |
| 2 | Núcleo: router, PDO, sesión, autenticación, CSRF, i18n, vistas |
| 3 | Parte pública: home, tienda, producto, Our Story, blog y páginas legales |
| 4 | Carrito y checkout: cupones, envíos, pedido y pago |
| 5 | Panel: acceso, escritorio, catálogo, stock, pedidos y contenido |
| 6 | Temas, Coming Soon, newsletter y cookies |
| 7 | SEO: hreflang, JSON-LD, sitemap, robots y Open Graph |
| 8 | Pulido responsive, accesibilidad y seguridad |

---

## 6. Seguridad

- Contraseñas con `password_hash()` (bcrypt).
- **Token CSRF en todos los formularios**, incluidos los del panel.
- Consultas exclusivamente con **sentencias preparadas** (PDO, con emulación desactivada).
- Escapado de toda la salida mediante un ayudante `e()` sobre `htmlspecialchars`.
- Rutas `/admin/*` bajo middleware de autenticación: sin sesión, al login.
- Cookie de sesión `HttpOnly` y `SameSite=Lax`, `Secure` bajo HTTPS, con
  `session_regenerate_id()` al entrar.
- Limitación de intentos de acceso: cinco fallos y bloqueo temporal.
- Subida de imágenes con lista blanca de MIME real (`finfo`), tamaño máximo y nombre saneado.
- `app/`, `config/`, `storage/` y `database/` quedan fuera del document root.
- Cabeceras `X-Frame-Options`, `X-Content-Type-Options`, `Referrer-Policy` y una CSP básica.

---

## 7. Cumplimiento de las preferencias de código

- `rem` con base de 10px (`html { font-size: 62.5% }`).
- HTML5 semántico: `header`, `nav`, `main`, `section`, `article`, `aside`, `footer`.
- CSS nativo con Grid y Flexbox. Sin preprocesadores ni frameworks.
- JavaScript nativo, sólo con `const` y `let`: **nunca `var`**.
- **Sin `alert`, `confirm` ni `prompt`**: modales y avisos propios con el estilo de la web.
- **Sin `innerHTML`**: el DOM se construye con `createElement` y `appendChild`.
- `preventDefault()` en todos los `submit` y en los `click` interceptados.

---

## 8. Optimizaciones propuestas

Recomendaciones surgidas del análisis:

1. **Caché de las consultas de catálogo** en `storage/cache`, en ficheros. La home y la
   tienda cambian poco y así se evita golpear MySQL en cada visita. *Implementado ya para
   `settings` y `ui_strings`.*
2. **Imágenes en WebP con `<picture>`** y `loading="lazy"`. El recorte ya genera JPEG
   progresivo; la conversión a WebP conviene hacerla en el momento de subir, cuando haya
   fotografía real.
3. **Confirmación doble de newsletter.** Ya están la columna `token` y el estado
   `pending`; sólo falta conectar el envío real cuando haya SMTP.
4. **Reserva de stock durante el checkout**, unos diez minutos, para evitar sobreventa en
   lanzamientos. Hoy el stock se descuenta al confirmar el pedido, que es suficiente para
   el volumen inicial.
5. **Pasarela de pago.** Se entrega con transferencia bancaria operativa y la integración
   con Stripe preparada: sólo hay que pegar las claves en *Ajustes → Pagos*. Para el
   mercado europeo conviene activar además iDEAL, para Países Bajos, y Bancontact desde el
   panel de Stripe.
6. **Copias de seguridad automáticas** de la base de datos desde el cron de Hostinger.
