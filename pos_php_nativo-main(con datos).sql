-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-05-2025 a las 05:26:06
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
(1, '2025-05-20 20:25:31', NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 2960.00, 1.00, 2961.00, 0.00, 0.00, 0.00, 2961.00, 0.00, 0.00, 'abierta', 65, NULL, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cajas`
--

CREATE TABLE `cajas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(35) NOT NULL,
  `numero_caja` varchar(10) NOT NULL,
  `nro_ticket` int(10) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `cajas`
--

INSERT INTO `cajas` (`id`, `nombre`, `numero_caja`, `nro_ticket`, `estado`) VALUES
(1, 'Caja de ventas', '1', 65, 1),
(2, 'Caja Administrativa', '2', 0, 0);

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
(6, ' guarniciones', '2025-04-26 02:16:04', 1);

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
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `id_arqueo_caja` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 14, 1, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(2, 6, 2, 'eco ala broasterd', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(3, 14, 3, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(4, 14, 4, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(5, 17, 5, 'eco contra brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(6, 66, 6, 'valle 1l durazno', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(7, 15, 7, 'eco ala brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(8, 17, 8, 'eco contra brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(9, 69, 9, 'salchipapa simple', 1, 12.00, 7.00, 12.00, NULL, NULL, 'M'),
(10, 69, 10, 'salchipapa simple', 1, 12.00, 7.00, 12.00, NULL, NULL, 'M'),
(11, 5, 11, 'eco pecho broasterd', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(12, 41, 12, 'pop papaya 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(13, 6, 13, 'eco ala broasterd', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(14, 15, 14, 'eco ala brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(15, 14, 15, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(16, 14, 16, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(17, 14, 17, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(18, 15, 18, 'eco ala brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(19, 14, 19, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(20, 16, 20, 'eco pierna brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(21, 6, 21, 'eco ala broasterd', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(22, 15, 22, 'eco ala brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(23, 5, 23, 'eco pecho broasterd', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(24, 63, 24, 'aquarios pomelo 2l', 1, 13.00, 11.00, 13.00, NULL, NULL, 'M'),
(25, 16, 25, 'eco pierna brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(26, 14, 26, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(27, 14, 27, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(28, 14, 28, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(29, 14, 29, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(30, 17, 30, 'eco contra brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(31, 15, 31, 'eco ala brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(32, 6, 32, 'eco ala broasterd', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(33, 15, 33, 'eco ala brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(34, 17, 34, 'eco contra brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(35, 40, 35, 'pop naranja 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(36, 63, 36, 'aquarios pomelo 2l', 1, 13.00, 11.00, 13.00, NULL, NULL, 'M'),
(37, 15, 37, 'eco ala brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(38, 15, 38, 'eco ala brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(39, 63, 39, 'aquarios pomelo 2l', 1, 13.00, 11.00, 13.00, NULL, NULL, 'M'),
(40, 40, 40, 'pop naranja 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(41, 6, 41, 'eco ala broasterd', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(42, 17, 42, 'eco contra brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(43, 16, 42, 'eco pierna brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(44, 15, 42, 'eco ala brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(45, 14, 42, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(46, 13, 42, '1/4 pierna brasa', 1, 23.00, 18.00, 23.00, NULL, NULL, 'M'),
(47, 12, 42, '1/4  pecho brasa', 1, 23.00, 18.00, 23.00, NULL, NULL, 'M'),
(48, 11, 42, '1/2 brasa', 1, 45.00, 30.00, 45.00, NULL, NULL, 'M'),
(49, 10, 42, 'entero brasa', 1, 90.00, 70.00, 90.00, NULL, NULL, 'M'),
(50, 40, 43, 'pop naranja 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(51, 17, 44, 'eco contra brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(52, 11, 45, '1/2 brasa', 1, 45.00, 30.00, 45.00, NULL, NULL, 'M'),
(53, 41, 46, 'pop papaya 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(54, 15, 47, 'eco ala brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(55, 5, 48, 'eco pecho broasterd', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(56, 5, 49, 'eco pecho broasterd', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(57, 15, 50, 'eco ala brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(58, 14, 51, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(59, 15, 52, 'eco ala brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(60, 14, 52, 'eco pecho brasa', 20, 13.00, 10.00, 260.00, NULL, NULL, 'M'),
(61, 16, 52, 'eco pierna brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(62, 10, 52, 'entero brasa', 1, 90.00, 70.00, 90.00, NULL, NULL, 'M'),
(63, 12, 52, '1/4  pecho brasa', 20, 23.00, 18.00, 460.00, NULL, NULL, 'M'),
(64, 11, 52, '1/2 brasa', 20, 45.00, 30.00, 900.00, NULL, NULL, 'M'),
(65, 13, 52, '1/4 pierna brasa', 1, 23.00, 18.00, 23.00, NULL, NULL, 'M'),
(66, 14, 53, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(67, 16, 54, 'eco pierna brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(68, 17, 55, 'eco contra brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(69, 69, 55, 'salchipapa simple', 1, 12.00, 7.00, 12.00, NULL, NULL, 'M'),
(70, 16, 56, 'eco pierna brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(71, 66, 57, 'valle 1l durazno', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(72, 1, 58, 'entero broasterd', 1, 90.00, 70.00, 90.00, NULL, NULL, 'M'),
(73, 66, 59, 'valle 1l durazno', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(74, 1, 60, 'entero broasterd', 1, 90.00, 70.00, 90.00, NULL, NULL, 'M'),
(75, 14, 61, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(76, 69, 62, 'salchipapa simple', 1, 12.00, 7.00, 12.00, NULL, NULL, 'M'),
(77, 14, 63, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(78, 16, 64, 'eco pierna brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(79, 74, 65, 'porcion de fideo', 1, 7.00, 4.00, 7.00, NULL, NULL, 'M'),
(80, 16, 65, 'eco pierna brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M');

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
(1, 's/n', 'sin carnet', '000-00-000', 'sin direccion', 133, '2025-05-20 23:22:20', '2025-05-21 03:22:20', 1),
(2, 'axel justiniano', '89989856sc', '745-15-545', 'cotoca', 4, '2025-05-20 23:24:29', '2025-05-21 03:24:29', 1);

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
(1, 1, '101', 'entero broasterd', 'vistas/img/productos/101/884.webp', 48, 90, 70, 2, '2025-05-21 03:20:17', 0, 1),
(2, 1, '102', '1/2 broasterd', 'vistas/img/productos/102/847.webp', 50, 45, 30, 0, '2025-05-12 02:54:06', 0, 1),
(3, 1, '103', '1/4 pecho broasterd', 'vistas/img/productos/103/138.webp', 50, 23, 18, 0, '2025-05-12 02:54:23', 0, 1),
(4, 1, '108', '1/4 pierna broasterd', 'vistas/img/productos/108/247.webp', 50, 23, 18, 0, '2025-05-12 02:54:48', 0, 1),
(5, 1, '104', 'eco pecho broasterd', 'vistas/img/productos/104/897.webp', 46, 13, 10, 4, '2025-05-21 01:13:42', 0, 1),
(6, 1, '106', 'eco ala broasterd', 'vistas/img/productos/106/670.webp', 45, 13, 10, 5, '2025-05-21 01:12:29', 0, 1),
(7, 1, '105', ' eco pierna broasterd', 'vistas/img/productos/105/476.webp', 50, 13, 10, 0, '2025-05-12 02:55:33', 0, 1),
(8, 1, '107', 'eco contra broasterd', 'vistas/img/productos/107/294.webp', 50, 13, 10, 0, '2025-05-12 02:55:50', 0, 1),
(10, 2, '201', 'entero brasa', 'vistas/img/productos/201/261.webp', 48, 90, 70, 2, '2025-05-21 01:55:00', 0, 1),
(11, 2, '202', '1/2 brasa', 'vistas/img/productos/202/826.webp', 28, 45, 30, 22, '2025-05-21 01:55:00', 0, 1),
(12, 2, '203', '1/4  pecho brasa', 'vistas/img/productos/203/459.webp', 29, 23, 18, 21, '2025-05-21 01:55:00', 0, 1),
(13, 2, '204', '1/4 pierna brasa', 'vistas/img/productos/204/610.webp', 48, 23, 18, 2, '2025-05-21 01:55:00', 0, 1),
(14, 2, '205', 'eco pecho brasa', 'vistas/img/productos/205/622.webp', 14, 13, 10, 36, '2025-05-21 03:23:03', 0, 1),
(15, 2, '206', 'eco ala brasa', 'vistas/img/productos/206/334.png', 38, 13, 10, 12, '2025-05-21 01:55:00', 0, 1),
(16, 2, '207', 'eco pierna brasa', 'vistas/img/productos/207/304.webp', 42, 13, 10, 8, '2025-05-21 03:24:29', 0, 1),
(17, 2, '208', 'eco contra brasa', 'vistas/img/productos/208/618.webp', 43, 13, 10, 7, '2025-05-21 03:19:21', 0, 1),
(18, 3, '301', 'coca cola 3l', 'vistas/img/productos/301/586.webp', 50, 17, 15, 0, '2025-05-12 03:00:50', 1, 1),
(19, 3, '302', 'coca cola 2l', 'vistas/img/productos/302/757.webp', 50, 13, 11, 0, '2025-05-12 03:01:02', 1, 1),
(20, 3, '303', 'coca cola 600ml', 'vistas/img/productos/303/248.webp', 50, 6, 3, 0, '2025-05-12 03:01:15', 1, 1),
(21, 3, '304', 'retornable coca cola 2.5l', 'vistas/img/productos/304/116.webp', 50, 12, 7, 0, '2025-05-12 03:01:27', 1, 1),
(22, 3, '305', 'fanta naranja 3l', 'vistas/img/productos/305/612.webp', 50, 17, 15, 0, '2025-05-12 03:01:44', 1, 1),
(23, 3, '306', 'fanta naranja 2l', 'vistas/img/productos/306/628.webp', 50, 13, 11, 0, '2025-05-12 03:01:57', 1, 1),
(24, 3, '307', 'fanta naranja 600ml', 'vistas/img/productos/307/828.webp', 50, 6, 3, 0, '2025-05-12 03:02:11', 1, 1),
(25, 3, '308', 'fanta papaya 3l', 'vistas/img/productos/308/132.webp', 50, 17, 15, 0, '2025-05-12 03:02:41', 1, 1),
(26, 3, '309', 'fanta papaya 2l', 'vistas/img/productos/309/885.webp', 50, 13, 11, 0, '2025-05-12 03:02:53', 1, 1),
(27, 3, '310', 'fanta guarana 3l', 'vistas/img/productos/310/726.webp', 50, 17, 15, 0, '2025-05-12 03:03:08', 1, 1),
(28, 3, '311', 'fanta guarana 2l', 'vistas/img/productos/311/328.webp', 50, 13, 11, 0, '2025-05-12 03:03:24', 1, 1),
(29, 3, '312', 'sprite 600ml', 'vistas/img/productos/312/220.webp', 50, 6, 3, 0, '2025-05-12 03:03:45', 1, 1),
(30, 3, '313', 'simba manzana 2l', 'vistas/img/productos/313/175.webp', 50, 12, 10, 0, '2025-05-12 03:04:45', 1, 1),
(31, 3, '314', 'simba piña 2l', 'vistas/img/productos/314/492.webp', 50, 12, 10, 0, '2025-05-12 03:05:06', 1, 1),
(32, 3, '315', 'simba durazno 2l', 'vistas/img/productos/315/973.webp', 50, 12, 10, 0, '2025-05-12 03:05:20', 1, 1),
(33, 3, '316', 'pepsi 2l', 'vistas/img/productos/316/642.webp', 50, 10, 9, 0, '2025-05-12 03:34:55', 1, 0),
(34, 3, '317', 'pepsi 1l', 'vistas/img/productos/317/756.webp', 50, 7, 3, 0, '2025-05-12 03:05:38', 1, 1),
(35, 3, '318', 'mendocina papaya 1l', 'vistas/img/productos/318/561.webp', 50, 7, 5, 0, '2025-05-12 03:05:50', 1, 1),
(36, 3, '326', 'fanta naranja mini', 'vistas/img/productos/326/635.webp', 50, 2, 1.4, 0, '2025-05-12 03:06:07', 1, 1),
(37, 3, '325', 'coca cola mini 190ml', 'vistas/img/productos/325/287.jpg', 50, 2, 1.4, 0, '2025-03-08 21:36:26', 1, 1),
(38, 3, '324', 'pop uva 620ml', 'vistas/img/productos/324/767.webp', 50, 3, 1.8, 0, '2025-05-12 03:38:31', 1, 1),
(39, 3, '319', 'pop piña  620ml', 'vistas/img/productos/319/719.webp', 50, 3, 1.8, 0, '2025-05-12 03:07:01', 1, 1),
(40, 3, '320', 'pop naranja 620ml', 'vistas/img/productos/320/429.webp', 47, 3, 1.8, 3, '2025-05-21 01:13:04', 1, 1),
(41, 3, '321', 'pop papaya 620ml', 'vistas/img/productos/321/710.webp', 48, 3, 1.8, 2, '2025-05-21 01:13:23', 1, 1),
(42, 3, '322', 'pop manzana 620ml', 'vistas/img/productos/322/983.webp', 50, 3, 1.8, 0, '2025-05-12 03:08:24', 1, 1),
(43, 3, '323', 'pop guarana 620ml', 'vistas/img/productos/323/698.webp', 50, 3, 1.8, 0, '2025-05-12 03:08:37', 1, 1),
(59, 4, '401', 'valle 3l  naranja', 'vistas/img/productos/401/178.webp', 50, 17, 14.5, 0, '2025-05-12 03:09:26', 1, 1),
(60, 4, '402', 'aquarios pera 3l', 'vistas/img/productos/402/979.webp', 50, 17, 15, 0, '2025-05-12 03:12:13', 1, 1),
(61, 4, '403', 'aquarios pera 2l', 'vistas/img/productos/403/838.webp', 50, 13, 11, 0, '2025-05-12 03:11:09', 1, 1),
(62, 4, '404', 'aquarios pomelo 3l', 'vistas/img/productos/404/845.webp', 50, 17, 15, 0, '2025-05-12 03:12:26', 1, 1),
(63, 4, '405', 'aquarios pomelo 2l', 'vistas/img/productos/405/871.webp', 47, 13, 11, 3, '2025-05-21 01:12:15', 1, 1),
(64, 4, '406', 'chicha 2l', 'vistas/img/productos/406/903.webp', 50, 12, 9, 0, '2025-05-12 03:31:47', 1, 0),
(65, 4, '407', 'tropi 600ml', 'vistas/img/productos/407/166.webp', 50, 6, 3, 0, '2025-05-12 03:13:09', 1, 1),
(66, 4, '408', 'valle 1l durazno', 'vistas/img/productos/408/174.webp', 47, 12, 10, 3, '2025-05-21 03:20:10', 1, 1),
(69, 5, '501', 'salchipapa simple', 'vistas/img/productos/501/916.webp', 46, 12, 7, 4, '2025-05-21 03:22:20', 0, 1),
(71, 4, '409', 'valle 1l manzana', 'vistas/img/productos/409/600.webp', 50, 12, 10, 0, '2025-05-12 03:17:09', 1, 1),
(72, 6, '601', 'porcion de arroz', 'vistas/img/productos/601/268.webp', 50, 7, 4, 0, '2025-05-12 03:17:21', 0, 1),
(73, 6, '602', 'porcion de papa fritas', 'vistas/img/productos/602/228.webp', 50, 7, 4, 0, '2025-05-12 03:17:34', 0, 1),
(74, 6, '603', 'porcion de fideo', 'vistas/img/productos/603/620.webp', 49, 7, 4, 1, '2025-05-21 03:24:29', 0, 1);

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
(1, 'rosmery quizpe', 'admin', '$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG', 'Administrador', 'vistas/img/usuarios/admin/202.png', 1, '2025-05-12 01:58:58', '2025-05-12 05:58:58', 1),
(2, 'luis hidalgo', 'luis10', '$2a$07$asxx54ahjppf45sd87a5auGZEtGHuyZwm.Ur.FJvWLCql3nmsMbXy', 'Vendedor', '', 1, '2025-04-25 22:50:43', '2025-04-26 02:50:43', 1),
(3, 'edwin yamil', 'edwin', '$2a$07$asxx54ahjppf45sd87a5auGZEtGHuyZwm.Ur.FJvWLCql3nmsMbXy', 'Especial', '', 1, '2025-04-25 22:46:53', '2025-04-26 02:46:53', 1);

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
(1, 1, NULL, 13, 50, '2025-05-21 00:25:37', '', 'Efectivo', 37, 'En Mesa', 1, 1, 1, 1, 1),
(2, 2, NULL, 13, 50, '2025-05-21 00:25:45', '', 'Efectivo', 37, 'En Mesa', 1, 1, 1, 1, 1),
(3, 3, NULL, 13, 80, '2025-05-21 00:25:52', '', 'Efectivo', 67, 'En Mesa', 1, 1, 1, 1, 1),
(4, 4, NULL, 13, 50, '2025-05-21 01:07:37', '', 'Efectivo', 37, 'En Mesa', 1, 1, 1, 1, 1),
(5, 5, NULL, 13, 50, '2025-05-21 01:07:49', '', 'Efectivo', 37, 'En Mesa', 1, 1, 1, 1, 1),
(6, 6, NULL, 12, 12, '2025-05-21 01:07:56', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 1),
(7, 7, NULL, 13, 80, '2025-05-21 01:08:02', '', 'Efectivo', 67, 'En Mesa', 1, 1, 1, 1, 1),
(8, 8, NULL, 13, 80, '2025-05-21 01:08:07', '', 'Efectivo', 67, 'En Mesa', 1, 1, 1, 1, 1),
(9, 9, NULL, 12, 50, '2025-05-21 01:08:14', '', 'Efectivo', 38, 'En Mesa', 1, 1, 1, 1, 1),
(10, 10, NULL, 12, 100, '2025-05-21 01:08:24', '', 'Efectivo', 88, 'En Mesa', 1, 1, 1, 1, 1),
(11, 11, NULL, 13, 100, '2025-05-21 01:08:31', '', 'Efectivo', 87, 'En Mesa', 1, 1, 1, 1, 1),
(12, 12, NULL, 3, 3, '2025-05-21 01:08:37', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 1),
(13, 13, NULL, 13, 50, '2025-05-21 01:08:43', '', 'Efectivo', 37, 'En Mesa', 1, 1, 1, 1, 1),
(14, 14, NULL, 13, 80, '2025-05-21 01:08:58', '', 'Efectivo', 67, 'En Mesa', 1, 1, 1, 1, 1),
(15, 15, NULL, 13, 80, '2025-05-21 01:09:08', '', 'Efectivo', 67, 'En Mesa', 1, 1, 1, 1, 1),
(16, 16, NULL, 13, 80, '2025-05-21 01:09:16', '', 'Efectivo', 67, 'En Mesa', 1, 1, 1, 1, 1),
(17, 17, NULL, 13, 80, '2025-05-21 01:09:24', '', 'Efectivo', 67, 'En Mesa', 1, 1, 1, 1, 1),
(18, 18, NULL, 13, 800, '2025-05-21 01:09:31', '', 'Efectivo', 787, 'En Mesa', 1, 1, 1, 1, 1),
(19, 19, NULL, 13, 80, '2025-05-21 01:09:36', '', 'Efectivo', 67, 'En Mesa', 1, 1, 1, 1, 1),
(20, 20, NULL, 13, 80, '2025-05-21 01:09:40', '', 'Efectivo', 67, 'En Mesa', 1, 1, 1, 1, 1),
(21, 21, NULL, 13, 80, '2025-05-21 01:09:45', '', 'Efectivo', 67, 'En Mesa', 1, 1, 1, 1, 1),
(22, 22, NULL, 13, 80, '2025-05-21 01:09:50', '', 'Efectivo', 67, 'En Mesa', 1, 1, 1, 1, 1),
(23, 23, NULL, 13, 80, '2025-05-21 01:09:56', '', 'Efectivo', 67, 'En Mesa', 1, 1, 1, 1, 1),
(24, 24, NULL, 13, 100, '2025-05-21 01:10:02', '', 'Efectivo', 87, 'En Mesa', 1, 1, 1, 1, 1),
(25, 25, NULL, 13, 80, '2025-05-21 01:10:08', '', 'Efectivo', 67, 'En Mesa', 1, 1, 1, 1, 1),
(26, 26, NULL, 13, 80, '2025-05-21 01:10:56', '', 'Efectivo', 67, 'En Mesa', 1, 1, 1, 1, 1),
(27, 27, NULL, 13, 80, '2025-05-21 01:11:03', '', 'Efectivo', 67, 'En Mesa', 1, 1, 1, 1, 1),
(28, 28, NULL, 13, 100, '2025-05-21 01:11:10', '', 'Efectivo', 87, 'En Mesa', 1, 1, 1, 1, 1),
(29, 29, NULL, 13, 100, '2025-05-21 01:11:15', '', 'Efectivo', 87, 'En Mesa', 1, 1, 1, 1, 1),
(30, 30, NULL, 13, 80, '2025-05-21 01:11:21', '', 'Efectivo', 67, 'En Mesa', 1, 1, 1, 1, 1),
(31, 31, NULL, 13, 80, '2025-05-21 01:11:27', '', 'Efectivo', 67, 'En Mesa', 1, 1, 1, 1, 1),
(32, 32, NULL, 13, 80, '2025-05-21 01:11:33', '', 'Efectivo', 67, 'En Mesa', 1, 1, 1, 1, 1),
(33, 33, NULL, 13, 80, '2025-05-21 01:11:37', '', 'Efectivo', 67, 'En Mesa', 1, 1, 1, 1, 1),
(34, 34, NULL, 13, 90, '2025-05-21 01:11:42', '', 'Efectivo', 77, 'En Mesa', 1, 1, 1, 1, 1),
(35, 35, NULL, 3, 100, '2025-05-21 01:11:50', '', 'Efectivo', 97, 'En Mesa', 1, 1, 1, 1, 1),
(36, 36, NULL, 13, 100, '2025-05-21 01:11:57', '', 'Efectivo', 87, 'En Mesa', 1, 1, 1, 1, 1),
(37, 37, NULL, 13, 100, '2025-05-21 01:12:03', '', 'Efectivo', 87, 'En Mesa', 1, 1, 1, 1, 1),
(38, 38, NULL, 13, 100, '2025-05-21 01:12:08', '', 'Efectivo', 87, 'En Mesa', 1, 1, 1, 1, 1),
(39, 39, NULL, 13, 100, '2025-05-21 01:12:15', '', 'Efectivo', 87, 'En Mesa', 1, 1, 1, 1, 1),
(40, 40, NULL, 3, 20, '2025-05-21 01:12:22', '', 'Efectivo', 17, 'En Mesa', 1, 1, 1, 1, 1),
(41, 41, NULL, 13, 100, '2025-05-21 01:12:29', '', 'Efectivo', 87, 'En Mesa', 1, 1, 1, 1, 1),
(42, 42, NULL, 233, 500, '2025-05-21 01:12:58', '', 'Efectivo', 267, 'En Mesa', 1, 1, 1, 1, 1),
(43, 43, NULL, 3, 80, '2025-05-21 01:13:04', '', 'Efectivo', 77, 'En Mesa', 1, 1, 1, 1, 1),
(44, 44, NULL, 13, 80, '2025-05-21 01:13:10', '', 'Efectivo', 67, 'En Mesa', 1, 1, 1, 1, 1),
(45, 45, NULL, 45, 100, '2025-05-21 01:13:15', '', 'Efectivo', 55, 'En Mesa', 1, 1, 1, 1, 1),
(46, 46, NULL, 3, 100, '2025-05-21 01:13:23', '', 'Efectivo', 97, 'En Mesa', 1, 1, 1, 1, 1),
(47, 47, NULL, 13, 500, '2025-05-21 01:13:30', '', 'Efectivo', 487, 'En Mesa', 1, 1, 1, 1, 1),
(48, 48, NULL, 13, 100, '2025-05-21 01:13:36', '', 'Efectivo', 87, 'En Mesa', 1, 1, 1, 1, 1),
(49, 49, NULL, 13, 500, '2025-05-21 01:13:42', '', 'Efectivo', 487, 'En Mesa', 1, 1, 1, 1, 1),
(50, 50, NULL, 13, 100, '2025-05-21 01:13:47', '', 'Efectivo', 87, 'En Mesa', 1, 1, 1, 1, 1),
(51, 51, NULL, 13, 80, '2025-05-21 01:44:15', '', 'QR', 67, 'En Mesa', 1, 1, 1, 1, 1),
(52, 52, NULL, 1759, 2000, '2025-05-21 01:55:00', '', 'Efectivo', 241, 'En Mesa', 1, 1, 1, 1, 1),
(53, 53, NULL, 13, 80, '2025-05-21 03:19:10', '', 'Efectivo', 67, 'En Mesa', 1, 1, 1, 1, 1),
(54, 54, NULL, 13, 80, '2025-05-21 03:19:14', '', 'Efectivo', 67, 'En Mesa', 1, 1, 1, 1, 1),
(55, 55, NULL, 25, 122, '2025-05-21 03:19:21', '', 'Efectivo', 97, 'En Mesa', 1, 1, 1, 1, 1),
(56, 56, NULL, 13, 80, '2025-05-21 03:19:29', '', 'QR', 67, 'En Mesa', 1, 1, 1, 1, 1),
(57, 57, NULL, 12, 80, '2025-05-21 03:19:36', '', 'Efectivo', 68, 'En Mesa', 1, 1, 1, 1, 1),
(58, 58, NULL, 90, 90, '2025-05-21 03:20:00', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 1),
(59, 59, NULL, 12, 12, '2025-05-21 03:20:10', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 1),
(60, 60, NULL, 90, 90, '2025-05-21 03:20:17', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 1),
(61, 61, NULL, 13, 80, '2025-05-21 03:22:10', '', 'Efectivo', 67, 'En Mesa', 1, 1, 1, 1, 1),
(62, 62, NULL, 12, 50, '2025-05-21 03:22:20', '', 'Efectivo', 38, 'En Mesa', 1, 1, 1, 1, 1),
(63, 63, NULL, 13, 13, '2025-05-21 03:23:03', '', 'Efectivo', 0, 'En Mesa', 1, 2, 1, 1, 1),
(64, 64, NULL, 13, 90, '2025-05-21 03:23:17', '', 'Efectivo', 77, 'En Mesa', 1, 2, 1, 1, 1),
(65, 65, NULL, 20, 50, '2025-05-21 03:24:29', '', 'Efectivo', 30, 'En Mesa', 1, 2, 1, 1, 1);

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
  ADD KEY `fk_compra_proveedor` (`id_proveedor`),
  ADD KEY `id_arqueo_caja` (`id_arqueo_caja`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `cajas`
--
ALTER TABLE `cajas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT de la tabla `meseros`
--
ALTER TABLE `meseros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

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
-- Filtros para la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD CONSTRAINT `fk_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_venta` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
