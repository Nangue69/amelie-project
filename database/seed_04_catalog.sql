-- =====================================================================
-- AMÉLIE — Datos iniciales 04: catálogo Colección 01
-- Contenido basado en el Product Development Book (fichas 01 y 05)
-- =====================================================================

SET NAMES utf8mb4;

-- Categorías ----------------------------------------------------------
INSERT INTO `categories` (`id`,`slug`,`image`,`position`,`is_active`) VALUES
(1,'lenceria','/assets/img/products/blush-flatlay.jpg',1,1),
(2,'corseteria','/assets/img/products/muse-front.jpg',2,1);

INSERT INTO `category_translations` (`category_id`,`lang`,`name`,`description`,`meta_title`,`meta_description`) VALUES
(1,'es','Lencería','Conjuntos coordinados de tul, bordado y encaje. Piezas ligeras que sostienen sin apretar.','Lencería','Conjuntos de lencería premium de AMÉLIE: tul bordado, encaje y fabricación italiana.'),
(1,'en','Lingerie','Coordinated sets in tulle, embroidery and lace. Light pieces that support without constraining.','Lingerie','AMÉLIE premium lingerie sets: embroidered tulle, lace and Italian craftsmanship.'),
(1,'nl','Lingerie','Bijpassende sets in tule, borduurwerk en kant. Lichte stukken die steunen zonder te knellen.','Lingerie','Premium lingeriesets van AMÉLIE: geborduurde tule, kant en Italiaans vakmanschap.'),
(1,'it','Lingerie','Coordinati in tulle, ricamo e pizzo. Pezzi leggeri che sostengono senza stringere.','Lingerie','Coordinati di lingerie premium AMÉLIE: tulle ricamato, pizzo e fabbricazione italiana.'),

(2,'es','Corsetería','Corsés y bustiers estructurados en satén. Para llevar debajo o como prenda en sí misma.','Corsetería','Corsetería premium de AMÉLIE: satén estructurado, ballenas verticales y encaje mínimo.'),
(2,'en','Corsetry','Structured corsets and bustiers in satin. To wear underneath or as a garment in itself.','Corsetry','AMÉLIE premium corsetry: structured satin, vertical boning and minimal lace.'),
(2,'nl','Korsetterie','Gestructureerde korsetten en bustiers in satijn. Eronder te dragen of als kledingstuk op zich.','Korsetterie','Premium korsetterie van AMÉLIE: gestructureerd satijn, verticale baleinen en minimaal kant.'),
(2,'it','Corsetteria','Corsetti e bustier strutturati in raso. Da indossare sotto o come capo a sé.','Corsetteria','Corsetteria premium AMÉLIE: raso strutturato, stecche verticali e pizzo minimo.');

-- Productos -----------------------------------------------------------
INSERT INTO `products` (`id`,`slug`,`sku`,`category_id`,`price`,`compare_price`,`status`,`is_featured`,`size_mode`,`position`) VALUES
(1,'blush-floral','AM-BF-01',1,189.00,NULL,'published',1,'bra_brief',1),
(2,'the-muse','AM-TM-05',2,229.00,NULL,'published',1,'single',2);

