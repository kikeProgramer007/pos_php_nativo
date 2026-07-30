-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 30, 2026 at 12:28 AM
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
(1, '2026-07-28 23:19:49', '2026-07-28 23:32:41', 0, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0.00, 0.00, 15.00, 0.00, 15.00, 0.00, 0.00, 0.00, 15.00, 15.00, 0.00, 15.00, 0.00, 1, 15.00, 'cerrada', 2, NULL, 1, 1),
(2, '2026-07-28 23:33:42', '2026-07-28 23:33:59', 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0.00, 0.00, 18.00, 0.00, 18.00, 0.00, 0.00, 0.00, 18.00, 50.00, 0.00, 50.00, 32.00, 1, 15.00, 'cerrada', 1, NULL, 1, 1),
(3, '2026-07-28 23:34:25', '2026-07-28 23:34:40', 0, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0.00, 0.00, 15.00, 0.00, 15.00, 0.00, 0.00, 0.00, 15.00, 15.00, 0.00, 15.00, 0.00, 1, 15.00, 'cerrada', 1, NULL, 1, 1),
(4, '2026-07-28 23:39:16', '2026-07-28 23:40:24', 0, 0, 0, 1, 0, 1, 0, 0, 0, 0, 0.00, 0.00, 25.00, 0.00, 25.00, 0.00, 100.00, 100.00, -75.00, 25.00, 0.00, 25.00, -50.00, 1, 15.00, 'cerrada', 1, NULL, 1, 1),
(5, '2026-07-28 23:45:49', '2026-07-28 23:46:54', 1, 0, 0, 0, 1, 0, 0, 1, 0, 0, 0.00, 0.00, 210.00, 1.00, 211.00, 0.00, 0.00, 0.00, 211.00, 211.00, 0.00, 211.00, 0.00, 0, 0.00, 'cerrada', 4, NULL, 1, 1),
(6, '2026-07-28 23:56:08', '2026-07-29 23:27:21', 1, 0, 0, 1, 0, 1, 0, 0, 0, 0, 0.00, 0.00, 225.00, 0.00, 225.00, 0.00, 0.00, 0.00, 225.00, 225.00, 0.00, 225.00, 0.00, 1, 15.00, 'cerrada', 4, NULL, 1, 1);

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
(1, 's/n', '2025-03-08 21:40:34', 1);

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
  `precio_compra` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `preferencias` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nota_adicional` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `forma_atencion` char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detalle_venta`
--

