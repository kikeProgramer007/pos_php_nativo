-- =============================================================================
-- Script: Cuentas pendientes / ventas con pago diferido
-- Sistema: POS PHP Nativo
-- Descripción: Agrega campos estado_pago y fecha_pago a la tabla ventas.
--              Las ventas existentes se marcan como PAGADA.
-- Ejecutar manualmente desde phpMyAdmin (no se ejecuta automáticamente).
-- =============================================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;

-- -----------------------------------------------------------------------------
-- 1. Agregar columna estado_pago (PENDIENTE | PAGADA)
-- -----------------------------------------------------------------------------
ALTER TABLE `ventas`
  ADD COLUMN `estado_pago` VARCHAR(20) NOT NULL DEFAULT 'PAGADA'
    COMMENT 'Estado del pago: PENDIENTE o PAGADA'
    AFTER `estado`;

-- -----------------------------------------------------------------------------
-- 2. Agregar columna fecha_pago (NULL si la cuenta sigue pendiente)
-- -----------------------------------------------------------------------------
ALTER TABLE `ventas`
  ADD COLUMN `fecha_pago` DATETIME NULL DEFAULT NULL
    COMMENT 'Fecha y hora en que se cobró la cuenta'
    AFTER `estado_pago`;

-- -----------------------------------------------------------------------------
-- 3. Marcar todas las ventas existentes como PAGADA
-- -----------------------------------------------------------------------------
UPDATE `ventas`
SET `estado_pago` = 'PAGADA'
WHERE `estado_pago` IS NULL OR `estado_pago` = '';

-- -----------------------------------------------------------------------------
-- 4. Índice para filtrar por estado de pago
-- -----------------------------------------------------------------------------
ALTER TABLE `ventas`
  ADD INDEX `idx_ventas_estado_pago` (`estado_pago`);

COMMIT;

-- =============================================================================
-- PARTE 2: Tabla arqueo_caja (resumen informativo al cierre / en pantalla)
-- Ejecutar también esta sección si ya corrió la parte 1 anteriormente.
-- =============================================================================

START TRANSACTION;

-- -----------------------------------------------------------------------------
-- 5. Cantidad de cuentas pendientes (solo informativo, no suma a ingresos)
-- -----------------------------------------------------------------------------
ALTER TABLE `arqueo_caja`
  ADD COLUMN `cuentas_pendientes_cantidad` INT NOT NULL DEFAULT 0
    COMMENT 'Cantidad de cuentas con estado_pago PENDIENTE al momento del cierre'
    AFTER `diferencia`;

-- -----------------------------------------------------------------------------
-- 6. Total por cobrar de cuentas pendientes (solo informativo)
-- -----------------------------------------------------------------------------
ALTER TABLE `arqueo_caja`
  ADD COLUMN `cuentas_pendientes_total` DECIMAL(11,2) NOT NULL DEFAULT 0.00
    COMMENT 'Total por cobrar de cuentas pendientes al momento del cierre'
    AFTER `cuentas_pendientes_cantidad`;

COMMIT;

-- =============================================================================
-- Verificación opcional (ejecutar después si desea confirmar):
-- SELECT estado_pago, COUNT(*) AS total FROM ventas GROUP BY estado_pago;
-- DESCRIBE arqueo_caja;
-- =============================================================================


