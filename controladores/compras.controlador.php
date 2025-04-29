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

			/*=============================================
			ACTUALIZAR LAS COMPRAS DEL MESERO Y REDUCIR EL STOCK Y AUMENTAR LAS VENTAS DE LOS PRODUCTOS
			=============================================*/

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


			$listaProductos = json_decode($_POST["listaProductos"], true);
	

			$totalProductosComprados = array();

			foreach ($listaProductos as $key => $value) {

			   array_push($totalProductosComprados, $value["cantidad"]);
				
			   $tablaProductos = "productos";

			    $item = "id";
			    $valor = $value["id"];
			    $orden = "id";

			    $traerProducto = ModeloProductos::mdlMostrarProductos($tablaProductos, $item, $valor, $orden);

				$item1a = "stock";
				$valor1a = $value["cantidad"] + $traerProducto["stock"];

			    $nuevasVentas = ModeloProductos::mdlActualizarProducto($tablaProductos, $item1a, $valor1a, $valor);

			}

			date_default_timezone_set('America/La_Paz');
		
			/*=============================================
			GUARDAR LA COMPRA
			=============================================*/	
			echo  $_POST["idUsuario"];
			$tabla = "compras";

			$datos = array("id_usuario"=> isset($_POST["idUsuario"]) ? $_POST["idUsuario"] : null,
						   "id_proveedor"=>$_POST["seleccionarProveedor"],
						   "codigo"=>$_POST["nuevaCompra"],
						   "productos"=>$_POST["listaProductos"],
						   "total"=>$_POST["totalCompra"]
						);
			
			// $respuesta = ModeloCompras::mdlIngresarCompra($tabla, $datos);
			$respuesta = ModeloCompras::mdlRegistrarCompra($tabla, $datos);

			if($respuesta == "ok"){
				ModeloArqueo::mdlRegistrarEgreso($_POST["idArqueoCaja"], $_POST["totalCompra"]);
			    $codigoCompra = $_POST["nuevaCompra"];
				echo "<script type='text/javascript'>
				     window.open('extensiones/tcpdf/pdf/extracto-compra.php?codigo={$codigoCompra}', '_blank');
					 window.location = 'crear-compra';
				</script>";
			}

		}

	}


	/*=============================================
	ELIMINAR VENTA
	=============================================*/

	static public function ctrEliminarVenta(){

		if(isset($_GET["idCompra"])){

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
				
				$tablaProductos = "productos";

				$item = "id";
				$valor = $value["id_producto"];
				$orden = "id";

				$traerProducto = ModeloProductos::mdlMostrarProductosActivosInactivos($tablaProductos, $item, $valor, $orden);
		
				$item1b = "stock";
				$valor1b = $traerProducto["stock"] - $value["cantidad"];
				$nuevoStock = ModeloProductos::mdlActualizarProducto($tablaProductos, $item1b, $valor1b, $valor);

			}

			/*=============================================
			ELIMINAR VENTA
			=============================================*/

			$respuesta = ModeloCompras::mdlEliminarCompra($tabla, $_GET["idCompra"]);

			if($respuesta == "ok"){

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