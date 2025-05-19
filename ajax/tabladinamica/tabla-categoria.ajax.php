<?php
require_once "../../controladores/categorias.controlador.php";
require_once "../../modelos/categorias.modelo.php";


class TablaCategorias
{
    /*=============================================
	MOSTRAR LA TABLA DE CATEGORIA
	=============================================*/
    public function mostrarTablaCategoria()
    {

        $item = null;
        $valor = null;

        $categorias = ControladorCategorias::ctrMostrarCategorias($item, $valor);

        $datosJson = '{
            "data": [';
            if (count($categorias) > 0) {

            for ($i = 0; $i < count($categorias); $i++) {
      
            /*=============================================
			TRAEMOS LAS ACCIONES
			=============================================*/
			if(isset($_GET["perfilOculto"]) && $_GET["perfilOculto"] == "Supervisor"){

				$botones =  ""; 

			}else{

                $botones =  "<div class='btn-group'><button class='btn btn-primary btnEditarCategoria' idCategoria='".$categorias[$i]["id"]."' data-toggle='modal' data-target='#modalEditarCategoria'><i class='fa fa-pencil'></i></button><button class='btn btn-danger btnEliminarCategoria' idCategoria='".$categorias[$i]["id"]."'><i class='fa fa-times'></i></button></div>"; 

				/*  $botones =  "<div class='btn-group'><a href='editar-categoria?id=" . $categorias[$i]['id'] . "' class='btn btn-primary btnEditarCategoria' idCategoria='".$categorias[$i]["id"]."' ><i class='fa fa-pencil'></i></a><button class='btn btn-danger btnEliminarCategoria' idCategoria='".$categorias[$i]["id"]."'><i class='fa fa-times'></i></button></div>";  */
                
			}


            $datosJson .= '[
                "' . ($i + 1) . '",
                "' . $categorias[$i]["categoria"] . '",
                "' . $categorias[$i]["fecha"] . '",
                "' . $botones . '",
                "' . $categorias[$i]["id"] . '"
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
        ACTIVAR TABLA DE CATEGORIA
    =============================================*/
    
    $activarCategorias = new TablaCategorias();
    $activarCategorias->mostrarTablaCategoria();









        