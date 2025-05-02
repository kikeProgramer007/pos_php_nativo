-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-05-2025 a las 02:29:56
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `pos_php_nativo-main`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `arqueo_caja`
--

CREATE TABLE `arqueo_caja` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fecha_apertura` datetime DEFAULT NULL,
  `fecha_cierre` datetime DEFAULT NULL,
  `Bs200` int(11) NOT NULL DEFAULT 0,
  `Bs100` int(11) NOT NULL DEFAULT 0,
  `Bs50` int(11) NOT NULL DEFAULT 0,
  `Bs20` int(11) NOT NULL DEFAULT 0,
  `Bs10` int(11) NOT NULL DEFAULT 0,
  `Bs5` int(11) NOT NULL DEFAULT 0,
  `Bs2` int(11) NOT NULL DEFAULT 0,
  `Bs1` int(11) NOT NULL DEFAULT 0,
  `Bs050` int(11) NOT NULL DEFAULT 0,
  `Bs020` int(11) NOT NULL DEFAULT 0,
  `monto_ventas` decimal(11,2) NOT NULL DEFAULT 0.00,
  `monto_apertura` decimal(11,2) NOT NULL DEFAULT 0.00,
  `total_ingresos` decimal(11,2) NOT NULL DEFAULT 0.00,
  `gastos_operativos` decimal(11,2) NOT NULL DEFAULT 0.00,
  `monto_compras` decimal(11,2) NOT NULL DEFAULT 0.00,
  `total_egresos` decimal(11,2) NOT NULL DEFAULT 0.00,
  `resultado_neto` decimal(11,2) NOT NULL DEFAULT 0.00,
  `efectivo_en_caja` decimal(11,2) NOT NULL DEFAULT 0.00,
  `diferencia` decimal(11,2) NOT NULL DEFAULT 0.00,
  `estado` varchar(20) DEFAULT NULL,
  `nroTicket` int(11) NOT NULL,
  `tipo_cambio` decimal(11,2) DEFAULT NULL,
  `id_caja` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `arqueo_caja`
--

INSERT INTO `arqueo_caja` (`id`, `fecha_apertura`, `fecha_cierre`, `Bs200`, `Bs100`, `Bs50`, `Bs20`, `Bs10`, `Bs5`, `Bs2`, `Bs1`, `Bs050`, `Bs020`, `monto_ventas`, `monto_apertura`, `total_ingresos`, `gastos_operativos`, `monto_compras`, `total_egresos`, `resultado_neto`, `efectivo_en_caja`, `diferencia`, `estado`, `nroTicket`, `tipo_cambio`, `id_caja`, `id_usuario`) VALUES
(1, '2025-04-14 12:31:02', NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'abierta', 0, NULL, 1, 1),
(2, '2025-04-25 21:43:10', '2025-04-25 22:51:28', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.00, 20.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'cerrada', 0, NULL, 1, 1),
(3, '2025-04-28 17:48:54', '2025-04-28 17:49:50', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.00, 200.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'cerrada', 1, NULL, 1, 1),
(4, '2025-04-29 13:02:11', '2025-04-29 15:52:51', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.00, 400.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'cerrada', 10, NULL, 1, 1),
(5, '2025-04-29 16:03:06', '2025-04-29 17:08:33', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 112.00, 1.00, 113.00, 0.00, 10.00, 10.00, 103.00, 200.00, 97.00, 'cerrada', 4, NULL, 1, 1),
(6, '2025-04-29 23:23:28', NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 26.00, 2.00, 28.00, 0.00, 150.00, 150.00, -122.00, 0.00, 0.00, 'abierta', 2, NULL, 1, 1),
(7, '2025-04-30 00:03:30', NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 142.00, 200.00, 342.00, 0.00, 0.00, 0.00, 342.00, 0.00, 0.00, 'abierta', 7, NULL, 1, 1),
(8, '2025-05-01 14:40:03', '2025-05-01 20:27:59', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.00, 50.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'cerrada', 15, NULL, 1, 1),
(9, '2025-05-01 20:28:07', NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 13.00, 200.00, 213.00, 0.00, 0.00, 0.00, 213.00, 0.00, 0.00, 'abierta', 1, NULL, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cajas`
--

