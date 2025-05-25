<?php
require_once "../../controladores/gastos.controlador.php";
require_once "../../modelos/gastos.modelo.php";


class TablaGastos
{
    /*=============================================
	MOSTRAR LA TABLA DE GASTOS
	=============================================*/
    public function mostrarTablagastos()
    {

        $item = null;
        $valor = null;
        $gastos = ControladorGastos::ctrMostrarGastos($item, $valor);

        $datosJson = '{
            "data": [';
            if (count($gastos) > 0) {
            for ($i = 0; $i < count($gastos); $i++) {
      
            /*=============================================
			TRAEMOS LAS ACCIONES
			=============================================*/
			if(isset($_GET["perfilOculto"]) && ($_GET["perfilOculto"] == "Vendedor" || $_GET["perfilOculto"] == "Supervisor")){

				$botones =  ""; 
              
			}else
            {
				 $botones =  "<div class='btn-group'><button class='btn btn-primary btnEditarGasto' idGasto='".$gastos[$i]["id"]."' data-toggle='modal' data-target='#modalEditarGasto'><i class='fa fa-pencil'></i></button><button class='btn btn-danger btnEliminarGasto' idGasto='".$gastos[$i]["id"]."'><i class='fa fa-times'></i></button></div>"; 
            }
			


            $datosJson .= '[
                "' . ($i + 1) . '",
                "' . $gastos[$i]["fecha"] . '",
                "' . $gastos[$i]["nombre_tipo_gasto"] . '",
                "' . $gastos[$i]["descripcion"] . '",
                "' . $gastos[$i]["monto"] . '",
                "' . $gastos[$i]["forma_pago_descripcion"] . '",
                "' . $gastos[$i]["nombre_usuario"] . '",
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
    
    $activarGastos = new TablaGastos();
    $activarGastos->mostrarTablagastos();


