<?php

require_once "conexion.php";

class ModeloUsuarios{

	/*=============================================
	MOSTRAR USUARIOS
	=============================================*/

	static public function mdlMostrarUsuarios($tabla, $item,$valor,$activo=1){

		$select = "SELECT u.*, COALESCE(p.nombre, u.perfil) AS perfil FROM $tabla u LEFT JOIN perfiles p ON p.id = u.id_perfil";

		try {
			if($item != null){
				$stmt = Conexion::conectar()->prepare("$select WHERE u.$item = :$item AND u.activo=:activo ORDER BY u.id DESC ");
				$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
				$stmt -> bindParam(":activo", $activo, PDO::PARAM_STR);
				$stmt -> execute();
				return $stmt -> fetch();
			}else{
				$stmt = Conexion::conectar()->prepare("$select WHERE u.activo=:activo ORDER BY u.id DESC ");
				$stmt -> bindParam(":activo", $activo, PDO::PARAM_STR);
				$stmt -> execute();
				return $stmt -> fetchAll();
			}
		} catch (Exception $e) {
			if($item != null){
				$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item AND activo=:activo ORDER BY id DESC ");
				$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
				$stmt -> bindParam(":activo", $activo, PDO::PARAM_STR);
				$stmt -> execute();
				return $stmt -> fetch();
			}else{
				$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE   activo=:activo ORDER BY id DESC ");
				$stmt -> bindParam(":activo", $activo, PDO::PARAM_STR);
				$stmt -> execute();
				return $stmt -> fetchAll();
			}
		}
		

		$stmt -> close();

		$stmt = null;

	}



	static public function mdlMostrarUsuariosActivosInactivos($tabla, $item,$valor){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item  ORDER BY id DESC ");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
			

			$stmt -> execute();

			return $stmt -> fetch();

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla   ORDER BY id DESC ");


			$stmt -> execute();

			return $stmt -> fetchAll();

		}
		

		$stmt -> close();

		$stmt = null;

	}
      
	/*=============================================
	REGISTRO DE USUARIO
	=============================================*/

	static public function mdlIngresarUsuario($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(nombre, usuario, password, perfil, id_perfil, foto) VALUES (:nombre, :usuario, :password, :perfil, :id_perfil, :foto)");

		$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt->bindParam(":usuario", $datos["usuario"], PDO::PARAM_STR);
		$stmt->bindParam(":password", $datos["password"], PDO::PARAM_STR);
		$stmt->bindParam(":perfil", $datos["perfil"], PDO::PARAM_STR);
		$stmt->bindParam(":id_perfil", $datos["id_perfil"], PDO::PARAM_INT);
		$stmt->bindParam(":foto", $datos["foto"], PDO::PARAM_STR);

		if($stmt->execute()){

			return "ok";	

		}else{

			return "error";
		
		}

		$stmt->close();
		
		$stmt = null;

	}

	/*=============================================
	EDITAR USUARIO
	=============================================*/

	static public function mdlEditarUsuario($tabla, $datos){
	
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET nombre = :nombre, password = :password, perfil = :perfil, id_perfil = :id_perfil, foto = :foto WHERE usuario = :usuario");

		$stmt -> bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt -> bindParam(":password", $datos["password"], PDO::PARAM_STR);
		$stmt -> bindParam(":perfil", $datos["perfil"], PDO::PARAM_STR);
		$stmt -> bindParam(":id_perfil", $datos["id_perfil"], PDO::PARAM_INT);
		$stmt -> bindParam(":foto", $datos["foto"], PDO::PARAM_STR);
		$stmt -> bindParam(":usuario", $datos["usuario"], PDO::PARAM_STR);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	ACTUALIZAR USUARIO
	=============================================*/

	static public function mdlActualizarUsuario($tabla, $item1, $valor1, $item2, $valor2){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $item1 = :$item1 WHERE $item2 = :$item2");

		$stmt -> bindParam(":".$item1, $valor1, PDO::PARAM_STR);
		$stmt -> bindParam(":".$item2, $valor2, PDO::PARAM_STR);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	BORRAR USUARIO
	=============================================*/

	static public function mdlBorrarUsuario($tabla, $datos){


		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET activo = 0 WHERE id = :id");


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
	RESTAURAR USUARIO
	=============================================*/

	static public function mdlRestaurarUsuario($tabla, $datos){


		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET activo = 1 WHERE id = :id");


		$stmt -> bindParam(":id", $datos, PDO::PARAM_INT);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt -> close();

		$stmt = null;


	}




}