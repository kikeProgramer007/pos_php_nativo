-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-05-2025 a las 20:36:13
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
(1, '2025-05-12 02:47:21', '2025-05-12 02:54:20', 0, 1, 0, 1, 0, 0, 1, 0, 0, 0, 122.00, 0.00, 122.00, 0.00, 0.00, 0.00, 122.00, 122.00, 0.00, 'cerrada', 1, NULL, 1, 1),
(2, '2025-05-12 02:54:53', '2025-05-18 14:50:00', 1, 2, 3, 2, 4, 7, 2, 15, 4, 5, 567.00, 120.00, 687.00, 0.00, 0.00, 0.00, 687.00, 687.00, 0.00, 'cerrada', 3, NULL, 1, 1),
(3, '2025-05-18 15:54:44', '2025-05-18 16:02:23', 1, 1, 2, 4, 2, 4, 7, 2, 3, 6, 559.00, 338.70, 897.70, 0.00, 359.00, 359.00, 538.70, 538.70, 0.00, 'cerrada', 1, NULL, 1, 1),
(4, '2025-05-18 17:00:25', '2025-05-18 17:01:03', 0, 0, 0, 1, 2, 0, 0, 0, 0, 0, 26.00, 0.00, 26.00, 0.00, 0.00, 0.00, 26.00, 40.00, 14.00, 'cerrada', 1, NULL, 1, 1),
(5, '2025-05-18 17:06:56', '2025-05-18 17:07:21', 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 13.00, 0.00, 13.00, 0.00, 0.00, 0.00, 13.00, 2.00, -11.00, 'cerrada', 1, NULL, 1, 1),
(6, '2025-05-18 20:40:02', '2025-05-18 20:50:09', 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0.00, 26.20, 26.20, 0.00, 164.00, 164.00, -137.80, 20.00, 157.80, 'cerrada', 0, NULL, 1, 1),
(7, '2025-05-18 20:55:37', '2025-05-18 21:06:50', 0, 1, 0, 0, 0, 2, 0, 0, 0, 0, 283.00, 26.20, 309.20, 0.00, 172.00, 172.00, 137.20, 110.00, -247.20, 'cerrada', 1, NULL, 1, 1),
(8, '2025-05-18 21:40:35', '2025-05-18 22:11:53', 0, 1, 1, 2, 0, 1, 2, 0, 0, 0, 52.00, 57.00, 109.00, 0.00, 318.80, 318.80, -209.80, 199.00, 0.00, 'cerrada', 1, NULL, 1, 1),
(9, '2025-05-18 22:12:02', '2025-05-18 22:24:39', 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 26.00, 30.00, 56.00, 0.00, 34.00, 34.00, 22.00, 20.00, -2.00, 'cerrada', 1, NULL, 1, 1),
(10, '2025-05-18 22:25:10', '2025-05-18 22:32:13', 0, 0, 0, 2, 1, 0, 0, 0, 0, 0, 12.00, 35.00, 47.00, 0.00, 0.00, 0.00, 47.00, 50.00, 3.00, 'cerrada', 1, NULL, 1, 1),
(11, '2025-05-18 22:32:27', '2025-05-18 22:55:28', 0, 0, 1, 1, 1, 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 150.00, 150.00, -150.00, 80.00, -70.00, 'cerrada', 0, NULL, 1, 1),
(12, '2025-05-18 22:55:44', '2025-05-18 22:57:34', 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 26.00, 0.00, 26.00, 0.00, 0.00, 0.00, 26.00, 100.00, 74.00, 'cerrada', 1, NULL, 1, 1),
(13, '2025-05-24 17:12:29', '2025-05-25 10:30:08', 3, 0, 1, 2, 0, 0, 0, 1, 1, 0, 820.00, 1.50, 821.50, 120.00, 10.00, 130.00, 691.50, 691.50, 0.00, 'cerrada', 6, NULL, 1, 1),
(14, '2025-05-25 11:43:11', '2025-05-25 14:22:02', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 182.00, 25.50, 207.50, 57.82, 0.00, 57.82, 149.68, 200.00, 50.32, 'cerrada', 1, NULL, 1, 1),
(15, '2025-05-25 14:31:37', NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.00, 125.00, 125.00, 45.52, 0.00, 45.52, 79.48, 0.00, 0.00, 'abierta', 0, NULL, 1, 1);

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
(1, 'Caja de ventas', '1', 0, 1),
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

--
-- Volcado de datos para la tabla `compras`
--

