-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.1.26-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win32
-- HeidiSQL Versión:             10.1.0.5464
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Volcando estructura de base de datos para pinv
CREATE DATABASE IF NOT EXISTS `pinv` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `pinv`;

-- Volcando estructura para tabla pinv.categorias
CREATE TABLE IF NOT EXISTS `categorias` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(70) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla pinv.categorias: ~0 rows (aproximadamente)
DELETE FROM `categorias`;
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;

-- Volcando estructura para función pinv.CREATE_ID
DELIMITER //
CREATE DEFINER=`root`@`localhost` FUNCTION `CREATE_ID`(
	`size` INT(10)

) RETURNS varchar(128) CHARSET utf8
    COMMENT 'Crea un un id de maximo 128 varchar'
BEGIN

	DECLARE base64 VARCHAR(64);
	DECLARE str VARCHAR(128);
	
	SET base64 = 'DVNcEQROdCPGBfyjsFib2U3WeSTIYMA5JKZa1o9_LHhvprtzlx086g4u-w7kmqnX';
	
	main: LOOP
	
		SET str = CONCAT(IF(str IS NULL, '', str),SUBSTR(base64, RAND_BETWEEN(1, 64), 1));
		
		IF LENGTH(str) = size THEN
			LEAVE main;
		END IF;
		
	END LOOP main;
	
	RETURN str;


END//
DELIMITER ;

-- Volcando estructura para tabla pinv.departamentos
CREATE TABLE IF NOT EXISTS `departamentos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(70) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla pinv.departamentos: ~0 rows (aproximadamente)
DELETE FROM `departamentos`;
/*!40000 ALTER TABLE `departamentos` DISABLE KEYS */;
/*!40000 ALTER TABLE `departamentos` ENABLE KEYS */;

-- Volcando estructura para tabla pinv.estatus
CREATE TABLE IF NOT EXISTS `estatus` (
  `id` int(1) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del estatus',
  `nombre` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Nombre del estatus',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Tipos de estatus';

-- Volcando datos para la tabla pinv.estatus: ~4 rows (aproximadamente)
DELETE FROM `estatus`;
/*!40000 ALTER TABLE `estatus` DISABLE KEYS */;
INSERT INTO `estatus` (`id`, `nombre`) VALUES
	(1, 'Activo'),
	(2, 'Inactivo'),
	(3, 'Suspendido'),
	(4, 'Eliminado');
/*!40000 ALTER TABLE `estatus` ENABLE KEYS */;

-- Volcando estructura para tabla pinv.estatus_obj
CREATE TABLE IF NOT EXISTS `estatus_obj` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(70) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla pinv.estatus_obj: ~2 rows (aproximadamente)
DELETE FROM `estatus_obj`;
/*!40000 ALTER TABLE `estatus_obj` DISABLE KEYS */;
INSERT INTO `estatus_obj` (`id`, `nombre`) VALUES
	(2, 'Buen estado'),
	(1, 'Mal estado');
/*!40000 ALTER TABLE `estatus_obj` ENABLE KEYS */;

-- Volcando estructura para tabla pinv.inventario
CREATE TABLE IF NOT EXISTS `inventario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) DEFAULT NULL COMMENT 'Nombre del objeto',
  `marca` int(10) NOT NULL COMMENT 'Marca del producto',
  `serial` varchar(40) NOT NULL COMMENT 'Serial del objeto',
  `categoria` int(10) NOT NULL COMMENT 'Tipo del objeto',
  `fecha_registro` datetime NOT NULL COMMENT 'Fecha en que se cargo',
  `estatus` int(1) NOT NULL COMMENT 'Estatus actual del objeto',
  `descripcion` varchar(500) NOT NULL COMMENT 'Descripcion del obejto',
  `pid` varchar(12) NOT NULL COMMENT 'Identificador publico del articulo',
  PRIMARY KEY (`id`),
  UNIQUE KEY `serial` (`serial`),
  KEY `FK1_categoria` (`categoria`),
  KEY `FK2_estatus` (`estatus`),
  KEY `FK3_marca` (`marca`),
  KEY `search_pid` (`pid`),
  CONSTRAINT `FK1_categoria` FOREIGN KEY (`categoria`) REFERENCES `categorias` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK2_estatus` FOREIGN KEY (`estatus`) REFERENCES `estatus_obj` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK3_marca` FOREIGN KEY (`marca`) REFERENCES `marcas` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla pinv.inventario: ~0 rows (aproximadamente)
