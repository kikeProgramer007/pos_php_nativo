<?php
require_once "../includes/sesion-permisos.php";
// Incluye los controladores y modelos necesarios
require_once "../controladores/ventas.controlador.php";
require_once "../modelos/ventas.modelo.php";
require_once "../modelos/productos.modelo.php";
require_once "../modelos/meseros.modelo.php";
require_once "../modelos/arqueo.modelo.php";
require_once "../controladores/promociones.controlador.php";
require_once "../modelos/promociones.modelo.php";

// Si usas sesiones para el usuario, inicia la sesión
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit;
}

// IMPORTANTE: actualizar/cobrar deben evaluarse ANTES que nuevaVenta,
// porque el formulario de edición también envía el campo name="nuevaVenta" (número de ticket).

// Actualizar cuenta pendiente
if (isset($_POST["actualizarCuentaPendiente"])) {
    ControladorVentas::ctrActualizarCuentaPendiente();
    exit;
}

// Cobrar cuenta pendiente
if (isset($_POST["cobrarCuentaPendiente"])) {
    ControladorVentas::ctrCobrarCuentaPendiente();
    exit;
}

// Registrar nueva venta o cuenta pendiente
if (isset($_POST["nuevaVenta"])) {
    ControladorVentas::ctrCrearVenta();
    exit;
}

?>
