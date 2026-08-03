<?php

require_once "conexion.php";

class ModeloPromociones
{
	/*=============================================
	MOSTRAR PROMOCIONES
	=============================================*/
	static public function mdlMostrarPromociones($item = null, $valor = null, $filtros = [])
	{
		date_default_timezone_set('America/La_Paz');
		$ahora = date('Y-m-d H:i:s');

		$query = "SELECT p.*,
			(SELECT COUNT(*) FROM promocion_productos pp WHERE pp.id_promocion = p.id AND pp.estado = 1) AS cant_productos,
			(SELECT COUNT(*) FROM promocion_intervalos pi WHERE pi.id_promocion = p.id AND pi.estado = 1) AS cant_intervalos,
			CASE
				WHEN p.estado = 0 THEN 'deshabilitada'
				WHEN p.fecha_inicio > :ahora1 THEN 'programada'
				WHEN p.fecha_fin < :ahora2 THEN 'vencida'
				ELSE 'habilitada'
			END AS estado_calculado
			FROM promociones p
			WHERE 1=1";

		$params = [
			":ahora1" => $ahora,
			":ahora2" => $ahora
		];

		if ($item !== null) {
			$query .= " AND p.$item = :valor";
			$params[":valor"] = $valor;
		}

		if (!empty($filtros["nombre"])) {
			$query .= " AND p.nombre LIKE :nombre";
			$params[":nombre"] = "%" . $filtros["nombre"] . "%";
		}

		if (isset($filtros["estado"]) && $filtros["estado"] !== "" && $filtros["estado"] !== "todos") {
			switch ($filtros["estado"]) {
				case "habilitada":
					$query .= " AND p.estado = 1 AND p.fecha_inicio <= :ahora3 AND p.fecha_fin >= :ahora4";
					$params[":ahora3"] = $ahora;
					$params[":ahora4"] = $ahora;
					break;
				case "deshabilitada":
					$query .= " AND p.estado = 0";
					break;
				case "vencida":
					$query .= " AND p.estado = 1 AND p.fecha_fin < :ahora5";
					$params[":ahora5"] = $ahora;
					break;
				case "programada":
					$query .= " AND p.estado = 1 AND p.fecha_inicio > :ahora6";
					$params[":ahora6"] = $ahora;
					break;
			}
		}

		if (!empty($filtros["vigencia"])) {
			if ($filtros["vigencia"] === "vigentes") {
				$query .= " AND p.fecha_inicio <= :vig1 AND p.fecha_fin >= :vig2";
				$params[":vig1"] = $ahora;
				$params[":vig2"] = $ahora;
			} elseif ($filtros["vigencia"] === "no_vigentes") {
				$query .= " AND (p.fecha_inicio > :vig3 OR p.fecha_fin < :vig4)";
				$params[":vig3"] = $ahora;
				$params[":vig4"] = $ahora;
			}
		}

		$query .= " ORDER BY p.prioridad DESC, p.id DESC";

		$stmt = Conexion::conectar()->prepare($query);
		foreach ($params as $key => $val) {
			$stmt->bindValue($key, $val);
		}
		$stmt->execute();

		if ($item !== null) {
			return $stmt->fetch(PDO::FETCH_ASSOC);
		}
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/*=============================================
	CREAR PROMOCIÓN
	=============================================*/
	static public function mdlCrearPromocion($datos)
	{
		$stmt = Conexion::conectar()->prepare(
			"INSERT INTO promociones(nombre, descripcion, fecha_inicio, fecha_fin, prioridad, estado, modo_cantidad, observacion)
			 VALUES (:nombre, :descripcion, :fecha_inicio, :fecha_fin, :prioridad, :estado, :modo_cantidad, :observacion)"
		);
		$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
		$stmt->bindParam(":fecha_inicio", $datos["fecha_inicio"], PDO::PARAM_STR);
		$stmt->bindParam(":fecha_fin", $datos["fecha_fin"], PDO::PARAM_STR);
		$stmt->bindParam(":prioridad", $datos["prioridad"], PDO::PARAM_INT);
		$stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_INT);
		$stmt->bindParam(":modo_cantidad", $datos["modo_cantidad"], PDO::PARAM_STR);
		$stmt->bindParam(":observacion", $datos["observacion"], PDO::PARAM_STR);

		if ($stmt->execute()) {
			return Conexion::conectar()->lastInsertId() ?: true;
		}
		return "error";
	}

