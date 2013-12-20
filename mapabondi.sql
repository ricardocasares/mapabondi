-- Adminer 3.7.0 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = '-03:00';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `lines`;
CREATE TABLE `lines` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `transport_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `image` varchar(255) COLLATE utf8_bin NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `lines` (`id`, `transport_id`, `name`, `image`, `created`, `updated`) VALUES
(1,	4,	'2 ',	'',	'2013-12-20 00:51:39',	NULL),
(2,	5,	'6 ',	'',	'2013-12-20 00:51:39',	NULL),
(3,	6,	'6 A',	'',	'2013-12-20 00:51:39',	NULL),
(4,	1,	'6 B',	'',	'2013-12-20 00:51:39',	NULL),
(5,	5,	'7 ',	'',	'2013-12-20 00:51:39',	NULL),
(6,	4,	'8 ',	'',	'2013-12-20 00:51:39',	NULL),
(7,	7,	'9 ',	'',	'2013-12-20 00:51:39',	NULL),
(8,	7,	'10 ',	'',	'2013-12-20 00:51:39',	NULL),
(9,	1,	'11 ',	'',	'2013-12-20 00:51:39',	NULL),
(10,	6,	'12 ',	'',	'2013-12-20 00:51:39',	NULL),
(11,	6,	'12 A',	'',	'2013-12-20 00:51:39',	NULL),
(12,	6,	'13 ',	'',	'2013-12-20 00:51:39',	NULL),
(13,	5,	'14 ',	'',	'2013-12-20 00:51:39',	NULL),
(14,	5,	'14 A',	'',	'2013-12-20 00:51:39',	NULL),
(15,	2,	'15 ',	'',	'2013-12-20 00:51:39',	NULL),
(16,	7,	'17 ',	'',	'2013-12-20 00:51:39',	NULL),
(17,	4,	'20 CGT',	'',	'2013-12-20 00:51:39',	NULL),
(18,	7,	'21 ',	'',	'2013-12-20 00:51:39',	NULL),
(19,	2,	'26 A',	'',	'2013-12-20 00:51:39',	NULL),
(20,	1,	'26 B',	'',	'2013-12-20 00:51:39',	NULL),
(21,	2,	'27 ',	'',	'2013-12-20 00:51:39',	NULL),
(22,	4,	'28 ',	'',	'2013-12-20 00:51:39',	NULL),
(23,	8,	'32 Directo',	'',	'2013-12-20 00:51:39',	NULL),
(24,	8,	'32 Escobar',	'',	'2013-12-20 00:51:39',	NULL),
(25,	4,	'33 M',	'',	'2013-12-20 00:51:39',	NULL),
(26,	4,	'33 S',	'',	'2013-12-20 00:51:39',	NULL),
(27,	5,	'35 ',	'',	'2013-12-20 00:51:39',	NULL),
(28,	8,	'36 ',	'',	'2013-12-20 00:51:39',	NULL),
(29,	7,	'38 ',	'',	'2013-12-20 00:51:39',	NULL),
(30,	1,	'39 ',	'',	'2013-12-20 00:51:39',	NULL),
(31,	7,	'40 ',	'',	'2013-12-20 00:51:39',	NULL),
(32,	7,	'41 ',	'',	'2013-12-20 00:51:39',	NULL),
(33,	7,	'42 ',	'',	'2013-12-20 00:51:39',	NULL),
(34,	2,	'45 ',	'',	'2013-12-20 00:51:39',	NULL),
(35,	2,	'47 ',	'',	'2013-12-20 00:51:39',	NULL),
(36,	2,	'49 ',	'',	'2013-12-20 00:51:39',	NULL),
(37,	2,	'50 ',	'',	'2013-12-20 00:51:39',	NULL),
(38,	7,	'53 ',	'',	'2013-12-20 00:51:39',	NULL),
(39,	2,	'60 ',	'',	'2013-12-20 00:51:39',	NULL),
(40,	5,	'70 ',	'',	'2013-12-20 00:51:39',	NULL),
(41,	1,	'4 Recorrido 1',	'',	'2013-12-20 00:51:39',	NULL),
(42,	1,	'4 Recorrido 2',	'',	'2013-12-20 00:51:39',	NULL),
(43,	1,	'4 Recorrido 3',	'',	'2013-12-20 00:51:39',	NULL),
(44,	9,	'109 Directo',	'',	'2013-12-20 00:51:39',	NULL),
(45,	2,	'16 ',	'',	'2013-12-20 00:51:39',	NULL),
(46,	4,	'18 Recorrido 1 y 2',	'',	'2013-12-20 00:51:39',	NULL),
(47,	4,	'18 Recorrido 3 y 4',	'',	'2013-12-20 00:51:39',	NULL),
(48,	4,	'18 A Nocturno',	'',	'2013-12-20 00:51:39',	NULL),
(49,	4,	'18 A',	'',	'2013-12-20 00:51:39',	NULL),
(50,	4,	'18 Campo de Batalla - Las Tapias',	'',	'2013-12-20 00:51:39',	NULL),
(51,	4,	'18 Campo de Batalla - Punta del Monte',	'',	'2013-12-20 00:51:39',	NULL),
(52,	4,	'18 El Bosque',	'',	'2013-12-20 00:51:39',	NULL),
(53,	4,	'18 Escuelas',	'',	'2013-12-20 00:51:39',	NULL),
(54,	4,	'18 Escuelas - Albardon',	'',	'2013-12-20 00:51:39',	NULL),
(55,	4,	'18 Las Tapias - Cementerio',	'',	'2013-12-20 00:51:39',	NULL),
(56,	1,	'19 Pozo Salado',	'',	'2013-12-20 00:51:39',	NULL),
(57,	1,	'19 La Plata - Tupeli',	'',	'2013-12-20 00:51:39',	NULL),
(58,	1,	'19 Colonia Rawson',	'',	'2013-12-20 00:51:39',	NULL),
(59,	1,	'19 San Martin',	'',	'2013-12-20 00:51:39',	NULL),
(60,	1,	'19 El Encon',	'',	'2013-12-20 00:51:39',	NULL),
(61,	1,	'19 Caucete - Media Agua',	'',	'2013-12-20 00:51:39',	NULL),
(62,	1,	'19 Caucete - Circuito Interno',	'',	'2013-12-20 00:51:39',	NULL),
(63,	4,	'20 Campo Afuera',	'',	'2013-12-20 00:51:39',	NULL),
(64,	4,	'20 Combinacion Nocturno - Domingos',	'',	'2013-12-20 00:51:39',	NULL),
(65,	4,	'20 Directo',	'',	'2013-12-20 00:51:39',	NULL),
(66,	4,	'20 Rincon - Las Tapias',	'',	'2013-12-20 00:51:39',	NULL),
(67,	1,	'22 9 de Julio',	'',	'2013-12-20 00:51:39',	NULL),
(68,	10,	'23 Recorrido 1',	'',	'2013-12-20 00:51:39',	NULL),
(69,	10,	'23 Recorrido 2',	'',	'2013-12-20 00:51:39',	NULL),
(70,	10,	'23 Recorrido 3',	'',	'2013-12-20 00:51:39',	NULL),
(71,	11,	'24 Cochagual',	'',	'2013-12-20 00:51:39',	NULL),
(72,	11,	'24 Colonia Fiscal',	'',	'2013-12-20 00:51:39',	NULL),
(73,	11,	'24 ',	'',	'2013-12-20 00:51:39',	NULL),
(74,	11,	'24 B Ruta 40',	'',	'2013-12-20 00:51:39',	NULL),
(75,	11,	'24 5',	'',	'2013-12-20 00:51:39',	NULL),
(76,	11,	'24 6',	'',	'2013-12-20 00:51:39',	NULL),
(77,	11,	'24 7',	'',	'2013-12-20 00:51:39',	NULL),
(78,	11,	'24 Pedernal',	'',	'2013-12-20 00:51:39',	NULL),
(79,	2,	'25 P',	'',	'2013-12-20 00:51:39',	NULL),
(80,	2,	'25 M',	'',	'2013-12-20 00:51:39',	NULL),
(81,	12,	'29 ',	'',	'2013-12-20 00:51:39',	NULL),
(82,	2,	'43 ',	'',	'2013-12-20 00:51:39',	NULL),
(83,	2,	'46 ',	'',	'2013-12-20 00:51:39',	NULL);