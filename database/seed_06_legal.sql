-- =====================================================================
-- AMÉLIE — Datos iniciales 06: páginas legales
-- Textos de partida, editables desde Panel → Páginas.
-- Conviene que los revise una asesoría legal antes de abrir la tienda.
-- =====================================================================

SET NAMES utf8mb4;

-- =====================================================================
-- 6 · POLÍTICA DE PRIVACIDAD
-- =====================================================================
INSERT INTO `page_translations` (`page_id`,`lang`,`title`,`intro`,`body`,`meta_title`,`meta_description`) VALUES
(6,'es','Política de privacidad','Tus datos son tuyos. Esto es todo lo que hacemos con ellos.',
'<h2>Responsable del tratamiento</h2>
<p>AMÉLIE Lingerie, S.L. — NIF B00000000 — Barcelona, España. Correo de contacto: hola@amelie.com</p>
<h2>Qué datos recogemos y para qué</h2>
<table class="data-table"><thead><tr><th>Finalidad</th><th>Datos</th><th>Base legal</th><th>Conservación</th></tr></thead><tbody>
<tr><td>Gestionar tu pedido</td><td>Nombre, correo, teléfono, dirección de envío</td><td>Ejecución del contrato</td><td>6 años (obligación fiscal)</td></tr>
<tr><td>Enviarte la newsletter</td><td>Correo electrónico e idioma</td><td>Tu consentimiento</td><td>Hasta que te des de baja</td></tr>
<tr><td>Responder a tu mensaje</td><td>Nombre, correo y el contenido del mensaje</td><td>Tu consentimiento</td><td>1 año desde la última comunicación</td></tr>
<tr><td>Medir el uso de la web</td><td>Datos de navegación agregados</td><td>Tu consentimiento (cookies)</td><td>Según la política de cookies</td></tr>
</tbody></table>
<h2>Con quién los compartimos</h2>
<p>Sólo con quien necesitamos para que el pedido llegue: la empresa de transporte, la pasarela de pago, el proveedor de alojamiento web y el de correo electrónico. Todos ellos actúan como encargados del tratamiento y están sujetos a contrato. <strong>No vendemos ni cedemos tus datos a terceros con fines comerciales.</strong></p>
<h2>Tus derechos</h2>
<p>Puedes acceder a tus datos, rectificarlos, suprimirlos, limitar u oponerte a su tratamiento y pedir su portabilidad. Basta con escribirnos a hola@amelie.com desde tu dirección de correo. Si consideras que no hemos atendido tu solicitud correctamente, puedes reclamar ante la Agencia Española de Protección de Datos (www.aepd.es).</p>
<h2>Seguridad</h2>
<p>La web funciona bajo HTTPS, las contraseñas se almacenan cifradas y no guardamos en ningún momento los datos de tu tarjeta: los gestiona directamente la pasarela de pago.</p>
<h2>Cambios</h2>
<p>Si modificamos esta política te lo indicaremos en esta misma página con la fecha de actualización.</p>',
'Política de privacidad','Cómo trata AMÉLIE tus datos personales: finalidades, base legal, plazos de conservación y cómo ejercer tus derechos.'),

