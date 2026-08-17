<?php

class ControladorMeseros{

	/*=============================================
	CREAR MESEROS
	=============================================*/

	static public function ctrCrearMesero(){

		if(isset($_POST["nuevoMesero"])){

			if (!Permisos::tiene("meseros.crear")) {
				Permisos::requiere("meseros.crear");
				return;
			}

			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoMesero"]) ||
			   preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoDocumentoId"])&&
			   preg_match('/^[()\-0-9 ]+$/', $_POST["nuevoTelefono"]) && 
			   preg_match('/^[#\.\-a-zA-Z0-9 ]+$/', $_POST["nuevaDireccion"])){
			   

			   	$tabla = "meseros";

			   	$datos = array("nombre"=>$_POST["nuevoMesero"],
					           "documento"=>$_POST["nuevoDocumentoId"],
							   "telefono"=>$_POST["nuevoTelefono"],
					           "direccion"=>$_POST["nuevaDireccion"]);
					           
				 	            

			   	$respuesta = ModeloMeseros::mdlIngresarMesero($tabla, $datos);

			   	if($respuesta == "ok"){

					echo'<script>

					swal({
						  type: "success",
						  title: "El Mesero ha sido guardado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {

									window.location = "meseros";

									}
								})

					</script>';

				}

			}else{

				echo'<script>

					swal({
						  type: "error",
						  title: "¡no puede ir vacío o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {

							window.location = "meseros";

							}
						})

			  	</script>';



			}

		}

	}


	//primer paso 
	/*=============================================
	MOSTRAR MESEROS
	=============================================*/

	static public function ctrMostrarMeseros($item, $valor,$estado=1){

		$tabla = "meseros";

		$respuesta = ModeloMeseros::mdlMostrarMeseros($tabla, $item, $valor,$estado);

		return $respuesta;

	}




		//primer paso 
	/*=============================================
	MOSTRAR MESEROS
	=============================================*/

	static public function ctrMostrarMeserosActivoInactivo($item, $valor){

		$tabla = "meseros";

		$respuesta = ModeloMeseros::mdlMostrarMeserosActivoInactivo($tabla, $item, $valor);

		return $respuesta;

	}

	/*=============================================
	EDITAR MESERO
	
	=============================================*/

	static public function ctrEditarMesero(){

		if(isset($_POST["editarMesero"])){

			if (!Permisos::tiene("meseros.editar")) {
				Permisos::requiere("meseros.editar");
				return;
			}

			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarMesero"]) ||
			   preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoDocumentoId"])&&
			   preg_match('/^[()\-0-9 ]+$/', $_POST["editarTelefono"]) && 
			   preg_match('/^[#\.\-a-zA-Z0-9 ]+$/', $_POST["editarDireccion"])){ 
	

			   	$tabla = "meseros";

			   	$datos = array("id"=>$_POST["idMesero"],
			   				   "nombre"=>$_POST["editarMesero"],
					           "documento"=>$_POST["editarDocumentoId"],
							    "telefono"=>$_POST["editarTelefono"],
					           "direccion"=>$_POST["editarDireccion"]);
					         
					               

			   	$respuesta = ModeloMeseros::mdlEditarMesero($tabla, $datos);

			   	if($respuesta == "ok"){

					echo'<script>

					swal({
						  type: "success",
						  title: "El Mesero ha sido cambiado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {

									window.location = "meseros";

									}
								})

					</script>';

				}

			}else{

				echo'<script>

					swal({
						  type: "error",
						  title: "¡no puede ir vacío o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {

							window.location = "meseros";

							}
						})

			  	</script>';



			}

		}

	}

	/*=============================================
	ELIMINAR MESERO
	=============================================*/

	static public function ctrEliminarMesero(){

		if(isset($_GET["idMesero"])){

			if (!Permisos::tiene("meseros.eliminar")) {
				Permisos::requiere("meseros.eliminar");
				return;
			}

			$tabla ="meseros";
			$datos = $_GET["idMesero"];

			$respuesta = ModeloMeseros::mdlEliminarMesero($tabla, $datos);

			if($respuesta == "ok"){

				echo'<script>

				swal({
					  type: "success",
					  title: "El Mesero ha sido borrado correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					  }).then(function(result){
								if (result.value) {

								window.location = "meseros";

								}
							})

				</script>';

			}		

		}

	}







   //tercer paso


   /*=============================================
	RESTAURAR MESERO
	=============================================*/

	static public function ctrRestaurarMesero(){

		if(isset($_GET["idMeseroRestaurar"])){

			if (!Permisos::tiene("meseros.eliminados")) {
				Permisos::requiere("meseros.eliminados");
				return;
			}

			$tabla ="meseros";
			$datos = $_GET["idMeseroRestaurar"];

			$respuesta = ModeloMeseros::mdlRestaurarMesero($tabla, $datos);

			if($respuesta == "ok"){

				echo'<script>

				swal({
					  type: "success",
					  title: "El Mesero ha sido Restaurado correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					  }).then(function(result){
								if (result.value) {

								window.location = "meseros";

								}
							})

				</script>';

			}		

		}

	}

}

