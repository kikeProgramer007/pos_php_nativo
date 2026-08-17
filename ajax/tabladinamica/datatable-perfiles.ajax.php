<?php
require_once "../../includes/sesion-permisos.php";
require_once "../../modelos/perfiles.modelo.php";

class TablaPerfiles
{
	public function mostrarTabla($activo = 1)
	{
		if ($activo == 1 && !Permisos::tiene("perfiles.ver")) {
			echo json_encode(["data" => []]);
			return;
		}
		if ($activo == 0 && !Permisos::tiene("perfiles.eliminados")) {
			echo json_encode(["data" => []]);
			return;
		}

		$perfiles = ModeloPerfiles::mdlMostrarPerfiles($activo);
		$data = [];
		$i = 1;
		foreach ($perfiles as $perfil) {
			if ($activo == 1) {
				$estado = intval($perfil["estado"]) === 1
					? "<span class='label label-success'>Habilitado</span>"
					: "<span class='label label-default'>Deshabilitado</span>";

				$botones = "<div class='btn-group'>";
				if (Permisos::tiene("perfiles.permisos")) {
					$botones .= "<a class='btn btn-success' href='index.php?ruta=asignar-permisos&id=".$perfil["id"]."' title='Asignar permisos'><i class='fa fa-unlock-alt'></i></a>";
				}
				if (Permisos::tiene("perfiles.editar")) {
					$botones .= "<a class='btn btn-primary' href='index.php?ruta=editar-perfil&id=".$perfil["id"]."' title='Editar'><i class='fa fa-pencil'></i></a>";
				}
				if (Permisos::tiene("perfiles.eliminar")) {
					$botones .= "<button class='btn btn-danger btnEliminarPerfil' idPerfil='".$perfil["id"]."' title='Eliminar'><i class='fa fa-times'></i></button>";
				}
				$botones .= "</div>";

				$data[] = [
					$i++,
					htmlspecialchars($perfil["nombre"]),
					htmlspecialchars($perfil["descripcion"]),
					$estado,
					$botones
				];
			} else {
				$botones = Permisos::tiene("perfiles.eliminados")
					? "<div class='btn-group'><button class='btn btn-primary btnRestaurarPerfil' idPerfil='".$perfil["id"]."' title='Restaurar'><i class='fa fa-undo'></i></button></div>"
					: "";
				$data[] = [
					$i++,
					htmlspecialchars($perfil["nombre"]),
					htmlspecialchars($perfil["descripcion"]),
					$botones
				];
			}
		}

		echo json_encode(["data" => $data]);
	}
}

$tabla = new TablaPerfiles();
$activo = (isset($_GET["eliminados"]) && $_GET["eliminados"] == "1") ? 0 : 1;
$tabla->mostrarTabla($activo);