(6,'en','Privacy policy','Your data is yours. This is everything we do with it.',
'<h2>Data controller</h2>
<p>AMÉLIE Lingerie, S.L. — VAT B00000000 — Barcelona, Spain. Contact: hola@amelie.com</p>
<h2>What we collect and why</h2>
<table class="data-table"><thead><tr><th>Purpose</th><th>Data</th><th>Legal basis</th><th>Retention</th></tr></thead><tbody>
<tr><td>Processing your order</td><td>Name, email, phone, shipping address</td><td>Performance of the contract</td><td>6 years (tax obligation)</td></tr>
<tr><td>Sending the newsletter</td><td>Email and language</td><td>Your consent</td><td>Until you unsubscribe</td></tr>
<tr><td>Replying to your message</td><td>Name, email and message content</td><td>Your consent</td><td>1 year from the last contact</td></tr>
<tr><td>Measuring site usage</td><td>Aggregated browsing data</td><td>Your consent (cookies)</td><td>As per the cookie policy</td></tr>
</tbody></table>
<h2>Who we share it with</h2>
<p>Only those we need for your order to reach you: the carrier, the payment gateway, the web hosting provider and the email provider. All act as data processors under contract. <strong>We do not sell or transfer your data to third parties for commercial purposes.</strong></p>
<h2>Your rights</h2>
<p>You may access, rectify, erase, restrict or object to the processing of your data, and request its portability. Simply write to hola@amelie.com from your email address. If you believe we have not handled your request properly, you may complain to the Spanish Data Protection Agency (www.aepd.es).</p>
<h2>Security</h2>
<p>The site runs over HTTPS, passwords are stored encrypted and we never store your card details: they are handled directly by the payment gateway.</p>
<h2>Changes</h2>
<p>If we amend this policy we will say so on this page, with the date of the update.</p>',
'Privacy policy','How AMÉLIE handles your personal data: purposes, legal basis, retention periods and how to exercise your rights.'),

(6,'nl','Privacybeleid','Jouw gegevens zijn van jou. Dit is alles wat wij ermee doen.',
'<h2>Verwerkingsverantwoordelijke</h2>
<p>AMÉLIE Lingerie, S.L. — btw-nummer B00000000 — Barcelona, Spanje. Contact: hola@amelie.com</p>
<h2>Wat we verzamelen en waarom</h2>
<table class="data-table"><thead><tr><th>Doel</th><th>Gegevens</th><th>Grondslag</th><th>Bewaartermijn</th></tr></thead><tbody>
<tr><td>Je bestelling verwerken</td><td>Naam, e-mail, telefoon, verzendadres</td><td>Uitvoering van de overeenkomst</td><td>6 jaar (fiscale plicht)</td></tr>
<tr><td>De nieuwsbrief versturen</td><td>E-mail en taal</td><td>Jouw toestemming</td><td>Tot je je afmeldt</td></tr>
<tr><td>Je bericht beantwoorden</td><td>Naam, e-mail en inhoud van het bericht</td><td>Jouw toestemming</td><td>1 jaar na het laatste contact</td></tr>
<tr><td>Gebruik van de site meten</td><td>Geaggregeerde surfgegevens</td><td>Jouw toestemming (cookies)</td><td>Volgens het cookiebeleid</td></tr>
</tbody></table>
<h2>Met wie we ze delen</h2>
<p>Alleen met wie nodig is om je bestelling te laten aankomen: de vervoerder, de betaaldienst, de hostingprovider en de e-mailprovider. Allen treden op als verwerker onder contract. <strong>Wij verkopen of verstrekken je gegevens niet aan derden voor commerciële doeleinden.</strong></p>
<h2>Je rechten</h2>
<p>Je mag je gegevens inzien, corrigeren, wissen, de verwerking beperken of er bezwaar tegen maken, en overdraagbaarheid vragen. Schrijf naar hola@amelie.com vanaf je e-mailadres. Vind je dat we je verzoek niet goed hebben behandeld, dan kun je klagen bij de Spaanse gegevensbeschermingsautoriteit (www.aepd.es) of bij de Autoriteit Persoonsgegevens.</p>
<h2>Beveiliging</h2>
<p>De site draait via HTTPS, wachtwoorden worden versleuteld bewaard en we slaan je kaartgegevens nooit op: die gaan rechtstreeks via de betaaldienst.</p>
<h2>Wijzigingen</h2>
<p>Als we dit beleid aanpassen, melden we dat op deze pagina met de datum van de update.</p>',
'Privacybeleid','Hoe AMÉLIE omgaat met je persoonsgegevens: doeleinden, grondslag, bewaartermijnen en hoe je je rechten uitoefent.'),

