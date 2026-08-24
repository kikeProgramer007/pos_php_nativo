<?php

class Permisos
{
	private static $cargado = false;

	/*=============================================
	Cargar permisos del usuario autenticado.
	Se ejecuta en cada request: si un administrador
	cambia el perfil, el usuario lo ve al recargar.
	=============================================*/
	static public function cargarSesion()
	{
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}

		if (!isset($_SESSION["iniciarSesion"]) || $_SESSION["iniciarSesion"] !== "ok") {
			return;
		}

		$idPerfil = isset($_SESSION["id_perfil"]) ? intval($_SESSION["id_perfil"]) : 0;

		if ($idPerfil <= 0 && !empty($_SESSION["id"])) {
			$usuario = ModeloUsuarios::mdlMostrarUsuarios("usuarios", "id", $_SESSION["id"]);
			if ($usuario) {
				$idPerfil = intval($usuario["id_perfil"] ?? 0);
				$_SESSION["id_perfil"] = $idPerfil ?: null;
				if (!empty($usuario["perfil"])) {
					$_SESSION["perfil"] = $usuario["perfil"];
				}
			}
		}

		$codigos = [];
		if ($idPerfil > 0 && class_exists("ModeloPerfiles")) {
			try {
				$filas = ModeloPerfiles::mdlPermisosPorPerfil($idPerfil);
				foreach ($filas as $fila) {
					$codigos[$fila["codigo"]] = true;
				}
				$perfil = ModeloPerfiles::mdlMostrarPerfil("id", $idPerfil);
				if ($perfil && intval($perfil["activo"]) === 1) {
					$_SESSION["perfil"] = $perfil["nombre"];
				}
			} catch (Exception $e) {
				$codigos = [];
			}
		}

