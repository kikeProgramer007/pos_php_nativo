<?php

require_once "conexion.php";

class ModeloClientes{

	/*=============================================
	CREAR CLIENTE
	=============================================*/

	static public function mdlIngresarCliente($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(nombre) VALUES (:nombre)");

		$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
	
		if($stmt->execute()){
			return "ok";
		}else{
			return "error";
		}

		$stmt->close();
		$stmt = null;

	}

	/*=============================================
	MOSTRAR CLIENTES
	=============================================*/

	static public function mdlMostrarClientes($tabla, $item, $valor,$estado){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item  AND estado=:estado ORDER BY id DESC ");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
			$stmt -> bindParam(":estado",$estado, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE estado=:estado ORDER BY id DESC ");
			$stmt -> bindParam(":estado",$estado, PDO::PARAM_STR);
			$stmt -> execute();
			return $stmt -> fetchAll();

		}

		$stmt -> close();

		$stmt = null;

	}


	/*=============================================
	MOSTRAR CLIENTES
	=============================================*/

	static public function mdlMostrarClientesActivoInactivo($tabla, $item, $valor){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item   ORDER BY id DESC ");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
			

			$stmt -> execute();

			return $stmt -> fetch();

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla  ORDER BY id DESC ");
		
			$stmt -> execute();
			return $stmt -> fetchAll();

		}

		$stmt -> close();

		$stmt = null;

	}




	/*=============================================
	EDITAR CLIENTE
	=============================================*/

	static public function mdlEditarCliente($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET nombre = :nombre  WHERE id = :id");

		$stmt->bindParam(":id", $datos["id"], PDO::PARAM_INT);
		$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt->close();
		$stmt = null;

	}

	/*=============================================
	ELIMINAR CLIENTE
	=============================================*/

	static public function mdlEliminarCliente($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET estado=0 WHERE  id = :id");

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
	RESRAURAR CLIENTE
	=============================================*/

	static public function mdlRestaurarCliente($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET estado=1 WHERE  id = :id");

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
	ACTUALIZAR CLIENTE
	=============================================*/

	static public function mdlActualizarCliente($tabla, $item1, $valor1, $valor){

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

	public static function mdlBuscarClientes($tabla, $valor, $page = 1, $perPage = 40) {
		$page = max(1, (int) $page);
		$perPage = max(1, min(50, (int) $perPage));
		$offset = ($page - 1) * $perPage;

		$valor = trim((string) $valor);
		$sqlWhere = "estado = 1";
		if ($valor !== "") {
			$sqlWhere .= " AND nombre LIKE :valor";
		}

		$pdo = Conexion::conectar();

		$stmtCount = $pdo->prepare("SELECT COUNT(*) FROM $tabla WHERE $sqlWhere");
		if ($valor !== "") {
			$searchValue = "%" . $valor . "%";
			$stmtCount->bindParam(":valor", $searchValue, PDO::PARAM_STR);
		}
		$stmtCount->execute();
		$total = (int) $stmtCount->fetchColumn();

		$stmt = $pdo->prepare(
			"SELECT id, nombre FROM $tabla
			 WHERE $sqlWhere
			 ORDER BY nombre ASC
			 LIMIT :limite OFFSET :offset"
		);
		if ($valor !== "") {
			$searchValue = "%" . $valor . "%";
			$stmt->bindParam(":valor", $searchValue, PDO::PARAM_STR);
		}
		$stmt->bindValue(":limite", $perPage, PDO::PARAM_INT);
		$stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return array(
			"items" => $rows,
			"total" => $total,
			"page" => $page,
			"perPage" => $perPage,
			"more" => ($offset + count($rows)) < $total
		);
	}

	

}