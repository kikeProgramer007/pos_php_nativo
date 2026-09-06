<?php

class ControladorProductoPresentaciones
{

	static public function ctrListarPorProducto($idProducto, $soloActivos = false)
	{
		return ModeloProductoPresentaciones::mdlListarPorProducto(intval($idProducto), $soloActivos);
	}

	static public function ctrGuardarDesdeAjax()
	{
		$idProducto = intval($_POST["id_producto"] ?? 0);
		$id = intval($_POST["id"] ?? 0);
		$nombre = trim(strtoupper($_POST["nombre"] ?? ""));
		$cantidad = intval($_POST["cantidad_unidades"] ?? 0);
		$orden = intval($_POST["orden"] ?? 0);
		$estado = isset($_POST["estado"]) ? (intval($_POST["estado"]) === 1 ? 1 : 0) : 1;

		if ($idProducto <= 0) {
			return ["status" => "error", "mensaje" => "Producto no válido"];
		}
		if ($nombre === "") {
			return ["status" => "error", "mensaje" => "Ingrese el nombre de la presentación"];
		}
		if ($cantidad < 1) {
			return ["status" => "error", "mensaje" => "La cantidad de unidades debe ser al menos 1"];
		}
		// Evitar "Unidad" redundante (ya es implícita)
		if ($cantidad === 1 && (stripos($nombre, "UNIDAD") !== false || $nombre === "UND" || $nombre === "U")) {
			return ["status" => "error", "mensaje" => "La presentación Unidad (1) ya existe de forma automática. No es necesario registrarla."];
		}

		$datos = [
			"id" => $id,
			"id_producto" => $idProducto,
			"nombre" => $nombre,
			"cantidad_unidades" => $cantidad,
			"orden" => $orden,
			"estado" => $estado
		];

		if ($id > 0) {
			return ModeloProductoPresentaciones::mdlEditar($datos);
		}
		return ModeloProductoPresentaciones::mdlCrear($datos);
	}

	static public function ctrDesactivarDesdeAjax()
	{
		$id = intval($_POST["id"] ?? 0);
		$idProducto = intval($_POST["id_producto"] ?? 0);
		if ($id <= 0 || $idProducto <= 0) {
			return ["status" => "error", "mensaje" => "Datos incompletos"];
		}
		return ModeloProductoPresentaciones::mdlDesactivar($id, $idProducto);
	}
}
