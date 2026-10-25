-- =====================================================================
-- AMÉLIE — Datos iniciales 07: preguntas frecuentes y Journal
-- =====================================================================

SET NAMES utf8mb4;

INSERT INTO `faqs` (`id`,`position`,`is_active`) VALUES
(1,1,1),(2,2,1),(3,3,1),(4,4,1),(5,5,1),(6,6,1),(7,7,1),(8,8,1);

INSERT INTO `faq_translations` (`faq_id`,`lang`,`question`,`answer`) VALUES
(1,'es','¿Puedo elegir tallas distintas de sujetador y de tanga?','Sí. En Blush Floral eliges las dos por separado, porque sabemos que casi nunca coinciden. En la ficha de producto verás dos selectores independientes.'),
(1,'en','Can I choose different bra and brief sizes?','Yes. With Blush Floral you choose both separately, because we know they rarely match. The product page has two independent selectors.'),
(1,'nl','Kan ik verschillende bh- en slipmaten kiezen?','Ja. Bij Blush Floral kies je beide afzonderlijk, omdat we weten dat ze zelden gelijk zijn. Op de productpagina staan twee aparte keuzelijsten.'),
(1,'it','Posso scegliere taglie diverse di reggiseno e slip?','Sì. Con Blush Floral le scegli separatamente, perché sappiamo che quasi mai coincidono. Nella scheda prodotto trovi due selettori indipendenti.'),

(2,'es','¿Las copas llevan relleno o push-up?','No. Las copas no llevan relleno ni efecto push-up. El sostén viene del aro, de la estructura de la copa y de la banda bajo el pecho, de modo que la forma se mantiene natural.'),
(2,'en','Are the cups padded or push-up?','No. The cups are unpadded with no push-up effect. Support comes from the underwire, the cup structure and the underband, so the shape stays natural.'),
(2,'nl','Zijn de cups voorgevormd of push-up?','Nee. De cups zijn zonder voering en zonder push-up effect. De steun komt van de beugel, de opbouw van de cup en de onderband, zodat de vorm natuurlijk blijft.'),
(2,'it','Le coppe sono imbottite o push-up?','No. Le coppe non sono imbottite e non hanno effetto push-up. Il sostegno arriva dal ferretto, dalla struttura della coppa e dalla fascia sottoseno, così la forma resta naturale.'),

(3,'es','¿Dónde se fabrican las piezas?','En talleres italianos especializados en corsetería y encaje. Seleccionamos los materiales uno a uno y revisamos los acabados a mano antes de enviar.'),
(3,'en','Where are the pieces made?','In Italian ateliers specialised in corsetry and lace. We select materials one by one and check the finishings by hand before shipping.'),
(3,'nl','Waar worden de stukken gemaakt?','In Italiaanse ateliers die gespecialiseerd zijn in korsetterie en kant. We kiezen de materialen stuk voor stuk en controleren de afwerking met de hand voor verzending.'),
(3,'it','Dove vengono fabbricati i capi?','In atelier italiani specializzati in corsetteria e pizzo. Selezioniamo i materiali uno a uno e controlliamo le finiture a mano prima della spedizione.'),

(4,'es','¿Cómo debo lavar la lencería?','A mano, en agua fría y con jabón neutro. Sin lejía ni suavizante, sin secadora y sin planchar sobre el bordado o el encaje. Secar en plano y a la sombra.'),
(4,'en','How should I wash the lingerie?','By hand, in cold water with a neutral soap. No bleach, no fabric softener, no tumble dryer, and never iron over the embroidery or the lace. Dry flat, away from direct light.'),
(4,'nl','Hoe moet ik de lingerie wassen?','Met de hand, in koud water met neutrale zeep. Geen bleek, geen wasverzachter, niet in de droger en nooit strijken over het borduursel of het kant. Plat drogen, uit direct licht.'),
(4,'it','Come devo lavare la lingerie?','A mano, in acqua fredda e con sapone neutro. Niente candeggina né ammorbidente, niente asciugatrice e mai stirare sopra il ricamo o il pizzo. Asciugare in piano, lontano dalla luce diretta.'),

