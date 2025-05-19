<?php
require_once "../../controladores/categorias.controlador.php";
require_once "../../modelos/categorias.modelo.php";

require_once "../../controladores/categorias.controlador.php";
require_once "../../modelos/categorias.modelo.php";

class TablaCategoriasEliminados
{
    /*=============================================
	MOSTRAR LA TABLA DE CATEGORIA
	=============================================*/
    public function mostrarTablaCategoria()
    {

        $item = null;
        $valor = null;
        $estado = 0;

        $categorias = ControladorCategorias::ctrMostrarCategorias($item, $valor,$estado);

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

                

                $botones =  "<div class='btn-group'><button class='btn btn-success btnRestaurarCategoria' idCategoria='".$categorias[$i]["id"]."'><i class='fa fa-undo'></i>Restaurar</button></div>"; 

			}


            $datosJson .= '[
                "' . ($i + 1) . '",
                "' . $categorias[$i]["categoria"] . '",
                "' . $categorias[$i]["fecha"] . '",
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
        ACTIVAR TABLA DE CATEGORIA
    =============================================*/
    
    $activarCategorias = new TablaCategoriasEliminados();
    $activarCategorias->mostrarTablaCategoria();









        