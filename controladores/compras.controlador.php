<?php

class ControladorCompras{

	/*=============================================
	MOSTRAR COMPRAS
	=============================================*/

	static public function ctrMostrarCompras($item, $valor){

		$tabla = "compras";

		$respuesta = ModeloCompras::mdlMostrarCompras($tabla, $item, $valor);

		return $respuesta;

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

			$idArqueoCaja = intval($_POST["idArqueoCaja"] ?? 0);
			if ($idArqueoCaja <= 0 || !ModeloArqueo::mdlVerificarCajaAbiertaPorIdArqueo($idArqueoCaja)) {
				echo'<script>
					swal({
					  type: "error",
					  title: "No hay caja abierta",
					  text: "Debe aperturar una caja antes de registrar compras.",
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
						   "total"=>$_POST["totalCompra"]
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
	static public function ctrRangoFechasComprasPdf($fechaInicial, $fechaFinal,$id_proveedor,$idCategoria){

		$tabla = "compras";
	
		$respuesta = ModeloCompras::mdlRangoFechasComprasPdf($tabla, $fechaInicial, $fechaFinal,$id_proveedor,$idCategoria);
	
		return $respuesta;
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