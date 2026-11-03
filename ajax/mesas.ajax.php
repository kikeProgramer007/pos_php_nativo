<?php

require_once "../includes/sesion-permisos.php";
require_once "../controladores/mesas.controlador.php";
require_once "../modelos/mesas.modelo.php";

if (isset($_POST["listarMesasSelector"])) {
	if (!Permisos::tiene("ventas.crear") && !Permisos::tiene("ventas.editar") && !Permisos::tiene("mesas.ver")) {
		echo json_encode(array("ok" => false, "mesas" => array()));
		return;
	}
	if (!class_exists("ModeloEmpresa")) {
		require_once "../modelos/empresa.modelo.php";
	}
	if (!ModeloEmpresa::mdlFuncionActiva("venta.gestion_mesas")) {
		echo json_encode(array("ok" => false, "mesas" => array(), "mensaje" => "Funcionalidad desactivada"));
		return;
	}
	$mesas = ModeloMesas::mdlListarParaSelector();
	echo json_encode(array("ok" => true, "mesas" => $mesas ?: array()));
	return;
}

if (isset($_POST["detalleAtencionMesa"])) {
	$puedeVer =
		Permisos::tiene("mesas.ver") ||
		Permisos::tiene("mesas.editar") ||
		ControladorMesas::puedeOperarMesaEnVenta();
	if (!$puedeVer) {
		echo json_encode(array("ok" => false, "mensaje" => "No autorizado"));
		return;
	}
	echo json_encode(ControladorMesas::ctrDetalleAtencion(intval($_POST["detalleAtencionMesa"])));
	return;
}

if (isset($_POST["idMesaLiberar"])) {
	ControladorMesas::ctrLiberarMesaAjax();
	return;
}

if (isset($_POST["liberarMesaDesdeSelector"])) {
	ControladorMesas::ctrLiberarMesaDesdeSelectorAjax();
	return;
}

if (isset($_POST["listarMesasDisponibles"])) {
	if (!ControladorMesas::puedeOperarMesaEnVenta() && !Permisos::tiene("mesas.ver") && !Permisos::tiene("mesas.editar")) {
		echo json_encode(array("ok" => false, "mesas" => array()));
		return;
	}
	ControladorMesas::ctrMesasDisponiblesAjax();
	return;
}

if (isset($_POST["idMesaOrigen"]) && isset($_POST["idMesaDestino"])) {
	ControladorMesas::ctrCambiarMesaAjax();
	return;
}

if (isset($_POST["idMesa"])) {
	if (!Permisos::tiene("mesas.ver") && !Permisos::tiene("mesas.editar")) {
		echo json_encode(null);
		return;
	}
	$mesa = ControladorMesas::ctrMostrarMesas("id", intval($_POST["idMesa"]));
	echo json_encode($mesa ?: null);
	return;
}

if (isset($_POST["validarNombreMesa"])) {
	$excepto = isset($_POST["exceptoId"]) ? intval($_POST["exceptoId"]) : 0;
	$existe = ModeloMesas::mdlExisteNombre($_POST["validarNombreMesa"], $excepto);
	echo json_encode($existe ? array("existe" => true) : null);
	return;
}

echo json_encode(null);
