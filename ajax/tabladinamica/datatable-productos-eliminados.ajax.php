<?php

require_once "../../controladores/productos.controlador.php";
require_once "../../modelos/productos.modelo.php";

require_once "../../controladores/categorias.controlador.php";
require_once "../../modelos/categorias.modelo.php";


class TablaProductosEliminados{

 	/*=============================================
 	 MOSTRAR LA TABLA DE PRODUCTOS
  	=============================================*/ 

	public function mostrarTablaProductos(){

		$item = null;
    	$valor = null;
		$orden = "id";
		$estado = 0;

  		$productos = ControladorProductos::ctrMostrarProductos($item, $valor,$orden,$estado);	
		
	// Inicializamos la estructura del JSON
		$datosJson = '{
		"data": [';
		if (count($productos) > 0) {
		  for($i = 0; $i < count($productos); $i++){

		  	/*=============================================
 	 		TRAEMOS LA IMAGEN
  			=============================================*/ 

		  	$imagen = "<img src='".$productos[$i]["imagen"]."' width='40px'>";

		  	/*=============================================
 	 		TRAEMOS LA CATEGORÍA
  			=============================================*/ 

		  	$item = "id";
		  	$valor = $productos[$i]["id_categoria"];

		  	$categorias = ControladorCategorias::ctrMostrarCategoriasActivasInactivas($item, $valor);

		  	/*=============================================
 	 		STOCK
  			=============================================*/ 

  			if($productos[$i]["stock"] <= 10){

  				$stock = "<button class='btn btn-danger'>".$productos[$i]["stock"]."</button>";

  			}else if($productos[$i]["stock"] > 11 && $productos[$i]["stock"] <= 15){

  				$stock = "<button class='btn btn-warning'>".$productos[$i]["stock"]."</button>";

  			}else{

  				$stock = "<button class='btn btn-success'>".$productos[$i]["stock"]."</button>";

  			}

		  /*=============================================
 	 		TRAEMOS LAS ACCIONES
  			=============================================*/ 

  			if(isset($_GET["perfilOculto"]) && $_GET["perfilOculto"] == "Supervisor"){

				$botones =  ""; 

			}else{

				 $botones =  "<div class='btn-group'><button class='btn btn-success btnRestaurarProducto' idProducto='".$productos[$i]["id"]."' codigo='".$productos[$i]["codigo"]."' imagen='".$productos[$i]["imagen"]."'><i class='fa fa-undo'></i> Restaurar</button></div>"; 

			}

		  	$datosJson .='[
			      "'.($i+1).'",
			      "'.$imagen.'",
			      "'.$productos[$i]["codigo"].'",
			      "'.$productos[$i]["descripcion"].'",
			      "'.$categorias["categoria"].'",
			      "'.$stock.'",
			      "'.$productos[$i]["precio_venta"].'",
				  "'.$productos[$i]["fecha"].'",
		          "'.$botones.'"
			    ],';

		  }

		
		  $datosJson = substr($datosJson, 0, -1);

		}
	
		$datosJson .= '] 
		}';
	
		echo $datosJson;

	}


}

/*=============================================
ACTIVAR TABLA DE PRODUCTOS
=============================================*/ 
$activarProductos = new TablaProductosEliminados();
$activarProductos -> mostrarTablaProductos();

