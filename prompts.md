# AMÉLIE — Historial de peticiones

Registro ordenado de cada petición recibida, de la más antigua a la más reciente.

---

## Prompt 001 — 2026-10-24

> Crea una tienda de lenceria fina basando en el fichero @CLAUDE.md

**Alcance interpretado:** construcción completa del proyecto a partir de las
especificaciones de `CLAUDE.md`, desde cero.

**Acciones realizadas:**

1. Análisis de `CLAUDE.md`, de la carpeta `img/` y del PDF de la raíz.
2. Extracción de la paleta de marca a partir de los SVG: burdeos `#651D36`,
   marfil `#F6F0E6` y oro champán `#C4A36A`.
3. Extracción del texto y de las dos láminas de imagen del PDF, y recorte de
   **20 fotografías de producto** para Blush Floral y The Muse.
4. Redacción de `plan-implementación.md` y de `tareas.md`.
5. Esquema de base de datos de 26 tablas, más datos iniciales en los cuatro idiomas.
6. Micro-framework MVC en PHP: router, PDO, sesión, autenticación, CSRF, i18n y vistas.
7. Parte pública completa: home, tienda, ficha de producto, Our Story, blog, páginas
   legales, carrito y checkout.
8. Panel de administración completo: escritorio, catálogo, stock, pedidos, contenido,
   blog, suscriptores, mensajes, cupones, envíos, temas y ajustes.
9. Modo Coming Soon, selector de temas visuales, newsletter y gestión de cookies.
10. SEO: `hreflang`, JSON-LD, `sitemap.xml`, `robots.txt` y Open Graph.

**Nota planteada al usuario:** `CLAUDE.md` menciona Shopify como plataforma preferida,
pero a la vez exige stack PHP, panel propio, multi-tema e i18n a medida. Son
incompatibles. Se ha seguido el stack explícito (PHP y MySQL) y se ha documentado la vía
de migración a Shopify en el plan de implementación.
