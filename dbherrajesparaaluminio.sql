-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-08-2020 a las 00:36:18
-- Versión del servidor: 10.4.13-MariaDB
-- Versión de PHP: 7.4.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `dbherrajesparaaluminio`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `actualizar_precio_producto` (`n_cantidad` INT, `n_precio` DECIMAL(10,2), `codigo` INT)  BEGIN 
DECLARE nueva_existencia int;
DECLARE nuevo_total decimal(10,2);
DECLARE nuevo_precio decimal(10,2);
DECLARE porcentaje decimal (10,2);
DECLARE cant_actual int;
DECLARE pre_actual decimal(10,2);
DECLARE precio_porcentaje decimal (10,2);

DECLARE actual_existencia int;
DECLARE actual_precio decimal(10,2);

SELECT precio,existencia INTO actual_precio,actual_existencia FROM producto WHERE codproducto = codigo;

SET nueva_existencia = actual_existencia + n_cantidad;
SET nuevo_total =(actual_existencia * actual_precio) + (n_cantidad * n_precio);
SET nuevo_precio = nuevo_total / nueva_existencia;
SET porcentaje = nuevo_precio * .10;
SET precio_porcentaje = porcentaje + nuevo_precio;
UPDATE producto SET existencia = nueva_existencia, precio = precio_porcentaje WHERE codproducto = codigo;

SELECT nueva_existencia,precio_porcentaje;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `add_detalle_temp` (`codigo` INT, `cantidad` INT, `token_user` VARCHAR(50))  BEGIN 

 DECLARE precio_actual decimal(10,2);
 SELECT precio INTO precio_actual FROM producto WHERE codproducto =codigo;
 
 INSERT INTO detalle_temp(token_user, codproducto, cantidad, precio_venta) VALUES (token_user, codigo,cantidad,precio_actual);
 
 SELECT tmp.correlativo, tmp.codproducto,p.descripcion,tmp.cantidad,tmp.precio_venta FROM detalle_temp tmp
 INNER JOIN producto p 
 ON tmp.codproducto = p.codproducto
 WHERE tmp.token_user = token_user;
 
 END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `del_detalle_temp` (`id_detalle` INT, `token` VARCHAR(50))  BEGIN 
DELETE FROM detalle_temp WHERE correlativo = id_detalle;

