<?php

require_once __DIR__ . "/conexion.php";

class ModeloEmpresa
{
	static public function mdlNombre($defecto = "El Gato Rico")
	{
		$fila = self::mdlActual();
		$nombre = $fila ? trim((string) ($fila["nombre"] ?? "")) : "";
		return $nombre !== "" ? $nombre : $defecto;
	}

	static public function mdlActual()
	{
		try {
			$stmt = Conexion::conectar()->query("SELECT * FROM empresa ORDER BY id ASC LIMIT 1");
			$fila = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;
			return $fila ?: null;
		} catch (Exception $e) {
			return null;
		}
	}

	static public function mdlNotaTicket()
	{
		$defecto = "¡GRACIAS POR SU COMPRA!\nPEDIDOS AL 79902345";
		$fila = self::mdlActual();
		$nota = $fila ? trim((string) ($fila["nota_ticket"] ?? "")) : "";
		$nota = str_replace(array("\r\n", "\r"), "\n", $nota);
		return $nota !== "" ? $nota : $defecto;
	}

	static public function mdlNotaTicketHtml()
	{
		$seguro = htmlspecialchars(self::mdlNotaTicket(), ENT_QUOTES, "UTF-8");
		return nl2br($seguro, false);
	}

	static public function mdlLogoDashboard()
	{
		$defecto = "vistas/img/plantilla/Logo_POS.webp";
		$fila = self::mdlActual();
		$logo = $fila ? trim((string) ($fila["logo_dashboard"] ?? "")) : "";
		if ($logo === "" || !is_file(__DIR__ . "/../" . $logo)) {
			return $defecto;
		}
		return $logo;
	}

	static public function mdlGuardar($datos)
	{
		$actual = self::mdlActual();
		if (!$actual) {
			return "error";
		}

		$columnas = self::columnas();
		$set = array("nombre = :nombre", "direccion = :direccion", "logo = :logo");
		if (isset($columnas["nota_ticket"])) {
			$set[] = "nota_ticket = :nota_ticket";
		}
		if (isset($columnas["logo_dashboard"])) {
			$set[] = "logo_dashboard = :logo_dashboard";
		}

		$stmt = Conexion::conectar()->prepare(
			"UPDATE empresa SET " . implode(", ", $set) . " WHERE id = :id"
		);
		$stmt->bindValue(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt->bindValue(":direccion", $datos["direccion"], PDO::PARAM_STR);
		$stmt->bindValue(":logo", $datos["logo"], PDO::PARAM_STR);
		$stmt->bindValue(":id", intval($actual["id"]), PDO::PARAM_INT);
		if (isset($columnas["nota_ticket"])) {
			$stmt->bindValue(":nota_ticket", $datos["nota_ticket"], PDO::PARAM_STR);
		}
		if (isset($columnas["logo_dashboard"])) {
			$stmt->bindValue(":logo_dashboard", $datos["logo_dashboard"], PDO::PARAM_STR);
		}
		if (!$stmt->execute()) {
			return "error";
		}
		if (!isset($columnas["nota_ticket"]) || !isset($columnas["logo_dashboard"])) {
			return "columnas";
		}
		return "ok";
	}

	static private function columnas()
	{
		static $columnas = null;
		if ($columnas !== null) {
			return $columnas;
		}
		$columnas = array();
		try {
			$stmt = Conexion::conectar()->query("SHOW COLUMNS FROM empresa");
			foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $fila) {
				$columnas[$fila["Field"]] = true;
			}
		} catch (Exception $e) {
			$columnas = array();
		}
		return $columnas;
	}

	static public function mdlFuncionalidades()
	{
		try {
			$empresa = self::mdlActual();
			if (!$empresa) {
				return array();
			}
			$stmt = Conexion::conectar()->prepare(
				"SELECT f.id, f.codigo, f.nombre, f.descripcion,
				        COALESCE(ef.activo, 0) AS activo, ef.id AS id_enlace
				 FROM funcionalidades f
				 LEFT JOIN empresa_funcionalidad ef
				   ON ef.id_funcionalidad = f.id AND ef.id_empresa = :id_empresa
				 WHERE f.estado = 1
				 ORDER BY f.nombre ASC"
			);
			$stmt->bindValue(":id_empresa", intval($empresa["id"]), PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: array();
		} catch (Exception $e) {
			return array();
		}
	}

	static public function mdlGuardarFuncionalidades($activas)
	{
		$empresa = self::mdlActual();
		if (!$empresa) {
			return "error";
		}
		$idEmpresa = intval($empresa["id"]);
		$activas = array_map("intval", (array) $activas);
		$lista = self::mdlFuncionalidades();
		$db = Conexion::conectar();

		foreach ($lista as $funcion) {
			$activo = in_array(intval($funcion["id"]), $activas, true) ? 1 : 0;
			if (!empty($funcion["id_enlace"])) {
				$stmt = $db->prepare("UPDATE empresa_funcionalidad SET activo = :activo WHERE id = :id AND id_empresa = :id_empresa");
				$stmt->bindValue(":activo", $activo, PDO::PARAM_INT);
				$stmt->bindValue(":id", intval($funcion["id_enlace"]), PDO::PARAM_INT);
				$stmt->bindValue(":id_empresa", $idEmpresa, PDO::PARAM_INT);
				$stmt->execute();
			} else {
				$stmt = $db->prepare(
					"INSERT INTO empresa_funcionalidad (id_empresa, id_funcionalidad, activo)
					 VALUES (:id_empresa, :id_funcionalidad, :activo)"
				);
				$stmt->bindValue(":id_empresa", $idEmpresa, PDO::PARAM_INT);
				$stmt->bindValue(":id_funcionalidad", intval($funcion["id"]), PDO::PARAM_INT);
				$stmt->bindValue(":activo", $activo, PDO::PARAM_INT);
				$stmt->execute();
			}
		}
		return "ok";
	}

	static public function mdlFuncionActiva($codigo)
	{
		try {
			$stmt = Conexion::conectar()->prepare(
				"SELECT ef.activo
				 FROM empresa e
				 INNER JOIN empresa_funcionalidad ef ON ef.id_empresa = e.id
				 INNER JOIN funcionalidades f ON f.id = ef.id_funcionalidad
				 WHERE f.codigo = :codigo AND f.estado = 1
				 ORDER BY e.id ASC
				 LIMIT 1"
			);
			$stmt->bindValue(":codigo", $codigo, PDO::PARAM_STR);
			$stmt->execute();
			$fila = $stmt->fetch(PDO::FETCH_ASSOC);
			return $fila && intval($fila["activo"]) === 1;
		} catch (Exception $e) {
			return false;
		}
	}
}
