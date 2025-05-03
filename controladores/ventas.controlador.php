<?php

class ControladorVentas{

	/*=============================================
	MOSTRAR VENTAS
	=============================================*/

	static public function ctrMostrarVentas($item, $valor){

		$tabla = "ventas";

		$respuesta = ModeloVentas::mdlMostrarVentas($tabla, $item, $valor);

		return $respuesta;

	}
	
	static public function ctrMostrarDetalleVentas($idVenta){

		$respuesta = ModeloVentas::mdlMostrarDetalleVentas($idVenta);
		return $respuesta;

	}

	/*=============================================
	CREAR VENTA
	=============================================*/

	static public function ctrCrearVenta(){

		if(isset($_POST["nuevaVenta"])){

			/*=============================================
			ACTUALIZAR LAS COMPRAS DEL CLIENTE Y REDUCIR EL STOCK Y AUMENTAR LAS VENTAS DE LOS PRODUCTOS
			=============================================*/
			$productos = json_decode($_POST["listaProductos"], true);
			if (empty($productos)) {

			echo '<script>

				swal({ 
					  type: "error",
					  title: "La venta no se  ejecuta si no hay productos Agregados",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					  }).then(function(result){
								if (result.value) {

								window.location = "crear-venta";

								}
							})

				</script>';

				return;
			}

			
			$listaProductos = json_decode($_POST["listaProductos"], true);

			$totalProductosComprados = array();

			foreach ($listaProductos as $key => $value) {

			   array_push($totalProductosComprados, $value["cantidad"]);
				
			   $tablaProductos = "productos";

			    $id = "id";
			    $valorIdProducto = $value["id"];
			    $orden = "id";

			    $traerProducto = ModeloProductos::mdlMostrarProductos($tablaProductos, $id, $valorIdProducto, $orden);

				$ventas = "ventas";
				$cantidadVentas = $value["cantidad"] + $traerProducto["ventas"];

			    $nuevasVentas = ModeloProductos::mdlActualizarProducto($tablaProductos, $ventas, $cantidadVentas, $valorIdProducto);

				$stock = "stock";
				$valorStock = $traerProducto["stock"] - $value["cantidad"];

				$nuevoStock = ModeloProductos::mdlActualizarProducto($tablaProductos, $stock, $valorStock, $valorIdProducto);

			}

			$tablaMeseros = "meseros";

			$item = "id";
			$valor = $_POST["seleccionarMesero"];

			//cambiar
			$estado=1;
			$traerMesero = ModeloMeseros::mdlMostrarMeseros($tablaMeseros, $item, $valor,$estado);

			$item1a = "compras";
			$valor1a = array_sum($totalProductosComprados) + $traerMesero["compras"];

			$comprasMesero = ModeloMeseros::mdlActualizarMesero($tablaMeseros, $item1a, $valor1a, $valor);

			$item1b = "ultima_compra";

			date_default_timezone_set('America/La_Paz');

			$fecha = date('Y-m-d');
			$hora = date('H:i:s');
			$valor1b = $fecha.' '.$hora;

			$fechaMesero = ModeloMeseros::mdlActualizarMesero($tablaMeseros, $item1b, $valor1b, $valor);
			$tipoPago = "";

			switch ($_POST["tipoPago"]) {
				case 1:
					$tipoPago = "Efectivo";
					break;
				case 2:
					$tipoPago = "QR";
					break;
				case 3:
					$tipoPago = "Transferencia";
					break;
					case 4:
						$tipoPago = "Qr y Efectivo(Mixto)";
						break;
				default:
					$tipoPago = "No Especificado";
					break;
			}
			$formaAtencion = "";

			switch ($_POST["formaAtencion"]) {
				case 1:
					$formaAtencion = "En Mesa";
					break;
				case 2:
					$formaAtencion = "Para Llevar";
					break;
				case 3:
					$formaAtencion = "Mixto";
					break;
				default:
					$formaAtencion = "No Especificado";
					break;
			}
			/*=============================================
			GUARDAR LA VENTA
			=============================================*/	
			
			$tabla = "ventas";

			$datos = array("id_vendedor"=>$_POST["idVendedor"],
						   "id_mesero"=>$_POST["seleccionarMesero"],
						   "id_cliente"=>$_POST["id_cliente"],
						   "codigo"=>$_POST["nuevaVenta"],
						   "productos"=>$_POST["listaProductos"],
						   "total"=>$_POST["totalVenta"],
						   "nota"=>strtoupper($_POST["nota"]),
						   "tipo_pago"=>$tipoPago,
						   "cambio"=>$_POST["nuevoCambioEfectivo"],
						   "forma_atencion"=>$formaAtencion,
						   "id_arqueo_caja" => $_POST["idArqueoCaja"],
							"total_pagado"=>$_POST["nuevoValorEfectivo"],
							"cliente"=>$_POST["cliente"]
						);
						
						
			// $respuesta = ModeloVentas::mdlIngresarVenta($tabla, $datos);
			$respuesta = ModeloVentas::mdlRegistrarVenta($tabla, $datos);

			
			if(is_array($respuesta) && $respuesta["status"] == "ok"){
				$arqueoActual = ModeloArqueo::mdlObtnerArqueoPorIDUsuario($_POST["idVendedor"]);

				ModeloArqueo::mdlRegistrarIngreso($arqueoActual ,$_POST["nuevaVenta"], $_POST["totalVenta"]);

				$imprimir = isset($_POST["sinImprimir"]) ? $_POST["sinImprimir"] : false;
				if ($imprimir == false) {
					echo json_encode([
						"status" => "ok",
						"idVenta" => $respuesta['idVenta'],
						"imprimir" => true
					]);
				} else {
					echo json_encode([
						"status" => "ok",
						"idVenta" => $respuesta['idVenta'],
						"imprimir" => false
					]);
				}
				return;
			} else {
				echo json_encode([
					"status" => "error",
					"mensaje" => is_string($respuesta) ? $respuesta : "Error desconocido"
				]);
				return;
			}

		}

	}

	