SELECT  tmp.correlativo, tmp.codproducto, p.descripcion, tmp.cantidad, tmp.precio_venta FROM detalle_temp tmp 
INNER JOIN  producto p
ON tmp.codproducto = p.codproducto
WHERE  tmp.token_user = token;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `procesar_venta` (`cod_usuario` INT, `cod_cliente` INT, `token` VARCHAR(50))  BEGIN
    DECLARE factura INT;
    DECLARE registros INT;
    DECLARE total DECIMAL(10,2);
    DECLARE nueva_existencia int;
    DECLARE existencia_actual int;
    DECLARE tmp_cod_producto int;
    DECLARE tmp_cant_producto int;
    DECLARE a INT;
    SET a = 1;
    
    CREATE TEMPORARY TABLE tbl_tmp_tokenuser(
    id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    cod_prod BIGINT,
    cant_prod int);
    SET registros = (SELECT COUNT(*) FROM detalle_temp WHERE token_user = token);
    
    IF registros > 0 THEN
       INSERT INTO tbl_tmp_tokenuser(cod_prod,cant_prod) SELECT codproducto,cantidad FROM detalle_temp WHERE token_user = token;
       INSERT INTO factura(usuario,codcliente)VALUES(cod_usuario,cod_cliente);
       SET factura = LAST_INSERT_ID();
       INSERT INTO detallefactura(nofactura,codproducto,cantidad,precio_venta) SELECT (factura) as nofactura, codproducto,cantidad,precio_venta FROM detalle_temp WHERE token_user = token;
   
   WHILE a <= registros DO
   SELECT cod_prod,cant_prod INTO tmp_cod_producto,tmp_cant_producto FROM tbl_tmp_tokenuser WHERE id = a;
   SELECT existencia INTO existencia_actual FROM producto WHERE codproducto = tmp_cod_producto;
   
   SET nueva_existencia = existencia_actual - tmp_cant_producto;
   UPDATE producto SET existencia = nueva_existencia WHERE codproducto = tmp_cod_producto;
   SET a=a+1;
   
   END WHILE;
   
   SET total =(SELECT SUM(cantidad * precio_venta) FROM detalle_temp WHERE token_user = token);
   UPDATE factura SET totalfactura = total WHERE nofactura = factura;
   DELETE FROM  detalle_temp WHERE token_user = token;
   TRUNCATE TABLE tbl_tmp_tokenuser;
   SELECT * FROM factura WHERE nofactura = factura;
   
   ELSE
   SELECT 0;
   END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `idcliente` int(11) NOT NULL,
  `nit` varchar(11) DEFAULT NULL,
  `nombre` varchar(80) DEFAULT NULL,
  `telefono` bigint(11) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `dateadd` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_id` int(11) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`idcliente`, `nit`, `nombre`, `telefono`, `direccion`, `dateadd`, `usuario_id`, `estatus`) VALUES
(1, 'cf', 'cf', 2228656175, 'sin direccion', '2020-05-09 16:08:08', 1, 1),
(2, 'AUSE990729', 'Edith Aguirre Sanchez', 2228771633, 'Priv 27 a sur 13314 letra B Hacienda Santa Clara', '2020-05-11 15:15:38', 1, 1),
(3, '2610', 'Juan Camaney', 2147483647, 'calle tulipanes #1205', '2020-05-11 15:18:06', 1, 1),
(4, '1234', 'Edith Aguirre', 211222121, 'calle tulipanes #1205', '2020-05-11 15:44:15', 1, 1),
(5, '12', 'Alex Prueba', 2147483647, 'calle rosale edif d 3', '2020-05-11 21:35:29', 1, 0),
(6, 'GAGA990502H', 'Ezequiel Moreno', 2212290360, 'rosale edif d # 4', '2020-05-12 19:04:18', 6, 1),
(9, '0', 'cf', 2228656175, '139 a pte. #1305 San Bernabe', '2020-07-28 06:18:59', 1, 0),
(10, 'AUSE', '', 0, '', '2020-07-31 23:04:16', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE `configuracion` (
  `id` bigint(20) NOT NULL,
  `nit` varchar(30) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `razon_social` varchar(100) NOT NULL,
  `telefono` bigint(20) NOT NULL,
  `email` varchar(200) NOT NULL,
  `direccion` text NOT NULL,
  `iva` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `configuracion`
--

INSERT INTO `configuracion` (`id`, `nit`, `nombre`, `razon_social`, `telefono`, `email`, `direccion`, `iva`) VALUES
(2, 'GASW7009242FA', 'Herrajes Para Aluminio', 'Willebaldo Gerardo Garcia Santiago', 2228656175, 'siac1ventas@hotmail.com', 'Privada 13 A Sur 13923 Local A colonia Jardines de castllotla', '16.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detallefactura`
--

