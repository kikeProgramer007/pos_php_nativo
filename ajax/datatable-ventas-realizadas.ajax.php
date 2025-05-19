<?php

require_once "../controladores/ventas.controlador.php";
require_once "../modelos/ventas.modelo.php";

class TablaProductosVentas{

 	/*=============================================
 	 MOSTRAR LA TABLA DE PRODUCTOS
  	=============================================*/ 

	public function mostrarTablaProductosVentas(){
	

  
		  if (isset($_GET["fechaInicial"]) && isset($_GET["fechaFinal"])) {
			$fechaInicial = $_GET["fechaInicial"];
			$fechaFinal = $_GET["fechaFinal"];
		} else {
			$fechaInicial = null;
			$fechaFinal = null;
		}

		
		date_default_timezone_set('America/La_Paz');

	   	$ventas = ControladorVentas::ctrRangoFechasVentasRealizadas($fechaInicial,$fechaFinal);
	
		
  		if(count($ventas) == 0){
  			// En caso de que no haya compras, retornamos un JSON vacío
  			echo json_encode(["data" => []]);
		  	return;
  		}
		
		
  		// Creamos un array para almacenar los datos
  		$datos = [];

  		for($i = 0; $i < count($ventas); $i++){

	
			  $botones ="<div class='btn-group'>";
			  /*=============================================
			  TRAEMOS LAS ACCIONES
			  =============================================*/
			  $botones.= "<button class='btn btn-info btnImprimirFactura' codigoVenta='".$ventas[$i]["id"]."'><i class='fa fa-print'></i></button>"; 

			  if ((isset($_GET["perfilOculto"]) && $_GET["perfilOculto"] == "Administrador") || (isset($_GET["perfilOculto"]) && $_GET["perfilOculto"] == "Supervisor")) {
				$botones .=  "<button class='btn btn-danger btnEliminarVenta' idVenta='".$ventas[$i]["id"]."'><i class='fa fa-times'></i></button>";
			}
			
			
			 $botones.="</div>";

		  	// Formateamos cada registro de compra como un array
		  	$datos[] = [
			      ($i+1),
				  ltrim($ventas[$i]["codigo"], '0'),
				  $ventas[$i]["mesero"],
                  $ventas[$i]["usuario"],
			      number_format($ventas[$i]["total"], 2),
				  $ventas[$i]["fecha"],
			      $botones
		  	];
  		}

  		// Convertimos los datos a JSON y los enviamos como respuesta
  		echo json_encode(["data" => $datos]);
	}
}

/*=============================================
ACTIVAR TABLA DE PRODUCTOS
=============================================*/ 
$activarProductosVentas1 = new TablaProductosVentas();
$activarProductosVentas1->mostrarTablaProductosVentas();

?>
