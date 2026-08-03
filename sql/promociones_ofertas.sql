-- =============================================================================
-- MÓDULO: Ofertas y Promociones
-- Ejecutar este script en la base de datos del POS (MySQL/MariaDB).
-- Seguro para ejecutar una sola vez. Usa IF NOT EXISTS / comprobaciones.
-- =============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------------------------------
-- 1) Tabla principal de promociones
-- estado: 1 = habilitada, 0 = deshabilitada
-- (vencida / programada se calculan por fechas en la aplicación)
-- modo_cantidad: individual | combinada (reservado para futuro)
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `promociones` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(150) NOT NULL,
  `descripcion` TEXT NULL,
  `fecha_inicio` DATETIME NOT NULL,
  `fecha_fin` DATETIME NOT NULL,
  `prioridad` INT(11) NOT NULL DEFAULT 1,
  `estado` TINYINT(1) NOT NULL DEFAULT 1,
  `modo_cantidad` VARCHAR(20) NOT NULL DEFAULT 'individual',
  `observacion` TEXT NULL,
  `fecha` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_promo_estado` (`estado`),
  KEY `idx_promo_fechas` (`fecha_inicio`, `fecha_fin`),
  KEY `idx_promo_prioridad` (`prioridad`),
  KEY `idx_promo_nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------------------------------
-- 2) Productos vinculados a una promoción
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `promocion_productos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_promocion` INT(11) NOT NULL,
  `id_producto` INT(11) NOT NULL,
  `estado` TINYINT(1) NOT NULL DEFAULT 1,
  `fecha` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_promo_producto` (`id_promocion`, `id_producto`),
  KEY `idx_pp_promocion` (`id_promocion`),
  KEY `idx_pp_producto` (`id_producto`),
  KEY `idx_pp_estado` (`estado`),
  CONSTRAINT `fk_pp_promocion` FOREIGN KEY (`id_promocion`) REFERENCES `promociones` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_pp_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------------------------------
-- 3) Intervalos de cantidad por promoción
-- tipo_descuento: fijo | porcentaje
-- cantidad_maxima NULL = sin límite (solo permitido en el último intervalo)
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `promocion_intervalos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_promocion` INT(11) NOT NULL,
  `cantidad_minima` INT(11) NOT NULL,
  `cantidad_maxima` INT(11) NULL DEFAULT NULL,
  `tipo_descuento` VARCHAR(20) NOT NULL DEFAULT 'fijo',
  `valor_descuento` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `estado` TINYINT(1) NOT NULL DEFAULT 1,
  `fecha` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_pi_promocion` (`id_promocion`),
  KEY `idx_pi_cantidades` (`cantidad_minima`, `cantidad_maxima`),
  KEY `idx_pi_estado` (`estado`),
  CONSTRAINT `fk_pi_promocion` FOREIGN KEY (`id_promocion`) REFERENCES `promociones` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------------------------------
-- 4) Extender detalle_venta para historial de promociones
-- precio_venta permanece como precio unitario FINAL cobrado
-- -----------------------------------------------------------------------------
SET @db := DATABASE();

SET @col_exists := (
  SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'detalle_venta' AND COLUMN_NAME = 'precio_original'
);
SET @sql := IF(@col_exists = 0,
  'ALTER TABLE `detalle_venta` ADD COLUMN `precio_original` DECIMAL(10,2) NULL DEFAULT NULL AFTER `precio_venta`',
  'SELECT "precio_original ya existe" AS info');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @col_exists := (
  SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'detalle_venta' AND COLUMN_NAME = 'tipo_descuento'
);
SET @sql := IF(@col_exists = 0,
  'ALTER TABLE `detalle_venta` ADD COLUMN `tipo_descuento` VARCHAR(20) NULL DEFAULT NULL AFTER `precio_original`',
  'SELECT "tipo_descuento ya existe" AS info');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @col_exists := (
  SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'detalle_venta' AND COLUMN_NAME = 'valor_descuento'
);
SET @sql := IF(@col_exists = 0,
  'ALTER TABLE `detalle_venta` ADD COLUMN `valor_descuento` DECIMAL(10,2) NULL DEFAULT NULL AFTER `tipo_descuento`',
  'SELECT "valor_descuento ya existe" AS info');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @col_exists := (
  SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'detalle_venta' AND COLUMN_NAME = 'descuento_unitario'
);
SET @sql := IF(@col_exists = 0,
  'ALTER TABLE `detalle_venta` ADD COLUMN `descuento_unitario` DECIMAL(10,2) NULL DEFAULT 0.00 AFTER `valor_descuento`',
  'SELECT "descuento_unitario ya existe" AS info');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @col_exists := (
  SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'detalle_venta' AND COLUMN_NAME = 'descuento_total'
);
SET @sql := IF(@col_exists = 0,
  'ALTER TABLE `detalle_venta` ADD COLUMN `descuento_total` DECIMAL(10,2) NULL DEFAULT 0.00 AFTER `descuento_unitario`',
  'SELECT "descuento_total ya existe" AS info');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @col_exists := (
  SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'detalle_venta' AND COLUMN_NAME = 'id_promocion'
);
SET @sql := IF(@col_exists = 0,
  'ALTER TABLE `detalle_venta` ADD COLUMN `id_promocion` INT(11) NULL DEFAULT NULL AFTER `descuento_total`',
  'SELECT "id_promocion ya existe" AS info');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @col_exists := (
  SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'detalle_venta' AND COLUMN_NAME = 'id_intervalo_promocion'
);
SET @sql := IF(@col_exists = 0,
  'ALTER TABLE `detalle_venta` ADD COLUMN `id_intervalo_promocion` INT(11) NULL DEFAULT NULL AFTER `id_promocion`',
  'SELECT "id_intervalo_promocion ya existe" AS info');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @col_exists := (
  SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'detalle_venta' AND COLUMN_NAME = 'nombre_promocion'
);
SET @sql := IF(@col_exists = 0,
  'ALTER TABLE `detalle_venta` ADD COLUMN `nombre_promocion` VARCHAR(150) NULL DEFAULT NULL AFTER `id_intervalo_promocion`',
  'SELECT "nombre_promocion ya existe" AS info');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Índices útiles en detalle_venta
SET @idx_exists := (
  SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS
  WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'detalle_venta' AND INDEX_NAME = 'idx_dv_promocion'
);
SET @sql := IF(@idx_exists = 0,
  'ALTER TABLE `detalle_venta` ADD KEY `idx_dv_promocion` (`id_promocion`)',
  'SELECT "idx_dv_promocion ya existe" AS info');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET FOREIGN_KEY_CHECKS = 1;

-- =============================================================================
-- Fin del script
-- =============================================================================