CREATE TABLE `detallefactura` (
  `correlativo` bigint(11) NOT NULL,
  `nofactura` bigint(11) DEFAULT NULL,
  `codproducto` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio_venta` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `detallefactura`
--

INSERT INTO `detallefactura` (`correlativo`, `nofactura`, `codproducto`, `cantidad`, `precio_venta`) VALUES
(1, 1, 23, 2, '29.27'),
(2, 1, 21, 1, '350.00'),
(3, 2, 19, 1, '105.37'),
(4, 2, 23, 1, '29.27'),
(5, 2, 15, 1, '6.05'),
(6, 3, 19, 1, '105.37'),
(7, 3, 23, 3, '29.27'),
(8, 3, 16, 3, '21.00'),
(9, 4, 23, 6, '29.27'),
(10, 4, 19, 1, '105.37'),
(12, 5, 23, 1, '29.27'),
(13, 6, 19, 1, '105.37'),
(14, 7, 23, 1, '29.27'),
(15, 7, 19, 1, '105.37'),
(16, 7, 15, 1, '6.05'),
(17, 7, 16, 1, '21.00'),
(18, 7, 18, 1, '23.00'),
(19, 8, 19, 1, '105.37'),
(20, 9, 19, 9, '105.37'),
(21, 9, 23, 2, '29.27'),
(23, 10, 23, 1, '29.27'),
(24, 10, 15, 1, '6.05'),
(25, 10, 18, 1, '23.00'),
(26, 11, 19, 1, '105.37'),
(27, 12, 19, 1, '105.37'),
(28, 12, 23, 1, '29.27'),
(30, 13, 23, 1, '29.27'),
(31, 13, 19, 1, '105.37'),
(32, 13, 15, 1, '6.05'),
(33, 14, 19, 2, '105.37'),
(34, 14, 23, 1, '29.27'),
(35, 14, 21, 1, '350.00'),
(36, 15, 21, 1, '350.00'),
(37, 15, 16, 5, '83.00'),
(39, 16, 19, 1, '105.37'),
(40, 17, 19, 1, '105.37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_temp`
--

CREATE TABLE `detalle_temp` (
  `correlativo` int(11) NOT NULL,
  `token_user` varchar(50) NOT NULL,
  `codproducto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entradas`
--

CREATE TABLE `entradas` (
  `correlativo` int(11) NOT NULL,
  `codproducto` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `usuario_id` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `entradas`
--

INSERT INTO `entradas` (`correlativo`, `codproducto`, `fecha`, `cantidad`, `precio`, `usuario_id`) VALUES
(12, 15, '2020-05-15 14:07:28', 1, '100.00', 1),
(13, 16, '2020-05-15 14:26:48', 23, '21.00', 1),
(15, 18, '2020-05-15 20:00:26', 2, '23.00', 1),
(16, 19, '2020-05-28 19:10:29', 10, '223.00', 1),
(17, 19, '2020-05-28 19:10:58', 200, '12.00', 1),
(18, 19, '2020-05-28 20:44:45', 21, '21.00', 1),
(19, 19, '2020-05-28 20:44:45', 21, '21.00', 1),
(20, 19, '2020-05-28 20:44:45', 21, '21.00', 1),
(21, 19, '2020-05-28 20:44:46', 21, '21.00', 1),
(22, 19, '2020-05-28 20:44:46', 21, '21.00', 1),
(23, 19, '2020-05-28 20:44:46', 21, '21.00', 1),
(24, 19, '2020-05-28 20:44:46', 21, '21.00', 1),
(25, 19, '2020-05-28 20:45:50', 10, '239.00', 1),
(26, 19, '2020-05-28 20:45:51', 10, '239.00', 1),
(27, 19, '2020-05-28 20:49:40', 23, '1222.00', 1),
(28, 19, '2020-05-28 20:49:41', 23, '1222.00', 1),
(29, 19, '2020-05-28 20:49:41', 23, '1222.00', 1),
(30, 19, '2020-05-28 20:49:41', 23, '1222.00', 1),
(31, 19, '2020-05-28 20:49:41', 23, '1222.00', 1),
(32, 19, '2020-05-28 20:49:41', 23, '1222.00', 1),
(33, 19, '2020-05-28 20:49:59', 34, '344.00', 1),
(34, 19, '2020-05-28 20:55:13', 10, '254.00', 1),
(35, 19, '2020-05-28 23:30:00', 85, '12.00', 1),
(36, 19, '2020-05-31 22:24:29', 10, '99.00', 1),
(38, 20, '2020-06-02 09:51:06', 10, '23.00', 1),
(39, 21, '2020-06-02 09:51:45', 10, '350.00', 1),
(40, 22, '2020-06-02 09:52:11', 10, '389.00', 1),
(41, 22, '2020-06-02 17:29:35', 10, '50.00', 1),
(42, 23, '2020-07-08 17:16:48', 12, '50.00', 1),
(43, 23, '2020-07-08 17:16:59', 50, '21.00', 1),
(44, 15, '2020-07-24 19:30:50', 9, '0.00', 1),
(45, 15, '2020-07-24 19:33:19', 10, '0.00', 1),
(46, 24, '2020-07-24 19:34:37', 10000, '123.00', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `nofactura` bigint(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario` int(11) DEFAULT NULL,
  `codcliente` int(11) DEFAULT NULL,
  `totalfactura` decimal(10,2) DEFAULT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `factura`
--

INSERT INTO `factura` (`nofactura`, `fecha`, `usuario`, `codcliente`, `totalfactura`, `estatus`) VALUES
(1, '2020-07-26 05:26:36', 1, 1, '408.54', 1),
(2, '2020-07-28 06:08:10', 1, 1, '140.69', 1),
(3, '2020-07-28 06:14:11', 1, 1, '256.18', 1),
(4, '2020-07-28 06:19:53', 1, 1, '280.99', 1),
(5, '2020-07-28 06:20:31', 1, 4, '29.27', 1),
(6, '2020-07-28 17:01:24', 1, 1, '105.37', 1),
(7, '2020-07-29 01:01:34', 1, 2, '184.69', 1),
(8, '2020-07-31 21:56:49', 1, 1, '105.37', 1),
(9, '2020-07-31 22:45:52', 1, 1, '1006.87', 1),
(10, '2020-07-31 22:47:45', 1, 1, '58.32', 1),
(11, '2020-07-31 22:49:49', 1, 1, '105.37', 1),
(12, '2020-07-31 22:51:56', 1, 2, '134.64', 1),
(13, '2020-07-31 22:53:24', 1, 2, '140.69', 1),
(14, '2020-07-31 23:05:00', 1, 2, '590.01', 1),
(15, '2020-07-31 23:09:39', 1, 2, '765.00', 1),
(16, '2020-07-31 23:15:55', 1, 1, '105.37', 1),
(17, '2020-07-31 23:32:58', 1, 6, '105.37', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `codproducto` int(11) NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `proveedor` int(11) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `existencia` int(11) DEFAULT NULL,
  `date_add` datetime DEFAULT current_timestamp(),
  `usuario_id` int(11) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1,
  `foto` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`codproducto`, `descripcion`, `proveedor`, `precio`, `existencia`, `date_add`, `usuario_id`, `estatus`, `foto`) VALUES
(15, 'logo', 14, '6.05', 16, '2020-05-15 14:07:28', 1, 1, 'sinimagen.jpg'),
(16, 'polvo esmeril23', 13, '83.00', 14, '2020-05-15 14:26:48', 1, 1, 'sinimagen.jpg'),
(18, 'Chambrana23', 12, '23.00', 0, '2020-05-15 20:00:26', 1, 1, 'sinimagen.jpg'),
(19, 'Logo', 12, '105.37', 103, '2020-05-28 19:10:29', 1, 1, 'sinimagen.jpg'),
(20, 'Nuevo Producto', 16, '23.00', 10, '2020-06-02 09:51:06', 1, 1, 'sinimagen.jpg'),
(21, 'Duel Lisa bco 6.10mtrs', 12, '350.00', 7, '2020-06-02 09:51:45', 1, 1, 'sinimagen.jpg'),
(22, 'Permaton Opalino', 14, '241.45', 20, '2020-06-02 09:52:11', 1, 1, 'sinimagen.jpg'),
(23, 'SILICON', 14, '29.27', 42, '2020-07-08 17:16:48', 1, 1, 'sinimagen.jpg'),
(24, 'Musica', 13, '123.00', 10000, '2020-07-24 19:34:37', 1, 1, 'sinimagen.jpg');

--
-- Disparadores `producto`
--
DELIMITER $$
CREATE TRIGGER `entradas_A_I` AFTER INSERT ON `producto` FOR EACH ROW BEGIN
INSERT INTO entradas(codproducto,cantidad,precio,usuario_id) VALUES(new.codproducto,new.existencia,new.precio,new.usuario_id);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `codproveedor` int(11) NOT NULL,
  `proveedor` varchar(100) DEFAULT NULL,
  `contacto` varchar(100) DEFAULT NULL,
  `telefono` bigint(11) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `date_add` datetime DEFAULT current_timestamp(),
  `usuario_id` int(11) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`codproveedor`, `proveedor`, `contacto`, `telefono`, `direccion`, `date_add`, `usuario_id`, `estatus`) VALUES
(12, 'COVICO', 'Claudia Rosales', 2213343234, 'Avenida torrecillas #1304', '2020-05-13 13:52:19', 1, 1),
(13, 'Aluvisa', 'Magda', 2212290360, 'calle tulipanes #1205', '2020-05-13 14:24:14', 1, 1),
(14, 'HPA', 'JUAN', 2212236272, 'calle rosales edifi de #123', '2020-05-13 14:40:45', 10, 1),
(15, 'Pensilvania', 'Covico', 22122321232, 'Toreecillas y 24 sur', '2020-05-14 22:27:58', 1, 1),
(16, 'Prov', '', 0, '', '2020-05-15 12:44:04', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `idrol` int(11) NOT NULL,
  `rol` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`idrol`, `rol`) VALUES
(1, 'Administrador'),
(2, 'Supervisor'),
(3, 'Vendedor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `idusuario` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `usuario` varchar(15) DEFAULT NULL,
  `clave` varchar(100) DEFAULT NULL,
  `rol` int(11) DEFAULT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idusuario`, `nombre`, `correo`, `usuario`, `clave`, `rol`, `estatus`) VALUES
(1, 'Alejandro Garcia Gallardo', 'siac1ventas@gmail.com', 'Administrador', '43e4e6a6f341e00671e123714de019a8', 1, 1),
(6, 'Willebaldo Gerardo Garcia Santiago', 'siac2ventas@gmail.com', 'Willy wonka1', '0004d0b59e19461ff126e3a08a814c33', 3, 1),
(7, 'Edith Aguirre', 'edith2610@gmail.com', 'VENDEDOR', '8dc5983b8c4ef1d8fcd5f325f9a65511', 3, 1),
(8, 'Juliana Gallardo', 'ju_ga@hotmail.com', 'July ', '81dc9bdb52d04dc20036dbd8313ed055', 1, 0),
(9, 'Marta Elena', 'marta@gmail.com', 'admin', '202cb962ac59075b964b07152d234b70', 2, 0),
(10, 'Eriberto Gomez', 'eri_ju@hotmail.com', 'ERI', '202cb962ac59075b964b07152d234b70', 2, 1),
(11, 'Juan Marquez', 'juan_marquez@gmail.com', 'Juan Ma', '149815eb972b3c370dee3b89d645ae14', 3, 0),
(12, 'David Hernandez', 'dave_hp@gmail.com', 'dave_123', '149815eb972b3c370dee3b89d645ae14', 2, 1),
(13, 'Guillermo Schiaffini', 'absalon@gmail.com', 'Chafini', 'a01610228fe998f515a72dd730294d87', 1, 0),
(14, 'Mauro Mendez', 'mau@hotmail.com', 'Mau2000', '1e48c4420b7073bc11916c6c1de226bb', 3, 1),
(15, 'Maria Esperanza', 'perita_ju@hotmail.com', 'Esperanzita', '1e48c4420b7073bc11916c6c1de226bb', 3, 1),
(16, 'Emiliana Gallardo', 'emi_lili@gmail.com', 'Emiliana ', 'a01610228fe998f515a72dd730294d87', 2, 1),
(17, 'Ezequiel Moreno', '1212_ju@gmail.com', 'Ezequiel', '934b535800b1cba8f96a5d72f72f1611', 1, 0),
(18, 'Juan Miguel', 'cadenas_ju@gmail.com', 'Caden Rosas', '149815eb972b3c370dee3b89d645ae14', 3, 1),
(19, 'Soni Hermeleginda', 'soni_123@hotmail.com', 'Sonia', '934b535800b1cba8f96a5d72f72f1611', 3, 1),
(20, 'Lalo lopez', 'lalo_99@gmail.com', 'Eduardo', 'b59c67bf196a4758191e42f76670ceba', 2, 1),
(21, 'Alan Garcia Nexticpaa', 'dsd@dga.com', 'Ramiro', 'b59c67bf196a4758191e42f76670ceba', 3, 1),
(22, 'Juan Manuel', 'manuelin_kilo@gmail.com', 'Manuel', '43e4e6a6f341e00671e123714de019a8', 1, 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`idcliente`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detallefactura`
--
ALTER TABLE `detallefactura`
  ADD PRIMARY KEY (`correlativo`),
  ADD KEY `codproducto` (`codproducto`),
  ADD KEY `nofactura` (`nofactura`);

--
-- Indices de la tabla `detalle_temp`
--
ALTER TABLE `detalle_temp`
  ADD PRIMARY KEY (`correlativo`),
  ADD KEY `nofactura` (`token_user`),
  ADD KEY `codproducto` (`codproducto`);

--
-- Indices de la tabla `entradas`
--
ALTER TABLE `entradas`
  ADD PRIMARY KEY (`correlativo`),
  ADD KEY `codproducto` (`codproducto`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`nofactura`),
  ADD KEY `usuario` (`usuario`),
  ADD KEY `codcliente` (`codcliente`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`codproducto`),
  ADD KEY `proveedor` (`proveedor`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`codproveedor`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`idrol`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idusuario`),
  ADD KEY `rol` (`rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `idcliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `detallefactura`
--
ALTER TABLE `detallefactura`
  MODIFY `correlativo` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `detalle_temp`
--
ALTER TABLE `detalle_temp`
  MODIFY `correlativo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT de la tabla `entradas`
--
ALTER TABLE `entradas`
  MODIFY `correlativo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de la tabla `factura`
--
ALTER TABLE `factura`
  MODIFY `nofactura` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `codproducto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `codproveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `idrol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`idusuario`);

--
-- Filtros para la tabla `detallefactura`
--
ALTER TABLE `detallefactura`
  ADD CONSTRAINT `detallefactura_ibfk_1` FOREIGN KEY (`nofactura`) REFERENCES `factura` (`nofactura`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detallefactura_ibfk_2` FOREIGN KEY (`codproducto`) REFERENCES `producto` (`codproducto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_temp`
--
ALTER TABLE `detalle_temp`
  ADD CONSTRAINT `detalle_temp_ibfk_2` FOREIGN KEY (`codproducto`) REFERENCES `producto` (`codproducto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `entradas`
--
ALTER TABLE `entradas`
  ADD CONSTRAINT `entradas_ibfk_1` FOREIGN KEY (`codproducto`) REFERENCES `producto` (`codproducto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `factura`
--
ALTER TABLE `factura`
  ADD CONSTRAINT `factura_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`idusuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `factura_ibfk_2` FOREIGN KEY (`codcliente`) REFERENCES `cliente` (`idcliente`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`proveedor`) REFERENCES `proveedor` (`codproveedor`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `producto_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`idusuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD CONSTRAINT `proveedor_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`idusuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`rol`) REFERENCES `rol` (`idrol`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
