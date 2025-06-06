-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-05-2025 a las 18:30:22
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
(1, 1, '101', 'entero broasterd', 'vistas/img/productos/101/884.webp', 50, 90, 80, 0, '2025-05-28 15:42:30', 0, 1),
(2, 1, '102', '1/2 broasterd', 'vistas/img/productos/102/847.webp', 50, 45, 40, 0, '2025-05-28 15:42:44', 0, 1),
(3, 1, '103', '1/4 pecho broasterd', 'vistas/img/productos/103/138.webp', 50, 23, 22, 0, '2025-05-28 15:43:01', 0, 1),
(4, 1, '108', '1/4 pierna broasterd', 'vistas/img/productos/108/247.webp', 50, 23, 22, 0, '2025-05-28 15:43:52', 0, 1),
(5, 1, '104', 'eco pecho broasterd', 'vistas/img/productos/104/897.webp', 50, 14, 13, 0, '2025-05-28 15:44:21', 0, 1),
(6, 1, '106', 'eco ala broasterd', 'vistas/img/productos/106/670.webp', 50, 14, 13, 0, '2025-05-28 15:45:50', 0, 1),
(7, 1, '105', ' eco pierna broasterd', 'vistas/img/productos/105/476.webp', 50, 14, 13, 0, '2025-05-28 15:46:19', 0, 1),
(8, 1, '107', 'eco contra broasterd', 'vistas/img/productos/107/294.webp', 50, 14, 13, 0, '2025-05-28 15:47:05', 0, 1),
(10, 2, '201', 'entero brasa', 'vistas/img/productos/201/261.webp', 50, 90, 80, 0, '2025-05-28 15:48:03', 0, 1),
(11, 2, '202', '1/2 brasa', 'vistas/img/productos/202/328.webp', 50, 45, 40, 0, '2025-05-28 15:48:21', 0, 1),
(12, 2, '203', '1/4  pecho brasa', 'vistas/img/productos/203/459.webp', 50, 23, 22, 0, '2025-05-28 15:48:44', 0, 1),
(13, 2, '204', '1/4 pierna brasa', 'vistas/img/productos/204/610.webp', 50, 23, 22, 0, '2025-05-28 15:49:04', 0, 1),
(14, 2, '205', 'eco pecho brasa', 'vistas/img/productos/205/622.webp', 50, 14, 13, 0, '2025-05-28 15:50:29', 0, 1),
(15, 2, '206', 'eco ala brasa', 'vistas/img/productos/206/334.png', 50, 14, 13, 0, '2025-05-28 15:50:40', 0, 1),
(16, 2, '207', 'eco pierna brasa', 'vistas/img/productos/207/304.webp', 50, 14, 13, 0, '2025-05-28 15:51:02', 0, 1),
(17, 2, '208', 'eco contra brasa', 'vistas/img/productos/208/618.webp', 50, 14, 13, 0, '2025-05-28 15:51:22', 0, 1),
(18, 3, '301', 'coca cola 3l', 'vistas/img/productos/301/586.webp', 50, 18, 16, 0, '2025-05-28 15:51:54', 1, 1),
(19, 3, '302', 'coca cola 2l', 'vistas/img/productos/302/757.webp', 50, 14, 13, 0, '2025-05-28 15:52:20', 1, 1),
(20, 3, '303', 'coca cola 600ml', 'vistas/img/productos/303/248.webp', 50, 6, 4, 0, '2025-05-28 15:52:40', 1, 1),
(21, 3, '304', 'retornable coca cola 2.5l', 'vistas/img/productos/304/116.webp', 50, 12, 10.5, 0, '2025-05-28 15:53:42', 1, 1),
(22, 3, '305', 'fanta naranja 3l', 'vistas/img/productos/305/612.webp', 50, 18, 16, 0, '2025-05-28 15:54:22', 1, 1),
(23, 3, '306', 'fanta naranja 2l', 'vistas/img/productos/306/628.webp', 50, 14, 13, 0, '2025-05-28 15:54:43', 1, 1),
(24, 3, '307', 'fanta naranja 600ml', 'vistas/img/productos/307/828.webp', 50, 6, 4, 0, '2025-05-28 15:55:03', 1, 1),
(25, 3, '308', 'fanta papaya 3l', 'vistas/img/productos/308/132.webp', 50, 18, 16, 0, '2025-05-28 15:58:32', 1, 1),
(26, 3, '309', 'fanta papaya 2l', 'vistas/img/productos/309/885.webp', 50, 14, 13, 0, '2025-05-28 15:57:07', 1, 1),
(27, 3, '310', 'fanta guarana 3l', 'vistas/img/productos/310/726.webp', 50, 18, 16, 0, '2025-05-28 15:58:01', 1, 1),
(28, 3, '311', 'fanta guarana 2l', 'vistas/img/productos/311/328.webp', 50, 14, 13, 0, '2025-05-28 15:59:11', 1, 1),
(29, 3, '312', 'sprite 600ml', 'vistas/img/productos/312/220.webp', 50, 6, 4, 0, '2025-05-28 15:59:41', 1, 1),
(30, 3, '313', 'simba manzana 2l', 'vistas/img/productos/313/175.webp', 50, 12, 10, 0, '2025-05-12 03:04:45', 1, 1),
(31, 3, '314', 'simba piña 2l', 'vistas/img/productos/314/492.webp', 50, 12, 10, 0, '2025-05-12 03:05:06', 1, 1),
(32, 3, '315', 'simba durazno 2l', 'vistas/img/productos/315/973.webp', 50, 12, 10, 0, '2025-05-12 03:05:20', 1, 1),
(33, 3, '316', 'pepsi 2l', 'vistas/img/productos/316/642.webp', 50, 10, 9, 0, '2025-05-12 03:34:55', 1, 0),
(34, 3, '317', 'pepsi 1l', 'vistas/img/productos/317/756.webp', 50, 7, 6, 0, '2025-05-28 16:00:26', 1, 1),
(35, 3, '318', 'mendocina papaya 1l', 'vistas/img/productos/318/561.webp', 50, 7, 5.5, 0, '2025-05-28 16:00:48', 1, 1),
(36, 3, '326', 'fanta naranja mini', 'vistas/img/productos/326/635.webp', 50, 2.5, 1.6, 0, '2025-05-28 16:02:00', 1, 1),
(37, 3, '325', 'Coca Cola 1.5L', 'vistas/img/productos/325/878.webp', 50, 10, 7.5, 0, '2025-05-28 16:10:47', 1, 1),
(38, 3, '324', 'soda pop ', 'vistas/img/productos/324/223.webp', 50, 3.5, 1.8, 0, '2025-05-28 16:03:10', 1, 1),
(59, 4, '401', 'valle 3l  naranja', 'vistas/img/productos/401/178.webp', 50, 18, 16.5, 0, '2025-05-28 16:03:47', 1, 1),
(60, 4, '402', 'aquarios pera 3l', 'vistas/img/productos/402/979.webp', 50, 18, 16, 0, '2025-05-28 16:04:18', 1, 1),
(61, 4, '403', 'aquarios pera 2l', 'vistas/img/productos/403/838.webp', 50, 14, 13, 0, '2025-05-28 16:04:37', 1, 1),
(62, 4, '404', 'aquarios pomelo 3l', 'vistas/img/productos/404/845.webp', 50, 18, 16, 0, '2025-05-28 16:04:57', 1, 1),
(63, 4, '405', 'aquarios pomelo 2l', 'vistas/img/productos/405/871.webp', 50, 14, 13, 0, '2025-05-28 16:05:19', 1, 1),
(64, 4, '406', 'chicha 2l', 'vistas/img/productos/406/903.webp', 50, 12, 9, 0, '2025-05-12 03:31:47', 1, 0),
(65, 4, '407', 'tropi 600ml', 'vistas/img/productos/407/166.webp', 50, 6, 4, 0, '2025-05-28 16:05:36', 1, 1),
(66, 4, '408', 'valle 1l durazno', 'vistas/img/productos/408/174.webp', 50, 12, 10, 0, '2025-05-28 16:05:52', 1, 0),
(69, 5, '501', 'salchipapa ', 'vistas/img/productos/501/916.webp', 50, 12, 10, 0, '2025-05-28 16:06:46', 0, 1),
(71, 4, '409', 'valle 1l manzana', 'vistas/img/productos/409/600.webp', 50, 12, 10, 0, '2025-05-28 16:07:02', 1, 0),
(72, 6, '601', 'porcion de arroz', 'vistas/img/productos/601/268.webp', 50, 7, 6, 0, '2025-05-28 16:07:13', 0, 1),
(73, 6, '602', 'porcion de papa fritas', 'vistas/img/productos/602/228.webp', 50, 7, 6, 0, '2025-05-28 16:07:26', 0, 1),
(74, 6, '603', 'porcion de fideo', 'vistas/img/productos/603/620.webp', 50, 7, 6, 0, '2025-05-28 16:07:34', 0, 1);

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
(1, 's/n', 's/n', '00000000', 'sin direccion', '2025-04-26 02:55:00', 1);

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
(1, 'rosmery quizpe', 'admin', '$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG', 'Administrador', 'vistas/img/usuarios/admin/997.webp', 1, '2025-05-12 13:51:07', '2025-05-12 17:51:07', 1),
(2, 'edwin yamil', 'edwin', '$2a$07$asxx54ahjppf45sd87a5auGZEtGHuyZwm.Ur.FJvWLCql3nmsMbXy', 'Supervisor', 'vistas/img/usuarios/default/anonymous.webp', 1, '0000-00-00 00:00:00', '2025-05-19 02:09:13', 1),
(3, 'luis hidalgo', 'luis10', '$2a$07$asxx54ahjppf45sd87a5auGZEtGHuyZwm.Ur.FJvWLCql3nmsMbXy', 'Vendedor', 'vistas/img/usuarios/default/anonymous.webp', 1, '0000-00-00 00:00:00', '2025-05-12 18:06:42', 1);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `gastos`
--
ALTER TABLE `gastos`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
-- Filtros para la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD CONSTRAINT `fk_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_venta` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
