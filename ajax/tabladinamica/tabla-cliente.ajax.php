<?php
require_once "../../includes/sesion-permisos.php";
require_once "../../controladores/clientes.controlador.php";
require_once "../../modelos/clientes.modelo.php";


class TablaClientes
{
    /*=============================================
	MOSTRAR LA TABLA DE CLIENTES
	=============================================*/
    public function mostrarTablaclientes()
    {

        $item = null;
        $valor = null;
        $clientes = ControladorClientes::ctrMostrarClientes($item, $valor);

        $datosJson = '{
            "data": [';
            if (count($clientes) > 0) {

            for ($i = 0; $i < count($clientes); $i++) {
      
            /*=============================================
			TRAEMOS LAS ACCIONES
			=============================================*/
			$botones = Permisos::botonesCrud(
				"clientes.editar",
				"<button class='btn btn-primary btnEditarCliente' idCliente='".$clientes[$i]["id"]."' data-toggle='modal' data-target='#modalEditarCliente'><i class='fa fa-pencil'></i></button>",
				"clientes.eliminar",
				"<button class='btn btn-danger btnEliminarCliente' idCliente='".$clientes[$i]["id"]."'><i class='fa fa-times'></i></button>"
			);
			


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
     ACTIVAR TABLA DE CATEGORIA
    =============================================*/
    
    $activarClientes = new TablaClientes();
    $activarClientes->mostrarTablaClientes();