	static public function mdlCrearPromocionTx($conexion, $datos)
	{
		$stmt = $conexion->prepare(
			"INSERT INTO promociones(nombre, descripcion, fecha_inicio, fecha_fin, prioridad, estado, modo_cantidad, observacion)
			 VALUES (:nombre, :descripcion, :fecha_inicio, :fecha_fin, :prioridad, :estado, :modo_cantidad, :observacion)"
		);
		$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
		$stmt->bindParam(":fecha_inicio", $datos["fecha_inicio"], PDO::PARAM_STR);
		$stmt->bindParam(":fecha_fin", $datos["fecha_fin"], PDO::PARAM_STR);
		$stmt->bindParam(":prioridad", $datos["prioridad"], PDO::PARAM_INT);
		$stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_INT);
		$stmt->bindParam(":modo_cantidad", $datos["modo_cantidad"], PDO::PARAM_STR);
		$stmt->bindParam(":observacion", $datos["observacion"], PDO::PARAM_STR);
		if ($stmt->execute()) {
			return $conexion->lastInsertId();
		}
		return false;
	}

	/*=============================================
	EDITAR PROMOCIÓN
	=============================================*/
	static public function mdlEditarPromocion($datos)
	{
		$stmt = Conexion::conectar()->prepare(
			"UPDATE promociones SET
				nombre = :nombre,
				descripcion = :descripcion,
				fecha_inicio = :fecha_inicio,
				fecha_fin = :fecha_fin,
				prioridad = :prioridad,
				estado = :estado,
				modo_cantidad = :modo_cantidad,
				observacion = :observacion
			 WHERE id = :id"
		);
		$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
		$stmt->bindParam(":fecha_inicio", $datos["fecha_inicio"], PDO::PARAM_STR);
		$stmt->bindParam(":fecha_fin", $datos["fecha_fin"], PDO::PARAM_STR);
		$stmt->bindParam(":prioridad", $datos["prioridad"], PDO::PARAM_INT);
		$stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_INT);
		$stmt->bindParam(":modo_cantidad", $datos["modo_cantidad"], PDO::PARAM_STR);
		$stmt->bindParam(":observacion", $datos["observacion"], PDO::PARAM_STR);
		$stmt->bindParam(":id", $datos["id"], PDO::PARAM_INT);
		return $stmt->execute() ? "ok" : "error";
	}

	static public function mdlCambiarEstado($id, $estado)
	{
		$stmt = Conexion::conectar()->prepare("UPDATE promociones SET estado = :estado WHERE id = :id");
		$stmt->bindParam(":estado", $estado, PDO::PARAM_INT);
		$stmt->bindParam(":id", $id, PDO::PARAM_INT);
		return $stmt->execute() ? "ok" : "error";
	}

	static public function mdlEliminarPromocion($id)
	{
		// Soft delete: deshabilitar
		return self::mdlCambiarEstado($id, 0);
	}

	/*=============================================
	PRODUCTOS VINCULADOS
	=============================================*/
	static public function mdlMostrarProductosPromocion($idPromocion)
	{
		$stmt = Conexion::conectar()->prepare(
			"SELECT pp.id, pp.id_promocion, pp.id_producto, pp.estado,
					p.codigo, p.descripcion, p.imagen, p.precio_venta, p.estado AS estado_producto
			 FROM promocion_productos pp
			 JOIN productos p ON p.id = pp.id_producto
			 WHERE pp.id_promocion = :id_promocion AND pp.estado = 1
			 ORDER BY p.descripcion ASC"
		);
		$stmt->bindParam(":id_promocion", $idPromocion, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	static public function mdlVincularProductos($idPromocion, $idsProductos)
	{
		$conexion = Conexion::conectar();
		try {
			$conexion->beginTransaction();
			$stmt = $conexion->prepare(
				"INSERT INTO promocion_productos(id_promocion, id_producto, estado)
				 VALUES (:id_promocion, :id_producto, 1)
				 ON DUPLICATE KEY UPDATE estado = 1"
			);
			foreach ($idsProductos as $idProducto) {
				$stmt->bindValue(":id_promocion", $idPromocion, PDO::PARAM_INT);
				$stmt->bindValue(":id_producto", intval($idProducto), PDO::PARAM_INT);
				if (!$stmt->execute()) {
					throw new Exception("Error al vincular producto");
				}
			}
			$conexion->commit();
			return "ok";
		} catch (Exception $e) {
			$conexion->rollBack();
			return "error";
		}
	}

	static public function mdlQuitarProductoPromocion($idVinculo)
	{
		$stmt = Conexion::conectar()->prepare("UPDATE promocion_productos SET estado = 0 WHERE id = :id");
		$stmt->bindParam(":id", $idVinculo, PDO::PARAM_INT);
		return $stmt->execute() ? "ok" : "error";
	}

	/*=============================================
	INTERVALOS
	=============================================*/
	static public function mdlMostrarIntervalos($idPromocion)
	{
		$stmt = Conexion::conectar()->prepare(
			"SELECT * FROM promocion_intervalos
			 WHERE id_promocion = :id_promocion AND estado = 1
			 ORDER BY cantidad_minima ASC"
		);
		$stmt->bindParam(":id_promocion", $idPromocion, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	static public function mdlGuardarIntervalo($datos)
	{
		if (!empty($datos["id"])) {
			$stmt = Conexion::conectar()->prepare(
				"UPDATE promocion_intervalos SET
					cantidad_minima = :cantidad_minima,
					cantidad_maxima = :cantidad_maxima,
					tipo_descuento = :tipo_descuento,
					valor_descuento = :valor_descuento,
					estado = 1
				 WHERE id = :id AND id_promocion = :id_promocion"
			);
			$stmt->bindParam(":id", $datos["id"], PDO::PARAM_INT);
		} else {
			$stmt = Conexion::conectar()->prepare(
				"INSERT INTO promocion_intervalos(id_promocion, cantidad_minima, cantidad_maxima, tipo_descuento, valor_descuento, estado)
				 VALUES (:id_promocion, :cantidad_minima, :cantidad_maxima, :tipo_descuento, :valor_descuento, 1)"
			);
		}
		$stmt->bindParam(":id_promocion", $datos["id_promocion"], PDO::PARAM_INT);
		$stmt->bindParam(":cantidad_minima", $datos["cantidad_minima"], PDO::PARAM_INT);
		$max = $datos["cantidad_maxima"];
		if ($max === null || $max === "" || $max === 0) {
			$stmt->bindValue(":cantidad_maxima", null, PDO::PARAM_NULL);
		} else {
			$stmt->bindValue(":cantidad_maxima", intval($max), PDO::PARAM_INT);
		}
		$stmt->bindParam(":tipo_descuento", $datos["tipo_descuento"], PDO::PARAM_STR);
		$stmt->bindParam(":valor_descuento", $datos["valor_descuento"], PDO::PARAM_STR);
		return $stmt->execute() ? "ok" : "error";
	}

	static public function mdlEliminarIntervalo($id)
	{
		$stmt = Conexion::conectar()->prepare("UPDATE promocion_intervalos SET estado = 0 WHERE id = :id");
		$stmt->bindParam(":id", $id, PDO::PARAM_INT);
		return $stmt->execute() ? "ok" : "error";
	}

	/*=============================================
	PROMOCIONES VIGENTES PARA PRODUCTOS (venta)
	Carga agrupada para evitar N+1
	=============================================*/
	static public function mdlObtenerPromocionesVigentesPorProductos($idsProductos)
	{
		if (empty($idsProductos)) {
			return [];
		}

		date_default_timezone_set('America/La_Paz');
		$ahora = date('Y-m-d H:i:s');
		$ids = array_map('intval', $idsProductos);
		$placeholders = implode(',', array_fill(0, count($ids), '?'));

		$sql = "SELECT
					pp.id_producto,
					p.id AS id_promocion,
					p.nombre AS nombre_promocion,
					p.prioridad,
					p.fecha AS fecha_creacion,
					p.modo_cantidad,
					pi.id AS id_intervalo,
					pi.cantidad_minima,
					pi.cantidad_maxima,
					pi.tipo_descuento,
					pi.valor_descuento
				FROM promocion_productos pp
				INNER JOIN promociones p ON p.id = pp.id_promocion
				INNER JOIN promocion_intervalos pi ON pi.id_promocion = p.id AND pi.estado = 1
				WHERE pp.id_producto IN ($placeholders)
				  AND pp.estado = 1
				  AND p.estado = 1
				  AND p.fecha_inicio <= ?
				  AND p.fecha_fin >= ?
				ORDER BY p.prioridad DESC, p.id DESC, pi.cantidad_minima ASC";

		$stmt = Conexion::conectar()->prepare($sql);
		$i = 1;
		foreach ($ids as $id) {
			$stmt->bindValue($i++, $id, PDO::PARAM_INT);
		}
		$stmt->bindValue($i++, $ahora, PDO::PARAM_STR);
		$stmt->bindValue($i++, $ahora, PDO::PARAM_STR);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
}