-- Blush Floral --------------------------------------------------------
INSERT INTO `product_translations`
(`product_id`,`lang`,`name`,`tagline`,`short_description`,`description`,`composition`,`care`,`shipping_returns`,`meta_title`,`meta_description`) VALUES
(1,'es','Blush Floral','Romántico · Sensual · Refinado',
'Conjunto coordinado de tul semitransparente con bordado floral rosa y pequeños acentos verdes.',
'<p>Un conjunto pensado para ser ligero. La base es un tul fino semitransparente, suave sobre la piel, sobre el que se distribuye un bordado floral rosa con pequeños acentos verdes: aireado, nunca recargado.</p><p>El sujetador lleva aro y copa sin relleno, sin efecto push-up. El sostén viene del aro, de la estructura de la copa y de la banda bajo el pecho, de modo que la forma del pecho se mantiene natural. Tirantes finos regulables en rosa cipria, elásticos y ribetes tono sobre tono, y cierre trasero de corchetes.</p><p>El tanga brasileño tiene talle medio-bajo y pierna ligeramente alta. El bordado continúa por los costados y se aligera en la espalda. Fondo interior de algodón.</p><p>El liguero, opcional, repite el mismo bordado en una estructura fina de cuatro tiras regulables. Completa el conjunto sin dominarlo.</p><p>Minutería en oro claro champán, siempre discreta. Sin lazos grandes ni aplicaciones voluminosas.</p>',
'<p>Composición definitiva pendiente de confirmar con el taller italiano durante la fase de prototipo.</p><ul><li>Base de copa: tul fino semitransparente, 100% poliamida</li><li>Bordado: hilo tono rosa con acentos verdes, tacto suave</li><li>Elásticos y ribetes: 82% poliamida, 18% elastano</li><li>Fondo del tanga: 100% algodón</li><li>Minutería: metal con acabado en oro champán</li></ul>',
'<ul><li>Lavar a mano en agua fría, máximo 30°, con jabón neutro</li><li>No usar lejía ni suavizante</li><li>No frotar el bordado</li><li>No secar en secadora: secar en plano y a la sombra</li><li>No planchar sobre el bordado</li><li>Guardar en plano, sin doblar la copa</li></ul>',
'<p>Envío en 2-3 días laborables a España peninsular y 3-7 días al resto de Europa. Envío gratuito a partir de 150 €.</p><p>Dispones de 14 días naturales para devolver la pieza. Por razones de higiene, la lencería sólo se admite sin usar, con todas las etiquetas y el precinto sanitario intactos.</p><p>Cada pedido se envía en el packaging de AMÉLIE, en embalaje exterior neutro y sin referencias al contenido.</p>',
'Blush Floral — Conjunto de lencería de tul bordado',
'Conjunto coordinado de tul semitransparente con bordado floral. Sujetador con aro sin relleno, tanga brasileño y liguero opcional. Fabricación italiana.'),

(1,'en','Blush Floral','Romantic · Sensual · Refined',
'A coordinated set in semi-sheer tulle with pink floral embroidery and small green accents.',
'<p>A set designed to feel light. The base is a fine semi-sheer tulle, soft against the skin, carrying a pink floral embroidery with small green accents: airy, never overloaded.</p><p>The bra has an underwire and an unpadded cup, with no push-up effect. Support comes from the wire, the cup structure and the underband, so the natural shape of the breast is preserved. Fine adjustable straps in powder pink, tone-on-tone elastics and trims, and a hook-and-eye back closure.</p><p>The Brazilian brief sits at mid-low rise with a slightly high leg. The embroidery continues along the sides and lightens at the back. Inner cotton gusset.</p><p>The garter belt, optional, repeats the same embroidery on a fine structure with four adjustable straps. It completes the set without dominating it.</p><p>Hardware in light champagne gold, always discreet. No large bows or bulky appliqués.</p>',
'<p>Final composition to be confirmed with the Italian atelier during the prototype phase.</p><ul><li>Cup base: fine semi-sheer tulle, 100% polyamide</li><li>Embroidery: pink thread with green accents, soft hand</li><li>Elastics and trims: 82% polyamide, 18% elastane</li><li>Brief gusset: 100% cotton</li><li>Hardware: metal with champagne gold finish</li></ul>',
'<ul><li>Hand wash in cold water, max 30°, with a neutral soap</li><li>Do not bleach, do not use fabric softener</li><li>Do not rub the embroidery</li><li>Do not tumble dry: dry flat, away from direct light</li><li>Do not iron over the embroidery</li><li>Store flat, without folding the cup</li></ul>',
'<p>Delivery in 2-3 working days within mainland Spain and 3-7 days to the rest of Europe. Free shipping over €150.</p><p>You have 14 calendar days to return the piece. For hygiene reasons, lingerie is only accepted unworn, with all labels and the hygiene seal intact.</p><p>Every order ships in AMÉLIE packaging, inside neutral outer packaging with no reference to the contents.</p>',
'Blush Floral — Embroidered tulle lingerie set',
'Coordinated set in semi-sheer tulle with floral embroidery. Unpadded underwire bra, Brazilian brief and optional garter belt. Italian craftsmanship.'),