(5,'es','¿El envío es discreto?','Sí. El pedido viaja en el packaging de AMÉLIE, dentro de un embalaje exterior neutro, sin logotipos ni ninguna referencia al contenido.'),
(5,'en','Is the delivery discreet?','Yes. Your order travels in AMÉLIE packaging, inside neutral outer packaging, with no logos and no reference to the contents.'),
(5,'nl','Is de verzending discreet?','Ja. Je bestelling reist in AMÉLIE-verpakking, binnen een neutrale buitenverpakking, zonder logo''s en zonder verwijzing naar de inhoud.'),
(5,'it','La spedizione è discreta?','Sì. L''ordine viaggia nel packaging AMÉLIE, dentro un imballo esterno neutro, senza loghi né riferimenti al contenuto.'),

(6,'es','¿Puedo devolver la lencería si no me queda bien?','Tienes 14 días naturales. Por higiene, sólo admitimos piezas sin usar, con todas las etiquetas y el precinto sanitario intactos. Pruébatelas siempre sobre tu ropa interior.'),
(6,'en','Can I return lingerie if it does not fit?','You have 14 calendar days. For hygiene, we only accept unworn pieces with all labels and the hygiene seal intact. Always try them on over your own underwear.'),
(6,'nl','Kan ik lingerie retourneren als hij niet past?','Je hebt 14 kalenderdagen. Om hygiënische redenen nemen we alleen ongedragen stukken aan met alle labels en het hygiënezegel intact. Pas ze altijd over je eigen ondergoed.'),
(6,'it','Posso restituire la lingerie se non mi sta bene?','Hai 14 giorni di calendario. Per igiene accettiamo solo capi non indossati, con tutte le etichette e il sigillo igienico intatti. Provali sempre sopra la tua biancheria.'),

(7,'es','¿Qué formas de pago aceptáis?','Transferencia bancaria y tarjeta de crédito o débito mediante una pasarela segura. Para clientas de Países Bajos y Bélgica aceptamos también iDEAL y Bancontact.'),
(7,'en','Which payment methods do you accept?','Bank transfer and credit or debit card through a secure gateway. For customers in the Netherlands and Belgium we also accept iDEAL and Bancontact.'),
(7,'nl','Welke betaalmethoden accepteren jullie?','Bankoverschrijving en creditcard of debetkaart via een beveiligde betaaldienst. Voor klanten in Nederland en België accepteren we ook iDEAL en Bancontact.'),
(7,'it','Quali metodi di pagamento accettate?','Bonifico bancario e carta di credito o debito tramite gateway sicuro. Per le clienti di Paesi Bassi e Belgio accettiamo anche iDEAL e Bancontact.'),

(8,'es','Estoy entre dos tallas, ¿cuál elijo?','En el sujetador, la banda más pequeña y una copa más. En el tanga y en el corsé, la talla mayor. Si prefieres que te acompañemos, escríbenos con tus medidas.'),
(8,'en','I am between two sizes, which should I choose?','For the bra, the smaller band and one cup up. For the brief and the corset, the larger size. If you prefer guidance, write to us with your measurements.'),
(8,'nl','Ik zit tussen twee maten, welke kies ik?','Bij de bh de kleinere band en één cup groter. Bij de slip en het korset de grotere maat. Wil je advies, stuur ons dan je maten.'),
(8,'it','Sono fra due taglie, quale scelgo?','Per il reggiseno, la fascia più piccola e una coppa in più. Per lo slip e il corsetto, la taglia più grande. Se preferisci un consiglio, scrivici con le tue misure.');

-- =====================================================================
-- Journal
-- =====================================================================
INSERT INTO `posts` (`id`,`slug`,`cover_image`,`author_id`,`status`,`published_at`) VALUES
(1,'el-arte-de-la-corseteria-italiana','/assets/img/products/muse-detail-fit.jpg',1,'published','2026-09-12 10:00:00'),
(2,'como-elegir-tu-talla-de-sujetador','/assets/img/products/blush-bra.jpg',1,'published','2026-09-30 10:00:00'),
(3,'anatomia-de-un-bordado','/assets/img/products/blush-detail-embroidery.jpg',1,'published','2026-10-15 10:00:00');