CREATE TABLE `cajas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(35) NOT NULL,
  `numero_caja` varchar(10) NOT NULL,
  `nro_ticket` int(10) UNSIGNED ZEROFILL NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `cajas`
--

INSERT INTO `cajas` (`id`, `nombre`, `numero_caja`, `nro_ticket`, `estado`) VALUES
(1, 'Caja de ventas', '1', 0000000001, 1),
(2, 'Caja Administrativa', '2', 0000000000, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `categoria` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `estado` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `categoria`, `fecha`, `estado`) VALUES
(1, 'broasterd', '2025-01-05 21:42:24', 1),
(2, 'brasa', '2025-01-05 21:42:30', 1),
(3, 'sodas', '2025-01-05 21:42:42', 1),
(4, 'jugos', '2025-01-05 21:42:46', 1),
(5, 'salchipapas', '2025-01-06 01:31:55', 1),
(6, ' guarniciones', '2025-04-26 02:16:04', 1),
(7, 'test', '2025-03-05 20:18:16', 0),
(8, 'sobras', '2025-04-26 02:16:14', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `estado` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `fecha`, `estado`) VALUES
(1, 's/n', '2025-03-08 21:40:34', 1),
(2, 'MARCOS GALARZA', '2025-04-28 21:49:25', 1),
(3, 'JASMIN GALARZA', '2025-04-29 18:08:39', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `id` int(11) NOT NULL,
  `codigo` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_proveedor` int(11) NOT NULL,
  `fecha_alta` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `estado` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `compras`
--

