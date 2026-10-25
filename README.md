# AMÉLIE — More Than Lingerie

Tienda online de lencería y corsetería premium: parte pública en cuatro idiomas
(español, inglés, neerlandés e italiano) y panel de gestión privado.

- **Plan técnico:** [plan-implementación.md](plan-implementación.md)
- **Estado de las tareas:** [tareas.md](tareas.md)
- **Historial de peticiones:** [prompts.md](prompts.md)

---

## Qué necesitas

- PHP **8.0 o superior** con las extensiones `pdo_mysql`, `mbstring` y `fileinfo`
- MySQL 5.7+ o MariaDB 10.3+
- Apache con `mod_rewrite` (es lo que lleva Hostinger de serie)

No hace falta Composer, ni Node, ni acceso por SSH.

---

## Instalación en Hostinger (o en cualquier alojamiento compartido)

1. **Crea la base de datos** desde el panel de tu alojamiento y apunta el nombre,
   el usuario y la contraseña.

2. **Sube los archivos** por FTP o desde el gestor de archivos.

   Lo ideal es que el dominio apunte a la carpeta `public/`. Si tu alojamiento no
   deja cambiar eso y el dominio apunta a la raíz, no pasa nada: el archivo
   `.htaccess` de la raíz ya redirige a `public/`.

3. **Abre `tudominio.com/install.php`** en el navegador y rellena el formulario.
   El instalador crea las tablas, carga la Colección 01 en los cuatro idiomas y
   crea tu cuenta de acceso.

4. **Borra `public/install.php`** del servidor. El instalador te lo recuerda.

5. Entra en **`tudominio.com/admin`** con el correo y la contraseña que elegiste.

### Instalación manual, si lo prefieres

```bash
mysql -u usuario -p tu_base < database/schema.sql
mysql -u usuario -p tu_base < database/seed_01_core.sql
mysql -u usuario -p tu_base < database/seed_02_ui_es_en.sql
mysql -u usuario -p tu_base < database/seed_03_ui_nl_it.sql
mysql -u usuario -p tu_base < database/seed_04_catalog.sql
mysql -u usuario -p tu_base < database/seed_05_pages.sql
mysql -u usuario -p tu_base < database/seed_06_legal.sql
mysql -u usuario -p tu_base < database/seed_07_faq_blog.sql
```

Después copia `config/config.example.php` a `config/config.php` y rellena los
datos de conexión.

La cuenta que viene en los datos iniciales es **admin@amelie.com** con contraseña
**Amelie2026!**. Cámbiala en *Panel → Cuentas de acceso* nada más entrar.

---

## Probarlo en tu ordenador

Con PHP instalado (por ejemplo, el de XAMPP), desde la carpeta del proyecto:

```bash
php -S localhost:8080 -t public public/router-dev.php
```

Y abre `http://localhost:8080`. No necesitas Apache para desarrollar.

---

## Primeros pasos en el panel

El panel está en `/admin` y todo él en español.

| Dónde | Para qué |
|-------|----------|
| **Ajustes** | Nombre de la marca, correo, moneda, IBAN, redes sociales y modo Coming Soon |
| **Aspecto de la web** | Cambiar entre los cuatro temas visuales |
| **Productos** | Fotos, textos en cada idioma, precio y tallas |
| **Existencias** | Las unidades de cada talla, en una sola pantalla |
| **Pedidos** | Ver cada compra y marcar cuándo se envía |
| **Textos de la web** | Cambiar cualquier palabra de la tienda, idioma a idioma |
| **Páginas** | Our Story, envíos, legales… |

### Antes de abrir al público

1. En **Ajustes**, cambia el IBAN, el correo de contacto y los datos fiscales.
2. Revisa las páginas legales: los textos son un punto de partida, conviene que
   los valide una asesoría.
3. Sustituye las fotografías por las del producto definitivo. Las actuales son
   referencias de la fase de desarrollo y la ficha de producto lo advierte.
4. Desactiva el **modo Coming Soon** cuando la colección esté lista.
5. Comprueba que **«Permitir que Google indexe la web»** está activado.

---

## Cómo está organizado

```
amelie-project/
├── app/            Código de la aplicación (fuera del alcance del navegador)
│   ├── Core/       Router, base de datos, sesión, carrito, idiomas…
│   ├── Models/     Acceso a datos
│   ├── Controllers/
│   └── Views/
├── config/         Configuración
├── database/       Esquema y datos iniciales
├── storage/        Registros y caché
├── img/            Logotipos originales de la marca
└── public/         Lo único que ve el navegador
    ├── index.php
    ├── install.php (bórralo tras instalar)
    ├── assets/
    └── uploads/
```

---

## Mantenimiento

- **Copias de seguridad:** exporta la base de datos y la carpeta `public/uploads`
  con regularidad. En Hostinger puedes programarlo desde el panel.
- **Registro de errores:** `storage/logs/php-error.log`.
- **Caché:** si cambias ajustes directamente en la base de datos, borra
  `storage/cache/settings.php` para que se refresquen.
- **Depuración:** pon `'debug' => true` en `config/config.php` sólo mientras
  investigas un problema, nunca en producción.

---

## Pagos

Se entrega con **transferencia bancaria** operativa: en cuanto pongas el IBAN en
Ajustes, funciona.

Para cobrar con tarjeta, crea una cuenta en Stripe, pega las dos claves en
*Ajustes → Cobros* y activa el interruptor. Para el mercado europeo conviene
habilitar además **iDEAL** (Países Bajos) y **Bancontact** (Bélgica) desde el
panel de Stripe.