INSERT INTO `post_translations` (`post_id`,`lang`,`title`,`excerpt`,`body`,`meta_title`,`meta_description`) VALUES
(1,'es','El arte de la corsetería italiana',
'Por qué una pieza bien construida se nota antes de mirarse al espejo.',
'<p>Un corsé no se sostiene por el tejido. Se sostiene por lo que no se ve: la disposición de las ballenas, el número de paneles, la dirección de cada costura.</p>
<h2>La ballena correcta en el sitio correcto</h2>
<p>En The Muse las ballenas son verticales, finas pero estructurantes. Suficientes para dibujar la cintura, no tantas como para convertir la pieza en una armadura. La diferencia entre un corsé que se lleva una tarde entera y uno que se quita a la hora está casi siempre ahí.</p>
<h2>Paneles que leen el cuerpo</h2>
<p>La pannellatura divide la pieza en secciones que siguen la curva real del torso. Cada costura vertical es una decisión: dónde ceñir, dónde dejar aire, dónde sostener.</p>
<h2>El encaje como puntuación</h2>
<p>En una pieza de satén, el encaje no es el protagonista. En The Muse aparece sólo en el borde superior de la copa, tono sobre tono. Es una coma, no un subrayado.</p>
<p>Esa es la idea que compartimos con los talleres con los que trabajamos: la elegancia está en lo que se quita.</p>',
'El arte de la corsetería italiana','Ballenas, paneles y encaje: lo que hace que un corsé se pueda llevar todo el día. Del Journal de AMÉLIE.'),

(1,'en','The art of Italian corsetry',
'Why a well-built piece is noticed before you even look in the mirror.',
'<p>A corset is not held up by its fabric. It is held up by what you cannot see: the arrangement of the boning, the number of panels, the direction of every seam.</p>
<h2>The right bone in the right place</h2>
<p>In The Muse the boning is vertical, slim but structuring. Enough to draw the waist, not so much that the piece becomes armour. The difference between a corset you wear all evening and one you take off within the hour is almost always there.</p>
<h2>Panels that read the body</h2>
<p>Panelling divides the piece into sections that follow the real curve of the torso. Every vertical seam is a decision: where to hold, where to leave air, where to support.</p>
<h2>Lace as punctuation</h2>
<p>On a satin piece, lace is not the protagonist. In The Muse it appears only along the upper edge of the cup, tone on tone. It is a comma, not an underline.</p>
<p>That is the idea we share with the ateliers we work with: elegance lies in what is taken away.</p>',
'The art of Italian corsetry','Boning, panelling and lace: what makes a corset wearable all day. From the AMÉLIE Journal.'),

(1,'nl','De kunst van de Italiaanse korsetterie',
'Waarom je een goed gebouwd stuk merkt nog voor je in de spiegel kijkt.',
'<p>Een korset wordt niet door de stof overeind gehouden. Het wordt gedragen door wat je niet ziet: de plaatsing van de baleinen, het aantal panelen, de richting van elke naad.</p>
<h2>De juiste balein op de juiste plek</h2>
<p>In The Muse zijn de baleinen verticaal, slank maar structurerend. Genoeg om de taille te tekenen, niet zoveel dat het stuk een harnas wordt. Het verschil tussen een korset dat je een hele avond draagt en een dat je binnen het uur uittrekt, zit bijna altijd daar.</p>
<h2>Panelen die het lichaam lezen</h2>
<p>De panelen verdelen het stuk in secties die de echte curve van de romp volgen. Elke verticale naad is een beslissing: waar aansluiten, waar lucht laten, waar steunen.</p>
<h2>Kant als leesteken</h2>
<p>Op een satijnen stuk is kant niet de hoofdrolspeler. In The Muse verschijnt het alleen langs de bovenrand van de cup, ton-sur-ton. Het is een komma, geen onderstreping.</p>
<p>Dat is het idee dat we delen met de ateliers waarmee we werken: elegantie zit in wat je weglaat.</p>',
'De kunst van de Italiaanse korsetterie','Baleinen, panelen en kant: wat een korset de hele dag draagbaar maakt. Uit het AMÉLIE Journal.'),

