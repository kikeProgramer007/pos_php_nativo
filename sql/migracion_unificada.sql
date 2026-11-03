-- =============================================================================
-- MIGRACIÓN UNIFICADA — POS PHP Nativo
-- =============================================================================
-- Para bases importadas desde pos_php_nativo-main.sql (u otro backup) que aún
-- NO tienen la versión nueva del código.
--
-- Cómo usar (phpMyAdmin / MySQL):
--   1. Importá el backup (ej. pos_php_nativo-main.sql) si aún no está.
--   2. Seleccioná esa base de datos.
--   3. Pegá ESTE archivo completo y Ejecutar.
--
-- Idempotente: se puede correr más de una vez sin romper datos.
-- No borra ventas, productos, clientes ni caja.
-- No recalcula totales históricos de compras/arqueos cerrados.
--
-- Incluye:
--   0) Empresa / funcionalidades base (si faltaran en backups muy viejos)
--   1) Compras: formas de pago (tipo_pago, total_efectivo, total_qr)
--   2) Arqueo: monto_compras_efectivo / monto_compras_qr + relleno cajas abiertas
--   3) Gestión de Mesas: funcionalidad, tabla mesas, permisos CRUD
--   4) Ventas ↔ mesas + atencion_mesa
--   5) ids_mesas_extra (hasta 5 mesas unidas; sin id_mesa_2)
--   6) Mesas: numero + descripcion
--   7) Quitar permisos obsoletos mesas.liberar / mesas.cambiar
--      (liberar/cambiar van en crear-venta con Gestión de Mesas + ventas.*)
--   8) Limpieza: migra id_mesa_2 → ids_mesas_extra y elimina columnas de más
--   9) Cobrar después / cobro posterior (funcionalidad Empresa)
-- =============================================================================

