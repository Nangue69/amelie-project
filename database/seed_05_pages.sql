-- =====================================================================
-- AMÉLIE — Datos iniciales 05: páginas de contenido
-- =====================================================================

SET NAMES utf8mb4;

INSERT INTO `pages` (`id`,`slug`,`template`,`is_system`,`is_active`,`position`) VALUES
(1,'our-story','story',1,1,1),
(2,'size-guide','sizeguide',1,1,2),
(3,'shipping-returns','default',1,1,3),
(4,'faq','faq',1,1,4),
(5,'contact','contact',1,1,5),
(6,'privacy','default',1,1,6),
(7,'cookies','default',1,1,7),
(8,'terms','default',1,1,8);

-- =====================================================================
-- 1 · OUR STORY
-- =====================================================================
INSERT INTO `page_translations` (`page_id`,`lang`,`title`,`intro`,`body`,`meta_title`,`meta_description`) VALUES
(1,'es','Nuestra historia','More Than Lingerie',
'<p>AMÉLIE nace de la convicción de que cada mujer merece la libertad de definir su propia historia.</p>
<p>Una historia de confianza, independencia y feminidad: sin expectativas, sin limitaciones.</p>
<p>Creemos que la lencería es más que lo que llevamos debajo de la ropa. Es una forma de expresar quiénes somos, cómo nos sentimos y la confianza que llevamos dentro.</p>
<p>Inspirada en la elegancia atemporal, en los detalles delicados y en el arte de la artesanía italiana, AMÉLIE reúne lencería romántica y corsetería sofisticada, diseñadas para celebrar cada faceta de la feminidad.</p>
<p class="p-accent">Delicada y poderosa. Sutil e intrépida. Romántica y audaz.</p>
<p>Cada creación de AMÉLIE es una invitación a abrazar tu individualidad, a celebrar tu belleza y a sentirte segura en tu propia piel.</p>
<p>Porque la verdadera elegancia no consiste en convertirse en otra persona.</p>
<p>Consiste en tener la libertad de ser tú misma.</p>
<p class="p-signature">AMÉLIE — More Than Lingerie.</p>',
'Nuestra historia','AMÉLIE nace de la convicción de que cada mujer merece la libertad de definir su propia historia. Lencería romántica y corsetería sofisticada de fabricación italiana.'),

(1,'en','Our Story','More Than Lingerie',
'<p>AMÉLIE was born from a belief that every woman deserves the freedom to define her own story.</p>
<p>A story of confidence, independence and femininity — without expectations, without limitations.</p>
<p>We believe lingerie is more than what we wear beneath our clothes. It is an expression of who we are, how we feel and the confidence we carry within ourselves.</p>
<p>Inspired by timeless elegance, delicate details and the art of Italian craftsmanship, AMÉLIE brings together romantic lingerie and sophisticated corsetry designed to celebrate every side of femininity.</p>
<p class="p-accent">Soft yet powerful. Delicate yet fearless. Romantic yet bold.</p>
<p>Every AMÉLIE creation is an invitation to embrace your individuality, celebrate your beauty and feel confident in your own skin.</p>
<p>Because true elegance is not about becoming someone else.</p>
<p>It is about having the freedom to be yourself.</p>
<p class="p-signature">AMÉLIE — More Than Lingerie.</p>',
'Our Story','AMÉLIE was born from a belief that every woman deserves the freedom to define her own story. Romantic lingerie and sophisticated corsetry, made in Italy.'),

(1,'nl','Ons verhaal','More Than Lingerie',
'<p>AMÉLIE ontstond uit de overtuiging dat elke vrouw de vrijheid verdient om haar eigen verhaal te bepalen.</p>
<p>Een verhaal van zelfvertrouwen, onafhankelijkheid en vrouwelijkheid — zonder verwachtingen, zonder beperkingen.</p>
<p>Wij geloven dat lingerie meer is dan wat we onder onze kleding dragen. Het is een uitdrukking van wie we zijn, hoe we ons voelen en welk zelfvertrouwen we in ons dragen.</p>
<p>Geïnspireerd door tijdloze elegantie, delicate details en de kunst van het Italiaanse vakmanschap brengt AMÉLIE romantische lingerie en verfijnde korsetterie samen, ontworpen om elke kant van vrouwelijkheid te vieren.</p>
<p class="p-accent">Zacht en toch krachtig. Delicaat en toch onverschrokken. Romantisch en toch gedurfd.</p>
<p>Elke creatie van AMÉLIE is een uitnodiging om je eigenheid te omarmen, je schoonheid te vieren en je zeker te voelen in je eigen huid.</p>
<p>Want ware elegantie gaat er niet over iemand anders te worden.</p>
<p>Het gaat erover de vrijheid te hebben jezelf te zijn.</p>
<p class="p-signature">AMÉLIE — More Than Lingerie.</p>',
'Ons verhaal','AMÉLIE ontstond uit de overtuiging dat elke vrouw de vrijheid verdient om haar eigen verhaal te bepalen. Romantische lingerie en verfijnde korsetterie uit Italië.'),

