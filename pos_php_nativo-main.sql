-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 06, 2026 at 05:19 PM
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
(3, '2026-08-06 14:33:59', NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 470.00, 0.00, 470.00, 0.00, 470.00, 0.00, 0.00, 0.00, 470.00, 0.00, 0.00, 0.00, 0.00, 0, 0.00, 'abierta', 8, NULL, 1, 1);

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
(1, 'Caja de ventas', '1', 8, 1),
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
(1, 'postres', '2026-07-27 03:01:08', 1),
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
(2, 'marcos', '2026-08-03 00:30:05', 1);

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
  `id_arqueo_caja` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `compras`
--

INSERT INTO `compras` (`id`, `codigo`, `total`, `id_usuario`, `id_proveedor`, `fecha_alta`, `estado`, `id_arqueo_caja`) VALUES
(1, 1, 29500.00, 1, 1, '2026-07-27 04:35:06', 1, 1),
(2, 2, 100.00, 1, 1, '2026-07-29 03:39:39', 1, 4);

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
(19, 8, 2, 'Corona', 5, 20.00, 100.00);

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
  `tipo_descuento` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `valor_descuento` decimal(10,2) DEFAULT NULL,
  `descuento_unitario` decimal(10,2) DEFAULT '0.00',
  `descuento_total` decimal(10,2) DEFAULT '0.00',
  `id_promocion` int DEFAULT NULL,
  `id_intervalo_promocion` int DEFAULT NULL,
  `nombre_promocion` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
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
(27, 33, 22, 'Chancho a la Caja China Personal ', 1, 55.00, 55.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 50.00, 55.00, NULL, NULL, 'M');

-- --------------------------------------------------------

--
-- Table structure for table `gastos`
--