(1,'nl','Blush Floral','Romantisch · Sensueel · Verfijnd',
'Bijpassende set van doorschijnende tule met roze bloemenborduursel en kleine groene accenten.',
'<p>Een set die licht moet aanvoelen. De basis is fijne, half doorschijnende tule, zacht op de huid, met daarop een roze bloemenborduursel met kleine groene accenten: luchtig, nooit overladen.</p><p>De bh heeft een beugel en een cup zonder voering, zonder push-up effect. De steun komt van de beugel, de opbouw van de cup en de onderband, zodat de natuurlijke vorm van de borst behouden blijft. Fijne verstelbare bandjes in poederroze, ton-sur-ton elastiek en biezen, en een sluiting met haakjes op de rug.</p><p>De Braziliaanse slip heeft een medium-lage taille en een licht hoog uitgesneden been. Het borduursel loopt door over de zijkanten en wordt lichter aan de achterkant. Katoenen binnenkruis.</p><p>De jarretelgordel, optioneel, herhaalt hetzelfde borduursel op een fijne structuur met vier verstelbare banden. Hij maakt de set af zonder te overheersen.</p><p>Sluitingen in licht champagnegoud, altijd discreet. Geen grote strikken of volumineuze applicaties.</p>',
'<p>Definitieve samenstelling nog te bevestigen met het Italiaanse atelier tijdens de prototypefase.</p><ul><li>Cupbasis: fijne doorschijnende tule, 100% polyamide</li><li>Borduursel: roze draad met groene accenten, zachte hand</li><li>Elastiek en biezen: 82% polyamide, 18% elastaan</li><li>Kruisje slip: 100% katoen</li><li>Sluitingen: metaal met champagnegouden afwerking</li></ul>',
'<ul><li>Handwas in koud water, max. 30°, met een neutrale zeep</li><li>Niet bleken, geen wasverzachter gebruiken</li><li>Niet over het borduursel wrijven</li><li>Niet in de droger: plat drogen, uit direct licht</li><li>Niet over het borduursel strijken</li><li>Plat bewaren, de cup niet vouwen</li></ul>',
'<p>Levering in 3-5 werkdagen in Nederland en België. Gratis verzending vanaf €150.</p><p>Je hebt 14 kalenderdagen om het stuk te retourneren. Om hygiënische redenen wordt lingerie alleen ongedragen aangenomen, met alle labels en het hygiënezegel intact.</p><p>Elke bestelling wordt verzonden in AMÉLIE-verpakking, in een neutrale buitenverpakking zonder verwijzing naar de inhoud.</p>',
'Blush Floral — Lingerieset van geborduurde tule',
'Bijpassende set van doorschijnende tule met bloemenborduursel. Beugelbh zonder voering, Braziliaanse slip en optionele jarretelgordel.'),

