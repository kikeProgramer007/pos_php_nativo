-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 12, 2026 at 05:26 PM
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
(1, '2026-09-10 19:47:27', '2026-09-10 19:48:35', 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 10.00, 0.00, 10.00, 10.00, 0, 0.00, 'cerrada', 0, NULL, 1, 1),
(2, '2026-09-10 19:48:41', NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 758.00, 0.00, 758.00, 0.00, 768.00, 15.00, 0.00, 15.00, 753.00, 0.00, 0.00, 0.00, 0.00, 1, 100.00, 'abierta', 23, NULL, 1, 1);

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
(1, 'Caja de ventas', '1', 23, 1),
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
(1, 'sodas ', '2026-09-09 02:26:34', 1),
(2, 'jugos ', '2026-09-09 03:16:47', 1),
(3, 'porciones', '2026-09-09 03:49:02', 1),
(4, 'pollo brasa', '2026-09-09 03:50:17', 1),
(5, 'pollo broaster', '2026-09-09 03:50:09', 1);

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
  `id_arqueo_caja` bigint UNSIGNED DEFAULT NULL,
  `descontar_caja` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=resta de caja/egresos; 0=solo informativo'
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
  `forma_atencion` char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_presentacion` int DEFAULT NULL,
  `nombre_presentacion` varchar(80) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cantidad_presentaciones` int DEFAULT NULL,
  `unidades_por_presentacion` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detalle_venta`
--

INSERT INTO `detalle_venta` (`id`, `id_producto`, `id_venta`, `producto`, `cantidad`, `precio_venta`, `precio_original`, `tipo_descuento`, `valor_descuento`, `descuento_unitario`, `descuento_total`, `id_promocion`, `id_intervalo_promocion`, `nombre_promocion`, `precio_compra`, `subtotal`, `preferencias`, `nota_adicional`, `forma_atencion`, `id_presentacion`, `nombre_presentacion`, `cantidad_presentaciones`, `unidades_por_presentacion`) VALUES
(136, 24, 6, 'entero broasterd sin porción', 1, 100.00, 100.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 90.00, 100.00, NULL, NULL, 'M', NULL, 'Unidad', 1, 1),
(137, 24, 7, 'entero broasterd sin porción', 1, 100.00, 100.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 90.00, 100.00, NULL, NULL, 'M', NULL, 'Unidad', 1, 1),
(138, 24, 8, 'entero broasterd sin porción', 1, 100.00, 100.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 90.00, 100.00, NULL, NULL, 'M', NULL, 'Unidad', 1, 1),
(139, 24, 9, 'entero broasterd sin porción', 1, 100.00, 100.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 90.00, 100.00, NULL, NULL, 'M', NULL, 'Unidad', 1, 1),
(140, 24, 10, 'entero broasterd sin porción', 1, 100.00, 100.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 90.00, 100.00, NULL, NULL, 'M', NULL, 'Unidad', 1, 1),
(141, 24, 11, 'entero broasterd sin porción', 1, 100.00, 100.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 90.00, 100.00, NULL, NULL, 'M', NULL, 'Unidad', 1, 1),
(142, 29, 12, 'economico pecho broasterd', 1, 15.00, 15.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 15.00, 15.00, NULL, NULL, 'M', NULL, 'Unidad', 1, 1),
(143, 29, 13, 'economico pecho broasterd', 1, 15.00, 15.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 15.00, 15.00, NULL, NULL, 'M', NULL, 'Unidad', 1, 1),
(144, 45, 14, 'porción de papa', 1, 7.00, 7.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 7.00, 7.00, NULL, NULL, 'M', NULL, 'Unidad', 1, 1),
(145, 42, 15, ' presa de pollo', 1, 10.00, 10.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 10.00, 10.00, NULL, NULL, 'M', NULL, 'Unidad', 1, 1),
(146, 30, 16, 'económico pierna broasterd', 1, 15.00, 15.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 15.00, 15.00, NULL, NULL, 'M', NULL, 'Unidad', 1, 1),
(147, 42, 17, ' presa de pollo', 1, 10.00, 10.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 10.00, 10.00, NULL, NULL, 'M', NULL, 'Unidad', 1, 1),
(148, 38, 18, 'económico pecho brasa', 2, 15.00, 15.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 15.00, 30.00, NULL, NULL, 'LL', NULL, 'Unidad', 2, 1),
(149, 40, 18, 'económico ala brasa', 1, 15.00, 15.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 15.00, 15.00, NULL, NULL, 'LL', NULL, 'Unidad', 1, 1),
(150, 30, 19, 'económico pierna broasterd', 1, 15.00, 15.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 15.00, 15.00, NULL, NULL, 'M', NULL, 'Unidad', 1, 1),
(151, 38, 20, 'económico pecho brasa', 1, 15.00, 15.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 15.00, 15.00, NULL, NULL, 'M', NULL, 'Unidad', 1, 1),
(152, 31, 21, 'económico ala broasterd', 1, 15.00, 15.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 15.00, 15.00, NULL, NULL, 'M', NULL, 'Unidad', 1, 1),
(153, 40, 21, 'económico ala brasa', 1, 15.00, 15.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 15.00, 15.00, NULL, NULL, 'M', NULL, 'Unidad', 1, 1),
(154, 38, 22, 'económico pecho brasa', 1, 15.00, 15.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 15.00, 15.00, NULL, NULL, 'M', NULL, 'Unidad', 1, 1),
(155, 43, 23, 'porción de arroz', 1, 7.00, 7.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 7.00, 7.00, NULL, NULL, 'M', NULL, 'Unidad', 1, 1),
(156, 43, 24, 'porción de arroz', 1, 7.00, 7.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 7.00, 7.00, NULL, NULL, 'M', NULL, 'Unidad', 1, 1),
(157, 43, 25, 'porción de arroz', 1, 7.00, 7.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 7.00, 7.00, NULL, NULL, 'M', NULL, 'Unidad', 1, 1),
(158, 38, 26, 'económico pecho brasa', 1, 15.00, 15.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 15.00, 15.00, NULL, NULL, 'M', NULL, 'Unidad', 1, 1),
(159, 39, 27, 'económico pierna brasa', 1, 15.00, 15.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 15.00, 15.00, NULL, NULL, 'M', NULL, 'Unidad', 1, 1),
(160, 31, 28, 'económico ala broasterd', 1, 15.00, 15.00, NULL, NULL, 0.00, 0.00, NULL, NULL, NULL, 15.00, 15.00, NULL, NULL, 'M', NULL, 'Unidad', 1, 1);

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
(1, '2026-09-10', 'pañales', 15.00, 15.00, 0.00, '1', 1, 1, 2);

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
(1, 's/n', '0000000', '00000000', 's/n', 405, '2026-09-12 16:23:35', '2026-09-12 20:23:35', 1),
(2, 'lisandra', 'sin carnet', '000-00-000', 'sin dirección', 0, NULL, '2026-09-09 02:15:56', 1);

-- --------------------------------------------------------

--
-- Table structure for table `otros_ingresos`
--

CREATE TABLE `otros_ingresos` (
  `id` int NOT NULL,
  `id_arqueo_caja` bigint UNSIGNED NOT NULL,
  `id_usuario` int NOT NULL,
  `descripcion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `monto` decimal(11,2) NOT NULL DEFAULT '0.00',
  `tipo_entrada` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'EFECTIVO',
  `monto_efectivo` decimal(11,2) NOT NULL DEFAULT '0.00',
  `monto_qr` decimal(11,2) NOT NULL DEFAULT '0.00',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estado` tinyint NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `otros_ingresos`
--

INSERT INTO `otros_ingresos` (`id`, `id_arqueo_caja`, `id_usuario`, `descripcion`, `monto`, `tipo_entrada`, `monto_efectivo`, `monto_qr`, `fecha`, `estado`) VALUES
(1, 2, 1, 'rr', 10.00, 'EFECTIVO', 10.00, 0.00, '2026-09-11 01:33:07', 1);

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
(3, 'cajero', 'Caja, ventas e impresión de reportes básicos', 1, 1, '2026-08-16 23:07:44');

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
(929, 3, 1),
(930, 3, 37),
(931, 3, 38),
(932, 3, 39),
(934, 3, 40),
(935, 3, 41),
(936, 3, 42),
(937, 3, 43),
(938, 3, 44),
(939, 3, 45),
(940, 3, 46),
(941, 3, 47),
(942, 3, 50),
(943, 3, 60),
(944, 3, 61),
(945, 3, 62),
(946, 3, 63),
(947, 3, 64),
(948, 3, 65),
(949, 3, 66),
(950, 3, 67),
(933, 3, 68),
(951, 3, 69);

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
(1, 1, '101', 'coca mini 190ml', 'vistas/img/productos/101/948.jpg', 0, 3, 1.3, 0, '2026-09-09 02:30:35', 1, 1),
(2, 1, '102', 'fanta mini 190ml', 'vistas/img/productos/102/370.png', 0, 3, 1.3, 0, '2026-09-09 02:34:51', 1, 1),
(3, 1, '103', 'coca cola peque  300ml', 'vistas/img/productos/103/474.webp', 0, 5, 3.3, 0, '2026-09-09 02:36:53', 1, 1),
(4, 1, '104', 'coca cola personal 500ml', 'vistas/img/productos/104/982.jpg', 0, 7, 4.65, 0, '2026-09-09 02:38:21', 1, 1),
(5, 1, '105', 'popular coca cola 600ml', 'vistas/img/productos/105/227.png', 0, 6, 3.8, 0, '2026-09-09 02:41:05', 1, 1),
(6, 1, '106', 'popular fanta 600ml', 'vistas/img/productos/106/428.png', 0, 6, 3.8, 0, '2026-09-09 02:42:58', 1, 1),
(7, 1, '107', 'soda salvietti 3l', 'vistas/img/productos/107/325.png', 0, 18, 15, 0, '2026-09-09 02:55:41', 1, 1),
(8, 1, '108', 'fanta  3lts', 'vistas/img/productos/108/561.jpg', 0, 20, 15, 0, '2026-09-09 02:57:23', 1, 1),
(9, 1, '109', 'coca cola 3lts', 'vistas/img/productos/109/303.jpg', 0, 20, 15, 0, '2026-09-09 02:58:12', 1, 1),
(10, 1, '110', 'soda salvietti 2lts', 'vistas/img/productos/110/286.jpg', 0, 14, 13, 0, '2026-09-09 03:01:12', 1, 1),
(11, 1, '111', 'soda simba 2lts', 'vistas/img/productos/111/260.png', 0, 14, 14, 0, '2026-09-09 03:03:51', 1, 1),
(12, 1, '112', 'soda fanta 2lts', 'vistas/img/productos/112/442.jpg', 0, 16, 16, 0, '2026-09-09 03:05:04', 1, 1),
(13, 1, '113', 'coca cola 2lts', 'vistas/img/productos/113/362.jpg', 0, 16, 16, 0, '2026-09-09 03:13:38', 1, 1),
(14, 2, '201', 'ades 1lts', 'vistas/img/productos/201/681.webp', 0, 14, 11, 0, '2026-09-09 03:18:28', 1, 1),
(15, 2, '202', 'jugo del valle 300ml', 'vistas/img/productos/202/519.jpg', 0, 5, 3.3, 0, '2026-09-09 03:22:00', 1, 1),
(16, 2, '203', 'Aquarius 300ml', 'vistas/img/productos/203/862.webp', 0, 5, 3.3, 0, '2026-09-09 03:23:52', 1, 1),
(17, 2, '204', 'Aquarius 500ml', 'vistas/img/productos/204/871.jpg', 0, 7, 4.35, 0, '2026-09-09 03:25:05', 1, 1),
(18, 2, '205', 'jugo del valle 500ml', 'vistas/img/productos/205/250.jpg', 0, 7, 4.35, 0, '2026-09-09 03:28:53', 1, 1),
(19, 2, '206', 'popular tropi', 'vistas/img/productos/206/411.jpg', 0, 6, 3.5, 0, '2026-09-09 03:33:08', 1, 1),
(20, 2, '207', 'jugo del valle 3lts', 'vistas/img/productos/207/178.jpg', 0, 20, 20, 0, '2026-09-09 03:36:10', 1, 1),
(21, 2, '208', 'Aquarius 3lts', 'vistas/img/productos/208/592.jpg', 0, 20, 20, 0, '2026-09-09 03:40:20', 1, 1),
(22, 2, '209', 'jugo del valle 2lts', 'vistas/img/productos/209/287.webp', 0, 16, 16, 0, '2026-09-09 03:47:14', 1, 1),
(23, 2, '210', 'Aquarius 2lts', 'vistas/img/productos/210/491.webp', 0, 16, 16, 0, '2026-09-09 03:48:14', 1, 1),
(24, 5, '501', 'entero broasterd sin porción', 'vistas/img/productos/501/670.webp', 99999, 80, 80, 6, '2026-09-11 02:15:59', 0, 1),
(25, 5, '502', 'entero broasterd con porción', 'vistas/img/productos/502/732.png', 99999, 100, 100, 0, '2026-09-11 02:12:49', 0, 1),
(26, 5, '503', '1/2 medio broasterd', 'vistas/img/productos/503/842.png', 99999, 50, 50, 0, '2026-09-11 02:15:15', 0, 1),
(27, 5, '504', 'cuarto  broasterd pecho con ala', 'vistas/img/productos/504/656.png', 99999, 25, 25, 0, '2026-09-11 02:23:30', 0, 1),
(28, 5, '505', 'cuarto broasterd contra con  pierna ', 'vistas/img/productos/505/212.png', 99999, 25, 25, 0, '2026-09-11 02:44:20', 0, 1),
(29, 5, '506', 'económico pecho broasterd', 'vistas/img/productos/506/965.png', 99999, 15, 15, 2, '2026-09-11 02:49:28', 0, 1),
(30, 5, '507', 'económico pierna broasterd', 'vistas/img/productos/507/427.png', 99999, 15, 15, 2, '2026-09-11 03:45:51', 0, 1),
(31, 5, '508', 'económico ala broasterd', 'vistas/img/productos/508/851.png', 99999, 15, 15, 2, '2026-09-12 20:23:35', 0, 1),
(32, 5, '509', 'económico contra broasterd', 'vistas/img/productos/509/585.png', 99999, 15, 15, 0, '2026-09-11 02:49:09', 0, 1),
(33, 4, '401', 'entero brasa sin porción', 'vistas/img/productos/401/750.webp', 99999, 80, 80, 0, '2026-09-11 01:24:54', 0, 1),
(34, 4, '402', 'entero brasa con porción ', 'vistas/img/productos/402/438.png', 99999, 100, 100, 0, '2026-09-11 02:37:50', 0, 1),
(35, 4, '403', '1/2 brasa', 'vistas/img/productos/403/922.png', 99999, 50, 50, 0, '2026-09-11 02:38:06', 0, 1),
(36, 4, '404', 'cuarto  brasa pecho con ala', 'vistas/img/productos/404/862.png', 99999, 25, 25, 0, '2026-09-11 02:40:27', 0, 1),
(37, 4, '405', 'cuarto brasa contra con pierna ', 'vistas/img/productos/405/170.png', 99999, 25, 25, 0, '2026-09-11 02:43:53', 0, 1),
(38, 4, '406', 'económico pecho brasa', 'vistas/img/productos/406/144.png', 99999, 15, 15, 5, '2026-09-12 20:13:37', 0, 1),
(39, 4, '407', 'económico pierna brasa', 'vistas/img/productos/407/523.png', 99999, 15, 15, 1, '2026-09-12 20:22:57', 0, 1),
(40, 4, '408', 'económico ala brasa', 'vistas/img/productos/408/252.png', 99999, 15, 15, 2, '2026-09-11 05:04:24', 0, 1),
(41, 4, '409', 'económico contra brasa', 'vistas/img/productos/409/580.png', 99999, 15, 15, 0, '2026-09-11 02:56:54', 0, 1),
(42, 3, '301', ' presa de pollo', 'vistas/img/productos/301/943.png', 99999, 10, 10, 2, '2026-09-11 03:30:51', 0, 1),
(43, 3, '302', 'porción de arroz', 'vistas/img/productos/302/399.png', 99999, 7, 7, 3, '2026-09-11 05:18:02', 0, 1),
(44, 3, '303', 'porción de fideo', 'vistas/img/productos/303/298.png', 99999, 7, 7, 0, '2026-09-11 03:08:36', 0, 1),
(45, 3, '304', 'porción de papa', 'vistas/img/productos/304/407.png', 99999, 7, 7, 1, '2026-09-11 03:11:44', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `producto_presentaciones`
--

CREATE TABLE `producto_presentaciones` (
  `id` int NOT NULL,
  `id_producto` int NOT NULL,
  `nombre` varchar(80) COLLATE utf8mb4_general_ci NOT NULL,
  `cantidad_unidades` int NOT NULL COMMENT 'Unidades reales por 1 presentación (ej. Balde=5)',
  `orden` int NOT NULL DEFAULT '0',
  `estado` tinyint NOT NULL DEFAULT '1' COMMENT '1=activo, 0=inactivo',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(2, 'proveedor coca cola y agua', 'S/N', '00000000', 's/n', '2026-09-09 02:16:27', 1),
(3, 'proveedor tropi', 's/n', '00000000', 'sin direccion', '2026-09-09 02:16:58', 1);

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
(1, 'soporte', 'soporte', '$2a$07$asxx54ahjppf45sd87a5auGZEtGHuyZwm.Ur.FJvWLCql3nmsMbXy', 'Administrador', 1, 'vistas/img/usuarios/admin/997.webp', 1, '2026-09-12 16:13:22', '2026-09-12 20:13:22', 1),
(2, 'zusana murgia', 'zusana', '$2a$07$asxx54ahjppf45sd87a5auGZEtGHuyZwm.Ur.FJvWLCql3nmsMbXy', 'Vendedor', 3, 'vistas/img/usuarios/default/anonymous.webp', 1, NULL, '2026-09-09 02:12:40', 1),
(3, 'epifania', 'epifania', '$2a$07$asxx54ahjppf45sd87a5auGZEtGHuyZwm.Ur.FJvWLCql3nmsMbXy', 'Administrador', 1, 'vistas/img/usuarios/default/anonymous.webp', 1, NULL, '2026-09-09 02:14:54', 1),
(4, 'santiago', 'santiago', '$2a$07$asxx54ahjppf45sd87a5auGZEtGHuyZwm.Ur.FJvWLCql3nmsMbXy', 'Administrador', 1, 'vistas/img/usuarios/default/anonymous.webp', 1, NULL, '2026-09-09 02:15:17', 1);

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
(6, 1, NULL, 0, 100, 100, 100.00, 0.00, 100, '2026-09-10 23:52:04', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-09-10 19:52:04', 1, 1, 1, 2),
(7, 2, NULL, 0, 100, 100, 100.00, 0.00, 100, '2026-09-10 23:53:42', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-09-10 19:53:42', 1, 1, 1, 2),
(8, 3, NULL, 0, 100, 100, 100.00, 0.00, 100, '2026-09-10 23:54:00', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-09-10 19:54:00', 1, 1, 1, 2),
(9, 4, NULL, 0, 100, 100, 100.00, 0.00, 100, '2026-09-10 23:57:45', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-09-10 19:57:45', 1, 1, 1, 2),
(10, 5, NULL, 0, 100, 100, 100.00, 0.00, 100, '2026-09-10 23:59:40', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-09-10 19:59:40', 1, 1, 1, 2),
(11, 6, NULL, 0, 0, 100, 100.00, 0.00, 0, '2026-09-11 00:00:51', '', '', 0, 'En Mesa', 1, 'PENDIENTE', NULL, 1, 1, 1, 2),
(12, 7, NULL, 0, 15, 15, 15.00, 0.00, 20, '2026-09-11 01:32:25', '', 'Efectivo', 5, 'En Mesa', 1, 'PAGADA', '2026-09-10 21:32:25', 1, 1, 1, 2),
(13, 8, NULL, 0, 15, 15, 15.00, 0.00, 100, '2026-09-11 02:29:29', '', 'Efectivo', 85, 'En Mesa', 1, 'PAGADA', '2026-09-10 22:29:29', 1, 1, 1, 2),
(14, 9, NULL, 0, 7, 7, 7.00, 0.00, 100, '2026-09-11 03:11:44', '', 'Efectivo', 93, 'En Mesa', 1, 'PAGADA', '2026-09-10 23:11:44', 1, 1, 1, 2),
(15, 10, NULL, 0, 10, 10, 10.00, 0.00, 100, '2026-09-11 03:12:02', '', 'Efectivo', 90, 'En Mesa', 1, 'PAGADA', '2026-09-10 23:12:02', 1, 1, 1, 2),
(16, 11, NULL, 0, 15, 15, 15.00, 0.00, 100, '2026-09-11 03:30:31', '', 'Efectivo', 85, 'En Mesa', 1, 'PAGADA', '2026-09-10 23:30:31', 1, 1, 1, 2),
(17, 12, NULL, 0, 10, 10, 10.00, 0.00, 100, '2026-09-11 03:30:51', '', 'Efectivo', 90, 'En Mesa', 1, 'PAGADA', '2026-09-10 23:30:51', 1, 1, 1, 2),
(18, 13, NULL, 0, 45, 45, 45.00, 0.00, 100, '2026-09-11 03:31:42', '', 'Efectivo', 55, 'Para Llevar', 1, 'PAGADA', '2026-09-10 23:31:42', 1, 1, 1, 2),
(19, 14, NULL, 0, 15, 15, 15.00, 0.00, 100, '2026-09-11 03:45:51', '', 'Efectivo', 85, 'En Mesa', 1, 'PAGADA', '2026-09-10 23:45:51', 1, 1, 1, 2),
(20, 15, NULL, 0, 15, 15, 15.00, 0.00, 100, '2026-09-11 05:03:57', '', 'Efectivo', 85, 'En Mesa', 1, 'PAGADA', '2026-09-11 01:03:57', 1, 1, 1, 2),
(21, 16, NULL, 0, 30, 30, 30.00, 0.00, 100, '2026-09-11 05:04:24', '', 'Efectivo', 70, 'En Mesa', 1, 'PAGADA', '2026-09-11 01:04:24', 1, 1, 1, 2),
(22, 17, NULL, 0, 15, 15, 15.00, 0.00, 15, '2026-09-12 20:23:45', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-09-12 16:23:45', 1, 1, 1, 2),
(23, 18, NULL, 0, 7, 7, 7.00, 0.00, 100, '2026-09-11 05:06:56', '', 'Efectivo', 93, 'En Mesa', 1, 'PAGADA', '2026-09-11 01:06:56', 1, 1, 1, 2),
(24, 19, NULL, 0, 7, 7, 7.00, 0.00, 100, '2026-09-11 05:08:09', '', 'Efectivo', 93, 'En Mesa', 1, 'PAGADA', '2026-09-11 01:08:09', 1, 1, 1, 2),
(25, 20, NULL, 0, 7, 7, 7.00, 0.00, 100, '2026-09-11 05:18:02', '', 'Efectivo', 93, 'En Mesa', 1, 'PAGADA', '2026-09-11 01:18:02', 1, 1, 1, 2),
(26, 21, NULL, 0, 15, 15, 15.00, 0.00, 15, '2026-09-12 20:13:37', '', 'Efectivo', 0, 'En Mesa', 1, 'PAGADA', '2026-09-12 16:13:37', 1, 1, 1, 2),
(27, 22, NULL, 0, 15, 15, 15.00, 0.00, 100, '2026-09-12 20:22:57', '', 'Efectivo', 85, 'En Mesa', 1, 'PAGADA', '2026-09-12 16:22:56', 1, 1, 1, 2),
(28, 23, NULL, 0, 15, 15, 15.00, 0.00, 100, '2026-09-12 20:23:35', '', 'Efectivo', 85, 'En Mesa', 1, 'PAGADA', '2026-09-12 16:23:35', 1, 1, 1, 2);

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
-- Indexes for table `producto_presentaciones`
--
ALTER TABLE `producto_presentaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pp_producto` (`id_producto`),
  ADD KEY `idx_pp_estado` (`estado`);

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cajas`
--
ALTER TABLE `cajas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=161;

--
-- AUTO_INCREMENT for table `gastos`
--
ALTER TABLE `gastos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `meseros`
--
ALTER TABLE `meseros`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `otros_ingresos`
--
ALTER TABLE `otros_ingresos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `perfiles`
--
ALTER TABLE `perfiles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `perfil_permisos`
--
ALTER TABLE `perfil_permisos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=952;

--
-- AUTO_INCREMENT for table `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `producto_presentaciones`
--
ALTER TABLE `producto_presentaciones`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `promociones`
--
ALTER TABLE `promociones`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

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
