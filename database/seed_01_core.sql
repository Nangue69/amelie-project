-- =====================================================================
-- AMÉLIE — Datos iniciales 01: idiomas, temas, ajustes, envíos, tallas
-- =====================================================================

SET NAMES utf8mb4;

-- Idiomas -------------------------------------------------------------
INSERT INTO `languages` (`code`,`name`,`native_name`,`is_default`,`is_active`,`position`) VALUES
('es','Español','Español',1,1,1),
('en','Inglés','English',0,1,2),
('nl','Neerlandés','Nederlands',0,1,3),
('it','Italiano','Italiano',0,1,4);

-- Temas visuales ------------------------------------------------------
INSERT INTO `themes` (`tkey`,`name`,`description`,`swatch`,`is_active`,`position`) VALUES
('borgogna','Borgoña','Burdeos profundo sobre marfil, con filetes en oro champán. El tema de marca.','#651D36,#F6F0E6,#C4A36A',1,1),
('avorio','Marfil','Luminoso y minimalista. El marfil domina y el oro aparece sólo en los detalles.','#F6F0E6,#C4A36A,#651D36',0,2),
('noir','Chocolate','Oscuro y envolvente, en la línea de The Muse. Satén, sombra y oro.','#3E2A22,#E8DED0,#C4A36A',0,3),
('blush','Blush','Rosa cipria y marfil, en la línea de Blush Floral. Suave y romántico.','#F2D3D8,#FDF7F3,#B07A8A',0,4);

-- Usuario administrador ----------------------------------------------
-- Correo: admin@amelie.com   Contraseña: Amelie2026!
INSERT INTO `users` (`name`,`email`,`password`,`role`,`is_active`) VALUES
('Administración AMÉLIE','admin@amelie.com','$2y$10$/B7N3uE.rcu7VMkSledfTOUR2WVaKWaNb0qezE47/4n1OJW97Y5MW','admin',1);

-- Ajustes -------------------------------------------------------------
INSERT INTO `settings` (`skey`,`svalue`,`sgroup`) VALUES
('site_name','AMÉLIE','general'),
('site_tagline','More Than Lingerie','general'),
('default_lang','es','general'),
('active_theme','borgogna','general'),
('coming_soon','0','general'),
('maintenance_message','','general'),

('contact_email','hola@amelie.com','contact'),
('contact_phone','+34 600 000 000','contact'),
('contact_address','Barcelona, España','contact'),
('company_legal_name','AMÉLIE Lingerie, S.L.','contact'),
('company_vat','B00000000','contact'),

('currency','EUR','shop'),
('currency_symbol','€','shop'),
('free_shipping_from','150.00','shop'),
('low_stock_threshold','5','shop'),
('products_per_page','12','shop'),

('instagram_url','https://instagram.com/amelie','social'),
('facebook_url','','social'),
('tiktok_url','','social'),
('pinterest_url','','social'),

('meta_title_suffix','AMÉLIE — More Than Lingerie','seo'),
('og_image','/assets/img/products/blush-flatlay.jpg','seo'),
('analytics_id','','seo'),
('robots_index','1','seo'),

('payment_bank_transfer','1','payment'),
('bank_holder','AMÉLIE Lingerie, S.L.','payment'),
('bank_name','','payment'),
('bank_iban','ES00 0000 0000 0000 0000 0000','payment'),
('bank_swift','','payment'),
('payment_stripe','0','payment'),
('stripe_public_key','','payment'),
('stripe_secret_key','','payment'),

('cookie_banner','1','privacy'),

('home_hero_image','/assets/img/products/blush-editorial.jpg','home'),
('home_story_image','/assets/img/products/muse-model-back.jpg','home'),
('home_craft_image','/assets/img/products/muse-detail-lace.jpg','home'),
('home_manifesto_image','/assets/img/products/blush-model-back.jpg','home');

-- Zonas de envío ------------------------------------------------------
INSERT INTO `shipping_rates` (`zone_name`,`countries`,`price`,`free_from`,`delivery_days`,`is_active`,`position`) VALUES
('España peninsular','ES',4.95,150.00,'2-3',1,1),
('Países Bajos y Bélgica','NL,BE',7.95,150.00,'3-5',1,2),
('Italia','IT',7.95,150.00,'3-5',1,3),
('Resto de la Unión Europea','DE,FR,PT,AT,IE,LU,DK,SE,FI,PL,CZ,SK,SI,HR,HU,RO,BG,EE,LV,LT,GR,CY,MT',9.95,180.00,'4-7',1,4),
('Resto del mundo','*',19.95,NULL,'7-14',1,5);

-- Cupón de bienvenida -------------------------------------------------
INSERT INTO `coupons` (`code`,`type`,`value`,`min_amount`,`usage_limit`,`is_active`) VALUES
('BIENVENIDA10','percent',10.00,0.00,NULL,1),
('ENVIOGRATIS','free_shipping',0.00,80.00,NULL,1);

-- Guías de tallas -----------------------------------------------------
INSERT INTO `size_guides` (`id`,`gkey`,`position`) VALUES
(1,'bra',1),
(2,'brief',2),
(3,'corset',3);

INSERT INTO `size_guide_rows` (`guide_id`,`label`,`bust`,`underbust`,`waist`,`hip`,`eu`,`it`,`uk`,`us`,`position`) VALUES
(1,'70B','82-84','68-72','','','70B','1B','32B','32B',1),
(1,'70C','84-86','68-72','','','70C','1C','32C','32C',2),
(1,'75B','87-89','73-77','','','75B','2B','34B','34B',3),
(1,'75C','89-91','73-77','','','75C','2C','34C','34C',4),
(1,'80B','92-94','78-82','','','80B','3B','36B','36B',5),
(1,'80C','94-96','78-82','','','80C','3C','36C','36C',6),

(2,'XS','','','60-64','86-90','34','38','6','2',1),
(2,'S','','','65-69','90-94','36','40','8','4',2),
(2,'M','','','70-74','94-98','38','42','10','6',3),
(2,'L','','','75-80','98-103','40','44','12','8',4),

(3,'XS','80-84','','60-64','86-90','34','38','6','2',1),
(3,'S','85-89','','65-69','90-94','36','40','8','4',2),
(3,'M','90-94','','70-74','94-98','38','42','10','6',3),
(3,'L','95-100','','75-80','98-103','40','44','12','8',4),
(3,'XL','101-106','','81-87','104-109','42','46','14','10',5);
