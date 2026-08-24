<?php

require_once "../includes/sesion-permisos.php";
require_once "../controladores/otros_ingresos.controlador.php";
require_once "../modelos/otros_ingresos.modelo.php";
require_once "../modelos/arqueo.modelo.php";

if (!isset($_SESSION["iniciarSesion"]) || $_SESSION["iniciarSesion"] !== "ok") {
    echo json_encode(["status" => "error", "mensaje" => "No autenticado"]);
    exit;
}

Permisos::ajaxRequiere("caja.otros_ingresos");

$accion = $_POST["accion"] ?? ($_GET["accion"] ?? "");

switch ($accion) {
    case "registrarOtroIngreso":
        echo json_encode(ControladorOtrosIngresos::ctrRegistrarOtroIngreso());
        break;

    case "editarOtroIngreso":
        echo json_encode(ControladorOtrosIngresos::ctrEditarOtroIngreso());
        break;

    case "anularOtroIngreso":
        echo json_encode(ControladorOtrosIngresos::ctrAnularOtroIngreso());
        break;

    case "obtenerOtroIngreso":
        $id = intval($_POST["idOtroIngreso"] ?? ($_GET["idOtroIngreso"] ?? 0));
        if ($id <= 0) {
            echo json_encode(["status" => "error", "mensaje" => "Ingreso no válido"]);
            break;
        }
        $ingreso = ControladorOtrosIngresos::ctrMostrarOtrosIngresos("id", $id);
        if (!$ingreso || intval($ingreso["estado"] ?? 0) !== 1) {
            echo json_encode(["status" => "error", "mensaje" => "Ingreso no encontrado"]);
            break;
        }
        echo json_encode(["status" => "ok", "data" => $ingreso]);
        break;

    default:
        echo json_encode(["status" => "error", "mensaje" => "Acción no válida"]);
}