(1,'it','L''arte della corsetteria italiana',
'Perché un capo costruito bene si sente prima ancora di guardarsi allo specchio.',
'<p>Un corsetto non si sostiene con il tessuto. Si sostiene con ciò che non si vede: la disposizione delle stecche, il numero di pannelli, la direzione di ogni cucitura.</p>
<h2>La stecca giusta nel punto giusto</h2>
<p>In The Muse le stecche sono verticali, sottili ma strutturanti. Abbastanza per disegnare la vita, non tante da trasformare il capo in un''armatura. La differenza tra un corsetto che si porta tutta la sera e uno che si toglie dopo un''ora sta quasi sempre lì.</p>
<h2>Pannelli che leggono il corpo</h2>
<p>La pannellatura divide il capo in sezioni che seguono la curva reale del busto. Ogni cucitura verticale è una decisione: dove stringere, dove lasciare aria, dove sostenere.</p>
<h2>Il pizzo come punteggiatura</h2>
<p>Su un capo in raso, il pizzo non è il protagonista. In The Muse compare solo sul bordo superiore della coppa, tono su tono. È una virgola, non una sottolineatura.</p>
<p>È l''idea che condividiamo con gli atelier con cui lavoriamo: l''eleganza sta in ciò che si toglie.</p>',
'L''arte della corsetteria italiana','Stecche, pannelli e pizzo: ciò che rende un corsetto indossabile tutto il giorno. Dal Journal di AMÉLIE.'),

(2,'es','Cómo elegir tu talla de sujetador',
'Dos medidas, cinco minutos y la mayoría de las devoluciones dejan de existir.',
'<p>La talla de sujetador tiene dos partes y casi siempre se falla en la primera.</p>
<h2>La banda sostiene, la copa acompaña</h2>
<p>El 80% del sostén lo da la banda bajo el pecho, no los tirantes. Si la banda sube por la espalda cuando levantas los brazos, te queda grande, por mucho que la copa parezca correcta.</p>
<h2>Las dos medidas</h2>
<p>Mide el <strong>contorno bajo pecho</strong> con la cinta horizontal y bien ajustada, y el <strong>contorno de pecho</strong> por la parte más ancha, sin comprimir. La diferencia entre ambas da la copa.</p>
<h2>Si estás entre dos tallas</h2>
<p>Baja la banda y sube una copa: una 80B y una 75C tienen el mismo volumen de copa, pero la segunda sostiene mejor si tu contorno está en el límite.</p>
<h2>El gesto que lo confirma</h2>
<p>Con el sujetador puesto, pasa dos dedos bajo la banda por la espalda. Deben entrar justos. Si entra toda la mano, prueba una talla menos de banda.</p>',
'Cómo elegir tu talla de sujetador','Guía práctica para acertar con la talla de sujetador: banda, copa y el truco de los dos dedos. Del Journal de AMÉLIE.'),

(2,'en','How to choose your bra size',
'Two measurements, five minutes, and most returns stop happening.',
'<p>Bra size has two parts, and the first one is almost always where it goes wrong.</p>
<h2>The band supports, the cup follows</h2>
<p>Eighty per cent of the support comes from the underband, not the straps. If the band rides up your back when you raise your arms, it is too big, however right the cup may look.</p>
<h2>The two measurements</h2>
<p>Measure the <strong>underbust</strong> with the tape horizontal and snug, and the <strong>bust</strong> across the fullest part, without compressing. The difference between the two gives you the cup.</p>
<h2>If you are between sizes</h2>
<p>Go down a band and up a cup: a 80B and a 75C hold the same cup volume, but the second supports better if your underbust sits on the edge.</p>
<h2>The gesture that confirms it</h2>
<p>With the bra on, slide two fingers under the band at the back. They should fit snugly. If your whole hand slides in, try one band size down.</p>',
'How to choose your bra size','A practical guide to getting your bra size right: band, cup and the two-finger test. From the AMÉLIE Journal.'),

