# AMÉLIE — Tareas

Leyenda: `[ ]` pendiente · `[~]` en curso · `[x]` completada

Última actualización: 25 de octubre de 2026

---

## Fase 0 — Análisis y base visual

- [x] Estudiar las especificaciones de `CLAUDE.md`
- [x] Extraer la paleta de marca de los SVG de `img/`
- [x] Extraer el texto del PDF del Product Development Book
- [x] Extraer las dos láminas de imagen incrustadas en el PDF
- [x] Recortar 20 fotografías de producto utilizables
- [x] Redactar `plan-implementación.md`
- [x] Crear `prompts.md` y `tareas.md`

## Fase 1 — Base de datos

- [x] Esquema con las 26 tablas (`database/schema.sql`)
- [x] Datos iniciales: idiomas, ajustes y temas
- [x] Datos iniciales: 704 textos de interfaz en los 4 idiomas
- [x] Datos iniciales: catálogo Blush Floral y The Muse con 29 variantes y stock
- [x] Datos iniciales: páginas legales, FAQ y guía de tallas
- [x] Datos iniciales: 3 entradas de blog y usuario administrador

## Fase 2 — Núcleo de la aplicación

- [x] Autocarga PSR-4 y arranque (`bootstrap.php`)
- [x] Configuración y conexión PDO
- [x] Router con prefijo de idioma
- [x] Petición, respuesta y controlador base
- [x] Motor de vistas con plantillas y parciales
- [x] Sesión, mensajes flash y CSRF
- [x] Autenticación del panel con límite de intentos
- [x] Internacionalización y ayudantes de URL
- [x] Ajustes y temas cacheados en disco
- [x] Modelos de acceso a datos (13 modelos)
- [x] Saneado de entrada a UTF-8 válido

## Fase 3 — Parte pública

- [x] Layout público: cabecera, navegación, footer
- [x] Home con todas las secciones pedidas
- [x] Tienda con filtro por categoría y cuatro criterios de orden
- [x] Ficha de producto con galería y selección de tallas
- [x] Talla de sujetador y de tanga por separado en Blush Floral
- [x] Our Story sobre fondo marfil, titulares burdeos y detalles en oro
- [x] Blog (Journal): listado y entrada
- [x] Guía de tallas, envíos, FAQ y contacto
- [x] Páginas legales: privacidad, cookies y términos
- [x] Buscador
- [x] Página 404 con el estilo de la tienda

## Fase 4 — Venta

- [x] Carrito en sesión revalidado contra la base de datos
- [x] Añadir, actualizar y eliminar sin recargar la página
- [x] Carrito lateral desplegable
- [x] Cupones de descuento: porcentaje, importe fijo y envío gratuito
- [x] Cálculo de gastos de envío por zona de países
- [x] Checkout con resumen y validación
- [x] Creación del pedido y descuento de stock en transacción
- [x] Transferencia bancaria operativa; Stripe preparado para claves
- [x] Página de confirmación con los datos de la transferencia
- [x] Correos de confirmación a la clienta y aviso a la tienda

## Fase 5 — Panel de administración

- [x] Acceso y protección de todas las rutas
- [x] Escritorio con indicadores, últimos pedidos y avisos de stock
- [x] Catálogo: productos con traducciones en los 4 idiomas
- [x] Variantes y control de stock por talla
- [x] Subida y orden de fotografías de producto
- [x] Categorías traducibles
- [x] Pedidos, cambio de estado y número de seguimiento
- [x] Páginas de contenido
- [x] Journal
- [x] Preguntas frecuentes
- [x] Guía de tallas editable
- [x] Textos de la web, agrupados y buscables
- [x] Biblioteca de imágenes con copia de ruta
- [x] Suscriptoras con exportación a CSV
- [x] Mensajes del formulario de contacto
- [x] Cupones y zonas de envío
- [x] Ajustes generales, contacto, tienda, cobros, redes, SEO y portada
- [x] Selector de temas visuales
- [x] Gestión de idiomas
- [x] Cuentas de acceso con dos niveles de permiso
- [x] Registro de actividad

## Fase 6 — Captación y contenido

- [x] Modo Coming Soon que repliega toda la tienda
- [x] Newsletter con alta, aviso de duplicado y baja por enlace
- [x] Banner de cookies con consentimiento granular
- [x] Analítica cargada sólo con consentimiento
- [x] Formulario de contacto con campo trampa anti-robots

## Fase 7 — SEO

- [x] Metadatos, Open Graph y canónica
- [x] `hreflang` recíproco en los 4 idiomas más `x-default`
- [x] JSON-LD de organización, producto, artículo, FAQ y migas
- [x] `sitemap.xml` con los cuatro idiomas
- [x] `robots.txt` sensible al modo Coming Soon

## Fase 8 — Pulido y entrega

- [x] Responsive en parte pública y panel
- [x] Accesibilidad: foco visible, `aria`, etiquetas en todos los campos
- [x] Repaso de seguridad: CSRF, consultas preparadas, escapado, cabeceras
- [x] Instalador web (`public/install.php`) con comprobaciones del servidor
- [x] Documentación de instalación (`README.md`)
- [x] Pruebas de extremo a extremo del flujo de compra
- [x] Pruebas de las 27 rutas del panel

---

## Pendiente de ti

- [ ] Facilitar las credenciales de MySQL para dejar la base de datos creada
      en tu entorno, o ejecutar `public/install.php`
- [ ] Sustituir el IBAN y los datos fiscales de ejemplo en *Ajustes*
- [ ] Revisión de las páginas legales por una asesoría
- [ ] Sustituir las fotografías conceptuales por las del producto definitivo
- [ ] Decidir si se contrata Stripe para el cobro con tarjeta