DELETE FROM `inventario`;
/*!40000 ALTER TABLE `inventario` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventario` ENABLE KEYS */;

-- Volcando estructura para tabla pinv.log_inventario
CREATE TABLE IF NOT EXISTS `log_inventario` (
  `id` varchar(10) NOT NULL COMMENT 'Identificador unico y publico',
  `item` int(10) NOT NULL COMMENT 'Identificador interno del obejto',
  `fecha` datetime NOT NULL COMMENT 'Fecha de la accion',
  `accion` varchar(2000) DEFAULT NULL COMMENT 'Que se realizo',
  `estatus` int(1) NOT NULL COMMENT 'Estatus del objeto cuando se realizo la accion',
  `departamento` int(10) DEFAULT NULL COMMENT 'Departamento al cual se llevo el objeto',
  `persona` int(10) unsigned DEFAULT NULL COMMENT 'Persona a la cual sera asignada',
  `asignado` tinyint(1) DEFAULT NULL COMMENT 'Indica si fue asignado o retirado',
  `puesto` int(10) DEFAULT NULL COMMENT 'En caso de que sea a un puesto',
  PRIMARY KEY (`id`),
  KEY `FK_estatus` (`estatus`),
  KEY `FK_inventario` (`item`),
  KEY `FK_departamento` (`departamento`),
  KEY `FK_persona` (`persona`),
  KEY `index_date` (`fecha`),
  CONSTRAINT `FK_departamento` FOREIGN KEY (`departamento`) REFERENCES `departamentos` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_estatus` FOREIGN KEY (`estatus`) REFERENCES `estatus_obj` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_inventario` FOREIGN KEY (`item`) REFERENCES `inventario` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_persona` FOREIGN KEY (`persona`) REFERENCES `personas` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla pinv.log_inventario: ~0 rows (aproximadamente)
DELETE FROM `log_inventario`;
/*!40000 ALTER TABLE `log_inventario` DISABLE KEYS */;
/*!40000 ALTER TABLE `log_inventario` ENABLE KEYS */;

-- Volcando estructura para tabla pinv.marcas
CREATE TABLE IF NOT EXISTS `marcas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla pinv.marcas: ~0 rows (aproximadamente)
DELETE FROM `marcas`;
/*!40000 ALTER TABLE `marcas` DISABLE KEYS */;
/*!40000 ALTER TABLE `marcas` ENABLE KEYS */;

-- Volcando estructura para función pinv.PASSWORD_HASH
DELIMITER //
CREATE DEFINER=`root`@`localhost` FUNCTION `PASSWORD_HASH`(
	`password` VARCHAR(70),
	`salt` VARCHAR(40),
	`name` VARCHAR(50)


) RETURNS varchar(128) CHARSET utf8
    COMMENT 'Crea una contraseña segurita'
BEGIN
	
	DECLARE str VARCHAR(128);
	SET str = 'A';
	SET str = SHA2(CONCAT(SHA2(password, 512), str), 512);
	SET str = SHA2(to_base64(CONCAT(str, salt, SHA2(name, 512))), 512);
	
	RETURN str;
END//
DELIMITER ;

-- Volcando estructura para tabla pinv.personas
CREATE TABLE IF NOT EXISTS `personas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identificador interno de la persona',
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Primer nombre',
  `lname` varchar(70) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Primer apellido',
  `created_at` datetime NOT NULL COMMENT 'Fecha de creacion',
  `cedula` varchar(12) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Documento de identificaicon',
  `pid` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Identificador publico unico',
  `inserted_by` int(10) NOT NULL COMMENT 'Creado por',
  PRIMARY KEY (`id`),
  KEY `FK_inserted_by` (`inserted_by`),
  CONSTRAINT `FK_inserted_by` FOREIGN KEY (`inserted_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Personas en el sistema';

-- Volcando datos para la tabla pinv.personas: ~1 rows (aproximadamente)
DELETE FROM `personas`;
/*!40000 ALTER TABLE `personas` DISABLE KEYS */;
INSERT INTO `personas` (`id`, `name`, `lname`, `created_at`, `cedula`, `pid`, `inserted_by`) VALUES
	(1, 'Daniel', 'Sepulveda', '2019-07-11 15:26:11', '21438842', 'fmd0o1c1r4', 1);
/*!40000 ALTER TABLE `personas` ENABLE KEYS */;

-- Volcando estructura para función pinv.RAND_BETWEEN
DELIMITER //
CREATE DEFINER=`root`@`localhost` FUNCTION `RAND_BETWEEN`(
	`minimun` INT(10),
	`maximun` INT(10)

) RETURNS int(10)
    COMMENT 'Numero aleatorio entre dos valores'
BEGIN
	RETURN ROUND((RAND() * (maximun-minimun))+minimun);
END//
DELIMITER ;

-- Volcando estructura para tabla pinv.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del usuario',
  `nick` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Nick publico',
  `password` varchar(128) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Not the password',
  `estatus` int(1) NOT NULL DEFAULT '1' COMMENT 'Estatus del usuario',
  `person` int(10) unsigned NOT NULL DEFAULT '1' COMMENT 'Propietario del usuario',
  `access` varchar(500) COLLATE utf8_unicode_ci DEFAULT '[]' COMMENT 'Permisos del usuario',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nick` (`nick`),
  KEY `FK_person_id` (`person`),
  KEY `FK_estatus_id` (`estatus`),
  CONSTRAINT `FK_estatus_id` FOREIGN KEY (`estatus`) REFERENCES `estatus` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_person_id` FOREIGN KEY (`person`) REFERENCES `personas` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Usuarios del sistema';

-- Volcando datos para la tabla pinv.users: ~1 rows (aproximadamente)
DELETE FROM `users`;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `nick`, `password`, `estatus`, `person`, `access`) VALUES
	(1, 'dsepulveda', 'dbbeeb85ec44f0c8145786f195e04ce526f4e9c09fa97a81fcbd544beb99766fb40afd3d29703810dae742a338581a0a0fe6c43904e5562febf9ec0c1befcfdc', 1, 1, '[0,1,2,3,4,5,6,7,8,9,10,11,12]');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

