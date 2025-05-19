<?php
require_once "../../controladores/meseros.controlador.php";
require_once "../../modelos/meseros.modelo.php";


class TablaMeserosEliminados
{
    /*=============================================
	MOSTRAR LA TABLA DE MESEROS
	=============================================*/
    public function mostrarTablameseros()
    {

        $item = null;
        $valor = null;
        $estado=0;
        $meseros = ControladorMeseros::ctrMostrarMeseros($item, $valor,$estado);

        $datosJson = '{
            "data": [';
            if (count($meseros) > 0) {

            for ($i = 0; $i < count($meseros); $i++) {
      
            /*=============================================
			TRAEMOS LAS ACCIONES
			=============================================*/
			if(isset($_GET["perfilOculto"]) && ($_GET["perfilOculto"] == "Vendedor" || $_GET["perfilOculto"] == "Supervisor")){

				$botones =  ""; 
              
			}else
            {
				 $botones =  "<div class='btn-group'><button class='btn btn-success btnRestaurarMesero' idMesero='".$meseros[$i]["id"]."'><i class='fa fa-undo'></i>Restaurar</button></div>"; 


            }
			


            $datosJson .= '[
                "' . ($i + 1) . '",
                "' . $meseros[$i]["nombre"] . '",
                "' . $meseros[$i]["documento"] . '",
                "' . $meseros[$i]["telefono"] . '",
                "' . $meseros[$i]["direccion"] . '",
                "' . $meseros[$i]["compras"] . '",
           
                "' . $meseros[$i]["fecha"] . '",
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
    
    $activarMeseros = new TablaMeserosEliminados();
    $activarMeseros->mostrarTablaMeseros();


