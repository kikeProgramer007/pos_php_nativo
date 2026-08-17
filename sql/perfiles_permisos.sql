-- =============================================================================
-- MÓDULO: Perfiles, roles y permisos
-- Ejecutar una sola vez en phpMyAdmin sobre la BD del POS.
-- Seguro: no borra usuarios.perfil ni datos existentes.
-- =============================================================================

SET NAMES utf8mb4;

-- -----------------------------------------------------------------------------
-- 1) Perfiles
-- estado: 1 = habilitado, 0 = deshabilitado
-- activo: 1 = vigente, 0 = eliminado (soft delete, igual que usuarios)
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `perfiles` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(80) NOT NULL,
  `descripcion` VARCHAR(255) NULL DEFAULT NULL,
  `estado` TINYINT(1) NOT NULL DEFAULT 1,
  `activo` TINYINT(1) NOT NULL DEFAULT 1,
  `fecha` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_perfiles_nombre` (`nombre`),
  KEY `idx_perfiles_estado` (`estado`),
  KEY `idx_perfiles_activo` (`activo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------------------------------
-- 2) Catálogo de permisos
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `permisos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `modulo` VARCHAR(80) NOT NULL,
  `codigo` VARCHAR(80) NOT NULL,
  `nombre` VARCHAR(120) NOT NULL,
  `descripcion` VARCHAR(255) NULL DEFAULT NULL,
  `orden` INT(11) NOT NULL DEFAULT 0,
  `estado` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_permisos_codigo` (`codigo`),
  KEY `idx_permisos_modulo` (`modulo`),
  KEY `idx_permisos_estado` (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------------------------------
-- 3) Relación perfil ↔ permisos
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `perfil_permisos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_perfil` INT(11) NOT NULL,
  `id_permiso` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_perfil_permiso` (`id_perfil`, `id_permiso`),
  KEY `idx_pp_perfil` (`id_perfil`),
  KEY `idx_pp_permiso` (`id_permiso`),
  CONSTRAINT `fk_pp_perfil` FOREIGN KEY (`id_perfil`) REFERENCES `perfiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_pp_permiso` FOREIGN KEY (`id_permiso`) REFERENCES `permisos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------------------------------
-- 4) Perfiles iniciales
-- -----------------------------------------------------------------------------
INSERT IGNORE INTO `perfiles` (`id`, `nombre`, `descripcion`, `estado`, `activo`) VALUES
(1, 'Administrador', 'Acceso completo al sistema', 1, 1),
(2, 'Supervisor', 'Supervisión operativa sin administración de usuarios/perfiles', 1, 1),
(3, 'Vendedor', 'Caja, ventas e impresión de reportes básicos', 1, 1);

-- -----------------------------------------------------------------------------
-- 5) Permisos según módulos y rutas reales del POS
-- -----------------------------------------------------------------------------
INSERT IGNORE INTO `permisos` (`modulo`, `codigo`, `nombre`, `descripcion`, `orden`, `estado`) VALUES
('Inicio', 'inicio.ver', 'Ver inicio', 'Acceder al panel de inicio', 10, 1),

('Usuarios', 'usuarios.ver', 'Ver usuarios', 'Listar usuarios', 20, 1),
('Usuarios', 'usuarios.crear', 'Agregar usuario', 'Registrar usuarios', 21, 1),
('Usuarios', 'usuarios.editar', 'Editar usuario', 'Modificar o activar/desactivar usuarios', 22, 1),
('Usuarios', 'usuarios.eliminar', 'Eliminar usuario', 'Desactivar/eliminar usuarios', 23, 1),
('Usuarios', 'usuarios.eliminados', 'Ver usuarios eliminados', 'Listar y restaurar usuarios eliminados', 24, 1),

('Perfiles', 'perfiles.ver', 'Ver perfiles', 'Listar perfiles', 30, 1),
('Perfiles', 'perfiles.crear', 'Agregar perfil', 'Crear perfiles', 31, 1),
('Perfiles', 'perfiles.editar', 'Editar perfil', 'Modificar perfiles', 32, 1),
('Perfiles', 'perfiles.eliminar', 'Eliminar perfil', 'Desactivar/eliminar perfiles', 33, 1),
('Perfiles', 'perfiles.permisos', 'Asignar permisos', 'Configurar permisos de un perfil', 34, 1),
('Perfiles', 'perfiles.eliminados', 'Ver perfiles eliminados', 'Listar y restaurar perfiles eliminados', 35, 1),