(1,'it','La nostra storia','More Than Lingerie',
'<p>AMÉLIE nasce dalla convinzione che ogni donna meriti la libertà di definire la propria storia.</p>
<p>Una storia di sicurezza, indipendenza e femminilità — senza aspettative, senza limitazioni.</p>
<p>Crediamo che la lingerie sia più di ciò che indossiamo sotto i vestiti. È un modo di esprimere chi siamo, come ci sentiamo e la sicurezza che portiamo dentro di noi.</p>
<p>Ispirata all''eleganza senza tempo, ai dettagli delicati e all''arte dell''artigianato italiano, AMÉLIE riunisce lingerie romantica e corsetteria sofisticata, disegnate per celebrare ogni lato della femminilità.</p>
<p class="p-accent">Delicata eppure potente. Sottile eppure impavida. Romantica eppure audace.</p>
<p>Ogni creazione AMÉLIE è un invito ad abbracciare la tua individualità, celebrare la tua bellezza e sentirti sicura nella tua pelle.</p>
<p>Perché la vera eleganza non consiste nel diventare qualcun altro.</p>
<p>Consiste nell''avere la libertà di essere te stessa.</p>
<p class="p-signature">AMÉLIE — More Than Lingerie.</p>',
'La nostra storia','AMÉLIE nasce dalla convinzione che ogni donna meriti la libertà di definire la propria storia. Lingerie romantica e corsetteria sofisticata di fabbricazione italiana.');

-- =====================================================================
-- 2 · GUÍA DE TALLAS
-- =====================================================================
INSERT INTO `page_translations` (`page_id`,`lang`,`title`,`intro`,`body`,`meta_title`,`meta_description`) VALUES
(2,'es','Guía de tallas','Tómate las medidas con una cinta métrica flexible, sobre la piel y sin apretar.',
'<h2>Cómo medirte</h2>
<ul>
<li><strong>Contorno bajo pecho:</strong> rodea el torso justo debajo del pecho, con la cinta horizontal y bien ajustada.</li>
<li><strong>Contorno de pecho:</strong> mide por la parte más ancha del pecho, sin comprimir.</li>
<li><strong>Cintura:</strong> por la parte más estrecha del torso, normalmente a la altura del ombligo o un poco por encima.</li>
<li><strong>Cadera:</strong> por la parte más ancha, con los pies juntos.</li>
</ul>
<h2>Si estás entre dos tallas</h2>
<p>En el sujetador, elige la banda más pequeña y sube una copa. En el tanga y en el corsé, elige la talla mayor: el cordonado trasero de The Muse permite ajustar varios centímetros.</p>
<h2>Blush Floral</h2>
<p>Puedes elegir la talla de sujetador y la de tanga por separado. No tienen por qué coincidir.</p>
<h2>¿Tienes dudas?</h2>
<p>Escríbenos con tus medidas y te recomendamos la talla. Preferimos dedicar cinco minutos a acertar que gestionar una devolución.</p>',
'Guía de tallas','Cómo tomarte las medidas y elegir tu talla en AMÉLIE. Tablas de sujetador, tanga y corsé con equivalencias EU, IT, UK y US.'),

(2,'en','Size guide','Take your measurements with a flexible tape, against the skin and without pulling tight.',
'<h2>How to measure</h2>
<ul>
<li><strong>Underbust:</strong> wrap the tape around the torso just under the bust, horizontal and snug.</li>
<li><strong>Bust:</strong> measure across the fullest part of the bust, without compressing.</li>
<li><strong>Waist:</strong> at the narrowest part of the torso, usually at or slightly above the navel.</li>
<li><strong>Hip:</strong> around the fullest part, with your feet together.</li>
</ul>
<h2>If you are between two sizes</h2>
<p>For the bra, take the smaller band and go up one cup. For the brief and the corset, take the larger size: the back lacing on The Muse allows for several centimetres of adjustment.</p>
<h2>Blush Floral</h2>
<p>You can choose your bra size and your brief size separately. They do not have to match.</p>
<h2>Still unsure?</h2>
<p>Write to us with your measurements and we will recommend a size. We would rather spend five minutes getting it right than process a return.</p>',
'Size guide','How to measure yourself and choose your size at AMÉLIE. Bra, brief and corset charts with EU, IT, UK and US equivalences.'),

