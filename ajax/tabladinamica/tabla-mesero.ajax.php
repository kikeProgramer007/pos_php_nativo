<?php
require_once "../../includes/sesion-permisos.php";
require_once "../../controladores/meseros.controlador.php";
require_once "../../modelos/meseros.modelo.php";


class TablaMeseros
{
    /*=============================================
	MOSTRAR LA TABLA DE MESEROS
	=============================================*/
    public function mostrarTablameseros()
    {

        $item = null;
        $valor = null;
        $meseros = ControladorMeseros::ctrMostrarMeseros($item, $valor);

        $datosJson = '{
            "data": [';
            if (count($meseros) > 0) {
            for ($i = 0; $i < count($meseros); $i++) {
      
            /*=============================================
			TRAEMOS LAS ACCIONES
			=============================================*/
			$botones = Permisos::botonesCrud(
				"meseros.editar",
				"<button class='btn btn-primary btnEditarMesero' idMesero='".$meseros[$i]["id"]."' data-toggle='modal' data-target='#modalEditarMesero'><i class='fa fa-pencil'></i></button>",
				"meseros.eliminar",
				"<button class='btn btn-danger btnEliminarMesero' idMesero='".$meseros[$i]["id"]."'><i class='fa fa-times'></i></button>"
			);
			


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
    
    $activarMeseros = new TablaMeseros();
    $activarMeseros->mostrarTablaMeseros();