(6,'it','Informativa sulla privacy','I tuoi dati sono tuoi. Questo è tutto ciò che ne facciamo.',
'<h2>Titolare del trattamento</h2>
<p>AMÉLIE Lingerie, S.L. — P. IVA B00000000 — Barcellona, Spagna. Contatto: hola@amelie.com</p>
<h2>Quali dati raccogliamo e perché</h2>
<table class="data-table"><thead><tr><th>Finalità</th><th>Dati</th><th>Base giuridica</th><th>Conservazione</th></tr></thead><tbody>
<tr><td>Gestire il tuo ordine</td><td>Nome, email, telefono, indirizzo di spedizione</td><td>Esecuzione del contratto</td><td>6 anni (obbligo fiscale)</td></tr>
<tr><td>Inviarti la newsletter</td><td>Email e lingua</td><td>Il tuo consenso</td><td>Fino alla cancellazione</td></tr>
<tr><td>Rispondere al tuo messaggio</td><td>Nome, email e contenuto del messaggio</td><td>Il tuo consenso</td><td>1 anno dall''ultimo contatto</td></tr>
<tr><td>Misurare l''uso del sito</td><td>Dati di navigazione aggregati</td><td>Il tuo consenso (cookie)</td><td>Secondo la cookie policy</td></tr>
</tbody></table>
<h2>Con chi li condividiamo</h2>
<p>Solo con chi serve perché il tuo ordine arrivi: il corriere, il gateway di pagamento, il fornitore di hosting e quello di posta elettronica. Tutti agiscono come responsabili del trattamento, vincolati da contratto. <strong>Non vendiamo né cediamo i tuoi dati a terzi per finalità commerciali.</strong></p>
<h2>I tuoi diritti</h2>
<p>Puoi accedere ai tuoi dati, rettificarli, cancellarli, limitarne od opporti al trattamento e chiederne la portabilità. Scrivi a hola@amelie.com dal tuo indirizzo email. Se ritieni che non abbiamo gestito correttamente la tua richiesta, puoi rivolgerti all''Agenzia spagnola per la protezione dei dati (www.aepd.es) o al Garante per la protezione dei dati personali.</p>
<h2>Sicurezza</h2>
<p>Il sito funziona in HTTPS, le password sono conservate cifrate e non salviamo mai i dati della tua carta: li gestisce direttamente il gateway di pagamento.</p>
<h2>Modifiche</h2>
<p>Se modifichiamo questa informativa lo indicheremo in questa stessa pagina con la data di aggiornamento.</p>',
'Informativa sulla privacy','Come AMÉLIE tratta i tuoi dati personali: finalità, base giuridica, tempi di conservazione e come esercitare i tuoi diritti.');

-- =====================================================================
-- 7 · POLÍTICA DE COOKIES
-- =====================================================================
INSERT INTO `page_translations` (`page_id`,`lang`,`title`,`intro`,`body`,`meta_title`,`meta_description`) VALUES
(7,'es','Política de cookies','Qué cookies usamos y cómo decidir sobre ellas.',
'<h2>Qué es una cookie</h2>
<p>Un archivo pequeño que la web guarda en tu navegador para recordar algo entre una página y la siguiente: por ejemplo, qué llevas en el carrito o en qué idioma estás navegando.</p>
<h2>Las que usamos</h2>
<table class="data-table"><thead><tr><th>Cookie</th><th>Tipo</th><th>Para qué</th><th>Duración</th></tr></thead><tbody>
<tr><td>amelie_session</td><td>Necesaria</td><td>Mantener tu sesión y tu carrito</td><td>Sesión</td></tr>
<tr><td>amelie_lang</td><td>Necesaria</td><td>Recordar tu idioma</td><td>1 año</td></tr>
<tr><td>amelie_consent</td><td>Necesaria</td><td>Guardar tu decisión sobre cookies</td><td>1 año</td></tr>
<tr><td>_ga, _ga_*</td><td>Analítica</td><td>Medir visitas de forma agregada</td><td>Hasta 2 años</td></tr>
</tbody></table>
<p>Las cookies analíticas <strong>sólo se instalan si las aceptas</strong>. Las necesarias no requieren consentimiento porque sin ellas la tienda no funciona.</p>
<h2>Cómo cambiar tu decisión</h2>
<p>Puedes modificar tus preferencias en cualquier momento desde el enlace <em>Preferencias de cookies</em> del pie de página, o borrando las cookies desde la configuración de tu navegador.</p>',
'Política de cookies','Qué cookies utiliza AMÉLIE, para qué sirven, cuánto duran y cómo cambiar tus preferencias en cualquier momento.'),