CREATE TABLE `gastos` (
  `id` int NOT NULL,
  `fecha` date NOT NULL,
  `descripcion` varchar(500) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
  `monto` decimal(11,2) NOT NULL DEFAULT '0.00',
  `forma_pago` varchar(100) CHARACTER SET utf32 COLLATE utf32_spanish_ci NOT NULL,
  `id_tipo_gasto` int NOT NULL,
  `id_usuario` int NOT NULL,
  `id_arqueo` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

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
(1, 's/n', '0000000', '00000000', 's/n', 112, '2026-08-06 16:18:55', '2026-08-06 20:18:55', 1),
(2, 'Belen Figueroa Miranda', ' 8870938', ' 690-90-581', 'Cotoca B/ San Marino', 4, '2026-08-01 23:01:33', '2026-08-03 00:31:05', 1),
(3, 'Raquel Taceo', '8160365', '123-45-678', 'Cotoca -Barrio las madresitas sector los tojos', 0, NULL, '2026-07-27 03:58:16', 1),
(4, 'Vanessa surubi paticu ', '14773348', '123-45-678', 'Calle 9 de abril atras de la escuelita vieja', 0, '2026-08-02 15:37:39', '2026-08-02 19:38:22', 1),
(5, 'Carla Viviana Tiain Bairo', '14138040', '123-45-678', 'B/ San Antonio', 2, '2026-07-27 00:11:45', '2026-08-03 00:42:08', 1);

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
(1, 1, '101', 'Paletas Q\' Deli', 'vistas/img/productos/101/641.jpg', 39, 15, 10, 11, '2026-08-03 00:32:55', 1, 1),
(2, 1, '102', 'Cheesecake de Oreo', 'vistas/img/productos/102/731.png', 50, 15, 10, 0, '2026-07-30 03:06:42', 1, 1),
(3, 2, '201', 'ron flor de caña', 'vistas/img/productos/201/162.png', 50, 120, 100, 0, '2026-07-30 02:52:42', 1, 1),
(4, 2, '202', 'Ron Habana Club', 'vistas/img/productos/202/609.webp', 50, 180, 150, 0, '2026-07-30 02:53:16', 1, 1),
(5, 2, '203', 'Vino Kohlberg', 'vistas/img/productos/203/469.webp', 50, 50, 40, 0, '2026-07-30 03:14:41', 1, 1),
(6, 2, '204', 'Vino Campos del Solana', 'vistas/img/productos/204/418.webp', 44, 50, 40, 6, '2026-08-03 00:30:47', 1, 1),
(8, 2, '206', 'Corona', 'vistas/img/productos/206/236.png', 16, 25, 20, 39, '2026-08-03 01:58:11', 1, 1),
(9, 2, '207', 'Huari 620 ml', 'vistas/img/productos/207/939.webp', 34, 32, 30, 16, '2026-08-03 01:42:39', 1, 1),
(10, 3, '301', 'Agua con Gas (500 ml)', 'vistas/img/productos/301/151.webp', 50, 8, 5, 0, '2026-07-30 02:54:39', 1, 1),
(11, 3, '302', 'Power de 1 Lt', 'vistas/img/productos/302/864.png', 50, 15, 10, 0, '2026-07-30 02:55:26', 1, 1),
(12, 3, '303', 'Power de 500 ml', 'vistas/img/productos/303/353.png', 50, 10, 5, 0, '2026-07-30 02:56:05', 1, 1),
(13, 3, '304', 'Agua 500 ml', 'vistas/img/productos/304/974.png', 50, 7, 5, 0, '2026-07-30 02:56:27', 1, 1),
(14, 3, '305', 'Soda Popular', 'vistas/img/productos/305/106.png', 49, 13, 10, 1, '2026-08-03 00:39:38', 1, 1),
(15, 3, '306', 'Soda 2 Lt', 'vistas/img/productos/306/425.jpg', 50, 22, 20, 0, '2026-07-30 02:57:16', 1, 1),
(16, 3, '307', 'Soda Personal', 'vistas/img/productos/307/302.webp', 50, 10, 8, 0, '2026-07-30 02:57:30', 1, 1),
(17, 3, '308', 'Tropifrut', 'vistas/img/productos/308/810.png', 60, 15, 10, 0, '2026-07-30 02:57:44', 1, 1),
(18, 3, '309', 'Limonada Jarra Mediana', 'vistas/img/productos/309/448.png', 49, 16, 14, 1, '2026-08-02 03:01:33', 0, 1),
(19, 3, '310', 'Limonada Jarra Grande', 'vistas/img/productos/310/244.png', 49, 22, 20, 1, '2026-08-02 19:38:22', 0, 1),
(20, 3, '311', 'Chicha Jarra Mediana', 'vistas/img/productos/311/416.png', 49, 15, 10, 1, '2026-08-02 22:39:10', 0, 1),
(21, 3, '312', 'Chicha Jarra Grande', 'vistas/img/productos/312/991.png', 47, 20, 15, 3, '2026-07-30 02:59:14', 1, 1),
(22, 4, '401', 'Cuadril (Carne Extra)', 'vistas/img/productos/401/311.png', 50, 40, 30, 0, '2026-07-30 02:59:37', 0, 1),
(23, 4, '402', 'Porción Cordon Blue', 'vistas/img/productos/402/339.png', 50, 35, 30, 0, '2026-08-06 19:48:35', 0, 1),
(24, 4, '403', 'Porción de Chorizo', 'vistas/img/productos/403/352.png', 48, 12, 10, 2, '2026-08-06 19:46:59', 0, 1),
(25, 4, '404', 'porción de Arroz', 'vistas/img/productos/404/556.webp', 50, 10, 8, 0, '2026-08-06 19:45:55', 0, 1),
(26, 4, '405', 'Porción Yuca Frita', 'vistas/img/productos/405/548.png', 49, 13, 10, 1, '2026-08-06 19:46:31', 0, 1),
(27, 4, '406', 'Porción de Papas Fritas', 'vistas/img/productos/406/195.webp', 50, 15, 10, 0, '2026-08-06 19:47:32', 0, 1),
(28, 5, '501', 'Sopa de Maní', 'vistas/img/productos/501/405.png', 42, 18, 15, 8, '2026-08-02 23:11:31', 0, 1),
(29, 6, '601', 'Keperí ', 'vistas/img/productos/601/878.png', 48, 55, 40, 2, '2026-08-06 18:18:56', 0, 1),
(30, 6, '602', 'Ojo de Bife ', 'vistas/img/productos/602/171.png', 50, 60, 50, 0, '2026-08-06 19:41:14', 0, 1),
(31, 6, '603', 'Costilla a la Parrilla ', 'vistas/img/productos/603/653.png', 50, 60, 50, 0, '2026-08-06 19:14:28', 0, 1),
(32, 6, '604', 'Cuadril Personal', 'vistas/img/productos/604/948.png', 50, 55, 50, 0, '2026-08-06 19:49:51', 0, 1),
(33, 6, '605', 'Chancho a la Caja China Personal ', 'vistas/img/productos/605/118.png', 37, 55, 50, 13, '2026-08-06 20:18:55', 0, 1),
(34, 6, '606', 'Costilla a la Parrilla 2 personas', 'vistas/img/productos/606/965.png', 48, 85, 70, 2, '2026-08-06 20:18:39', 0, 1),
(35, 6, '607', 'Tablita Personal ', 'vistas/img/productos/607/901.png', 50, 75, 70, 0, '2026-08-06 19:08:07', 0, 1),
(36, 6, '608', 'Tablita Mixta 2 personas', 'vistas/img/productos/608/301.png', 42, 100, 90, 8, '2026-08-06 19:05:39', 0, 1);

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
(2, 'promolocura', 'fulll', '2026-08-02 21:51:00', '2037-05-02 21:51:00', 3, 1, 'individual', 'rico', '2026-08-03 01:52:02');

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
(2, 2, 3, 3, 'fijo', 5.00, 1, '2026-08-03 01:53:16');

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
(3, 2, 33, 1, '2026-08-03 01:54:16');

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
  `foto` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `ultimo_login` datetime DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `activo` tinyint NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `usuario`, `password`, `perfil`, `foto`, `estado`, `ultimo_login`, `fecha`, `activo`) VALUES
