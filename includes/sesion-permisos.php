<?php
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

require_once __DIR__ . "/../controladores/permisos.controlador.php";
require_once __DIR__ . "/../modelos/conexion.php";
require_once __DIR__ . "/../modelos/perfiles.modelo.php";
require_once __DIR__ . "/../modelos/usuarios.modelo.php";

if (isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"] === "ok") {
	Permisos::cargarSesion();
}