-- =============================================================================
-- 0) EMPRESA / FUNCIONALIDADES BASE (backups antiguos sin estas tablas)
-- =============================================================================
CREATE TABLE IF NOT EXISTS `empresa` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(150) NOT NULL,
  `direccion` VARCHAR(250) NOT NULL DEFAULT '',
  `logo` VARCHAR(200) NOT NULL DEFAULT 'images/logo-negro-bloque.jpg',
  `logo_dashboard` VARCHAR(200) NOT NULL DEFAULT 'vistas/img/plantilla/Logo_POS.webp',
  `nota_ticket` VARCHAR(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `funcionalidades` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `codigo` VARCHAR(80) NOT NULL,
  `nombre` VARCHAR(150) NOT NULL,
  `descripcion` VARCHAR(300) DEFAULT NULL,
  `estado` TINYINT NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_funcionalidad_codigo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `empresa_funcionalidad` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_empresa` INT NOT NULL,
  `id_funcionalidad` INT NOT NULL,
  `activo` TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_empresa_funcionalidad` (`id_empresa`, `id_funcionalidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET @sql := (
  SELECT IF(
    COUNT(*) = 0,
    'ALTER TABLE `empresa` ADD COLUMN `nota_ticket` VARCHAR(100) NOT NULL DEFAULT ''''',
    'SELECT 1'
  )
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'empresa' AND COLUMN_NAME = 'nota_ticket'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := (
  SELECT IF(
    COUNT(*) = 0,
    'ALTER TABLE `empresa` ADD COLUMN `logo_dashboard` VARCHAR(200) NOT NULL DEFAULT ''vistas/img/plantilla/Logo_POS.webp''',
    'SELECT 1'
  )
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'empresa' AND COLUMN_NAME = 'logo_dashboard'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

INSERT INTO funcionalidades (codigo, nombre, descripcion, estado)
SELECT 'venta.notas_todos_productos',
       'Notas en todos los productos',
       'En crear venta, preferencias y nota adicional salen en todos los productos, también los inventariables.',
       1
FROM DUAL
WHERE NOT EXISTS (
  SELECT 1 FROM funcionalidades WHERE codigo = 'venta.notas_todos_productos'
);

INSERT INTO permisos (modulo, codigo, nombre, descripcion, orden, estado)
SELECT 'Empresa', 'empresa.ver', 'Empresa y funcionalidades', 'Datos del negocio y funciones activas', 90, 1
FROM DUAL
WHERE NOT EXISTS (
  SELECT 1 FROM permisos WHERE codigo = 'empresa.ver'
);

INSERT INTO perfil_permisos (id_perfil, id_permiso)
SELECT p.id, perm.id
FROM perfiles p
INNER JOIN permisos perm ON perm.codigo = 'empresa.ver'
WHERE LOWER(p.nombre) IN ('administrador', 'soporte')
  AND NOT EXISTS (
    SELECT 1 FROM perfil_permisos pp
    WHERE pp.id_perfil = p.id AND pp.id_permiso = perm.id
  );

INSERT INTO perfil_permisos (id_perfil, id_permiso)
SELECT DISTINCT pp.id_perfil, perm.id
FROM perfil_permisos pp
INNER JOIN permisos origen ON origen.id = pp.id_permiso AND origen.codigo = 'perfiles.ver'
INNER JOIN permisos perm ON perm.codigo = 'empresa.ver'
WHERE NOT EXISTS (
  SELECT 1 FROM perfil_permisos ya
  WHERE ya.id_perfil = pp.id_perfil AND ya.id_permiso = perm.id
);

INSERT INTO empresa_funcionalidad (id_empresa, id_funcionalidad, activo)
SELECT e.id, f.id, 1
FROM empresa e
INNER JOIN funcionalidades f ON f.codigo = 'venta.notas_todos_productos'
WHERE NOT EXISTS (
  SELECT 1 FROM empresa_funcionalidad ef
  WHERE ef.id_empresa = e.id AND ef.id_funcionalidad = f.id
);

-- =============================================================================
-- 1) COMPRAS: formas de pago
-- =============================================================================
SET @sql := (
  SELECT IF(
    COUNT(*) = 0,
    'ALTER TABLE `compras` ADD COLUMN `tipo_pago` VARCHAR(20) NULL DEFAULT NULL AFTER `descontar_caja`',
    'SELECT 1'
  )
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'compras' AND COLUMN_NAME = 'tipo_pago'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := (
  SELECT IF(
    COUNT(*) = 0,
    'ALTER TABLE `compras` ADD COLUMN `total_efectivo` DECIMAL(12,2) NULL DEFAULT NULL AFTER `tipo_pago`',
    'SELECT 1'
  )
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'compras' AND COLUMN_NAME = 'total_efectivo'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := (
  SELECT IF(
    COUNT(*) = 0,
    'ALTER TABLE `compras` ADD COLUMN `total_qr` DECIMAL(12,2) NULL DEFAULT NULL AFTER `total_efectivo`',
    'SELECT 1'
  )
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'compras' AND COLUMN_NAME = 'total_qr'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- =============================================================================
-- 2) ARQUEO: desglose compras + relleno solo cajas ABIERTAS
-- =============================================================================
SET @sql := (
  SELECT IF(
    COUNT(*) = 0,
    'ALTER TABLE `arqueo_caja` ADD COLUMN `monto_compras_efectivo` DECIMAL(11,2) NOT NULL DEFAULT 0.00 AFTER `monto_compras`',
    'SELECT 1'
  )
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'arqueo_caja' AND COLUMN_NAME = 'monto_compras_efectivo'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := (
  SELECT IF(
    COUNT(*) = 0,
    'ALTER TABLE `arqueo_caja` ADD COLUMN `monto_compras_qr` DECIMAL(11,2) NOT NULL DEFAULT 0.00 AFTER `monto_compras_efectivo`',
    'SELECT 1'
  )
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'arqueo_caja' AND COLUMN_NAME = 'monto_compras_qr'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Compras sin forma de pago: todo el total a efectivo (comportamiento anterior)
UPDATE `arqueo_caja` a
SET
  a.`monto_compras_efectivo` = (
    SELECT COALESCE(SUM(
      CASE
        WHEN COALESCE(c.descontar_caja, 0) <> 1 THEN 0
        WHEN c.tipo_pago IS NULL OR c.tipo_pago = '' THEN c.total
        WHEN c.tipo_pago IN ('1', 'Efectivo') THEN COALESCE(c.total_efectivo, c.total)
        WHEN c.tipo_pago IN ('4', 'Mixto') THEN COALESCE(c.total_efectivo, 0)
        ELSE 0
      END
    ), 0)
    FROM `compras` c
    WHERE c.id_arqueo_caja = a.id
      AND c.estado = 1
  ),
  a.`monto_compras_qr` = (
    SELECT COALESCE(SUM(
      CASE
        WHEN COALESCE(c.descontar_caja, 0) <> 1 THEN 0
        WHEN c.tipo_pago IS NULL OR c.tipo_pago = '' THEN 0
        WHEN c.tipo_pago IN ('2', 'QR') THEN COALESCE(c.total_qr, c.total)
        WHEN c.tipo_pago IN ('4', 'Mixto') THEN COALESCE(c.total_qr, 0)
        ELSE 0
      END
    ), 0)
    FROM `compras` c
    WHERE c.id_arqueo_caja = a.id
      AND c.estado = 1
  )
WHERE a.estado = 'abierta';

-- =============================================================================
-- 3) GESTIÓN DE MESAS (funcionalidad + tabla + permisos CRUD)
-- =============================================================================
INSERT INTO funcionalidades (codigo, nombre, descripcion, estado)
SELECT 'venta.gestion_mesas',
       'Gestión de Mesas',
       'Habilita la atención por mesas en el módulo de ventas.',
       1
FROM DUAL
WHERE NOT EXISTS (
  SELECT 1 FROM funcionalidades WHERE codigo = 'venta.gestion_mesas'
);

INSERT INTO empresa_funcionalidad (id_empresa, id_funcionalidad, activo)
SELECT e.id, f.id, 0
FROM empresa e
INNER JOIN funcionalidades f ON f.codigo = 'venta.gestion_mesas'
WHERE NOT EXISTS (
  SELECT 1 FROM empresa_funcionalidad ef
  WHERE ef.id_empresa = e.id AND ef.id_funcionalidad = f.id
);

CREATE TABLE IF NOT EXISTS `mesas` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `numero` INT NULL DEFAULT NULL,
  `nombre` VARCHAR(80) NOT NULL,
  `descripcion` VARCHAR(120) NULL DEFAULT NULL,
  `capacidad` INT NOT NULL DEFAULT 1,
  `estado` VARCHAR(20) NOT NULL DEFAULT 'disponible',
  `fecha_alta` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_mesa_nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO permisos (modulo, codigo, nombre, descripcion, orden, estado)
SELECT 'Mesas', 'mesas.ver', 'Ver mesas', 'Listar mesas del salón', 91, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM permisos WHERE codigo = 'mesas.ver');

INSERT INTO permisos (modulo, codigo, nombre, descripcion, orden, estado)
SELECT 'Mesas', 'mesas.crear', 'Crear mesas', 'Registrar mesas nuevas', 92, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM permisos WHERE codigo = 'mesas.crear');

INSERT INTO permisos (modulo, codigo, nombre, descripcion, orden, estado)
SELECT 'Mesas', 'mesas.editar', 'Editar mesas', 'Modificar nombre, capacidad o estado', 93, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM permisos WHERE codigo = 'mesas.editar');

INSERT INTO permisos (modulo, codigo, nombre, descripcion, orden, estado)
SELECT 'Mesas', 'mesas.eliminar', 'Eliminar mesas', 'Quitar mesas sin historial de ocupación', 94, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM permisos WHERE codigo = 'mesas.eliminar');

INSERT INTO perfil_permisos (id_perfil, id_permiso)
SELECT p.id, perm.id
FROM perfiles p
INNER JOIN permisos perm ON perm.codigo IN ('mesas.ver', 'mesas.crear', 'mesas.editar', 'mesas.eliminar')
WHERE LOWER(p.nombre) IN ('administrador', 'soporte')
  AND NOT EXISTS (
    SELECT 1 FROM perfil_permisos pp
    WHERE pp.id_perfil = p.id AND pp.id_permiso = perm.id
  );

INSERT INTO perfil_permisos (id_perfil, id_permiso)
SELECT DISTINCT pp.id_perfil, perm.id
FROM perfil_permisos pp
INNER JOIN permisos origen ON origen.id = pp.id_permiso AND origen.codigo = 'empresa.ver'
INNER JOIN permisos perm ON perm.codigo IN ('mesas.ver', 'mesas.crear', 'mesas.editar', 'mesas.eliminar')
WHERE NOT EXISTS (
  SELECT 1 FROM perfil_permisos ya
  WHERE ya.id_perfil = pp.id_perfil AND ya.id_permiso = perm.id
);

-- =============================================================================
-- 4) VENTAS ↔ MESAS + tabla atencion_mesa
-- =============================================================================
SET @sql := (
  SELECT IF(
    COUNT(*) = 0,
    'ALTER TABLE `ventas` ADD COLUMN `id_mesa` INT NULL DEFAULT NULL AFTER `forma_atencion`',
    'SELECT 1'
  )
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'ventas' AND COLUMN_NAME = 'id_mesa'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := (
  SELECT IF(
    COUNT(*) = 0,
    'ALTER TABLE `ventas` ADD COLUMN `cantidad_personas` INT NULL DEFAULT NULL AFTER `id_mesa`',
    'SELECT 1'
  )
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'ventas' AND COLUMN_NAME = 'cantidad_personas'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := (
  SELECT IF(
    COUNT(*) = 0,
    'ALTER TABLE `ventas` ADD COLUMN `id_atencion_mesa` INT NULL DEFAULT NULL AFTER `cantidad_personas`',
    'SELECT 1'
  )
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'ventas' AND COLUMN_NAME = 'id_atencion_mesa'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

CREATE TABLE IF NOT EXISTS `atencion_mesa` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_mesa` INT NOT NULL,
  `ids_mesas_extra` VARCHAR(100) NULL DEFAULT NULL,
  `cantidad_personas` INT NOT NULL DEFAULT 1,
  `estado` VARCHAR(20) NOT NULL DEFAULT 'abierta',
  `fecha_apertura` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_cierre` DATETIME NULL DEFAULT NULL,
  `id_usuario_apertura` INT NULL DEFAULT NULL,
  `id_usuario_cierre` INT NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_atencion_mesa_mesa` (`id_mesa`),
  KEY `idx_atencion_mesa_estado` (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET @sql := (
  SELECT IF(
    COUNT(*) = 0,
    'ALTER TABLE `ventas` ADD KEY `idx_ventas_id_mesa` (`id_mesa`)',
    'SELECT 1'
  )
  FROM information_schema.STATISTICS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'ventas' AND INDEX_NAME = 'idx_ventas_id_mesa'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := (
  SELECT IF(
    COUNT(*) = 0,
    'ALTER TABLE `ventas` ADD KEY `idx_ventas_id_atencion_mesa` (`id_atencion_mesa`)',
    'SELECT 1'
  )
  FROM information_schema.STATISTICS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'ventas' AND INDEX_NAME = 'idx_ventas_id_atencion_mesa'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- =============================================================================
-- 5) ids_mesas_extra (mesas unidas; sin id_mesa_2)
-- =============================================================================
SET @sql := (
  SELECT IF(
    COUNT(*) = 0,
    'ALTER TABLE `ventas` ADD COLUMN `ids_mesas_extra` VARCHAR(100) NULL DEFAULT NULL AFTER `id_mesa`',
    'SELECT 1'
  )
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'ventas' AND COLUMN_NAME = 'ids_mesas_extra'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := (
  SELECT IF(
    COUNT(*) = 0,
    'ALTER TABLE `atencion_mesa` ADD COLUMN `ids_mesas_extra` VARCHAR(100) NULL DEFAULT NULL AFTER `id_mesa`',
    'SELECT 1'
  )
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'atencion_mesa' AND COLUMN_NAME = 'ids_mesas_extra'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- =============================================================================
-- 6) MESAS: numero + descripcion (si la tabla ya existía sin ellas)
-- =============================================================================
SET @sql := (
  SELECT IF(
    COUNT(*) = 0,
    'ALTER TABLE `mesas` ADD COLUMN `numero` INT NULL DEFAULT NULL AFTER `id`',
    'SELECT 1'
  )
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'mesas' AND COLUMN_NAME = 'numero'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := (
  SELECT IF(
    COUNT(*) = 0,
    'ALTER TABLE `mesas` ADD COLUMN `descripcion` VARCHAR(120) NULL DEFAULT NULL AFTER `nombre`',
    'SELECT 1'
  )
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'mesas' AND COLUMN_NAME = 'descripcion'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- =============================================================================
-- 7) QUITAR permisos obsoletos Liberar / Cambiar mesa
--     Esas acciones salen del offcanvas de Crear venta si Gestión de Mesas
--     está activa y el perfil tiene ventas.crear / editar / cobrar.
-- =============================================================================
DELETE pp
FROM perfil_permisos pp
INNER JOIN permisos pe ON pe.id = pp.id_permiso
WHERE pe.codigo IN ('mesas.liberar', 'mesas.cambiar');

UPDATE permisos
SET estado = 0
WHERE codigo IN ('mesas.liberar', 'mesas.cambiar');

-- =============================================================================
-- 8) LIMPIEZA: migrar id_mesa_2 → ids_mesas_extra y eliminar columnas de más
-- =============================================================================
SET @sql := (
  SELECT IF(
    COUNT(*) > 0,
    'UPDATE `ventas` SET `ids_mesas_extra` = CASE
        WHEN `ids_mesas_extra` IS NULL OR `ids_mesas_extra` = '''' THEN CAST(`id_mesa_2` AS CHAR)
        WHEN FIND_IN_SET(`id_mesa_2`, `ids_mesas_extra`) > 0 THEN `ids_mesas_extra`
        ELSE CONCAT(`ids_mesas_extra`, CHAR(44), `id_mesa_2`)
      END
      WHERE `id_mesa_2` IS NOT NULL',
    'SELECT 1'
  )
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'ventas' AND COLUMN_NAME = 'id_mesa_2'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := (
  SELECT IF(
    COUNT(*) > 0,
    'UPDATE `atencion_mesa` SET `ids_mesas_extra` = CASE
        WHEN `ids_mesas_extra` IS NULL OR `ids_mesas_extra` = '''' THEN CAST(`id_mesa_2` AS CHAR)
        WHEN FIND_IN_SET(`id_mesa_2`, `ids_mesas_extra`) > 0 THEN `ids_mesas_extra`
        ELSE CONCAT(`ids_mesas_extra`, CHAR(44), `id_mesa_2`)
      END
      WHERE `id_mesa_2` IS NOT NULL',
    'SELECT 1'
  )
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'atencion_mesa' AND COLUMN_NAME = 'id_mesa_2'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := (
  SELECT IF(
    COUNT(*) > 0,
    'ALTER TABLE `ventas` DROP INDEX `idx_ventas_id_mesa_2`',
    'SELECT 1'
  )
  FROM information_schema.STATISTICS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'ventas' AND INDEX_NAME = 'idx_ventas_id_mesa_2'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := (
  SELECT IF(
    COUNT(*) > 0,
    'ALTER TABLE `ventas` DROP COLUMN `id_mesa_2`',
    'SELECT 1'
  )
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'ventas' AND COLUMN_NAME = 'id_mesa_2'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := (
  SELECT IF(
    COUNT(*) > 0,
    'ALTER TABLE `atencion_mesa` DROP INDEX `idx_atencion_mesa_mesa_2`',
    'SELECT 1'
  )
  FROM information_schema.STATISTICS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'atencion_mesa' AND INDEX_NAME = 'idx_atencion_mesa_mesa_2'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := (
  SELECT IF(
    COUNT(*) > 0,
    'ALTER TABLE `atencion_mesa` DROP COLUMN `id_mesa_2`',
    'SELECT 1'
  )
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'atencion_mesa' AND COLUMN_NAME = 'id_mesa_2'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := (
  SELECT IF(
    COUNT(*) > 0,
    'ALTER TABLE `atencion_mesa` DROP COLUMN `autorizado_con_deuda`',
    'SELECT 1'
  )
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'atencion_mesa' AND COLUMN_NAME = 'autorizado_con_deuda'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := (
  SELECT IF(
    COUNT(*) > 0,
    'ALTER TABLE `atencion_mesa` DROP COLUMN `id_usuario_autoriza`',
    'SELECT 1'
  )
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'atencion_mesa' AND COLUMN_NAME = 'id_usuario_autoriza'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := (
  SELECT IF(
    COUNT(*) > 0,
    'ALTER TABLE `atencion_mesa` DROP COLUMN `observacion`',
    'SELECT 1'
  )
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'atencion_mesa' AND COLUMN_NAME = 'observacion'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- =============================================================================
-- 9) COBRAR DESPUÉS / COBRO POSTERIOR (Empresa → Funcionalidad)
-- =============================================================================
INSERT INTO funcionalidades (codigo, nombre, descripcion, estado)
SELECT 'venta.cobrar_despues',
       'Cobrar después / cobro posterior',
       'Permite guardar ventas como cuenta pendiente (Cobrar después) y cobrarlas luego desde Ventas realizadas.',
       1
FROM DUAL
WHERE NOT EXISTS (
  SELECT 1 FROM funcionalidades WHERE codigo = 'venta.cobrar_despues'
);

INSERT INTO empresa_funcionalidad (id_empresa, id_funcionalidad, activo)
SELECT e.id, f.id, 1
FROM empresa e
INNER JOIN funcionalidades f ON f.codigo = 'venta.cobrar_despues'
WHERE NOT EXISTS (
  SELECT 1 FROM empresa_funcionalidad ef
  WHERE ef.id_empresa = e.id AND ef.id_funcionalidad = f.id
);

-- =============================================================================
-- Verificación (opcional, ejecutar aparte):
-- SHOW COLUMNS FROM compras WHERE Field IN ('tipo_pago','total_efectivo','total_qr');
-- SHOW COLUMNS FROM arqueo_caja WHERE Field LIKE 'monto_compras%';
-- SHOW COLUMNS FROM ventas WHERE Field IN ('id_mesa','ids_mesas_extra','cantidad_personas','id_atencion_mesa');
-- SHOW COLUMNS FROM atencion_mesa;
-- SHOW COLUMNS FROM mesas WHERE Field IN ('numero','descripcion');
-- SELECT codigo, nombre FROM funcionalidades
--   WHERE codigo IN ('venta.gestion_mesas','venta.cobrar_despues','venta.notas_todos_productos');
-- SELECT codigo, estado FROM permisos WHERE codigo LIKE 'mesas.%' ORDER BY codigo;
-- (mesas.liberar / mesas.cambiar deben quedar estado=0 o ausentes)
-- =============================================================================
