<?php

require_once "../includes/sesion-permisos.php";
require_once "../controladores/producto_presentaciones.controlador.php";
require_once "../modelos/producto_presentaciones.modelo.php";

header("Content-Type: application/json; charset=utf-8");

if (!isset($_SESSION["iniciarSesion"]) || $_SESSION["iniciarSesion"] !== "ok") {
	echo json_encode(["status" => "error", "mensaje" => "No autenticado"]);
	exit;
}

$accion = $_POST["accion"] ?? $_GET["accion"] ?? "";

switch ($accion) {
	case "listar":
		$idProducto = intval($_POST["id_producto"] ?? $_GET["id_producto"] ?? 0);
		$soloActivos = isset($_REQUEST["solo_activos"]) && $_REQUEST["solo_activos"] == "1";
		echo json_encode([
			"status" => "ok",
			"data" => ControladorProductoPresentaciones::ctrListarPorProducto($idProducto, $soloActivos)
		]);
		break;

	case "guardar":
		if (!Permisos::tiene("productos.editar") && !Permisos::tiene("productos.crear")) {
			echo json_encode(["status" => "error", "mensaje" => "Sin permiso"]);
			exit;
		}
		echo json_encode(ControladorProductoPresentaciones::ctrGuardarDesdeAjax());
		break;

	case "desactivar":
		if (!Permisos::tiene("productos.editar")) {
			echo json_encode(["status" => "error", "mensaje" => "Sin permiso"]);
			exit;
		}
		echo json_encode(ControladorProductoPresentaciones::ctrDesactivarDesdeAjax());
		break;

	default:
		echo json_encode(["status" => "error", "mensaje" => "Acción no válida"]);
}
