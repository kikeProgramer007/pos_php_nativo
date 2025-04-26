-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-04-2025 a las 03:41:09
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
(1, '2025-04-14 12:31:02', NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 117.00, 0.00, 117.00, 0.00, 15.00, 15.00, 102.00, 0.00, 0.00, 'abierta', 00000008, NULL, 1, 1),
(2, '2025-04-15 16:55:38', '2025-04-15 17:15:13', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'cerrada', 00000010, NULL, 1, 1),
(3, '2025-04-15 17:15:28', NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 988.00, 10.00, 998.00, 0.00, 0.00, 0.00, 998.00, 0.00, 0.00, 'abierta', 00000015, NULL, 1, 1),
(4, '2025-04-16 13:25:47', '2025-04-16 13:26:59', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 107.00, 0.00, 107.00, 0.00, 0.00, 0.00, 107.00, 200.00, 93.00, 'cerrada', 00000004, NULL, 1, 1),
(5, '2025-04-16 13:27:52', '2025-04-16 13:31:12', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.00, 50.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'cerrada', 00000000, NULL, 1, 1),
(6, '2025-04-16 13:31:52', '2025-04-16 13:32:16', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.00, 200.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'cerrada', 00000000, NULL, 1, 1),
(7, '2025-04-16 13:33:49', NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.00, 200.00, 200.00, 0.00, 0.00, 0.00, 200.00, 0.00, 0.00, 'abierta', 00000000, NULL, 1, 1),
(8, '2025-04-17 13:39:10', '2025-04-17 13:39:43', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.00, 2.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'cerrada', 00000002, NULL, 1, 1),
(9, '2025-04-17 13:55:07', NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 434.00, 2.00, 436.00, 0.00, 0.00, 0.00, 436.00, 0.00, 0.00, 'abierta', 00000021, NULL, 1, 1),
(10, '2025-04-18 13:44:07', NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 18.00, 5.00, 23.00, 0.00, 0.00, 0.00, 23.00, 0.00, 0.00, 'abierta', 00000022, NULL, 1, 1),
(11, '2025-04-19 22:49:51', NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 90.00, 0.00, 90.00, 0.00, 0.00, 0.00, 90.00, 0.00, 0.00, 'abierta', 00000029, NULL, 1, 1),
(12, '2025-04-20 00:18:07', '2025-04-20 00:18:39', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'cerrada', 00000029, NULL, 1, 1),
(13, '2025-04-20 00:18:46', '2025-04-20 16:44:29', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'cerrada', 00000048, NULL, 1, 1),
(14, '2025-04-20 16:41:09', NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.00, 10.00, 10.00, 0.00, 0.00, 0.00, 10.00, 0.00, 0.00, 'abierta', 00000048, NULL, 1, 2),
(15, '2025-04-20 16:44:41', '2025-04-20 16:44:57', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'cerrada', 00000000, NULL, 1, 1),
(16, '2025-04-20 16:45:32', '2025-04-20 16:45:51', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'cerrada', 00000000, NULL, 1, 1),
(17, '2025-04-20 16:47:59', NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 40967.00, 0.00, 40967.00, 0.00, 0.00, 0.00, 40967.00, 0.00, 0.00, 'abierta', 00000006, NULL, 1, 1),
(18, '2025-04-22 16:19:38', NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 36.00, 0.50, 36.50, 0.00, 0.00, 0.00, 36.50, 0.00, 0.00, 'abierta', 00000009, NULL, 1, 1),
(19, '2025-04-23 14:59:31', NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 12.00, 0.50, 12.50, 0.00, 0.00, 0.00, 12.50, 0.00, 0.00, 'abierta', 00000010, NULL, 1, 1),
(20, '2025-04-25 12:38:11', '2025-04-25 17:07:16', 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 120.00, 200.00, 320.00, 0.00, 0.00, 0.00, 320.00, 5.00, -315.00, 'cerrada', 00000019, NULL, 1, 1);

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
(6, 'hamburguesa', '2025-01-05 21:43:07', 1),
(7, 'test', '2025-03-05 20:18:16', 0),
(8, 'qswd', '2025-04-14 17:40:41', 0);

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
(2, 'yina rivero garcia', '2025-04-14 21:36:40', 1),
(3, 'ada', '2025-04-17 19:49:39', 1),
(4, 'marcos galarza', '2025-04-17 20:02:02', 1),
(5, 'david rivero', '2025-04-18 17:45:40', 1),
(6, 'marcosgalarza', '2025-04-20 03:05:43', 1);

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
(5, 10005, 15.00, 1, 3, '2025-04-14 21:28:26', 1);

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
(37, 66, 5, 'pura vida de 3l ', 1, 15.00, 15.00);

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
(1, 14, 1, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, 'No Papas', NULL, 'M'),
(2, 14, 2, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(3, 14, 3, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(4, 14, 4, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, 'Solo Fideo', NULL, 'M'),
(5, 14, 5, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, 'No Fideo', NULL, 'M'),
(6, 5, 6, 'eco pecho broasterd', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(7, 14, 7, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'LL'),
(8, 3, 8, '1/4 pecho broasterd', 1, 20.00, 18.00, 20.00, NULL, NULL, 'M'),
(9, 19, 8, 'coca cola 2l', 1, 13.00, 11.00, 13.00, NULL, NULL, 'M'),
(10, 14, 9, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(11, 14, 10, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(12, 69, 11, 'salchipapa simple', 1, 10.00, 7.00, 10.00, NULL, NULL, 'M'),
(13, 14, 12, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, 'No Fideo', NULL, 'LL'),
(14, 14, 12, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(15, 14, 13, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, 'Solo Arroz', NULL, 'M'),
(16, 14, 13, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, 'Solo Fideo', NULL, 'LL'),
(17, 14, 14, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'LL'),
(18, 14, 14, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'LL'),
(19, 14, 15, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(20, 14, 15, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(21, 14, 16, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(22, 14, 17, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(23, 14, 17, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(24, 14, 18, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(25, 15, 18, 'eco pierna brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(26, 14, 19, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(27, 14, 20, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(28, 15, 20, 'eco pierna brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(29, 65, 20, 'tropi 600ml', 1, 6.00, 3.00, 6.00, NULL, NULL, 'M'),
(30, 69, 20, 'salchipapa simple', 1, 10.00, 7.00, 10.00, NULL, NULL, 'M'),
(31, 60, 20, 'aquarios pera 3l', 1, 17.00, 15.00, 17.00, NULL, NULL, 'M'),
(32, 42, 20, 'pop manzana 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(33, 14, 21, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(34, 15, 21, 'eco pierna brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(35, 16, 21, 'eco ala brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(36, 17, 21, 'eco contra brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(37, 69, 22, 'salchipapa simple', 1, 10.00, 7.00, 10.00, NULL, NULL, 'M'),
(38, 64, 22, 'chicha 2l', 1, 12.00, 9.00, 12.00, NULL, NULL, 'M'),
(39, 59, 22, 'valle 3l  naranja', 1, 17.00, 14.50, 17.00, NULL, NULL, 'M'),
(40, 60, 22, 'aquarios pera 3l', 1, 17.00, 15.00, 17.00, NULL, NULL, 'M'),
(41, 62, 22, 'aquarios pomelo 3l', 1, 17.00, 15.00, 17.00, NULL, NULL, 'M'),
(42, 61, 22, 'aquarios pera 2l', 1, 13.00, 11.00, 13.00, NULL, NULL, 'M'),
(43, 66, 22, 'pura vida de 3l ', 1, 18.00, 15.00, 18.00, NULL, NULL, 'M'),
(44, 65, 22, 'tropi 600ml', 1, 6.00, 3.00, 6.00, NULL, NULL, 'M'),
(45, 63, 22, 'aquarios pomelo 2l', 1, 13.00, 11.00, 13.00, NULL, NULL, 'M'),
(46, 58, 22, 'pop uva 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(47, 43, 22, 'pop guarana 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(48, 42, 22, 'pop manzana 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(49, 41, 22, 'pop papaya 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(50, 40, 22, 'pop naranja 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(51, 39, 22, 'pop piña  620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(52, 38, 22, 'pop uva 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(53, 37, 22, 'coca cola mini 190ml', 1, 2.00, 1.40, 2.00, NULL, NULL, 'M'),
(54, 36, 22, 'fanta naranja mini', 1, 2.00, 1.40, 2.00, NULL, NULL, 'M'),
(55, 35, 22, 'mendocina papaya 1l', 1, 6.00, 5.00, 6.00, NULL, NULL, 'M'),
(56, 34, 22, 'pepsi 1l', 1, 6.00, 3.00, 6.00, NULL, NULL, 'M'),
(57, 33, 22, 'pepsi 2l', 1, 10.00, 9.00, 10.00, NULL, NULL, 'M'),
(58, 17, 22, 'eco contra brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(59, 16, 22, 'eco ala brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(60, 15, 22, 'eco pierna brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(61, 14, 22, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(62, 13, 22, '1/4 pierna brasa', 1, 20.00, 18.00, 20.00, NULL, NULL, 'M'),
(63, 12, 22, '1/4  pecho brasa', 1, 20.00, 18.00, 20.00, NULL, NULL, 'M'),
(64, 11, 22, '1/2 brasa', 1, 40.00, 30.00, 40.00, NULL, NULL, 'M'),
(65, 10, 22, 'entero brasa', 1, 80.00, 70.00, 80.00, NULL, NULL, 'M'),
(66, 8, 22, 'eco contra broasterd', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(67, 7, 22, 'eco ala broasterd', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(68, 6, 22, 'eco pierna broasterd', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(69, 5, 22, 'eco pecho broasterd', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(70, 4, 22, '1/4 pierna broasterd', 1, 20.00, 18.00, 20.00, NULL, NULL, 'M'),
(71, 3, 22, '1/4 pecho broasterd', 1, 20.00, 18.00, 20.00, NULL, NULL, 'M'),
(72, 2, 22, '1/2 broasterd', 1, 40.00, 30.00, 40.00, NULL, NULL, 'M'),
(73, 1, 22, 'entero broasterd', 1, 80.00, 70.00, 80.00, NULL, NULL, 'M'),
(74, 16, 23, 'eco ala brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(75, 65, 23, 'tropi 600ml', 1, 6.00, 3.00, 6.00, NULL, NULL, 'M'),
(76, 66, 24, 'pura vida de 3l ', 1, 18.00, 15.00, 18.00, NULL, NULL, 'M'),
(77, 10, 25, 'entero brasa', 1, 80.00, 70.00, 80.00, NULL, NULL, 'M'),
(78, 14, 26, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(79, 14, 27, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(80, 10, 28, 'entero brasa', 1, 80.00, 70.00, 80.00, NULL, NULL, 'M'),
(81, 41, 29, 'pop papaya 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(82, 14, 30, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(83, 14, 31, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(84, 14, 32, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(85, 5, 33, 'eco pecho broasterd', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(86, 14, 34, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(87, 14, 35, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(88, 15, 36, 'eco pierna brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(89, 16, 37, 'eco ala brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(90, 17, 38, 'eco contra brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(91, 17, 39, 'eco contra brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(92, 65, 40, 'tropi 600ml', 1, 6.00, 3.00, 6.00, NULL, NULL, 'M'),
(93, 15, 41, 'eco pierna brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(94, 15, 42, 'eco pierna brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(95, 15, 43, 'eco pierna brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(96, 16, 44, 'eco ala brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(97, 10, 45, 'entero brasa', 1, 80.00, 70.00, 80.00, NULL, NULL, 'M'),
(98, 14, 46, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(99, 2, 47, '1/2 broasterd', 1, 40.00, 30.00, 40.00, NULL, NULL, 'M'),
(100, 1, 47, 'entero broasterd', 1, 80.00, 70.00, 80.00, NULL, NULL, 'M'),
(101, 16, 48, 'eco ala brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(102, 15, 49, 'eco pierna brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(103, 15, 50, 'eco pierna brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(104, 14, 51, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(105, 15, 52, 'eco pierna brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(106, 14, 52, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'LL'),
(107, 5, 53, 'eco pecho broasterd', 1, 12.00, 10.00, 12.00, NULL, 'sin arroz\n', 'M'),
(108, 20, 53, 'coca cola 600ml', 1, 6.00, 3.00, 6.00, NULL, NULL, 'M'),
(109, 14, 54, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(110, 14, 55, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(111, 16, 56, 'eco ala brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(112, 20, 56, 'coca cola 600ml', 1, 6.00, 3.00, 6.00, NULL, NULL, 'M'),
(113, 14, 57, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(114, 14, 58, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(115, 14, 59, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(116, 14, 60, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(117, 14, 61, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(118, 14, 61, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, 'Solo Fideo', NULL, 'M'),
(119, 14, 62, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(120, 14, 63, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(121, 5, 64, 'eco pecho broasterd', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(122, 5, 65, 'eco pecho broasterd', 1, 12.00, 10.00, 12.00, 'Solo Fideo,No Papas', NULL, 'M'),
(123, 5, 65, 'eco pecho broasterd', 1, 12.00, 10.00, 12.00, 'Solo Fideo,No Papas', NULL, 'LL'),
(124, 69, 66, 'salchipapa simple', 1, 10.00, 7.00, 10.00, NULL, NULL, 'M'),
(125, 60, 66, 'aquarios pera 3l', 1, 17.00, 15.00, 17.00, NULL, NULL, 'M'),
(126, 15, 67, 'eco pierna brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(127, 14, 68, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, 'Solo Fideo,No Papas', 'oicabte', 'M'),
(128, 16, 69, 'eco ala brasa', 1, 12.00, 10.00, 12.00, 'Solo Fideo', NULL, 'M'),
(129, 15, 69, 'eco pierna brasa', 1, 12.00, 10.00, 12.00, 'Solo Fideo', NULL, 'M'),
(130, 41, 69, 'pop papaya 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(131, 6, 69, 'eco pierna broasterd', 1, 12.00, 10.00, 12.00, 'Solo Papas', NULL, 'M'),
(132, 2, 69, '1/2 broasterd', 1, 40.00, 30.00, 40.00, NULL, NULL, 'M'),
(133, 14, 70, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, 'Solo Fideo', NULL, 'M'),
(134, 37, 70, 'coca cola mini 190ml', 1, 2.00, 1.40, 2.00, NULL, NULL, 'M'),
(135, 5, 70, 'eco pecho broasterd', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(136, 14, 71, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(137, 37, 71, 'coca cola mini 190ml', 1, 2.00, 1.40, 2.00, NULL, NULL, 'M'),
(138, 14, 72, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(139, 14, 73, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(140, 14, 74, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(141, 14, 75, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(142, 19, 76, 'coca cola 2l', 1, 13.00, 11.00, 13.00, NULL, NULL, 'M'),
(143, 5, 76, 'eco pecho broasterd', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(144, 69, 76, 'salchipapa simple', 1, 10.00, 7.00, 10.00, NULL, NULL, 'M'),
(145, 43, 76, 'pop guarana 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(146, 12, 76, '1/4  pecho brasa', 1, 20.00, 18.00, 20.00, NULL, NULL, 'M'),
(147, 10, 76, 'entero brasa', 1, 80.00, 70.00, 80.00, NULL, NULL, 'M'),
(148, 2, 76, '1/2 broasterd', 1, 40.00, 30.00, 40.00, NULL, NULL, 'M'),
(149, 3, 76, '1/4 pecho broasterd', 1, 20.00, 18.00, 20.00, NULL, NULL, 'M'),
(150, 7, 76, 'eco ala broasterd', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(151, 69, 77, 'salchipapa simple', 1, 10.00, 7.00, 10.00, NULL, NULL, 'M'),
(152, 66, 77, 'pura vida de 3l ', 1, 18.00, 15.00, 18.00, NULL, NULL, 'M'),
(153, 65, 77, 'tropi 600ml', 1, 6.00, 3.00, 6.00, NULL, NULL, 'M'),
(154, 64, 77, 'chicha 2l', 1, 12.00, 9.00, 12.00, NULL, NULL, 'M'),
(155, 63, 77, 'aquarios pomelo 2l', 1, 13.00, 11.00, 13.00, NULL, NULL, 'M'),
(156, 62, 77, 'aquarios pomelo 3l', 1, 17.00, 15.00, 17.00, NULL, NULL, 'M'),
(157, 61, 77, 'aquarios pera 2l', 1, 13.00, 11.00, 13.00, NULL, NULL, 'M'),
(158, 60, 77, 'aquarios pera 3l', 1, 17.00, 15.00, 17.00, NULL, NULL, 'M'),
(159, 59, 77, 'valle 3l  naranja', 1, 17.00, 14.50, 17.00, NULL, NULL, 'M'),
(160, 58, 77, 'pop uva 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(161, 43, 77, 'pop guarana 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(162, 42, 77, 'pop manzana 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(163, 41, 77, 'pop papaya 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(164, 40, 77, 'pop naranja 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(165, 39, 77, 'pop piña  620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(166, 38, 77, 'pop uva 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(167, 37, 77, 'coca cola mini 190ml', 1, 2.00, 1.40, 2.00, NULL, NULL, 'M'),
(168, 36, 77, 'fanta naranja mini', 1, 2.00, 1.40, 2.00, NULL, NULL, 'M'),
(169, 35, 77, 'mendocina papaya 1l', 1, 6.00, 5.00, 6.00, NULL, NULL, 'M'),
(170, 34, 77, 'pepsi 1l', 1, 6.00, 3.00, 6.00, NULL, NULL, 'M'),
(171, 33, 77, 'pepsi 2l', 1, 10.00, 9.00, 10.00, NULL, NULL, 'M'),
(172, 15, 77, 'eco pierna brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(173, 14, 77, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(174, 14, 78, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(175, 21, 78, 'retornable coca cola 2.5l', 1, 12.00, 7.00, 12.00, NULL, NULL, 'M'),
(176, 14, 79, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(177, 69, 80, 'salchipapa simple', 1, 10.00, 7.00, 10.00, NULL, NULL, 'M'),
(178, 66, 80, 'pura vida de 3l ', 1, 18.00, 15.00, 18.00, NULL, NULL, 'M'),
(179, 65, 80, 'tropi 600ml', 1, 6.00, 3.00, 6.00, NULL, NULL, 'M'),
(180, 64, 80, 'chicha 2l', 1, 12.00, 9.00, 12.00, NULL, NULL, 'M'),
(181, 63, 80, 'aquarios pomelo 2l', 1, 13.00, 11.00, 13.00, NULL, NULL, 'M'),
(182, 62, 80, 'aquarios pomelo 3l', 1, 17.00, 15.00, 17.00, NULL, NULL, 'M'),
(183, 61, 80, 'aquarios pera 2l', 1, 13.00, 11.00, 13.00, NULL, NULL, 'M'),
(184, 60, 80, 'aquarios pera 3l', 1, 17.00, 15.00, 17.00, NULL, NULL, 'M'),
(185, 59, 80, 'valle 3l  naranja', 1, 17.00, 14.50, 17.00, NULL, NULL, 'M'),
(186, 58, 80, 'pop uva 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(187, 43, 80, 'pop guarana 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(188, 42, 80, 'pop manzana 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(189, 5, 80, 'eco pecho broasterd', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(190, 6, 80, 'eco pierna broasterd', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(191, 7, 80, 'eco ala broasterd', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(192, 8, 80, 'eco contra broasterd', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(193, 1, 80, 'entero broasterd', 1, 80.00, 70.00, 80.00, NULL, NULL, 'M'),
(194, 2, 80, '1/2 broasterd', 1, 40.00, 30.00, 40.00, NULL, NULL, 'M'),
(195, 3, 80, '1/4 pecho broasterd', 1, 20.00, 18.00, 20.00, NULL, NULL, 'M'),
(196, 4, 80, '1/4 pierna broasterd', 1, 20.00, 18.00, 20.00, NULL, NULL, 'M'),
(197, 16, 80, 'eco ala brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(198, 15, 80, 'eco pierna brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(199, 14, 80, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(200, 17, 80, 'eco contra brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(201, 13, 80, '1/4 pierna brasa', 1, 20.00, 18.00, 20.00, NULL, NULL, 'M'),
(202, 12, 80, '1/4  pecho brasa', 1, 20.00, 18.00, 20.00, NULL, NULL, 'M'),
(203, 11, 80, '1/2 brasa', 1, 40.00, 30.00, 40.00, NULL, NULL, 'M'),
(204, 10, 80, 'entero brasa', 1, 80.00, 70.00, 80.00, NULL, NULL, 'M'),
(205, 41, 80, 'pop papaya 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(206, 37, 80, 'coca cola mini 190ml', 1, 2.00, 1.40, 2.00, NULL, NULL, 'M'),
(207, 38, 80, 'pop uva 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(208, 33, 80, 'pepsi 2l', 1, 10.00, 9.00, 10.00, NULL, NULL, 'M'),
(209, 34, 80, 'pepsi 1l', 1, 6.00, 3.00, 6.00, NULL, NULL, 'M'),
(210, 35, 80, 'mendocina papaya 1l', 1, 6.00, 5.00, 6.00, NULL, NULL, 'M'),
(211, 36, 80, 'fanta naranja mini', 1, 2.00, 1.40, 2.00, NULL, NULL, 'M'),
(212, 40, 80, 'pop naranja 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(213, 39, 80, 'pop piña  620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(214, 16, 81, 'eco ala brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(215, 17, 82, 'eco contra brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(216, 16, 82, 'eco ala brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(217, 15, 82, 'eco pierna brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(218, 14, 82, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(219, 10, 82, 'entero brasa', 1, 80.00, 70.00, 80.00, NULL, NULL, 'M'),
(220, 11, 82, '1/2 brasa', 1, 40.00, 30.00, 40.00, NULL, NULL, 'M'),
(221, 12, 82, '1/4  pecho brasa', 1, 20.00, 18.00, 20.00, NULL, NULL, 'M'),
(222, 64, 82, 'chicha 2l', 1, 12.00, 9.00, 12.00, NULL, NULL, 'M'),
(223, 15, 83, 'eco pierna brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(224, 14, 83, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(225, 14, 84, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(226, 16, 85, 'eco ala brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(227, 15, 85, 'eco pierna brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(228, 14, 86, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, 'Solo Fideo,No Fideo,No Papas', NULL, 'M'),
(229, 15, 86, 'eco pierna brasa', 1, 12.00, 10.00, 12.00, 'Solo Fideo,No Fideo', NULL, 'M'),
(230, 6, 86, 'eco pierna broasterd', 1, 12.00, 10.00, 12.00, 'Solo Arroz', NULL, 'M'),
(231, 73, 87, 'adfgfrdgrdgfgregrgdgdfgrgrtfgrtgrtgrtgrtgtrgtrgtrgtrgtrgtrgtrgtrgtrgtgrrtgrtgtrgtrg', 1, 50.00, 12.00, 50.00, NULL, NULL, 'M'),
(232, 14, 88, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(233, 14, 89, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(234, 17, 90, 'eco contra brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(235, 16, 90, 'eco ala brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(236, 15, 90, 'eco pierna brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(237, 14, 90, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(238, 13, 90, '1/4 pierna brasa', 1, 20.00, 18.00, 20.00, NULL, NULL, 'M'),
(239, 12, 90, '1/4  pecho brasa', 1, 20.00, 18.00, 20.00, NULL, NULL, 'M'),
(240, 11, 90, '1/2 brasa', 1, 40.00, 30.00, 40.00, NULL, NULL, 'M'),
(241, 10, 90, 'entero brasa', 1, 80.00, 70.00, 80.00, NULL, NULL, 'M'),
(242, 6, 90, 'eco pierna broasterd', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(243, 7, 90, 'eco ala broasterd', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(244, 8, 90, 'eco contra broasterd', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(245, 4, 90, '1/4 pierna broasterd', 1, 20.00, 18.00, 20.00, NULL, NULL, 'M'),
(246, 3, 90, '1/4 pecho broasterd', 1, 20.00, 18.00, 20.00, NULL, NULL, 'M'),
(247, 2, 90, '1/2 broasterd', 1, 40.00, 30.00, 40.00, NULL, NULL, 'M'),
(248, 69, 90, 'salchipapa simple', 1, 10.00, 7.00, 10.00, NULL, NULL, 'M'),
(249, 43, 90, 'pop guarana 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(250, 14, 91, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(251, 15, 91, 'eco pierna brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(252, 16, 91, 'eco ala brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(253, 17, 91, 'eco contra brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(254, 14, 92, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(255, 65, 92, 'tropi 600ml', 1, 6.00, 3.00, 6.00, NULL, NULL, 'M'),
(256, 14, 93, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(257, 14, 94, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, 'Sin fideo ❌,Sin arroz ❌,Sin papas ❌,Más arroz ✅', NULL, 'M'),
(258, 15, 95, 'eco pierna brasa', 1, 12.00, 10.00, 12.00, 'Sin fideo ❌,Más papas ✅', NULL, 'M'),
(259, 66, 95, 'pura vida de 3l ', 1, 18.00, 15.00, 18.00, NULL, NULL, 'M'),
(260, 69, 96, 'salchipapa simple', 1, 10.00, 7.00, 10.00, 'Sin papas ❌', NULL, 'M'),
(261, 15, 96, 'eco pierna brasa', 1, 12.00, 10.00, 12.00, 'Más fideo ✅', NULL, 'M'),
(262, 14, 96, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, 'Sin arroz ❌', NULL, 'M'),
(263, 64, 96, 'chicha 2l', 1, 12.00, 9.00, 12.00, NULL, NULL, 'M'),
(264, 19, 97, 'coca cola 2l', 1, 13.00, 11.00, 13.00, 'Más fideo ✅', NULL, 'M'),
(265, 12, 97, '1/4  pecho brasa', 1, 20.00, 18.00, 20.00, NULL, NULL, 'M'),
(266, 19, 98, 'coca cola 2l', 1, 13.00, 11.00, 13.00, 'Sin arroz ❌', NULL, 'M'),
(267, 14, 98, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(268, 14, 99, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, 'Sin papas ❌', NULL, 'M'),
(269, 63, 99, 'aquarios pomelo 2l', 1, 13.00, 11.00, 13.00, NULL, NULL, 'M'),
(270, 65, 100, 'tropi 600ml', 1, 6.00, 3.00, 6.00, 'Sin arroz ❌', NULL, 'M'),
(271, 7, 100, 'eco ala broasterd', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(272, 65, 101, 'tropi 600ml', 1, 6.00, 3.00, 6.00, 'Solo Fideo', NULL, 'M'),
(273, 5, 101, 'eco pecho broasterd', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(274, 14, 102, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(275, 14, 102, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, 'Sin arroz ❌', NULL, 'M'),
(276, 14, 102, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, 'Más arroz ✅', NULL, 'M'),
(277, 14, 102, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, 'Más arroz ✅,Solo papas', NULL, 'M'),
(278, 14, 103, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(279, 65, 103, 'tropi 600ml', 1, 6.00, 3.00, 6.00, NULL, NULL, 'M'),
(280, 14, 104, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(281, 37, 105, 'coca cola mini 190ml', 1, 2.00, 1.40, 2.00, 'Sin arroz ❌', NULL, 'M'),
(282, 17, 105, 'eco contra brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(283, 14, 106, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, 'Sin arroz ❌', NULL, 'M'),
(284, 41, 106, 'pop papaya 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(285, 14, 107, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(286, 37, 107, 'coca cola mini 190ml', 1, 2.00, 1.40, 2.00, NULL, NULL, 'M'),
(287, 14, 108, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, 'Sin arroz ❌', NULL, 'M'),
(288, 63, 108, 'aquarios pomelo 2l', 1, 13.00, 11.00, 13.00, 'Más arroz ✅,Más papas ✅', NULL, 'M'),
(289, 6, 108, 'eco pierna broasterd', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(290, 14, 109, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(291, 14, 110, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(292, 14, 111, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(293, 15, 112, 'eco pierna brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(294, 16, 112, 'eco ala brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(295, 14, 112, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(296, 10, 112, 'entero brasa', 1, 80.00, 70.00, 80.00, NULL, NULL, 'M'),
(297, 11, 112, '1/2 brasa', 1, 40.00, 30.00, 40.00, NULL, NULL, 'M'),
(298, 12, 112, '1/4  pecho brasa', 1, 20.00, 18.00, 20.00, NULL, NULL, 'M'),
(299, 13, 112, '1/4 pierna brasa', 1, 20.00, 18.00, 20.00, NULL, NULL, 'M'),
(300, 17, 112, 'eco contra brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(301, 64, 112, 'chicha 2l', 30, 12.00, 9.00, 360.00, NULL, NULL, 'M'),
(302, 66, 112, 'pura vida de 3l ', 1, 18.00, 15.00, 18.00, NULL, NULL, 'M'),
(303, 69, 112, 'salchipapa simple', 1, 10.00, 7.00, 10.00, NULL, NULL, 'M'),
(304, 65, 112, 'tropi 600ml', 1, 6.00, 3.00, 6.00, NULL, NULL, 'M'),
(305, 61, 112, 'aquarios pera 2l', 1, 13.00, 11.00, 13.00, NULL, NULL, 'M'),
(306, 62, 112, 'aquarios pomelo 3l', 1, 17.00, 15.00, 17.00, NULL, NULL, 'M'),
(307, 63, 112, 'aquarios pomelo 2l', 1, 13.00, 11.00, 13.00, NULL, NULL, 'M'),
(308, 60, 112, 'aquarios pera 3l', 1, 17.00, 15.00, 17.00, NULL, NULL, 'M'),
(309, 6, 112, 'eco pierna broasterd', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(310, 7, 112, 'eco ala broasterd', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(311, 8, 112, 'eco contra broasterd', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(312, 5, 112, 'eco pecho broasterd', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(313, 1, 112, 'entero broasterd', 1, 80.00, 70.00, 80.00, NULL, NULL, 'M'),
(314, 2, 112, '1/2 broasterd', 1, 40.00, 30.00, 40.00, NULL, NULL, 'M'),
(315, 3, 112, '1/4 pecho broasterd', 1, 20.00, 18.00, 20.00, NULL, NULL, 'M'),
(316, 4, 112, '1/4 pierna broasterd', 1, 20.00, 18.00, 20.00, NULL, NULL, 'M'),
(317, 14, 113, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(318, 69, 113, 'salchipapa simple', 1, 10.00, 7.00, 10.00, NULL, NULL, 'M'),
(319, 42, 113, 'pop manzana 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(320, 21, 113, 'retornable coca cola 2.5l', 1, 12.00, 7.00, 12.00, NULL, NULL, 'M'),
(321, 1, 114, 'entero broasterd', 500, 80.00, 70.00, 40000.00, NULL, NULL, 'M'),
(322, 7, 114, 'eco ala broasterd', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(323, 8, 114, 'eco contra broasterd', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(324, 14, 115, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(325, 14, 116, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(326, 14, 117, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(327, 14, 118, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(328, 14, 119, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(329, 14, 120, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(330, 15, 120, 'eco pierna brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(331, 14, 121, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(332, 14, 122, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(333, 14, 123, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(334, 14, 124, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(335, 14, 125, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(336, 5, 126, 'eco pecho broasterd', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(337, 14, 127, 'eco pecho brasa', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M');

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
(1, 's/n', 'sin carnet', '000-00-000', 'sin direccion', 1002, '2025-04-25 16:04:16', '2025-04-25 20:04:16', 1),
(2, 'Pablo', '14231231', '61214123', 'sin direccion', 3, '2025-04-18 13:45:40', '2025-04-18 17:45:40', 1),
(3, 'luis marcos galarza rivero', '9818074as', ' 705-65-454', 'av la salles', 1, '2025-04-17 15:49:39', '2025-04-17 19:49:39', 1);

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
(1, 1, '101', 'entero broasterd', 'vistas/img/productos/101/814.jpg', 0, 80, 70, 504, '2025-04-20 21:57:52', 0, 1),
(2, 1, '102', '1/2 broasterd', 'vistas/img/productos/102/393.jpg', 43, 40, 30, 7, '2025-04-20 21:35:43', 0, 1),
(3, 1, '103', '1/4 pecho broasterd', 'vistas/img/productos/103/562.jpg', 44, 20, 18, 6, '2025-04-20 21:35:43', 0, 1),
(4, 1, '108', '1/4 pierna broasterd', 'vistas/img/productos/108/876.jpg', 46, 20, 18, 4, '2025-04-20 21:35:43', 0, 1),
(5, 1, '104', 'eco pecho broasterd', 'vistas/img/productos/104/986.jpg', 37, 12, 10, 13, '2025-04-25 19:56:24', 0, 1),
(6, 1, '105', 'eco pierna broasterd', 'vistas/img/productos/105/136.jpg', 43, 12, 10, 7, '2025-04-20 21:35:43', 0, 1),
(7, 1, '106', 'eco ala broasterd', 'vistas/img/productos/106/524.jpg', 43, 12, 10, 7, '2025-04-20 21:57:52', 0, 1),
(8, 1, '107', 'eco contra broasterd', 'vistas/img/productos/107/140.jpg', 45, 12, 10, 5, '2025-04-20 21:57:52', 0, 1),
(10, 2, '201', 'entero brasa', 'vistas/img/productos/201/518.png', 41, 80, 70, 9, '2025-04-20 21:35:43', 0, 1),
(11, 2, '202', '1/2 brasa', 'vistas/img/productos/202/592.png', 45, 40, 30, 5, '2025-04-20 21:35:43', 0, 1),
(12, 2, '203', '1/4  pecho brasa', 'vistas/img/productos/203/989.png', 43, 20, 18, 7, '2025-04-20 21:35:43', 0, 1),
(13, 2, '204', '1/4 pierna brasa', 'vistas/img/productos/204/129.png', 46, 20, 18, 4, '2025-04-20 21:35:43', 0, 1),
(14, 2, '205', 'eco pecho brasa', 'vistas/img/productos/205/937.jpg', 5, 12, 10, 95, '2025-04-25 20:04:16', 0, 1),
(15, 2, '206', 'eco pierna brasa', 'vistas/img/productos/206/586.png', 25, 12, 10, 25, '2025-04-25 16:45:17', 0, 1),
(16, 2, '207', 'eco ala brasa', 'vistas/img/productos/207/314.jpg', 35, 12, 10, 15, '2025-04-20 21:35:43', 0, 1),
(17, 2, '208', 'eco contra brasa', 'vistas/img/productos/208/425.png', 40, 12, 10, 10, '2025-04-20 21:35:43', 0, 1),
(18, 3, '301', 'coca cola 3l', 'vistas/img/productos/301/316.jpg', 50, 17, 15, 0, '2025-01-07 02:39:59', 1, 1),
(19, 3, '302', 'coca cola 2l', 'vistas/img/productos/302/532.jpg', 46, 13, 11, 4, '2025-04-20 19:26:42', 1, 1),
(20, 3, '303', 'coca cola 600ml', 'vistas/img/productos/303/492.jpg', 48, 6, 3, 2, '2025-04-20 03:05:43', 1, 1),
(21, 3, '304', 'retornable coca cola 2.5l', 'vistas/img/productos/304/739.jpg', 48, 12, 7, 2, '2025-04-20 21:48:41', 1, 1),
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
(33, 3, '316', 'pepsi 2l', 'vistas/img/productos/316/938.jpg', 47, 10, 9, 3, '2025-04-20 08:48:43', 1, 1),
(34, 3, '317', 'pepsi 1l', 'vistas/img/productos/317/343.png', 47, 6, 3, 3, '2025-04-20 08:48:43', 1, 1),
(35, 3, '318', 'mendocina papaya 1l', 'vistas/img/productos/318/539.png', 47, 6, 5, 3, '2025-04-20 08:48:43', 1, 1),
(36, 3, '326', 'fanta naranja mini', 'vistas/img/productos/326/408.jpg', 48, 2, 1.4, 3, '2025-04-20 08:48:43', 1, 1),
(37, 3, '325', 'coca cola mini 190ml', 'vistas/img/productos/325/287.jpg', 43, 2, 1.4, 7, '2025-04-20 20:24:33', 1, 1),
(38, 3, '324', 'pop uva 620ml', 'vistas/img/productos/324/957.jpg', 47, 3, 1.8, 3, '2025-04-20 08:48:43', 1, 1),
(39, 3, '319', 'pop piña  620ml', 'vistas/img/productos/319/217.jpg', 47, 3, 1.8, 3, '2025-04-20 08:48:43', 1, 1),
(40, 3, '320', 'pop naranja 620ml', 'vistas/img/productos/320/896.jpg', 47, 3, 1.8, 3, '2025-04-20 08:48:43', 1, 1),
(41, 3, '321', 'pop papaya 620ml', 'vistas/img/productos/321/665.jpg', 44, 3, 1.8, 6, '2025-04-20 20:23:35', 1, 1),
(42, 3, '322', 'pop manzana 620ml', 'vistas/img/productos/322/283.jpg', 45, 3, 1.8, 5, '2025-04-20 21:48:41', 1, 1),
(43, 3, '323', 'pop guarana 620ml', 'vistas/img/productos/323/386.jpg', 45, 3, 1.8, 5, '2025-04-20 09:32:16', 1, 1),
(58, 3, '324', 'pop uva 620ml', 'vistas/img/productos/324/957.jpg', 47, 3, 1.8, 3, '2025-04-20 08:48:43', 1, 1),
(59, 4, '401', 'valle 3l  naranja', 'vistas/img/productos/401/147.jpg', 47, 17, 14.5, 3, '2025-04-20 08:48:43', 1, 1),
(60, 4, '402', 'aquarios pera 3l', 'vistas/img/productos/402/399.jpg', 44, 17, 15, 6, '2025-04-20 21:35:43', 1, 1),
(61, 4, '403', 'aquarios pera 2l', 'vistas/img/productos/403/956.jpg', 46, 13, 11, 4, '2025-04-20 21:35:43', 1, 1),
(62, 4, '404', 'aquarios pomelo 3l', 'vistas/img/productos/404/930.jpg', 46, 17, 15, 4, '2025-04-20 21:35:43', 1, 1),
(63, 4, '405', 'aquarios pomelo 2l', 'vistas/img/productos/405/715.png', 44, 13, 11, 6, '2025-04-20 21:35:43', 1, 1),
(64, 4, '406', 'chicha 2l', 'vistas/img/productos/406/636.png', 15, 12, 9, 35, '2025-04-20 21:35:43', 1, 1),
(65, 4, '407', 'tropi 600ml', 'vistas/img/productos/407/356.jpg', 39, 6, 3, 11, '2025-04-20 21:35:43', 1, 1),
(66, 4, '408', 'pura vida de 3l ', 'vistas/img/productos/408/764.jpg', 45, 18, 15, 6, '2025-04-20 21:35:43', 1, 1),
(69, 5, '501', 'salchipapa simple', 'vistas/img/productos/501/488.png', 500, 10, 7, 11, '2025-04-20 21:57:15', 0, 1),
(70, 6, '601', 'adfgfrdgrdgfgregrgdgdfgrgrtfgrtgrtgrtgrtgtrgtrgtrgtrgtrgtrgtrgtrgtrgtgrrtgrtgtrgtrg', 'vistas/img/productos/default/anonymous.png', 50, 50, 12, 0, '2025-04-20 09:15:59', 0, 0),
(71, 4, '409', 'naranja', 'vistas/img/productos/409/585.jpg', 50, 7, 5, 0, '2025-04-14 19:47:10', 0, 0),
(72, 6, '601', 'adfgfrdgrdgfgregrgdgdfgrgrtfgrtgrtgrtgrtgtrgtrgtrgtrgtrgtrgtrgtrgtrgtgrrtgrtgtrgtrg', 'vistas/img/productos/default/anonymous.png', 50, 50, 12, 0, '2025-04-20 09:15:59', 0, 0),
(73, 6, '601', 'adfgfrdgrdgfgregrgdgdfgrgrtfgrtgrtgrtgrtgtrgtrgtrgtrgtrgtrgtrgtrgtrgtgrrtgrtgtrgtrg', 'vistas/img/productos/default/anonymous.png', 49, 50, 12, 1, '2025-04-20 09:39:46', 0, 0);

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
(1, 's/n', 'coca cola', '67709910', 'sin direccion', '2025-01-06 01:59:38', 1),
(2, 'victor hino', 'coca cola', '67709910', 'sin direccion', '2025-01-06 01:59:47', 1),
(3, 'alex', 'dmlap', '75029230', 'av la salles', '2025-04-15 21:10:06', 1);

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
(1, 'rosmery quizpe', 'admin', '$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG', 'Administrador', 'vistas/img/usuarios/admin/202.png', 1, '2025-04-25 21:29:33', '2025-04-26 01:29:33', 1),
(2, 'luis hidalgo', 'luis10', '$2a$07$asxx54ahjppf45sd87a5auGZEtGHuyZwm.Ur.FJvWLCql3nmsMbXy', 'Vendedor', '', 1, '2025-04-20 16:42:10', '2025-04-20 20:42:10', 1),
(3, 'edwin yamil', 'edwin', '$2a$07$asxx54ahjppf45sd87a5auGZEtGHuyZwm.Ur.FJvWLCql3nmsMbXy', 'Especial', '', 1, '2025-04-19 23:35:23', '2025-04-20 03:35:23', 1),
(4, 'enrique', 'enrique', '$2a$07$asxx54ahjppf45sd87a5auLSGQafrhT1q/TDAijaGwwOMXE8ecr.q', 'Administrador', '', 1, '2025-03-07 12:07:11', '2025-03-07 16:07:11', 1),
(5, 'mario terrazas moye', '121wsdewd', '$2a$07$asxx54ahjppf45sd87a5auGZEtGHuyZwm.Ur.FJvWLCql3nmsMbXy', 'Administrador', 'vistas/img/usuarios/121wsdewd/433.jpg', 0, '0000-00-00 00:00:00', '2025-04-14 17:40:30', 0);

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
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `codigo`, `nro_ticket`, `total`, `total_pagado`, `fecha`, `nota`, `tipo_pago`, `cambio`, `forma_atencion`, `estado`, `id_mesero`, `id_cliente`, `id_vendedor`, `id_arqueo_caja`) VALUES
(1, 00000001, NULL, 12, 12, '2025-04-14 18:17:45', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 1),
(2, 00000002, NULL, 12, 12, '2025-04-14 18:44:29', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 1),
(3, 00000003, NULL, 12, 12, '2025-04-14 20:18:31', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 1),
(4, 00000004, NULL, 12, 12, '2025-04-14 20:18:58', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 1),
(5, 00000005, NULL, 12, 23, '2025-04-14 20:19:11', '', 'Efectivo', 11, 'En Mesa', 1, 1, 1, 1, 1),
(6, 00000006, NULL, 12, 15, '2025-04-14 21:34:29', '', 'Efectivo', 3, 'En Mesa', 1, 1, 1, 1, 1),
(7, 00000007, NULL, 12, 12, '2025-04-14 21:34:54', '', 'Efectivo', 0, 'Para Llevar', 1, 1, 1, 1, 1),
(8, 00000008, NULL, 33, 100, '2025-04-14 21:36:40', '', 'Efectivo', 67, 'En Mesa', 1, 1, 2, 1, 1),
(9, 00000009, NULL, 12, 12, '2025-04-15 20:56:29', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 2),
(10, 00000010, NULL, 12, 12, '2025-04-15 21:08:58', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 2),
(11, 00000001, NULL, 10, 10, '2025-04-15 21:15:42', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 3),
(12, 00000002, NULL, 24, 30, '2025-04-15 21:20:58', 'PREPARAR RAPIDO', 'Efectivo', 6, 'Mixto', 1, 1, 1, 1, 3),
(13, 00000003, NULL, 24, 24, '2025-04-15 21:22:36', 'PREPARAR RAPIDO', 'Efectivo', 0, 'Mixto', 1, 1, 1, 1, 3),
(14, 00000004, NULL, 24, 10000000000000, '2025-04-15 21:23:53', '', 'Efectivo', 10000000000000, 'Para Llevar', 1, 1, 1, 1, 3),
(15, 00000005, NULL, 24, 2000000000000, '2025-04-15 21:25:00', '', 'Efectivo', 2000000000000, 'En Mesa', 1, 1, 1, 1, 3),
(16, 00000006, NULL, 12, 500000, '2025-04-15 21:25:16', '', 'Efectivo', 499988, 'En Mesa', 1, 1, 1, 1, 3),
(17, 00000007, NULL, 24, 3566670, '2025-04-15 21:25:32', '', 'Efectivo', 3566640, 'En Mesa', 1, 1, 1, 1, 3),
(18, 00000008, NULL, 24, 3566670, '2025-04-15 21:27:31', '', 'Efectivo', 3566650, 'En Mesa', 1, 1, 1, 1, 3),
(19, 00000009, NULL, 12, 1200, '2025-04-15 21:29:41', '', 'Efectivo', 1188, 'En Mesa', 1, 1, 1, 1, 3),
(20, 00000010, NULL, 60, 1500, '2025-04-15 21:30:04', '', 'Efectivo', 1440, 'En Mesa', 1, 1, 1, 1, 3),
(21, 00000011, NULL, 48, 1212, '2025-04-15 21:30:47', '', 'Efectivo', 1164, 'En Mesa', 1, 1, 1, 1, 3),
(22, 00000012, NULL, 586, 590, '2025-04-15 21:31:39', '', 'Efectivo', 4, 'En Mesa', 1, 1, 1, 1, 3),
(23, 00000013, NULL, 18, 20, '2025-04-15 21:32:12', '', 'Efectivo', 2, 'En Mesa', 1, 1, 1, 1, 3),
(24, 00000014, NULL, 18, 20, '2025-04-15 21:32:42', '', 'Efectivo', 2, 'En Mesa', 1, 1, 1, 1, 3),
(25, 00000015, NULL, 80, 80, '2025-04-15 21:32:47', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 3),
(26, 00000001, NULL, 12, 12, '2025-04-16 17:25:59', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 4),
(27, 00000002, NULL, 12, 12, '2025-04-16 17:26:15', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 4),
(28, 00000003, NULL, 80, 80, '2025-04-16 17:26:27', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 4),
(29, 00000004, NULL, 3, 3, '2025-04-16 17:26:39', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 4),
(30, 00000001, NULL, 12, 12, '2025-04-17 17:39:21', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 8),
(31, 00000002, NULL, 12, 12, '2025-04-17 17:39:33', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 8),
(32, 00000001, NULL, 12, 12, '2025-04-17 17:55:13', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 9),
(33, 00000002, NULL, 12, 12, '2025-04-17 19:32:35', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 9),
(34, 00000003, NULL, 12, 12, '2025-04-17 19:32:52', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 9),
(35, 00000004, NULL, 12, 12, '2025-04-17 19:47:33', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 9),
(36, 00000005, NULL, 12, 12, '2025-04-17 19:47:39', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 9),
(37, 00000006, NULL, 12, 12, '2025-04-17 19:47:47', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 9),
(38, 00000007, NULL, 12, 12, '2025-04-17 19:47:53', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 9),
(39, 00000008, NULL, 12, 12, '2025-04-17 19:47:59', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 9),
(40, 00000009, NULL, 6, 12, '2025-04-17 19:48:07', '', 'Efectivo', 6, 'En Mesa', 1, 1, 1, 1, 9),
(41, 00000010, NULL, 12, 12, '2025-04-17 19:48:12', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 9),
(42, 00000011, NULL, 12, 12, '2025-04-17 19:48:26', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 9),
(43, 00000012, NULL, 12, 80, '2025-04-17 19:48:31', '', 'Efectivo', 68, 'En Mesa', 1, 1, 1, 1, 9),
(44, 00000013, NULL, 12, 12, '2025-04-17 19:48:38', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 9),
(45, 00000014, NULL, 80, 80, '2025-04-17 19:48:48', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 9),
(46, 00000015, NULL, 12, 12, '2025-04-17 19:49:16', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 9),
(47, 00000016, NULL, 120, 500, '2025-04-17 19:49:29', '', 'Efectivo', 380, 'En Mesa', 1, 1, 1, 1, 9),
(48, 00000017, NULL, 12, 12, '2025-04-17 19:49:39', '', 'Efectivo', 0, 'En Mesa', 1, 3, 3, 1, 9),
(49, 00000018, NULL, 12, 12, '2025-04-17 19:49:46', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 9),
(50, 00000019, NULL, 12, 50, '2025-04-17 19:49:53', '', 'Efectivo', 38, 'En Mesa', 1, 1, 1, 1, 9),
(51, 00000020, NULL, 12, 50, '2025-04-17 20:02:02', '', 'Efectivo', 38, 'En Mesa', 1, 2, 4, 1, 9),
(52, 00000021, NULL, 24, 50, '2025-04-17 20:15:15', '', 'Efectivo', 26, 'Mixto', 1, 1, 1, 1, 9),
(53, 00000022, NULL, 18, 20, '2025-04-18 17:45:40', 'PREPAREN RAPIDO', 'Efectivo', 2, 'En Mesa', 1, 2, 5, 1, 10),
(54, 00000023, NULL, 12, 12, '2025-04-20 02:50:05', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 11),
(55, 00000024, NULL, 12, 12, '2025-04-20 03:02:01', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 11),
(56, 00000025, NULL, 18, 100, '2025-04-20 03:05:43', '', 'Efectivo', 82, 'En Mesa', 1, 1, 6, 1, 11),
(57, 00000026, NULL, 12, 12, '2025-04-20 03:08:32', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 11),
(58, 00000027, NULL, 12, 12, '2025-04-20 03:10:01', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 11),
(59, 00000028, NULL, 12, 12, '2025-04-20 03:23:56', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 11),
(60, 00000029, NULL, 12, 12, '2025-04-20 03:58:07', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 11),
(61, 00000001, NULL, 24, 24, '2025-04-20 04:19:09', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 13),
(62, 00000002, NULL, 12, 50, '2025-04-20 04:23:45', '', 'Efectivo', 38, 'En Mesa', 1, 1, 1, 1, 13),
(63, 00000003, NULL, 12, 12, '2025-04-20 04:31:29', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 13),
(64, 00000004, NULL, 12, 50, '2025-04-20 05:00:22', '', 'Efectivo', 38, 'En Mesa', 1, 1, 1, 1, 13),
(65, 00000005, NULL, 24, 50, '2025-04-20 05:06:02', '', 'Efectivo', 26, 'Mixto', 1, 1, 1, 1, 13),
(66, 00000006, NULL, 27, 50, '2025-04-20 05:47:11', '', 'Efectivo', 23, 'En Mesa', 1, 1, 1, 1, 13),
(67, 00000007, NULL, 12, 12, '2025-04-20 06:16:21', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 13),
(68, 00000008, NULL, 12, 12, '2025-04-20 06:22:27', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 13),
(69, 00000009, NULL, 79, 80, '2025-04-20 06:22:58', '', 'Efectivo', 1, 'En Mesa', 1, 1, 1, 1, 13),
(70, 00000010, NULL, 26, 50, '2025-04-20 06:23:51', '', 'Efectivo', 24, 'En Mesa', 1, 1, 1, 1, 13),
(71, 00000011, NULL, 14, 50, '2025-04-20 07:38:35', '', 'Efectivo', 36, 'En Mesa', 1, 1, 1, 1, 13),
(72, 00000012, NULL, 12, 50, '2025-04-20 07:50:40', '', 'Efectivo', 38, 'En Mesa', 1, 1, 1, 1, 13),
(73, 00000013, NULL, 12, 40, '2025-04-20 08:22:22', '', 'Efectivo', 28, 'En Mesa', 1, 1, 1, 1, 13),
(74, 00000014, NULL, 12, 50, '2025-04-20 08:25:14', '', 'Efectivo', 38, 'En Mesa', 1, 1, 1, 1, 13),
(75, 00000015, NULL, 12, 50, '2025-04-20 08:27:08', '', 'Efectivo', 38, 'En Mesa', 1, 1, 1, 1, 13),
(76, 00000016, NULL, 210, 500, '2025-04-20 08:29:31', '', 'Efectivo', 290, 'En Mesa', 1, 1, 1, 1, 13),
(77, 00000017, NULL, 194, 200, '2025-04-20 08:30:32', '', 'Efectivo', 6, 'En Mesa', 1, 1, 1, 1, 13),
(78, 00000018, NULL, 24, 50, '2025-04-20 08:40:32', '', 'Efectivo', 26, 'En Mesa', 1, 1, 1, 1, 13),
(79, 00000019, NULL, 12, 50, '2025-04-20 08:46:42', '', 'Efectivo', 38, 'En Mesa', 1, 1, 1, 1, 13),
(80, 00000020, NULL, 586, 1000, '2025-04-20 08:48:43', '', 'Efectivo', 414, 'En Mesa', 1, 1, 1, 1, 13),
(81, 00000021, NULL, 12, 50, '2025-04-20 08:52:26', '', 'Efectivo', 38, 'En Mesa', 1, 1, 1, 1, 13),
(82, 00000022, NULL, 200, 5000, '2025-04-20 09:04:59', '', 'Efectivo', 4800, 'En Mesa', 1, 1, 1, 1, 13),
(83, 00000023, NULL, 24, 50, '2025-04-20 09:06:10', '', 'Efectivo', 26, 'En Mesa', 1, 1, 1, 1, 13),
(84, 00000024, NULL, 12, 50, '2025-04-20 09:06:30', '', 'Efectivo', 38, 'En Mesa', 1, 1, 1, 1, 13),
(85, 00000025, NULL, 24, 50, '2025-04-20 09:06:37', '', 'Efectivo', 26, 'En Mesa', 1, 1, 1, 1, 13),
(86, 00000026, NULL, 36, 80, '2025-04-20 09:08:05', '', 'Efectivo', 44, 'En Mesa', 1, 1, 1, 1, 13),
(87, 00000027, NULL, 50, 50, '2025-04-20 09:16:06', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 13),
(88, 00000028, NULL, 12, 80, '2025-04-20 09:17:59', '', 'Efectivo', 68, 'En Mesa', 1, 1, 1, 1, 13),
(89, 00000029, NULL, 12, 50, '2025-04-20 09:30:59', '', 'Efectivo', 38, 'En Mesa', 1, 1, 1, 1, 13),
(90, 00000030, NULL, 337, 500, '2025-04-20 09:32:16', '', 'Efectivo', 163, 'En Mesa', 1, 1, 1, 1, 13),
(91, 00000031, NULL, 48, 560, '2025-04-20 09:32:56', '', 'Efectivo', 512, 'En Mesa', 1, 1, 1, 1, 13),
(92, 00000032, NULL, 18, 20, '2025-04-20 18:42:07', '', 'Efectivo', 2, 'En Mesa', 1, 1, 1, 1, 13),
(93, 00000033, NULL, 12, 40, '2025-04-20 19:08:40', '', 'Efectivo', 28, 'En Mesa', 1, 1, 1, 1, 13),
(94, 00000034, NULL, 12, 12, '2025-04-20 19:20:21', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 13),
(95, 00000035, NULL, 30, 50, '2025-04-20 19:23:16', '', 'Efectivo', 38, 'En Mesa', 1, 1, 1, 1, 13),
(96, 00000036, NULL, 46, 50, '2025-04-20 19:23:52', '', 'Efectivo', 4, 'En Mesa', 1, 1, 1, 1, 13),
(97, 00000037, NULL, 33, 100, '2025-04-20 19:25:13', '', 'Efectivo', 67, 'En Mesa', 1, 1, 1, 1, 13),
(98, 00000038, NULL, 25, 50, '2025-04-20 19:26:42', '', 'Efectivo', 25, 'En Mesa', 1, 1, 1, 1, 13),
(99, 00000039, NULL, 25, 50, '2025-04-20 19:27:06', '', 'Efectivo', 25, 'En Mesa', 1, 1, 1, 1, 13),
(100, 00000040, NULL, 18, 80, '2025-04-20 19:28:43', '', 'Efectivo', 62, 'En Mesa', 1, 1, 1, 1, 13),
(101, 00000041, NULL, 18, 50, '2025-04-20 19:32:53', '', 'Efectivo', 32, 'En Mesa', 1, 1, 1, 1, 13),
(102, 00000042, NULL, 48, 50, '2025-04-20 19:36:53', '', 'Efectivo', 2, 'En Mesa', 1, 1, 1, 1, 13),
(103, 00000043, NULL, 18, 50, '2025-04-20 19:39:20', '', 'Efectivo', 32, 'En Mesa', 1, 1, 1, 1, 13),
(104, 00000044, NULL, 12, 12, '2025-04-20 20:22:26', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 13),
(105, 00000045, NULL, 14, 50, '2025-04-20 20:23:06', '', 'Efectivo', 36, 'En Mesa', 1, 1, 1, 1, 13),
(106, 00000046, NULL, 15, 50, '2025-04-20 20:23:35', '', 'Efectivo', 35, 'En Mesa', 1, 1, 1, 1, 13),
(107, 00000047, NULL, 14, 15, '2025-04-20 20:24:33', '', 'Efectivo', 1, 'En Mesa', 1, 1, 1, 1, 13),
(108, 00000048, NULL, 37, 50, '2025-04-20 20:26:52', '', 'Efectivo', 13, 'En Mesa', 1, 1, 1, 1, 13),
(109, 00000001, NULL, 12, 12, '2025-04-20 20:50:34', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 17),
(110, 00000002, NULL, 12, 12, '2025-04-20 21:12:25', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 17),
(111, 00000003, NULL, 12, 12, '2025-04-20 21:13:45', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 17),
(112, 00000004, NULL, 870, 10000, '2025-04-20 21:35:43', '', 'Efectivo', 9130, 'En Mesa', 1, 1, 1, 1, 17),
(113, 00000005, NULL, 37, 50, '2025-04-20 21:48:41', '', 'Efectivo', 13, 'En Mesa', 1, 1, 1, 1, 17),
(114, 00000006, NULL, 40024, 50000, '2025-04-20 21:57:52', '', 'Efectivo', 9976, 'En Mesa', 1, 1, 1, 1, 17),
(115, 00000007, NULL, 12, 12, '2025-04-22 20:19:58', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 18),
(116, 00000008, NULL, 12, 12, '2025-04-22 20:21:48', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 18),
(117, 00000009, NULL, 12, 12, '2025-04-22 20:23:08', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 18),
(118, 00000010, NULL, 12, 12, '2025-04-23 18:59:39', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 19),
(119, 00000011, NULL, 12, 12, '2025-04-25 16:38:32', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 20),
(120, 00000012, NULL, 24, 50, '2025-04-25 16:45:17', '', 'Efectivo', 26, 'En Mesa', 1, 1, 1, 1, 20),
(121, 00000013, NULL, 12, 12, '2025-04-25 17:00:57', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 20),
(122, 00000014, NULL, 12, 12, '2025-04-25 18:24:00', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 20),
(123, 00000015, NULL, 12, 12, '2025-04-25 18:24:48', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 20),
(124, 00000016, NULL, 12, 12, '2025-04-25 18:26:36', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 20),
(125, 00000017, NULL, 12, 20, '2025-04-25 18:27:54', '', 'Efectivo', 8, 'En Mesa', 1, 1, 1, 1, 20),
(126, 00000018, NULL, 12, 50, '2025-04-25 19:56:24', '', 'Efectivo', 38, 'En Mesa', 1, 1, 1, 1, 20),
(127, 00000019, NULL, 12, 12, '2025-04-25 20:04:16', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 20);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=338;

--
-- AUTO_INCREMENT de la tabla `meseros`
--
ALTER TABLE `meseros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

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
