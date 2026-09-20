-- =============================================================================
-- Migración: meseros.compras -> meseros.ventas_atendidas
--            eliminar meseros.ultima_compra (ya no se usa en el sistema)
-- Ejecutar en producción ANTES o al mismo tiempo que el deploy del código PHP.
-- (Si el PHP nuevo corre sin esta migración, fallará al leer ventas_atendidas.)
-- =============================================================================

-- 1) Renombrar contador de unidades atendidas por mesero
ALTER TABLE `meseros`
  CHANGE COLUMN `compras` `ventas_atendidas` INT NOT NULL DEFAULT 0;

-- 2) Eliminar fecha de última compra (campo sin uso en UI/reportes)
ALTER TABLE `meseros`
  DROP COLUMN `ultima_compra`;
