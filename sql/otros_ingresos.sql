-- -----------------------------------------------------------------------------
-- Otros ingresos de caja (no son ventas)
-- Ejecutar este script en phpMyAdmin sobre la base del POS.
-- No modifica ni elimina datos existentes.
-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `otros_ingresos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_arqueo_caja` BIGINT UNSIGNED NOT NULL,
  `id_usuario` INT NOT NULL,
  `descripcion` VARCHAR(255) NOT NULL,
  `monto` DECIMAL(11,2) NOT NULL DEFAULT 0.00,
  `fecha` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estado` TINYINT NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `idx_oi_arqueo` (`id_arqueo_caja`),
  KEY `idx_oi_usuario` (`id_usuario`),
  KEY `idx_oi_estado` (`estado`),
  CONSTRAINT `fk_oi_arqueo` FOREIGN KEY (`id_arqueo_caja`) REFERENCES `arqueo_caja` (`id`),
  CONSTRAINT `fk_oi_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Permiso específico (idempotente si ya existe)
INSERT IGNORE INTO `permisos` (`modulo`, `codigo`, `nombre`, `descripcion`, `orden`, `estado`) VALUES
('Caja', 'caja.otros_ingresos', 'Registrar otros ingresos', 'Registrar ingresos de caja que no corresponden a una venta', 93, 1);

-- Asignar a Administrador, Supervisor y Vendedor (perfiles por nombre)
INSERT IGNORE INTO `perfil_permisos` (`id_perfil`, `id_permiso`)
SELECT p.`id`, pe.`id`
FROM `perfiles` p
CROSS JOIN `permisos` pe
WHERE pe.`codigo` = 'caja.otros_ingresos'
  AND p.`nombre` IN ('Administrador', 'Supervisor', 'Vendedor')
  AND p.`estado` = 1
  AND p.`activo` = 1;
