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

		$estadoPago = isset($_GET["estadoPago"]) ? $_GET["estadoPago"] : "todos";
		$idMesero = isset($_GET["idMesero"]) ? $_GET["idMesero"] : "0";

		
		date_default_timezone_set('America/La_Paz');

	   	$ventas = ControladorVentas::ctrRangoFechasVentasRealizadas($fechaInicial, $fechaFinal, 1, $estadoPago, $idMesero);
	
		
  		if(count($ventas) == 0){
  			// En caso de que no haya compras, retornamos un JSON vacío
  			echo json_encode(["data" => []]);
		  	return;
  		}
		
		
  		// Creamos un array para almacenar los datos
  		$datos = [];

  		for($i = 0; $i < count($ventas); $i++){

	
			  $menuAcciones = "";

			  /*=============================================
			  TRAEMOS LAS ACCIONES
			  =============================================*/
			  $menuAcciones .= "<li><a href='javascript:void(0)' class='btnVerFactura action-view' codigoVenta='".$ventas[$i]["id"]."'><i class='fa fa-eye'></i> Ver</a></li>";
			  $menuAcciones .= "<li><a href='javascript:void(0)' class='btnImprimirFactura action-print' codigoVenta='".$ventas[$i]["id"]."'><i class='fa fa-print'></i> Imprimir</a></li>";

			  if (isset($ventas[$i]["estado_pago"]) && $ventas[$i]["estado_pago"] === "PENDIENTE") {
				$menuAcciones .= "<li><a class='btnEditarCuenta action-edit' href='index.php?ruta=crear-venta&editarCuenta=".$ventas[$i]["id"]."'><i class='fa fa-pencil'></i> Editar cuenta</a></li>";
			  }

			  if ((isset($_GET["perfilOculto"]) && $_GET["perfilOculto"] == "Administrador") || (isset($_GET["perfilOculto"]) && $_GET["perfilOculto"] == "Supervisor")) {
				$menuAcciones .= "<li><a href='javascript:void(0)' class='btnEliminarVenta action-delete' idVenta='".$ventas[$i]["id"]."'><i class='fa fa-times'></i> Eliminar</a></li>";
			  }

			  $botones = "
			  <div class='acciones-ventas-wrap'>";

			  if (isset($ventas[$i]["estado_pago"]) && $ventas[$i]["estado_pago"] === "PENDIENTE") {
				$botones .= "
				<button type='button' class='btn btn-success btn-sm btnCobrarCuenta action-charge-button' idVenta='".$ventas[$i]["id"]."' totalVenta='".$ventas[$i]["total"]."' codigoVenta='".$ventas[$i]["codigo"]."'>
				  <i class='fa fa-money'></i> Cobrar
				</button>";
			  }

			  $botones .= "
			  <div class='btn-group'>
				<button type='button' class='btn btn-default btn-sm dropdown-toggle action-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false' title='Acciones'>
				  <i class='fa fa-bars'></i>
				</button>
				<ul class='dropdown-menu dropdown-menu-right'>
				  ".$menuAcciones."
				</ul>
			  </div>
			  </div>";

			$estadoPagoLabel = "CUENTA PAGADA";
			$estadoPagoClass = "label-success";
			if (isset($ventas[$i]["estado_pago"]) && $ventas[$i]["estado_pago"] === "PENDIENTE") {
				$estadoPagoLabel = "CUENTA PENDIENTE";
				$estadoPagoClass = "label-danger";
			}

		  	// Formateamos cada registro de compra como un array
		  	$datos[] = [
			      ($i+1),
				  ltrim($ventas[$i]["codigo"], '0'),
				  $ventas[$i]["mesero"],
				  $ventas[$i]["cliente"],
				  $ventas[$i]["tipo_pago"] ? $ventas[$i]["tipo_pago"] : "-",
                  $ventas[$i]["usuario"],
			      number_format($ventas[$i]["total"], 2),
				  "<span class='label ".$estadoPagoClass."'>".$estadoPagoLabel."</span>",
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
