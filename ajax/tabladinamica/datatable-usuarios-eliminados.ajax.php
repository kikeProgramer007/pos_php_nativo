<?php
require_once "../../controladores/usuarios.controlador.php";
require_once "../../modelos/usuarios.modelo.php";





class TablaUsuariosEliminados
{
    /*=============================================
	MOSTRAR LA TABLA DE USUARIO
	=============================================*/
    public function mostrarTablaUsuarios()
    {
        $item = null;
        $valor = null;
        $activo=0;
        $usuario = ControladorUsuarios::ctrMostrarUsuarios($item, $valor,$activo);

	// Inicializamos la estructura del JSON
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
			if(isset($_GET["perfilOculto"]) && $_GET["perfilOculto"] == "Supervisor"){

				$botones =  "<div class='btn-group'></div>"; 

			}else{

               

				 $botones =  "<div class='btn-group'><button class='btn btn-success btnRestauraUsuario' idUsuario='".$usuario[$i]["id"]."' fotoUsuario='".$usuario[$i]["foto"]."' usuario='".$usuario[$i]["usuario"]."'><i class='fa fa-undo'></i> Restaurar </button></div>";  

			}
        
            

           

            $datosJson .= '[
		"' . ($i + 1) . '",
		"' . $usuario[$i]["nombre"] . '",
		"' . $usuario[$i]["usuario"] . '",
		"' . $imagen . '",
		"' . $usuario[$i]["perfil"] . '",
		
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

$activarUsuarios = new TablaUsuariosEliminados();
$activarUsuarios->mostrarTablaUsuarios();


