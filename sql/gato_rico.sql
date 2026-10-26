-- SOLO base de El Gato Rico.
-- No borra ni modifica ventas, productos, caja ni clientes.
-- Si la empresa ya existe, no cambia su nombre ni su dirección.
-- Se puede ejecutar más de una vez.

CREATE TABLE IF NOT EXISTS empresa (
  id INT NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(150) NOT NULL,
  direccion VARCHAR(250) NOT NULL DEFAULT '',
  logo VARCHAR(200) NOT NULL DEFAULT 'images/logo-negro-bloque.jpg',
  logo_dashboard VARCHAR(200) NOT NULL DEFAULT 'vistas/img/plantilla/Logo_POS.webp',
  nota_ticket VARCHAR(100) NOT NULL DEFAULT '',
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS funcionalidades (
  id INT NOT NULL AUTO_INCREMENT,
  codigo VARCHAR(80) NOT NULL,
  nombre VARCHAR(150) NOT NULL,
  descripcion VARCHAR(300) DEFAULT NULL,
  estado TINYINT NOT NULL DEFAULT 1,
  PRIMARY KEY (id),
  UNIQUE KEY uk_funcionalidad_codigo (codigo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS empresa_funcionalidad (
  id INT NOT NULL AUTO_INCREMENT,
  id_empresa INT NOT NULL,
  id_funcionalidad INT NOT NULL,
  activo TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  UNIQUE KEY uk_empresa_funcionalidad (id_empresa, id_funcionalidad),
  CONSTRAINT fk_ef_empresa FOREIGN KEY (id_empresa) REFERENCES empresa (id),
  CONSTRAINT fk_ef_funcionalidad FOREIGN KEY (id_funcionalidad) REFERENCES funcionalidades (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET @sql_nota = (
  SELECT IF(
    COUNT(*) = 0,
    'ALTER TABLE empresa ADD COLUMN nota_ticket VARCHAR(100) NOT NULL DEFAULT ''''',
    'SELECT 1'
  )
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'empresa'
    AND COLUMN_NAME = 'nota_ticket'
);
PREPARE stmt_nota FROM @sql_nota;
EXECUTE stmt_nota;
DEALLOCATE PREPARE stmt_nota;

SET @sql_logo = (
  SELECT IF(
    COUNT(*) = 0,
    'ALTER TABLE empresa ADD COLUMN logo_dashboard VARCHAR(200) NOT NULL DEFAULT ''vistas/img/plantilla/Logo_POS.webp''',
    'SELECT 1'
  )
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'empresa'
    AND COLUMN_NAME = 'logo_dashboard'
);
PREPARE stmt_logo FROM @sql_logo;
EXECUTE stmt_logo;
DEALLOCATE PREPARE stmt_logo;

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

INSERT INTO empresa (nombre, direccion, logo)
SELECT 'El Gato Rico', 'Heroes Del Chaco 9, Cotoca', 'images/logo-negro-bloque.jpg'
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM empresa);

INSERT INTO empresa_funcionalidad (id_empresa, id_funcionalidad, activo)
SELECT e.id, f.id, 1
FROM empresa e
INNER JOIN funcionalidades f ON f.codigo = 'venta.notas_todos_productos'
WHERE e.nombre = 'El Gato Rico'
  AND e.id = (SELECT MIN(id) FROM empresa)
  AND NOT EXISTS (
    SELECT 1 FROM empresa_funcionalidad ef
    WHERE ef.id_empresa = e.id AND ef.id_funcionalidad = f.id
  );

UPDATE empresa_funcionalidad ef
INNER JOIN funcionalidades f ON f.id = ef.id_funcionalidad
INNER JOIN empresa e ON e.id = ef.id_empresa
SET ef.activo = 1
WHERE f.codigo = 'venta.notas_todos_productos'
  AND e.nombre = 'El Gato Rico';

UPDATE empresa
SET nota_ticket = CONCAT('¡GRACIAS POR SU COMPRA!', CHAR(10), 'PEDIDOS AL 79902345')
WHERE nombre = 'El Gato Rico'
  AND (nota_ticket IS NULL OR nota_ticket = '');
