-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 24, 2026 at 01:09 AM
-- Server version: 9.6.0
-- PHP Version: 8.5.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pos_php_nativo-main`
--

-- --------------------------------------------------------

--
-- Table structure for table `arqueo_caja`
--

CREATE TABLE `arqueo_caja` (
  `id` bigint UNSIGNED NOT NULL,
  `fecha_apertura` datetime DEFAULT NULL,
  `fecha_cierre` datetime DEFAULT NULL,
  `Bs200` int NOT NULL DEFAULT '0',
  `Bs100` int NOT NULL DEFAULT '0',
  `Bs50` int NOT NULL DEFAULT '0',
  `Bs20` int NOT NULL DEFAULT '0',
  `Bs10` int NOT NULL DEFAULT '0',
  `Bs5` int NOT NULL DEFAULT '0',
  `Bs2` int NOT NULL DEFAULT '0',
  `Bs1` int NOT NULL DEFAULT '0',
  `Bs050` int NOT NULL DEFAULT '0',
  `Bs020` int NOT NULL DEFAULT '0',
  `monto_ventas_efectivo` decimal(11,2) DEFAULT '0.00',
  `monto_ventas_qr` decimal(11,2) DEFAULT '0.00',
  `monto_ventas` decimal(11,2) NOT NULL DEFAULT '0.00',
  `monto_apertura` decimal(11,2) NOT NULL DEFAULT '0.00',
  `total_ingresos` decimal(11,2) NOT NULL DEFAULT '0.00',
  `gastos_operativos` decimal(11,2) NOT NULL DEFAULT '0.00',
  `monto_compras` decimal(11,2) NOT NULL DEFAULT '0.00',
  `total_egresos` decimal(11,2) NOT NULL DEFAULT '0.00',
  `resultado_neto` decimal(11,2) NOT NULL DEFAULT '0.00',
  `efectivo_en_caja` decimal(11,2) NOT NULL DEFAULT '0.00',
  `qr_en_caja` decimal(11,2) NOT NULL DEFAULT '0.00',
  `total_efectivo_qr_en_caja` decimal(11,2) NOT NULL DEFAULT '0.00',
  `diferencia` decimal(11,2) NOT NULL DEFAULT '0.00',
  `cuentas_pendientes_cantidad` int NOT NULL DEFAULT '0' COMMENT 'Cantidad de cuentas con estado_pago PENDIENTE al momento del cierre',
  `cuentas_pendientes_total` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT 'Total por cobrar de cuentas pendientes al momento del cierre',
  `estado` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nroTicket` int NOT NULL,
  `tipo_cambio` decimal(11,2) DEFAULT NULL,
  `id_caja` int NOT NULL,
  `id_usuario` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `arqueo_caja`
--

INSERT INTO `arqueo_caja` (`id`, `fecha_apertura`, `fecha_cierre`, `Bs200`, `Bs100`, `Bs50`, `Bs20`, `Bs10`, `Bs5`, `Bs2`, `Bs1`, `Bs050`, `Bs020`, `monto_ventas_efectivo`, `monto_ventas_qr`, `monto_ventas`, `monto_apertura`, `total_ingresos`, `gastos_operativos`, `monto_compras`, `total_egresos`, `resultado_neto`, `efectivo_en_caja`, `qr_en_caja`, `total_efectivo_qr_en_caja`, `diferencia`, `cuentas_pendientes_cantidad`, `cuentas_pendientes_total`, `estado`, `nroTicket`, `tipo_cambio`, `id_caja`, `id_usuario`) VALUES
(1, '2026-08-02 20:24:11', '2026-08-02 20:49:26', 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 135.00, 30.00, 165.00, 0.00, 165.00, 0.00, 29500.00, 29500.00, -29335.00, 70.00, 0.00, 70.00, 29405.00, 0, 0.00, 'cerrada', 8, NULL, 1, 1),
(2, '2026-08-02 20:49:55', '2026-08-06 14:15:17', 4, 1, 1, 1, 1, 0, 0, 0, 0, 0, 980.00, 0.00, 980.00, 0.00, 980.00, 0.00, 0.00, 0.00, 980.00, 980.00, 0.00, 980.00, 0.00, 0, 0.00, 'cerrada', 6, NULL, 1, 1),
(3, '2026-08-06 14:33:59', '2026-08-06 18:02:01', 2, 1, 1, 0, 0, 0, 0, 0, 0, 0, 550.00, 0.00, 550.00, 0.00, 550.00, 0.00, 0.00, 0.00, 550.00, 550.00, 0.00, 550.00, 0.00, 0, 0.00, 'cerrada', 12, NULL, 1, 1),
(4, '2026-08-06 18:03:07', '2026-08-06 18:26:13', 1, 2, 1, 0, 1, 1, 2, 5, 0, 0, 572.00, 0.00, 572.00, 0.00, 572.00, 0.00, 100.00, 100.00, 472.00, 474.00, 0.00, 474.00, 2.00, 0, 0.00, 'cerrada', 9, NULL, 1, 1),
(5, '2026-08-06 18:38:40', '2026-08-23 18:15:43', 2, 10, 2, 2, 1, 0, 1, 1, 0, 0, 1533.00, 0.00, 1533.00, 0.00, 1553.00, 0.00, 0.00, 0.00, 1553.00, 1553.00, 0.00, 1553.00, 0.00, 0, 0.00, 'cerrada', 23, NULL, 1, 1),
(6, '2026-08-23 18:17:17', '2026-08-24 00:07:18', 18, 15, 8, 0, 4, 1, 2, 0, 0, 0, 5374.00, 0.00, 5374.00, 100.00, 5664.00, 116.00, 0.00, 116.00, 5548.00, 5549.00, 0.00, 5549.00, 1.00, 0, 0.00, 'cerrada', 17, NULL, 1, 1),
(7, '2026-08-24 00:07:53', '2026-08-24 00:08:33', 0, 0, 1, 0, 0, 1, 0, 0, 0, 0, 55.00, 0.00, 55.00, 0.00, 55.00, 0.00, 0.00, 0.00, 55.00, 55.00, 0.00, 55.00, 0.00, 0, 0.00, 'cerrada', 1, NULL, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cajas`
--

CREATE TABLE `cajas` (
  `id` int NOT NULL,
  `nombre` varchar(35) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `numero_caja` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `nro_ticket` int NOT NULL,
  `estado` tinyint NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

--
-- Dumping data for table `cajas`
--

INSERT INTO `cajas` (`id`, `nombre`, `numero_caja`, `nro_ticket`, `estado`) VALUES
(1, 'Caja de ventas', '1', 0, 1),
(2, 'Caja Administrativa', '2', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `categorias`
--

CREATE TABLE `categorias` (
  `id` int NOT NULL,
  `categoria` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `estado` tinyint NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

--
-- Dumping data for table `categorias`
--

INSERT INTO `categorias` (`id`, `categoria`, `fecha`, `estado`) VALUES
(1, 'postres', '2026-08-06 20:38:34', 1),
(2, 'bebidas', '2026-07-27 03:01:25', 1),
(3, 'sodas y refrescos', '2026-07-27 03:01:37', 1),
(4, 'extras', '2026-07-27 03:02:03', 1),
(5, 'sopa', '2026-07-27 03:02:23', 1),
(6, 'parillas', '2026-07-27 03:02:35', 1);

-- --------------------------------------------------------

--
-- Table structure for table `clientes`
--

CREATE TABLE `clientes` (
  `id` int NOT NULL,
  `nombre` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `estado` tinyint NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

--
-- Dumping data for table `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `fecha`, `estado`) VALUES
(1, 's/n', '2025-03-08 21:40:34', 1),
(2, 'marcos', '2026-08-03 00:30:05', 1),
(3, 'natalia saucedo', '2026-08-07 02:53:41', 1),
(4, 'sebas', '2026-08-07 02:57:21', 1),
(5, 'FULANITO', '2026-08-24 01:13:28', 1);

-- --------------------------------------------------------

--
-- Table structure for table `compras`
--

