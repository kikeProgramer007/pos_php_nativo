<?php

require_once "../controladores/clientes.controlador.php";
require_once "../modelos/clientes.modelo.php";

class AjaxClientes
{

	/*=============================================
	EDITAR CLIENTE
	=============================================*/

	public $idCliente;

	public function ajaxEditarCliente()
	{

		$item = "id";
		$valor = $this->idCliente;

		$respuesta = ControladorClientes::ctrMostrarClientes($item, $valor);

		echo json_encode($respuesta);
	}

	/*=============================================
VALIDAR NO REPETIR CLIENTE
=============================================*/

	public $validarCliente;

	public function ajaxValidarCliente()
	{
		$item = "cliente";
		$valor = $this->validarCliente;

		$respuesta = ControladorClientes::ctrMostrarClientes($item, $valor);

		echo json_encode($respuesta);
	}
}
/*=============================================
EDITAR CLIENTE
=============================================*/

if (isset($_POST["idCliente"])) {

	$cliente = new AjaxClientes();
	$cliente->idCliente = $_POST["idCliente"];
	$cliente->ajaxEditarCliente();
}



/*=============================================
VALIDAR NO REPETIR CLIENTE
=============================================*/

if (isset($_POST["validarCliente"])) {

	$valCliente = new AjaxClientes();
	$valCliente->validarCliente = $_POST["validarCliente"];
	$valCliente->ajaxValidarCliente();
}


if (isset($_GET["term"]) || isset($_GET["page"])) {

	header('Content-Type: application/json; charset=utf-8');

	$tabla = "clientes";
	$valor = isset($_GET["term"]) ? trim((string) $_GET["term"]) : "";
	$esSelect2 = isset($_GET["page"]);

	// Select2: lista paginada (term vacío = primeras páginas)
	if ($esSelect2) {
		$page = isset($_GET["page"]) ? (int) $_GET["page"] : 1;
		$perPage = 20;
		$busqueda = ModeloClientes::mdlBuscarClientes($tabla, $valor, $page, $perPage);
		$results = array();
		if (!empty($busqueda["items"])) {
			foreach ($busqueda["items"] as $row) {
				$results[] = array(
					"id" => $row["id"],
					"text" => $row["nombre"]
				);
			}
		}
		echo json_encode(array(
			"results" => $results,
			"pagination" => array(
				"more" => !empty($busqueda["more"])
			)
		));
		exit;
	}

	// Autocomplete legado (crear-venta): array plano, mín. 2 letras
	$returnData = array();
	if (mb_strlen($valor) >= 2) {
		$busqueda = ModeloClientes::mdlBuscarClientes($tabla, $valor, 1, 40);
		if (!empty($busqueda["items"])) {
			foreach ($busqueda["items"] as $row) {
				$returnData[] = array(
					"id" => $row["id"],
					"value" => $row["nombre"],
					"text" => $row["nombre"]
				);
			}
		}
	}

	echo json_encode($returnData);
	exit;
}
