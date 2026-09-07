<?php

require_once __DIR__ . "/conexion.php";

class ModeloProductos{

	/** Stock interno para productos no inventariables marcados como disponibles. Nunca mostrar en UI. */
	const STOCK_DISPONIBLE_NO_INV = 99999;

	static public function mdlStockDisponibleNoInventariable(){
		return self::STOCK_DISPONIBLE_NO_INV;
	}

	static public function mdlProductoEstaDisponible($producto){
		return intval($producto["stock"] ?? 0) > 0;
	}

	/**
	 * Texto/HTML de stock para UI: no inventariables no muestran 99999.
	 */
	static public function mdlEtiquetaStockUi($producto, $formatoHtml = true){
		$inventariable = intval($producto["inventariable"] ?? 1) === 1;
		$stock = intval($producto["stock"] ?? 0);

		if (!$inventariable) {
			$texto = $stock > 0 ? "Disponible" : "Agotado";
			if (!$formatoHtml) {
				return $texto;
			}
			$clase = $stock > 0 ? "btn btn-success" : "btn btn-danger";
			return "<button class='".$clase."'>".$texto."</button>";
		}

		if (!$formatoHtml) {
			return (string) $stock;
		}

		if ($stock <= 10) {
			return "<button class='btn btn-danger'>".$stock."</button>";
		}
		if ($stock > 11 && $stock <= 15) {
			return "<button class='btn btn-warning'>".$stock."</button>";
		}
		return "<button class='btn btn-success'>".$stock."</button>";
	}

	/*=============================================
	MOSTRAR PRODUCTOS
	=============================================*/

	static public function mdlMostrarProductos($tabla, $item, $valor, $orden, $estado=1){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item AND estado=:estado  ORDER BY id DESC");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
			$stmt -> bindParam(":estado", $estado, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE estado=:estado ORDER BY $orden DESC");
			$stmt -> bindParam(":estado", $estado, PDO::PARAM_STR);
			$stmt -> execute();

			return $stmt -> fetchAll();

		}

		$stmt -> close();

		$stmt = null;

	}

	static public function mdlMostrarProductosActivosInventariable($tabla, $item, $valor, $orden){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item AND estado=1 AND  inventariable=1  ORDER BY id DESC");
			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
			$stmt -> execute();
			return $stmt -> fetch();

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE estado=1 AND estado=1 AND  inventariable=1  ORDER BY $orden DESC");
			$stmt -> execute();
			return $stmt -> fetchAll();
		}

