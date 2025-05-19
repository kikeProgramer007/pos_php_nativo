<?php
require_once "../../controladores/proveedor.controlador.php";
require_once "../../modelos/proveedor.modelo.php";


class TablaProveedor
{
    /*=============================================
	MOSTRAR LA TABLA DE PROVEEDOR
	=============================================*/
    public function mostrarTablaProveedor()
    {

        $item = null;
        $valor = null;

        $proveedor = ControladorProveedors::ctrMostrarProveedors($item, $valor);
      
        $datosJson = '{
            "data": [';
            if (count($proveedor) > 0) {
            for ($i = 0; $i < count($proveedor); $i++) {
      
            /*=============================================
			TRAEMOS LAS ACCIONES
			=============================================*/
			if(isset($_GET["perfilOculto"])  && $_GET["perfilOculto"] == "Supervisor"){

				$botones =  ""; 
              
			}else
            
            {

				 $botones =  "<div class='btn-group'><button class='btn btn-primary  btnEditarProveedor' idProveedor='".$proveedor[$i]["id"]."' data-toggle='modal' data-target='#modalEditarProveedor'><i class='fa fa-pencil'></i></button><button class='btn btn-danger btnEliminarProveedor' idProveedor='".$proveedor[$i]["id"]."'><i class='fa fa-times'></i></button></div>"; 
               

            }


            
			


            $datosJson .= '[
                "' . ($i + 1) . '",
                "' . $proveedor[$i]["nombre"] . '",
                "' . $proveedor[$i]["empresa"] . '",
                "' . $proveedor[$i]["telefono"] . '",
                "' . $proveedor[$i]["direccion"] . '",
                "' . $proveedor[$i]["fecha"] . '",
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
     ACTIVAR TABLA DE Proveedor
    =============================================*/
    
    $activarProveedor = new TablaProveedor();
    $activarProveedor->mostrarTablaProveedor();


