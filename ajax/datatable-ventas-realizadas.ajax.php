<?php

require_once "../includes/sesion-permisos.php";
require_once "../controladores/ventas.controlador.php";
require_once "../modelos/ventas.modelo.php";
require_once "../modelos/mesas.modelo.php";
require_once "../modelos/empresa.modelo.php";

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
		$gestionMesas = ModeloEmpresa::mdlFuncionActiva("venta.gestion_mesas");
		$cobrarDespues = ModeloEmpresa::mdlFuncionActiva("venta.cobrar_despues");
		$idMesa = ($gestionMesas && isset($_GET["idMesa"])) ? $_GET["idMesa"] : "0";

		
		date_default_timezone_set('America/La_Paz');

	   	$ventas = ControladorVentas::ctrRangoFechasVentasRealizadas($fechaInicial, $fechaFinal, 1, $estadoPago, $idMesero, $idMesa);
	
		
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
			  if (Permisos::tiene("ventas.imprimir")) {
			  	$menuAcciones .= "<li><a href='javascript:void(0)' class='btnImprimirFactura action-print' codigoVenta='".$ventas[$i]["id"]."'><i class='fa fa-print'></i> Imprimir</a></li>";
			  }

			  if ($cobrarDespues && isset($ventas[$i]["estado_pago"]) && $ventas[$i]["estado_pago"] === "PENDIENTE") {
				$cajaAbierta = isset($ventas[$i]["estado_arqueo"]) && $ventas[$i]["estado_arqueo"] === "abierta";
				if ($cajaAbierta && Permisos::tiene("ventas.editar")) {
					$menuAcciones .= "<li><a class='btnEditarCuenta action-edit' href='index.php?ruta=crear-venta&editarCuenta=".$ventas[$i]["id"]."'><i class='fa fa-pencil'></i> Editar cuenta</a></li>";
				}
			  }

			  if (Permisos::tiene("ventas.eliminar")) {
				$menuAcciones .= "<li><a href='javascript:void(0)' class='btnEliminarVenta action-delete' idVenta='".$ventas[$i]["id"]."'><i class='fa fa-times'></i> Eliminar</a></li>";
			  }

			  $botones = "
			  <div class='acciones-ventas-wrap'>";

			  $estadoPagoLabel = "PAGADO";
			  $estadoPagoClass = "label-success";
			  if (isset($ventas[$i]["estado_pago"]) && $ventas[$i]["estado_pago"] === "PENDIENTE") {
				$cajaAbierta = isset($ventas[$i]["estado_arqueo"]) && $ventas[$i]["estado_arqueo"] === "abierta";
				$estadoPagoLabel = "PENDIENTE";
				$estadoPagoClass = $cajaAbierta ? "label-danger" : "label-warning";
				if ($cobrarDespues) {
					if ($cajaAbierta) {
						if (Permisos::tiene("ventas.cobrar")) {
						$idMesaCobro = !empty($ventas[$i]["id_mesa"]) ? intval($ventas[$i]["id_mesa"]) : 0;
						$etiquetaMesaCobro = "";
						if ($idMesaCobro > 0) {
							if (!class_exists("ModeloMesas")) {
								require_once "../modelos/mesas.modelo.php";
							}
							$textoMesas = ModeloMesas::mdlTextoMesasVenta($ventas[$i]);
							$etiquetaMesaCobro = "LIBERAR " . ($textoMesas !== "" ? $textoMesas : ("Mesa " . $idMesaCobro));
						}
						$botones .= "
						<button type='button' class='btn btn-success btn-sm btnCobrarCuenta action-charge-button' idVenta='".$ventas[$i]["id"]."' totalVenta='".$ventas[$i]["total"]."' codigoVenta='".$ventas[$i]["codigo"]."' idMesa='".$idMesaCobro."' etiquetaLiberarMesa='".htmlspecialchars($etiquetaMesaCobro, ENT_QUOTES, "UTF-8")."'>
						  <i class='fa fa-money'></i> Cobrar
						</button>";
						}
					} else {
						$botones .= "
						<button type='button' class='btn btn-warning btn-sm btnCobrarCajaCerrada action-charge-locked' idVenta='".$ventas[$i]["id"]."' codigoVenta='".$ventas[$i]["codigo"]."' title='La caja de esta venta ya fue cerrada'>
						  <i class='fa fa-lock'></i> Cobrar
						</button>";
					}
				}
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

			$fila = array(
			      ($i+1),
				  ltrim($ventas[$i]["codigo"], '0')
			);
			if ($gestionMesas) {
				$textoMesa = "-";
				if (!empty($ventas[$i]["id_mesa"])) {
					$textoMesa = ModeloMesas::mdlTextoMesasVenta($ventas[$i]);
					if ($textoMesa === "") {
						$textoMesa = !empty($ventas[$i]["mesa_numero"])
							? ("Mesa " . intval($ventas[$i]["mesa_numero"]))
							: ($ventas[$i]["mesa_nombre"] ?? "-");
					}
				}
				$fila[] = $textoMesa;
			}
			$fila[] = $ventas[$i]["mesero"];
			$fila[] = $ventas[$i]["cliente"];
			$fila[] = $ventas[$i]["tipo_pago"] ? $ventas[$i]["tipo_pago"] : "-";
			$fila[] = $ventas[$i]["usuario"];
			$fila[] = number_format($ventas[$i]["total"], 2);
			$fila[] = "<span class='label ".$estadoPagoClass."'>".$estadoPagoLabel."</span>";
			$fila[] = $ventas[$i]["fecha"];
			$fila[] = $botones;
		  	$datos[] = $fila;
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