(2,'nl','Hoe kies je je bh-maat',
'Twee maten, vijf minuten, en de meeste retouren verdwijnen.',
'<p>Een bh-maat bestaat uit twee delen, en het gaat bijna altijd mis bij het eerste.</p>
<h2>De band steunt, de cup volgt</h2>
<p>Tachtig procent van de steun komt van de onderband, niet van de bandjes. Als de band omhoogkruipt op je rug wanneer je je armen optilt, is hij te groot, hoe goed de cup er ook uitziet.</p>
<h2>De twee maten</h2>
<p>Meet de <strong>onderborstomvang</strong> met het lint horizontaal en strak, en de <strong>borstomvang</strong> over het volste deel, zonder in te drukken. Het verschil tussen beide geeft de cup.</p>
<h2>Als je tussen maten zit</h2>
<p>Ga een band omlaag en een cup omhoog: een 80B en een 75C hebben hetzelfde cupvolume, maar de tweede steunt beter als je onderborstmaat op de grens ligt.</p>
<h2>Het gebaar dat het bevestigt</h2>
<p>Schuif met de bh aan twee vingers onder de band op je rug. Die moeten er net onder passen. Glijdt je hele hand erin, probeer dan een bandmaat kleiner.</p>',
'Hoe kies je je bh-maat','Praktische gids om je bh-maat goed te kiezen: band, cup en de tweevingertest. Uit het AMÉLIE Journal.'),

(2,'it','Come scegliere la taglia del reggiseno',
'Due misure, cinque minuti, e la maggior parte dei resi smette di esistere.',
'<p>La taglia del reggiseno ha due parti, e quasi sempre si sbaglia la prima.</p>
<h2>La fascia sostiene, la coppa accompagna</h2>
<p>L''ottanta per cento del sostegno arriva dalla fascia sottoseno, non dalle spalline. Se la fascia risale sulla schiena quando alzi le braccia, è troppo larga, per quanto la coppa sembri giusta.</p>
<h2>Le due misure</h2>
<p>Misura il <strong>sottoseno</strong> con il metro orizzontale e ben aderente, e la <strong>circonferenza seno</strong> nel punto più ampio, senza comprimere. La differenza tra le due dà la coppa.</p>
<h2>Se sei fra due taglie</h2>
<p>Scendi di fascia e sali di coppa: una 80B e una 75C hanno lo stesso volume di coppa, ma la seconda sostiene meglio se il tuo sottoseno è al limite.</p>
<h2>Il gesto che lo conferma</h2>
<p>Con il reggiseno indossato, fai passare due dita sotto la fascia sulla schiena. Devono entrare giuste. Se entra tutta la mano, prova una fascia in meno.</p>',
'Come scegliere la taglia del reggiseno','Guida pratica per indovinare la taglia del reggiseno: fascia, coppa e il test delle due dita. Dal Journal di AMÉLIE.'),

(3,'es','Anatomía de un bordado',
'Qué separa un bordado que acaricia de uno que molesta a las dos horas.',
'<p>En lencería, un bordado tiene que cumplir dos cosas a la vez: verse delicado y no notarse sobre la piel. No es evidente.</p>
<h2>La densidad</h2>
<p>Un bordado demasiado tupido endurece la copa y la deforma. En Blush Floral el motivo floral se distribuye de forma aireada, dejando respirar el tul entre flor y flor.</p>
<h2>El reverso</h2>
<p>Es donde se decide la comodidad. Los hilos del revés deben ir rematados y planos. Si rascan, el bordado está mal resuelto, por bonito que se vea de frente.</p>
<h2>El color</h2>
<p>Rosa sobre rosa, con pequeños acentos verdes. El contraste justo para que el motivo se lea sin convertirse en un estampado.</p>
<h2>Y la mano</h2>
<p>La prueba definitiva no es visual. Se cierra la mano sobre el tejido: si el bordado cede con el tul, está bien. Si opone resistencia, vuelve al taller.</p>',
'Anatomía de un bordado','Densidad, reverso, color y tacto: los cuatro criterios con los que evaluamos un bordado. Del Journal de AMÉLIE.'),

