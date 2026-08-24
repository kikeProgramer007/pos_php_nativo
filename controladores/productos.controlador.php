<?php

class ControladorProductos{

	/*=============================================
	AQUI SE MOSTRARRA EL PRODUCTO
	=============================================*/

	static public function ctrMostrarProductos($item, $valor,$orden, $estado=1){

		$tabla = "productos";

		$respuesta = ModeloProductos::mdlMostrarProductos($tabla, $item, $valor,$orden,$estado);

		return $respuesta;

	}


	static public function ctrMostrarProductosActivosInventariable($item, $valor,$orden){

		$tabla = "productos";

		$respuesta = ModeloProductos::mdlMostrarProductosActivosInventariable($tabla, $item, $valor,$orden);

		return $respuesta;

	}

	/*=============================================
	CREAR PRODUCTO
	=============================================*/

	static public function ctrCrearProducto(){

		if(isset($_POST["nuevaDescripcion"])){

			if (!Permisos::tiene("productos.crear")) {
				Permisos::requiere("productos.crear");
				return;
			}

			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevaDescripcion"]) ||
			   preg_match('/^[0-9]+$/', $_POST["nuevoStock"]) &&	
			   preg_match('/^[0-9.]+$/', $_POST["nuevoPrecioVenta"]) &&	
			   preg_match('/^[0-9.]+$/', $_POST["nuevoPrecioCompra"])
			   ){

		   		/*=============================================
				VALIDAR IMAGEN
				=============================================*/

			   	$ruta = "vistas/img/productos/default/anonymous.webp";

			   	if(isset($_FILES["nuevaImagen"]["tmp_name"])   && !empty($_FILES["nuevaImagen"]["tmp_name"])){
					
					
					list($ancho, $alto) = getimagesize($_FILES["nuevaImagen"]["tmp_name"]);

					$nuevoAncho = 500;
					$nuevoAlto = 500;

					/*=============================================
					CREAMOS EL DIRECTORIO DONDE VAMOS A GUARDAR LA FOTO DEL USUARIO
					=============================================*/

					$directorio = "vistas/img/productos/".$_POST["nuevoCodigo"];

					if (!file_exists($directorio)) {
						mkdir($directorio, 0755);
					}

					/*=============================================
					DE ACUERDO AL TIPO DE IMAGEN APLICAMOS LAS FUNCIONES POR DEFECTO DE PHP
					=============================================*/

					if($_FILES["nuevaImagen"]["type"] == "image/jpeg"){

						/*=============================================
						GUARDAMOS LA IMAGEN EN EL DIRECTORIO
						=============================================*/

						$aleatorio = mt_rand(100,999);

						$ruta = "vistas/img/productos/".$_POST["nuevoCodigo"]."/".$aleatorio.".jpg";

						$origen = imagecreatefromjpeg($_FILES["nuevaImagen"]["tmp_name"]);						

						$destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

						imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

						imagejpeg($destino, $ruta);

					}

					if($_FILES["nuevaImagen"]["type"] == "image/png"){

						/*=============================================
						GUARDAMOS LA IMAGEN EN EL DIRECTORIO
						=============================================*/

						$aleatorio = mt_rand(100,999);

						$ruta = "vistas/img/productos/".$_POST["nuevoCodigo"]."/".$aleatorio.".png";

						$origen = imagecreatefrompng($_FILES["nuevaImagen"]["tmp_name"]);						

						$destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

						imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

						imagepng($destino, $ruta);

					}

					if($_FILES["nuevaImagen"]["type"] == "image/webp"){

						/*=============================================
						GUARDAMOS LA IMAGEN EN EL DIRECTORIO
						=============================================*/

						$aleatorio = mt_rand(100,999);

						$ruta = "vistas/img/productos/".$_POST["nuevoCodigo"]."/".$aleatorio.".webp";

						$origen = imagecreatefromwebp($_FILES["nuevaImagen"]["tmp_name"]);						

						$destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

						imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

						imagewebp($destino, $ruta);

					}

				}

				$tabla = "productos";

				$inventariable = intval($_POST["inventariable"]);
				$stock = ($inventariable === 0)
					? ModeloProductos::mdlStockDisponibleNoInventariable()
					: $_POST["nuevoStock"];

				$datos = array("id_categoria" => $_POST["nuevaCategoria"],
							   "codigo" => $_POST["nuevoCodigo"],
							   "descripcion" => $_POST["nuevaDescripcion"],
							   "stock" => $stock,
							   "inventariable" => $inventariable,
							   "precio_venta" => $_POST["nuevoPrecioVenta"],
							   "precio_compra" => $_POST["nuevoPrecioCompra"],
							   "imagen" => $ruta);
			

	     $respuesta = ModeloProductos::mdlIngresarProducto($tabla, $datos);
		 if($respuesta == "ok"){

			echo'<script>

				swal({
					  type: "success",
					  title: "El producto ha sido guardado correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					  }).then(function(result){
								if (result.value) {

								window.location = "productos";

								}
							})

				</script>';

		}



	     }else{

			echo'<script>

				swal({
					  type: "error",
					  title: "¡El producto no puede ir con los campos vacíos o llevar caracteres especiales!",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					  }).then(function(result){
						if (result.value) {

						window.location = "productos";

						}
					})

			  </script>';
			}
	
		}

	
	}

