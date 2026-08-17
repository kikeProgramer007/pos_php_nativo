<?php
require_once "../../includes/sesion-permisos.php";
require_once "../../controladores/proveedor.controlador.php";
require_once "../../modelos/proveedor.modelo.php";


class TablaProveedorEliminados
{
    /*=============================================
	MOSTRAR LA TABLA DE PROVEEDOR
	=============================================*/
    public function mostrarTablaProveedor()
    {

        $item = null;
        $valor = null;
        $estado=0;

        $proveedor = ControladorProveedors::ctrMostrarProveedors($item, $valor,$estado);
     
        $datosJson = '{
            "data": [';
            if (count($proveedor) > 0) {
            for ($i = 0; $i < count($proveedor); $i++) {
      
            /*=============================================
			TRAEMOS LAS ACCIONES
			=============================================*/
			$botones = Permisos::tiene("proveedores.eliminados")
				? "<div class='btn-group'><button class='btn btn-success btnRestaurarProveedor' idProveedor='".$proveedor[$i]["id"]."'><i class='fa fa-undo'></i>Restaurar</button></div>"
				: "";


            
			


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
    
    $activarProveedor = new TablaProveedorEliminados();
    $activarProveedor->mostrarTablaProveedor();