		$stmt -> close();
		$stmt = null;
	}
	
	static public function mdlMostrarProductosActivosInactivos($tabla, $item, $valor, $orden){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item ORDER BY id DESC");
			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
			$stmt -> execute();
			return $stmt -> fetch();

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY $orden DESC");
			$stmt -> execute();
			return $stmt -> fetchAll();
		}

		$stmt -> close();
		$stmt = null;
	}
	/*=============================================
	REGISTRO DE PRODUCTO
	=============================================*/

	static public function mdlIngresarProducto($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(id_categoria, codigo, descripcion, imagen, stock,precio_venta,precio_compra,inventariable) VALUES (:id_categoria, :codigo, :descripcion, :imagen, :stock, :precio_venta,:precio_compra,:inventariable)");
		$stmt->bindParam(":id_categoria", $datos["id_categoria"], PDO::PARAM_INT);
		$stmt->bindParam(":codigo", $datos["codigo"], PDO::PARAM_STR);
		$stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
		$stmt->bindParam(":imagen", $datos["imagen"], PDO::PARAM_STR);
		$stmt->bindParam(":stock", $datos["stock"], PDO::PARAM_STR);
		$stmt->bindParam(":inventariable", $datos["inventariable"], PDO::PARAM_STR);
		$stmt->bindParam(":precio_venta", $datos["precio_venta"], PDO::PARAM_STR);
		$stmt->bindParam(":precio_compra", $datos["precio_compra"], PDO::PARAM_STR);
		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt->close();
		$stmt = null;

	}

    /*=============================================
	EDITAR PRODUCTO
	=============================================*/
	static public function mdlEditarProducto($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET id_categoria = :id_categoria, descripcion = :descripcion, imagen = :imagen, stock = :stock,  precio_venta = :precio_venta, precio_compra = :precio_compra, inventariable=:inventariable WHERE id = :id AND estado = 1");

		$stmt->bindParam(":id_categoria", $datos["id_categoria"], PDO::PARAM_INT);
		$stmt->bindParam(":id", $datos["id"], PDO::PARAM_STR);
		$stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
		$stmt->bindParam(":imagen", $datos["imagen"], PDO::PARAM_STR);
		$stmt->bindParam(":stock", $datos["stock"], PDO::PARAM_STR);
		$stmt->bindParam(":inventariable", $datos["inventariable"], PDO::PARAM_STR);
		$stmt->bindParam(":precio_venta", $datos["precio_venta"], PDO::PARAM_STR);
		$stmt->bindParam(":precio_compra", $datos["precio_compra"], PDO::PARAM_STR);
		
		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt->close();
		$stmt = null;
      
	}


	/*=============================================
	BORRAR PRODUCTO
	=============================================*/

	static public function mdlEliminarProducto($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET estado=0 WHERE id = :id");

		$stmt -> bindParam(":id", $datos, PDO::PARAM_INT);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt -> close();

		$stmt = null;

	}
	/*=============================================
	RESTAURAR PRODUCTO
	=============================================*/

	static public function mdlRestaurarProducto($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET estado=1 WHERE id = :id");

		$stmt -> bindParam(":id", $datos, PDO::PARAM_INT);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt -> close();

		$stmt = null;

	}


	/*=============================================
	ACTUALIZAR PRODUCTO
	=============================================*/

	static public function mdlActualizarProducto($tabla, $item1, $valor1, $valor){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $item1 = :$item1 WHERE id = :id");

		$stmt -> bindParam(":".$item1, $valor1, PDO::PARAM_STR);
		$stmt -> bindParam(":id", $valor, PDO::PARAM_STR);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	DESCONTAR STOCK DE VENTA (solo inventariable, atómico)
	=============================================*/
	static public function mdlDescontarStockVenta($idProducto, $cantidad){

		$cantidad = intval($cantidad);
		if ($cantidad <= 0) {
			return true;
		}

		$stmt = Conexion::conectar()->prepare(
			"UPDATE productos
			 SET stock = stock - :cantidad, ventas = ventas + :cantidadVentas
			 WHERE id = :id
			   AND inventariable = 1
			   AND estado = 1
			   AND stock >= :cantidadMin"
		);

		$stmt->bindParam(":cantidad", $cantidad, PDO::PARAM_INT);
		$stmt->bindParam(":cantidadVentas", $cantidad, PDO::PARAM_INT);
		$stmt->bindParam(":cantidadMin", $cantidad, PDO::PARAM_INT);
		$stmt->bindParam(":id", $idProducto, PDO::PARAM_INT);
		$stmt->execute();

		return $stmt->rowCount() > 0;
	}

	/*=============================================
	DEVOLVER STOCK DE VENTA (solo inventariable)
	=============================================*/
	static public function mdlDevolverStockVenta($idProducto, $cantidad){

		$cantidad = intval($cantidad);
		if ($cantidad <= 0) {
			return true;
		}

		$stmt = Conexion::conectar()->prepare(
			"UPDATE productos
			 SET stock = stock + :cantidad,
			     ventas = GREATEST(0, ventas - :cantidadVentas)
			 WHERE id = :id AND inventariable = 1"
		);

		$stmt->bindParam(":cantidad", $cantidad, PDO::PARAM_INT);
		$stmt->bindParam(":cantidadVentas", $cantidad, PDO::PARAM_INT);
		$stmt->bindParam(":id", $idProducto, PDO::PARAM_INT);

		return $stmt->execute();
	}

	/*=============================================
	INCREMENTAR CONTADOR VENTAS (no inventariable)
	=============================================*/
	static public function mdlIncrementarContadorVentas($idProducto, $cantidad){

		$cantidad = intval($cantidad);
		if ($cantidad <= 0) {
			return true;
		}

		$stmt = Conexion::conectar()->prepare(
			"UPDATE productos SET ventas = ventas + :cantidad WHERE id = :id AND estado = 1"
		);
		$stmt->bindParam(":cantidad", $cantidad, PDO::PARAM_INT);
		$stmt->bindParam(":id", $idProducto, PDO::PARAM_INT);

		return $stmt->execute();
	}

	/*=============================================
	INCREMENTAR STOCK POR COMPRA (solo inventariable)
	=============================================*/
	static public function mdlIncrementarStockCompra($idProducto, $cantidad){

		$cantidad = intval($cantidad);
		if ($cantidad <= 0) {
			return true;
		}

		$stmt = Conexion::conectar()->prepare(
			"UPDATE productos
			 SET stock = stock + :cantidad
			 WHERE id = :id AND inventariable = 1 AND estado = 1"
		);
		$stmt->bindParam(":cantidad", $cantidad, PDO::PARAM_INT);
		$stmt->bindParam(":id", $idProducto, PDO::PARAM_INT);
		$stmt->execute();

		return $stmt->rowCount() > 0;
	}

	/*=============================================
	REVERTIR STOCK DE COMPRA (no deja negativo)
	=============================================*/
	static public function mdlRevertirStockCompra($idProducto, $cantidad){

		$cantidad = intval($cantidad);
		if ($cantidad <= 0) {
			return true;
		}

		$stmt = Conexion::conectar()->prepare(
			"UPDATE productos
			 SET stock = GREATEST(0, stock - :cantidad)
			 WHERE id = :id AND inventariable = 1"
		);
		$stmt->bindParam(":cantidad", $cantidad, PDO::PARAM_INT);
		$stmt->bindParam(":id", $idProducto, PDO::PARAM_INT);

		return $stmt->execute();
	}

	/*=============================================
	MARCAR DISPONIBLE / AGOTADO (solo no inventariable)
	=============================================*/
	static public function mdlMarcarDisponibilidadNoInventariable($idProducto, $disponible){

		$idProducto = intval($idProducto);
		$stock = $disponible ? self::STOCK_DISPONIBLE_NO_INV : 0;

		$stmt = Conexion::conectar()->prepare(
			"UPDATE productos
			 SET stock = :stock
			 WHERE id = :id AND inventariable = 0 AND estado = 1"
		);
		$stmt->bindParam(":stock", $stock, PDO::PARAM_INT);
		$stmt->bindParam(":id", $idProducto, PDO::PARAM_INT);
		$stmt->execute();

		return $stmt->rowCount() > 0;
	}




	/*=============================================
	MOSTRAR SUMA VENTAS
	=============================================*/	

	static public function mdlMostrarSumaVentas($tabla){

		$stmt = Conexion::conectar()->prepare("SELECT SUM(ventas) as total FROM $tabla");

		$stmt -> execute();

		return $stmt -> fetch();

		$stmt -> close();

		$stmt = null;
	}

	/*=============================================
	PRODUCTO SEGUN CATEGORIA
	=============================================*/	
	static public function mdlProductoPorCategoriaPdf($tabla, $idCategoria)
	{

		$query = "SELECT productos.*, c.categoria AS categoria
		FROM $tabla 
		JOIN categorias AS c ON productos.id_categoria=c.id WHERE productos.estado=1";

		// Añadir la condición del proveedor si $idProveedor no es 0
		$query .= ($idCategoria == 0) ? "" : " AND  c.id = $idCategoria";

		$stmt = Conexion::conectar()->prepare($query);

		$stmt->execute();

		return $stmt->fetchAll();
	}

	/*=============================================
	PRODUCTO SEGUN CATEGORIA
	=============================================*/	
	static public function mdlProductoFaltantePdf($tabla)
	{

		$query = "SELECT * FROM $tabla WHERE productos.stock<=0 AND estado=1";


		$stmt = Conexion::conectar()->prepare($query);

		$stmt->execute();

		return $stmt->fetchAll();
	}


}