	/*=============================================
	ELIMINAR VENTA
	=============================================*/

	static public function ctrEliminarVenta(){

		if(isset($_GET["idVenta"]) ){
		
			$tabla = "ventas";

			$item = "id";
			$valor = $_GET["idVenta"];

			$traerVenta = ModeloVentas::mdlMostrarVentas($tabla, $item, $valor);
			

			/*=============================================
			VERIFICAR QUE LA CAJA O ARQUEO A LA QUE PERTENECE ESTE ABIERTO
			=============================================*/
			if (session_status() == PHP_SESSION_NONE) {
				session_start();
			}

			if ($_SESSION["idArqueoCaja"] != $traerVenta["id_arqueo_caja"]) {
			echo '<script>
					swal({ 
						type: "error",
						title: "La venta no puede eliminarse dado que su caja ha sido cerrada",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
						}).then(function(result){
									if (result.value) {
									window.location = "ventas";
									}
								})
					</script>';
				return;
			}
			
			/*=============================================
			ACTUALIZAR FECHA ÚLTIMA COMPRA
			=============================================*/

			$tablaMeseros = "meseros";

			$itemVentas = null;
			$valorVentas = null;

			$traerVentas = ModeloVentas::mdlMostrarVentas($tabla, $itemVentas, $valorVentas);

			$guardarFechas = array();


			// Verificar que $traerVenta y $traerVenta["id_mesero"] existen
			if (isset($traerVenta["id_mesero"]) && is_array($traerVentas)) {
				foreach ($traerVentas as $value) {
					if (isset($value["id_mesero"]) && $value["id_mesero"] == $traerVenta["id_mesero"]) {
						array_push($guardarFechas, $value["fecha"]);
					}
				}
			}

			
			if(count($guardarFechas) > 1){

				if($traerVenta["fecha"] > $guardarFechas[count($guardarFechas)-2]){

					$item = "ultima_compra";
					$valor = $guardarFechas[count($guardarFechas)-2];
					$valorIdMesero = $traerVenta["id_mesero"];

					$comprasMesero = ModeloMeseros::mdlActualizarMesero($tablaMeseros, $item, $valor, $valorIdMesero);

				}else{

					$item = "ultima_compra";
					$valor = $guardarFechas[count($guardarFechas)-1];
					$valorIdMesero = $traerVenta["id_mesero"];

					$comprasMesero = ModeloMeseros::mdlActualizarMesero($tablaMeseros, $item, $valor, $valorIdMesero);

				}


			}else{

				$item = "ultima_compra";
				$valor = "0000-00-00 00:00:00";
				$valorIdMesero = $traerVenta["id_mesero"];

				$comprasMesero = ModeloMeseros::mdlActualizarMesero($tablaMeseros, $item, $valor, $valorIdMesero);

			}
		
			/*=============================================
			FORMATEAR TABLA DE PRODUCTOS Y LA DE MESEROS
			=============================================*/

			// $productos =  json_decode($traerVenta["productos"], true);
			$productos = ModeloVentas::mdlMostrarDetalleVentas($traerVenta["id"]);
	
			$totalProductosComprados = array();

			foreach ($productos as $value) {

				array_push($totalProductosComprados, $value["cantidad"]);
				
				$tablaProductos = "productos";

				$item = "id";
				$valor = $value["id_producto"];
				$orden = "id";

				$traerProducto = ModeloProductos::mdlMostrarProductos($tablaProductos, $item, $valor, $orden);


				$item1a = "ventas";
				$valor1a = $traerProducto["ventas"] - $value["cantidad"];

				$nuevasVentas = ModeloProductos::mdlActualizarProducto($tablaProductos, $item1a, $valor1a, $valor);

				$item1b = "stock";
				$valor1b = $value["cantidad"] + $traerProducto["stock"];

				$nuevoStock = ModeloProductos::mdlActualizarProducto($tablaProductos, $item1b, $valor1b, $valor);

			}

			$tablaMeseros = "meseros";

			$itemMesero = "id";
			$valorMesero = $traerVenta["id_mesero"];

			//cambiar
            $estado=1;
			$traerMesero = ModeloMeseros::mdlMostrarMeseros($tablaMeseros, $itemMesero, $valorMesero,$estado);

			$item1a = "compras";
			$valor1a = $traerMesero["compras"] - array_sum($totalProductosComprados);

			$comprasMesero = ModeloMeseros::mdlActualizarMesero($tablaMeseros, $item1a, $valor1a, $valorMesero);

			/*=============================================
			ELIMINAR VENTA
			=============================================*/

			$respuesta = ModeloVentas::mdlEliminarVenta($tabla, $_GET["idVenta"]);
			
			if($respuesta == "ok"){
				ModeloArqueo::mdlEliminarIngreso($traerVenta["id_arqueo_caja"], $traerVenta["total"]);
				echo'<script>

				swal({
					  type: "success",
					  title: "La venta ha sido anulada correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					  }).then(function(result){
								if (result.value) {

								window.location = "ventas";

								}
							})

				</script>'; 
			}
		}

	}