(7,'en','Cookie policy','Which cookies we use and how to decide about them.',
'<h2>What a cookie is</h2>
<p>A small file the site stores in your browser to remember something between one page and the next: what is in your cart, for instance, or which language you are browsing in.</p>
<h2>The ones we use</h2>
<table class="data-table"><thead><tr><th>Cookie</th><th>Type</th><th>Purpose</th><th>Duration</th></tr></thead><tbody>
<tr><td>amelie_session</td><td>Necessary</td><td>Keeping your session and your cart</td><td>Session</td></tr>
<tr><td>amelie_lang</td><td>Necessary</td><td>Remembering your language</td><td>1 year</td></tr>
<tr><td>amelie_consent</td><td>Necessary</td><td>Storing your cookie decision</td><td>1 year</td></tr>
<tr><td>_ga, _ga_*</td><td>Analytics</td><td>Measuring visits in aggregate</td><td>Up to 2 years</td></tr>
</tbody></table>
<p>Analytics cookies are <strong>only installed if you accept them</strong>. Necessary cookies require no consent because the shop cannot work without them.</p>
<h2>How to change your decision</h2>
<p>You can change your preferences at any time from the <em>Cookie preferences</em> link in the footer, or by clearing cookies in your browser settings.</p>',
'Cookie policy','Which cookies AMÉLIE uses, what they are for, how long they last and how to change your preferences at any time.'),

(7,'nl','Cookiebeleid','Welke cookies we gebruiken en hoe je erover beslist.',
'<h2>Wat een cookie is</h2>
<p>Een klein bestand dat de site in je browser bewaart om iets te onthouden tussen de ene pagina en de volgende: wat er in je winkelmand zit bijvoorbeeld, of in welke taal je kijkt.</p>
<h2>Die wij gebruiken</h2>
<table class="data-table"><thead><tr><th>Cookie</th><th>Type</th><th>Doel</th><th>Duur</th></tr></thead><tbody>
<tr><td>amelie_session</td><td>Noodzakelijk</td><td>Je sessie en winkelmand bewaren</td><td>Sessie</td></tr>
<tr><td>amelie_lang</td><td>Noodzakelijk</td><td>Je taal onthouden</td><td>1 jaar</td></tr>
<tr><td>amelie_consent</td><td>Noodzakelijk</td><td>Je cookiekeuze bewaren</td><td>1 jaar</td></tr>
<tr><td>_ga, _ga_*</td><td>Analyse</td><td>Bezoeken geaggregeerd meten</td><td>Tot 2 jaar</td></tr>
</tbody></table>
<p>Analysecookies worden <strong>alleen geplaatst als je ze accepteert</strong>. Voor noodzakelijke cookies is geen toestemming nodig, want zonder deze werkt de shop niet.</p>
<h2>Je keuze wijzigen</h2>
<p>Je kunt je voorkeuren altijd aanpassen via de link <em>Cookievoorkeuren</em> in de voettekst, of door cookies te wissen in je browserinstellingen.</p>',
'Cookiebeleid','Welke cookies AMÉLIE gebruikt, waarvoor ze dienen, hoe lang ze bewaard blijven en hoe je je voorkeuren wijzigt.'),