		$_SESSION["permisos"] = $codigos;
		self::$cargado = true;
	}

	static public function tiene($codigo)
	{
		if (!self::$cargado) {
			self::cargarSesion();
		}

		if (!empty($_SESSION["permisos"]) && isset($_SESSION["permisos"][$codigo])) {
			return true;
		}

		// Si ya hay permisos de BD (incluso vacíos tras asignación), no usar legado
		if (isset($_SESSION["id_perfil"]) && intval($_SESSION["id_perfil"]) > 0 && is_array($_SESSION["permisos"] ?? null)) {
			return isset($_SESSION["permisos"][$codigo]);
		}

		return self::legacyTiene($codigo);
	}

	static public function tieneAlguno($codigos)
	{
		foreach ((array) $codigos as $codigo) {
			if (self::tiene($codigo)) {
				return true;
			}
		}
		return false;
	}

	static public function requiere($codigo)
	{
		if (!self::tiene($codigo)) {
			echo '<script>window.location = "no-autorizado";</script>';
			return false;
		}
		return true;
	}

	static public function ajaxRequiere($codigo)
	{
		if (!self::tiene($codigo)) {
			echo json_encode(["status" => "error", "error" => "no-autorizado", "mensaje" => "Acceso no autorizado"]);
			exit;
		}
	}

	static public function datosPerfilPorId($id)
	{
		$id = intval($id);
		if ($id <= 0 || !class_exists("ModeloPerfiles")) {
			return null;
		}
		try {
			$perfil = ModeloPerfiles::mdlMostrarPerfil("id", $id);
			if ($perfil && intval($perfil["activo"]) === 1 && intval($perfil["estado"]) === 1) {
				return $perfil;
			}
		} catch (Exception $e) {
			return null;
		}
		return null;
	}

	static public function botonesCrud($codigoEditar, $htmlEditar, $codigoEliminar, $htmlEliminar)
	{
		$editar = self::tiene($codigoEditar) ? $htmlEditar : "";
		$eliminar = self::tiene($codigoEliminar) ? $htmlEliminar : "";
		if ($editar === "" && $eliminar === "") {
			return "";
		}
		return "<div class='btn-group'>".$editar.$eliminar."</div>";
	}

	static public function permisoDeRuta($ruta)
	{
		$mapa = [
			"inicio" => "inicio.ver",
			"usuarios" => "usuarios.ver",
			"agregar-usuario" => "usuarios.crear",
			"usuarios-eliminados" => "usuarios.eliminados",
			"perfiles" => "perfiles.ver",
			"agregar-perfil" => "perfiles.crear",
			"editar-perfil" => "perfiles.editar",
			"asignar-permisos" => "perfiles.permisos",
			"perfiles-eliminados" => "perfiles.eliminados",
			"categorias" => "categorias.ver",
			"agregar-categoria" => "categorias.crear",
			"editar-categoria" => "categorias.editar",
			"categorias-eliminados" => "categorias.eliminados",
			"productos" => "productos.ver",
			"agregar-producto" => "productos.crear",
			"productos-eliminados" => "productos.eliminados",
			"promociones" => "promociones.ver",
			"agregar-promocion" => "promociones.crear",
			"editar-promocion" => "promociones.editar",
			"clientes" => "clientes.ver",
			"agregar-cliente" => "clientes.crear",
			"clientes-eliminados" => "clientes.eliminados",
			"meseros" => "meseros.ver",
			"agregar-mesero" => "meseros.crear",
			"meseros-eliminados" => "meseros.eliminados",
			"arqueo-de-caja" => "caja.ver",
			"gastos" => "gastos.ver",
			"agregar-gasto" => "gastos.crear",
			"otros-ingresos" => "caja.otros_ingresos",
			"crear-venta" => "ventas.crear",
			"editar-venta" => "ventas.editar",
			"ventas" => "ventas.ver",
			"ventas-eliminadas" => "ventas.eliminados",
			"compras" => "compras.ver",
			"crear-compra" => "compras.crear",
			"compras-eliminadas" => "compras.eliminados",
			"proveedor" => "proveedores.ver",
			"agregar-proveedor" => "proveedores.crear",
			"proveedor-eliminados" => "proveedores.eliminados",
			"reportes" => "reportes.ventas",
			"reporte-venta" => "reportes.venta_fecha",
			"reporte-top-productos" => "reportes.top_productos",
			"reporte-top-meseros-ventas" => "reportes.top_meseros",
			"ver-productos-faltantes" => "reportes.faltantes",
			"reporte-categoria" => "reportes.categorias",
			"ganancias-ventas" => "reportes.ganancias",
			"reporte-compra" => "reportes.compras",
			"reporte-gastos" => "reportes.gastos"
		];

		return $mapa[$ruta] ?? null;
	}

	static public function perfilesSelect()
	{
		if (!class_exists("ModeloPerfiles")) {
			return [];
		}
		try {
			return ModeloPerfiles::mdlMostrarPerfiles(1, 1);
		} catch (Exception $e) {
			return [];
		}
	}

	private static function legacyTiene($codigo)
	{
		$perfil = $_SESSION["perfil"] ?? "";

		if ($perfil === "Administrador") {
			return true;
		}

		$supervisor = [
			"inicio.ver",
			"categorias.ver", "categorias.crear", "categorias.editar", "categorias.eliminar", "categorias.eliminados",
			"productos.ver", "productos.crear", "productos.editar", "productos.eliminar", "productos.eliminados",
			"promociones.ver", "promociones.crear", "promociones.editar", "promociones.eliminar",
			"clientes.ver", "clientes.crear", "clientes.editar", "clientes.eliminar",
			"meseros.ver", "meseros.crear", "meseros.editar", "meseros.eliminar",
			"caja.ver", "caja.abrir", "caja.cerrar", "caja.otros_ingresos",
			"gastos.ver", "gastos.crear", "gastos.editar", "gastos.eliminar",
			"ventas.crear", "ventas.ver", "ventas.editar", "ventas.cobrar", "ventas.eliminar", "ventas.imprimir",
			"compras.ver", "compras.crear", "compras.eliminar", "compras.eliminados",
			"proveedores.ver", "proveedores.crear", "proveedores.editar", "proveedores.eliminar", "proveedores.eliminados",
			"reportes.ventas", "reportes.venta_fecha", "reportes.top_productos", "reportes.top_meseros",
			"reportes.faltantes", "reportes.categorias", "reportes.ganancias", "reportes.compras", "reportes.gastos"
		];

		$vendedor = [
			"inicio.ver",
			"caja.ver", "caja.abrir", "caja.cerrar", "caja.otros_ingresos",
			"ventas.crear", "ventas.ver", "ventas.editar", "ventas.cobrar", "ventas.imprimir",
			"reportes.ventas", "reportes.venta_fecha", "reportes.top_productos", "reportes.top_meseros",
			"reportes.faltantes", "reportes.categorias", "reportes.ganancias", "reportes.compras", "reportes.gastos"
		];

		if ($perfil === "Supervisor") {
			return in_array($codigo, $supervisor, true);
		}
		if ($perfil === "Vendedor") {
			return in_array($codigo, $vendedor, true);
		}

		return false;
	}
}