INSERT INTO `compras` (`id`, `codigo`, `total`, `id_usuario`, `id_proveedor`, `fecha_alta`, `estado`) VALUES
(1, 10001, 1.40, 1, 1, '2025-01-06 02:00:54', 1),
(2, 10002, 1870.00, 3, 2, '2025-01-07 02:39:59', 1),
(3, 10003, 4855.00, 1, 1, '2025-01-08 02:08:22', 1),
(4, 10004, 7070.00, 1, 1, '2025-01-08 02:09:18', 1),
(5, 10005, 1000.00, 1, 1, '2025-04-26 02:30:03', 1),
(6, 10006, 5.40, 1, 1, '2025-04-29 19:52:25', 1),
(7, 10007, 10.00, 1, 2, '2025-04-29 20:03:22', 1),
(8, 10008, 150.00, 1, 1, '2025-04-30 03:24:33', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_compra`
--

CREATE TABLE `detalle_compra` (
  `id` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_compra` int(11) NOT NULL,
  `producto` varchar(200) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_compra` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_compra`
--

INSERT INTO `detalle_compra` (`id`, `id_producto`, `id_compra`, `producto`, `cantidad`, `precio_compra`, `subtotal`) VALUES
(1, 36, 1, 'fanta naranja mini', 1, 1.00, 1.40),
(2, 18, 2, 'coca cola 3l', 50, 15.00, 750.00),
(3, 19, 2, 'coca cola 2l', 50, 11.00, 550.00),
(4, 20, 2, 'coca cola 600ml', 50, 3.00, 150.00),
(5, 21, 2, 'retornable coca cola 2.5l', 50, 7.00, 350.00),
(6, 37, 2, 'coca cola mini 190ml', 50, 1.00, 70.00),
(7, 66, 3, 'pura vida de 3l ', 50, 15.00, 750.00),
(8, 65, 3, 'tropi 600ml', 50, 3.00, 150.00),
(9, 64, 3, 'chicha 2l', 50, 9.00, 450.00),
(10, 63, 3, 'aquarios pomelo 2l', 50, 11.00, 550.00),
(11, 62, 3, 'aquarios pomelo 3l', 50, 15.00, 750.00),
(12, 61, 3, 'aquarios pera 2l', 50, 11.00, 550.00),
(13, 60, 3, 'aquarios pera 3l', 50, 15.00, 750.00),
(14, 59, 3, 'valle 3l  naranja', 50, 14.00, 725.00),
(15, 58, 3, 'pop uva 620ml', 50, 1.00, 90.00),
(16, 43, 3, 'pop guarana 620ml', 50, 1.00, 90.00),
(17, 42, 4, 'pop manzana 620ml', 50, 1.00, 90.00),
(18, 41, 4, 'pop papaya 620ml', 50, 1.00, 90.00),
(19, 40, 4, 'pop naranja 620ml', 50, 1.00, 90.00),
(20, 39, 4, 'pop piña  620ml', 50, 1.00, 90.00),
(21, 38, 4, 'pop uva 620ml', 50, 1.00, 90.00),
(22, 36, 4, 'fanta naranja mini', 50, 1.00, 70.00),
(23, 35, 4, 'mendocina papaya 1l', 50, 5.00, 250.00),
(24, 34, 4, 'pepsi 1l', 50, 3.00, 150.00),
(25, 33, 4, 'pepsi 2l', 50, 9.00, 450.00),
(26, 23, 4, 'fanta naranja 2l', 50, 11.00, 550.00),
(27, 24, 4, 'fanta naranja 600ml', 50, 3.00, 150.00),
(28, 25, 4, 'fanta papaya 3l', 50, 15.00, 750.00),
(29, 26, 4, 'fanta papaya 2l', 50, 11.00, 550.00),
(30, 27, 4, 'fanta guarana 3l', 50, 15.00, 750.00),
(31, 28, 4, 'fanta guarana 2l', 50, 11.00, 550.00),
(32, 29, 4, 'sprite 600ml', 50, 3.00, 150.00),
(33, 30, 4, 'simba manzana 2l', 50, 10.00, 500.00),
(34, 31, 4, 'simba piña 2l', 50, 10.00, 500.00),
(35, 32, 4, 'simba durazno 2l', 50, 10.00, 500.00),
(36, 22, 4, 'fanta naranja 3l', 50, 15.00, 750.00),
(37, 71, 5, 'valle 1l manzana', 50, 10.00, 500.00),
(38, 70, 5, 'valle 1l durazno', 50, 10.00, 500.00),
(39, 39, 6, 'pop piña  620ml', 3, 1.00, 5.40),
(40, 71, 7, 'valle 1l manzana', 1, 10.00, 10.00),
(41, 65, 8, 'tropi 600ml', 50, 3.00, 150.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_venta`
--

CREATE TABLE `detalle_venta` (
  `id` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL,
  `producto` varchar(200) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL,
  `precio_compra` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `preferencias` varchar(200) DEFAULT NULL,
  `nota_adicional` text DEFAULT NULL,
  `forma_atencion` char(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_venta`
--

INSERT INTO `detalle_venta` (`id`, `id_producto`, `id_venta`, `producto`, `cantidad`, `precio_venta`, `precio_compra`, `subtotal`, `preferencias`, `nota_adicional`, `forma_atencion`) VALUES
(1, 5, 1, 'eco pecho broasterd', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(2, 41, 1, 'pop papaya 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(3, 14, 2, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(4, 5, 3, 'eco pecho broasterd', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(5, 41, 3, 'pop papaya 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(6, 14, 4, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(7, 14, 5, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(8, 14, 6, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(9, 14, 7, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(10, 14, 8, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(11, 14, 9, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(12, 14, 10, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(13, 14, 11, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(14, 6, 12, 'eco ala broasterd', 1, 13.00, 10.00, 13.00, NULL, NULL, 'LL'),
(15, 5, 13, 'eco pecho broasterd', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(16, 15, 14, 'eco ala brasa', 1, 13.00, 10.00, 13.00, 'Sin arroz ❌', NULL, 'M'),
(17, 14, 15, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(18, 14, 16, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(19, 14, 17, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(20, 69, 18, 'salchipapa simple', 1, 12.00, 7.00, 12.00, NULL, NULL, 'M'),
(21, 16, 19, 'eco pierna brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(22, 65, 19, 'tropi 600ml', 1, 6.00, 3.00, 6.00, NULL, NULL, 'M'),
(23, 63, 19, 'aquarios pomelo 2l', 1, 13.00, 11.00, 13.00, NULL, NULL, 'M'),
(24, 70, 19, 'valle 1l durazno', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(25, 60, 19, 'aquarios pera 3l', 1, 17.00, 15.00, 17.00, NULL, NULL, 'M'),
(26, 15, 19, 'eco ala brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(27, 14, 20, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(28, 14, 21, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(29, 14, 22, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(30, 14, 23, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(31, 10, 24, 'entero brasa', 1, 90.00, 70.00, 90.00, NULL, NULL, 'M'),
(32, 14, 25, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(33, 14, 26, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(34, 15, 27, 'eco ala brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(35, 14, 28, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(36, 69, 29, 'salchipapa simple', 1, 12.00, 7.00, 12.00, NULL, NULL, 'M'),
(37, 14, 30, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(38, 14, 31, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(39, 14, 32, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(40, 14, 33, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(41, 14, 34, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(42, 14, 35, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(43, 14, 36, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `meseros`
--

CREATE TABLE `meseros` (
  `id` int(11) NOT NULL,
  `nombre` text NOT NULL,
  `documento` varchar(11) NOT NULL,
  `telefono` text NOT NULL,
  `direccion` text NOT NULL,
  `compras` int(11) NOT NULL,
  `ultima_compra` datetime NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `estado` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `meseros`
--

INSERT INTO `meseros` (`id`, `nombre`, `documento`, `telefono`, `direccion`, `compras`, `ultima_compra`, `fecha`, `estado`) VALUES
(1, 's/n', 'sin carnet', '000-00-000', 'sin direccion', 43, '2025-05-01 20:28:14', '2025-05-02 00:28:14', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `codigo` text NOT NULL,
  `descripcion` text NOT NULL,
  `imagen` text NOT NULL,
  `stock` int(11) NOT NULL,
  `precio_venta` float NOT NULL,
  `precio_compra` float NOT NULL,
  `ventas` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `inventariable` tinyint(4) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `id_categoria`, `codigo`, `descripcion`, `imagen`, `stock`, `precio_venta`, `precio_compra`, `ventas`, `fecha`, `inventariable`, `estado`) VALUES
(1, 1, '101', 'entero broasterd', 'vistas/img/productos/101/814.jpg', 50, 90, 70, 0, '2025-04-26 02:18:36', 0, 1),
(2, 1, '102', '1/2 broasterd', 'vistas/img/productos/102/393.jpg', 50, 45, 30, 0, '2025-04-26 02:18:58', 0, 1),
(3, 1, '103', '1/4 pecho broasterd', 'vistas/img/productos/103/450.png', 50, 23, 18, 0, '2025-04-26 02:19:25', 0, 1),
(4, 1, '108', '1/4 pierna broasterd', 'vistas/img/productos/108/291.png', 50, 23, 18, 0, '2025-04-26 02:19:47', 0, 1),
(5, 1, '104', 'eco pecho broasterd', 'vistas/img/productos/104/248.png', 47, 13, 10, 3, '2025-04-29 18:07:33', 0, 1),
(6, 1, '106', 'eco ala broasterd', 'vistas/img/productos/106/421.png', 49, 13, 10, 1, '2025-04-29 18:03:41', 0, 1),
(7, 1, '105', ' eco pierna broasterd', 'vistas/img/productos/105/376.png', 50, 13, 10, 0, '2025-04-26 02:21:02', 0, 1),
(8, 1, '107', 'eco contra broasterd', 'vistas/img/productos/107/825.png', 50, 13, 10, 0, '2025-04-26 02:21:33', 0, 1),
(10, 2, '201', 'entero brasa', 'vistas/img/productos/201/518.png', 49, 90, 70, 1, '2025-04-30 04:26:11', 0, 1),
(11, 2, '202', '1/2 brasa', 'vistas/img/productos/202/592.png', 50, 45, 30, 0, '2025-04-26 02:22:01', 0, 1),
(12, 2, '203', '1/4  pecho brasa', 'vistas/img/productos/203/343.png', 50, 23, 18, 0, '2025-04-26 02:22:19', 0, 1),
(13, 2, '204', '1/4 pierna brasa', 'vistas/img/productos/204/736.png', 50, 23, 18, 0, '2025-04-26 02:22:40', 0, 1),
(14, 2, '205', 'eco pecho brasa', 'vistas/img/productos/205/661.png', 24, 13, 10, 26, '2025-05-02 00:28:14', 0, 1),
(15, 2, '206', 'eco ala brasa', 'vistas/img/productos/206/334.png', 47, 13, 10, 3, '2025-04-30 14:58:13', 0, 1),
(16, 2, '207', 'eco pierna brasa', 'vistas/img/productos/207/221.png', 49, 13, 10, 1, '2025-04-29 21:06:14', 0, 1),
(17, 2, '208', 'eco contra brasa', 'vistas/img/productos/208/169.png', 50, 13, 10, 0, '2025-04-26 02:23:03', 0, 1),
(18, 3, '301', 'coca cola 3l', 'vistas/img/productos/301/316.jpg', 50, 17, 15, 0, '2025-01-07 02:39:59', 1, 1),
(19, 3, '302', 'coca cola 2l', 'vistas/img/productos/302/532.jpg', 50, 13, 11, 0, '2025-03-08 21:36:26', 1, 1),
(20, 3, '303', 'coca cola 600ml', 'vistas/img/productos/303/492.jpg', 50, 6, 3, 0, '2025-01-07 02:39:59', 1, 1),
(21, 3, '304', 'retornable coca cola 2.5l', 'vistas/img/productos/304/739.jpg', 50, 12, 7, 0, '2025-01-07 02:39:59', 1, 1),
(22, 3, '305', 'fanta naranja 3l', 'vistas/img/productos/305/781.jpg', 50, 17, 15, 0, '2025-01-08 02:09:18', 1, 1),
(23, 3, '306', 'fanta naranja 2l', 'vistas/img/productos/306/696.jpg', 50, 13, 11, 0, '2025-01-08 02:09:18', 1, 1),
(24, 3, '307', 'fanta naranja 600ml', 'vistas/img/productos/307/182.jpg', 50, 6, 3, 0, '2025-01-08 02:09:18', 1, 1),
(25, 3, '308', 'fanta papaya 3l', 'vistas/img/productos/308/584.jpg', 50, 17, 15, 0, '2025-01-08 02:09:18', 1, 1),
(26, 3, '309', 'fanta papaya 2l', 'vistas/img/productos/309/560.jpg', 50, 13, 11, 0, '2025-01-08 02:09:18', 1, 1),
(27, 3, '310', 'fanta guarana 3l', 'vistas/img/productos/310/152.jpg', 50, 17, 15, 0, '2025-01-08 02:09:18', 1, 1),
(28, 3, '311', 'fanta guarana 2l', 'vistas/img/productos/311/908.jpg', 50, 13, 11, 0, '2025-01-08 02:09:18', 1, 1),
(29, 3, '312', 'sprite 600ml', 'vistas/img/productos/312/281.jpg', 50, 6, 3, 0, '2025-01-08 02:09:18', 1, 1),
(30, 3, '313', 'simba manzana 2l', 'vistas/img/productos/313/940.jpg', 50, 12, 10, 0, '2025-01-08 02:09:18', 1, 1),
(31, 3, '314', 'simba piña 2l', 'vistas/img/productos/314/740.png', 50, 12, 10, 0, '2025-01-08 02:09:18', 1, 1),
(32, 3, '315', 'simba durazno 2l', 'vistas/img/productos/315/954.jpg', 50, 12, 10, 0, '2025-01-08 02:09:18', 1, 1),
(33, 3, '316', 'pepsi 2l', 'vistas/img/productos/316/938.jpg', 50, 10, 9, 0, '2025-04-26 02:30:31', 1, 0),
(34, 3, '317', 'pepsi 1l', 'vistas/img/productos/317/343.png', 50, 7, 3, 0, '2025-04-26 02:30:46', 1, 1),
(35, 3, '318', 'mendocina papaya 1l', 'vistas/img/productos/318/504.png', 50, 7, 5, 0, '2025-04-26 02:33:02', 1, 1),
(36, 3, '326', 'fanta naranja mini', 'vistas/img/productos/326/408.jpg', 51, 2, 1.4, 0, '2025-03-08 21:36:53', 1, 1),
(37, 3, '325', 'coca cola mini 190ml', 'vistas/img/productos/325/287.jpg', 50, 2, 1.4, 0, '2025-03-08 21:36:26', 1, 1),
(38, 3, '324', 'pop uva 620ml', 'vistas/img/productos/324/957.jpg', 50, 3, 1.8, 0, '2025-01-08 02:09:18', 1, 1),
(39, 3, '319', 'pop piña  620ml', 'vistas/img/productos/319/217.jpg', 53, 3, 1.8, 0, '2025-04-29 19:52:25', 1, 1),
(40, 3, '320', 'pop naranja 620ml', 'vistas/img/productos/320/896.jpg', 50, 3, 1.8, 0, '2025-01-08 02:09:18', 1, 1),
(41, 3, '321', 'pop papaya 620ml', 'vistas/img/productos/321/665.jpg', 48, 3, 1.8, 2, '2025-04-29 17:04:03', 1, 1),
(42, 3, '322', 'pop manzana 620ml', 'vistas/img/productos/322/283.jpg', 50, 3, 1.8, 0, '2025-01-08 02:09:18', 1, 1),
(43, 3, '323', 'pop guarana 620ml', 'vistas/img/productos/323/386.jpg', 50, 3, 1.8, 0, '2025-01-08 02:08:22', 1, 1),
(58, 3, '324', 'pop uva 620ml', 'vistas/img/productos/324/957.jpg', 50, 3, 1.8, 0, '2025-01-08 02:08:22', 1, 1),
(59, 4, '401', 'valle 3l  naranja', 'vistas/img/productos/401/147.jpg', 50, 17, 14.5, 0, '2025-01-08 02:08:22', 1, 1),
(60, 4, '402', 'aquarios pera 3l', 'vistas/img/productos/402/399.jpg', 49, 17, 15, 1, '2025-04-29 21:06:14', 1, 1),
(61, 4, '403', 'aquarios pera 2l', 'vistas/img/productos/403/956.jpg', 50, 13, 11, 0, '2025-03-08 21:36:26', 1, 1),
(62, 4, '404', 'aquarios pomelo 3l', 'vistas/img/productos/404/930.jpg', 50, 17, 15, 0, '2025-03-08 21:36:26', 1, 1),
(63, 4, '405', 'aquarios pomelo 2l', 'vistas/img/productos/405/715.png', 49, 13, 11, 1, '2025-04-29 21:06:14', 1, 1),
(64, 4, '406', 'chicha 2l', 'vistas/img/productos/406/636.png', 50, 12, 9, 0, '2025-04-26 02:24:19', 1, 0),
(65, 4, '407', 'tropi 600ml', 'vistas/img/productos/407/356.jpg', 99, 6, 3, 1, '2025-04-30 03:24:33', 1, 1),
(66, 4, '408', 'valle 1l durazno', 'vistas/img/productos/408/819.png', 0, 12, 10, 0, '2025-04-26 02:28:54', 1, 0),
(69, 5, '501', 'salchipapa simple', 'vistas/img/productos/501/406.png', 48, 12, 7, 2, '2025-05-02 00:09:43', 0, 1),
(70, 4, '408', 'valle 1l durazno', 'vistas/img/productos/408/819.png', 49, 12, 10, 1, '2025-04-29 21:06:14', 1, 1),
(71, 4, '409', 'valle 1l manzana', 'vistas/img/productos/409/514.jpg', 51, 12, 10, 0, '2025-04-29 20:03:22', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `empresa` varchar(100) NOT NULL,
  `telefono` varchar(12) NOT NULL,
  `direccion` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `estado` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`id`, `nombre`, `empresa`, `telefono`, `direccion`, `fecha`, `estado`) VALUES
(1, 's/n', 's/n', '00000000', 'sin direccion', '2025-04-26 02:55:00', 1),
(2, 'victor hino', 'coca cola', '67709910', 'sin direccion', '2025-01-06 01:59:47', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` text NOT NULL,
  `usuario` text NOT NULL,
  `password` text NOT NULL,
  `perfil` text NOT NULL,
  `foto` text NOT NULL,
  `estado` int(11) NOT NULL,
  `ultimo_login` datetime NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `activo` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `usuario`, `password`, `perfil`, `foto`, `estado`, `ultimo_login`, `fecha`, `activo`) VALUES
(1, 'rosmery quizpe', 'admin', '$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG', 'Administrador', 'vistas/img/usuarios/admin/202.png', 1, '2025-05-01 20:11:16', '2025-05-02 00:11:16', 1),
(2, 'luis hidalgo', 'luis10', '$2a$07$asxx54ahjppf45sd87a5auGZEtGHuyZwm.Ur.FJvWLCql3nmsMbXy', 'Vendedor', '', 1, '2025-04-29 16:31:48', '2025-04-29 20:31:48', 1),
(3, 'edwin yamil', 'edwin', '$2a$07$asxx54ahjppf45sd87a5auGZEtGHuyZwm.Ur.FJvWLCql3nmsMbXy', 'Especial', '', 1, '2025-04-25 22:46:53', '2025-04-26 02:46:53', 1),
(4, 'enrique', 'enrique', '$2a$07$asxx54ahjppf45sd87a5auLSGQafrhT1q/TDAijaGwwOMXE8ecr.q', 'Administrador', '', 1, '2025-03-07 12:07:11', '2025-03-07 16:07:11', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `codigo` int(11) NOT NULL,
  `nro_ticket` int(8) UNSIGNED ZEROFILL DEFAULT NULL,
  `total` float NOT NULL,
  `total_pagado` float DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `nota` varchar(300) DEFAULT NULL,
  `tipo_pago` varchar(100) DEFAULT NULL,
  `cambio` float DEFAULT NULL,
  `forma_atencion` varchar(200) DEFAULT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `id_mesero` int(11) DEFAULT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `id_vendedor` int(11) DEFAULT NULL,
  `id_arqueo_caja` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `codigo`, `nro_ticket`, `total`, `total_pagado`, `fecha`, `nota`, `tipo_pago`, `cambio`, `forma_atencion`, `estado`, `id_mesero`, `id_cliente`, `id_vendedor`, `id_arqueo_caja`) VALUES
(1, 1, NULL, 16, 20, '2025-04-28 21:49:25', '', 'Efectivo', 4, 'En Mesa', 1, 1, 2, 1, 3),
(2, 1, NULL, 13, 20, '2025-04-29 17:02:23', 'BIEN', 'Efectivo', 7, 'En Mesa', 1, 1, 1, 1, 4),
(3, 2, NULL, 16, 20, '2025-04-29 17:04:03', 'RAPIDO', 'Efectivo', 4, 'En Mesa', 1, 1, 1, 1, 4),
(4, 3, NULL, 13, 122, '2025-04-29 17:11:33', '3ED2RF2E4RF', 'Efectivo', 109, 'En Mesa', 1, 1, 1, 1, 4),
(5, 0, NULL, 13, 122, '2025-04-29 17:26:20', '', 'Efectivo', 109, 'En Mesa', 1, 1, 1, 1, 4),
(6, 1, NULL, 13, 20, '2025-04-29 17:27:23', '', 'Efectivo', 7, 'En Mesa', 1, 1, 1, 1, 4),
(7, 2, NULL, 13, 50, '2025-04-29 17:50:44', '', 'Efectivo', 37, 'En Mesa', 1, 1, 1, 1, 4),
(8, 3, NULL, 13, 13, '2025-04-29 17:57:55', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 4),
(9, 4, NULL, 13, 20, '2025-04-29 18:00:15', '', 'Efectivo', 7, 'En Mesa', 1, 1, 1, 1, 4),
(10, 5, NULL, 13, 13, '2025-04-29 18:00:35', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 4),
(11, 6, NULL, 13, 13, '2025-04-29 18:00:56', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 4),
(12, 7, NULL, 13, 100, '2025-04-29 18:03:41', '', 'Efectivo', 87, 'Para Llevar', 1, 1, 1, 1, 4),
(13, 8, NULL, 13, 50, '2025-04-29 18:07:33', '', 'Efectivo', 37, 'En Mesa', 1, 1, 1, 1, 4),
(14, 9, NULL, 13, 50, '2025-04-29 18:08:39', '', 'Efectivo', 37, 'En Mesa', 1, 1, 3, 1, 4),
(15, 10, NULL, 13, 13, '2025-04-29 18:20:20', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 4),
(16, 1, NULL, 13, 20, '2025-04-29 20:58:05', '', 'Efectivo', 7, 'En Mesa', 1, 1, 1, 1, 5),
(17, 2, NULL, 13, 50, '2025-04-29 21:04:43', '', 'Efectivo', 37, 'En Mesa', 1, 1, 1, 1, 5),
(18, 3, NULL, 12, 12, '2025-04-29 21:05:46', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 5),
(19, 4, NULL, 74, 100, '2025-04-29 21:06:14', '', 'Efectivo', 26, 'En Mesa', 1, 1, 1, 1, 5),
(20, 1, NULL, 13, 50, '2025-04-30 03:40:35', '', 'Efectivo', 37, 'En Mesa', 1, 1, 1, 1, 6),
(21, 2, NULL, 13, 50, '2025-04-30 03:41:22', '', 'Efectivo', 37, 'En Mesa', 1, 1, 1, 1, 6),
(22, 3, NULL, 13, 20, '2025-04-30 04:01:15', '', 'Efectivo', 7, 'En Mesa', 1, 1, 1, 1, 6),
(23, 3, NULL, 13, 15, '2025-04-30 04:25:55', '', 'Efectivo', 2, 'En Mesa', 1, 1, 1, 1, 7),
(24, 4, NULL, 90, 90, '2025-04-30 04:26:11', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 7),
(25, 5, NULL, 13, 23, '2025-04-30 14:57:20', '', 'Efectivo', 10, 'En Mesa', 1, 1, 1, 1, 7),
(26, 6, NULL, 13, 123, '2025-04-30 14:58:06', '', 'Efectivo', 110, 'En Mesa', 1, 1, 1, 1, 7),
(27, 7, NULL, 13, 331, '2025-04-30 14:58:13', '', 'Efectivo', 318, 'En Mesa', 1, 1, 1, 1, 7),
(28, 8, NULL, 13, 20, '2025-05-01 18:40:10', '', 'Efectivo', 7, 'En Mesa', 1, 1, 1, 1, 8),
(29, 9, NULL, 12, 12, '2025-05-02 00:09:43', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 8),
(30, 10, NULL, 13, 24, '2025-05-02 00:11:27', '', 'Efectivo', 11, 'En Mesa', 1, 1, 1, 1, 8),
(31, 11, NULL, 13, 50, '2025-05-02 00:12:02', '', 'Efectivo', 37, 'En Mesa', 1, 1, 1, 1, 8),
(32, 12, NULL, 13, 20, '2025-05-02 00:14:45', '', 'Efectivo', 7, 'En Mesa', 1, 1, 1, 1, 8),
(33, 13, NULL, 13, 50, '2025-05-02 00:15:42', '', 'Efectivo', 37, 'En Mesa', 1, 1, 1, 1, 8),
(34, 14, NULL, 13, 20, '2025-05-02 00:19:16', '', 'Efectivo', 7, 'En Mesa', 1, 1, 1, 1, 8),
(35, 15, NULL, 13, 50, '2025-05-02 00:22:34', '', 'Efectivo', 37, 'En Mesa', 1, 1, 1, 1, 8),
(36, 1, NULL, 13, 50, '2025-05-02 00:28:14', '', 'Efectivo', 37, 'En Mesa', 1, 1, 1, 1, 9);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `arqueo_caja`
--
ALTER TABLE `arqueo_caja`
  ADD PRIMARY KEY (`id`),
  ADD KEY `arqueo_caja_id_usuario_foreign` (`id_usuario`),
  ADD KEY `fk_arqueo_caja` (`id_caja`);

--
-- Indices de la tabla `cajas`
--
ALTER TABLE `cajas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_compra_usuario` (`id_usuario`),
  ADD KEY `fk_compra_proveedor` (`id_proveedor`);

--
-- Indices de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_detalle_compra_producto` (`id_producto`),
  ADD KEY `fk_detalle_compra_compra` (`id_compra`);

--
-- Indices de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_producto` (`id_producto`),
  ADD KEY `fk_venta` (`id_venta`);

--
-- Indices de la tabla `meseros`
--
ALTER TABLE `meseros`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_mesero` (`id_mesero`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_vendedor` (`id_vendedor`),
  ADD KEY `id_arqueo_caja` (`id_arqueo_caja`) USING BTREE;

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `arqueo_caja`
--
ALTER TABLE `arqueo_caja`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `cajas`
--
ALTER TABLE `cajas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de la tabla `meseros`
--
ALTER TABLE `meseros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `arqueo_caja`
--
ALTER TABLE `arqueo_caja`
  ADD CONSTRAINT `arqueo_caja_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `fk_arqueo_caja` FOREIGN KEY (`id_caja`) REFERENCES `cajas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `fk_compra_proveedor` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id`),
  ADD CONSTRAINT `fk_compra_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  ADD CONSTRAINT `fk_detalle_compra_compra` FOREIGN KEY (`id_compra`) REFERENCES `compras` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_detalle_compra_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD CONSTRAINT `fk_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_venta` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `fk_ventas_arqueo_caja` FOREIGN KEY (`id_arqueo_caja`) REFERENCES `arqueo_caja` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_ventas_clientes` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ventas_meseros` FOREIGN KEY (`id_mesero`) REFERENCES `meseros` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ventas_usuarios` FOREIGN KEY (`id_vendedor`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