(1,'it','Blush Floral','Romantico · Sensuale · Raffinato',
'Coordinato in tulle semi-trasparente con ricamo floreale rosa e piccoli accenti verdi.',
'<p>Un coordinato pensato per essere leggero. La base è un tulle fine semi-trasparente, morbido sulla pelle, su cui si distribuisce un ricamo floreale rosa con piccoli accenti verdi: arioso, mai eccessivamente fitto.</p><p>Il reggiseno ha ferretto e coppa non imbottita, senza effetto push-up. Il sostegno arriva dal ferretto, dalla struttura della coppa e dalla fascia sottoseno, così la forma del seno resta naturale. Spalline sottili regolabili in rosa cipria, elastici e bordature tono su tono, e chiusura posteriore con ganci.</p><p>Lo slip brasiliano ha vita medio-bassa e gamba leggermente sgambata. Il ricamo prosegue sui fianchi e si alleggerisce sul retro. Tassello interno in cotone.</p><p>Il reggicalze, opzionale, ripete lo stesso ricamo su una struttura sottile con quattro reggicalze regolabili. Completa il set senza diventare dominante.</p><p>Minuteria in oro chiaro champagne, sempre discreta. Nessun fiocco grande né applicazione voluminosa.</p>',
'<p>Composizione definitiva da confermare con l''atelier italiano durante la fase di prototipo.</p><ul><li>Base coppe: tulle fine semi-trasparente, 100% poliammide</li><li>Ricamo: filo rosa con accenti verdi, mano morbida</li><li>Elastici e bordature: 82% poliammide, 18% elastan</li><li>Tassello slip: 100% cotone</li><li>Minuteria: metallo con finitura oro champagne</li></ul>',
'<ul><li>Lavare a mano in acqua fredda, massimo 30°, con sapone neutro</li><li>Non candeggiare, non usare ammorbidente</li><li>Non sfregare il ricamo</li><li>Non asciugare in asciugatrice: asciugare in piano, lontano dalla luce diretta</li><li>Non stirare sopra il ricamo</li><li>Conservare in piano, senza piegare la coppa</li></ul>',
'<p>Consegna in 3-5 giorni lavorativi in Italia. Spedizione gratuita a partire da 150 €.</p><p>Hai 14 giorni di calendario per restituire il capo. Per ragioni igieniche, la lingerie si accetta solo non indossata, con tutte le etichette e il sigillo igienico intatti.</p><p>Ogni ordine viene spedito nel packaging AMÉLIE, dentro un imballo esterno neutro e senza riferimenti al contenuto.</p>',
'Blush Floral — Coordinato lingerie in tulle ricamato',
'Coordinato in tulle semi-trasparente con ricamo floreale. Reggiseno con ferretto non imbottito, slip brasiliano e reggicalze opzionale.');

-- The Muse ------------------------------------------------------------
INSERT INTO `product_translations`
(`product_id`,`lang`,`name`,`tagline`,`short_description`,`description`,`composition`,`care`,`shipping_returns`,`meta_title`,`meta_description`) VALUES
(2,'es','The Muse','Chocolate · Satén · Corsetería',
'Corsé estructurado en satén color chocolate, con ballenas verticales y encaje mínimo en el borde superior.',
'<p>Un corsé sofisticado y cómodo de llevar, pensado también como prenda de vestir. El protagonista es el satén estructurado en color chocolate fondant, con costuras verticales legibles que dibujan la silueta.</p><p>La construcción es ajustada, con ballenas verticales y paneles que definen la cintura. Sostiene con firmeza sin llegar a ser rígido, de modo que se puede llevar como top.</p><p>Las copas llevan aro y están estructuradas, pero sin efecto push-up. El escote es ligeramente sweetheart. El encaje aparece sólo a lo largo del borde superior de la copa: fino, tono sobre tono, justo lo necesario.</p><p>Por detrás, cordonado regulable de corsetería con una solución interna que permite ajustar sin comprometer la línea exterior.</p><p>Tirantes finos regulables y minutería en oro claro champán. Los corchetes frontales son discretos y funcionales. Sin lazos.</p>',
'<p>Composición definitiva pendiente de confirmar con el taller italiano durante la fase de prototipo.</p><ul><li>Tejido principal: satén estructurado color chocolate, tacto premium</li><li>Encaje: fino, tono sobre tono, sólo en el borde de la copa</li><li>Ballenas: verticales, finas pero estructurantes</li><li>Forro y refuerzos: solución técnica del taller</li><li>Cordonado trasero: cinta tono sobre tono</li><li>Minutería: metal con acabado en oro champán</li></ul>',
'<ul><li>Lavar a mano en agua fría con jabón neutro, o limpieza en seco</li><li>No usar lejía</li><li>No retorcer ni escurrir</li><li>No secar en secadora: secar en plano y a la sombra</li><li>Planchar a baja temperatura por el revés, nunca sobre el encaje</li><li>Guardar colgado o en plano, sin aplastar las copas</li></ul>',
'<p>Envío en 2-3 días laborables a España peninsular y 3-7 días al resto de Europa. Envío gratuito a partir de 150 €.</p><p>Dispones de 14 días naturales para devolver la pieza, sin usar y con todas las etiquetas intactas.</p><p>Cada pedido se envía en el packaging de AMÉLIE, en embalaje exterior neutro y sin referencias al contenido.</p>',
'The Muse — Corsé de satén chocolate',
'Corsé estructurado en satén chocolate con ballenas verticales, encaje mínimo y cordonado trasero regulable. Fabricación italiana.'),

