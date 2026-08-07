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

			// Evitar crear venta nueva si se está editando una cuenta pendiente
			if (isset($_POST["idVentaEditar"]) && intval($_POST["idVentaEditar"]) > 0) {
				echo json_encode([
					"status" => "error",
					"mensaje" => "Use el botón Actualizar cuenta para modificar una cuenta pendiente."
				]);
				return;
			}

            if(ModeloArqueo::mdlVerificarCajaAbiertaPorIdArqueo($_POST["idArqueoCaja"]) == false){
				if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
				$idArqueoActual = ModeloArqueo::mdlObtnerArqueoPorIDUsuario(1);
                // Guardamos el id en la variable de sesión
                $_SESSION["idArqueoCaja"] = $idArqueoActual["id"];
                $_SESSION["idCaja"] = $idArqueoActual["id_caja"];
			
				echo json_encode([
				 
					"status" => "recargar",
					"mensaje" =>  "Error desconocido"
				]);
				return;
			}
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

			/*=============================================
			RECALCULAR PROMOCIONES EN BACKEND (antes de pagos)
			=============================================*/
			$promoCalc = self::aplicarPromocionesAListaProductos($listaProductos);
			$listaProductos = $promoCalc["productos"];
			$_POST["listaProductos"] = json_encode($listaProductos);
			$totalNeto = $promoCalc["total_neto"];
			$totalBruto = $promoCalc["total_bruto"];
			$totalDescuento = $promoCalc["total_descuento"];
			$_POST["totalVenta"] = number_format($totalNeto, 2, '.', '');

			$tipoPago = "";
			$totalQR = 0;
			$totalEfectivo = 0;
			$totalPagado = 0;

			$estadoPago = (isset($_POST["estadoPago"]) && $_POST["estadoPago"] === "PENDIENTE") ? "PENDIENTE" : "PAGADA";
			$esPendiente = ($estadoPago === "PENDIENTE");

			if (!$esPendiente) {
			switch ($_POST["tipoPago"]) {
				case 1:
					$tipoPago = "Efectivo";
					$totalVenta = floatval($totalNeto);
					$totalEfectivo =  floatval($_POST["nuevoValorEfectivo"] ?? 0);
					$totalPagado = number_format($totalEfectivo, 2, '.', ',');
					$totalEfectivo = number_format($totalVenta, 2, '.', ',');
					break;
				case 2:
					$tipoPago = "QR";
					$totalVenta = floatval($totalNeto);
					$totalQR = floatval($_POST["nuevoValorQR"] ?? 0);
					$totalPagado = number_format($totalQR, 2, '.', ',');
					$totalQR = number_format($totalVenta, 2, '.', ',');
					break;
				case 3:
					$tipoPago = "Transferencia";
					break;
					case 4:
						$tipoPago = "Qr y Efectivo(Mixto)";

						$totalEfectivo = floatval($_POST["nuevoValorEfectivo"] ?? 0);
						$totalQR = floatval($_POST["nuevoValorQR"] ?? 0);
						$totalCambio = floatval($_POST["nuevoCambioEfectivo"] ?? 0);
						$totalPagado = $totalEfectivo + $totalQR;
						
						//Cambio es mayor a 0, entonces el total pagado es igual al total de la venta
						if ($totalCambio > 0){
							$totalEfectivo = number_format($totalEfectivo - $totalCambio, 2, '.', ',');
						}		
					
						break;
				default:
					$tipoPago = "No Especificado";
					break;
			}
			} else {
				$tipoPago = "";
				$totalQR = 0;
				$totalEfectivo = 0;
				$totalPagado = 0;
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
			$ultimoNroTicket=0;
			$ultimoNroTicket = ModeloArqueo::mdlObtenerUltimoNroTicketDeVentas($_POST["idArqueoCaja"]);
			$ultimoNroTicket++;
			

		

			$datos = array("id_vendedor"=>$_POST["idVendedor"],
						   "id_mesero"=>$_POST["seleccionarMesero"],
						   "id_cliente"=>$_POST["id_cliente"],
						   "codigo"=>$ultimoNroTicket,
						   "productos"=>$_POST["listaProductos"],
						   "total"=>number_format($totalNeto, 2, '.', ''),
						   "total_bruto"=>number_format($totalBruto, 2, '.', ''),
						   "total_descuento"=>number_format($totalDescuento, 2, '.', ''),
						   "nota"=>strtoupper($_POST["nota"]),
						   "tipo_pago"=>$tipoPago,
						   "cambio"=>$esPendiente ? 0 : $_POST["nuevoCambioEfectivo"],
						   "forma_atencion"=>$formaAtencion,
						   "id_arqueo_caja" => $_POST["idArqueoCaja"],
							"total_pagado"=>number_format($totalPagado, 2, '.', ','),
							"total_efectivo"=>number_format($totalEfectivo, 2, '.', ','),
							"total_qr"=>number_format($totalQR, 2, '.', ','),
							"cliente"=>$_POST["cliente"],
							"estado_pago"=>$estadoPago,
							"fecha_pago"=>$esPendiente ? null : ($fecha.' '.$hora)
						);
						
						
			// $respuesta = ModeloVentas::mdlIngresarVenta($tabla, $datos);
			$respuesta = ModeloVentas::mdlRegistrarVenta($tabla, $datos);

			
			if(is_array($respuesta) && $respuesta["status"] == "ok"){
				if (!$esPendiente) {
					$arqueoActual = ModeloArqueo::mdlObtnerArqueoPorIDUsuario($_POST["idVendedor"]);
					ModeloArqueo::mdlRegistrarIngreso($arqueoActual ,$ultimoNroTicket, number_format($totalNeto, 2, '.', ''),$totalEfectivo, $totalQR);
				}
				ModeloArqueo::mdlSincronizarCuentasPendientesEnArqueoAbierto($_POST["idArqueoCaja"]);

				$mensaje = $esPendiente
					? "Cuenta pendiente registrada correctamente."
					: "La venta ha sido registrada correctamente";

				echo json_encode([
					"status" => "ok",
					"mensaje" => $mensaje,
					"idVenta" => $respuesta["idVenta"],
					"esPendiente" => $esPendiente
				]);
				return ;
				
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
				if (isset($traerVenta["estado_pago"]) && $traerVenta["estado_pago"] === "PAGADA") {
					ModeloArqueo::mdlEliminarIngreso($traerVenta["id_arqueo_caja"], $traerVenta["total"], $traerVenta["total_efectivo"], $traerVenta["total_qr"]);
				} elseif (isset($traerVenta["estado_pago"]) && $traerVenta["estado_pago"] === "PENDIENTE" && !empty($traerVenta["id_arqueo_caja"])) {
					ModeloArqueo::mdlSincronizarCuentasPendientesEnArqueoAbierto($traerVenta["id_arqueo_caja"]);
				}
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

	static public function ctrRangoFechasVentasRealizadas($fechaInicial, $fechaFinal, $estado = 1, $estadoPago = null, $idMesero = null){

		$tabla = "ventas";

		$respuesta = ModeloVentas::mdlRangoFechaVentasRealizadas($tabla, $fechaInicial, $fechaFinal, $estado, $estadoPago, $idMesero);

		return $respuesta;
		
	}

	/*=============================================
	ACTUALIZAR CUENTA PENDIENTE
	=============================================*/
	static public function ctrActualizarCuentaPendiente(){

		if(!isset($_POST["actualizarCuentaPendiente"])){
			return;
		}

		if(ModeloArqueo::mdlVerificarCajaAbiertaPorIdArqueo($_POST["idArqueoCaja"]) == false){
			echo json_encode([
				"status" => "error",
				"mensaje" => "No hay caja abierta. No se puede actualizar la cuenta."
			]);
			return;
		}

		$listaProductos = json_decode($_POST["listaProductos"], true);
		if (empty($listaProductos)) {
			echo json_encode([
				"status" => "error",
				"mensaje" => "La cuenta debe tener al menos un producto."
			]);
			return;
		}

		$idVenta = intval($_POST["idVentaEditar"]);
		$ventaActual = ModeloVentas::mdlMostrarVentas("ventas", "id", $idVenta);

		if (!$ventaActual || $ventaActual["estado_pago"] !== "PENDIENTE" || $ventaActual["estado"] != 1) {
			echo json_encode([
				"status" => "error",
				"mensaje" => "La cuenta no está pendiente o no existe."
			]);
			return;
		}

		if (empty($ventaActual["id_arqueo_caja"]) || !ModeloArqueo::mdlVerificarCajaAbiertaPorIdArqueo($ventaActual["id_arqueo_caja"])) {
			echo json_encode([
				"status" => "error",
				"mensaje" => "Esta cuenta pertenece a una caja cerrada y no puede editarse."
			]);
			return;
		}

		if (intval($ventaActual["id_arqueo_caja"]) !== intval($_POST["idArqueoCaja"])) {
			echo json_encode([
				"status" => "error",
				"mensaje" => "Esta cuenta pertenece a una caja diferente y no puede editarse en la caja actual."
			]);
			return;
		}

		$detalleAnterior = ModeloVentas::mdlMostrarDetalleVentas($idVenta);
		self::ajustarInventarioPorDiferencia($detalleAnterior, $listaProductos);
		self::ajustarMeseroPorDiferencia(
			$ventaActual["id_mesero"],
			intval($_POST["seleccionarMesero"]),
			$detalleAnterior,
			$listaProductos
		);

		$formaAtencion = self::obtenerFormaAtencionTexto($_POST["formaAtencion"]);

		$promoCalc = self::aplicarPromocionesAListaProductos($listaProductos);
		$listaProductos = $promoCalc["productos"];

		$datos = array(
			"id_venta" => $idVenta,
			"id_mesero" => $_POST["seleccionarMesero"],
			"id_cliente" => $_POST["id_cliente"],
			"total" => number_format($promoCalc["total_neto"], 2, '.', ''),
			"total_bruto" => number_format($promoCalc["total_bruto"], 2, '.', ''),
			"total_descuento" => number_format($promoCalc["total_descuento"], 2, '.', ''),
			"nota" => strtoupper($_POST["nota"]),
			"forma_atencion" => $formaAtencion,
			"productos" => json_encode($listaProductos),
			"cliente" => $_POST["cliente"]
		);

		$respuesta = ModeloVentas::mdlActualizarCuentaPendiente($datos);

		if (is_array($respuesta) && $respuesta["status"] === "ok") {
			ModeloArqueo::mdlSincronizarCuentasPendientesEnArqueoAbierto($ventaActual["id_arqueo_caja"]);
			echo json_encode([
				"status" => "ok",
				"mensaje" => "Cuenta pendiente actualizada correctamente.",
				"idVenta" => $respuesta["idVenta"],
				"idsDetalleNuevos" => $respuesta["idsDetalleNuevos"]
			]);
			return;
		}

		echo json_encode([
			"status" => "error",
			"mensaje" => is_string($respuesta) ? $respuesta : "Error al actualizar la cuenta."
		]);
	}

	/*=============================================
	COBRAR CUENTA PENDIENTE
	=============================================*/
	static public function ctrCobrarCuentaPendiente(){

		if(!isset($_POST["cobrarCuentaPendiente"])){
			return;
		}

		$idVenta = intval($_POST["idVentaCobrar"]);
		$venta = ModeloVentas::mdlMostrarVentas("ventas", "id", $idVenta);

		if (!$venta || $venta["estado_pago"] !== "PENDIENTE" || $venta["estado"] != 1) {
			echo json_encode([
				"status" => "error",
				"mensaje" => "La cuenta no está pendiente o ya fue cobrada."
			]);
			return;
		}

		if (empty($venta["id_arqueo_caja"])) {
			echo json_encode([
				"status" => "error",
				"mensaje" => "Esta cuenta no tiene una caja asociada y no puede cobrarse."
			]);
			return;
		}

		$idArqueoVenta = intval($venta["id_arqueo_caja"]);
		if (!ModeloArqueo::mdlVerificarCajaAbiertaPorIdArqueo($idArqueoVenta)) {
			echo json_encode([
				"status" => "error",
				"mensaje" => "Esta cuenta pertenece a una caja cerrada y no puede cobrarse."
			]);
			return;
		}

		$arqueoActual = ModeloArqueo::mdlObtnerArqueoPorIDUsuario($_POST["idVendedorCobro"]);
		if (!$arqueoActual || !ModeloArqueo::mdlVerificarCajaAbiertaPorIdArqueo($arqueoActual["id"])) {
			echo json_encode([
				"status" => "error",
				"mensaje" => "No hay caja abierta. No se puede cobrar la cuenta."
			]);
			return;
		}

		if (intval($arqueoActual["id"]) !== $idArqueoVenta) {
			echo json_encode([
				"status" => "error",
				"mensaje" => "Esta cuenta pertenece a una caja cerrada y no puede cobrarse."
			]);
			return;
		}

		$tipoPago = "";
		$totalQR = 0;
		$totalEfectivo = 0;
		$totalPagado = 0;
		$totalVenta = floatval($_POST["totalVentaCobro"] ?? $venta["total"]);

		switch ($_POST["tipoPagoCobro"]) {
			case 1:
				$tipoPago = "Efectivo";
				$totalEfectivo = number_format($totalVenta, 2, '.', ',');
				$totalPagado = number_format(floatval($_POST["nuevoValorEfectivoCobro"] ?? 0), 2, '.', ',');
				break;
			case 2:
				$tipoPago = "QR";
				$totalQR = number_format($totalVenta, 2, '.', ',');
				$totalPagado = number_format(floatval($_POST["nuevoValorQRCobro"] ?? 0), 2, '.', ',');
				break;
			case 4:
				$tipoPago = "Qr y Efectivo(Mixto)";
				$efectivo = floatval($_POST["nuevoValorEfectivoCobro"] ?? 0);
				$qr = floatval($_POST["nuevoValorQRCobro"] ?? 0);
				$totalCambio = floatval($_POST["nuevoCambioEfectivoCobro"] ?? 0);
				$totalPagado = $efectivo + $qr;
				if ($totalCambio > 0) {
					$totalEfectivo = number_format($efectivo - $totalCambio, 2, '.', ',');
				} else {
					$totalEfectivo = number_format($efectivo, 2, '.', ',');
				}
				$totalQR = number_format($qr, 2, '.', ',');
				break;
			default:
				echo json_encode([
					"status" => "error",
					"mensaje" => "Tipo de pago no válido."
				]);
				return;
		}

		date_default_timezone_set('America/La_Paz');
		$fechaPago = date('Y-m-d H:i:s');

		$datos = array(
			"id_venta" => $idVenta,
			"fecha_pago" => $fechaPago,
			"tipo_pago" => $tipoPago,
			"total_efectivo" => $totalEfectivo,
			"total_qr" => $totalQR,
			"total_pagado" => number_format($totalPagado, 2, '.', ','),
			"cambio" => $_POST["nuevoCambioEfectivoCobro"] ?? 0,
			"total" => $totalVenta,
			"id_arqueo_caja" => $idArqueoVenta
		);

		$respuesta = ModeloVentas::mdlCobrarCuentaPendiente($datos);

		if (is_array($respuesta) && $respuesta["status"] === "ok") {
			$ultimoNroTicket = ModeloArqueo::mdlObtenerUltimoNroTicketDeVentas($idArqueoVenta);
			ModeloArqueo::mdlRegistrarIngreso(
				$arqueoActual,
				$ultimoNroTicket,
				$totalVenta,
				$totalEfectivo,
				$totalQR
			);
			ModeloArqueo::mdlSincronizarCuentasPendientesEnArqueoAbierto($idArqueoVenta);

			echo json_encode([
				"status" => "ok",
				"mensaje" => "Cuenta cobrada correctamente.",
				"idVenta" => $respuesta["idVenta"]
			]);
			return;
		}

		$mensajeError = is_string($respuesta) ? preg_replace('/^error:\s*/', '', $respuesta) : "Error al cobrar la cuenta.";
		echo json_encode([
			"status" => "error",
			"mensaje" => $mensajeError
		]);
	}

	/*=============================================
	RESUMEN CUENTAS PENDIENTES
	=============================================*/
	static public function ctrResumenCuentasPendientes($idArqueo = null){
		if ($idArqueo !== null) {
			return ModeloVentas::mdlResumenCuentasPendientesPorArqueo(intval($idArqueo));
		}
		return ModeloVentas::mdlResumenCuentasPendientes();
	}

	static public function ctrResumenCuentasPendientesCajasCerradas(){
		return ModeloVentas::mdlResumenCuentasPendientesCajasCerradas();
	}

	private static function obtenerFormaAtencionTexto($formaAtencion){
		switch ($formaAtencion) {
			case 1:
				return "En Mesa";
			case 2:
				return "Para Llevar";
			case 3:
				return "Mixto";
			default:
				return "No Especificado";
		}
	}

	private static function sumarCantidadesPorProducto($detalle){
		$mapa = [];
		foreach ($detalle as $linea) {
			$idProducto = isset($linea["id_producto"]) ? $linea["id_producto"] : $linea["id"];
			$mapa[$idProducto] = ($mapa[$idProducto] ?? 0) + intval($linea["cantidad"]);
		}
		return $mapa;
	}

	private static function sumarCantidadesDesdeJson($productos){
		$mapa = [];
		foreach ($productos as $producto) {
			$idProducto = $producto["id"];
			$mapa[$idProducto] = ($mapa[$idProducto] ?? 0) + intval($producto["cantidad"]);
		}
		return $mapa;
	}

	private static function ajustarInventarioPorDiferencia($detalleAnterior, $productosNuevos){
		$antes = self::sumarCantidadesPorProducto($detalleAnterior);
		$despues = self::sumarCantidadesDesdeJson($productosNuevos);
		$idsProductos = array_unique(array_merge(array_keys($antes), array_keys($despues)));

		foreach ($idsProductos as $idProducto) {
			$qtyAntes = $antes[$idProducto] ?? 0;
			$qtyDespues = $despues[$idProducto] ?? 0;
			$diff = $qtyDespues - $qtyAntes;

			if ($diff === 0) {
				continue;
			}

			$tablaProductos = "productos";
			$traerProducto = ModeloProductos::mdlMostrarProductos($tablaProductos, "id", $idProducto, "id");
			if (!$traerProducto) {
				continue;
			}

			$nuevoStock = $traerProducto["stock"] - $diff;
			$nuevasVentas = $traerProducto["ventas"] + $diff;

			ModeloProductos::mdlActualizarProducto($tablaProductos, "stock", $nuevoStock, $idProducto);
			ModeloProductos::mdlActualizarProducto($tablaProductos, "ventas", $nuevasVentas, $idProducto);
		}
	}

	private static function ajustarMeseroPorDiferencia($idMeseroAnterior, $idMeseroNuevo, $detalleAnterior, $productosNuevos){
		$totalAnterior = array_sum(array_column($detalleAnterior, "cantidad"));
		$totalNuevo = 0;
		foreach ($productosNuevos as $producto) {
			$totalNuevo += intval($producto["cantidad"]);
		}

		$tablaMeseros = "meseros";
		$estado = 1;

		if ($idMeseroAnterior != $idMeseroNuevo) {
			$meseroAnterior = ModeloMeseros::mdlMostrarMeseros($tablaMeseros, "id", $idMeseroAnterior, $estado);
			if ($meseroAnterior) {
				ModeloMeseros::mdlActualizarMesero($tablaMeseros, "compras", $meseroAnterior["compras"] - $totalAnterior, $idMeseroAnterior);
			}
			$meseroNuevo = ModeloMeseros::mdlMostrarMeseros($tablaMeseros, "id", $idMeseroNuevo, $estado);
			if ($meseroNuevo) {
				ModeloMeseros::mdlActualizarMesero($tablaMeseros, "compras", $meseroNuevo["compras"] + $totalNuevo, $idMeseroNuevo);
			}
		} else {
			$diff = $totalNuevo - $totalAnterior;
			if ($diff !== 0) {
				$mesero = ModeloMeseros::mdlMostrarMeseros($tablaMeseros, "id", $idMeseroNuevo, $estado);
				if ($mesero) {
					ModeloMeseros::mdlActualizarMesero($tablaMeseros, "compras", $mesero["compras"] + $diff, $idMeseroNuevo);
				}
			}
		}
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

				$ventas = ModeloVentas::mdlRangoFechasVentas($tabla, null, null);

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
	static public function ctrRangoFechasVentasPdf($fechaInicial, $fechaFinal,$id_proveedor,$idCategoria,$idCliente, $registroEliminados, $tipoPago = "0", $estadoPago = "0"){

		$tabla = "ventas";
	
		$respuesta = ModeloVentas::mdlRangoFechasVentasPdf($tabla, $fechaInicial, $fechaFinal,$id_proveedor,$idCategoria,$idCliente, $registroEliminados, $tipoPago, $estadoPago);
	
		return $respuesta;
	}
	/*=============================================
	rango de ventas top meseros:
	=============================================*/
	static public function ctrRangoFechasVentasTopMeserosPdf($fechaInicial, $fechaFinal, $idMesero = 0, $idCategoria = 0){

		$tabla = "ventas";
	
		$respuesta = ModeloVentas::mdlRangoFechasVentasTopMeseroPdf($tabla, $fechaInicial,$fechaFinal, $idMesero, $idCategoria);
	
		return $respuesta;
	}
	/*=============================================
	rango fechas para obtener top productos mas vendidos:
	=============================================*/
	static public function ctrRangoFechasTopProductoMasVendidosPdf($fechaInicial, $fechaFinal, $idCategoria = 0){

		$tabla = "ventas";
	
		$respuesta = ModeloVentas::mdlRangoFechasTopProductoVendidos($tabla, $fechaInicial,$fechaFinal, $idCategoria);
	
		return $respuesta;
	}

	static public function aplicarPromocionesAListaProductos($listaProductos)
	{
		$totalBruto = 0;
		$totalDescuento = 0;

		if (!is_array($listaProductos)) {
			$listaProductos = array();
		}

		$itemsCalc = array();
		foreach ($listaProductos as $i => $producto) {
			$id = intval(isset($producto["id"]) ? $producto["id"] : 0);
			$cant = intval(isset($producto["cantidad"]) ? $producto["cantidad"] : 0);
			$traer = ModeloProductos::mdlMostrarProductos("productos", "id", $id, "id");
			if ($traer) {
				$precioOriginal = round(floatval($traer["precio_venta"]), 2);
				$listaProductos[$i]["precioCompra"] = $traer["precio_compra"];
			} else {
				$precioOriginal = round(floatval(isset($producto["precioOriginal"]) ? $producto["precioOriginal"] : (isset($producto["precio"]) ? $producto["precio"] : 0)), 2);
			}
			$listaProductos[$i]["precioOriginal"] = $precioOriginal;
			$totalBruto += round($precioOriginal * $cant, 2);
			$itemsCalc[] = array("id" => $id, "cantidad" => $cant, "precio" => $precioOriginal);
		}

		$mapa = array();
		if (class_exists("ControladorPromociones")) {
			$mapa = ControladorPromociones::ctrCalcularPromocionesParaVenta($itemsCalc);
		}

		foreach ($listaProductos as $i => $producto) {
			$id = intval($producto["id"]);
			$cant = intval($producto["cantidad"]);
			$precioOriginal = floatval($producto["precioOriginal"]);
			$info = isset($mapa[$id]) ? $mapa[$id] : null;
			$descUnit = 0;
			$descTotal = 0;
			$precioFinal = $precioOriginal;
			$subtotalOriginal = round($precioOriginal * $cant, 2);
			$promo = array(
				"id_promocion" => null,
				"id_intervalo_promocion" => null,
				"nombre_promocion" => null,
				"tipo_descuento" => null,
				"valor_descuento" => null,
				"descuento_unitario" => 0,
				"descuento_total" => 0,
				"precio_original" => $precioOriginal,
				"precio_unitario_final" => $precioOriginal,
				"subtotal_original" => $subtotalOriginal,
				"subtotal_final" => $subtotalOriginal
			);

			if ($info && !empty($info["id_promocion"]) && floatval($info["descuento_unitario"]) > 0) {
				$descUnit = round(floatval($info["descuento_unitario"]), 2);
				if ($descUnit > $precioOriginal) {
					$descUnit = $precioOriginal;
				}
				$precioFinal = round($precioOriginal - $descUnit, 2);
				if ($precioFinal < 0) {
					$precioFinal = 0;
					$descUnit = $precioOriginal;
				}
				$descTotal = round($descUnit * $cant, 2);
				if ($descTotal > $subtotalOriginal) {
					$descTotal = $subtotalOriginal;
					$precioFinal = 0;
				}
				$promo = array(
					"id_promocion" => $info["id_promocion"],
					"id_intervalo_promocion" => $info["id_intervalo_promocion"],
					"nombre_promocion" => $info["nombre_promocion"],
					"tipo_descuento" => $info["tipo_descuento"],
					"valor_descuento" => $info["valor_descuento"],
					"descuento_unitario" => $descUnit,
					"descuento_total" => $descTotal,
					"precio_original" => $precioOriginal,
					"precio_unitario_final" => $precioFinal,
					"subtotal_original" => $subtotalOriginal,
					"subtotal_final" => round($subtotalOriginal - $descTotal, 2)
				);
			}

			$totalDescuento += $descTotal;
			$listaProductos[$i]["precio"] = $precioFinal;
			$listaProductos[$i]["total"] = round($precioFinal * $cant, 2);
			$listaProductos[$i]["promo"] = $promo;
		}

		$totalBruto = round($totalBruto, 2);
		$totalDescuento = round($totalDescuento, 2);
		$totalNeto = round($totalBruto - $totalDescuento, 2);
		if ($totalNeto < 0) {
			$totalNeto = 0;
		}

		return array(
			"productos" => $listaProductos,
			"total_bruto" => $totalBruto,
			"total_descuento" => $totalDescuento,
			"total_neto" => $totalNeto
		);
	}
}
