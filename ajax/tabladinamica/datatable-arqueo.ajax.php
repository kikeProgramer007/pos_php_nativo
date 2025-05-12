<?php

require_once "../../controladores/arqueo.controlador.php";
require_once "../../modelos/arqueo.modelo.php";

class DatatableArqueosCaja{

 	/*=============================================
 	 MOSTRAR LA TABLA DE ARQUEOS
  	=============================================*/ 

	public function mostrarTablaArqueosCaja(){

  		$arqueos = ControladorArqueo::ctrMostrarArqueo(null,null);	
		
  		if(count($arqueos) == 0){
  			// En caso de que no haya compras, retornamos un JSON vacío
  			echo json_encode(["data" => []]);
		  	return;
  		}
		
  		// Creamos un array para almacenar los datos
  		$datos = [];

  		for($i = 0; $i < count($arqueos); $i++){

			$color= $arqueos[$i]["estado"] == 'abierta'? "label label-primary" : "label label-default";
		
			  /*=============================================
			  TRAEMOS LAS ACCIONES
			  =============================================*/
			$estado = "<span class='".$color."'>".$arqueos[$i]["estado"]."</span>";
			  //if(isset($_GET["perfilOculto"]) && $_GET["perfilOculto"] == "Administrador"){
				  $botones=  "<button class='btn btn-default btn-xs'  onclick='arqueoCaja.imprimirMovimientosEnCaja(".$arqueos[$i]["id"].")'><i class='fa fa-print'></i></button>"; 
			   //}
			
		
		  	// Formateamos cada registro de compra como un array
		  	$datos[] = [
			      ($i+1),
			      $arqueos[$i]["usuario"],
				  $estado,
				  $arqueos[$i]["fecha_apertura"],
				  $arqueos[$i]["fecha_cierre"],
			      number_format($arqueos[$i]["total_ingresos"], 2),
				  number_format($arqueos[$i]["total_egresos"], 2),
				  $arqueos[$i]["resultado_neto"],
				  $botones
			      
		  	];
  		}

  		// Convertimos los datos a JSON y los enviamos como respuesta
  		echo json_encode(["data" => $datos]);
	}
}

/*=============================================
ACTIVAR TABLA DE ARQUEOS
=============================================*/ 
$activarProductosVentas = new DatatableArqueosCaja();
$activarProductosVentas->mostrarTablaArqueosCaja();

?>
