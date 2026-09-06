-- =========================================================
-- PRESENTACIONES DE VENTA
-- Ejecutar UNA VEZ en phpMyAdmin (base del POS).
-- NO es destructivo: no borra productos ni ventas existentes.
-- =========================================================

-- 1) Tabla hija: presentaciones por producto
CREATE TABLE IF NOT EXISTS `producto_presentaciones` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_producto` int NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `cantidad_unidades` int NOT NULL COMMENT 'Unidades reales por 1 presentación (ej. Balde=5)',
  `orden` int NOT NULL DEFAULT 0,
  `estado` tinyint NOT NULL DEFAULT 1 COMMENT '1=activo, 0=inactivo',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_pp_producto` (`id_producto`),
  KEY `idx_pp_estado` (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- FK opcional (si falla por motor/charset, la tabla igual sirve)
-- ALTER TABLE `producto_presentaciones`
--   ADD CONSTRAINT `fk_pp_producto`
--   FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`)
--   ON DELETE CASCADE ON UPDATE CASCADE;

-- 2) Auditoría en detalle_venta (nullable = ventas antiguas intactas)
-- Si alguna columna YA existe, comenta esa línea y continúa.

ALTER TABLE `detalle_venta`
  ADD COLUMN `id_presentacion` int NULL DEFAULT NULL AFTER `forma_atencion`;

ALTER TABLE `detalle_venta`
  ADD COLUMN `nombre_presentacion` varchar(80) NULL DEFAULT NULL AFTER `id_presentacion`;

ALTER TABLE `detalle_venta`
  ADD COLUMN `cantidad_presentaciones` int NULL DEFAULT NULL AFTER `nombre_presentacion`;

ALTER TABLE `detalle_venta`
  ADD COLUMN `unidades_por_presentacion` int NULL DEFAULT NULL AFTER `cantidad_presentaciones`;

-- Índice (ignorar error si ya existe)
-- ALTER TABLE `detalle_venta` ADD KEY `idx_dv_presentacion` (`id_presentacion`);

-- NOTA:
-- La presentación "Unidad" (1 und) es IMPLÍCITA: no requiere fila en producto_presentaciones.
-- `detalle_venta.cantidad` sigue siendo SIEMPRE unidades reales de inventario.
