<?php

require_once __DIR__ . "/../modelos/moneda.php";

class ControladorCompras{

	/*=============================================
	MOSTRAR COMPRAS
	=============================================*/

	static public function ctrMostrarCompras($item, $valor){

		$tabla = "compras";

		$respuesta = ModeloCompras::mdlMostrarCompras($tabla, $item, $valor);

		return $respuesta;

	}
	static public function resolverPagoCompra($tipo, $total, $efectivo, $qr){
		$tipo = (string) $tipo;
		if (!in_array($tipo, array("1", "2", "4"), true)) {
			return array("error" => "Seleccione efectivo, QR o mixto.");
		}

		$totalC = Moneda::centavos($total);
		$efectivoC = Moneda::centavos($efectivo);
		$qrC = Moneda::centavos($qr);

		if ($totalC <= 0) {
			return array("error" => "El monto de la compra debe ser mayor a cero.");
		}

		if ($tipo === "1") {
			$efectivoC = $totalC;
			$qrC = 0;
		} elseif ($tipo === "2") {
			$efectivoC = 0;
			$qrC = $totalC;
		} else {
			if ($efectivoC <= 0 || $qrC <= 0) {
				return array("error" => "En un pago mixto el efectivo y el QR deben ser mayores a cero.");
			}
			if ($efectivoC > $totalC || $qrC > $totalC || ($efectivoC + $qrC) !== $totalC) {
				return array("error" => "El efectivo y el QR deben sumar exactamente el total de la compra.");
			}
		}

		return array(
			"error" => "",
			"tipo_pago" => $tipo,
			"total_efectivo" => Moneda::desdeCentavos($efectivoC),
			"total_qr" => Moneda::desdeCentavos($qrC)
		);
	}

	static public function etiquetaFormaPagoCompra($tipo){
		switch ((string) $tipo) {
			case "1":
			case "Efectivo":
				return "Efectivo";
			case "2":
			case "QR":
				return "QR";
			case "4":
			case "Mixto":
				return "Efectivo y QR (Mixto)";
			default:
				return "—";
		}
	}

	/**
	 * Efectivo / QR / Total para una fila del reporte.
	 * Histórico sin forma de pago: todo el total va a efectivo.
	 */
	static public function montosReporteCompra($item){
		$total = floatval($item["total"] ?? 0);
		$tipo = (string) ($item["tipo_pago"] ?? "");
		$ef = array_key_exists("total_efectivo", $item) && $item["total_efectivo"] !== null && $item["total_efectivo"] !== ""
			? floatval($item["total_efectivo"])
			: null;
		$qr = array_key_exists("total_qr", $item) && $item["total_qr"] !== null && $item["total_qr"] !== ""
			? floatval($item["total_qr"])
			: null;

		if ($tipo === "1" || $tipo === "Efectivo") {
			return array("efectivo" => $total, "qr" => 0.0, "total" => $total);
		}
		if ($tipo === "2" || $tipo === "QR") {
			return array("efectivo" => 0.0, "qr" => $total, "total" => $total);
		}
		if ($tipo === "4" || $tipo === "Mixto") {
			$ef = $ef !== null ? $ef : 0.0;
			$qr = $qr !== null ? $qr : 0.0;
			$suma = $ef + $qr;
			if ($suma > 0 && abs($suma - $total) > 0.009) {
				$factor = $total / $suma;
				$ef = round($ef * $factor, 2);
				$qr = round($total - $ef, 2);
			} elseif ($suma <= 0) {
				$ef = $total;
				$qr = 0.0;
			}
			return array("efectivo" => $ef, "qr" => $qr, "total" => $total);
		}

		return array("efectivo" => $total, "qr" => 0.0, "total" => $total);
	}

	static public function ctrMostrarDetalleCompras($idCompra){

		$respuesta = ModeloCompras::mdlMostrarDetalleCompras($idCompra);
		return $respuesta;

	}

	/*=============================================
	CREAR COMPRA
	=============================================*/