('Categorías', 'categorias.ver', 'Ver categorías', 'Listar categorías', 40, 1),
('Categorías', 'categorias.crear', 'Agregar categoría', 'Crear categorías', 41, 1),
('Categorías', 'categorias.editar', 'Editar categoría', 'Modificar categorías', 42, 1),
('Categorías', 'categorias.eliminar', 'Eliminar categoría', 'Eliminar categorías', 43, 1),
('Categorías', 'categorias.eliminados', 'Ver categorías eliminadas', 'Listar categorías eliminadas', 44, 1),

('Productos', 'productos.ver', 'Ver productos', 'Listar productos', 50, 1),
('Productos', 'productos.crear', 'Agregar producto', 'Crear productos', 51, 1),
('Productos', 'productos.editar', 'Editar producto', 'Modificar productos', 52, 1),
('Productos', 'productos.eliminar', 'Eliminar producto', 'Eliminar productos', 53, 1),
('Productos', 'productos.eliminados', 'Ver productos eliminados', 'Listar productos eliminados', 54, 1),

('Ofertas y promociones', 'promociones.ver', 'Ver promociones', 'Listar ofertas y promociones', 60, 1),
('Ofertas y promociones', 'promociones.crear', 'Agregar promoción', 'Crear promociones', 61, 1),
('Ofertas y promociones', 'promociones.editar', 'Editar promoción', 'Modificar o habilitar/deshabilitar promociones', 62, 1),
('Ofertas y promociones', 'promociones.eliminar', 'Eliminar promoción', 'Eliminar promociones', 63, 1),

('Clientes', 'clientes.ver', 'Ver clientes', 'Listar clientes', 70, 1),
('Clientes', 'clientes.crear', 'Agregar cliente', 'Crear clientes', 71, 1),
('Clientes', 'clientes.editar', 'Editar cliente', 'Modificar clientes', 72, 1),
('Clientes', 'clientes.eliminar', 'Eliminar cliente', 'Eliminar clientes', 73, 1),
('Clientes', 'clientes.eliminados', 'Ver clientes eliminados', 'Listar clientes eliminados', 74, 1),

('Meseros', 'meseros.ver', 'Ver meseros', 'Listar meseros', 80, 1),
('Meseros', 'meseros.crear', 'Agregar mesero', 'Crear meseros', 81, 1),
('Meseros', 'meseros.editar', 'Editar mesero', 'Modificar meseros', 82, 1),
('Meseros', 'meseros.eliminar', 'Eliminar mesero', 'Eliminar meseros', 83, 1),
('Meseros', 'meseros.eliminados', 'Ver meseros eliminados', 'Listar meseros eliminados', 84, 1),

('Caja', 'caja.ver', 'Ver caja / arqueo', 'Acceder al arqueo de caja', 90, 1),
('Caja', 'caja.abrir', 'Abrir caja', 'Aperturar caja', 91, 1),
('Caja', 'caja.cerrar', 'Cerrar caja', 'Cerrar arqueo de caja', 92, 1),
('Caja', 'caja.otros_ingresos', 'Registrar otros ingresos', 'Registrar ingresos de caja que no corresponden a una venta', 93, 1),

('Gastos', 'gastos.ver', 'Ver gastos', 'Listar gastos de caja', 100, 1),
('Gastos', 'gastos.crear', 'Registrar gasto', 'Crear gastos', 101, 1),
('Gastos', 'gastos.editar', 'Editar gasto', 'Modificar gastos', 102, 1),
('Gastos', 'gastos.eliminar', 'Eliminar gasto', 'Eliminar gastos', 103, 1),

('Ventas', 'ventas.crear', 'Crear venta', 'Acceder a vender / crear venta', 110, 1),
('Ventas', 'ventas.ver', 'Ver ventas realizadas', 'Listar ventas', 111, 1),
('Ventas', 'ventas.editar', 'Editar cuenta pendiente', 'Actualizar cuentas pendientes', 112, 1),
('Ventas', 'ventas.cobrar', 'Cobrar cuenta', 'Cobrar cuentas pendientes', 113, 1),
('Ventas', 'ventas.eliminar', 'Anular venta', 'Anular o eliminar ventas', 114, 1),
('Ventas', 'ventas.eliminados', 'Ver ventas eliminadas', 'Listar ventas anuladas', 115, 1),
('Ventas', 'ventas.imprimir', 'Imprimir ticket', 'Reimprimir tickets', 116, 1),

('Compras', 'compras.ver', 'Ver compras', 'Administrar compras', 120, 1),
('Compras', 'compras.crear', 'Crear compra', 'Registrar compras', 121, 1),
('Compras', 'compras.eliminar', 'Eliminar compra', 'Anular compras', 122, 1),
('Compras', 'compras.eliminados', 'Ver compras eliminadas', 'Listar compras eliminadas', 123, 1),