(3,'en','Anatomy of an embroidery',
'What separates an embroidery that caresses from one that irritates after two hours.',
'<p>In lingerie, an embroidery has to do two things at once: look delicate and go unnoticed against the skin. It is not obvious.</p>
<h2>Density</h2>
<p>An embroidery that is too dense stiffens the cup and distorts it. In Blush Floral the floral motif is distributed airily, letting the tulle breathe between one flower and the next.</p>
<h2>The reverse</h2>
<p>This is where comfort is decided. The threads on the back must be finished and flat. If they scratch, the embroidery is poorly resolved, however beautiful it looks from the front.</p>
<h2>Colour</h2>
<p>Pink on pink, with small green accents. Just enough contrast for the motif to read without turning into a print.</p>
<h2>And the hand</h2>
<p>The final test is not visual. You close your hand around the fabric: if the embroidery gives with the tulle, it is right. If it resists, it goes back to the atelier.</p>',
'Anatomy of an embroidery','Density, reverse, colour and hand: the four criteria by which we judge an embroidery. From the AMÉLIE Journal.'),

(3,'nl','Anatomie van een borduursel',
'Wat een borduursel dat streelt onderscheidt van een dat na twee uur irriteert.',
'<p>In lingerie moet een borduursel twee dingen tegelijk doen: er delicaat uitzien en niet voelbaar zijn op de huid. Dat spreekt niet vanzelf.</p>
<h2>De dichtheid</h2>
<p>Een te dicht borduursel verstijft de cup en vervormt hem. In Blush Floral is het bloemmotief luchtig verdeeld, zodat de tule tussen de bloemen door kan ademen.</p>
<h2>De achterkant</h2>
<p>Daar wordt het comfort beslist. De draden aan de achterkant moeten afgewerkt en vlak zijn. Krassen ze, dan is het borduursel slecht opgelost, hoe mooi het er van voren ook uitziet.</p>
<h2>De kleur</h2>
<p>Roze op roze, met kleine groene accenten. Precies genoeg contrast om het motief te laten lezen zonder dat het een print wordt.</p>
<h2>En de hand</h2>
<p>De laatste test is niet visueel. Je sluit je hand om de stof: geeft het borduursel mee met de tule, dan klopt het. Biedt het weerstand, dan gaat het terug naar het atelier.</p>',
'Anatomie van een borduursel','Dichtheid, achterkant, kleur en hand: de vier criteria waarmee we een borduursel beoordelen. Uit het AMÉLIE Journal.'),

(3,'it','Anatomia di un ricamo',
'Cosa separa un ricamo che accarezza da uno che dà fastidio dopo due ore.',
'<p>Nella lingerie un ricamo deve fare due cose insieme: sembrare delicato e non farsi sentire sulla pelle. Non è scontato.</p>
<h2>La densità</h2>
<p>Un ricamo troppo fitto irrigidisce la coppa e la deforma. In Blush Floral il motivo floreale è distribuito in modo arioso, lasciando respirare il tulle fra un fiore e l''altro.</p>
<h2>Il rovescio</h2>
<p>È dove si decide il comfort. I fili sul retro devono essere rifiniti e piatti. Se graffiano, il ricamo è risolto male, per quanto sia bello visto di fronte.</p>
<h2>Il colore</h2>
<p>Rosa su rosa, con piccoli accenti verdi. Il contrasto giusto perché il motivo si legga senza diventare una stampa.</p>
<h2>E la mano</h2>
<p>La prova definitiva non è visiva. Si chiude la mano sul tessuto: se il ricamo cede insieme al tulle, va bene. Se oppone resistenza, torna in atelier.</p>',
'Anatomia di un ricamo','Densità, rovescio, colore e mano: i quattro criteri con cui valutiamo un ricamo. Dal Journal di AMÉLIE.');
