-- Extensión de ventas para totales bruto / descuento de promociones
-- Ejecutar una sola vez después de promociones_ofertas.sql

SET NAMES utf8mb4;
SET @db := DATABASE();

SET @col_exists := (
  SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'ventas' AND COLUMN_NAME = 'total_bruto'
);
SET @sql := IF(@col_exists = 0,
  'ALTER TABLE `ventas` ADD COLUMN `total_bruto` DECIMAL(12,2) NULL DEFAULT NULL AFTER `total`',
  'SELECT "total_bruto ya existe" AS info');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @col_exists := (
  SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'ventas' AND COLUMN_NAME = 'total_descuento'
);
SET @sql := IF(@col_exists = 0,
  'ALTER TABLE `ventas` ADD COLUMN `total_descuento` DECIMAL(12,2) NULL DEFAULT 0.00 AFTER `total_bruto`',
  'SELECT "total_descuento ya existe" AS info');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Rellenar históricos: si no hay descuento, bruto = total
UPDATE ventas
SET total_bruto = COALESCE(total_bruto, total),
    total_descuento = COALESCE(total_descuento, 0)
WHERE total_bruto IS NULL OR total_descuento IS NULL;