-- Volcando estructura para disparador pinv.inventario_before_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `inventario_before_insert` BEFORE INSERT ON `inventario` FOR EACH ROW BEGIN
	SET NEW.pid = REPLACE(REPLACE(CREATE_ID(12), '_', 'w'), '-', 'r');
	
	WHILE((SELECT id FROM inventario WHERE pid = NEW.pid) > 0) DO
		SET NEW.pid = REPLACE(REPLACE(CREATE_ID(12), '_', 'w'), '-', 'r');
	END WHILE;
	
	SET NEW.fecha_registro = NOW();
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Volcando estructura para disparador pinv.log_inventario_before_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `log_inventario_before_insert` BEFORE INSERT ON `log_inventario` FOR EACH ROW BEGIN
	SET NEW.id = CREATE_ID(10);
	
	WHILE((SELECT id FROM log_inventario WHERE id = NEW.id) > 0) DO
		SET NEW.id = CREATE_ID(10);
	END WHILE;
	
	SET NEW.fecha = NOW();
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Volcando estructura para disparador pinv.personas_before_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `personas_before_insert` BEFORE INSERT ON `personas` FOR EACH ROW BEGIN
	SET NEW.pid = CREATE_ID(10);
	
	WHILE((SELECT id FROM personas WHERE pid = NEW.pid) > 0) DO
		SET NEW.pid = CREATE_ID(10);
	END WHILE;
	
	SET NEW.created_at = NOW();
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