	static public function ctrCrearCompra(){

		if(isset($_POST["nuevaCompra"])){

			if (!Permisos::tiene("compras.crear")) {
				Permisos::requiere("compras.crear");
				return;
			}

			if($_POST["listaProductos"] == ""){

					 echo'<script>

				swal({
					  type: "error",
					  title: "La compra no se  ejecuta si no hay productos Agregados",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					  }).then(function(result){
								if (result.value) {

								window.location = "crear-compra";

								}
							})

				</script>'; 

				return;
			}

			$descontarCaja = isset($_POST["descontarCaja"]) ? 1 : 0;
			$idArqueoCaja = intval($_POST["idArqueoCaja"] ?? 0);
			$cajaAbierta = $idArqueoCaja > 0 && ModeloArqueo::mdlVerificarCajaAbiertaPorIdArqueo($idArqueoCaja);
			if ($descontarCaja === 1 && !$cajaAbierta) {
				echo'<script>
					swal({
					  type: "error",
					  title: "No hay caja abierta",
					  text: "Para descontar de caja debe haber una caja abierta.",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					}).then(function(result){
						if (result.value) {
							window.location = "crear-compra";
						}
					});
				</script>';
				return;
			}
			if (!$cajaAbierta) {
				$idArqueoCaja = 0;
			}

			$pago = self::resolverPagoCompra($_POST["tipoPagoCompra"] ?? "1", $_POST["totalCompra"] ?? 0, $_POST["pagoEfectivoCompra"] ?? 0, $_POST["pagoQrCompra"] ?? 0);
			if ($pago["error"] !== "") {
				$mensajePago = json_encode($pago["error"]);
				echo "<script>
					swal({
					  type: 'error',
					  title: 'Forma de pago inválida',
					  text: $mensajePago,
					  showConfirmButton: true,
					  confirmButtonText: 'Cerrar'
					}).then(function(result){
						if (result.value) {
							window.location = 'crear-compra';
						}
					});
				</script>";
				return;
			}

			$listaProductos = json_decode($_POST["listaProductos"], true);
			if (!is_array($listaProductos) || count($listaProductos) === 0) {
				echo'<script>
					swal({
					  type: "error",
					  title: "Lista de productos inválida",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					}).then(function(result){
						if (result.value) {
							window.location = "crear-compra";
						}
					});
				</script>';
				return;
			}

			date_default_timezone_set('America/La_Paz');

			$tabla = "compras";

			$datos = array("id_usuario"=> isset($_POST["idUsuario"]) ? $_POST["idUsuario"] : null,
						   "id_proveedor"=>$_POST["seleccionarProveedor"],
						   "codigo"=>$_POST["nuevaCompra"],
						   "productos"=>$_POST["listaProductos"],
						   "id_arqueo_caja" => $idArqueoCaja,
						   "total"=>$_POST["totalCompra"],
						   "descontar_caja" => $descontarCaja,
						   "tipo_pago" => $pago["tipo_pago"],
						   "total_efectivo" => $pago["total_efectivo"],
						   "total_qr" => $pago["total_qr"]
						);

			$respuesta = ModeloCompras::mdlRegistrarCompra($tabla, $datos);

			if(is_array($respuesta) && $respuesta["status"] === "ok"){

				foreach ($listaProductos as $key => $value) {
					ModeloProductos::mdlIncrementarStockCompra($value["id"], intval($value["cantidad"]));
				}

			    $codigoCompra = $_POST["nuevaCompra"];
				echo "<script type='text/javascript'>
				     window.open('extensiones/tcpdf/pdf/extracto-compra.php?codigo={$codigoCompra}', '_blank');
					 window.location = 'crear-compra';
				</script>";
				return;
			}

			$mensajeError = is_array($respuesta)
				? ($respuesta["mensaje"] ?? "Error al registrar la compra")
				: (is_string($respuesta) ? preg_replace('/^error:\s*/', '', $respuesta) : "Error al registrar la compra");
			$mensajeJs = json_encode($mensajeError);

			echo "<script>
				swal({
				  type: 'error',
				  title: 'No se pudo registrar la compra',
				  html: String($mensajeJs).replace(/\\n/g, '<br>'),
				  showConfirmButton: true,
				  confirmButtonText: 'Cerrar'
				}).then(function(result){
					if (result.value) {
						window.location = 'crear-compra';
					}
				});
			</script>";

		}

	}


	/*=============================================
	ELIMINAR VENTA
	=============================================*/

	static public function ctrEliminarVenta(){

		if(isset($_GET["idCompra"])){

			if (!Permisos::tiene("compras.eliminar")) {
				Permisos::requiere("compras.eliminar");
				return;
			}
			$tabla = "compras";

			$item = "id";
			$valor = $_GET["idCompra"];

			$traerCompra = ModeloCompras::mdlMostrarCompras($tabla, $item, $valor);

			/*=============================================
			FORMATEAR TABLA DE PRODUCTOS Y LA DE MESEROS
			=============================================*/

			// $productos =  json_decode($traerCompra["productos"], true);
			$productos = ModeloCompras::mdlMostrarDetalleCompras($valor);

			$totalProductosComprados = array();

			foreach ($productos as $key => $value) {

				array_push($totalProductosComprados, $value["cantidad"]);
				
				ModeloProductos::mdlRevertirStockCompra($value["id_producto"], intval($value["cantidad"]));

			}

			/*=============================================
			ELIMINAR VENTA
			=============================================*/

			$respuesta = ModeloCompras::mdlEliminarCompra($tabla, $_GET["idCompra"]);

			if($respuesta == "ok"){
				if (!empty($traerCompra["id_arqueo_caja"])) {
					ModeloArqueo::mdlSincronizarMontosArqueo($traerCompra["id_arqueo_caja"]);
				}
				echo'<script>

				swal({
					  type: "success",
					  title: "La compra ha sido anulada correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					  }).then(function(result){
								if (result.value) {
									window.location = "compras";
								}
							})

				</script>';
 
			}		
		}

	}

	/*=============================================
	RANGO FECHAS
	=============================================*/	

	static public function ctrRangoFechasCompras($fechaInicial, $fechaFinal){

		$tabla = "compras";
	
		$respuesta = ModeloCompras::mdlRangoFechasCompras($tabla, $fechaInicial, $fechaFinal);
	
		return $respuesta;
		
	}
	static public function ctrRangoFechasComprasPdf($fechaInicial, $fechaFinal, $id_proveedor, $idCategoria, $tipoPago = 0, $descontarCaja = 0){

		$tabla = "compras";
	
		$respuesta = ModeloCompras::mdlRangoFechasComprasPdf($tabla, $fechaInicial, $fechaFinal, $id_proveedor, $idCategoria, $tipoPago, $descontarCaja);
	
		return $respuesta;
	}

	static public function etiquetaDescontarCajaFiltro($valor){
		$valor = (string) $valor;
		if ($valor === "1") {
			return "Restaron de caja";
		}
		if ($valor === "2") {
			return "No afectaron caja";
		}
		return "Todas";
	}
	static public function ctrComprasRealizadas($estado=1){

		$tabla = "compras";
	
		$respuesta = ModeloCompras::mdlComprasRealizadas($tabla,$estado);
	
		return $respuesta;
	}


	/*=============================================
	SUMA TOTAL VENTAS
	=============================================*/

	static public function ctrSumaTotalVentas(){

		$tabla = "compras";

		$respuesta = ModeloVentas::mdlSumaTotalVentas($tabla);

		return $respuesta;

	}

}