(7,'it','Cookie policy','Quali cookie usiamo e come decidere su di essi.',
'<h2>Che cos''è un cookie</h2>
<p>Un piccolo file che il sito salva nel tuo browser per ricordare qualcosa da una pagina all''altra: per esempio cosa c''è nel carrello o in quale lingua stai navigando.</p>
<h2>Quelli che usiamo</h2>
<table class="data-table"><thead><tr><th>Cookie</th><th>Tipo</th><th>Finalità</th><th>Durata</th></tr></thead><tbody>
<tr><td>amelie_session</td><td>Necessario</td><td>Mantenere sessione e carrello</td><td>Sessione</td></tr>
<tr><td>amelie_lang</td><td>Necessario</td><td>Ricordare la tua lingua</td><td>1 anno</td></tr>
<tr><td>amelie_consent</td><td>Necessario</td><td>Salvare la tua scelta sui cookie</td><td>1 anno</td></tr>
<tr><td>_ga, _ga_*</td><td>Analitico</td><td>Misurare le visite in forma aggregata</td><td>Fino a 2 anni</td></tr>
</tbody></table>
<p>I cookie analitici <strong>vengono installati solo se li accetti</strong>. Quelli necessari non richiedono consenso perché senza di essi lo shop non funziona.</p>
<h2>Come cambiare la tua scelta</h2>
<p>Puoi modificare le preferenze in qualsiasi momento dal link <em>Preferenze cookie</em> nel piè di pagina, oppure cancellando i cookie dalle impostazioni del browser.</p>',
'Cookie policy','Quali cookie usa AMÉLIE, a cosa servono, quanto durano e come modificare le tue preferenze in qualsiasi momento.');

-- =====================================================================
-- 8 · TÉRMINOS Y CONDICIONES
-- =====================================================================
INSERT INTO `page_translations` (`page_id`,`lang`,`title`,`intro`,`body`,`meta_title`,`meta_description`) VALUES
(8,'es','Términos y condiciones','Las condiciones de compra en amelie.com.',
'<h2>1. Quiénes somos</h2>
<p>AMÉLIE Lingerie, S.L., NIF B00000000, con domicilio en Barcelona (España) y correo de contacto hola@amelie.com.</p>
<h2>2. Objeto</h2>
<p>Estas condiciones regulan la compra de productos a través de esta web. Al confirmar un pedido declaras ser mayor de edad y aceptar estas condiciones.</p>
<h2>3. Productos y precios</h2>
<p>Los precios se muestran en euros e incluyen el IVA aplicable. Los gastos de envío se calculan en el checkout antes de confirmar el pedido. Nos reservamos el derecho a modificar los precios; el precio aplicable es el vigente en el momento de confirmar la compra.</p>
<p>Las imágenes de la Colección 01 corresponden a la fase de desarrollo. Se sustituirán por fotografía del producto definitivo antes de su puesta a la venta.</p>
<h2>4. Disponibilidad</h2>
<p>El stock se actualiza en tiempo real. Si, de forma excepcional, una pieza no estuviera disponible después de confirmar tu pedido, te avisaremos y te devolveremos el importe íntegro.</p>
<h2>5. Proceso de compra</h2>
<p>Añade las piezas al carrito, elige tu talla, introduce tus datos de envío y selecciona la forma de pago. Recibirás la confirmación por correo electrónico. El contrato se perfecciona con esa confirmación.</p>
<h2>6. Formas de pago</h2>
<p>Aceptamos transferencia bancaria y tarjeta de crédito o débito a través de una pasarela segura. En transferencia, el pedido se prepara al recibir el abono; reservamos tu talla durante 5 días naturales.</p>
<h2>7. Envíos</h2>
<p>Plazos y costes detallados en la página de <a href="/es/shipping-returns">Envíos y devoluciones</a>.</p>
<h2>8. Desistimiento y devoluciones</h2>
<p>Dispones de 14 días naturales desde la recepción para desistir, sin justificación. Por razones de higiene, la lencería y la corsetería sólo se admiten sin usar, con todas las etiquetas y el precinto sanitario intactos, conforme al artículo 103.e del Real Decreto Legislativo 1/2007.</p>
<h2>9. Garantía</h2>
<p>Los productos tienen la garantía legal de conformidad de 3 años. Si una pieza presenta un defecto de fabricación, escríbenos con una fotografía y lo resolvemos.</p>
<h2>10. Resolución de conflictos</h2>
<p>Puedes acudir a la plataforma europea de resolución de litigios en línea: ec.europa.eu/consumers/odr. Estas condiciones se rigen por la legislación española.</p>',
'Términos y condiciones','Condiciones de compra, pago, envío, desistimiento y garantía en la tienda online de AMÉLIE.'),