INSERT INTO `compras` (`id`, `codigo`, `total`, `id_usuario`, `id_proveedor`, `fecha_alta`, `estado`, `id_arqueo_caja`) VALUES
(1, 1, 50.00, 1, 1, '2025-05-12 06:50:23', 0, 1),
(2, 2, 40.00, 1, 1, '2025-05-12 06:50:11', 0, 1),
(3, 3, 50.00, 1, 1, '2025-05-12 06:53:44', 0, 1),
(4, 4, 359.00, 1, 2, '2025-05-18 19:57:05', 1, 3),
(5, 5, 10.00, 1, 2, '2025-05-19 00:46:02', 0, 6),
(6, 6, 164.00, 1, 1, '2025-05-19 00:48:45', 1, 6),
(7, 7, 110.00, 1, 1, '2025-05-25 14:14:28', 0, 6),
(8, 8, 20.00, 1, 1, '2025-05-19 00:58:51', 1, 7),
(9, 9, 152.00, 1, 1, '2025-05-19 00:59:12', 1, 7),
(10, 10, 108.00, 1, 1, '2025-05-19 01:42:39', 1, 8),
(11, 11, 200.00, 1, 1, '2025-05-19 01:57:37', 1, 8),
(12, 12, 10.80, 1, 1, '2025-05-19 02:15:11', 1, 8),
(13, 13, 12.00, 1, 1, '2025-05-19 02:15:41', 1, 9),
(14, 14, 22.00, 1, 1, '2025-05-19 02:16:17', 1, 9),
(15, 15, 150.00, 1, 1, '2025-05-19 02:32:53', 1, 11),
(16, 16, 20.00, 1, 1, '2025-05-25 13:55:18', 0, 13),
(17, 17, 10.00, 1, 1, '2025-05-25 13:54:49', 1, 13);

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
(1, 71, 1, 'valle 1l manzana', 5, 10.00, 50.00),
(2, 65, 2, 'tropi 600ml', 1, 3.00, 3.00),
(3, 63, 2, 'aquarios pomelo 2l', 1, 11.00, 11.00),
(4, 62, 2, 'aquarios pomelo 3l', 1, 15.00, 15.00),
(5, 61, 2, 'aquarios pera 2l', 1, 11.00, 11.00),
(6, 71, 3, 'valle 1l manzana', 5, 10.00, 50.00),
(7, 71, 4, 'valle 1l manzana', 20, 10.00, 200.00),
(8, 66, 4, 'valle 1l durazno', 10, 10.00, 100.00),
(9, 65, 4, 'tropi 600ml', 10, 3.00, 30.00),
(10, 18, 4, 'coca cola 3l', 1, 15.00, 15.00),
(11, 19, 4, 'coca cola 2l', 1, 11.00, 11.00),
(12, 20, 4, 'coca cola 600ml', 1, 3.00, 3.00),
(13, 71, 5, 'valle 1l manzana', 1, 10.00, 10.00),
(14, 71, 6, 'valle 1l manzana', 2, 10.00, 20.00),
(15, 66, 6, 'valle 1l durazno', 2, 10.00, 20.00),
(16, 65, 6, 'tropi 600ml', 3, 3.00, 9.00),
(17, 63, 6, 'aquarios pomelo 2l', 2, 11.00, 22.00),
(18, 62, 6, 'aquarios pomelo 3l', 4, 15.00, 60.00),
(19, 61, 6, 'aquarios pera 2l', 3, 11.00, 33.00),
(20, 71, 7, 'valle 1l manzana', 1, 10.00, 10.00),
(21, 66, 7, 'valle 1l durazno', 1, 10.00, 10.00),
(22, 65, 7, 'tropi 600ml', 4, 3.00, 12.00),
(23, 63, 7, 'aquarios pomelo 2l', 1, 11.00, 11.00),
(24, 62, 7, 'aquarios pomelo 3l', 3, 15.00, 45.00),
(25, 61, 7, 'aquarios pera 2l', 2, 11.00, 22.00),
(26, 71, 8, 'valle 1l manzana', 1, 10.00, 10.00),
(27, 66, 8, 'valle 1l durazno', 1, 10.00, 10.00),
(28, 66, 9, 'valle 1l durazno', 2, 10.00, 20.00),
(29, 65, 9, 'tropi 600ml', 2, 3.00, 6.00),
(30, 63, 9, 'aquarios pomelo 2l', 4, 11.00, 44.00),
(31, 62, 9, 'aquarios pomelo 3l', 4, 15.00, 60.00),
(32, 61, 9, 'aquarios pera 2l', 2, 11.00, 22.00),
(33, 71, 10, 'valle 1l manzana', 1, 10.00, 10.00),
(34, 66, 10, 'valle 1l durazno', 1, 10.00, 10.00),
(35, 65, 10, 'tropi 600ml', 12, 3.00, 36.00),
(36, 62, 10, 'aquarios pomelo 3l', 2, 15.00, 30.00),
(37, 61, 10, 'aquarios pera 2l', 2, 11.00, 22.00),
(38, 71, 11, 'valle 1l manzana', 20, 10.00, 200.00),
(39, 65, 12, 'tropi 600ml', 3, 3.00, 9.00),
(40, 42, 12, 'pop manzana 620ml', 1, 1.00, 1.80),
(41, 65, 13, 'tropi 600ml', 4, 3.00, 12.00),
(42, 65, 14, 'tropi 600ml', 4, 3.00, 12.00),
(43, 66, 14, 'valle 1l durazno', 1, 10.00, 10.00),
(44, 71, 15, 'valle 1l manzana', 11, 10.00, 110.00),
(45, 66, 15, 'valle 1l durazno', 4, 10.00, 40.00),
(46, 66, 16, 'valle 1l durazno', 2, 10.00, 20.00),
(47, 66, 17, 'valle 1l durazno', 1, 10.00, 10.00);

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
(2, 15, 1, 'eco ala brasa', 2, 13.00, 10.00, 26.00, NULL, NULL, 'M'),
(3, 16, 1, 'eco pierna brasa', 1, 13.00, 10.00, 13.00, 'Sin fideo ❌,Sin papas ❌,Más arroz ✅,Más papas ✅', 'teste', 'M'),
(4, 17, 1, 'eco contra brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(5, 11, 1, '1/2 brasa', 1, 45.00, 30.00, 45.00, NULL, NULL, 'M'),
(6, 41, 1, 'pop papaya 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(7, 40, 1, 'pop naranja 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(8, 42, 1, 'pop manzana 620ml', 2, 3.00, 1.80, 6.00, NULL, NULL, 'M'),
(9, 14, 2, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(10, 15, 2, 'eco ala brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(11, 14, 3, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(12, 15, 3, 'eco ala brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(13, 16, 3, 'eco pierna brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(14, 10, 4, 'entero brasa', 2, 90.00, 70.00, 180.00, NULL, NULL, 'LL'),
(15, 11, 4, '1/2 brasa', 1, 45.00, 30.00, 45.00, NULL, NULL, 'M'),
(16, 12, 4, '1/4  pecho brasa', 5, 23.00, 18.00, 115.00, NULL, NULL, 'LL'),
(17, 13, 4, '1/4 pierna brasa', 6, 23.00, 18.00, 138.00, NULL, NULL, 'LL'),
(18, 40, 4, 'pop naranja 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'LL'),
(19, 41, 4, 'pop papaya 620ml', 4, 3.00, 1.80, 12.00, NULL, NULL, 'M'),
(20, 42, 4, 'pop manzana 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'LL'),
(21, 43, 4, 'pop guarana 620ml', 2, 3.00, 1.80, 6.00, NULL, NULL, 'LL'),
(22, 15, 5, 'eco ala brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(23, 14, 5, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, 'Solo arroz', NULL, 'LL'),
(24, 16, 5, 'eco pierna brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(25, 10, 5, 'entero brasa', 1, 90.00, 70.00, 90.00, NULL, NULL, 'M'),
(26, 11, 5, '1/2 brasa', 1, 45.00, 30.00, 45.00, NULL, NULL, 'M'),
(27, 12, 5, '1/4  pecho brasa', 1, 23.00, 18.00, 23.00, 'Sin arroz ❌,Sin papas ❌', NULL, 'LL'),
(28, 13, 5, '1/4 pierna brasa', 1, 23.00, 18.00, 23.00, NULL, NULL, 'M'),
(29, 17, 5, 'eco contra brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(30, 43, 5, 'pop guarana 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(31, 42, 5, 'pop manzana 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(32, 41, 5, 'pop papaya 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(33, 40, 5, 'pop naranja 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'LL'),
(34, 36, 5, 'fanta naranja mini', 1, 2.00, 1.40, 2.00, NULL, NULL, 'M'),
(35, 37, 5, 'coca cola mini 190ml', 1, 2.00, 1.40, 2.00, NULL, NULL, 'M'),
(36, 38, 5, 'pop uva 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(37, 39, 5, 'pop piña  620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(38, 31, 5, 'simba piña 2l', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(39, 32, 5, 'simba durazno 2l', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(40, 34, 5, 'pepsi 1l', 1, 7.00, 3.00, 7.00, NULL, NULL, 'M'),
(41, 20, 5, 'coca cola 600ml', 1, 6.00, 3.00, 6.00, NULL, NULL, 'M'),
(42, 19, 5, 'coca cola 2l', 1, 13.00, 11.00, 13.00, NULL, NULL, 'M'),
(43, 21, 5, 'retornable coca cola 2.5l', 1, 12.00, 7.00, 12.00, NULL, NULL, 'M'),
(44, 23, 5, 'fanta naranja 2l', 2, 13.00, 11.00, 26.00, NULL, NULL, 'M'),
(45, 24, 5, 'fanta naranja 600ml', 1, 6.00, 3.00, 6.00, NULL, NULL, 'M'),
(46, 25, 5, 'fanta papaya 3l', 1, 17.00, 15.00, 17.00, NULL, NULL, 'M'),
(47, 26, 5, 'fanta papaya 2l', 4, 13.00, 11.00, 52.00, NULL, NULL, 'M'),
(48, 22, 5, 'fanta naranja 3l', 1, 17.00, 15.00, 17.00, NULL, NULL, 'M'),
(49, 18, 5, 'coca cola 3l', 1, 17.00, 15.00, 17.00, NULL, NULL, 'M'),
(50, 71, 5, 'valle 1l manzana', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(51, 66, 5, 'valle 1l durazno', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(52, 65, 5, 'tropi 600ml', 1, 6.00, 3.00, 6.00, NULL, NULL, 'M'),
(53, 63, 5, 'aquarios pomelo 2l', 1, 13.00, 11.00, 13.00, NULL, NULL, 'M'),
(54, 61, 5, 'aquarios pera 2l', 1, 13.00, 11.00, 13.00, NULL, NULL, 'M'),
(55, 60, 5, 'aquarios pera 3l', 1, 17.00, 15.00, 17.00, NULL, NULL, 'M'),
(56, 59, 5, 'valle 3l  naranja', 1, 17.00, 14.50, 17.00, NULL, NULL, 'M'),
(57, 62, 5, 'aquarios pomelo 3l', 1, 17.00, 15.00, 17.00, NULL, NULL, 'M'),
(58, 14, 6, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(59, 15, 6, 'eco ala brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(60, 15, 7, 'eco ala brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(61, 14, 8, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(62, 10, 8, 'entero brasa', 3, 90.00, 70.00, 270.00, NULL, NULL, 'M'),
(63, 17, 9, 'eco contra brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(64, 16, 9, 'eco pierna brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(65, 14, 9, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(66, 15, 9, 'eco ala brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(67, 14, 10, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(68, 15, 10, 'eco ala brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(69, 71, 11, 'valle 1l manzana', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(70, 14, 12, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(71, 15, 12, 'eco ala brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(72, 14, 13, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, 'Sin fideo ❌,Sin arroz ❌,Sin papas ❌,Más arroz ✅,Más fideo ✅,Más papas ✅,Solo papas,Poco fideo', 'hola', 'M'),
(73, 38, 14, 'pop uva 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(74, 37, 14, 'coca cola mini 190ml', 1, 2.00, 1.40, 2.00, NULL, NULL, 'M'),
(75, 36, 14, 'fanta naranja mini', 1, 2.00, 1.40, 2.00, NULL, NULL, 'M'),
(76, 14, 15, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(77, 15, 15, 'eco ala brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(78, 14, 16, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'LL'),
(79, 15, 16, 'eco ala brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(80, 16, 16, 'eco pierna brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(81, 6, 17, 'eco ala broasterd', 3, 13.00, 10.00, 39.00, 'Sin fideo ❌,Más arroz ✅', NULL, 'LL'),
(82, 5, 17, 'eco pecho broasterd', 1, 13.00, 10.00, 13.00, NULL, NULL, 'LL'),
(83, 1, 17, 'entero broasterd', 1, 90.00, 70.00, 90.00, NULL, NULL, 'M'),
(84, 72, 18, 'porcion de arroz', 1, 7.00, 4.00, 7.00, NULL, NULL, 'M'),
(85, 71, 18, 'valle 1l manzana', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(86, 73, 18, 'porcion de papa fritas', 1, 7.00, 4.00, 7.00, NULL, NULL, 'M'),
(87, 74, 18, 'porcion de fideo', 1, 7.00, 4.00, 7.00, NULL, NULL, 'M'),
(88, 60, 18, 'aquarios pera 3l', 1, 17.00, 15.00, 17.00, NULL, NULL, 'M'),
(89, 61, 18, 'aquarios pera 2l', 1, 13.00, 11.00, 13.00, NULL, NULL, 'M'),
(90, 63, 18, 'aquarios pomelo 2l', 1, 13.00, 11.00, 13.00, NULL, NULL, 'M'),
(91, 65, 18, 'tropi 600ml', 1, 6.00, 3.00, 6.00, NULL, NULL, 'M'),
(92, 59, 18, 'valle 3l  naranja', 1, 17.00, 14.50, 17.00, NULL, NULL, 'M'),
(93, 66, 18, 'valle 1l durazno', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(94, 69, 18, 'salchipapa simple', 1, 12.00, 7.00, 12.00, NULL, NULL, 'M'),
(95, 62, 18, 'aquarios pomelo 3l', 1, 17.00, 15.00, 17.00, NULL, NULL, 'M'),
(96, 32, 18, 'simba durazno 2l', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(97, 31, 18, 'simba piña 2l', 1, 12.00, 10.00, 12.00, NULL, NULL, 'M'),
(98, 34, 18, 'pepsi 1l', 1, 7.00, 3.00, 7.00, NULL, NULL, 'M'),
(99, 35, 18, 'mendocina papaya 1l', 1, 7.00, 5.00, 7.00, NULL, NULL, 'M'),
(100, 39, 18, 'pop piña  620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(101, 38, 18, 'pop uva 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(102, 37, 18, 'coca cola mini 190ml', 1, 2.00, 1.40, 2.00, NULL, NULL, 'M'),
(103, 36, 18, 'fanta naranja mini', 1, 2.00, 1.40, 2.00, NULL, NULL, 'M'),
(104, 40, 18, 'pop naranja 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(105, 41, 18, 'pop papaya 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(106, 42, 18, 'pop manzana 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(107, 43, 18, 'pop guarana 620ml', 1, 3.00, 1.80, 3.00, NULL, NULL, 'M'),
(108, 1, 18, 'entero broasterd', 1, 90.00, 70.00, 90.00, NULL, NULL, 'M'),
(109, 2, 18, '1/2 broasterd', 1, 45.00, 30.00, 45.00, NULL, NULL, 'M'),
(110, 3, 18, '1/4 pecho broasterd', 1, 23.00, 18.00, 23.00, NULL, NULL, 'M'),
(111, 4, 18, '1/4 pierna broasterd', 1, 23.00, 18.00, 23.00, NULL, NULL, 'M'),
(112, 5, 18, 'eco pecho broasterd', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(113, 18, 18, 'coca cola 3l', 1, 17.00, 15.00, 17.00, NULL, NULL, 'M'),
(114, 17, 18, 'eco contra brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(115, 16, 18, 'eco pierna brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(116, 15, 18, 'eco ala brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(117, 11, 18, '1/2 brasa', 1, 45.00, 30.00, 45.00, NULL, NULL, 'M'),
(118, 13, 18, '1/4 pierna brasa', 1, 23.00, 18.00, 23.00, NULL, NULL, 'M'),
(119, 14, 18, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(120, 12, 18, '1/4  pecho brasa', 1, 23.00, 18.00, 23.00, NULL, NULL, 'M'),
(121, 7, 18, ' eco pierna broasterd', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(122, 8, 18, 'eco contra broasterd', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(123, 6, 18, 'eco ala broasterd', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(124, 14, 19, 'eco pecho brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(125, 15, 19, 'eco ala brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M'),
(126, 16, 19, 'eco pierna brasa', 11, 13.00, 10.00, 143.00, NULL, NULL, 'M'),
(127, 17, 19, 'eco contra brasa', 1, 13.00, 10.00, 13.00, NULL, NULL, 'M');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gastos`
--

CREATE TABLE `gastos` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `descripcion` varchar(500) DEFAULT NULL,
  `monto` decimal(11,2) NOT NULL DEFAULT 0.00,
  `forma_pago` varchar(100) NOT NULL,
  `id_tipo_gasto` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_arqueo` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Volcado de datos para la tabla `gastos`
--

INSERT INTO `gastos` (`id`, `fecha`, `descripcion`, `monto`, `forma_pago`, `id_tipo_gasto`, `id_usuario`, `id_arqueo`) VALUES
(2, '2025-05-24', 'Se pago a matias ', 120.00, '2', 4, 1, 13),
(3, '2025-05-25', 'se pago', 25.50, '1', 2, 1, 14),
(4, '2025-05-25', 'reparacion del lavamanos', 32.32, '1', 3, 1, 14),
(5, '2025-05-25', 'descripcion', 7.00, '1', 2, 1, 0),
(6, '2025-05-25', 'TESTE', 45.52, '3', 2, 1, 15);

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
(1, 's/n', 'sin carnet', '000-00-000', 'sin direccion', 161, '2025-05-25 14:21:51', '2025-05-25 18:21:51', 1),
(2, '1', '1', ' 1__-__-___', '1', 0, '0000-00-00 00:00:00', '2025-05-25 12:29:26', 1);

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
(1, 1, '101', 'entero broasterd entero broasterd entero broasterd ', 'vistas/img/productos/101/884.webp', 48, 90, 70, 2, '2025-05-25 18:00:48', 0, 1),
(2, 1, '102', '1/2 broasterd', 'vistas/img/productos/102/847.webp', 49, 45, 30, 1, '2025-05-25 02:03:44', 0, 1),
(3, 1, '103', '1/4 pecho broasterd', 'vistas/img/productos/103/138.webp', 49, 23, 18, 1, '2025-05-25 02:03:44', 0, 1),
(4, 1, '108', '1/4 pierna broasterd', 'vistas/img/productos/108/247.webp', 49, 23, 18, 1, '2025-05-25 02:03:44', 0, 1),
(5, 1, '104', 'eco pecho broasterd', 'vistas/img/productos/104/897.webp', 48, 13, 10, 2, '2025-05-25 02:03:44', 0, 1),
(6, 1, '106', 'eco ala broasterd', 'vistas/img/productos/106/670.webp', 46, 13, 10, 4, '2025-05-25 02:03:44', 0, 1),
(7, 1, '105', ' eco pierna broasterd', 'vistas/img/productos/105/476.webp', 49, 13, 10, 1, '2025-05-25 02:03:44', 0, 1),
(8, 1, '107', 'eco contra broasterd', 'vistas/img/productos/107/294.webp', 49, 13, 10, 1, '2025-05-25 02:03:44', 0, 1),
(10, 2, '201', 'entero brasa', 'vistas/img/productos/201/261.webp', 44, 90, 70, 6, '2025-05-19 01:06:11', 0, 1),
(11, 2, '202', '1/2 brasa', 'vistas/img/productos/202/826.webp', 46, 45, 30, 4, '2025-05-25 02:03:44', 0, 1),
(12, 2, '203', '1/4  pecho brasa', 'vistas/img/productos/203/459.webp', 43, 23, 18, 7, '2025-05-25 02:03:44', 0, 1),
(13, 2, '204', '1/4 pierna brasa', 'vistas/img/productos/204/610.webp', 42, 23, 18, 8, '2025-05-25 02:03:44', 0, 1),
(14, 2, '205', 'eco pecho brasa', 'vistas/img/productos/205/622.webp', 36, 13, 10, 14, '2025-05-25 18:21:51', 0, 1),
(15, 2, '206', 'eco ala brasa', 'vistas/img/productos/206/334.png', 36, 13, 10, 14, '2025-05-25 18:21:51', 0, 1),
(16, 2, '207', 'eco pierna brasa', 'vistas/img/productos/207/304.webp', 33, 13, 10, 17, '2025-05-25 18:21:51', 0, 1),
(17, 2, '208', 'eco contra brasa', 'vistas/img/productos/208/618.webp', 45, 13, 10, 5, '2025-05-25 18:21:51', 0, 1),
(18, 3, '301', 'coca cola 3l', 'vistas/img/productos/301/586.webp', 49, 17, 15, 2, '2025-05-25 02:03:44', 1, 1),
(19, 3, '302', 'coca cola 2l', 'vistas/img/productos/302/757.webp', 50, 13, 11, 1, '2025-05-18 19:57:05', 1, 1),
(20, 3, '303', 'coca cola 600ml', 'vistas/img/productos/303/248.webp', 50, 6, 3, 1, '2025-05-18 19:57:05', 1, 1),
(21, 3, '304', 'retornable coca cola 2.5l', 'vistas/img/productos/304/116.webp', 49, 12, 7, 1, '2025-05-18 19:55:55', 1, 1),
(22, 3, '305', 'fanta naranja 3l', 'vistas/img/productos/305/612.webp', 49, 17, 15, 1, '2025-05-18 19:55:55', 1, 1),
(23, 3, '306', 'fanta naranja 2l', 'vistas/img/productos/306/628.webp', 48, 13, 11, 2, '2025-05-18 19:55:55', 1, 1),
(24, 3, '307', 'fanta naranja 600ml', 'vistas/img/productos/307/828.webp', 49, 6, 3, 1, '2025-05-18 19:55:55', 1, 1),
(25, 3, '308', 'fanta papaya 3l', 'vistas/img/productos/308/132.webp', 49, 17, 15, 1, '2025-05-18 19:55:55', 1, 1),
(26, 3, '309', 'fanta papaya 2l', 'vistas/img/productos/309/885.webp', 46, 13, 11, 4, '2025-05-18 19:55:55', 1, 1),
(27, 3, '310', 'fanta guarana 3l', 'vistas/img/productos/310/726.webp', 50, 17, 15, 0, '2025-05-12 03:03:08', 1, 1),
(28, 3, '311', 'fanta guarana 2l', 'vistas/img/productos/311/328.webp', 50, 13, 11, 0, '2025-05-12 03:03:24', 1, 1),
(29, 3, '312', 'sprite 600ml', 'vistas/img/productos/312/220.webp', 50, 6, 3, 0, '2025-05-12 03:03:45', 1, 1),
(30, 3, '313', 'simba manzana 2l', 'vistas/img/productos/313/175.webp', 50, 12, 10, 0, '2025-05-12 03:04:45', 1, 1),
(31, 3, '314', 'simba piña 2l', 'vistas/img/productos/314/492.webp', 48, 12, 10, 2, '2025-05-25 02:03:44', 1, 1),
(32, 3, '315', 'simba durazno 2l', 'vistas/img/productos/315/973.webp', 48, 12, 10, 2, '2025-05-25 02:03:44', 1, 1),
(33, 3, '316', 'pepsi 2l', 'vistas/img/productos/316/642.webp', 50, 10, 9, 0, '2025-05-12 03:34:55', 1, 0),
(34, 3, '317', 'pepsi 1l', 'vistas/img/productos/317/756.webp', 48, 7, 3, 2, '2025-05-25 02:03:44', 1, 1),
(35, 3, '318', 'mendocina papaya 1l', 'vistas/img/productos/318/561.webp', 49, 7, 5, 1, '2025-05-25 02:03:44', 1, 1),
(36, 3, '326', 'fanta naranja mini', 'vistas/img/productos/326/635.webp', 47, 2, 1.4, 3, '2025-05-25 02:03:44', 1, 1),
(37, 3, '325', 'coca cola mini 190ml', 'vistas/img/productos/325/287.jpg', 47, 2, 1.4, 3, '2025-05-25 02:03:44', 1, 1),
(38, 3, '324', 'pop uva 620ml', 'vistas/img/productos/324/767.webp', 47, 3, 1.8, 3, '2025-05-25 02:03:44', 1, 1),
(39, 3, '319', 'pop piña  620ml', 'vistas/img/productos/319/719.webp', 48, 3, 1.8, 2, '2025-05-25 02:03:44', 1, 1),
(40, 3, '320', 'pop naranja 620ml', 'vistas/img/productos/320/429.webp', 46, 3, 1.8, 4, '2025-05-25 02:03:44', 1, 1),
(41, 3, '321', 'pop papaya 620ml', 'vistas/img/productos/321/710.webp', 43, 3, 1.8, 7, '2025-05-25 02:03:44', 1, 1),
(42, 3, '322', 'pop manzana 620ml', 'vistas/img/productos/322/983.webp', 46, 3, 1.8, 5, '2025-05-25 02:03:44', 1, 1),
(43, 3, '323', 'pop guarana 620ml', 'vistas/img/productos/323/698.webp', 46, 3, 1.8, 4, '2025-05-25 02:03:44', 1, 1),
(59, 4, '401', 'valle 3l  naranja', 'vistas/img/productos/401/178.webp', 48, 17, 14.5, 2, '2025-05-25 02:03:44', 1, 1),
(60, 4, '402', 'aquarios pera 3l', 'vistas/img/productos/402/979.webp', 48, 17, 15, 2, '2025-05-25 02:03:44', 1, 1),
(61, 4, '403', 'aquarios pera 2l', 'vistas/img/productos/403/838.webp', 55, 13, 11, 2, '2025-05-25 14:14:28', 1, 1),
(62, 4, '404', 'aquarios pomelo 3l', 'vistas/img/productos/404/845.webp', 58, 17, 15, 2, '2025-05-25 14:14:28', 1, 1),
(63, 4, '405', 'aquarios pomelo 2l', 'vistas/img/productos/405/871.webp', 54, 13, 11, 2, '2025-05-25 14:14:28', 1, 1),
(64, 4, '406', 'chicha 2l', 'vistas/img/productos/406/903.webp', 50, 12, 9, 0, '2025-05-12 03:31:47', 1, 0),
(65, 4, '407', 'tropi 600ml', 'vistas/img/productos/407/166.webp', 86, 6, 3, 2, '2025-05-25 14:14:28', 1, 1),
(66, 4, '408', 'valle 1l durazno', 'vistas/img/productos/408/174.webp', 70, 12, 10, 2, '2025-05-25 14:14:28', 1, 1),
(69, 5, '501', 'salchipapa simple', 'vistas/img/productos/501/916.webp', 49, 12, 7, 1, '2025-05-25 02:03:44', 0, 1),
(71, 4, '409', 'valle 1l manzana', 'vistas/img/productos/409/600.webp', 102, 12, 10, 3, '2025-05-25 14:14:28', 1, 1),
(72, 6, '601', 'porcion de arroz', 'vistas/img/productos/601/268.webp', 49, 7, 4, 1, '2025-05-25 02:03:44', 0, 1),
(73, 6, '602', 'porcion de papa fritas', 'vistas/img/productos/602/228.webp', 49, 7, 4, 1, '2025-05-25 02:03:44', 0, 1),
(74, 6, '603', 'porcion de fideo', 'vistas/img/productos/603/620.webp', 49, 7, 4, 1, '2025-05-25 02:03:44', 0, 1);

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
-- Estructura de tabla para la tabla `tipo_gasto`
--

CREATE TABLE `tipo_gasto` (
  `id` int(11) NOT NULL,
  `nombre` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Volcado de datos para la tabla `tipo_gasto`
--

INSERT INTO `tipo_gasto` (`id`, `nombre`) VALUES
(1, 'Servicios (luz, agua, internet)'),
(2, 'Sueldos'),
(3, 'Reparaciones'),
(4, 'Otros');

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
(1, 'rosmery quizpe', 'admin', '$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG', 'Administrador', 'vistas/img/usuarios/admin/545.png', 1, '2025-05-24 21:21:23', '2025-05-25 01:21:23', 1),
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
(1, 1, NULL, 122, 122, '2025-05-12 06:51:30', 'PRUEBA', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 1),
(2, 1, NULL, 26, 33, '2025-05-18 18:45:06', '', 'Efectivo', 7, 'En Mesa', 1, 1, 1, 1, 2),
(3, 2, NULL, 39, 45, '2025-05-18 18:47:27', '', 'Efectivo', 6, 'En Mesa', 1, 1, 1, 1, 2),
(4, 3, NULL, 502, 520, '2025-05-18 18:48:28', '', 'Efectivo', 18, 'Mixto', 1, 1, 1, 1, 2),
(5, 1, NULL, 559, 600, '2025-05-18 19:55:55', 'RAPIDO', 'Efectivo', 41, 'Mixto', 1, 1, 1, 1, 3),
(6, 1, NULL, 26, 30, '2025-05-18 21:00:36', '', 'Efectivo', 4, 'En Mesa', 1, 1, 1, 1, 4),
(7, 1, NULL, 13, 123, '2025-05-18 21:07:04', '', 'Efectivo', 110, 'En Mesa', 1, 1, 1, 1, 5),
(8, 1, NULL, 283, 333, '2025-05-19 01:06:11', '', 'Efectivo', 50, 'En Mesa', 1, 1, 1, 1, 7),
(9, 1, NULL, 52, 70, '2025-05-19 01:40:53', '', 'Efectivo', 18, 'En Mesa', 1, 1, 1, 1, 8),
(10, 1, NULL, 26, 33, '2025-05-19 02:12:16', '', 'Efectivo', 7, 'En Mesa', 1, 1, 1, 1, 9),
(11, 1, NULL, 12, 12, '2025-05-19 02:25:27', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 10),
(12, 1, NULL, 26, 33, '2025-05-19 02:56:55', '', 'Efectivo', 7, 'En Mesa', 1, 1, 1, 1, 12),
(13, 1, NULL, 13, 13, '2025-05-24 21:17:55', 'DAR PRIORIDAD A ESTE TICKET', 'QR', 0, 'Mixto', 1, 1, 1, 1, 13),
(14, 2, NULL, 7, 7, '2025-05-24 21:39:42', '', 'Transferencia', 0, 'Mixto', 1, 1, 1, 1, 13),
(15, 3, NULL, 26, 26, '2025-05-24 21:40:27', '', 'Efectivo', 0, 'En Mesa', 1, 1, 1, 1, 13),
(16, 4, NULL, 39, 40, '2025-05-24 21:41:09', '', 'Efectivo', 1, 'Mixto', 1, 1, 1, 1, 13),
(17, 5, NULL, 142, 150, '2025-05-25 01:23:48', 'LISTO PARA 1/2 HORA', 'QR', 8, 'Mixto', 1, 1, 1, 1, 13),
(18, 6, NULL, 593, 600, '2025-05-25 02:03:44', '', 'Qr y Efectivo(Mixto)', 7, 'En Mesa', 1, 1, 1, 1, 13),
(19, 1, NULL, 182, 200, '2025-05-25 18:21:51', '', 'Efectivo', 18, 'En Mesa', 1, 1, 1, 1, 14);

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
-- Indices de la tabla `gastos`
--
ALTER TABLE `gastos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_gastos_tipo_gasto` (`id_tipo_gasto`) USING BTREE,
  ADD KEY `fk_gastos_id_usuario` (`id_usuario`) USING BTREE,
  ADD KEY `fk_gastos_id_arqueo` (`id_arqueo`);

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
-- Indices de la tabla `tipo_gasto`
--
ALTER TABLE `tipo_gasto`
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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT de la tabla `gastos`
--
ALTER TABLE `gastos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
-- AUTO_INCREMENT de la tabla `tipo_gasto`
--
ALTER TABLE `tipo_gasto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

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
