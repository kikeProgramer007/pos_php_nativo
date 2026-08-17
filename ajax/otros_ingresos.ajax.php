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

if (isset($_POST["accion"]) && $_POST["accion"] === "registrarOtroIngreso") {
    echo json_encode(ControladorOtrosIngresos::ctrRegistrarOtroIngreso());
    return;
}

echo json_encode(["status" => "error", "mensaje" => "Acción no válida"]);