	/*=============================================
	RANGO FECHAS
	=============================================*/	

	static public function ctrRangoFechasVentas($fechaInicial, $fechaFinal){

		$tabla = "ventas";

		$respuesta = ModeloVentas::mdlRangoFechasVentas($tabla, $fechaInicial, $fechaFinal);

		return $respuesta;
		
	}

	static public function ctrRangoFechasVentasRealizadas($fechaInicial, $fechaFinal, $estado = 1){

		$tabla = "ventas";

		$respuesta = ModeloVentas::mdlRangoFechaVentasRealizadas($tabla, $fechaInicial, $fechaFinal,$estado);

		return $respuesta;
		
	}

	/*=============================================
	DESCARGAR EXCEL
	=============================================*/

	public function ctrDescargarReporte(){

		if(isset($_GET["reporte"])){

			$tabla = "ventas";

			if(isset($_GET["fechaInicial"]) && isset($_GET["fechaFinal"])){

				$ventas = ModeloVentas::mdlRangoFechasVentas($tabla, $_GET["fechaInicial"], $_GET["fechaFinal"]);

			}else{

				$item = null;
				$valor = null;

				$ventas = ModeloVentas::mdlMostrarVentas($tabla, $item, $valor);

			}


			/*=============================================
			CREAMOS EL ARCHIVO DE EXCEL
			=============================================*/

			$Name = $_GET["reporte"].'.xls';

			header('Expires: 0');
			header('Cache-control: private');
			header("Content-type: application/vnd.ms-excel"); // Archivo de Excel
			header("Cache-Control: cache, must-revalidate"); 
			header('Content-Description: File Transfer');
			header('Last-Modified: '.date('D, d M Y H:i:s'));
			header("Pragma: public"); 
			header('Content-Disposition:; filename="'.$Name.'"');
			header("Content-Transfer-Encoding: binary");

			echo utf8_decode("<table border='0'> 

				<tr> 
					<td style='font-weight:bold; border:1px solid #eee;'>N°TICKET</td> 
					<td style='font-weight:bold; border:1px solid #eee;'>MESERO</td>
					<td style='font-weight:bold; border:1px solid #eee;'>USUARIO</td>
					<td style='font-weight:bold; border:1px solid #eee;'>CANTIDAD</td>
					<td style='font-weight:bold; border:1px solid #eee;'>DETALLE</td>
					<td style='font-weight:bold; border:1px solid #eee;'>TOTAL</td>		
					<td style='font-weight:bold; border:1px solid #eee;'>FECHA</td>		
					</tr>");

			foreach ($ventas as $row => $item){

				$mesero = ControladorMeseros::ctrMostrarMeseros("id", $item["id_mesero"]);
				$nombreMesero = $mesero ? $mesero["nombre"] : "Sin asignar";

				$vendedor = ControladorUsuarios::ctrMostrarUsuarios("id", $item["id_vendedor"]);
				$nombreVendedor = $vendedor ? $vendedor["nombre"] : "Sin asignar";

				echo utf8_decode("<tr>
					<td style='border:1px solid #eee;'>".$item["codigo"]."</td> 
					<td style='border:1px solid #eee;'>".$nombreMesero."</td>
					<td style='border:1px solid #eee;'>".$nombreVendedor."</td>
					<td style='border:1px solid #eee;'>");


			
				$productos = ModeloVentas::mdlMostrarDetalleVentas($item["id"]);

			 	foreach ($productos as $valueProductos) {
			 			
			 			echo utf8_decode($valueProductos["cantidad"]."<br>");
			 		}

			 	echo utf8_decode("</td><td style='border:1px solid #eee;'>");	

		 		foreach ($productos as $valueProductos) {
			 			
		 			echo utf8_decode($valueProductos["producto"]."<br>");
		 		
		 		}

		 		echo utf8_decode("</td>
				
					
					<td style='border:1px solid #eee;'>Bs ".number_format($item["total"],2)."</td>
				
					<td style='border:1px solid #eee;'>".substr($item["fecha"],0,10)."</td>		
		 			</tr>");


			}


			echo "</table>";

		}

	}


	/*=============================================
	SUMA TOTAL VENTAS
	=============================================*/

	static public function ctrSumaTotalVentas(){

		$tabla = "ventas";

		$respuesta = ModeloVentas::mdlSumaTotalVentas($tabla);

		return $respuesta;

	}
		/*=============================================
	SUMA TOTAL VENTAS DEL MES
	=============================================*/

	static public function ctrVentasTotalMes(){

		$tabla = "ventas";

		$respuesta = ModeloVentas::mdlVentasTotalMes($tabla);

		return $respuesta;

	}
	/*=============================================
	SUMA TOTAL DEL DIA
	=============================================*/

	static public function ctrVentasTotalDia(){

		$tabla = "ventas";

		$respuesta = ModeloVentas::mdlVentasTotalDia($tabla);

		return $respuesta;

	}
	/*=============================================
	rango de ventas:
	=============================================*/
	static public function ctrRangoFechasVentasPdf($fechaInicial, $fechaFinal,$id_proveedor,$idCategoria,$idCliente, $registroEliminados){

		$tabla = "ventas";
	
		$respuesta = ModeloVentas::mdlRangoFechasVentasPdf($tabla, $fechaInicial, $fechaFinal,$id_proveedor,$idCategoria,$idCliente, $registroEliminados);
	
		return $respuesta;
	}
	/*=============================================
	rango de ventas top meseros:
	=============================================*/
	static public function ctrRangoFechasVentasTopMeserosPdf($fechaInicial, $fechaFinal){

		$tabla = "ventas";
	
		$respuesta = ModeloVentas::mdlRangoFechasVentasTopMeseroPdf($tabla, $fechaInicial,$fechaFinal);
	
		return $respuesta;
	}
	/*=============================================
	rango fechas para obtener top productos mas vendidos:
	=============================================*/
	static public function ctrRangoFechasTopProductoMasVendidosPdf($fechaInicial, $fechaFinal){

		$tabla = "ventas";
	
		$respuesta = ModeloVentas::mdlRangoFechasTopProductoVendidos($tabla, $fechaInicial,$fechaFinal);
	
		return $respuesta;
	}
}