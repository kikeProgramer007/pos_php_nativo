<?php
// Incluye los controladores y modelos necesarios
require_once "../controladores/ventas.controlador.php";
require_once "../modelos/ventas.modelo.php";
require_once "../modelos/productos.modelo.php";
require_once "../modelos/meseros.modelo.php";
require_once "../modelos/arqueo.modelo.php";

// Si usas sesiones para el usuario, inicia la sesión
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Solo procesa si es una petición POST y viene el dato de nuevaVenta
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["nuevaVenta"])) {
    // Llama al método estático del controlador
    ControladorVentas::ctrCrearVenta();
    // El método ya imprime el JSON y hace return
    exit;
}

// Si quieres puedes agregar más acciones aquí para otros métodos AJAX
?>