(8,'en','Terms and conditions','The conditions of purchase at amelie.com.',
'<h2>1. Who we are</h2>
<p>AMÉLIE Lingerie, S.L., VAT B00000000, registered in Barcelona (Spain), contact hola@amelie.com.</p>
<h2>2. Purpose</h2>
<p>These conditions govern the purchase of products through this website. By confirming an order you declare that you are of legal age and accept these conditions.</p>
<h2>3. Products and prices</h2>
<p>Prices are shown in euros and include applicable VAT. Shipping costs are calculated at checkout before you confirm the order. We reserve the right to change prices; the price that applies is the one in force when the purchase is confirmed.</p>
<p>The images of Collection 01 belong to the development phase. They will be replaced by photography of the final product before it goes on sale.</p>
<h2>4. Availability</h2>
<p>Stock is updated in real time. If, exceptionally, a piece is unavailable after you confirm your order, we will let you know and refund the full amount.</p>
<h2>5. Purchase process</h2>
<p>Add the pieces to your cart, choose your size, enter your shipping details and select a payment method. You will receive confirmation by email. The contract is formed with that confirmation.</p>
<h2>6. Payment methods</h2>
<p>We accept bank transfer and credit or debit card through a secure gateway. With a transfer, the order is prepared once the payment arrives; we hold your size for 5 calendar days.</p>
<h2>7. Shipping</h2>
<p>Times and costs are detailed on the <a href="/en/shipping-returns">Shipping and returns</a> page.</p>
<h2>8. Withdrawal and returns</h2>
<p>You have 14 calendar days from delivery to withdraw, without justification. For hygiene reasons, lingerie and corsetry are only accepted unworn, with all labels and the hygiene seal intact, in accordance with article 103.e of Spanish Royal Legislative Decree 1/2007.</p>
<h2>9. Warranty</h2>
<p>Products carry the 3-year legal warranty of conformity. If a piece has a manufacturing defect, write to us with a photograph and we will resolve it.</p>
<h2>10. Dispute resolution</h2>
<p>You may use the European online dispute resolution platform: ec.europa.eu/consumers/odr. These conditions are governed by Spanish law.</p>',
'Terms and conditions','Conditions of purchase, payment, shipping, withdrawal and warranty at the AMÉLIE online shop.'),

(8,'nl','Algemene voorwaarden','De koopvoorwaarden op amelie.com.',
'<h2>1. Wie wij zijn</h2>
<p>AMÉLIE Lingerie, S.L., btw-nummer B00000000, gevestigd in Barcelona (Spanje), contact hola@amelie.com.</p>
<h2>2. Doel</h2>
<p>Deze voorwaarden regelen de aankoop van producten via deze website. Door een bestelling te bevestigen verklaar je meerderjarig te zijn en deze voorwaarden te aanvaarden.</p>
<h2>3. Producten en prijzen</h2>
<p>Prijzen worden getoond in euro en zijn inclusief de toepasselijke btw. Verzendkosten worden bij het afrekenen berekend voordat je de bestelling bevestigt. Wij behouden ons het recht voor prijzen te wijzigen; de geldende prijs is die op het moment van bevestiging.</p>
<p>De beelden van Collectie 01 horen bij de ontwikkelingsfase. Ze worden vóór de verkoop vervangen door fotografie van het definitieve product.</p>
<h2>4. Beschikbaarheid</h2>
<p>De voorraad wordt in realtime bijgewerkt. Mocht een stuk bij uitzondering toch niet beschikbaar zijn nadat je hebt besteld, dan laten we het je weten en betalen we het volledige bedrag terug.</p>
<h2>5. Bestelproces</h2>
<p>Voeg de stukken toe aan je winkelmand, kies je maat, vul je verzendgegevens in en kies een betaalmethode. Je ontvangt de bevestiging per e-mail. De overeenkomst komt met die bevestiging tot stand.</p>
<h2>6. Betaalmethoden</h2>
<p>Wij accepteren bankoverschrijving en creditcard of debetkaart via een beveiligde betaaldienst. Bij een overschrijving wordt de bestelling klaargemaakt zodra de betaling binnen is; we houden je maat 5 kalenderdagen apart.</p>
<h2>7. Verzending</h2>
<p>Tijden en kosten staan op de pagina <a href="/nl/shipping-returns">Verzending en retour</a>.</p>
<h2>8. Herroeping en retour</h2>
<p>Je hebt 14 kalenderdagen vanaf ontvangst om zonder opgaaf van reden te herroepen. Om hygiënische redenen worden lingerie en korsetterie alleen ongedragen aangenomen, met alle labels en het hygiënezegel intact, conform artikel 103.e van het Spaanse Koninklijk Wetsbesluit 1/2007.</p>
<h2>9. Garantie</h2>
<p>Op de producten rust de wettelijke conformiteitsgarantie van 3 jaar. Heeft een stuk een fabricagefout, schrijf ons dan met een foto en wij lossen het op.</p>
<h2>10. Geschillen</h2>
<p>Je kunt terecht bij het Europese platform voor onlinegeschillenbeslechting: ec.europa.eu/consumers/odr. Op deze voorwaarden is Spaans recht van toepassing.</p>',
'Algemene voorwaarden','Voorwaarden voor aankoop, betaling, verzending, herroeping en garantie in de online shop van AMÉLIE.'),

