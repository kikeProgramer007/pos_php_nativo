<?php

require_once __DIR__ . "/conexion.php";

class ModeloMeseros{

	/*=============================================
	CREAR MESERO
	=============================================*/

	static public function mdlIngresarMesero($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(nombre, documento, telefono, direccion) VALUES (:nombre, :documento,:telefono, :direccion)");

		$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt->bindParam(":documento", $datos["documento"], PDO::PARAM_STR);
		$stmt->bindParam(":telefono", $datos["telefono"], PDO::PARAM_STR);
		$stmt->bindParam(":direccion", $datos["direccion"], PDO::PARAM_STR);
	
	
		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt->close();
		$stmt = null;

	}

	/*=============================================
	MOSTRAR MESEROS
	=============================================*/

	//segundo paso  
	static public function mdlMostrarMeseros($tabla, $item, $valor,$estado){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item  AND estado=:estado ORDER BY id DESC ");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
			$stmt -> bindParam(":estado",$estado,  PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

		}else{
	//segundo paso 
			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE estado=:estado ORDER BY id DESC ");
			$stmt -> bindParam(":estado",$estado,  PDO::PARAM_STR);
			$stmt -> execute();

			return $stmt -> fetchAll();

		}

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	MOSTRAR MESEROS
	=============================================*/

	//segundo paso  
	static public function mdlMostrarMeserosActivoInactivo($tabla, $item, $valor){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item   ORDER BY id DESC ");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
			

			$stmt -> execute();

			return $stmt -> fetch();

		}else{
	//segundo paso 
			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY id DESC ");
		
			$stmt -> execute();

			return $stmt -> fetchAll();

		}

		$stmt -> close();

		$stmt = null;

	}






	/*=============================================
	EDITAR MESERO
	=============================================*/

	static public function mdlEditarMesero($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET nombre = :nombre, documento = :documento,  telefono = :telefono, direccion = :direccion  WHERE id = :id");

		$stmt->bindParam(":id", $datos["id"], PDO::PARAM_INT);
		$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt->bindParam(":documento", $datos["documento"], PDO::PARAM_STR);
		$stmt->bindParam(":telefono", $datos["telefono"], PDO::PARAM_STR);
		$stmt->bindParam(":direccion", $datos["direccion"], PDO::PARAM_STR);
		
		

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt->close();
		$stmt = null;

	}

	/*=============================================
	ELIMINAR MESERO
	=============================================*/

	//cuarto paso

	static public function mdlEliminarMesero($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("UPDATE  $tabla SET estado=0 WHERE id = :id");

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
	RESTAURAR MESERO
	=============================================*/

	//quinto paso

	static public function mdlRestaurarMesero($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("UPDATE  $tabla SET estado=1 WHERE id = :id");

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
	ACTUALIZAR MESERO
	=============================================*/

	static public function mdlActualizarMesero($tabla, $item1, $valor1, $valor){

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

	
}