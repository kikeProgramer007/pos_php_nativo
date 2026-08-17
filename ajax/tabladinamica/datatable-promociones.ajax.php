<?php

require_once "../../includes/sesion-permisos.php";
require_once "../../controladores/promociones.controlador.php";
require_once "../../modelos/promociones.modelo.php";

class TablaPromociones
{
	public function mostrarTabla()
	{
		$filtros = [
			"nombre" => $_GET["nombre"] ?? "",
			"estado" => $_GET["estado"] ?? "todos",
			"vigencia" => $_GET["vigencia"] ?? ""
		];

		$promociones = ControladorPromociones::ctrMostrarPromociones(null, null, $filtros);
		if (!$promociones || count($promociones) === 0) {
			echo json_encode(["data" => []]);
			return;
		}

		$datos = [];
		foreach ($promociones as $i => $p) {
			$estadoCalc = $p["estado_calculado"] ?? "deshabilitada";
			$badgeClass = [
				"habilitada" => "label-success",
				"deshabilitada" => "label-default",
				"vencida" => "label-danger",
				"programada" => "label-info"
			][$estadoCalc] ?? "label-default";

			$tipoResumen = "—";
			$intervalos = ControladorPromociones::ctrMostrarIntervalos($p["id"]);
			if (count($intervalos) > 0) {
				$tipos = array_unique(array_column($intervalos, "tipo_descuento"));
				$tipoResumen = implode(" / ", array_map(function ($t) {
					return $t === "porcentaje" ? "% Porcentaje" : "Monto fijo";
				}, $tipos));
			}

			$botones = "<div class='btn-group'>";
			if (Permisos::tiene("promociones.editar")) {
				$botones .= "<a class='btn btn-default btn-xs' href='index.php?ruta=editar-promocion&idPromocion=".$p["id"]."' title='Ver / Editar'><i class='fa fa-pencil'></i></a>";
				if (intval($p["estado"]) === 1) {
					$botones .= "<button class='btn btn-warning btn-xs btnTogglePromo' idPromocion='".$p["id"]."' estado='0' title='Deshabilitar'><i class='fa fa-ban'></i></button>";
				} else {
					$botones .= "<button class='btn btn-success btn-xs btnTogglePromo' idPromocion='".$p["id"]."' estado='1' title='Habilitar'><i class='fa fa-check'></i></button>";
				}
			}
			if (Permisos::tiene("promociones.eliminar")) {
				$botones .= "<button class='btn btn-danger btn-xs btnEliminarPromo' idPromocion='".$p["id"]."' title='Deshabilitar'><i class='fa fa-times'></i></button>";
			}
			$botones .= "</div>";

			$datos[] = [
				$i + 1,
				htmlspecialchars($p["nombre"]),
				$tipoResumen,
				intval($p["cant_productos"]),
				intval($p["cant_intervalos"]),
				date('d/m/Y H:i', strtotime($p["fecha_inicio"])),
				date('d/m/Y H:i', strtotime($p["fecha_fin"])),
				"<span class='label ".$badgeClass."'>".strtoupper($estadoCalc)."</span>",
				$botones
			];
		}

		echo json_encode(["data" => $datos]);
	}
}

$tabla = new TablaPromociones();
$tabla->mostrarTabla();