INSERT INTO `detalle_venta` (`id`, `id_producto`, `id_venta`, `producto`, `cantidad`, `precio_venta`, `precio_compra`, `subtotal`, `preferencias`, `nota_adicional`, `forma_atencion`) VALUES
(1, 1, 1, 'Paletas Q\' Deli', 1, 15.00, 10.00, 15.00, NULL, NULL, 'M'),
(2, 1, 2, 'Paletas Q\' Deli', 1, 15.00, 10.00, 15.00, NULL, NULL, 'M'),
(3, 28, 3, 'Sopa de Maní', 1, 18.00, 15.00, 18.00, NULL, NULL, 'M'),
(4, 1, 4, 'Paletas Q\' Deli', 1, 15.00, 10.00, 15.00, NULL, NULL, 'M'),
(5, 8, 5, 'Corona', 1, 25.00, 20.00, 25.00, NULL, NULL, 'M'),
(6, 6, 6, 'Vino Campos del Solana', 1, 50.00, 40.00, 50.00, NULL, NULL, 'M'),
(7, 21, 7, 'Chicha Jarra Grande', 1, 20.00, 15.00, 20.00, NULL, NULL, 'M'),
(8, 36, 8, 'Tablita Mixta 2 personas(Cuadril, cordon blue, chorizo, yuca frita y papas fritas)', 1, 100.00, 90.00, 100.00, NULL, NULL, 'M'),
(9, 8, 9, 'Corona', 1, 25.00, 20.00, 25.00, NULL, NULL, 'M'),
(10, 1, 10, 'Paletas Q\' Deli', 1, 15.00, 10.00, 15.00, NULL, NULL, 'M'),
(11, 6, 11, 'Vino Campos del Solana', 1, 50.00, 40.00, 50.00, NULL, NULL, 'M'),
(12, 1, 12, 'Paletas Q\' Deli', 1, 15.00, 10.00, 15.00, NULL, NULL, 'M'),
(13, 9, 13, 'Huari 620 ml', 5, 32.00, 30.00, 160.00, NULL, NULL, 'M');

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
(1, 's/n', '0000000', '00000000', 's/n', 32, '2026-07-29 23:26:42', '2026-07-30 03:26:42', 1),
(2, 'Belen Figueroa Miranda', ' 8870938', ' 690-90-581', 'Cotoca B/ San Marino', 1, '2026-07-27 00:02:53', '2026-07-27 04:28:34', 1),
(3, 'Raquel Taceo', '8160365', '123-45-678', 'Cotoca -Barrio las madresitas sector los tojos', 0, NULL, '2026-07-27 03:58:16', 1),
(4, 'Vanessa surubi paticu ', '14773348', '123-45-678', 'Calle 9 de abril atras de la escuelita vieja', 0, NULL, '2026-07-27 03:59:02', 1),
(5, 'Carla Viviana Tiain Bairo', '14138040', '123-45-678', 'B/ San Antonio', 1, '2026-07-27 00:11:45', '2026-07-27 04:11:45', 1);

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
(1, 1, '101', 'Paletas Q\' Deli', 'vistas/img/productos/101/641.jpg', 43, 15, 10, 7, '2026-07-30 03:26:31', 1, 1),
(2, 1, '102', 'Cheesecake de Oreo', 'vistas/img/productos/102/731.png', 50, 15, 10, 0, '2026-07-30 03:06:42', 1, 1),
(3, 2, '201', 'ron flor de caña', 'vistas/img/productos/201/162.png', 50, 120, 100, 0, '2026-07-30 02:52:42', 1, 1),
(4, 2, '202', 'Ron Habana Club', 'vistas/img/productos/202/609.webp', 50, 180, 150, 0, '2026-07-30 02:53:16', 1, 1),
(5, 2, '203', 'Vino Kohlberg', 'vistas/img/productos/203/469.webp', 50, 50, 40, 0, '2026-07-30 03:14:41', 1, 1),
(6, 2, '204', 'Vino Campos del Solana', 'vistas/img/productos/204/418.webp', 46, 50, 40, 4, '2026-07-30 03:26:21', 1, 1),
(7, 2, '205', 'Balde de Coronas (5 unidades)', 'vistas/img/productos/default/anonymous.webp', 49, 120, 100, 1, '2026-07-29 02:45:52', 1, 1),
(8, 2, '206', 'Corona', 'vistas/img/productos/206/236.png', 53, 25, 20, 2, '2026-07-30 02:54:03', 1, 1),
(9, 2, '207', 'Huari 620 ml', 'vistas/img/productos/207/939.webp', 45, 32, 30, 5, '2026-07-30 03:26:42', 1, 1),
(10, 3, '301', 'Agua con Gas (500 ml)', 'vistas/img/productos/301/151.webp', 50, 8, 5, 0, '2026-07-30 02:54:39', 1, 1),
(11, 3, '302', 'Power de 1 Lt', 'vistas/img/productos/302/864.png', 50, 15, 10, 0, '2026-07-30 02:55:26', 1, 1),
(12, 3, '303', 'Power de 500 ml', 'vistas/img/productos/303/353.png', 50, 10, 5, 0, '2026-07-30 02:56:05', 1, 1),
(13, 3, '304', 'Agua 500 ml', 'vistas/img/productos/304/974.png', 50, 7, 5, 0, '2026-07-30 02:56:27', 1, 1),
(14, 3, '305', 'Soda Popular', 'vistas/img/productos/305/106.png', 50, 13, 10, 0, '2026-07-30 02:56:39', 1, 1),
(15, 3, '306', 'Soda 2 Lt', 'vistas/img/productos/306/425.jpg', 50, 22, 20, 0, '2026-07-30 02:57:16', 1, 1),
(16, 3, '307', 'Soda Personal', 'vistas/img/productos/307/302.webp', 50, 10, 8, 0, '2026-07-30 02:57:30', 1, 1),
(17, 3, '308', 'Tropifrut', 'vistas/img/productos/308/810.png', 60, 15, 10, 0, '2026-07-30 02:57:44', 1, 1),
(18, 3, '309', 'Limonada Jarra Mediana', 'vistas/img/productos/309/448.png', 50, 16, 14, 0, '2026-07-30 02:58:31', 0, 1),
(19, 3, '310', 'Limonada Jarra Grande', 'vistas/img/productos/310/244.png', 50, 22, 20, 0, '2026-07-30 02:58:38', 0, 1),
(20, 3, '311', 'Chicha Jarra Mediana', 'vistas/img/productos/311/416.png', 50, 15, 10, 0, '2026-07-30 02:58:56', 0, 1),
(21, 3, '312', 'Chicha Jarra Grande', 'vistas/img/productos/312/991.png', 47, 20, 15, 3, '2026-07-30 02:59:14', 1, 1),
(22, 4, '401', 'Cuadril (Carne Extra)', 'vistas/img/productos/401/311.png', 50, 40, 30, 0, '2026-07-30 02:59:37', 0, 1),
(23, 4, '402', 'Cordon Blue', 'vistas/img/productos/402/339.png', 50, 35, 30, 0, '2026-07-30 03:20:08', 0, 1),
(24, 4, '403', 'Chorizo', 'vistas/img/productos/403/352.png', 50, 12, 10, 0, '2026-07-30 03:20:23', 0, 1),
(25, 4, '404', 'Arroz', 'vistas/img/productos/404/556.webp', 50, 10, 8, 0, '2026-07-30 03:20:36', 0, 1),
(26, 4, '405', 'Yuca Frita', 'vistas/img/productos/405/548.png', 50, 13, 10, 0, '2026-07-30 03:20:51', 0, 1),
(27, 4, '406', 'Papas Fritas', 'vistas/img/productos/406/195.webp', 50, 15, 10, 0, '2026-07-30 03:21:05', 0, 1),
(28, 5, '501', 'Sopa de Maní', 'vistas/img/productos/501/405.png', 46, 18, 15, 4, '2026-07-30 03:21:25', 0, 1),
(29, 6, '601', 'Keperí (Arroz con queso, yuca y ensalada)', 'vistas/img/productos/601/878.png', 50, 55, 40, 0, '2026-07-30 03:21:36', 0, 1),
(30, 6, '602', 'Ojo de Bife (Arroz con queso, yuca y ensalada)', 'vistas/img/productos/602/501.png', 50, 60, 50, 0, '2026-07-30 03:21:54', 0, 1),
(31, 6, '603', 'Costilla a la Parrilla (Corte español, arroz con queso, yuca frita y ensalada)', 'vistas/img/productos/default/anonymous.webp', 50, 60, 50, 0, '2026-07-27 04:32:34', 0, 1),
(32, 6, '604', 'Cuadril Personal (Cuadril ,chorizo, arroz con queso, yuca y ensalada)', 'vistas/img/productos/default/anonymous.webp', 50, 55, 50, 0, '2026-07-27 04:32:28', 0, 1),
(33, 6, '605', 'Chancho a la Caja China Personal (Chancho, arroz, yuca y ensalada)', 'vistas/img/productos/605/118.png', 50, 55, 50, 0, '2026-07-30 03:22:10', 0, 1),
(34, 6, '606', 'Costilla a la Parrilla 2 personas(Corte español, arroz con queso, yuca frita y ensalada)', 'vistas/img/productos/default/anonymous.webp', 50, 85, 70, 0, '2026-07-27 04:32:17', 0, 1),
(35, 6, '607', 'Tablita Personal (Cuadril, chorizo, yuca frita y papas fritas)', 'vistas/img/productos/default/anonymous.webp', 50, 75, 70, 0, '2026-07-27 04:32:12', 0, 1),
(36, 6, '608', 'Tablita Mixta 2 personas(Cuadril, cordon blue, chorizo, yuca frita y papas fritas)', 'vistas/img/productos/default/anonymous.webp', 42, 100, 90, 8, '2026-07-29 03:46:16', 0, 1);

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
(1, 'soporte', 'soporte', '$2a$07$asxx54ahjppf45sd87a5auGZEtGHuyZwm.Ur.FJvWLCql3nmsMbXy', 'Administrador', 'vistas/img/usuarios/admin/997.webp', 1, '2026-07-29 23:24:19', '2026-07-30 03:24:19', 1);

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

