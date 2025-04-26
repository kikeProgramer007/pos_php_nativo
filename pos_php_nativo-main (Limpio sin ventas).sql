-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-04-2025 a las 04:57:13
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
  `nroTicket` int(8) UNSIGNED ZEROFILL NOT NULL DEFAULT 00000000,
  `tipo_cambio` decimal(11,2) DEFAULT NULL,
  `id_caja` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `arqueo_caja`
--

INSERT INTO `arqueo_caja` (`id`, `fecha_apertura`, `fecha_cierre`, `Bs200`, `Bs100`, `Bs50`, `Bs20`, `Bs10`, `Bs5`, `Bs2`, `Bs1`, `Bs050`, `Bs020`, `monto_ventas`, `monto_apertura`, `total_ingresos`, `gastos_operativos`, `monto_compras`, `total_egresos`, `resultado_neto`, `efectivo_en_caja`, `diferencia`, `estado`, `nroTicket`, `tipo_cambio`, `id_caja`, `id_usuario`) VALUES
(1, '2025-04-14 12:31:02', NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'abierta', 00000000, NULL, 1, 1),
(2, '2025-04-25 21:43:10', '2025-04-25 22:51:28', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.00, 20.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'cerrada', 00000000, NULL, 1, 1);

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
(1, 'Caja de ventas', '1', 0000000000, 1),
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
(1, 's/n', '2025-03-08 21:40:34', 1);

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
(5, 10005, 1000.00, 1, 1, '2025-04-26 02:30:03', 1);

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
(38, 70, 5, 'valle 1l durazno', 50, 10.00, 500.00);

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
(1, 's/n', 'sin carnet', '000-00-000', 'sin direccion', 0, '0000-00-00 00:00:00', '2025-04-26 02:54:27', 1);

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
(5, 1, '104', 'eco pecho broasterd', 'vistas/img/productos/104/248.png', 50, 13, 10, 0, '2025-04-26 02:20:50', 0, 1),
(6, 1, '106', 'eco ala broasterd', 'vistas/img/productos/106/421.png', 50, 13, 10, 0, '2025-04-26 02:21:16', 0, 1),
(7, 1, '105', ' eco pierna broasterd', 'vistas/img/productos/105/376.png', 50, 13, 10, 0, '2025-04-26 02:21:02', 0, 1),
(8, 1, '107', 'eco contra broasterd', 'vistas/img/productos/107/825.png', 50, 13, 10, 0, '2025-04-26 02:21:33', 0, 1),
(10, 2, '201', 'entero brasa', 'vistas/img/productos/201/518.png', 50, 90, 70, 0, '2025-04-26 02:21:48', 0, 1),
(11, 2, '202', '1/2 brasa', 'vistas/img/productos/202/592.png', 50, 45, 30, 0, '2025-04-26 02:22:01', 0, 1),
(12, 2, '203', '1/4  pecho brasa', 'vistas/img/productos/203/343.png', 50, 23, 18, 0, '2025-04-26 02:22:19', 0, 1),
(13, 2, '204', '1/4 pierna brasa', 'vistas/img/productos/204/736.png', 50, 23, 18, 0, '2025-04-26 02:22:40', 0, 1),
(14, 2, '205', 'eco pecho brasa', 'vistas/img/productos/205/661.png', 50, 13, 10, 0, '2025-04-26 02:23:29', 0, 1),
(15, 2, '206', 'eco ala brasa', 'vistas/img/productos/206/334.png', 50, 13, 10, 0, '2025-04-26 02:23:22', 0, 1),
(16, 2, '207', 'eco pierna brasa', 'vistas/img/productos/207/221.png', 50, 13, 10, 0, '2025-04-26 02:23:12', 0, 1),
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
(39, 3, '319', 'pop piña  620ml', 'vistas/img/productos/319/217.jpg', 50, 3, 1.8, 0, '2025-03-08 21:36:26', 1, 1),
(40, 3, '320', 'pop naranja 620ml', 'vistas/img/productos/320/896.jpg', 50, 3, 1.8, 0, '2025-01-08 02:09:18', 1, 1),
(41, 3, '321', 'pop papaya 620ml', 'vistas/img/productos/321/665.jpg', 50, 3, 1.8, 0, '2025-03-08 21:36:26', 1, 1),
(42, 3, '322', 'pop manzana 620ml', 'vistas/img/productos/322/283.jpg', 50, 3, 1.8, 0, '2025-01-08 02:09:18', 1, 1),
(43, 3, '323', 'pop guarana 620ml', 'vistas/img/productos/323/386.jpg', 50, 3, 1.8, 0, '2025-01-08 02:08:22', 1, 1),
(58, 3, '324', 'pop uva 620ml', 'vistas/img/productos/324/957.jpg', 50, 3, 1.8, 0, '2025-01-08 02:08:22', 1, 1),
(59, 4, '401', 'valle 3l  naranja', 'vistas/img/productos/401/147.jpg', 50, 17, 14.5, 0, '2025-01-08 02:08:22', 1, 1),
(60, 4, '402', 'aquarios pera 3l', 'vistas/img/productos/402/399.jpg', 50, 17, 15, 0, '2025-03-08 21:36:26', 1, 1),
(61, 4, '403', 'aquarios pera 2l', 'vistas/img/productos/403/956.jpg', 50, 13, 11, 0, '2025-03-08 21:36:26', 1, 1),
(62, 4, '404', 'aquarios pomelo 3l', 'vistas/img/productos/404/930.jpg', 50, 17, 15, 0, '2025-03-08 21:36:26', 1, 1),
(63, 4, '405', 'aquarios pomelo 2l', 'vistas/img/productos/405/715.png', 50, 13, 11, 0, '2025-03-08 21:36:26', 1, 1),
(64, 4, '406', 'chicha 2l', 'vistas/img/productos/406/636.png', 50, 12, 9, 0, '2025-04-26 02:24:19', 1, 0),
(65, 4, '407', 'tropi 600ml', 'vistas/img/productos/407/356.jpg', 50, 6, 3, 0, '2025-03-08 21:36:26', 1, 1),
(66, 4, '408', 'valle 1l durazno', 'vistas/img/productos/408/819.png', 0, 12, 10, 0, '2025-04-26 02:28:54', 1, 0),
(69, 5, '501', 'salchipapa simple', 'vistas/img/productos/501/406.png', 50, 12, 7, 0, '2025-04-26 02:24:47', 0, 1),
(70, 4, '408', 'valle 1l durazno', 'vistas/img/productos/408/819.png', 50, 12, 10, 0, '2025-04-26 02:30:03', 1, 1),
(71, 4, '409', 'valle 1l manzana', 'vistas/img/productos/409/514.jpg', 50, 12, 10, 0, '2025-04-26 02:30:03', 1, 1);

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
(1, 'rosmery quizpe', 'admin', '$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG', 'Administrador', 'vistas/img/usuarios/admin/202.png', 1, '2025-04-25 22:50:51', '2025-04-26 02:50:51', 1),
(2, 'luis hidalgo', 'luis10', '$2a$07$asxx54ahjppf45sd87a5auGZEtGHuyZwm.Ur.FJvWLCql3nmsMbXy', 'Vendedor', '', 1, '2025-04-25 22:50:43', '2025-04-26 02:50:43', 1),
(3, 'edwin yamil', 'edwin', '$2a$07$asxx54ahjppf45sd87a5auGZEtGHuyZwm.Ur.FJvWLCql3nmsMbXy', 'Especial', '', 1, '2025-04-25 22:46:53', '2025-04-26 02:46:53', 1),
(4, 'enrique', 'enrique', '$2a$07$asxx54ahjppf45sd87a5auLSGQafrhT1q/TDAijaGwwOMXE8ecr.q', 'Administrador', '', 1, '2025-03-07 12:07:11', '2025-03-07 16:07:11', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `codigo` int(8) UNSIGNED ZEROFILL NOT NULL,
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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