(2,'nl','Maattabel','Neem je maten met een flexibel meetlint, op de huid en zonder aan te trekken.',
'<h2>Hoe je meet</h2>
<ul>
<li><strong>Onderborstomvang:</strong> leg het lint om de romp net onder de borst, horizontaal en strak aansluitend.</li>
<li><strong>Borstomvang:</strong> meet over het volste deel van de borst, zonder in te drukken.</li>
<li><strong>Taille:</strong> op het smalste deel van de romp, meestal ter hoogte van de navel of net erboven.</li>
<li><strong>Heup:</strong> rond het volste deel, met je voeten tegen elkaar.</li>
</ul>
<h2>Als je tussen twee maten zit</h2>
<p>Neem bij de bh de kleinere band en ga één cup omhoog. Neem bij de slip en het korset de grotere maat: de rugveter van The Muse geeft enkele centimeters speling.</p>
<h2>Blush Floral</h2>
<p>Je kunt je bh-maat en je slipmaat afzonderlijk kiezen. Ze hoeven niet gelijk te zijn.</p>
<h2>Twijfel je nog?</h2>
<p>Stuur ons je maten en wij adviseren een maat. Liever vijf minuten goed kiezen dan een retour verwerken.</p>',
'Maattabel','Hoe je jezelf opmeet en je maat kiest bij AMÉLIE. Tabellen voor bh, slip en korset met EU-, IT-, UK- en US-maten.'),

(2,'it','Guida alle taglie','Prendi le misure con un metro flessibile, sulla pelle e senza stringere.',
'<h2>Come misurarti</h2>
<ul>
<li><strong>Sottoseno:</strong> gira il metro attorno al torace appena sotto il seno, orizzontale e ben aderente.</li>
<li><strong>Circonferenza seno:</strong> misura nel punto più ampio del seno, senza comprimere.</li>
<li><strong>Vita:</strong> nel punto più stretto del busto, di solito all''altezza dell''ombelico o poco sopra.</li>
<li><strong>Fianchi:</strong> nel punto più ampio, con i piedi uniti.</li>
</ul>
<h2>Se sei fra due taglie</h2>
<p>Per il reggiseno, scegli la fascia più piccola e sali di una coppa. Per lo slip e il corsetto, scegli la taglia più grande: l''allacciatura posteriore di The Muse consente di regolare diversi centimetri.</p>
<h2>Blush Floral</h2>
<p>Puoi scegliere separatamente la taglia del reggiseno e quella dello slip. Non devono coincidere.</p>
<h2>Hai ancora dubbi?</h2>
<p>Scrivici con le tue misure e ti consigliamo la taglia. Preferiamo dedicare cinque minuti a indovinare piuttosto che gestire un reso.</p>',
'Guida alle taglie','Come prendere le misure e scegliere la taglia da AMÉLIE. Tabelle reggiseno, slip e corsetto con equivalenze EU, IT, UK e US.');

-- =====================================================================
-- 3 · ENVÍOS Y DEVOLUCIONES
-- =====================================================================
INSERT INTO `page_translations` (`page_id`,`lang`,`title`,`intro`,`body`,`meta_title`,`meta_description`) VALUES
(3,'es','Envíos y devoluciones','Preparamos cada pedido a mano y lo enviamos en embalaje neutro.',
'<h2>Plazos y gastos de envío</h2>
<table class="data-table"><thead><tr><th>Destino</th><th>Plazo</th><th>Coste</th></tr></thead><tbody>
<tr><td>España peninsular</td><td>2-3 días laborables</td><td>4,95 €</td></tr>
<tr><td>Países Bajos y Bélgica</td><td>3-5 días laborables</td><td>7,95 €</td></tr>
<tr><td>Italia</td><td>3-5 días laborables</td><td>7,95 €</td></tr>
<tr><td>Resto de la Unión Europea</td><td>4-7 días laborables</td><td>9,95 €</td></tr>
<tr><td>Resto del mundo</td><td>7-14 días laborables</td><td>19,95 €</td></tr>
</tbody></table>
<p><strong>Envío gratuito a partir de 150 €</strong> en España y en la Unión Europea.</p>
<h2>Embalaje</h2>
<p>Cada pieza viaja en el packaging de AMÉLIE, dentro de un embalaje exterior neutro y sin ninguna referencia al contenido.</p>
<h2>Devoluciones</h2>
<p>Dispones de <strong>14 días naturales</strong> desde la recepción para devolver tu pedido. Escríbenos y te enviamos las instrucciones.</p>
<p>Por razones de higiene, la lencería y la corsetería sólo se admiten <strong>sin usar</strong>, con todas las etiquetas y el precinto sanitario intactos. Pruébate las piezas sobre tu ropa interior.</p>
<p>El reembolso se realiza por el mismo medio de pago en un plazo máximo de 14 días desde que recibimos la devolución. Los gastos de devolución corren por cuenta de la clienta, salvo que la pieza llegue defectuosa o sea un error nuestro.</p>
<h2>Cambios</h2>
<p>¿Te has equivocado de talla? Escríbenos antes de devolver nada: reservamos tu talla mientras gestionamos el cambio.</p>',
'Envíos y devoluciones','Plazos, costes de envío y política de devoluciones de AMÉLIE. Envío gratuito a partir de 150 € y 14 días para devolver.'),

