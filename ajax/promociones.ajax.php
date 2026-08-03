<?php

require_once "../controladores/promociones.controlador.php";
require_once "../modelos/promociones.modelo.php";
require_once "../controladores/productos.controlador.php";
require_once "../modelos/productos.modelo.php";
require_once "../controladores/categorias.controlador.php";
require_once "../modelos/categorias.modelo.php";

class AjaxPromociones
{
	public function procesar()
	{
		$accion = $_POST["accion"] ?? ($_GET["accion"] ?? "");

		switch ($accion) {
			case "cambiarEstado":
				echo json_encode(ControladorPromociones::ctrCambiarEstado($_POST["idPromocion"] ?? 0, $_POST["estado"] ?? 0));
				break;

			case "eliminarPromocion":
				echo json_encode(ControladorPromociones::ctrEliminarPromocion($_POST["idPromocion"] ?? 0));
				break;

			case "vincularProductos":
				$ids = json_decode($_POST["idsProductos"] ?? "[]", true);
				echo json_encode(ControladorPromociones::ctrVincularProductos($_POST["idPromocion"] ?? 0, $ids));
				break;

			case "quitarProducto":
				echo json_encode(ControladorPromociones::ctrQuitarProducto($_POST["idVinculo"] ?? 0));
				break;

			case "guardarIntervalo":
				echo json_encode(ControladorPromociones::ctrGuardarIntervalo($_POST));
				break;

			case "eliminarIntervalo":
				echo json_encode(ControladorPromociones::ctrEliminarIntervalo($_POST["idIntervalo"] ?? 0));
				break;

			case "listarIntervalos":
				$id = intval($_GET["idPromocion"] ?? ($_POST["idPromocion"] ?? 0));
				echo json_encode(["status" => "ok", "data" => ControladorPromociones::ctrMostrarIntervalos($id)]);
				break;

			case "listarProductosPromocion":
				$id = intval($_GET["idPromocion"] ?? ($_POST["idPromocion"] ?? 0));
				echo json_encode(["status" => "ok", "data" => ControladorPromociones::ctrMostrarProductosPromocion($id)]);
				break;

			case "buscarProductos":
				$this->buscarProductos();
				break;

			case "calcularPromociones":
				$items = json_decode($_POST["items"] ?? "[]", true);
				$resultado = ControladorPromociones::ctrCalcularPromocionesParaVenta($items);
				echo json_encode(["status" => "ok", "data" => $resultado]);
				break;

			case "obtenerPromocion":
				$id = intval($_GET["idPromocion"] ?? 0);
				$promo = ControladorPromociones::ctrMostrarPromociones("id", $id);
				echo json_encode(["status" => $promo ? "ok" : "error", "data" => $promo]);
				break;

			default:
				echo json_encode(["status" => "error", "mensaje" => "Acción no válida"]);
		}
	}

	private function buscarProductos()
	{
		$term = trim($_GET["term"] ?? ($_POST["term"] ?? ""));
		$codigo = trim($_GET["codigo"] ?? ($_POST["codigo"] ?? ""));
		$idCategoria = intval($_GET["idCategoria"] ?? ($_POST["idCategoria"] ?? 0));
		$idPromocion = intval($_GET["idPromocion"] ?? ($_POST["idPromocion"] ?? 0));

		$productos = ControladorProductos::ctrMostrarProductos(null, null, "id");
		$vinculados = [];
		if ($idPromocion > 0) {
			foreach (ControladorPromociones::ctrMostrarProductosPromocion($idPromocion) as $v) {
				$vinculados[intval($v["id_producto"])] = true;
			}
		}

		$data = [];
		foreach ($productos as $p) {
			if (intval($p["estado"]) !== 1) continue;
			if ($idCategoria > 0 && intval($p["id_categoria"]) !== $idCategoria) continue;
			if ($codigo !== "" && stripos($p["codigo"], $codigo) === false) continue;
			if ($term !== "" && stripos($p["descripcion"], $term) === false && stripos($p["codigo"], $term) === false) continue;
			if (isset($vinculados[intval($p["id"])])) continue;

			$data[] = [
				"id" => $p["id"],
				"codigo" => $p["codigo"],
				"descripcion" => $p["descripcion"],
				"precio_venta" => $p["precio_venta"],
				"imagen" => $p["imagen"],
				"estado" => $p["estado"]
			];
			if (count($data) >= 100) break;
		}

		echo json_encode(["status" => "ok", "data" => $data]);
	}
}

$ajax = new AjaxPromociones();
$ajax->procesar();