('Proveedores', 'proveedores.ver', 'Ver proveedores', 'Listar proveedores', 130, 1),
('Proveedores', 'proveedores.crear', 'Agregar proveedor', 'Crear proveedores', 131, 1),
('Proveedores', 'proveedores.editar', 'Editar proveedor', 'Modificar proveedores', 132, 1),
('Proveedores', 'proveedores.eliminar', 'Eliminar proveedor', 'Eliminar proveedores', 133, 1),
('Proveedores', 'proveedores.eliminados', 'Ver proveedores eliminados', 'Listar proveedores eliminados', 134, 1),

('Reportes', 'reportes.ventas', 'Reporte de ventas', 'Gráfico y reporte general de ventas', 140, 1),
('Reportes', 'reportes.venta_fecha', 'Ventas por fecha', 'Reporte de ventas por rango de fechas', 141, 1),
('Reportes', 'reportes.top_productos', 'Productos más vendidos', 'Top productos', 142, 1),
('Reportes', 'reportes.top_meseros', 'Meseros con más ventas', 'Top meseros', 143, 1),
('Reportes', 'reportes.faltantes', 'Productos faltantes', 'Stock mínimo / faltantes', 144, 1),
('Reportes', 'reportes.categorias', 'Reporte de categorías', 'Ventas por categoría', 145, 1),
('Reportes', 'reportes.ganancias', 'Reporte de ganancias', 'Utilidades', 146, 1),
('Reportes', 'reportes.compras', 'Reporte de compras', 'Compras por fecha', 147, 1);

-- -----------------------------------------------------------------------------
-- 6) Administrador: todos los permisos
-- -----------------------------------------------------------------------------
INSERT IGNORE INTO `perfil_permisos` (`id_perfil`, `id_permiso`)
SELECT 1, `id` FROM `permisos` WHERE `estado` = 1;

-- -----------------------------------------------------------------------------
-- 7) Supervisor: todo menos usuarios y perfiles (coincide con el backend actual)
-- -----------------------------------------------------------------------------
INSERT IGNORE INTO `perfil_permisos` (`id_perfil`, `id_permiso`)
SELECT 2, `id` FROM `permisos`
WHERE `estado` = 1
  AND `codigo` NOT LIKE 'usuarios.%'
  AND `codigo` NOT LIKE 'perfiles.%';

-- -----------------------------------------------------------------------------
-- 8) Vendedor: caja, ventas y reportes (coincide con el menú actual)
-- -----------------------------------------------------------------------------
INSERT IGNORE INTO `perfil_permisos` (`id_perfil`, `id_permiso`)
SELECT 3, `id` FROM `permisos`
WHERE `codigo` IN (
  'inicio.ver',
  'caja.ver', 'caja.abrir', 'caja.cerrar', 'caja.otros_ingresos',
  'ventas.crear', 'ventas.ver', 'ventas.editar', 'ventas.cobrar', 'ventas.imprimir',
  'reportes.ventas', 'reportes.venta_fecha', 'reportes.top_productos', 'reportes.top_meseros',
  'reportes.faltantes', 'reportes.categorias', 'reportes.ganancias', 'reportes.compras'
);

-- -----------------------------------------------------------------------------
-- 9) usuarios.id_perfil (no se elimina usuarios.perfil)
-- -----------------------------------------------------------------------------
SET @db := DATABASE();
SET @col_exists := (
  SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'usuarios' AND COLUMN_NAME = 'id_perfil'
);
SET @sql := IF(@col_exists = 0,
  'ALTER TABLE `usuarios` ADD COLUMN `id_perfil` INT(11) NULL DEFAULT NULL AFTER `perfil`',
  'SELECT "id_perfil ya existe" AS info');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

UPDATE `usuarios` u
INNER JOIN `perfiles` p ON LOWER(TRIM(p.nombre)) = LOWER(TRIM(u.perfil))
SET u.id_perfil = p.id
WHERE u.id_perfil IS NULL AND u.perfil IS NOT NULL AND u.perfil <> '';

SET @fk_exists := (
  SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS
  WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'usuarios' AND CONSTRAINT_NAME = 'fk_usuarios_perfil'
);
SET @sql := IF(@fk_exists = 0,
  'ALTER TABLE `usuarios` ADD CONSTRAINT `fk_usuarios_perfil` FOREIGN KEY (`id_perfil`) REFERENCES `perfiles` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT',
  'SELECT "fk_usuarios_perfil ya existe" AS info');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- -----------------------------------------------------------------------------
-- 10) Después de verificar que todo funciona con id_perfil, se podrá eliminar
--     la columna antigua usuarios.perfil (NO ejecutar ahora):
-- ALTER TABLE `usuarios` DROP COLUMN `perfil`;
-- -----------------------------------------------------------------------------