(1, 'soporte', 'soporte', '$2a$07$asxx54ahjppf45sd87a5auGZEtGHuyZwm.Ur.FJvWLCql3nmsMbXy', 'Administrador', 'vistas/img/usuarios/admin/997.webp', 1, '2026-08-06 14:13:21', '2026-08-06 18:13:21', 1),
(3, 'Irys Gabriela Vargas Jimenez ', 'Gabriela ', '$2a$07$asxx54ahjppf45sd87a5auTjc6l.msIbvUzGvRzKgYOcSUPnmTqBa', 'Administrador', 'vistas/img/usuarios/default/anonymous.webp', 1, '2026-08-02 17:43:05', '2026-08-06 19:54:13', 1),
(4, 'Daniel Rico roca ', 'daniel', '$2a$07$asxx54ahjppf45sd87a5auNeOt1twHeRTIMuKpA4DwE3ykFdG8v2q', 'Vendedor', 'vistas/img/usuarios/default/anonymous.webp', 1, NULL, '2026-08-06 19:54:51', 1);

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
(22, 8, NULL, 0, 55, 55, 55.00, 0.00, 55, '2026-08-06 20:19:12', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-08-06 16:19:12', 1, 1, 1, 3);

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
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `compras`
--
ALTER TABLE `compras`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `detalle_compra`
--
ALTER TABLE `detalle_compra`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `detalle_venta`
--
ALTER TABLE `detalle_venta`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `gastos`
--
ALTER TABLE `gastos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `meseros`
--
ALTER TABLE `meseros`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `promociones`
--
ALTER TABLE `promociones`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `promocion_intervalos`
--
ALTER TABLE `promocion_intervalos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `promocion_productos`
--
ALTER TABLE `promocion_productos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tipo_gasto`
--
ALTER TABLE `tipo_gasto`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
