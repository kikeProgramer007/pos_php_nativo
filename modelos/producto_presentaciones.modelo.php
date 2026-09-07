<?php

require_once __DIR__ . "/conexion.php";

class ModeloProductoPresentaciones
{

	/*=============================================
	LISTAR POR PRODUCTO
	=============================================*/
	static public function mdlListarPorProducto($idProducto, $soloActivos = false)
	{
		try {
			$sql = "SELECT id, id_producto, nombre, cantidad_unidades, orden, estado, fecha
					FROM producto_presentaciones
					WHERE id_producto = :id_producto";
			if ($soloActivos) {
				$sql .= " AND estado = 1";
			}
			$sql .= " ORDER BY orden ASC, id ASC";

			$stmt = Conexion::conectar()->prepare($sql);
			$stmt->bindParam(":id_producto", $idProducto, PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (Exception $e) {
			error_log("mdlListarPorProducto: " . $e->getMessage());
			return [];
		} finally {
			if (isset($stmt)) {
				$stmt = null;
			}
		}
	}

	/*=============================================
	MOSTRAR POR ID
	=============================================*/
	static public function mdlMostrarPorId($id)
	{
		try {
			$stmt = Conexion::conectar()->prepare(
				"SELECT id, id_producto, nombre, cantidad_unidades, orden, estado, fecha
				 FROM producto_presentaciones WHERE id = :id LIMIT 1"
			);
			$stmt->bindParam(":id", $id, PDO::PARAM_INT);
			$stmt->execute();
			$fila = $stmt->fetch(PDO::FETCH_ASSOC);
			return $fila ? $fila : null;
		} catch (Exception $e) {
			error_log("mdlMostrarPorId presentacion: " . $e->getMessage());
			return null;
		} finally {
			if (isset($stmt)) {
				$stmt = null;
			}
		}
	}

	/*=============================================
	CREAR
	=============================================*/
	static public function mdlCrear($datos)
	{
		try {
			$conexion = Conexion::conectar();
			$stmt = $conexion->prepare(
				"INSERT INTO producto_presentaciones(id_producto, nombre, cantidad_unidades, orden, estado)
				 VALUES (:id_producto, :nombre, :cantidad_unidades, :orden, :estado)"
			);
			$stmt->bindParam(":id_producto", $datos["id_producto"], PDO::PARAM_INT);
			$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
			$stmt->bindParam(":cantidad_unidades", $datos["cantidad_unidades"], PDO::PARAM_INT);
			$stmt->bindParam(":orden", $datos["orden"], PDO::PARAM_INT);
			$stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_INT);

			if ($stmt->execute()) {
				return ["status" => "ok", "id" => $conexion->lastInsertId()];
			}
			return ["status" => "error", "mensaje" => "No se pudo crear la presentación"];
		} catch (Exception $e) {
			error_log("mdlCrear presentacion: " . $e->getMessage());
			return ["status" => "error", "mensaje" => $e->getMessage()];
		} finally {
			if (isset($stmt)) {
				$stmt = null;
			}
		}
	}

	/*=============================================
	EDITAR
	=============================================*/
	static public function mdlEditar($datos)
	{
		try {
			$stmt = Conexion::conectar()->prepare(
				"UPDATE producto_presentaciones
				 SET nombre = :nombre,
				     cantidad_unidades = :cantidad_unidades,
				     orden = :orden,
				     estado = :estado
				 WHERE id = :id AND id_producto = :id_producto"
			);
			$stmt->bindParam(":id", $datos["id"], PDO::PARAM_INT);
			$stmt->bindParam(":id_producto", $datos["id_producto"], PDO::PARAM_INT);
			$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
			$stmt->bindParam(":cantidad_unidades", $datos["cantidad_unidades"], PDO::PARAM_INT);
			$stmt->bindParam(":orden", $datos["orden"], PDO::PARAM_INT);
			$stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_INT);

			return $stmt->execute()
				? ["status" => "ok"]
				: ["status" => "error", "mensaje" => "No se pudo actualizar"];
		} catch (Exception $e) {
			error_log("mdlEditar presentacion: " . $e->getMessage());
			return ["status" => "error", "mensaje" => $e->getMessage()];
		} finally {
			if (isset($stmt)) {
				$stmt = null;
			}
		}
	}

	/*=============================================
	DESACTIVAR (soft delete)
	=============================================*/
	static public function mdlDesactivar($id, $idProducto)
	{
		try {
			$stmt = Conexion::conectar()->prepare(
				"UPDATE producto_presentaciones SET estado = 0
				 WHERE id = :id AND id_producto = :id_producto"
			);
			$stmt->bindParam(":id", $id, PDO::PARAM_INT);
			$stmt->bindParam(":id_producto", $idProducto, PDO::PARAM_INT);
			return $stmt->execute()
				? ["status" => "ok"]
				: ["status" => "error", "mensaje" => "No se pudo desactivar"];
		} catch (Exception $e) {
			error_log("mdlDesactivar presentacion: " . $e->getMessage());
			return ["status" => "error", "mensaje" => $e->getMessage()];
		} finally {
			if (isset($stmt)) {
				$stmt = null;
			}
		}
	}
}
