<?php

require_once __DIR__ . "/conexion.php";

class ModeloPerfiles
{
	static public function mdlMostrarPerfiles($activo = 1, $estado = null)
	{
		$sql = "SELECT * FROM perfiles WHERE activo = :activo";
		if ($estado !== null) {
			$sql .= " AND estado = :estado";
		}
		$sql .= " ORDER BY id ASC";

		$stmt = Conexion::conectar()->prepare($sql);
		$stmt->bindParam(":activo", $activo, PDO::PARAM_INT);
		if ($estado !== null) {
			$stmt->bindParam(":estado", $estado, PDO::PARAM_INT);
		}
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	static public function mdlMostrarPerfil($item, $valor)
	{
		$stmt = Conexion::conectar()->prepare("SELECT * FROM perfiles WHERE $item = :valor LIMIT 1");
		$stmt->bindParam(":valor", $valor, PDO::PARAM_STR);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	static public function mdlIngresarPerfil($datos)
	{
		$stmt = Conexion::conectar()->prepare(
			"INSERT INTO perfiles(nombre, descripcion, estado, activo) VALUES (:nombre, :descripcion, :estado, 1)"
		);
		$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
		$stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_INT);
		return $stmt->execute() ? "ok" : "error";
	}

	static public function mdlEditarPerfil($datos)
	{
		$stmt = Conexion::conectar()->prepare(
			"UPDATE perfiles SET nombre = :nombre, descripcion = :descripcion, estado = :estado WHERE id = :id AND activo = 1"
		);
		$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
		$stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_INT);
		$stmt->bindParam(":id", $datos["id"], PDO::PARAM_INT);
		return $stmt->execute() ? "ok" : "error";
	}

	static public function mdlActualizarPerfil($item1, $valor1, $id)
	{
		$stmt = Conexion::conectar()->prepare("UPDATE perfiles SET $item1 = :valor WHERE id = :id");
		$stmt->bindParam(":valor", $valor1, PDO::PARAM_STR);
		$stmt->bindParam(":id", $id, PDO::PARAM_INT);
		return $stmt->execute() ? "ok" : "error";
	}

	static public function mdlContarUsuariosDePerfil($idPerfil)
	{
		$stmt = Conexion::conectar()->prepare(
			"SELECT COUNT(*) AS total FROM usuarios WHERE id_perfil = :id AND activo = 1"
		);
		$stmt->bindParam(":id", $idPerfil, PDO::PARAM_INT);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return intval($row["total"] ?? 0);
	}

	static public function mdlEsUltimoPerfilConPermiso($idPerfil, $codigoPermiso)
	{
		$sql = "SELECT COUNT(DISTINCT p.id) AS total
				FROM perfiles p
				INNER JOIN perfil_permisos pp ON pp.id_perfil = p.id
				INNER JOIN permisos pe ON pe.id = pp.id_permiso
				WHERE p.activo = 1 AND p.estado = 1 AND p.id <> :id
				  AND pe.codigo = :codigo";
		$stmt = Conexion::conectar()->prepare($sql);
		$stmt->bindParam(":id", $idPerfil, PDO::PARAM_INT);
		$stmt->bindParam(":codigo", $codigoPermiso, PDO::PARAM_STR);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return intval($row["total"] ?? 0) === 0;
	}

	static public function mdlMostrarPermisosAgrupados()
	{
		$stmt = Conexion::conectar()->prepare(
			"SELECT * FROM permisos WHERE estado = 1 ORDER BY orden ASC, id ASC"
		);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	static public function mdlPermisosPorPerfil($idPerfil)
	{
		$stmt = Conexion::conectar()->prepare(
			"SELECT pe.*
			 FROM perfil_permisos pp
			 INNER JOIN permisos pe ON pe.id = pp.id_permiso
			 WHERE pp.id_perfil = :id AND pe.estado = 1
			 ORDER BY pe.orden ASC"
		);
		$stmt->bindParam(":id", $idPerfil, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	static public function mdlIdsPermisosPerfil($idPerfil)
	{
		$stmt = Conexion::conectar()->prepare(
			"SELECT id_permiso FROM perfil_permisos WHERE id_perfil = :id"
		);
		$stmt->bindParam(":id", $idPerfil, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_COLUMN);
	}

	static public function mdlGuardarPermisosPerfil($idPerfil, $idsPermisos)
	{
		$conexion = Conexion::conectar();
		try {
			$conexion->beginTransaction();
			$del = $conexion->prepare("DELETE FROM perfil_permisos WHERE id_perfil = :id");
			$del->bindParam(":id", $idPerfil, PDO::PARAM_INT);
			$del->execute();

			if (!empty($idsPermisos)) {
				$ins = $conexion->prepare(
					"INSERT INTO perfil_permisos(id_perfil, id_permiso) VALUES (:id_perfil, :id_permiso)"
				);
				foreach ($idsPermisos as $idPermiso) {
					$idPermiso = intval($idPermiso);
					if ($idPermiso <= 0) {
						continue;
					}
					$ins->bindValue(":id_perfil", $idPerfil, PDO::PARAM_INT);
					$ins->bindValue(":id_permiso", $idPermiso, PDO::PARAM_INT);
					$ins->execute();
				}
			}

			$conexion->commit();
			return "ok";
		} catch (Exception $e) {
			$conexion->rollBack();
			return "error";
		}
	}
}