(2,'en','The Muse','Chocolate · Satin · Corsetry',
'A structured corset in chocolate satin, with vertical boning and minimal lace along the upper edge.',
'<p>A sophisticated corset, comfortable enough to be worn as a fashion piece in its own right. The protagonist is structured satin in dark chocolate, with legible vertical seams that draw the silhouette.</p><p>The construction is close-fitting, with vertical boning and panelling that defines the waist. It holds firmly without becoming rigid, so it can be worn as a top.</p><p>The cups are underwired and structured, but without a push-up effect. The neckline is softly sweetheart. Lace appears only along the upper edge of the cup: fine, tone-on-tone, exactly as much as is needed.</p><p>At the back, adjustable corset lacing with an internal solution that allows adjustment without compromising the outer line.</p><p>Fine adjustable straps and light champagne gold hardware. The front hooks are discreet and functional. No bows.</p>',
'<p>Final composition to be confirmed with the Italian atelier during the prototype phase.</p><ul><li>Main fabric: structured chocolate satin, premium hand</li><li>Lace: fine, tone-on-tone, only on the cup edge</li><li>Boning: vertical, slim but structuring</li><li>Lining and reinforcements: technical solution from the atelier</li><li>Back lacing: tone-on-tone ribbon</li><li>Hardware: metal with champagne gold finish</li></ul>',
'<ul><li>Hand wash in cold water with a neutral soap, or dry clean</li><li>Do not bleach</li><li>Do not wring or twist</li><li>Do not tumble dry: dry flat, away from direct light</li><li>Iron at low temperature on the reverse, never over the lace</li><li>Store hanging or flat, without crushing the cups</li></ul>',
'<p>Delivery in 2-3 working days within mainland Spain and 3-7 days to the rest of Europe. Free shipping over €150.</p><p>You have 14 calendar days to return the piece, unworn and with all labels intact.</p><p>Every order ships in AMÉLIE packaging, inside neutral outer packaging with no reference to the contents.</p>',
'The Muse — Chocolate satin corset',
'Structured corset in chocolate satin with vertical boning, minimal lace and adjustable back lacing. Italian craftsmanship.'),

(2,'nl','The Muse','Chocolade · Satijn · Korsetterie',
'Gestructureerd korset in chocoladekleurig satijn, met verticale baleinen en minimaal kant langs de bovenrand.',
'<p>Een verfijnd korset, comfortabel genoeg om als modestuk op zich te dragen. De hoofdrol is voor gestructureerd satijn in diep chocoladebruin, met duidelijke verticale naden die de silhouetlijn tekenen.</p><p>De constructie sluit nauw aan, met verticale baleinen en panelen die de taille definiëren. Het houdt stevig vast zonder stijf te worden, zodat het ook als top gedragen kan worden.</p><p>De cups hebben beugels en zijn gestructureerd, maar zonder push-up effect. De halslijn is zacht sweetheart. Kant verschijnt alleen langs de bovenrand van de cup: fijn, ton-sur-ton, precies zoveel als nodig is.</p><p>Aan de achterkant een verstelbare korsetveter met een interne oplossing die afstellen mogelijk maakt zonder de buitenlijn aan te tasten.</p><p>Fijne verstelbare bandjes en sluitingen in licht champagnegoud. De haakjes aan de voorkant zijn discreet en functioneel. Geen strikken.</p>',
'<p>Definitieve samenstelling nog te bevestigen met het Italiaanse atelier tijdens de prototypefase.</p><ul><li>Hoofdstof: gestructureerd chocoladesatijn, premium hand</li><li>Kant: fijn, ton-sur-ton, alleen op de cuprand</li><li>Baleinen: verticaal, slank maar structurerend</li><li>Voering en versteviging: technische oplossing van het atelier</li><li>Rugveter: ton-sur-ton lint</li><li>Sluitingen: metaal met champagnegouden afwerking</li></ul>',
'<ul><li>Handwas in koud water met neutrale zeep, of stomen</li><li>Niet bleken</li><li>Niet uitwringen of draaien</li><li>Niet in de droger: plat drogen, uit direct licht</li><li>Strijken op lage temperatuur aan de binnenkant, nooit over het kant</li><li>Hangend of plat bewaren, de cups niet platdrukken</li></ul>',
'<p>Levering in 3-5 werkdagen in Nederland en België. Gratis verzending vanaf €150.</p><p>Je hebt 14 kalenderdagen om het stuk te retourneren, ongedragen en met alle labels intact.</p><p>Elke bestelling wordt verzonden in AMÉLIE-verpakking, in een neutrale buitenverpakking zonder verwijzing naar de inhoud.</p>',
'The Muse — Korset van chocoladesatijn',
'Gestructureerd korset in chocoladesatijn met verticale baleinen, minimaal kant en verstelbare rugveter. Italiaans vakmanschap.'),

