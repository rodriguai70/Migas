DROP DATABASE IF EXISTS todomiga;

CREATE DATABASE todomiga DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci;
USE todomiga;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = @@session.time_zone;


CREATE TABLE IF NOT EXISTS `categoria` (
  `cod_categoria` VARCHAR(6) COLLATE utf8mb4_spanish_ci NOT NULL,
  `nombre_cat` VARCHAR(200) COLLATE utf8mb4_spanish_ci NOT NULL,
  PRIMARY KEY (`cod_categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

INSERT INTO `categoria` (`cod_categoria`, `nombre_cat`) VALUES
('PANADERIA', 'Panadería'),
('BOLLERIA', 'Bollería'),
('PASTELERIA', 'Pastelería');

CREATE TABLE IF NOT EXISTS `usuarios` (
  `usuario` VARCHAR(20) COLLATE utf8mb4_spanish_ci NOT NULL,
  `contrasena` VARCHAR(255) COLLATE utf8mb4_spanish_ci NOT NULL,
  PRIMARY KEY (`usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

/*INSERT INTO `usuarios` (`usuario`, `contrasena`) VALUES
('Rodri', PASSWORD('1234')),
('Maria', PASSWORD('1234')),
('Juan', PASSWORD('1234')),
('Pedro', PASSWORD('1234')),
('Luis', PASSWORD('1234')),
('Ana', PASSWORD('1234')),
('Carlos', PASSWORD('1234')),
('Sofia', PASSWORD('1234')),
('Galo', PASSWORD('1234'));*/

CREATE TABLE IF NOT EXISTS `producto` (
  `cod_producto` INT NOT NULL AUTO_INCREMENT,
  `nombre_prod` VARCHAR(100) NOT NULL,
  `ingrediente` TEXT DEFAULT NULL,
  `precio` DECIMAL(10,2) NOT NULL,
  `cod_categoria` VARCHAR(6) COLLATE utf8mb4_spanish_ci NOT NULL,
  PRIMARY KEY (`cod_producto`),
  FOREIGN KEY (`cod_categoria`) REFERENCES `categoria`(`cod_categoria`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;


INSERT INTO `producto` (`nombre_prod`, `ingrediente`, `precio`, `cod_categoria`) VALUES
('Baguette', 'Harina de trigo, agua, sal yodada, masa madre, sémola de trigo, levadura, enzimas, ácido ascórbico.', 0.60, 'PANADERIA'),
('Barra Bocadillo', 'Barra de bocadillo muy crujiente, a base de harina de trigo, levadura y sal.', 0.40, 'PANADERIA'),
('Barra Pan', 'Barra de pan esponjosa recién horneada, elaborada con harina de trigo, levadura y sal.', 0.90, 'PANADERIA'),
('Barra Rústica', 'Barra rústica con 15% de harina integral de trigo y harina de malta de cebada.', 1.20, 'PANADERIA'),
('Chapata', 'Pan de miga ligera y corteza crujiente, con masa madre fermentada 24 horas.', 1.10, 'PANADERIA'),
('Hogaza Centeno', 'Pan de centeno, rico en fibra, hierro y minerales. Contenido en gluten más bajo.', 2.10, 'PANADERIA'),
('Hogaza Premium', 'Hogaza premium artesanal, con ingredientes 100% naturales y masa madre exclusiva.', 2.40, 'PANADERIA'),
('Hogaza Pueblo', 'Hogaza de pueblo con miga tierna y corteza crujiente, con masa madre de trigo.', 2.50, 'PANADERIA'),
('Mollete', 'Pan esponjoso y corteza crujiente, ideal para desayunos y almuerzos.', 0.40, 'PANADERIA'),
('Pan Abuela', 'Pan tradicional con harina de trigo y centeno, agua, levadura y sal.', 0.90, 'PANADERIA'),
('Panecillo Semillas', 'Panecillos con semillas de sésamo, avena, centeno y cebada.', 0.70, 'PANADERIA'),
('Pulguita', 'Panecillo tierno de corteza fina y miga suave, ideal para pinchos y bocaditos.', 0.20, 'PANADERIA'),
('Croissant pequeño', 'Croissant tradicional de margarina.',0.60, 'BOLLERIA'),
('Tocino de cielo','Azúcar, yema pasteurizada de HUEVO (yema de HUEVO de gallina, conservador (E-202)), agua, HUEVOS.' ,2.00, 'PASTELERIA'),
('Trufas de chocolate', 'NATA 35% M.G (NATA (LECHE), proteínas de la LECHE y estabilizantes (carragenato, celulosa microcristalina y carboximetil celulosa)), 
chocolate negro Nestle (azúcar, pasta de cacao (50%), manteca de cacao, emulgente (lecitinas), aroma natural de vainilla), cacao en polvo (cacao desgrasado (12%)).',2.50,'PASTELERIA'),
('Torta de chocolate', 'HUEVO (yema de HUEVO de gallina, conservador (E-202)), azúcar, harina de trigo, leche,
manteca de cacao, chocolate negro Nestle (azúcar, pasta de cacao (50%), manteca de cacao, emulgente (lecitinas),
aroma natural de vainilla), cacao en polvo (cacao desgrasado (12%)).', 3.00, 'PASTELERIA'),
('Torta de frutas', 'Harina de TRIGO, margarina (grasas vegetales totalmente hidrogenadas y no hidrogenadas 
(palma, colza, palmiste), agua, sal, emulgente (E-471), colorante (E-160ª(i), aromas), HUEVO, sal, LECHE desnatada en polvo, 
gasificantes (bicarbonato de sodio E-500 (ii)), crema pastelera (azúcar, almidón modificado, LECHE entera en polvo, suero de LECHE en polvo,
estabilizantes (fosfato disódico anhidro, difosfato tetrasódico, alginato sódico y sulfato cálcico), sal, acidulante (ácido cítrico), colorantes (betacaroteno, carmín cochinilla), 
conservador (sorbato potásico), aroma), frutas (12%)(fresa, kiwi piña, frambuesa, arándanos, grosella roja), gelatina.',2.60, 'PASTELERIA'),
('BROWNIE', 'Azúcar, harina de TRIGO, cacao en polvo desgrasado, glucosa, almidón modificado, almidón de TRIGO,
aceite vegetal totalmente hidrogenado de coco, gasificantes (difosfato disódico y bicarbonato sódico), emulgente (mono
y diglicéridos de ácidos grasos), albúmina de HUEVO, LECHE desnatada en polvo, sal, jarabe de glucosa, aromas (LECHE),
estabilizante (goma xantana), proteína de LECHE, aceite de girasol, agua, HUEVO fresco, NUECES troceadas.', 2.80, 'PASTELERIA'),
('Tatin de manzana', 'GALLETA SABLÉ (Harina de TRIGO, mantequilla (LECHE), HUEVOS, azúcar glass (azúcar, almidón), aroma
vainilla y colorantes (E-102*, E-129*)).CREMA PASTELERA (azúcar, almidón modificado, LECHE entera en polvo, suero de LECHE en polvo,estabilizantes (E-339ii, E-450ii, E-516i),
 sal, acidulante (E-330), colorantes (E-610a, E-120*), conservador (E202), aroma), manzana (0,25%), gelatina (jarabe de glucosa, agua, azúcar, estabilizantes (E-440i, E-415),'
 , 3.20, 'PASTELERIA'),
 ('Palmera de crema', 'Relleno de crema de cacao con avellanas (41%), con una masa hojaldrada.' , 2.50, 'BOLLERIA'),
 ('Palmera de chocolate', 'Relleno de chocolate con avellanas (41%), con una masa hojaldrada.' , 2.50, 'BOLLERIA'),
 ('Pañuelo de crema de cacao con avellanas', 'Relleno de crema de cacao con avellanas (41%), con una masa hojaldrada.' , 2.50, 'BOLLERIA'),
 ('Entremet crujiente 3 chocolates', 'Una capa de "Biscuit Joconde" con otra de praliné crocante (chocolate con leche y crepe dentelle), 
 crema bavaroise al chocolate blanco y crema bavaroise al chocolate negro (cacao 64%), chocolate negro en polvo (cacao 64%)' , 4.00, 'PASTELERIA'),
 ('Croissant relleno chocolate', 'Croissant tradicional de margarina relleno de chocolate.', 2.00, 'BOLLERIA'),
 ('Croissant bañado chocolate', 'Croissant tradicional bañado de chocolate.' , 2.00, 'BOLLERIA'),
 ('Ensaimada pequeña', 'Ensaimada tradicional hojaldrada y espolvoreada con azúcar lustre.', 1.50, 'BOLLERIA'),
 ('Bandas de hojaldre', 'Bandas de hojaldre con crema y manzana/nuez/chocolate. Precortadas.' , 2.00, 'BOLLERIA'),
 ('Palo bañado chocolate', 'Palo de pasta petisú relleno de crema y bañado con chocolate.', 2.00, 'BOLLERIA'),
 ('Milhojas', 'Pastel de milhoja: Hojaldre, crema pastelera y cobertura de chocolate.' , 3.00, 'PASTELERIA'),
 ('Medias lunas', 'Medias lunas rellenas de crema y espolvoreadas con azúcar lustre.', 2.00, 'BOLLERIA'),
 ('Palo de crema', 'Palo de pasta petisú relleno de crema pastelera.', 2.00, 'BOLLERIA'),
 ('Palo de crema', 'Palo de pasta petisú relleno de crema pastelera.', 2.00, 'BOLLERIA'),
 ('Empanadillas Cabello', 'Empanadilla de masa escaldada con relleno de cabello de ángel.',  2.00, 'PASTELERIA');

CREATE TABLE IF NOT EXISTS `cesta` (
  `usuario` VARCHAR(20) COLLATE utf8mb4_spanish_ci NOT NULL COMMENT 'Código del usuario',
  `cod_producto` INT NOT NULL COMMENT 'Código del producto',
  `unidades` SMALLINT(6) NOT NULL,
  PRIMARY KEY (`usuario`, `cod_producto`),
  FOREIGN KEY (`usuario`) REFERENCES `usuarios`(`usuario`) ON DELETE CASCADE,
  FOREIGN KEY (`cod_producto`) REFERENCES `producto`(`cod_producto`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci COMMENT='Contiene la cesta guardada de un usuario';

COMMIT;

