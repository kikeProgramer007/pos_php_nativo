<?php
require_once "../../includes/sesion-permisos.php";
require_once "../../controladores/otros_ingresos.controlador.php";
require_once "../../modelos/otros_ingresos.modelo.php";

if (!isset($_SESSION["iniciarSesion"]) || $_SESSION["iniciarSesion"] !== "ok") {
    echo json_encode(["data" => []]);
    return;
}

Permisos::ajaxRequiere("caja.otros_ingresos");

class TablaOtrosIngresos
{
    public function mostrarTabla()
    {
        $ingresos = ControladorOtrosIngresos::ctrMostrarOtrosIngresos(null, null);
        $data = [];

        if (!is_array($ingresos)) {
            echo json_encode(["data" => []]);
            return;
        }

        foreach ($ingresos as $i => $ingreso) {
            $data[] = [
                $i + 1,
                $ingreso["fecha"],
                $ingreso["descripcion"],
                number_format(floatval($ingreso["monto"]), 2, ".", ""),
                $ingreso["nombre_usuario"],
                $ingreso["id_arqueo_caja"]
            ];
        }

        echo json_encode(["data" => $data]);
    }
}

$activar = new TablaOtrosIngresos();
$activar->mostrarTabla();
