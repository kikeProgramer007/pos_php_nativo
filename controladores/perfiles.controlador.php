<?php

class ControladorPerfiles
{
	static public function ctrMostrarPerfiles($activo = 1)
	{
		return ModeloPerfiles::mdlMostrarPerfiles($activo);
	}

	static public function ctrMostrarPerfil($item, $valor)
	{
		return ModeloPerfiles::mdlMostrarPerfil($item, $valor);
	}

	static public function ctrCrearPerfil()
	{
		if (!isset($_POST["nuevoNombrePerfil"])) {
			return;
		}
		if (!Permisos::tiene("perfiles.crear")) {
			Permisos::requiere("perfiles.crear");
			return;
		}

		$nombre = trim($_POST["nuevoNombrePerfil"]);
		if ($nombre === "" || !preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $nombre)) {
			self::alerta("error", "¡El nombre no puede ir vacío o llevar caracteres especiales!", "perfiles");
			return;
		}

		$existe = ModeloPerfiles::mdlMostrarPerfil("nombre", $nombre);
		if ($existe) {
			self::alerta("error", "Ya existe un perfil con ese nombre", "perfiles");
			return;
		}

		$datos = [
			"nombre" => $nombre,
			"descripcion" => trim($_POST["nuevaDescripcionPerfil"] ?? ""),
			"estado" => isset($_POST["nuevoEstadoPerfil"]) ? 1 : 1
		];

		$respuesta = ModeloPerfiles::mdlIngresarPerfil($datos);
		if ($respuesta === "ok") {
			self::alerta("success", "El perfil ha sido guardado correctamente", "perfiles");
		}
	}

	static public function ctrEditarPerfil()
	{
		if (!isset($_POST["editarNombrePerfil"], $_POST["idPerfil"])) {
			return;
		}
		if (!Permisos::tiene("perfiles.editar")) {
			Permisos::requiere("perfiles.editar");
			return;
		}

		$id = intval($_POST["idPerfil"]);
		$nombre = trim($_POST["editarNombrePerfil"]);
		if ($id <= 0 || $nombre === "" || !preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $nombre)) {
			self::alerta("error", "¡El nombre no puede ir vacío o llevar caracteres especiales!", "perfiles");
			return;
		}

		$existe = ModeloPerfiles::mdlMostrarPerfil("nombre", $nombre);
		if ($existe && intval($existe["id"]) !== $id) {
			self::alerta("error", "Ya existe un perfil con ese nombre", "perfiles");
			return;
		}

		$datos = [
			"id" => $id,
			"nombre" => $nombre,
			"descripcion" => trim($_POST["editarDescripcionPerfil"] ?? ""),
			"estado" => isset($_POST["editarEstadoPerfil"]) ? 1 : 1
		];

		$respuesta = ModeloPerfiles::mdlEditarPerfil($datos);
		if ($respuesta === "ok") {
			self::alerta("success", "El perfil ha sido editado correctamente", "perfiles");
		}
	}

	static public function ctrEliminarPerfil()
	{
		if (!isset($_GET["idPerfilEliminar"])) {
			return;
		}
		if (!Permisos::tiene("perfiles.eliminar")) {
			Permisos::requiere("perfiles.eliminar");
			return;
		}

		$id = intval($_GET["idPerfilEliminar"]);
		$perfil = ModeloPerfiles::mdlMostrarPerfil("id", $id);
		if (!$perfil) {
			self::alerta("error", "El perfil no existe", "perfiles");
			return;
		}

		if (ModeloPerfiles::mdlEsUltimoPerfilConPermiso($id, "perfiles.permisos")) {
			self::alerta("error", "No se puede eliminar el último perfil con permiso para administrar permisos", "perfiles");
			return;
		}

		$usuarios = ModeloPerfiles::mdlContarUsuariosDePerfil($id);
		if ($usuarios > 0) {
			self::alerta("error", "No se puede eliminar: hay $usuarios usuario(s) asignado(s) a este perfil", "perfiles");
			return;
		}

		$respuesta = ModeloPerfiles::mdlActualizarPerfil("activo", 0, $id);
		if ($respuesta === "ok") {
			self::alerta("success", "El perfil ha sido eliminado correctamente", "perfiles");
		}
	}

	static public function ctrRestaurarPerfil()
	{
		if (!isset($_GET["idPerfilRestaurar"])) {
			return;
		}
		if (!Permisos::tiene("perfiles.eliminados")) {
			Permisos::requiere("perfiles.eliminados");
			return;
		}

		$id = intval($_GET["idPerfilRestaurar"]);
		$respuesta = ModeloPerfiles::mdlActualizarPerfil("activo", 1, $id);
		if ($respuesta === "ok") {
			self::alerta("success", "El perfil ha sido restaurado correctamente", "perfiles-eliminados");
		}
	}

	static public function ctrGuardarPermisosPerfil()
	{
		if (!isset($_POST["idPerfilPermisos"])) {
			return;
		}
		if (!Permisos::tiene("perfiles.permisos")) {
			Permisos::requiere("perfiles.permisos");
			return;
		}

		$id = intval($_POST["idPerfilPermisos"]);
		$perfil = ModeloPerfiles::mdlMostrarPerfil("id", $id);
		if (!$perfil || intval($perfil["activo"]) !== 1) {
			self::alerta("error", "El perfil no existe", "perfiles");
			return;
		}

		$ids = isset($_POST["permisos"]) && is_array($_POST["permisos"]) ? $_POST["permisos"] : [];

		if (ModeloPerfiles::mdlEsUltimoPerfilConPermiso($id, "perfiles.permisos")) {
			$catalogo = ModeloPerfiles::mdlMostrarPermisosAgrupados();
			$tieneAdmin = false;
			foreach ($catalogo as $perm) {
				if ($perm["codigo"] === "perfiles.permisos" && in_array((string)$perm["id"], array_map("strval", $ids), true)) {
					$tieneAdmin = true;
					break;
				}
			}
			if (!$tieneAdmin) {
				self::alerta("error", "Debe mantener el permiso de asignar permisos en al menos un perfil", "asignar-permisos");
				return;
			}
		}

		$respuesta = ModeloPerfiles::mdlGuardarPermisosPerfil($id, $ids);
		if ($respuesta === "ok") {
			self::alerta("success", "Los permisos del perfil se guardaron correctamente", "perfiles");
		} else {
			self::alerta("error", "No se pudieron guardar los permisos", "perfiles");
		}
	}

	static private function alerta($tipo, $mensaje, $ruta)
	{
		echo '<script>
			swal({
				type: "' . $tipo . '",
				title: "' . addslashes($mensaje) . '",
				showConfirmButton: true,
				confirmButtonText: "Cerrar"
			}).then(function(result){
				if(result.value){
					window.location = "' . $ruta . '";
				}
			});
		</script>';
	}
}