INSERT INTO `ventas` (`id`, `codigo`, `nro_ticket`, `total_qr`, `total_efectivo`, `total`, `total_pagado`, `fecha`, `nota`, `tipo_pago`, `cambio`, `forma_atencion`, `estado`, `estado_pago`, `fecha_pago`, `id_mesero`, `id_cliente`, `id_vendedor`, `id_arqueo_caja`) VALUES
(1, 1, NULL, 0, 15, 15, 15, '2026-07-29 03:46:36', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-07-28 23:46:36', 1, 1, 1, 5),
(2, 2, NULL, 0, 15, 15, 15, '2026-07-29 03:20:38', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-07-28 23:20:38', 1, 1, 1, 1),
(3, 1, NULL, 0, 18, 18, 50, '2026-07-29 03:33:54', '', 'Efectivo', 32, 'En Mesa', 1, 'PAGADA', '2026-07-28 23:33:54', 1, 1, 1, 2),
(4, 1, NULL, 0, 15, 15, 15, '2026-07-29 03:34:34', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-07-28 23:34:34', 1, 1, 1, 3),
(5, 1, NULL, 0, 25, 25, 25, '2026-07-29 03:40:13', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-07-28 23:40:13', 1, 1, 1, 4),
(6, 1, NULL, 0, 50, 50, 50, '2026-07-29 03:45:58', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-07-28 23:45:58', 1, 1, 1, 5),
(7, 2, NULL, 0, 20, 20, 50, '2026-07-29 03:46:09', '', 'Efectivo', 30, 'En Mesa', 1, 'PAGADA', '2026-07-28 23:46:09', 1, 1, 1, 5),
(8, 3, NULL, 0, 100, 100, 100, '2026-07-29 03:46:16', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-07-28 23:46:16', 1, 1, 1, 5),
(9, 4, NULL, 0, 25, 25, 25, '2026-07-29 03:46:33', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-07-28 23:46:33', 1, 1, 1, 5),
(10, 1, NULL, 0, 0, 15, 0, '2026-07-29 03:57:14', '', '', 0, 'En Mesa', 1, 'PENDIENTE', NULL, 1, 1, 1, 6),
(11, 2, NULL, 0, 50, 50, 50, '2026-07-30 03:26:21', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-07-29 23:26:21', 1, 1, 1, 6),
(12, 3, NULL, 0, 15, 15, 15, '2026-07-30 03:26:31', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-07-29 23:26:31', 1, 1, 1, 6),
(13, 4, NULL, 0, 160, 160, 160, '2026-07-30 03:26:42', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-07-29 23:26:42', 1, 1, 1, 6);

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
  ADD KEY `fk_venta` (`id_venta`);

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