CREATE TABLE `compras` (
  `id` int NOT NULL,
  `codigo` int NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `id_usuario` int NOT NULL,
  `id_proveedor` int NOT NULL,
  `fecha_alta` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `id_arqueo_caja` bigint UNSIGNED DEFAULT NULL,
  `descontar_caja` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=resta de caja/egresos; 0=solo informativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `compras`
--

INSERT INTO `compras` (`id`, `codigo`, `total`, `id_usuario`, `id_proveedor`, `fecha_alta`, `estado`, `id_arqueo_caja`, `descontar_caja`) VALUES
(1, 1, 29500.00, 1, 1, '2026-07-27 04:35:06', 1, 1, 0),
(2, 2, 100.00, 1, 1, '2026-07-29 03:39:39', 1, 4, 0),
(3, 3, 18.00, 1, 1, '2026-08-24 03:45:34', 0, 6, 1),
(4, 4, 25.00, 1, 1, '2026-08-24 03:45:08', 1, 6, 0);

-- --------------------------------------------------------

--
-- Table structure for table `detalle_compra`
--

CREATE TABLE `detalle_compra` (
  `id` int NOT NULL,
  `id_producto` int NOT NULL,
  `id_compra` int NOT NULL,
  `producto` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `cantidad` int NOT NULL,
  `precio_compra` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detalle_compra`
--

INSERT INTO `detalle_compra` (`id`, `id_producto`, `id_compra`, `producto`, `cantidad`, `precio_compra`, `subtotal`) VALUES
(1, 21, 1, 'Chicha Jarra Grande', 50, 15.00, 750.00),
(2, 17, 1, 'Tropifrut', 60, 10.00, 600.00),
(3, 16, 1, 'Soda Personal', 50, 8.00, 400.00),
(4, 15, 1, 'Soda 2 Lt', 50, 20.00, 1000.00),
(5, 14, 1, 'Soda Popular', 50, 10.00, 500.00),
(6, 13, 1, 'Agua 500 ml', 50, 5.00, 250.00),
(7, 12, 1, 'Powerade 500 ml', 50, 5.00, 250.00),
(8, 11, 1, 'Powerade 1 Lt', 50, 10.00, 500.00),
(9, 10, 1, 'Agua con Gas (500 ml)', 50, 5.00, 250.00),
(10, 9, 1, 'Huari 620 ml', 50, 30.00, 1500.00),
(11, 1, 1, 'Paletas Q\' Deli', 50, 10.00, 500.00),
(12, 2, 1, 'Cheesecake de Oreo', 50, 10.00, 500.00),
(13, 3, 1, 'ron flor de caña', 50, 100.00, 5000.00),
(14, 4, 1, 'Ron Habana Club', 50, 150.00, 7500.00),
(15, 5, 1, 'Vino Kohlberg', 50, 40.00, 2000.00),
(16, 6, 1, 'Vino Campos del Solana', 50, 40.00, 2000.00),
(17, 7, 1, 'Balde de Coronas (5 unidades)', 50, 100.00, 5000.00),
(18, 8, 1, 'Corona', 50, 20.00, 1000.00),
(19, 8, 2, 'Corona', 5, 20.00, 100.00),
(20, 16, 3, 'Soda Personal', 1, 8.00, 8.00),
(21, 17, 3, 'Tropifrut', 1, 10.00, 10.00),
(22, 21, 4, 'Chicha Jarra Grande', 1, 15.00, 15.00),
(23, 17, 4, 'Tropifrut', 1, 10.00, 10.00);

-- --------------------------------------------------------

--
-- Table structure for table `detalle_venta`
--

CREATE TABLE `detalle_venta` (
  `id` int NOT NULL,
  `id_producto` int NOT NULL,
  `id_venta` int NOT NULL,
  `producto` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `cantidad` int NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL,
  `precio_original` decimal(10,2) DEFAULT NULL,
  `tipo_descuento` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `valor_descuento` decimal(10,2) DEFAULT NULL,
  `descuento_unitario` decimal(10,2) DEFAULT '0.00',
  `descuento_total` decimal(10,2) DEFAULT '0.00',
  `id_promocion` int DEFAULT NULL,
  `id_intervalo_promocion` int DEFAULT NULL,
  `nombre_promocion` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `precio_compra` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `preferencias` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nota_adicional` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `forma_atencion` char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detalle_venta`
--

INSERT INTO `detalle_venta` (`id`, `id_producto`, `id_venta`, `producto`, `cantidad`, `precio_venta`, `precio_original`, `tipo_descuento`, `valor_descuento`, `descuento_unitario`, `descuento_total`, `id_promocion`, `id_intervalo_promocion`, `nombre_promocion`, `precio_compra`, `subtotal`, `preferencias`, `nota_adicional`, `forma_atencion`) VALUES
(1, 8, 1, 'Corona', 1, 25.00, NULL, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 20.00, 25.00, NULL, NULL, 'M'),
(2, 1, 2, 'Paletas Q\' Deli', 1, 15.00, NULL, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 10.00, 15.00, NULL, NULL, 'M'),
(4, 8, 4, 'Corona', 1, 25.00, NULL, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 20.00, 25.00, NULL, NULL, 'M'),
(7, 1, 3, 'Paletas Q\' Deli', 1, 15.00, NULL, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 10.00, 15.00, NULL, NULL, 'LL'),
(8, 1, 5, 'Paletas Q\' Deli', 1, 15.00, NULL, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 10.00, 15.00, NULL, NULL, 'M'),
(9, 8, 6, 'Corona', 1, 25.00, NULL, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 20.00, 25.00, NULL, NULL, 'M'),
(10, 14, 7, 'Soda Popular', 1, 13.00, NULL, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 10.00, 13.00, NULL, NULL, 'M'),
(12, 9, 8, 'Huari 620 ml', 1, 32.00, NULL, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 30.00, 32.00, NULL, NULL, 'M'),
(13, 8, 9, 'Corona', 5, 25.00, 25.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 20.00, 125.00, NULL, NULL, 'M'),
(14, 8, 10, 'Corona', 1, 25.00, 25.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 20.00, 25.00, NULL, NULL, 'M'),
(16, 8, 11, 'Corona', 5, 24.00, 25.00, 'fijo', 1.00, 1.00, 5.00, 1, 1, 'balde de coronasssss', 20.00, 120.00, NULL, NULL, 'M'),
(17, 9, 12, 'Huari 620 ml', 10, 32.00, 32.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 30.00, 320.00, NULL, NULL, 'M'),
(18, 33, 13, 'Chancho a la Caja China Personal (Chancho, arroz, yuca y ensalada)', 3, 50.00, 55.00, 'fijo', 5.00, 5.00, 15.00, 2, 2, 'promolocura', 50.00, 150.00, NULL, NULL, 'M'),
(19, 8, 14, 'Corona', 5, 24.00, 25.00, 'fijo', 1.00, 1.00, 5.00, 1, 1, 'balde de coronasssss', 20.00, 120.00, NULL, NULL, 'M'),
(20, 33, 15, 'Chancho a la Caja China Personal ', 1, 55.00, 55.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 50.00, 55.00, NULL, NULL, 'M'),
(21, 33, 16, 'Chancho a la Caja China Personal ', 1, 55.00, 55.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 50.00, 55.00, NULL, NULL, 'M'),
(22, 33, 17, 'Chancho a la Caja China Personal ', 1, 55.00, 55.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 50.00, 55.00, NULL, NULL, 'M'),
(23, 33, 18, 'Chancho a la Caja China Personal ', 1, 55.00, 55.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 50.00, 55.00, NULL, NULL, 'M'),
(24, 33, 19, 'Chancho a la Caja China Personal ', 1, 55.00, 55.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 50.00, 55.00, NULL, NULL, 'M'),
(25, 34, 20, 'Costilla a la Parrilla 2 personas', 1, 85.00, 85.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 70.00, 85.00, NULL, NULL, 'M'),
(26, 33, 21, 'Chancho a la Caja China Personal ', 1, 55.00, 55.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 50.00, 55.00, NULL, NULL, 'M'),
(27, 33, 22, 'Chancho a la Caja China Personal ', 1, 55.00, 55.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 50.00, 55.00, NULL, NULL, 'M'),
(28, 33, 23, 'Chancho a la Caja China Personal ', 1, 55.00, 55.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 50.00, 55.00, NULL, NULL, 'M'),
(29, 8, 24, 'Corona', 1, 25.00, 25.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 20.00, 25.00, NULL, NULL, 'M'),
(30, 8, 25, 'Corona', 1, 25.00, 25.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 20.00, 25.00, NULL, NULL, 'M'),
(31, 8, 26, 'Corona', 1, 25.00, 25.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 20.00, 25.00, NULL, NULL, 'M'),
(32, 8, 27, 'Corona', 1, 25.00, 25.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 20.00, 25.00, NULL, NULL, 'M'),
(33, 35, 28, 'Tablita Personal ', 1, 75.00, 75.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 70.00, 75.00, NULL, NULL, 'M'),
(34, 19, 29, 'Limonada Jarra Grande', 1, 22.00, 22.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 20.00, 22.00, NULL, NULL, 'M'),
(35, 35, 29, 'Tablita Personal ', 1, 75.00, 75.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 70.00, 75.00, NULL, NULL, 'M'),
(36, 34, 30, 'Costilla a la Parrilla 2 personas', 1, 85.00, 85.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 70.00, 85.00, NULL, NULL, 'M'),
(37, 34, 31, 'Costilla a la Parrilla 2 personas', 1, 85.00, 85.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 70.00, 85.00, NULL, NULL, 'M'),
(38, 33, 32, 'Chancho a la Caja China Personal ', 1, 55.00, 55.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 50.00, 55.00, NULL, NULL, 'M'),
(39, 5, 33, 'Vino Kohlberg', 1, 50.00, 50.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 40.00, 50.00, NULL, NULL, 'M'),
(40, 6, 34, 'Vino Campos del Solana', 1, 50.00, 50.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 40.00, 50.00, NULL, NULL, 'M'),
(41, 6, 35, 'Vino Campos del Solana', 1, 50.00, 50.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 40.00, 50.00, NULL, NULL, 'M'),
(42, 1, 36, 'Paletas Q\' Deli', 10, 15.00, 15.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 10.00, 150.00, NULL, NULL, 'M'),
(43, 33, 37, 'Chancho a la Caja China Personal ', 1, 55.00, 55.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 50.00, 55.00, NULL, NULL, 'M'),
(44, 19, 38, 'Limonada Jarra Grande', 1, 22.00, 22.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 20.00, 22.00, NULL, NULL, 'M'),
(45, 26, 39, 'Porción Yuca Frita', 1, 13.00, 13.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 10.00, 13.00, NULL, NULL, 'M'),
(46, 26, 40, 'Porción Yuca Frita', 1, 13.00, 13.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 10.00, 13.00, NULL, NULL, 'M'),
(47, 26, 41, 'Porción Yuca Frita', 1, 13.00, 13.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 10.00, 13.00, NULL, NULL, 'M'),
(48, 26, 42, 'Porción Yuca Frita', 1, 13.00, 13.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 10.00, 13.00, NULL, NULL, 'M'),
(49, 25, 43, 'porción de Arroz', 1, 10.00, 10.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 8.00, 10.00, NULL, NULL, 'M'),
(50, 8, 44, 'Corona', 1, 25.00, 25.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 20.00, 25.00, NULL, NULL, 'M'),
(51, 1, 45, 'Paletas Q\' Deli', 1, 15.00, 15.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 10.00, 15.00, NULL, NULL, 'M'),
(52, 35, 46, 'Tablita Personal ', 1, 75.00, 75.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 70.00, 75.00, NULL, NULL, 'M'),
(53, 8, 47, 'Corona', 5, 24.00, 25.00, 'fijo', 1.00, 1.00, 5.00, 1, 1, 'balde de coronasssss', 20.00, 120.00, NULL, NULL, 'M'),
(54, 26, 48, 'Porción Yuca Frita', 1, 13.00, 13.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 10.00, 13.00, NULL, NULL, 'M'),
(55, 25, 49, 'porción de Arroz', 1, 10.00, 10.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 8.00, 10.00, NULL, NULL, 'M'),
(56, 28, 50, 'Sopa de Maní', 1, 18.00, 18.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 15.00, 18.00, NULL, NULL, 'M'),
(57, 32, 51, 'Cuadril Personal', 5, 55.00, 55.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 50.00, 275.00, NULL, NULL, 'LL'),
(58, 10, 51, 'Agua con Gas (500 ml)', 1, 8.00, 8.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 5.00, 8.00, NULL, NULL, 'LL'),
(59, 33, 52, 'Chancho a la Caja China Personal ', 1, 55.00, 55.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 50.00, 55.00, NULL, NULL, 'M'),
(60, 35, 53, 'Tablita Personal ', 1, 75.00, 75.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 70.00, 75.00, NULL, NULL, 'M'),
(61, 8, 53, 'Corona', 1, 25.00, 25.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 20.00, 25.00, NULL, NULL, 'M'),
(62, 35, 54, 'Tablita Personal ', 1, 75.00, 75.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 70.00, 75.00, 'Sin fideo ❌', NULL, 'M'),
(63, 33, 57, 'Chancho a la Caja China Personal ', 1, 55.00, 55.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 50.00, 55.00, NULL, NULL, 'M'),
(64, 35, 59, 'Tablita Personal ', 1, 75.00, 75.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 70.00, 75.00, 'Término medio 🔥', NULL, 'M'),
(65, 25, 60, 'porción de Arroz', 1, 10.00, 10.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 8.00, 10.00, 'Poco chorizo,Poca sal', NULL, 'M'),
(66, 33, 61, 'Chancho a la Caja China Personal ', 1, 55.00, 55.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 50.00, 55.00, NULL, NULL, 'M'),
(67, 34, 61, 'Costilla a la Parrilla 2 personas', 1, 85.00, 85.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 70.00, 85.00, NULL, NULL, 'M'),
(68, 30, 61, 'Ojo de Bife ', 1, 60.00, 60.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 50.00, 60.00, NULL, NULL, 'M'),
(69, 29, 61, 'Keperí ', 1, 55.00, 55.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 40.00, 55.00, NULL, NULL, 'M'),
(70, 31, 61, 'Costilla a la Parrilla ', 1, 60.00, 60.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 50.00, 60.00, NULL, NULL, 'M'),
(71, 33, 62, 'Chancho a la Caja China Personal ', 1, 55.00, 55.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 50.00, 55.00, NULL, NULL, 'M'),
(72, 30, 62, 'Ojo de Bife ', 1, 60.00, 60.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 50.00, 60.00, NULL, NULL, 'M'),
(73, 31, 62, 'Costilla a la Parrilla ', 1, 60.00, 60.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 50.00, 60.00, NULL, NULL, 'M'),
(74, 35, 62, 'Tablita Personal ', 1, 75.00, 75.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 70.00, 75.00, NULL, NULL, 'M'),
(75, 34, 62, 'Costilla a la Parrilla 2 personas', 1, 85.00, 85.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 70.00, 85.00, NULL, NULL, 'M'),
(76, 36, 62, 'Tablita Mixta 2 personas', 2, 100.00, 100.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 90.00, 200.00, NULL, NULL, 'M'),
(77, 32, 62, 'Cuadril Personal', 1, 55.00, 55.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 50.00, 55.00, NULL, NULL, 'M'),
(78, 29, 62, 'Keperí ', 1, 55.00, 55.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 40.00, 55.00, NULL, NULL, 'M'),
(79, 25, 62, 'porción de Arroz', 1, 10.00, 10.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 8.00, 10.00, NULL, NULL, 'M'),
(80, 26, 62, 'Porción Yuca Frita', 1, 13.00, 13.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 10.00, 13.00, NULL, NULL, 'M'),
(81, 27, 62, 'Porción de Papas Fritas', 1, 15.00, 15.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 10.00, 15.00, NULL, NULL, 'M'),
(82, 14, 62, 'Soda Popular', 1, 13.00, 13.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 10.00, 13.00, NULL, NULL, 'M'),
(83, 13, 62, 'Agua 500 ml', 1, 7.00, 7.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 5.00, 7.00, NULL, NULL, 'M'),
(84, 15, 62, 'Soda 2 Lt', 1, 22.00, 22.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 20.00, 22.00, NULL, NULL, 'M'),
(85, 4, 62, 'Ron Habana Club', 2, 180.00, 180.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 150.00, 360.00, NULL, NULL, 'M'),
(86, 5, 62, 'Vino Kohlberg', 2, 50.00, 50.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 40.00, 100.00, NULL, NULL, 'M'),
(87, 6, 62, 'Vino Campos del Solana', 1, 50.00, 50.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 40.00, 50.00, NULL, NULL, 'M'),
(88, 8, 62, 'Corona', 1, 25.00, 25.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 20.00, 25.00, NULL, NULL, 'M'),
(89, 9, 62, 'Huari 620 ml', 2, 32.00, 32.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 30.00, 64.00, NULL, NULL, 'M'),
(90, 11, 62, 'Power de 1 Lt', 1, 15.00, 15.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 10.00, 15.00, NULL, NULL, 'M'),
(91, 12, 62, 'Power de 500 ml', 1, 10.00, 10.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 5.00, 10.00, NULL, NULL, 'M'),
(92, 10, 62, 'Agua con Gas (500 ml)', 1, 8.00, 8.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 5.00, 8.00, NULL, NULL, 'M'),
(93, 33, 63, 'Chancho a la Caja China Personal ', 1, 55.00, 55.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 50.00, 55.00, NULL, NULL, 'M'),
(94, 1, 64, 'Paletas Q\' Deli', 27, 15.00, 15.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 10.00, 405.00, NULL, NULL, 'M'),
(95, 2, 64, 'Cheesecake de Oreo', 50, 15.00, 15.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 10.00, 750.00, NULL, NULL, 'M'),
(96, 33, 65, 'Chancho a la Caja China Personal ', 1, 55.00, 55.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 50.00, 55.00, NULL, NULL, 'M'),
(97, 33, 66, 'Chancho a la Caja China Personal ', 1, 55.00, 55.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 50.00, 55.00, NULL, NULL, 'M'),
(98, 33, 67, 'Chancho a la Caja China Personal ', 1, 55.00, 55.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 50.00, 55.00, NULL, NULL, 'M'),
(99, 35, 68, 'Tablita Personal ', 1, 75.00, 75.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 70.00, 75.00, NULL, NULL, 'M'),
(100, 31, 69, 'Costilla a la Parrilla ', 1, 60.00, 60.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 50.00, 60.00, NULL, NULL, 'M'),
(101, 35, 69, 'Tablita Personal ', 1, 75.00, 75.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 70.00, 75.00, NULL, NULL, 'M'),
(102, 34, 69, 'Costilla a la Parrilla 2 personas', 1, 85.00, 85.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 70.00, 85.00, NULL, NULL, 'M'),
(103, 33, 70, 'Chancho a la Caja China Personal ', 1, 55.00, 55.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 50.00, 55.00, NULL, NULL, 'M'),
(104, 33, 71, 'Chancho a la Caja China Personal ', 2, 55.00, 55.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 50.00, 110.00, NULL, NULL, 'M'),
(105, 33, 72, 'Chancho a la Caja China Personal ', 1, 55.00, 55.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 50.00, 55.00, NULL, NULL, 'M'),
(106, 29, 73, 'Keperí ', 1, 55.00, 55.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 40.00, 55.00, NULL, NULL, 'M'),
(107, 30, 73, 'Ojo de Bife ', 1, 60.00, 60.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 50.00, 60.00, NULL, NULL, 'M'),
(108, 9, 74, 'Huari 620 ml', 15, 31.00, 32.00, 'fijo', 1.00, 1.00, 15.00, 3, 3, 'BALDE CORONA HUARI', 30.00, 465.00, NULL, NULL, 'M'),
(109, 19, 75, 'Limonada Jarra Grande', 1, 22.00, 22.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 20.00, 22.00, NULL, NULL, 'M'),
(110, 33, 76, 'Chancho a la Caja China Personal ', 1, 55.00, 55.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 50.00, 55.00, NULL, NULL, 'M'),
(111, 29, 76, 'Keperí ', 1, 55.00, 55.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 40.00, 55.00, NULL, NULL, 'M'),
(112, 36, 77, 'Tablita Mixta 2 personas', 3, 100.00, 100.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 90.00, 300.00, NULL, NULL, 'M'),
(113, 35, 77, 'Tablita Personal ', 3, 75.00, 75.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 70.00, 225.00, NULL, NULL, 'M'),
(114, 33, 77, 'Chancho a la Caja China Personal ', 2, 55.00, 55.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 50.00, 110.00, NULL, NULL, 'M'),
(115, 31, 77, 'Costilla a la Parrilla ', 4, 60.00, 60.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 50.00, 240.00, NULL, NULL, 'M'),
(116, 29, 77, 'Keperí ', 3, 55.00, 55.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 40.00, 165.00, NULL, NULL, 'M'),
(117, 32, 77, 'Cuadril Personal', 3, 55.00, 55.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 50.00, 165.00, NULL, NULL, 'M'),
(118, 25, 77, 'porción de Arroz', 2, 10.00, 10.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 8.00, 20.00, NULL, NULL, 'M'),
(119, 30, 77, 'Ojo de Bife ', 1, 60.00, 60.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 50.00, 60.00, NULL, NULL, 'M'),
(120, 33, 78, 'Chancho a la Caja China Personal ', 1, 55.00, 55.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 50.00, 55.00, NULL, NULL, 'M'),
(121, 35, 78, 'Tablita Personal ', 1, 75.00, 75.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 70.00, 75.00, NULL, NULL, 'M'),
(122, 33, 79, 'Chancho a la Caja China Personal ', 1, 55.00, 55.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 50.00, 55.00, NULL, NULL, 'M');

-- --------------------------------------------------------

--
-- Table structure for table `gastos`
--

CREATE TABLE `gastos` (
  `id` int NOT NULL,
  `fecha` date NOT NULL,
  `descripcion` varchar(500) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `monto` decimal(11,2) NOT NULL DEFAULT '0.00',
  `monto_efectivo` decimal(11,2) NOT NULL DEFAULT '0.00',
  `monto_qr` decimal(11,2) NOT NULL DEFAULT '0.00',
  `forma_pago` varchar(100) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `id_tipo_gasto` int NOT NULL,
  `id_usuario` int NOT NULL,
  `id_arqueo` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Dumping data for table `gastos`
--

INSERT INTO `gastos` (`id`, `fecha`, `descripcion`, `monto`, `monto_efectivo`, `monto_qr`, `forma_pago`, `id_tipo_gasto`, `id_usuario`, `id_arqueo`) VALUES
(1, '2026-08-23', 'test', 10.00, 10.00, 0.00, '1', 10, 1, 6),
(2, '2026-08-23', 'alquiler del mes agosto', 100.00, 0.00, 100.00, '2', 5, 1, 6),
(3, '2026-08-23', 'test', 6.00, 3.00, 3.00, '4', 10, 1, 6);

-- --------------------------------------------------------

--
-- Table structure for table `meseros`
--

CREATE TABLE `meseros` (
  `id` int NOT NULL,
  `nombre` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `documento` varchar(11) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `telefono` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `direccion` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `compras` int NOT NULL DEFAULT '0',
  `ultima_compra` datetime DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `estado` tinyint NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

--
-- Dumping data for table `meseros`
--

INSERT INTO `meseros` (`id`, `nombre`, `documento`, `telefono`, `direccion`, `compras`, `ultima_compra`, `fecha`, `estado`) VALUES
(1, 's/n', '0000000', '00000000', 's/n', 374, '2026-08-24 00:08:05', '2026-08-24 04:08:05', 1),
(2, 'Belen Figueroa Miranda', ' 8870938', ' 690-90-581', 'Cotoca B/ San Marino', 13, '2026-08-06 22:57:21', '2026-08-07 02:57:38', 1),
(3, 'Raquel Taceo', '8160365', '123-45-678', 'Cotoca -Barrio las madresitas sector los tojos', 1, '2026-08-06 18:53:20', '2026-08-06 22:53:20', 1),
(4, 'Vanessa surubi paticu ', '14773348', '123-45-678', 'Calle 9 de abril atras de la escuelita vieja', 2, '2026-08-06 18:58:10', '2026-08-06 22:58:10', 1),
(5, 'Carla Viviana Tiain Bairo', '14138040', '123-45-678', 'B/ San Antonio', 4, '2026-08-06 20:49:12', '2026-08-07 00:49:12', 1);

-- --------------------------------------------------------

--
-- Table structure for table `otros_ingresos`
--

CREATE TABLE `otros_ingresos` (
  `id` int NOT NULL,
  `id_arqueo_caja` bigint UNSIGNED NOT NULL,
  `id_usuario` int NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `monto` decimal(11,2) NOT NULL DEFAULT '0.00',
  `tipo_entrada` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'EFECTIVO',
  `monto_efectivo` decimal(11,2) NOT NULL DEFAULT '0.00',
  `monto_qr` decimal(11,2) NOT NULL DEFAULT '0.00',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estado` tinyint NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `otros_ingresos`
--

INSERT INTO `otros_ingresos` (`id`, `id_arqueo_caja`, `id_usuario`, `descripcion`, `monto`, `tipo_entrada`, `monto_efectivo`, `monto_qr`, `fecha`, `estado`) VALUES
(1, 5, 1, 'prueba', 20.00, 'EFECTIVO', 20.00, 0.00, '2026-08-17 01:19:38', 1),
(2, 6, 1, 'test', 90.00, 'MIXTO', 50.00, 40.00, '2026-08-24 00:18:06', 1),
(3, 6, 1, 'aportes', 100.00, 'QR', 0.00, 100.00, '2026-08-24 00:20:11', 1);

-- --------------------------------------------------------

--
-- Table structure for table `perfiles`
--

CREATE TABLE `perfiles` (
  `id` int NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `perfiles`
--

INSERT INTO `perfiles` (`id`, `nombre`, `descripcion`, `estado`, `activo`, `fecha`) VALUES
(1, 'Administrador', 'Acceso completo al sistema', 1, 1, '2026-08-16 23:07:44'),
(2, 'Supervisor', 'Supervisión operativa sin administración de usuarios/perfiles', 1, 1, '2026-08-16 23:07:44'),
(3, 'Vendedor', 'Caja, ventas e impresión de reportes básicos', 1, 1, '2026-08-16 23:07:44'),
(4, 'Cajero Test', '123', 1, 1, '2026-08-16 23:16:42');

-- --------------------------------------------------------

--
-- Table structure for table `perfil_permisos`
--

CREATE TABLE `perfil_permisos` (
  `id` int NOT NULL,
  `id_perfil` int NOT NULL,
  `id_permiso` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `perfil_permisos`
--

INSERT INTO `perfil_permisos` (`id`, `id_perfil`, `id_permiso`) VALUES
(838, 1, 1),
(839, 1, 2),
(840, 1, 3),
(841, 1, 4),
(842, 1, 5),
(843, 1, 6),
(844, 1, 7),
(845, 1, 8),
(846, 1, 9),
(847, 1, 10),
(848, 1, 11),
(849, 1, 12),
(850, 1, 13),
(851, 1, 14),
(852, 1, 15),
(853, 1, 16),
(854, 1, 17),
(855, 1, 18),
(856, 1, 19),
(857, 1, 20),
(858, 1, 21),
(859, 1, 22),
(860, 1, 23),
(861, 1, 24),
(862, 1, 25),
(863, 1, 26),
(864, 1, 27),
(865, 1, 28),
(866, 1, 29),
(867, 1, 30),
(868, 1, 31),
(869, 1, 32),
(870, 1, 33),
(871, 1, 34),
(872, 1, 35),
(873, 1, 36),
(874, 1, 37),
(875, 1, 38),
(876, 1, 39),
(878, 1, 40),
(879, 1, 41),
(880, 1, 42),
(881, 1, 43),
(882, 1, 44),
(883, 1, 45),
(884, 1, 46),
(885, 1, 47),
(886, 1, 48),
(887, 1, 49),
(888, 1, 50),
(889, 1, 51),
(890, 1, 52),
(891, 1, 53),
(892, 1, 54),
(893, 1, 55),
(894, 1, 56),
(895, 1, 57),
(896, 1, 58),
(897, 1, 59),
(898, 1, 60),
(899, 1, 61),
(900, 1, 62),
(901, 1, 63),
(902, 1, 64),
(903, 1, 65),
(904, 1, 66),
(905, 1, 67),
(877, 1, 68),
(906, 1, 69),
(667, 2, 1),
(668, 2, 13),
(669, 2, 14),
(670, 2, 15),
(671, 2, 16),
(672, 2, 17),
(673, 2, 18),
(674, 2, 19),
(675, 2, 20),
(676, 2, 21),
(677, 2, 22),
(678, 2, 23),
(679, 2, 24),
(680, 2, 25),
(681, 2, 26),
(682, 2, 27),
(683, 2, 28),
(684, 2, 29),
(685, 2, 30),
(686, 2, 31),
(687, 2, 32),
(688, 2, 33),
(689, 2, 34),
(690, 2, 35),
(691, 2, 36),
(692, 2, 37),
(693, 2, 38),
(694, 2, 39),
(695, 2, 40),
(696, 2, 41),
(697, 2, 42),
(698, 2, 43),
(699, 2, 44),
(700, 2, 45),
(701, 2, 46),
(702, 2, 47),
(703, 2, 48),
(704, 2, 49),
(705, 2, 50),
(706, 2, 51),
(707, 2, 52),
(708, 2, 53),
(709, 2, 54),
(710, 2, 55),
(711, 2, 56),
(712, 2, 57),
(713, 2, 58),
(714, 2, 59),
(715, 2, 60),
(716, 2, 61),
(717, 2, 62),
(718, 2, 63),
(719, 2, 64),
(720, 2, 65),
(721, 2, 66),
(722, 2, 67),
(741, 2, 68),
(744, 2, 69),
(746, 3, 1),
(747, 3, 37),
(748, 3, 38),
(749, 3, 39),
(751, 3, 40),
(752, 3, 41),
(753, 3, 42),
(754, 3, 43),
(755, 3, 44),
(756, 3, 45),
(757, 3, 46),
(758, 3, 47),
(759, 3, 50),
(760, 3, 60),
(761, 3, 61),
(762, 3, 62),
(763, 3, 63),
(764, 3, 64),
(765, 3, 65),
(766, 3, 66),
(767, 3, 67),
(750, 3, 68),
(768, 3, 69);

-- --------------------------------------------------------

--
-- Table structure for table `permisos`
--

CREATE TABLE `permisos` (
  `id` int NOT NULL,
  `modulo` varchar(80) NOT NULL,
  `codigo` varchar(80) NOT NULL,
  `nombre` varchar(120) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `orden` int NOT NULL DEFAULT '0',
  `estado` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `permisos`
--

INSERT INTO `permisos` (`id`, `modulo`, `codigo`, `nombre`, `descripcion`, `orden`, `estado`) VALUES
(1, 'Inicio', 'inicio.ver', 'Ver inicio', 'Acceder al panel de inicio', 10, 1),
(2, 'Usuarios', 'usuarios.ver', 'Ver usuarios', 'Listar usuarios', 20, 1),
(3, 'Usuarios', 'usuarios.crear', 'Agregar usuario', 'Registrar usuarios', 21, 1),
(4, 'Usuarios', 'usuarios.editar', 'Editar usuario', 'Modificar o activar/desactivar usuarios', 22, 1),
(5, 'Usuarios', 'usuarios.eliminar', 'Eliminar usuario', 'Desactivar/eliminar usuarios', 23, 1),
(6, 'Usuarios', 'usuarios.eliminados', 'Ver usuarios eliminados', 'Listar y restaurar usuarios eliminados', 24, 1),
(7, 'Perfiles', 'perfiles.ver', 'Ver perfiles', 'Listar perfiles', 30, 1),
(8, 'Perfiles', 'perfiles.crear', 'Agregar perfil', 'Crear perfiles', 31, 1),
(9, 'Perfiles', 'perfiles.editar', 'Editar perfil', 'Modificar perfiles', 32, 1),
(10, 'Perfiles', 'perfiles.eliminar', 'Eliminar perfil', 'Desactivar/eliminar perfiles', 33, 1),
(11, 'Perfiles', 'perfiles.permisos', 'Asignar permisos', 'Configurar permisos de un perfil', 34, 1),
(12, 'Perfiles', 'perfiles.eliminados', 'Ver perfiles eliminados', 'Listar y restaurar perfiles eliminados', 35, 1),
(13, 'Categorías', 'categorias.ver', 'Ver categorías', 'Listar categorías', 40, 1),
(14, 'Categorías', 'categorias.crear', 'Agregar categoría', 'Crear categorías', 41, 1),
(15, 'Categorías', 'categorias.editar', 'Editar categoría', 'Modificar categorías', 42, 1),
(16, 'Categorías', 'categorias.eliminar', 'Eliminar categoría', 'Eliminar categorías', 43, 1),
(17, 'Categorías', 'categorias.eliminados', 'Ver categorías eliminadas', 'Listar categorías eliminadas', 44, 1),
(18, 'Productos', 'productos.ver', 'Ver productos', 'Listar productos', 50, 1),
(19, 'Productos', 'productos.crear', 'Agregar producto', 'Crear productos', 51, 1),
(20, 'Productos', 'productos.editar', 'Editar producto', 'Modificar productos', 52, 1),
(21, 'Productos', 'productos.eliminar', 'Eliminar producto', 'Eliminar productos', 53, 1),
(22, 'Productos', 'productos.eliminados', 'Ver productos eliminados', 'Listar productos eliminados', 54, 1),
(23, 'Ofertas y promociones', 'promociones.ver', 'Ver promociones', 'Listar ofertas y promociones', 60, 1),
(24, 'Ofertas y promociones', 'promociones.crear', 'Agregar promoción', 'Crear promociones', 61, 1),
(25, 'Ofertas y promociones', 'promociones.editar', 'Editar promoción', 'Modificar o habilitar/deshabilitar promociones', 62, 1),
(26, 'Ofertas y promociones', 'promociones.eliminar', 'Eliminar promoción', 'Eliminar promociones', 63, 1),
(27, 'Clientes', 'clientes.ver', 'Ver clientes', 'Listar clientes', 70, 1),
(28, 'Clientes', 'clientes.crear', 'Agregar cliente', 'Crear clientes', 71, 1),
(29, 'Clientes', 'clientes.editar', 'Editar cliente', 'Modificar clientes', 72, 1),
(30, 'Clientes', 'clientes.eliminar', 'Eliminar cliente', 'Eliminar clientes', 73, 1),
(31, 'Clientes', 'clientes.eliminados', 'Ver clientes eliminados', 'Listar clientes eliminados', 74, 1),
(32, 'Meseros', 'meseros.ver', 'Ver meseros', 'Listar meseros', 80, 1),
(33, 'Meseros', 'meseros.crear', 'Agregar mesero', 'Crear meseros', 81, 1),
(34, 'Meseros', 'meseros.editar', 'Editar mesero', 'Modificar meseros', 82, 1),
(35, 'Meseros', 'meseros.eliminar', 'Eliminar mesero', 'Eliminar meseros', 83, 1),
(36, 'Meseros', 'meseros.eliminados', 'Ver meseros eliminados', 'Listar meseros eliminados', 84, 1),
(37, 'Caja', 'caja.ver', 'Ver caja / arqueo', 'Acceder al arqueo de caja', 90, 1),
(38, 'Caja', 'caja.abrir', 'Abrir caja', 'Aperturar caja', 91, 1),
(39, 'Caja', 'caja.cerrar', 'Cerrar caja', 'Cerrar arqueo de caja', 92, 1),
(40, 'Gastos', 'gastos.ver', 'Ver gastos', 'Listar gastos de caja', 100, 1),
(41, 'Gastos', 'gastos.crear', 'Registrar gasto', 'Crear gastos', 101, 1),
(42, 'Gastos', 'gastos.editar', 'Editar gasto', 'Modificar gastos', 102, 1),
(43, 'Gastos', 'gastos.eliminar', 'Eliminar gasto', 'Eliminar gastos', 103, 1),
(44, 'Ventas', 'ventas.crear', 'Crear venta', 'Acceder a vender / crear venta', 110, 1),
(45, 'Ventas', 'ventas.ver', 'Ver ventas realizadas', 'Listar ventas', 111, 1),
(46, 'Ventas', 'ventas.editar', 'Editar cuenta pendiente', 'Actualizar cuentas pendientes', 112, 1),
(47, 'Ventas', 'ventas.cobrar', 'Cobrar cuenta', 'Cobrar cuentas pendientes', 113, 1),
(48, 'Ventas', 'ventas.eliminar', 'Anular venta', 'Anular o eliminar ventas', 114, 1),
(49, 'Ventas', 'ventas.eliminados', 'Ver ventas eliminadas', 'Listar ventas anuladas', 115, 1),
(50, 'Ventas', 'ventas.imprimir', 'Imprimir ticket', 'Reimprimir tickets', 116, 1),
(51, 'Compras', 'compras.ver', 'Ver compras', 'Administrar compras', 120, 1),
(52, 'Compras', 'compras.crear', 'Crear compra', 'Registrar compras', 121, 1),
(53, 'Compras', 'compras.eliminar', 'Eliminar compra', 'Anular compras', 122, 1),
(54, 'Compras', 'compras.eliminados', 'Ver compras eliminadas', 'Listar compras eliminadas', 123, 1),
(55, 'Proveedores', 'proveedores.ver', 'Ver proveedores', 'Listar proveedores', 130, 1),
(56, 'Proveedores', 'proveedores.crear', 'Agregar proveedor', 'Crear proveedores', 131, 1),
(57, 'Proveedores', 'proveedores.editar', 'Editar proveedor', 'Modificar proveedores', 132, 1),
(58, 'Proveedores', 'proveedores.eliminar', 'Eliminar proveedor', 'Eliminar proveedores', 133, 1),
(59, 'Proveedores', 'proveedores.eliminados', 'Ver proveedores eliminados', 'Listar proveedores eliminados', 134, 1),
(60, 'Reportes', 'reportes.ventas', 'Reporte de ventas', 'Gráfico y reporte general de ventas', 140, 1),
(61, 'Reportes', 'reportes.venta_fecha', 'Ventas por fecha', 'Reporte de ventas por rango de fechas', 141, 1),
(62, 'Reportes', 'reportes.top_productos', 'Productos más vendidos', 'Top productos', 142, 1),
(63, 'Reportes', 'reportes.top_meseros', 'Meseros con más ventas', 'Top meseros', 143, 1),
(64, 'Reportes', 'reportes.faltantes', 'Productos faltantes', 'Stock mínimo / faltantes', 144, 1),
(65, 'Reportes', 'reportes.categorias', 'Reporte de categorías', 'Ventas por categoría', 145, 1),
(66, 'Reportes', 'reportes.ganancias', 'Reporte de ganancias', 'Utilidades', 146, 1),
(67, 'Reportes', 'reportes.compras', 'Reporte de compras', 'Compras por fecha', 147, 1),
(68, 'Caja', 'caja.otros_ingresos', 'Registrar otros ingresos', 'Registrar ingresos de caja que no corresponden a una venta', 93, 1),
(69, 'Reportes', 'reportes.gastos', 'Reporte de gastos', 'Reporte de gastos filtrado por fechas y otros criterios', 148, 1);

-- --------------------------------------------------------

--
-- Table structure for table `productos`
--

CREATE TABLE `productos` (
  `id` int NOT NULL,
  `id_categoria` int NOT NULL,
  `codigo` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `imagen` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `stock` int NOT NULL,
  `precio_venta` float NOT NULL,
  `precio_compra` float NOT NULL,
  `ventas` int NOT NULL DEFAULT '0',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `inventariable` tinyint NOT NULL,
  `estado` tinyint NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

--
-- Dumping data for table `productos`
--

INSERT INTO `productos` (`id`, `id_categoria`, `codigo`, `descripcion`, `imagen`, `stock`, `precio_venta`, `precio_compra`, `ventas`, `fecha`, `inventariable`, `estado`) VALUES
(1, 1, '101', 'Paletas Q\' Deli', 'vistas/img/productos/101/641.jpg', 1, 15, 10, 49, '2026-08-23 23:07:01', 1, 1),
(2, 1, '102', 'Cheesecake de Oreo', 'vistas/img/productos/102/731.png', 0, 15, 10, 50, '2026-08-23 23:07:01', 1, 1),
(3, 2, '201', 'ron flor de caña', 'vistas/img/productos/201/162.png', 50, 120, 100, 0, '2026-07-30 02:52:42', 1, 1),
(4, 2, '202', 'Ron Habana Club', 'vistas/img/productos/202/609.webp', 45, 180, 150, 5, '2026-08-23 22:38:05', 1, 1),
(5, 2, '203', 'Vino Kohlberg', 'vistas/img/productos/203/469.webp', 41, 50, 40, 9, '2026-08-23 22:38:05', 1, 1),
(6, 2, '204', 'Vino Campos del Solana', 'vistas/img/productos/204/418.webp', 38, 50, 40, 12, '2026-08-23 22:38:05', 1, 1),
(8, 2, '206', 'Corona', 'vistas/img/productos/206/236.png', 0, 25, 20, 55, '2026-08-23 22:38:05', 1, 1),
(9, 2, '207', 'Huari 620 ml', 'vistas/img/productos/207/939.webp', 14, 32, 30, 36, '2026-08-24 00:48:03', 1, 1),
(10, 3, '301', 'Agua con Gas (500 ml)', 'vistas/img/productos/301/151.webp', 48, 8, 5, 2, '2026-08-23 22:38:05', 1, 1),
(11, 3, '302', 'Power de 1 Lt', 'vistas/img/productos/302/864.png', 49, 15, 10, 1, '2026-08-23 22:38:05', 1, 1),
(12, 3, '303', 'Power de 500 ml', 'vistas/img/productos/303/353.png', 49, 10, 5, 1, '2026-08-23 22:38:05', 1, 1),
(13, 3, '304', 'Agua 500 ml', 'vistas/img/productos/304/974.png', 49, 7, 5, 1, '2026-08-23 22:38:05', 1, 1),
(14, 3, '305', 'Soda Popular', 'vistas/img/productos/305/106.png', 42, 13, 10, 8, '2026-08-23 22:38:05', 1, 1),
(15, 3, '306', 'Soda 2 Lt', 'vistas/img/productos/306/425.jpg', 49, 22, 20, 1, '2026-08-23 22:38:05', 1, 1),
(16, 3, '307', 'Soda Personal', 'vistas/img/productos/307/302.webp', 50, 10, 8, 0, '2026-08-24 03:45:34', 1, 1),
(17, 3, '308', 'Tropifrut', 'vistas/img/productos/308/810.png', 61, 15, 10, 0, '2026-08-24 03:45:34', 1, 1),
(18, 3, '309', 'Limonada Jarra Mediana', 'vistas/img/productos/309/448.png', 99999, 16, 14, 1, '2026-08-23 23:33:06', 0, 1),
(19, 3, '310', 'Limonada Jarra Grande', 'vistas/img/productos/310/244.png', 99999, 22, 20, 4, '2026-08-24 01:02:33', 0, 1),
(20, 3, '311', 'Chicha Jarra Mediana', 'vistas/img/productos/311/416.png', 99999, 15, 10, 1, '2026-08-23 23:33:06', 0, 1),
(21, 3, '312', 'Chicha Jarra Grande', 'vistas/img/productos/312/991.png', 48, 20, 15, 3, '2026-08-24 03:45:08', 1, 1),
(22, 4, '401', 'Cuadril (Carne Extra)', 'vistas/img/productos/401/311.png', 99999, 40, 30, 0, '2026-08-23 23:33:06', 0, 1),
(23, 4, '402', 'Porción Cordon Blue', 'vistas/img/productos/402/339.png', 99999, 35, 30, 0, '2026-08-23 23:33:06', 0, 1),
(24, 4, '403', 'Porción de Chorizo', 'vistas/img/productos/403/352.png', 99999, 12, 10, 2, '2026-08-23 23:33:06', 0, 1),
(25, 4, '404', 'porción de Arroz', 'vistas/img/productos/404/556.webp', 99999, 10, 8, 9, '2026-08-24 01:03:49', 0, 1),
(26, 4, '405', 'Porción Yuca Frita', 'vistas/img/productos/405/548.png', 99999, 13, 10, 10, '2026-08-23 23:33:06', 0, 1),
(27, 4, '406', 'Porción de Papas Fritas', 'vistas/img/productos/406/195.webp', 99999, 15, 10, 4, '2026-08-23 23:33:06', 0, 1),
(28, 5, '501', 'Sopa de Maní', 'vistas/img/productos/501/405.png', 99999, 18, 15, 12, '2026-08-23 23:33:06', 0, 1),
(29, 6, '601', 'Keperí ', 'vistas/img/productos/601/874.png', 99999, 55, 40, 12, '2026-08-24 01:03:49', 0, 1),
(30, 6, '602', 'Ojo de Bife ', 'vistas/img/productos/602/171.png', 99999, 60, 50, 7, '2026-08-24 01:03:49', 0, 1),
(31, 6, '603', 'Costilla a la Parrilla ', 'vistas/img/productos/603/653.png', 99999, 60, 50, 10, '2026-08-24 01:03:49', 0, 1),
(32, 6, '604', 'Cuadril Personal', 'vistas/img/productos/604/948.png', 99999, 55, 50, 12, '2026-08-24 01:03:49', 0, 1),
(33, 6, '605', 'Chancho a la Caja China Personal ', 'vistas/img/productos/605/118.png', 99999, 55, 50, 38, '2026-08-24 04:08:05', 0, 1),
(34, 6, '606', 'Costilla a la Parrilla 2 personas', 'vistas/img/productos/606/965.png', 99999, 85, 70, 10, '2026-08-24 00:00:25', 0, 1),
(35, 6, '607', 'Tablita Personal ', 'vistas/img/productos/607/257.png', 99999, 75, 70, 17, '2026-08-24 01:13:28', 0, 1),
(36, 6, '608', 'Tablita Mixta 2 personas', 'vistas/img/productos/608/301.png', 99999, 100, 90, 16, '2026-08-24 01:03:49', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `promociones`
--

CREATE TABLE `promociones` (
  `id` int NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` text,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  `prioridad` int NOT NULL DEFAULT '1',
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `modo_cantidad` varchar(20) NOT NULL DEFAULT 'individual',
  `observacion` text,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `promociones`
--

INSERT INTO `promociones` (`id`, `nombre`, `descripcion`, `fecha_inicio`, `fecha_fin`, `prioridad`, `estado`, `modo_cantidad`, `observacion`, `fecha`) VALUES
(1, 'balde de coronasssss', 'bien heladas', '2026-08-02 21:26:00', '2030-07-30 21:26:00', 5, 1, 'individual', '', '2026-08-03 01:27:07'),
(2, 'promolocura', 'fulll', '2026-08-02 21:51:00', '2037-05-02 21:51:00', 3, 1, 'individual', 'rico', '2026-08-03 01:52:02'),
(3, 'BALDE CORONA HUARI', 'BALDE CERVEZA HUARI.', '2026-08-23 20:44:00', '2026-09-06 20:49:00', 1, 1, 'individual', '', '2026-08-24 00:44:41');

-- --------------------------------------------------------

--
-- Table structure for table `promocion_intervalos`
--

CREATE TABLE `promocion_intervalos` (
  `id` int NOT NULL,
  `id_promocion` int NOT NULL,
  `cantidad_minima` int NOT NULL,
  `cantidad_maxima` int DEFAULT NULL,
  `tipo_descuento` varchar(20) NOT NULL DEFAULT 'fijo',
  `valor_descuento` decimal(10,2) NOT NULL DEFAULT '0.00',
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `promocion_intervalos`
--

INSERT INTO `promocion_intervalos` (`id`, `id_promocion`, `cantidad_minima`, `cantidad_maxima`, `tipo_descuento`, `valor_descuento`, `estado`, `fecha`) VALUES
(1, 1, 5, 5, 'fijo', 1.00, 1, '2026-08-03 01:29:37'),
(2, 2, 3, 3, 'fijo', 5.00, 1, '2026-08-03 01:53:16'),
(3, 3, 5, 10, 'fijo', 1.00, 1, '2026-08-24 00:45:36');

-- --------------------------------------------------------

--
-- Table structure for table `promocion_productos`
--

CREATE TABLE `promocion_productos` (
  `id` int NOT NULL,
  `id_promocion` int NOT NULL,
  `id_producto` int NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `promocion_productos`
--

INSERT INTO `promocion_productos` (`id`, `id_promocion`, `id_producto`, `estado`, `fecha`) VALUES
(1, 1, 8, 1, '2026-08-03 01:28:00'),
(2, 2, 1, 1, '2026-08-03 01:53:49'),
(3, 2, 33, 1, '2026-08-03 01:54:16'),
(4, 3, 9, 1, '2026-08-24 00:45:52');

-- --------------------------------------------------------

--
-- Table structure for table `proveedor`
--

CREATE TABLE `proveedor` (
  `id` int NOT NULL,
  `nombre` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `empresa` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `telefono` varchar(12) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `direccion` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `estado` tinyint NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

--
-- Dumping data for table `proveedor`
--

INSERT INTO `proveedor` (`id`, `nombre`, `empresa`, `telefono`, `direccion`, `fecha`, `estado`) VALUES
(1, 's/n', 's/n', '00000000', 'sin direccion', '2025-04-26 02:55:00', 1),
(2, 'Hino Laura Victor', 'S/N', '67709910', 'zona sur', '2026-03-15 16:41:32', 1),
(3, 'Promotor', 'coca cola srl', '00000000', 's/n', '2026-03-15 18:04:12', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tipo_gasto`
--

CREATE TABLE `tipo_gasto` (
  `id` int NOT NULL,
  `nombre` varchar(300) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Dumping data for table `tipo_gasto`
--

INSERT INTO `tipo_gasto` (`id`, `nombre`) VALUES
(1, 'Otros'),
(2, 'Servicios'),
(3, 'Insumos'),
(4, 'Transporte'),
(5, 'Alquiler'),
(6, 'Mantenimiento'),
(7, 'Limpieza'),
(8, 'Publicidad'),
(9, 'Combustible'),
(10, 'Varios');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int NOT NULL,
  `nombre` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `usuario` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `password` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `perfil` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `id_perfil` int DEFAULT NULL,
  `foto` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `ultimo_login` datetime DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `activo` tinyint NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `usuario`, `password`, `perfil`, `id_perfil`, `foto`, `estado`, `ultimo_login`, `fecha`, `activo`) VALUES
(1, 'soporte', 'soporte', '$2a$07$asxx54ahjppf45sd87a5auGZEtGHuyZwm.Ur.FJvWLCql3nmsMbXy', 'Administrador', 1, 'vistas/img/usuarios/admin/997.webp', 1, '2026-08-23 21:30:15', '2026-08-24 01:30:15', 1),
(3, 'Irys Gabriela Vargas Jimenez ', 'Gabriela ', '$2a$07$asxx54ahjppf45sd87a5auTjc6l.msIbvUzGvRzKgYOcSUPnmTqBa', 'Administrador', 1, 'vistas/img/usuarios/default/anonymous.webp', 1, '2026-08-02 17:43:05', '2026-08-16 23:07:45', 1),
(4, 'Daniel Rico roca ', 'daniel', '$2a$07$asxx54ahjppf45sd87a5auGZEtGHuyZwm.Ur.FJvWLCql3nmsMbXy', 'Vendedor', 3, 'vistas/img/usuarios/default/anonymous.webp', 1, '2026-08-23 22:16:48', '2026-08-24 02:16:48', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ventas`
--

CREATE TABLE `ventas` (
  `id` int NOT NULL,
  `codigo` int NOT NULL,
  `nro_ticket` int(8) UNSIGNED ZEROFILL DEFAULT NULL,
  `total_qr` float DEFAULT NULL,
  `total_efectivo` float DEFAULT NULL,
  `total` float NOT NULL,
  `total_bruto` decimal(12,2) DEFAULT NULL,
  `total_descuento` decimal(12,2) DEFAULT '0.00',
  `total_pagado` float DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `nota` varchar(300) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `tipo_pago` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `cambio` float DEFAULT NULL,
  `forma_atencion` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `estado` tinyint NOT NULL DEFAULT '1',
  `estado_pago` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL DEFAULT 'PAGADA' COMMENT 'Estado del pago: PENDIENTE o PAGADA',
  `fecha_pago` datetime DEFAULT NULL COMMENT 'Fecha y hora en que se cobró la cuenta',
  `id_mesero` int DEFAULT NULL,
  `id_cliente` int DEFAULT NULL,
  `id_vendedor` int DEFAULT NULL,
  `id_arqueo_caja` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

--
-- Dumping data for table `ventas`
--

INSERT INTO `ventas` (`id`, `codigo`, `nro_ticket`, `total_qr`, `total_efectivo`, `total`, `total_bruto`, `total_descuento`, `total_pagado`, `fecha`, `nota`, `tipo_pago`, `cambio`, `forma_atencion`, `estado`, `estado_pago`, `fecha_pago`, `id_mesero`, `id_cliente`, `id_vendedor`, `id_arqueo_caja`) VALUES
(1, 1, NULL, 0, 25, 25, 25.00, 0.00, 25, '2026-08-03 01:21:41', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-02 20:34:19', 1, 1, 1, 1),
(2, 2, NULL, 0, 15, 15, 15.00, 0.00, 15, '2026-08-03 01:21:41', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-02 20:34:31', 1, 1, 1, 1),
(3, 3, NULL, 15, 0, 15, 15.00, 0.00, 15, '2026-08-03 01:21:41', '', 'QR', 0, 'Para Llevar', 1, 'PAGADA', '2026-08-02 20:35:24', 2, 2, 1, 1),
(4, 4, NULL, 0, 25, 25, 25.00, 0.00, 25, '2026-08-03 01:21:41', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-02 20:27:45', 1, 1, 1, 1),
(5, 5, NULL, 15, 0, 15, 15.00, 0.00, 15, '2026-08-03 01:21:41', '', 'QR', 0, 'En Mesa', 1, 'PAGADA', '2026-08-02 20:35:52', 5, 1, 1, 1),
(6, 6, NULL, 0, 25, 25, 25.00, 0.00, 25, '2026-08-03 01:21:41', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-02 20:49:16', 1, 2, 1, 1),
(7, 7, NULL, 0, 13, 13, 13.00, 0.00, 50, '2026-08-03 01:21:41', '', 'Efectivo', 37, 'En Mesa', 1, 'PAGADA', '2026-08-02 20:39:38', 1, 1, 1, 1),
(8, 8, NULL, 0, 32, 32, 32.00, 0.00, 32, '2026-08-03 01:21:41', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-02 20:49:14', 1, 1, 1, 1),
(9, 1, NULL, 0, 125, 125, 125.00, 0.00, 125, '2026-08-03 01:24:10', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-02 21:24:10', 1, 1, 1, 2),
(10, 2, NULL, 0, 145, 145, 145.00, 0.00, 145, '2026-08-06 18:14:26', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-06 14:14:26', 1, 1, 1, 2),
(11, 3, NULL, 0, 120, 120, 125.00, 5.00, 120, '2026-08-03 01:30:06', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-02 21:30:05', 1, 1, 1, 2),
(12, 4, NULL, 0, 320, 320, 320.00, 0.00, 320, '2026-08-03 01:42:39', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-02 21:42:39', 1, 1, 1, 2),
(13, 5, NULL, 0, 150, 150, 165.00, 15.00, 150, '2026-08-03 01:56:06', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-02 21:56:06', 1, 1, 1, 2),
(14, 6, NULL, 0, 120, 120, 125.00, 5.00, 120, '2026-08-03 01:58:11', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-02 21:58:11', 1, 1, 1, 2),
(15, 1, NULL, 0, 55, 55, 55.00, 0.00, 100, '2026-08-06 19:42:37', '', 'Efectivo', 45, 'En Mesa', 1, 'PAGADA', '2026-08-06 15:42:37', 1, 1, 1, 3),
(16, 2, NULL, 0, 55, 55, 55.00, 0.00, 100, '2026-08-06 20:07:29', '', 'Efectivo', 45, 'En Mesa', 1, 'PAGADA', '2026-08-06 16:07:29', 1, 1, 1, 3),
(17, 3, NULL, 0, 55, 55, 55.00, 0.00, 100, '2026-08-06 20:15:52', '', 'Efectivo', 45, 'En Mesa', 1, 'PAGADA', '2026-08-06 16:15:52', 1, 1, 1, 3),
(18, 4, NULL, 0, 55, 55, 55.00, 0.00, 100, '2026-08-06 20:16:02', '', 'Efectivo', 45, 'En Mesa', 1, 'PAGADA', '2026-08-06 16:16:02', 1, 1, 1, 3),
(19, 5, NULL, 0, 55, 55, 55.00, 0.00, 100, '2026-08-06 20:16:24', '', 'Efectivo', 45, 'En Mesa', 1, 'PAGADA', '2026-08-06 16:16:23', 1, 1, 1, 3),
(20, 6, NULL, 0, 85, 85, 85.00, 0.00, 100, '2026-08-06 20:18:39', '', 'Efectivo', 15, 'En Mesa', 1, 'PAGADA', '2026-08-06 16:18:39', 1, 1, 1, 3),
(21, 7, NULL, 0, 55, 55, 55.00, 0.00, 100, '2026-08-06 20:18:50', '', 'Efectivo', 45, 'En Mesa', 1, 'PAGADA', '2026-08-06 16:18:50', 1, 1, 1, 3),
(22, 8, NULL, 0, 55, 55, 55.00, 0.00, 55, '2026-08-06 20:19:12', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-06 16:19:12', 1, 1, 1, 3),
(23, 9, NULL, 0, 55, 55, 55.00, 0.00, 55, '2026-08-06 21:26:05', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-06 17:26:05', 1, 1, 1, 3),
(24, 10, NULL, 0, 25, 25, 25.00, 0.00, 145, '2026-08-06 21:32:08', '', 'Efectivo', 120, 'En Mesa', 1, 'PAGADA', '2026-08-06 17:32:08', 1, 1, 1, 3),
(25, 11, NULL, 0, 25, 25, 25.00, 0.00, 145, '2026-08-06 22:01:16', '', 'Efectivo', 120, 'En Mesa', 0, 'PAGADA', '2026-08-06 17:33:13', 1, 1, 1, 3),
(26, 12, NULL, 0, 25, 25, 25.00, 0.00, 125, '2026-08-06 22:01:05', '', 'Efectivo', 100, 'En Mesa', 0, 'PAGADA', '2026-08-06 17:34:23', 1, 1, 1, 3),
(27, 1, NULL, 0, 25, 25, 25.00, 0.00, 25, '2026-08-06 22:25:36', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-06 18:25:36', 1, 1, 1, 4),
(28, 2, NULL, 0, 75, 75, 75.00, 0.00, 75, '2026-08-06 22:03:30', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-06 18:03:30', 1, 1, 1, 4),
(29, 3, NULL, 0, 97, 97, 97.00, 0.00, 97, '2026-08-06 22:25:34', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-06 18:25:34', 1, 1, 1, 4),
(30, 4, NULL, 0, 85, 85, 85.00, 0.00, 85, '2026-08-06 22:25:28', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-06 18:25:28', 1, 1, 1, 4),
(31, 5, NULL, 0, 85, 85, 85.00, 0.00, 85, '2026-08-06 22:25:31', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-06 18:25:31', 1, 1, 1, 4),
(32, 6, NULL, 0, 55, 55, 55.00, 0.00, 100, '2026-08-06 22:04:12', '', 'Efectivo', 45, 'En Mesa', 1, 'PAGADA', '2026-08-06 18:04:12', 1, 1, 1, 4),
(33, 7, NULL, 0, 50, 50, 50.00, 0.00, 100, '2026-08-06 22:04:22', '', 'Efectivo', 50, 'En Mesa', 1, 'PAGADA', '2026-08-06 18:04:22', 1, 1, 1, 4),
(34, 8, NULL, 0, 50, 50, 50.00, 0.00, 50, '2026-08-06 22:25:23', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-06 18:25:23', 1, 1, 1, 4),
(35, 9, NULL, 0, 50, 50, 50.00, 0.00, 50, '2026-08-06 22:25:20', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-06 18:25:20', 1, 1, 1, 4),
(36, 1, NULL, 0, 150, 150, 150.00, 0.00, 150, '2026-08-06 22:38:58', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-06 18:38:58', 1, 1, 1, 5),
(37, 2, NULL, 0, 55, 55, 55.00, 0.00, 55, '2026-08-06 22:53:20', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-06 18:53:20', 3, 1, 1, 5),
(38, 3, NULL, 0, 22, 22, 22.00, 0.00, 22, '2026-08-06 22:55:56', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-06 18:55:55', 1, 1, 1, 5),
(39, 4, NULL, 0, 13, 13, 13.00, 0.00, 13, '2026-08-06 22:56:27', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-06 18:56:27', 5, 1, 1, 5),
(40, 5, NULL, 0, 13, 13, 13.00, 0.00, 20, '2026-08-06 22:57:01', '', 'Efectivo', 7, 'En Mesa', 1, 'PAGADA', '2026-08-06 18:57:01', 1, 1, 1, 5),
(41, 6, NULL, 0, 13, 13, 13.00, 0.00, 50, '2026-08-06 22:57:33', '', 'Efectivo', 37, 'En Mesa', 1, 'PAGADA', '2026-08-06 18:57:33', 4, 1, 1, 5),
(42, 7, NULL, 0, 13, 13, 13.00, 0.00, 13, '2026-08-06 22:58:10', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-06 18:58:10', 4, 1, 1, 5),
(43, 8, NULL, 0, 10, 10, 10.00, 0.00, 10, '2026-08-06 23:00:45', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-06 19:00:45', 2, 1, 1, 5),
(44, 9, NULL, 0, 25, 25, 25.00, 0.00, 100, '2026-08-06 23:51:29', '', 'Efectivo', 75, 'En Mesa', 1, 'PAGADA', '2026-08-06 19:51:29', 6, 1, 1, 5),
(45, 10, NULL, 0, 15, 15, 15.00, 0.00, 15, '2026-08-06 23:52:50', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-06 19:52:50', 2, 1, 1, 5),
(46, 11, NULL, 0, 75, 75, 75.00, 0.00, 75, '2026-08-06 23:54:07', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-06 19:54:07', 2, 1, 1, 5),
(47, 12, NULL, 0, 120, 120, 125.00, 5.00, 120, '2026-08-07 00:09:07', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-06 20:09:07', 6, 1, 1, 5),
(48, 13, NULL, 0, 13, 13, 13.00, 0.00, 13, '2026-08-07 00:14:50', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-06 20:14:50', 1, 1, 1, 5),
(49, 14, NULL, 0, 10, 10, 10.00, 0.00, 10, '2026-08-07 00:15:44', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-06 20:15:44', 6, 1, 1, 5),
(50, 15, NULL, 0, 18, 18, 18.00, 0.00, 18, '2026-08-07 00:49:12', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-06 20:49:12', 5, 1, 1, 5),
(51, 16, NULL, 0, 283, 283, 283.00, 0.00, 283, '2026-08-07 02:56:09', '', 'Efectivo', 0, 'Para Llevar', 1, 'PAGADA', '2026-08-06 22:56:09', 2, 3, 1, 5),
(52, 17, NULL, 0, 55, 55, 55.00, 0.00, 55, '2026-08-23 22:14:41', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-23 18:14:41', 1, 1, 1, 5),
(53, 18, NULL, 0, 100, 100, 100.00, 0.00, 100, '2026-08-07 02:58:08', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-06 22:58:08', 1, 4, 1, 5),
(54, 19, NULL, 0, 75, 75, 75.00, 0.00, 75, '2026-08-23 22:14:38', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-23 18:14:38', 1, 1, 1, 5),
(57, 20, NULL, 0, 55, 55, 55.00, 0.00, 500, '2026-08-07 04:18:09', '', 'Efectivo', 445, 'En Mesa', 1, 'PAGADA', '2026-08-07 00:18:09', 1, 1, 1, 5),
(59, 21, NULL, 0, 75, 75, 75.00, 0.00, 100, '2026-08-07 04:26:40', '', 'Efectivo', 25, 'En Mesa', 1, 'PAGADA', '2026-08-07 00:26:40', 1, 1, 1, 5),
(60, 22, NULL, 0, 10, 10, 10.00, 0.00, 100, '2026-08-07 04:27:17', '', 'Efectivo', 90, 'En Mesa', 1, 'PAGADA', '2026-08-07 00:27:17', 1, 1, 1, 5),
(61, 23, NULL, 0, 315, 315, 315.00, 0.00, 315, '2026-08-16 23:14:02', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-16 19:14:02', 1, 1, 1, 5),
(62, 1, NULL, 0, 1357, 1357, 1357.00, 0.00, 1400, '2026-08-23 22:38:06', '', 'Efectivo', 43, 'En Mesa', 1, 'PAGADA', '2026-08-23 18:38:05', 1, 1, 1, 6),
(63, 2, NULL, 0, 55, 55, 55.00, 0.00, 55, '2026-08-24 03:42:54', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-23 23:42:54', 1, 1, 1, 6),
(64, 3, NULL, 0, 1155, 1155, 1155.00, 0.00, 2000, '2026-08-23 23:07:01', '', 'Efectivo', 845, 'En Mesa', 1, 'PAGADA', '2026-08-23 19:07:01', 1, 1, 1, 6),
(65, 4, NULL, 0, 55, 55, 55.00, 0.00, 55, '2026-08-24 03:42:44', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-23 23:42:44', 1, 1, 1, 6),
(66, 5, NULL, 0, 55, 55, 55.00, 0.00, 60, '2026-08-23 23:59:50', '', 'Efectivo', 5, 'En Mesa', 1, 'PAGADA', '2026-08-23 19:59:50', 1, 1, 1, 6),
(67, 6, NULL, 0, 55, 55, 55.00, 0.00, 55, '2026-08-24 03:42:51', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-23 23:42:51', 1, 1, 1, 6),
(68, 7, NULL, 0, 75, 75, 75.00, 0.00, 75, '2026-08-24 03:42:29', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-23 23:42:29', 1, 1, 1, 6),
(69, 8, NULL, 0, 220, 220, 220.00, 0.00, 220, '2026-08-24 03:42:38', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-23 23:42:38', 1, 1, 1, 6),
(70, 9, NULL, 0, 55, 55, 55.00, 0.00, 55, '2026-08-24 03:42:32', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-23 23:42:32', 1, 1, 1, 6),
(71, 10, NULL, 0, 110, 110, 110.00, 0.00, 110, '2026-08-24 03:42:24', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-23 23:42:24', 1, 1, 1, 6),
(72, 11, NULL, 0, 55, 55, 55.00, 0.00, 100, '2026-08-24 00:05:11', '', 'Efectivo', 45, 'En Mesa', 1, 'PAGADA', '2026-08-23 20:05:11', 1, 1, 1, 6),
(73, 12, NULL, 0, 115, 115, 115.00, 0.00, 115, '2026-08-24 03:42:20', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-23 23:42:20', 1, 1, 1, 6),
(74, 13, NULL, 0, 465, 465, 480.00, 15.00, 500, '2026-08-24 00:48:03', '', 'Efectivo', 35, 'En Mesa', 1, 'PAGADA', '2026-08-23 20:48:03', 1, 1, 1, 6),
(75, 14, NULL, 0, 22, 22, 22.00, 0.00, 22, '2026-08-24 03:42:17', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-23 23:42:17', 1, 1, 1, 6),
(76, 15, NULL, 0, 110, 110, 110.00, 0.00, 110, '2026-08-24 03:42:14', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-23 23:42:14', 1, 1, 1, 6),
(77, 16, NULL, 0, 1285, 1285, 1285.00, 0.00, 1285, '2026-08-24 03:42:10', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-23 23:42:10', 1, 1, 1, 6),
(78, 17, NULL, 0, 130, 130, 130.00, 0.00, 130, '2026-08-24 01:13:28', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-23 21:13:28', 1, 5, 1, 6),
(79, 1, NULL, 0, 55, 55, 55.00, 0.00, 55, '2026-08-24 04:08:05', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-24 00:08:05', 1, 1, 1, 7);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `arqueo_caja`
--
ALTER TABLE `arqueo_caja`
  ADD PRIMARY KEY (`id`),
  ADD KEY `arqueo_caja_id_usuario_foreign` (`id_usuario`),
  ADD KEY `fk_arqueo_caja` (`id_caja`);

--
-- Indexes for table `cajas`
--
ALTER TABLE `cajas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_compra_usuario` (`id_usuario`),
  ADD KEY `fk_compra_proveedor` (`id_proveedor`),
  ADD KEY `id_arqueo_caja` (`id_arqueo_caja`);

--
-- Indexes for table `detalle_compra`
--
ALTER TABLE `detalle_compra`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_detalle_compra_producto` (`id_producto`),
  ADD KEY `fk_detalle_compra_compra` (`id_compra`);

--
-- Indexes for table `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_producto` (`id_producto`),
  ADD KEY `fk_venta` (`id_venta`),
  ADD KEY `idx_dv_promocion` (`id_promocion`);

--
-- Indexes for table `gastos`
--
ALTER TABLE `gastos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_gastos_tipo_gasto` (`id_tipo_gasto`) USING BTREE,
  ADD KEY `fk_gastos_id_usuario` (`id_usuario`) USING BTREE,
  ADD KEY `fk_gastos_id_arqueo` (`id_arqueo`);

--
-- Indexes for table `meseros`
--
ALTER TABLE `meseros`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `otros_ingresos`
--
ALTER TABLE `otros_ingresos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_oi_arqueo` (`id_arqueo_caja`),
  ADD KEY `idx_oi_usuario` (`id_usuario`),
  ADD KEY `idx_oi_estado` (`estado`);

--
-- Indexes for table `perfiles`
--
ALTER TABLE `perfiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_perfiles_nombre` (`nombre`),
  ADD KEY `idx_perfiles_estado` (`estado`),
  ADD KEY `idx_perfiles_activo` (`activo`);

--
-- Indexes for table `perfil_permisos`
--
ALTER TABLE `perfil_permisos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_perfil_permiso` (`id_perfil`,`id_permiso`),
  ADD KEY `idx_pp_perfil` (`id_perfil`),
  ADD KEY `idx_pp_permiso` (`id_permiso`);

--
-- Indexes for table `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_permisos_codigo` (`codigo`),
  ADD KEY `idx_permisos_modulo` (`modulo`),
  ADD KEY `idx_permisos_estado` (`estado`);

--
-- Indexes for table `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indexes for table `promociones`
--
ALTER TABLE `promociones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_promo_estado` (`estado`),
  ADD KEY `idx_promo_fechas` (`fecha_inicio`,`fecha_fin`),
  ADD KEY `idx_promo_prioridad` (`prioridad`),
  ADD KEY `idx_promo_nombre` (`nombre`);

--
-- Indexes for table `promocion_intervalos`
--
ALTER TABLE `promocion_intervalos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pi_promocion` (`id_promocion`),
  ADD KEY `idx_pi_cantidades` (`cantidad_minima`,`cantidad_maxima`),
  ADD KEY `idx_pi_estado` (`estado`);

--
-- Indexes for table `promocion_productos`
--
ALTER TABLE `promocion_productos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_promo_producto` (`id_promocion`,`id_producto`),
  ADD KEY `idx_pp_promocion` (`id_promocion`),
  ADD KEY `idx_pp_producto` (`id_producto`),
  ADD KEY `idx_pp_estado` (`estado`);

--
-- Indexes for table `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tipo_gasto`
--
ALTER TABLE `tipo_gasto`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_usuarios_perfil` (`id_perfil`);

--
-- Indexes for table `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_mesero` (`id_mesero`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_vendedor` (`id_vendedor`),
  ADD KEY `id_arqueo_caja` (`id_arqueo_caja`) USING BTREE,
  ADD KEY `idx_ventas_estado_pago` (`estado_pago`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `arqueo_caja`
--
ALTER TABLE `arqueo_caja`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cajas`
--
ALTER TABLE `cajas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `compras`
--
ALTER TABLE `compras`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `detalle_compra`
--
ALTER TABLE `detalle_compra`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `detalle_venta`
--
ALTER TABLE `detalle_venta`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT for table `gastos`
--
ALTER TABLE `gastos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `meseros`
--
ALTER TABLE `meseros`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `otros_ingresos`
--
ALTER TABLE `otros_ingresos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `perfiles`
--
ALTER TABLE `perfiles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `perfil_permisos`
--
ALTER TABLE `perfil_permisos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=907;

--
-- AUTO_INCREMENT for table `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `promociones`
--
ALTER TABLE `promociones`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `promocion_intervalos`
--
ALTER TABLE `promocion_intervalos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `promocion_productos`
--
ALTER TABLE `promocion_productos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tipo_gasto`
--
ALTER TABLE `tipo_gasto`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `arqueo_caja`
--
ALTER TABLE `arqueo_caja`
  ADD CONSTRAINT `arqueo_caja_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `fk_arqueo_caja` FOREIGN KEY (`id_caja`) REFERENCES `cajas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `fk_compra_proveedor` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id`),
  ADD CONSTRAINT `fk_compra_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);

--
-- Constraints for table `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD CONSTRAINT `fk_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_venta` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `otros_ingresos`
--
ALTER TABLE `otros_ingresos`
  ADD CONSTRAINT `fk_oi_arqueo` FOREIGN KEY (`id_arqueo_caja`) REFERENCES `arqueo_caja` (`id`),
  ADD CONSTRAINT `fk_oi_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);

--
-- Constraints for table `perfil_permisos`
--
ALTER TABLE `perfil_permisos`
  ADD CONSTRAINT `fk_pp_perfil` FOREIGN KEY (`id_perfil`) REFERENCES `perfiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pp_permiso` FOREIGN KEY (`id_permiso`) REFERENCES `permisos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `promocion_intervalos`
--
ALTER TABLE `promocion_intervalos`
  ADD CONSTRAINT `fk_pi_promocion` FOREIGN KEY (`id_promocion`) REFERENCES `promociones` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `promocion_productos`
--
ALTER TABLE `promocion_productos`
  ADD CONSTRAINT `fk_pp_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pp_promocion` FOREIGN KEY (`id_promocion`) REFERENCES `promociones` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_perfil` FOREIGN KEY (`id_perfil`) REFERENCES `perfiles` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