(2,'it','The Muse','Cioccolato · Raso · Corsetteria',
'Corsetto strutturato in raso color cioccolato, con stecche verticali e pizzo minimo sul bordo superiore.',
'<p>Un corsetto sofisticato e indossabile anche come capo fashion. Protagonista è il raso strutturato color cioccolato fondente, con cuciture verticali leggibili che disegnano la silhouette.</p><p>La costruzione è aderente, con stecche verticali e pannellatura che definisce la vita. Sostiene con fermezza senza risultare eccessivamente rigido, così da poter essere portato anche come top.</p><p>Le coppe hanno ferretto e sono strutturate, ma senza effetto push-up. La scollatura è leggermente sweetheart. Il pizzo compare soltanto lungo il margine superiore della coppa: sottile, tono su tono, quanto basta.</p><p>Sul retro, allacciatura corsetto regolabile con una soluzione interna che consente la regolazione senza compromettere la linea esterna.</p><p>Spalline sottili regolabili e minuteria in oro chiaro champagne. I ganci frontali sono discreti e funzionali. Nessun fiocco.</p>',
'<p>Composizione definitiva da confermare con l''atelier italiano durante la fase di prototipo.</p><ul><li>Tessuto principale: raso strutturato cioccolato, mano premium</li><li>Pizzo: sottile, tono su tono, solo sul bordo coppa</li><li>Stecche: verticali, sottili ma strutturanti</li><li>Fodera e rinforzi: soluzione tecnica dell''atelier</li><li>Allacciatura posteriore: nastro tono su tono</li><li>Minuteria: metallo con finitura oro champagne</li></ul>',
'<ul><li>Lavare a mano in acqua fredda con sapone neutro, oppure lavaggio a secco</li><li>Non candeggiare</li><li>Non torcere né strizzare</li><li>Non asciugare in asciugatrice: asciugare in piano, lontano dalla luce diretta</li><li>Stirare a bassa temperatura dal rovescio, mai sopra il pizzo</li><li>Conservare appeso o in piano, senza schiacciare le coppe</li></ul>',
'<p>Consegna in 3-5 giorni lavorativi in Italia. Spedizione gratuita a partire da 150 €.</p><p>Hai 14 giorni di calendario per restituire il capo, non indossato e con tutte le etichette intatte.</p><p>Ogni ordine viene spedito nel packaging AMÉLIE, dentro un imballo esterno neutro e senza riferimenti al contenuto.</p>',
'The Muse — Corsetto in raso cioccolato',
'Corsetto strutturato in raso cioccolato con stecche verticali, pizzo minimo e allacciatura posteriore regolabile.');

