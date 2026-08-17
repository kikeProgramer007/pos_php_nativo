<?php

class ControladorProveedors{

	/*=============================================
	CREAR PROVEEDOR
	=============================================*/

	static public function ctrCrearProveedor(){

		if(isset($_POST["nuevoProveedor"])){

			if (!Permisos::tiene("proveedores.crear")) {
				Permisos::requiere("proveedores.crear");
				return;
			}

			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ \/]+$/', $_POST["nuevoProveedor"]) ||
			   preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ \/]+$/', $_POST["nuevaEmpresa"]) ||
			   preg_match('/^[()\-0-9 ]+$/', $_POST["nuevoTelefono"]) ||
			   preg_match('/^[#/\.-a-zA-Z0-9 ]+$/', $_POST["nuevaDireccion"])){

			   	$tabla = "proveedor";

			   	$datos = array("nombre"=>$_POST["nuevoProveedor"],
			   				   "empresa"=>$_POST["nuevaEmpresa"],
					           "telefono"=>$_POST["nuevoTelefono"],
					           "direccion"=>$_POST["nuevaDireccion"]);

			   	$respuesta = ModeloProveedors::mdlIngresarProveedor($tabla, $datos);

			   	if($respuesta == "ok"){

					echo'<script>

					swal({
						  type: "success",
						  title: "El proveedor ha sido guardado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {

									window.location = "proveedor";

									}
								})

					</script>';

				}

			}else{

				echo'<script>

					swal({
						  type: "error",
						  title: "¡El proveedor no puede ir vacío o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {

							window.location = "proveedor";

							}
						})

			  	</script>';



			}

		}

	}

	/*=============================================
	MOSTRAR PROVEEDOR
	=============================================*/

	static public function ctrMostrarProveedors($item, $valor,$estado=1){

		$tabla = "proveedor";

		$respuesta = ModeloProveedors::mdlMostrarProveedors($tabla, $item, $valor,$estado);

		return $respuesta;

	}
	static public function ctrMostrarProveedorsActivosInactivos($item, $valor,$estado=1){

		$tabla = "proveedor";
		$respuesta = ModeloProveedors::mdlMostrarProveedorsActivosInactivos($tabla, $item, $valor,$estado);
		return $respuesta;

	}

	/*=============================================
	EDITAR PROVEEDOR
	=============================================*/

	static public function ctrEditarProveedor(){

		if(isset($_POST["editarProveedor"])){

			if (!Permisos::tiene("proveedores.editar")) {
				Permisos::requiere("proveedores.editar");
				return;
			}

			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ \/]+$/', $_POST["editarProveedor"]) &&
			   preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ \/]+$/', $_POST["editarEmpresa"]) &&
			   preg_match('/^[()\-0-9 ]+$/', $_POST["editarTelefono"]) && 
			   preg_match('/^[#\.\-a-zA-Z0-9 ]+$/', $_POST["editarDireccion"])){

			   	$tabla = "proveedor";

			   	$datos = array("id"=>$_POST["idProveedor"],
			   				   "nombre"=>$_POST["editarProveedor"],
			   				   "empresa"=>$_POST["editarEmpresa"],
					           "telefono"=>$_POST["editarTelefono"],
					           "direccion"=>$_POST["editarDireccion"]);

			   	$respuesta = ModeloProveedors::mdlEditarProveedor($tabla, $datos);

			   	if($respuesta == "ok"){

					echo'<script>

					swal({
						  type: "success",
						  title: "El proveedor ha sido cambiado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {

									window.location = "proveedor";

									}
								})

					</script>';

				}

			}else{

				echo'<script>

					swal({
						  type: "error",
						  title: "¡El proveedor no puede ir vacío o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {

							window.location = "proveedor";

							}
						})

			  	</script>';



			}

		}

	}

	/*=============================================
	ELIMINAR PROVEEDOR
	=============================================*/

	static public function ctrEliminarProveedor(){

		if(isset($_GET["idProveedor"])){

			if (!Permisos::tiene("proveedores.eliminar")) {
				Permisos::requiere("proveedores.eliminar");
				return;
			}

			$tabla ="proveedor";
			$datos = $_GET["idProveedor"];

			$respuesta = ModeloProveedors::mdlEliminarProveedor($tabla, $datos);

			if($respuesta == "ok"){

				echo'<script>

				swal({
					  type: "success",
					  title: "El proveedor ha sido borrado correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar",
					  closeOnConfirm: false
					  }).then(function(result){
								if (result.value) {

								window.location = "proveedor";

								}
							})

				</script>';

			}		

		}

	}



	/*=============================================
	ELIMINAR PROVEEDOR
	=============================================*/

	static public function ctrRestaurarProveedor(){

		if(isset($_GET["idProveedorRestaurar"])){

			if (!Permisos::tiene("proveedores.eliminados")) {
				Permisos::requiere("proveedores.eliminados");
				return;
			}

			$tabla ="proveedor";
			$datos = $_GET["idProveedorRestaurar"];

			$respuesta = ModeloProveedors::mdlRestaurarProveedor($tabla, $datos);

			if($respuesta == "ok"){

				echo'<script>

				swal({
					  type: "success",
					  title: "El proveedor ha sido Restaurado correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar",
					  closeOnConfirm: false
					  }).then(function(result){
								if (result.value) {

								window.location = "proveedor";

								}
							})

				</script>';

			}		

		}

	}










}