(3,'en','Shipping and returns','Every order is prepared by hand and shipped in neutral packaging.',
'<h2>Delivery times and costs</h2>
<table class="data-table"><thead><tr><th>Destination</th><th>Time</th><th>Cost</th></tr></thead><tbody>
<tr><td>Mainland Spain</td><td>2-3 working days</td><td>€4.95</td></tr>
<tr><td>Netherlands and Belgium</td><td>3-5 working days</td><td>€7.95</td></tr>
<tr><td>Italy</td><td>3-5 working days</td><td>€7.95</td></tr>
<tr><td>Rest of the European Union</td><td>4-7 working days</td><td>€9.95</td></tr>
<tr><td>Rest of the world</td><td>7-14 working days</td><td>€19.95</td></tr>
</tbody></table>
<p><strong>Free shipping over €150</strong> within Spain and the European Union.</p>
<h2>Packaging</h2>
<p>Every piece travels in AMÉLIE packaging, inside neutral outer packaging with no reference to the contents.</p>
<h2>Returns</h2>
<p>You have <strong>14 calendar days</strong> from delivery to return your order. Write to us and we will send you the instructions.</p>
<p>For hygiene reasons, lingerie and corsetry are only accepted <strong>unworn</strong>, with all labels and the hygiene seal intact. Please try pieces on over your own underwear.</p>
<p>The refund is issued to the original payment method within a maximum of 14 days from receipt of the return. Return costs are borne by the customer, unless the piece arrives faulty or we made a mistake.</p>
<h2>Exchanges</h2>
<p>Wrong size? Write to us before sending anything back: we will hold your size while we arrange the exchange.</p>',
'Shipping and returns','Delivery times, shipping costs and return policy at AMÉLIE. Free shipping over €150 and 14 days to return.'),

(3,'nl','Verzending en retour','Elke bestelling wordt met de hand klaargemaakt en verzonden in neutrale verpakking.',
'<h2>Levertijden en kosten</h2>
<table class="data-table"><thead><tr><th>Bestemming</th><th>Levertijd</th><th>Kosten</th></tr></thead><tbody>
<tr><td>Spanje (vasteland)</td><td>2-3 werkdagen</td><td>€ 4,95</td></tr>
<tr><td>Nederland en België</td><td>3-5 werkdagen</td><td>€ 7,95</td></tr>
<tr><td>Italië</td><td>3-5 werkdagen</td><td>€ 7,95</td></tr>
<tr><td>Rest van de Europese Unie</td><td>4-7 werkdagen</td><td>€ 9,95</td></tr>
<tr><td>Rest van de wereld</td><td>7-14 werkdagen</td><td>€ 19,95</td></tr>
</tbody></table>
<p><strong>Gratis verzending vanaf € 150</strong> binnen Spanje en de Europese Unie.</p>
<h2>Verpakking</h2>
<p>Elk stuk reist in AMÉLIE-verpakking, binnen een neutrale buitenverpakking zonder enige verwijzing naar de inhoud.</p>
<h2>Retourneren</h2>
<p>Je hebt <strong>14 kalenderdagen</strong> vanaf ontvangst om je bestelling te retourneren. Schrijf ons en we sturen je de instructies.</p>
<p>Om hygiënische redenen worden lingerie en korsetterie alleen <strong>ongedragen</strong> aangenomen, met alle labels en het hygiënezegel intact. Pas de stukken over je eigen ondergoed.</p>
<p>De terugbetaling gebeurt via dezelfde betaalmethode binnen maximaal 14 dagen na ontvangst van de retour. De retourkosten zijn voor rekening van de klant, tenzij het stuk defect aankomt of wij een fout hebben gemaakt.</p>
<h2>Ruilen</h2>
<p>Verkeerde maat? Schrijf ons voordat je iets terugstuurt: we houden je maat apart terwijl we de ruil regelen.</p>',
'Verzending en retour','Levertijden, verzendkosten en retourbeleid van AMÉLIE. Gratis verzending vanaf € 150 en 14 dagen retourrecht.'),