-- Imágenes ------------------------------------------------------------
INSERT INTO `product_images` (`product_id`,`path`,`alt`,`kind`,`position`,`is_primary`) VALUES
(1,'/assets/img/products/blush-model-front.jpg','Conjunto Blush Floral visto de frente sobre modelo','model',1,1),
(1,'/assets/img/products/blush-model-back.jpg','Conjunto Blush Floral visto de espalda sobre modelo','model',2,0),
(1,'/assets/img/products/blush-bra.jpg','Sujetador Blush Floral de tul bordado','product',3,0),
(1,'/assets/img/products/blush-brief.jpg','Tanga brasileño Blush Floral','product',4,0),
(1,'/assets/img/products/blush-garter.jpg','Liguero Blush Floral opcional','product',5,0),
(1,'/assets/img/products/blush-flatlay.jpg','Conjunto Blush Floral completo','product',6,0),
(1,'/assets/img/products/blush-detail-embroidery.jpg','Detalle del bordado floral','detail',7,0),
(1,'/assets/img/products/blush-detail-applique.jpg','Detalle de la aplicación floral','detail',8,0),
(1,'/assets/img/products/blush-detail-hardware.jpg','Detalle de la minutería en oro champán','detail',9,0),
(1,'/assets/img/products/blush-detail-straps.jpg','Detalle de los tirantes y las cintas','detail',10,0),
(1,'/assets/img/products/blush-editorial.jpg','Blush Floral, imagen de campaña','model',11,0),

(2,'/assets/img/products/muse-front.jpg','Corsé The Muse visto de frente','product',1,1),
(2,'/assets/img/products/muse-back.jpg','Corsé The Muse visto por detrás, con cordonado','product',2,0),
(2,'/assets/img/products/muse-model-front.jpg','Corsé The Muse de frente sobre modelo','model',3,0),
(2,'/assets/img/products/muse-model-back.jpg','Corsé The Muse de espalda sobre modelo','model',4,0),
(2,'/assets/img/products/muse-detail-lace.jpg','Detalle del encaje en el borde de la copa','detail',5,0),
(2,'/assets/img/products/muse-detail-hardware.jpg','Detalle de los corchetes frontales en oro champán','detail',6,0),
(2,'/assets/img/products/muse-detail-fit.jpg','Detalle de las costuras verticales y el ajuste','detail',7,0),
(2,'/assets/img/products/muse-detail-lacing.jpg','Detalle del cordonado trasero regulable','detail',8,0),
(2,'/assets/img/products/muse-editorial.jpg','The Muse, imagen de campaña','model',9,0);

-- Variantes Blush Floral: talla de sujetador × talla de tanga ----------
INSERT INTO `product_variants` (`product_id`,`sku`,`size_bra`,`size_brief`,`stock`,`position`) VALUES
(1,'AM-BF-70B-XS','70B','XS',6,1),
(1,'AM-BF-70B-S','70B','S',8,2),
(1,'AM-BF-70B-M','70B','M',5,3),
(1,'AM-BF-70B-L','70B','L',3,4),
(1,'AM-BF-70C-XS','70C','XS',4,5),
(1,'AM-BF-70C-S','70C','S',7,6),
(1,'AM-BF-70C-M','70C','M',6,7),
(1,'AM-BF-70C-L','70C','L',3,8),
(1,'AM-BF-75B-XS','75B','XS',5,9),
(1,'AM-BF-75B-S','75B','S',10,10),
(1,'AM-BF-75B-M','75B','M',9,11),
(1,'AM-BF-75B-L','75B','L',6,12),
(1,'AM-BF-75C-XS','75C','XS',4,13),
(1,'AM-BF-75C-S','75C','S',9,14),
(1,'AM-BF-75C-M','75C','M',8,15),
(1,'AM-BF-75C-L','75C','L',5,16),
(1,'AM-BF-80B-XS','80B','XS',2,17),
(1,'AM-BF-80B-S','80B','S',6,18),
(1,'AM-BF-80B-M','80B','M',7,19),
(1,'AM-BF-80B-L','80B','L',6,20),
(1,'AM-BF-80C-XS','80C','XS',0,21),
(1,'AM-BF-80C-S','80C','S',4,22),
(1,'AM-BF-80C-M','80C','M',6,23),
(1,'AM-BF-80C-L','80C','L',5,24);

-- Variantes The Muse: talla única --------------------------------------
INSERT INTO `product_variants` (`product_id`,`sku`,`size`,`stock`,`position`) VALUES
(2,'AM-TM-XS','XS',4,1),
(2,'AM-TM-S','S',9,2),
(2,'AM-TM-M','M',11,3),
(2,'AM-TM-L','L',7,4),
(2,'AM-TM-XL','XL',3,5);
