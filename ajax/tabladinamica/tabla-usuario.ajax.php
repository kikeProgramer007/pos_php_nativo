<?php
require_once "../../includes/sesion-permisos.php";
require_once "../../controladores/usuarios.controlador.php";
require_once "../../modelos/usuarios.modelo.php";


class TablaUsuarios
{
    /*=============================================
	MOSTRAR LA TABLA DE USUARIO
	=============================================*/
    public function mostrarTablaUsuarios()
    {
        $item = null;
        $valor = null;
        $usuario = ControladorUsuarios::ctrMostrarUsuarios($item, $valor);

        $datosJson = '{
		"data": [';
		if (count($usuario) > 0) {
        for ($i = 0; $i < count($usuario); $i++) {
            /*=============================================
				TRAEMOS LA IMAGEN
			=============================================*/
			if($usuario[$i]["foto"] != ""){

				$imagen = "<img src='" . $usuario[$i]["foto"] . "' class='img-thumbnail' width='40px'>";

			  }else{

				$imagen = "<img src='vistas/img/usuarios/default/anonymous.png' class='img-thumbnail' width='40px'>";

			  }





        

            /*=============================================
			TRAEMOS LAS ACCIONES
			=============================================*/
			$botones = Permisos::botonesCrud(
				"usuarios.editar",
				"<button class='btn btn-primary btnEditarUsuario' idUsuario='".$usuario[$i]["id"]."' data-toggle='modal' data-target='#modalEditarUsuario'><i class='fa fa-pencil'></i></button>",
				"usuarios.eliminar",
				"<button class='btn btn-danger btnEliminarUsuario' idUsuario='".$usuario[$i]["id"]."' fotoUsuario='".$usuario[$i]["foto"]."' usuario='".$usuario[$i]["usuario"]."'><i class='fa fa-times'></i></button>"
			);

			if ($usuario[$i]["estado"] != 0) {
                $activar = Permisos::tiene("usuarios.editar")
					? "<button class='btn btn-success btn-xs btnActivar' idUsuario='" . $usuario[$i]["id"] . "' estadoUsuario='1'>Activado</button>"
					: "<span class='label label-success'>Activado</span>";
            } else {
                $activar = Permisos::tiene("usuarios.editar")
					? "<button class='btn btn-danger btn-xs btnActivar' idUsuario='" . $usuario[$i]["id"] . "' estadoUsuario='0'>Desactivado</button>"
					: "<span class='label label-danger'>Desactivado</span>";
            }

            $datosJson .= '[
		"' . ($i + 1) . '",
		"' . $usuario[$i]["nombre"] . '",
		"' . $usuario[$i]["usuario"] . '",
		"' . $imagen . '",
		"' . $usuario[$i]["perfil"] . '",
		"' . $activar . '",
		"' . $usuario[$i]["ultimo_login"] . '",
		"' . $botones . '"
		
		],';
        }

        $datosJson = substr($datosJson, 0, -1);
	}
        $datosJson .= ']
	}';
        echo $datosJson;
    }
}
/*=============================================
	ACTIVAR TABLA DE USUARIO
	=============================================*/

$activarUsuarios = new TablaUsuarios();
$activarUsuarios->mostrarTablaUsuarios();