/*=============================================
	EDITAR PRODUCTO
	=============================================*/

	static public function ctrEditarProducto(){

		if(isset($_POST["editarDescripcion"])){

			if (!Permisos::tiene("productos.editar")) {
				Permisos::requiere("productos.editar");
				return;
			}

			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarDescripcion"]) ||
			   preg_match('/^[0-9]+$/', $_POST["editarStock"]) &&	
			   preg_match('/^[0-9.]+$/', $_POST["editarPrecioVenta"]) &&
			   preg_match('/^[0-9.]+$/', $_POST["editarPrecioCompra"])
			   ){

		   		/*=============================================
				VALIDAR IMAGEN
				=============================================*/

			   	$ruta = $_POST["imagenActual"];

			   	if(isset($_FILES["editarImagen"]["tmp_name"]) && !empty($_FILES["editarImagen"]["tmp_name"])){

					list($ancho, $alto) = getimagesize($_FILES["editarImagen"]["tmp_name"]);

					$nuevoAncho = 500;
					$nuevoAlto = 500;

					/*=============================================
					CREAMOS EL DIRECTORIO DONDE VAMOS A GUARDAR LA FOTO DEL USUARIO
					=============================================*/

					$directorio = "vistas/img/productos/".$_POST["editarCodigo"];

					/*=============================================
					PRIMERO PREGUNTAMOS SI EXISTE OTRA IMAGEN EN LA BD
					=============================================*/

					if(!empty($_POST["imagenActual"]) && $_POST["imagenActual"] != "vistas/img/productos/default/anonymous.webp"){

						unlink($_POST["imagenActual"]);

					}else{

						mkdir($directorio, 0755);	
					
					}
					
					/*=============================================
					DE ACUERDO AL TIPO DE IMAGEN APLICAMOS LAS FUNCIONES POR DEFECTO DE PHP
					=============================================*/

					if($_FILES["editarImagen"]["type"] == "image/jpeg"){

						/*=============================================
						GUARDAMOS LA IMAGEN EN EL DIRECTORIO
						=============================================*/

						$aleatorio = mt_rand(100,999);

						$ruta = "vistas/img/productos/".$_POST["editarCodigo"]."/".$aleatorio.".jpg";

						$origen = imagecreatefromjpeg($_FILES["editarImagen"]["tmp_name"]);						

						$destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

						imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

						imagejpeg($destino, $ruta);

					}

					if($_FILES["editarImagen"]["type"] == "image/png"){

						/*=============================================
						GUARDAMOS LA IMAGEN EN EL DIRECTORIO
						=============================================*/

						$aleatorio = mt_rand(100,999);

						$ruta = "vistas/img/productos/".$_POST["editarCodigo"]."/".$aleatorio.".png";

						$origen = imagecreatefrompng($_FILES["editarImagen"]["tmp_name"]);						

						$destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

						imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

						imagepng($destino, $ruta);

					}

					if($_FILES["editarImagen"]["type"] == "image/webp"){

						/*=============================================
						GUARDAMOS LA IMAGEN EN EL DIRECTORIO
						=============================================*/

						$aleatorio = mt_rand(100,999);

						$ruta = "vistas/img/productos/".$_POST["editarCodigo"]."/".$aleatorio.".webp";

						$origen = imagecreatefromwebp($_FILES["editarImagen"]["tmp_name"]);						

						$destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

						imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

						imagewebp($destino, $ruta);

					}

				}

				$tabla = "productos";

				$inventariable = intval($_POST["editarInventariable"]);
				if ($inventariable === 0) {
					// Select Disponible/Agotado en UI envía 1 o 0 en editarDisponibleNoInv
					$disponible = isset($_POST["editarDisponibleNoInv"])
						? (intval($_POST["editarDisponibleNoInv"]) === 1)
						: (intval($_POST["editarStock"]) > 0);
					$stock = $disponible
						? ModeloProductos::mdlStockDisponibleNoInventariable()
						: 0;
				} else {
					$stock = $_POST["editarStock"];
				}

				$datos = array("id_categoria" => $_POST["editarCategoria"],
							   "id" => $_POST["idProductoEditar"],
							   "codigo" => $_POST["editarCodigo"],
							   "descripcion" => $_POST["editarDescripcion"],
							   "stock" => $stock,
							   "inventariable" => $inventariable,
							   "precio_venta" => $_POST["editarPrecioVenta"],
							   "precio_compra" => $_POST["editarPrecioCompra"],
							   "imagen" => $ruta);

				$respuesta = ModeloProductos::mdlEditarProducto($tabla, $datos);

				if($respuesta == "ok"){

					echo'<script>

						swal({
							  type: "success",
							  title: "El producto ha sido editado correctamente",
							  showConfirmButton: true,
							  confirmButtonText: "Cerrar"
							  }).then(function(result){
										if (result.value) {

										window.location = "productos";

										}
									})

						</script>';

				}


			}else{

				echo'<script>

					swal({
						  type: "error",
						  title: "¡El producto no puede ir con los campos vacíos o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {

							window.location = "productos";

							}
						})

			  	</script>';
			}
		}

	}

	/*=============================================
	BORRAR PRODUCTO
	=============================================*/
	static public function ctrEliminarProducto(){

		if(isset($_GET["idProducto"])){

			if (!Permisos::tiene("productos.eliminar")) {
				Permisos::requiere("productos.eliminar");
				return;
			}

			$tabla ="productos";
			$datos = $_GET["idProducto"];

			// if($_GET["imagen"] != "" && $_GET["imagen"] != "vistas/img/productos/default/anonymous.png"){

			// 	unlink($_GET["imagen"]);
			// 	rmdir('vistas/img/productos/'.$_GET["codigo"]);

			// }

			$respuesta = ModeloProductos::mdlEliminarProducto($tabla, $datos);

			if($respuesta == "ok"){

				echo'<script>

				swal({
					  type: "success",
					  title: "El producto ha sido borrado correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					  }).then(function(result){
								if (result.value) {

								window.location = "productos";

								}
							})

				</script>';

			}		
		}


	}

	/*=============================================
	RESTAURAR PRODUCTO
	=============================================*/
	static public function ctrRestaurarProducto(){

		if(isset($_GET["idProductoRestaurar"])){

			if (!Permisos::tiene("productos.eliminados")) {
				Permisos::requiere("productos.eliminados");
				return;
			}

			$tabla ="productos";
			$datos = $_GET["idProductoRestaurar"];


			$respuesta = ModeloProductos::mdlRestaurarProducto($tabla, $datos);

			if($respuesta == "ok"){

				echo'<script>

				swal({
					  type: "success",
					  title: "El producto ha sido Restaurando correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					  }).then(function(result){
					  	if (result.value) {

								window.location = "productos";

								}
					  })

				</script>';

			}		
		}


	}

	/*=============================================
	MOSTRAR SUMA VENTAS
	=============================================*/

	static public function ctrMostrarSumaVentas(){

		$tabla = "productos";

		$respuesta = ModeloProductos::mdlMostrarSumaVentas($tabla);

		return $respuesta;

	}

	/*=============================================
	MOSTRAR PRODUCTOS SEGUN CATEGORIA
	=============================================*/
	static public function ctrMostrarProductosSegunCategoria($idCategoria){

		$tabla = "productos";

		$respuesta = ModeloProductos::mdlProductoPorCategoriaPdf($tabla,$idCategoria);

		return $respuesta;

	}
	/*=============================================
	MOSTRAR PRODUCTOS SEGUN CATEGORIA
	=============================================*/
	static public function ctrMostrarProductosFaltante(){

		$tabla = "productos";

		$respuesta = ModeloProductos::mdlProductoFaltantePdf($tabla);

		return $respuesta;

	}

	/*=============================================
	MARCAR DISPONIBLE / AGOTADO (no inventariable)
	=============================================*/
	static public function ctrMarcarDisponibilidadNoInventariable($idProducto, $disponible){

		if (!Permisos::tiene("ventas.crear") && !Permisos::tiene("productos.editar")) {
			return ["status" => "error", "mensaje" => "No tiene permiso para cambiar la disponibilidad."];
		}

		$idProducto = intval($idProducto);
		$disponible = (bool) $disponible;

		$producto = ModeloProductos::mdlMostrarProductos("productos", "id", $idProducto, "id");
		if (!$producto) {
			return ["status" => "error", "mensaje" => "Producto no encontrado."];
		}

		if (intval($producto["inventariable"]) === 1) {
			return ["status" => "error", "mensaje" => "Este producto es inventariable; use compras/stock para gestionarlo."];
		}

		$ok = ModeloProductos::mdlMarcarDisponibilidadNoInventariable($idProducto, $disponible);
		if (!$ok) {
			return ["status" => "error", "mensaje" => "No se pudo actualizar la disponibilidad."];
		}

		$stock = $disponible ? ModeloProductos::mdlStockDisponibleNoInventariable() : 0;

		return [
			"status" => "ok",
			"mensaje" => $disponible ? "Producto marcado como disponible." : "Producto marcado como agotado.",
			"id" => $idProducto,
			"disponible" => $disponible,
			"stock" => $stock,
			"etiqueta" => $disponible ? "Disponible" : "Agotado"
		];
	}

}

