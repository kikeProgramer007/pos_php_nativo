<?php

require_once __DIR__ . "/../../../modelos/empresa.modelo.php";

function empresaPdfLogo()
{
	$fila = ModeloEmpresa::mdlActual();
	$logo = $fila ? trim((string) ($fila["logo"] ?? "")) : "";
	if ($logo === "" || !is_file(__DIR__ . "/" . $logo)) {
		return "images/logo-negro-bloque.jpg";
	}
	return $logo;
}

function empresaPdfTextos($nombre, $direccion)
{
	$fila = ModeloEmpresa::mdlActual();
	if (!$fila) {
		return array($nombre, $direccion);
	}
	$nombreFila = trim((string) ($fila["nombre"] ?? ""));
	if ($nombreFila !== "") {
		$nombre = $nombreFila;
	}
	$direccion = (string) ($fila["direccion"] ?? "");
	return array($nombre, $direccion);
}
