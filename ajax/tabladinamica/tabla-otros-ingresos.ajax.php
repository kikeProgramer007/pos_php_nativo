<?php
require_once "../../includes/sesion-permisos.php";
require_once "../../controladores/otros_ingresos.controlador.php";
require_once "../../modelos/otros_ingresos.modelo.php";
require_once "../../modelos/arqueo.modelo.php";

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
            $tipo = strtoupper($ingreso["tipo_entrada"] ?? "EFECTIVO");
            $idArqueo = intval($ingreso["id_arqueo_caja"] ?? 0);
            $cajaAbierta = $idArqueo > 0 && ModeloArqueo::mdlVerificarCajaAbiertaPorIdArqueo($idArqueo);

            $btnEditar = $cajaAbierta
                ? "<button class='btn btn-primary btnEditarOtroIngreso' idOtroIngreso='".$ingreso["id"]."' data-toggle='modal' data-target='#modalEditarOtroIngreso'><i class='fa fa-pencil'></i></button>"
                : "";
            $btnAnular = $cajaAbierta
                ? "<button class='btn btn-danger btnAnularOtroIngreso' idOtroIngreso='".$ingreso["id"]."'><i class='fa fa-times'></i></button>"
                : "";

            $botones = "";
            if (Permisos::tiene("caja.otros_ingresos") && ($btnEditar !== "" || $btnAnular !== "")) {
                $botones = "<div class='btn-group'>".$btnEditar.$btnAnular."</div>";
            }

            $data[] = [
                $i + 1,
                $ingreso["fecha"],
                $ingreso["descripcion"],
                $tipo,
                number_format(floatval($ingreso["monto"]), 2, ".", ""),
                $ingreso["nombre_usuario"],
                $ingreso["id_arqueo_caja"],
                $botones
            ];
        }

        echo json_encode(["data" => $data]);
    }
}

$activar = new TablaOtrosIngresos();
$activar->mostrarTabla();