(3,'it','Spedizioni e resi','Ogni ordine viene preparato a mano e spedito in imballo neutro.',
'<h2>Tempi e costi di spedizione</h2>
<table class="data-table"><thead><tr><th>Destinazione</th><th>Tempi</th><th>Costo</th></tr></thead><tbody>
<tr><td>Spagna peninsulare</td><td>2-3 giorni lavorativi</td><td>4,95 €</td></tr>
<tr><td>Paesi Bassi e Belgio</td><td>3-5 giorni lavorativi</td><td>7,95 €</td></tr>
<tr><td>Italia</td><td>3-5 giorni lavorativi</td><td>7,95 €</td></tr>
<tr><td>Resto dell''Unione Europea</td><td>4-7 giorni lavorativi</td><td>9,95 €</td></tr>
<tr><td>Resto del mondo</td><td>7-14 giorni lavorativi</td><td>19,95 €</td></tr>
</tbody></table>
<p><strong>Spedizione gratuita a partire da 150 €</strong> in Spagna e nell''Unione Europea.</p>
<h2>Imballo</h2>
<p>Ogni pezzo viaggia nel packaging AMÉLIE, dentro un imballo esterno neutro e senza alcun riferimento al contenuto.</p>
<h2>Resi</h2>
<p>Hai <strong>14 giorni di calendario</strong> dalla consegna per restituire il tuo ordine. Scrivici e ti invieremo le istruzioni.</p>
<p>Per ragioni igieniche, lingerie e corsetteria si accettano solo <strong>non indossate</strong>, con tutte le etichette e il sigillo igienico intatti. Prova i capi sopra la tua biancheria.</p>
<p>Il rimborso avviene con lo stesso metodo di pagamento entro un massimo di 14 giorni dalla ricezione del reso. Le spese di reso sono a carico della cliente, salvo capo difettoso o errore nostro.</p>
<h2>Cambi</h2>
<p>Taglia sbagliata? Scrivici prima di restituire qualcosa: terremo la tua taglia da parte mentre gestiamo il cambio.</p>',
'Spedizioni e resi','Tempi, costi di spedizione e politica dei resi di AMÉLIE. Spedizione gratuita da 150 € e 14 giorni per il reso.');

-- =====================================================================
-- 4 · FAQ   ·   5 · CONTACTO
-- =====================================================================
INSERT INTO `page_translations` (`page_id`,`lang`,`title`,`intro`,`body`,`meta_title`,`meta_description`) VALUES
(4,'es','Preguntas frecuentes','Lo que nos preguntáis más a menudo. Si no encuentras tu respuesta, escríbenos.','','Preguntas frecuentes','Resolvemos las dudas más habituales sobre tallas, envíos, devoluciones, cuidados y pagos en AMÉLIE.'),
(4,'en','Frequently asked questions','What we are asked most often. If you cannot find your answer, write to us.','','Frequently asked questions','The most common questions about sizing, shipping, returns, care and payment at AMÉLIE.'),
(4,'nl','Veelgestelde vragen','Wat ons het vaakst gevraagd wordt. Vind je je antwoord niet? Schrijf ons.','','Veelgestelde vragen','De meest gestelde vragen over maten, verzending, retour, onderhoud en betaling bij AMÉLIE.'),
(4,'it','Domande frequenti','Quello che ci chiedete più spesso. Se non trovi la tua risposta, scrivici.','','Domande frequenti','Le domande più comuni su taglie, spedizioni, resi, cura dei capi e pagamenti da AMÉLIE.'),

(5,'es','Contacto','Estamos al otro lado. Escríbenos y te respondemos en menos de 24 horas laborables.','','Contacto','Contacta con AMÉLIE. Dudas de tallas, pedidos, devoluciones o colaboraciones.'),
(5,'en','Contact','We are on the other side. Write to us and we reply within 24 working hours.','','Contact','Get in touch with AMÉLIE. Questions about sizing, orders, returns or collaborations.'),
(5,'nl','Contact','Wij staan aan de andere kant. Schrijf ons en we antwoorden binnen 24 werkuren.','','Contact','Neem contact op met AMÉLIE. Vragen over maten, bestellingen, retouren of samenwerkingen.'),
(5,'it','Contatti','Siamo dall''altra parte. Scrivici e rispondiamo entro 24 ore lavorative.','','Contatti','Contatta AMÉLIE. Domande su taglie, ordini, resi o collaborazioni.');