(8,'it','Termini e condizioni','Le condizioni di acquisto su amelie.com.',
'<h2>1. Chi siamo</h2>
<p>AMÉLIE Lingerie, S.L., P. IVA B00000000, con sede a Barcellona (Spagna), contatto hola@amelie.com.</p>
<h2>2. Oggetto</h2>
<p>Queste condizioni regolano l''acquisto di prodotti attraverso questo sito. Confermando un ordine dichiari di essere maggiorenne e di accettare queste condizioni.</p>
<h2>3. Prodotti e prezzi</h2>
<p>I prezzi sono indicati in euro e includono l''IVA applicabile. Le spese di spedizione si calcolano al checkout prima di confermare l''ordine. Ci riserviamo il diritto di modificare i prezzi; vale quello in vigore al momento della conferma.</p>
<p>Le immagini della Collezione 01 appartengono alla fase di sviluppo. Saranno sostituite dalla fotografia del prodotto definitivo prima della messa in vendita.</p>
<h2>4. Disponibilità</h2>
<p>Le giacenze si aggiornano in tempo reale. Se, in via eccezionale, un pezzo non fosse disponibile dopo la conferma dell''ordine, te lo comunicheremo e rimborseremo l''intero importo.</p>
<h2>5. Processo di acquisto</h2>
<p>Aggiungi i pezzi al carrello, scegli la taglia, inserisci i dati di spedizione e seleziona il metodo di pagamento. Riceverai la conferma via email. Il contratto si perfeziona con tale conferma.</p>
<h2>6. Metodi di pagamento</h2>
<p>Accettiamo bonifico bancario e carta di credito o debito tramite gateway sicuro. Con bonifico, l''ordine viene preparato alla ricezione del pagamento; teniamo la tua taglia per 5 giorni di calendario.</p>
<h2>7. Spedizioni</h2>
<p>Tempi e costi dettagliati nella pagina <a href="/it/shipping-returns">Spedizioni e resi</a>.</p>
<h2>8. Recesso e resi</h2>
<p>Hai 14 giorni di calendario dalla consegna per recedere, senza motivazione. Per ragioni igieniche, lingerie e corsetteria si accettano solo non indossate, con tutte le etichette e il sigillo igienico intatti, ai sensi dell''articolo 103.e del Regio Decreto Legislativo spagnolo 1/2007.</p>
<h2>9. Garanzia</h2>
<p>I prodotti godono della garanzia legale di conformità di 3 anni. Se un capo presenta un difetto di fabbricazione, scrivici con una fotografia e lo risolviamo.</p>
<h2>10. Risoluzione delle controversie</h2>
<p>Puoi rivolgerti alla piattaforma europea di risoluzione delle controversie online: ec.europa.eu/consumers/odr. Queste condizioni sono regolate dalla legge spagnola.</p>',
'Termini e condizioni','Condizioni di acquisto, pagamento, spedizione, recesso e garanzia nello shop online di AMÉLIE.');
