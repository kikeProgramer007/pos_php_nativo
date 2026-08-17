<?php
require_once "../../includes/sesion-permisos.php";
require_once "../../controladores/clientes.controlador.php";
require_once "../../modelos/clientes.modelo.php";


class TablaClientesEliminados
{
    /*=============================================
	MOSTRAR LA TABLA DE CLIENTES
	=============================================*/
    public function mostrarTablaclientes()
    {

        $item = null;
        $valor = null;
        $estado=0;
        $clientes = ControladorClientes::ctrMostrarClientes($item, $valor,$estado);

        $datosJson = '{
            "data": [';
            if (count($clientes) > 0) {

            for ($i = 0; $i < count($clientes); $i++) {
      
            /*=============================================
			TRAEMOS LAS ACCIONES
			=============================================*/
			$botones = Permisos::tiene("clientes.eliminados")
				? "<div class='btn-group'><button class='btn btn-success btnRestaurarCliente' idCliente='".$clientes[$i]["id"]."'><i class='fa fa-undo'></i>Restaurar</button></div>"
				: ""; 

            $datosJson .= '[
                "' . ($i + 1) . '",
                "' . $clientes[$i]["nombre"] . '",
                "' . $clientes[$i]["fecha"] . '",
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
     ACTIVAR TABLA DE CLIENTES
    =============================================*/
    
    $activarClientes = new TablaClientesEliminados();
    $activarClientes->mostrarTablaClientes();


