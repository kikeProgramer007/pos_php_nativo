-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 26, 2026 at 07:22 PM
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
  `estado` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nroTicket` int NOT NULL,
  `tipo_cambio` decimal(11,2) DEFAULT NULL,
  `id_caja` int NOT NULL,
  `id_usuario` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 'Caja de ventas', '1', 1, 1),
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
(1, 'broasterd', '2025-01-05 21:42:24', 1),
(2, 'brasa', '2025-01-05 21:42:30', 1),
(3, 'sodas', '2025-01-05 21:42:42', 1),
(4, 'jugos', '2025-01-05 21:42:46', 1),
(5, 'salchipapas', '2025-01-06 01:31:55', 1),
(6, ' guarniciones', '2025-04-26 02:16:04', 1);

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
(1, 's/n', 'sin carnet', '000-00-000', 'sin direccion', 311, '2026-05-26 18:18:52', '2026-05-26 22:18:52', 1);

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
(1, 1, '101', 'entero broasterd', 'vistas/img/productos/101/884.webp', 2, 100, 80, 0, '2026-04-18 18:13:47', 0, 1),
(2, 1, '102', '1/2 broasterd', 'vistas/img/productos/102/847.webp', 1, 50, 40, 2, '2026-04-18 19:52:45', 0, 1),
(3, 1, '103', '1/4 pecho broasterd', 'vistas/img/productos/103/138.webp', 2, 25, 22, 2, '2026-04-18 18:13:15', 0, 1),
(4, 1, '108', '1/4 pierna broasterd', 'vistas/img/productos/108/247.webp', 2, 25, 22, 1, '2026-04-18 18:13:30', 0, 1),
(5, 1, '104', 'eco pecho broasterd', 'vistas/img/productos/104/897.webp', 5, 15, 13, 12, '2026-04-18 19:51:57', 0, 1),
(6, 1, '106', 'eco ala broasterd', 'vistas/img/productos/106/670.webp', 4, 15, 13, 13, '2026-04-18 19:48:54', 0, 1),
(7, 1, '105', ' eco pierna broasterd', 'vistas/img/productos/105/476.webp', 7, 15, 13, 13, '2026-04-18 19:39:43', 0, 1),
(8, 1, '107', 'eco contra broasterd', 'vistas/img/productos/107/294.webp', 4, 15, 13, 15, '2026-04-18 19:47:54', 0, 1),
(10, 2, '201', 'entero brasa', 'vistas/img/productos/201/261.webp', 49, 100, 80, 1, '2026-04-18 17:22:57', 0, 1),
(11, 2, '202', '1/2 brasa', 'vistas/img/productos/202/328.webp', 48, 50, 40, 2, '2026-04-18 18:40:16', 0, 1),
(12, 2, '203', '1/4  pecho brasa', 'vistas/img/productos/203/459.webp', 47, 25, 22, 3, '2026-04-18 18:59:53', 0, 1),
(13, 2, '204', '1/4 pierna brasa', 'vistas/img/productos/204/610.webp', 47, 25, 22, 3, '2026-04-18 21:02:02', 0, 1),
(14, 2, '205', 'eco pecho brasa', 'vistas/img/productos/205/622.webp', 6, 15, 13, 45, '2026-05-26 22:18:52', 0, 1),
(15, 2, '206', 'eco ala brasa', 'vistas/img/productos/206/334.png', 24, 15, 13, 25, '2026-05-26 20:04:24', 0, 1),
(16, 2, '207', 'eco pierna brasa', 'vistas/img/productos/207/304.webp', 26, 15, 13, 25, '2026-04-18 21:02:45', 0, 1),
(17, 2, '208', 'eco contra brasa', 'vistas/img/productos/208/618.webp', 26, 15, 13, 26, '2026-04-18 21:02:01', 0, 1),
(18, 3, '301', 'coca cola 3l', 'vistas/img/productos/301/586.webp', 17, 20, 18.5, 1, '2026-04-18 16:41:36', 1, 1),
(19, 3, '302', 'coca cola 2l', 'vistas/img/productos/302/757.webp', 27, 15, 13.5, 1, '2026-04-19 04:27:06', 1, 1),
(20, 3, '303', 'coca cola 600ml', 'vistas/img/productos/303/248.webp', 74, 7, 3.5, 4, '2026-04-18 18:41:18', 1, 1),
(21, 3, '304', 'retornable coca cola 2.5l', 'vistas/img/productos/304/116.webp', 6, 12, 10.5, 4, '2026-04-19 04:27:06', 1, 1),
(22, 3, '305', 'fanta naranja 3l', 'vistas/img/productos/305/612.webp', 3, 20, 18.5, 1, '2026-04-18 15:11:19', 1, 1),
(23, 3, '306', 'fanta naranja 2l', 'vistas/img/productos/306/628.webp', 2, 15, 13.5, 0, '2026-04-18 01:16:28', 1, 1),
(24, 3, '307', 'fanta naranja 600ml vidrio', 'vistas/img/productos/307/628.png', 24, 7, 3.5, 1, '2026-04-18 19:49:21', 1, 1),
(25, 3, '308', 'fanta papaya 3l', 'vistas/img/productos/308/132.webp', 2, 20, 18.5, 0, '2026-04-18 01:17:55', 1, 1),
(26, 3, '309', 'fanta papaya 2l', 'vistas/img/productos/309/885.webp', 4, 15, 13.5, 0, '2026-04-18 01:15:54', 1, 1),
(27, 3, '310', 'fanta guarana 3l', 'vistas/img/productos/310/726.webp', 0, 20, 18.5, 1, '2026-04-19 04:27:06', 1, 1),
(28, 3, '311', 'fanta guarana 2l', 'vistas/img/productos/311/328.webp', 2, 15, 13.5, 0, '2026-04-18 01:15:31', 1, 1),
(29, 3, '312', 'sprite 600ml', 'vistas/img/productos/312/220.webp', 12, 7, 3.5, 0, '2026-04-18 01:32:10', 1, 1),
(30, 3, '313', 'simba manzana 2l', 'vistas/img/productos/313/175.webp', 9, 14, 10, 4, '2026-04-19 04:27:06', 1, 1),
(31, 3, '314', 'simba piña 2l', 'vistas/img/productos/314/492.webp', 7, 14, 10, 2, '2026-04-19 04:27:06', 1, 1),
(32, 3, '315', 'simba durazno 2l', 'vistas/img/productos/315/973.webp', 11, 14, 10, 3, '2026-04-18 20:55:57', 1, 1),
(33, 3, '316', 'pepsi 2l', 'vistas/img/productos/316/642.webp', 0, 10, 9, 0, '2026-04-18 01:46:55', 1, 1),
(34, 3, '317', 'pepsi 1l', 'vistas/img/productos/317/756.webp', 9, 7, 5.5, 1, '2026-04-18 16:36:54', 1, 1),
(35, 3, '318', 'mendocina 1l', 'vistas/img/productos/318/561.webp', 10, 7, 5.5, 0, '2026-04-18 02:07:50', 1, 1),
(36, 3, '326', 'fanta naranja mini', 'vistas/img/productos/326/635.webp', 49, 3, 1.5, 10, '2026-04-19 04:18:57', 1, 1),
(37, 3, '325', 'Coca Cola 1.5L', 'vistas/img/productos/325/878.webp', 28, 10, 9.2, 3, '2026-04-18 18:05:30', 1, 1),
(38, 3, '324', 'soda pop ', 'vistas/img/productos/324/223.webp', 156, 4, 2.5, 20, '2026-04-19 04:27:06', 1, 1),
(59, 4, '401', 'valle 3l  naranja', 'vistas/img/productos/401/178.webp', 8, 20, 18.5, 1, '2026-04-19 04:27:06', 1, 1),
(60, 4, '402', 'aquarios pera 3l', 'vistas/img/productos/402/979.webp', 0, 20, 18.5, 0, '2026-03-15 16:25:15', 1, 1),
(61, 4, '403', 'aquarios pera 2l', 'vistas/img/productos/403/838.webp', 12, 15, 13.5, 1, '2026-04-18 16:28:43', 1, 1),
(62, 4, '404', 'aquarios pomelo 3l', 'vistas/img/productos/404/845.webp', 0, 18, 16, 0, '2026-04-18 01:47:02', 1, 1),
(63, 4, '405', 'aquarios pomelo 2l', 'vistas/img/productos/405/871.webp', 0, 14, 13, 0, '2026-04-18 01:47:08', 1, 1),
(64, 4, '406', 'chicha 2l', 'vistas/img/productos/406/903.webp', 0, 12, 9, 0, '2026-03-15 16:25:15', 1, 0),
(65, 4, '407', 'tropi 600ml', 'vistas/img/productos/407/166.webp', 58, 7, 4, 10, '2026-04-19 04:27:06', 1, 1),
(66, 4, '408', 'valle 1l durazno', 'vistas/img/productos/408/174.webp', 0, 10, 9, 0, '2026-03-15 16:25:15', 1, 1),
(69, 5, '501', 'salchipapa ', 'vistas/img/productos/501/916.webp', 20, 15, 10, 0, '2026-04-18 02:50:29', 0, 1),
(71, 4, '409', 'valle 1l manzana', 'vistas/img/productos/409/600.webp', 0, 10, 9, 0, '2026-03-15 16:22:36', 1, 1),
(72, 6, '601', 'porcion de arroz', 'vistas/img/productos/601/268.webp', 10, 7, 6, 0, '2026-04-18 02:50:38', 0, 1),
(73, 6, '602', 'porcion de papa fritas', 'vistas/img/productos/602/228.webp', 10, 8, 6, 0, '2026-04-18 02:50:20', 0, 1),
(74, 6, '603', 'porcion de fideo', 'vistas/img/productos/603/620.webp', 10, 7, 6, 0, '2026-04-18 02:50:12', 0, 1),
(76, 4, '410', 'Valle 3L Durazno', 'vistas/img/productos/410/691.webp', 2, 22, 18.5, 0, '2026-04-18 02:03:51', 1, 1),
(77, 4, '411', 'Valle 3L Manzana', 'vistas/img/productos/411/831.webp', 6, 22, 18.5, 1, '2026-04-19 04:27:06', 1, 1),
(78, 3, '325', 'Sprite 2l', 'vistas/img/productos/325/805.jpg', 8, 15, 13.5, 0, '2026-04-18 01:31:46', 1, 1),
(79, 3, '326', 'mini coca cola ', 'vistas/img/productos/326/189.webp', 89, 3, 1.5, 36, '2026-04-19 04:18:57', 1, 1),
(80, 3, '327', 'mini sprite   190ml', 'vistas/img/productos/327/966.webp', 34, 3, 1.5, 9, '2026-04-19 04:18:57', 1, 1),
(81, 3, '328', 'Peque Coca Cola 300Ml', 'vistas/img/productos/328/512.webp', 18, 4, 3.5, 2, '2026-05-26 20:42:08', 1, 1),
(82, 4, '412', 'valle 2l naranja citrus', 'vistas/img/productos/412/660.jpg', 9, 15, 13.5, 1, '2026-04-18 19:29:47', 1, 1),
(83, 3, '329', 'cabaña 2l', 'vistas/img/productos/329/209.webp', 10, 15, 13.5, 2, '2026-05-26 20:40:28', 1, 1),
(84, 3, '330', 'coca cola 3l zero', 'vistas/img/productos/330/975.webp', 6, 18, 16, 0, '2026-04-18 01:10:22', 1, 1),
(85, 3, '331', 'fanta 2l mandarina', 'vistas/img/productos/331/455.webp', 11, 15, 13.5, 0, '2026-04-18 01:15:01', 1, 1),
(86, 3, '332', 'sprite 3l', 'vistas/img/productos/332/304.webp', 1, 20, 18.5, 0, '2026-04-18 01:31:27', 1, 1),
(87, 3, '333', 'sprite 600ml  vidrio', 'vistas/img/productos/333/532.png', 12, 7, 3.5, 0, '2026-04-18 01:38:47', 1, 1),
(88, 4, '413', 'valle manzana 2l ', 'vistas/img/productos/413/396.webp', 18, 16, 14.2, 0, '2026-04-18 01:54:59', 1, 1),
(89, 4, '414', ' Aquarius Pera  300Ml peque', 'vistas/img/productos/414/381.webp', 9, 4, 3.5, 3, '2026-04-18 19:57:36', 1, 1),
(90, 3, '334', 'ciclon 350ml', 'vistas/img/productos/334/268.webp', 12, 6, 4.5, 1, '2026-04-19 04:27:06', 1, 1);

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
(1, 'soporte', 'soporte', '$2a$07$asxx54ahjppf45sd87a5auGZEtGHuyZwm.Ur.FJvWLCql3nmsMbXy', 'Administrador', 'vistas/img/usuarios/admin/997.webp', 1, '2026-05-26 16:00:10', '2026-05-26 20:00:10', 1),
(2, 'ROSMERY QUIZPE', 'rosmery', '$2a$07$asxx54ahjppf45sd87a5auGZEtGHuyZwm.Ur.FJvWLCql3nmsMbXy', 'Administrador', 'vistas/img/usuarios/rosmery/468.png', 1, '2026-05-26 16:25:06', '2026-05-26 20:25:06', 1);

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
  `id_mesero` int DEFAULT NULL,
  `id_cliente` int DEFAULT NULL,
  `id_vendedor` int DEFAULT NULL,
  `id_arqueo_caja` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

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
  ADD KEY `id_arqueo_caja` (`id_arqueo_caja`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `arqueo_caja`
--
ALTER TABLE `arqueo_caja`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detalle_compra`
--
ALTER TABLE `detalle_compra`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detalle_venta`
--
ALTER TABLE `detalle_venta`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gastos`
--
ALTER TABLE `gastos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `meseros`
--
ALTER TABLE `meseros`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

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
