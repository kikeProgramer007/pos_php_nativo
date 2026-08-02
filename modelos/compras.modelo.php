<?php

require_once "conexion.php";
require_once "arqueo.modelo.php";

class ModeloCompras{

	/*=============================================
	MOSTRAR COMPRAS
	=============================================*/

	static public function mdlMostrarCompras($tabla, $item, $valor){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item ORDER BY id  ASC ");
			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
			$stmt -> execute();

			return $stmt -> fetch();

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY id ASC ");
			$stmt -> execute();
			return $stmt -> fetchAll();

		}
		
		$stmt -> close();
		$stmt = null;

	}
	
	
	static public function mdlMostrarDetalleCompras($idCompra) {
		if ($idCompra != null) {
			// Preparar la consulta SQL correctamente
			$stmt = Conexion::conectar()->prepare("SELECT * FROM detalle_compra WHERE id_compra = :id_compra ORDER BY id ASC");
	
			// Enlazar el parámetro correctamente
			$stmt->bindParam(":id_compra", $idCompra, PDO::PARAM_INT); // Cambiado a PDO::PARAM_INT si el ID es un entero
	
			// Ejecutar la consulta
			$stmt->execute();
	
			// Devolver todos los resultados en lugar de solo uno
			return $stmt->fetchAll(PDO::FETCH_ASSOC); // Cambiado a fetchAll para obtener todos los registros
		}
	
		// No se necesita cerrar el stmt aquí si no se ejecuta
		return null; // Devolver null si no hay ID de compra
	}
	

	/*=============================================
	REGISTRO DE COMPRAS
	=============================================*/

	static public function mdlIngresarCompra($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(codigo,total, productos, id_usuario, id_proveedor) VALUES (:codigo,:total,:productos, :id_usuario, :id_proveedor  )");

		$stmt->bindParam(":codigo", $datos["codigo"], PDO::PARAM_STR);
		$stmt->bindParam(":total", $datos["total"], PDO::PARAM_STR);
		$stmt->bindParam(":productos", $datos["productos"], PDO::PARAM_STR);
		$stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
		$stmt->bindParam(":id_proveedor", $datos["id_proveedor"], PDO::PARAM_INT);
		// $stmt->bindParam(":estado", 1, PDO::PARAM_INT);
		// $stmt->bindParam(":fecha_alta", $datos["fecha_alta"], PDO::PARAM_STR);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt->close();
		$stmt = null;

	}

/*=============================================
	REGISTRO DE COMPRAS Y DETALLE (CON VALIDACIÓN DE EFECTIVO Y CAJA)
=============================================*/
static public function mdlRegistrarCompra($tabla, $datos){

	$conexion = Conexion::conectar();

	try {
		$conexion->beginTransaction();

		$idArqueo = intval($datos["id_arqueo_caja"] ?? 0);
		$totalCompra = round(floatval($datos["total"] ?? 0), 2);

		if ($idArqueo <= 0) {
			throw new Exception("No hay caja abierta. No se puede registrar la compra.");
		}

		if ($totalCompra <= 0) {
			throw new Exception("El monto de la compra debe ser mayor a cero.");
		}

		$disponibilidad = ModeloArqueo::mdlCalcularEfectivoDisponible($idArqueo, $conexion);
		if (!$disponibilidad["ok"]) {
			throw new Exception($disponibilidad["mensaje"]);
		}

		$disponible = floatval($disponibilidad["disponible"]);
		if ($totalCompra > $disponible + 0.0001) {
			$faltante = round($totalCompra - $disponible, 2);
			throw new Exception(
				"No hay suficiente efectivo disponible en caja.\n\n" .
				"Disponible: Bs " . number_format($disponible, 2, '.', '') . "\n" .
				"Monto de la compra: Bs " . number_format($totalCompra, 2, '.', '') . "\n" .
				"Faltante: Bs " . number_format($faltante, 2, '.', '')
			);
		}

		$stmt = $conexion->prepare(
			"INSERT INTO $tabla(codigo, total, id_usuario, id_proveedor, id_arqueo_caja)
			 VALUES (:codigo, :total, :id_usuario, :id_proveedor, :id_arqueo_caja)"
		);

		$stmt->bindParam(":codigo", $datos["codigo"], PDO::PARAM_STR);
		$stmt->bindParam(":total", $datos["total"], PDO::PARAM_STR);
		$stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
		$stmt->bindParam(":id_proveedor", $datos["id_proveedor"], PDO::PARAM_INT);
		$stmt->bindParam(":id_arqueo_caja", $idArqueo, PDO::PARAM_INT);

		if (!$stmt->execute()) {
			throw new Exception("Error al registrar la compra");
		}

		$idCompra = $conexion->lastInsertId();
		$productos = json_decode($datos["productos"], true);

		if (!is_array($productos)) {
			throw new Exception("Error: los productos no son un array válido.");
		}

		$stmtDetalle = $conexion->prepare(
			"INSERT INTO detalle_compra(id_compra, id_producto, producto, cantidad, precio_compra, subtotal)
			 VALUES (:id_compra, :id_producto, :producto, :cantidad, :precio_compra, :subtotal)"
		);
		$stmtDetalle->bindParam(":id_compra", $idCompra, PDO::PARAM_INT);

		foreach ($productos as $producto) {
			$stmtDetalle->bindValue(":id_producto", $producto["id"], PDO::PARAM_INT);
			$stmtDetalle->bindValue(":producto", $producto["descripcion"], PDO::PARAM_STR);
			$stmtDetalle->bindValue(":cantidad", $producto["cantidad"], PDO::PARAM_INT);
			$stmtDetalle->bindValue(":precio_compra", $producto["precio"], PDO::PARAM_STR);
			$stmtDetalle->bindValue(":subtotal", $producto["total"], PDO::PARAM_STR);

			if (!$stmtDetalle->execute()) {
				throw new Exception("Error al registrar el detalle de la compra: " . implode(", ", $stmtDetalle->errorInfo()));
			}
		}

		$comprasActualizadas = ModeloArqueo::mdlSumarComprasPorArqueo($idArqueo, $conexion);
		$gastosActualizados = ModeloArqueo::mdlSumarGastosPorArqueo($idArqueo, $conexion);
		$totalEgresos = $comprasActualizadas + $gastosActualizados;

		$stmtArqueo = $conexion->prepare(
			"UPDATE arqueo_caja
			 SET monto_compras = :monto_compras,
			     total_egresos = :total_egresos,
			     resultado_neto = (total_ingresos - :total_egresos2)
			 WHERE id = :id_arqueo
			   AND estado = 'abierta'"
		);
		$stmtArqueo->bindValue(":monto_compras", $comprasActualizadas, PDO::PARAM_STR);
		$stmtArqueo->bindValue(":total_egresos", $totalEgresos, PDO::PARAM_STR);
		$stmtArqueo->bindValue(":total_egresos2", $totalEgresos, PDO::PARAM_STR);
		$stmtArqueo->bindValue(":id_arqueo", $idArqueo, PDO::PARAM_INT);

		if (!$stmtArqueo->execute() || $stmtArqueo->rowCount() === 0) {
			throw new Exception("No se pudo actualizar el arqueo de caja. Verifique que la caja siga abierta.");
		}

		$conexion->commit();

		return [
			"status" => "ok",
			"idCompra" => $idCompra,
			"disponible_restante" => round($disponible - $totalCompra, 2)
		];

	} catch (Exception $e) {
		if ($conexion->inTransaction()) {
			$conexion->rollBack();
		}
		return [
			"status" => "error",
			"mensaje" => $e->getMessage()
		];
	} finally {
		$stmt = null;
		$stmtDetalle = null;
		$conexion = null;
	}
}

	/*=============================================
	ELIMINAR VENTA
	=============================================*/

	static public function mdlEliminarCompra($tabla, $datos){

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
	RANGO FECHAS
	=============================================*/	

	static public function mdlRangoFechasCompras($tabla, $fechaInicial, $fechaFinal){

		if($fechaInicial == null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY id ASC");

			$stmt -> execute();

			return $stmt -> fetchAll();	


		}else if($fechaInicial == $fechaFinal){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE fecha_alta like '%$fechaFinal%'");

			/* $stmt -> bindParam(":fecha", $fechaFinal, PDO::PARAM_STR); */

			$stmt -> execute();

			return $stmt -> fetchAll();

		}else{

			$fechaActual = new DateTime();
			$fechaActual ->add(new DateInterval("P1D"));
			$fechaActualMasUno = $fechaActual->format("Y-m-d");

			$fechaFinal2 = new DateTime($fechaFinal);
			$fechaFinal2 ->add(new DateInterval("P1D"));
			$fechaFinalMasUno = $fechaFinal2->format("Y-m-d");

			if($fechaFinalMasUno == $fechaActualMasUno){

				$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE fecha_alta BETWEEN '$fechaInicial' AND '$fechaFinalMasUno'");

			}else{

				$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE fecha_alta BETWEEN '$fechaInicial' AND '$fechaFinal'");

			}
		
			$stmt -> execute();

			return $stmt -> fetchAll();

		}

	}


/*=============================================
	SUMAR EL TOTAL DE COMPRAS
	=============================================*/

	static public function mdlSumaTotalCompras($tabla){	

		$stmt = Conexion::conectar()->prepare("SELECT SUM(total) as total FROM $tabla");

		$stmt -> execute();

		return $stmt -> fetch();

		$stmt -> close();

		$stmt = null;

	}



/*=============================================
	RANGO FECHAS
	=============================================*/	

	static public function mdlRangoFechasComprasPdf($tabla, $fechaInicial, $fechaFinal, $idProveedor, $idCategoria=0) {
		if ($fechaInicial <= $fechaFinal) {
			$query = "SELECT 
						compras.codigo, 
						compras.fecha_alta, 
						usuarios.nombre AS usuario,
						proveedor.nombre AS proveedor,
						SUM(dc.subtotal) AS total
					  FROM $tabla 
					  JOIN detalle_compra dc ON compras.id = dc.id_compra
					  JOIN productos p ON dc.id_producto = p.id
					  JOIN categorias c ON p.id_categoria = c.id
					  JOIN usuarios ON compras.id_usuario = usuarios.id
					  JOIN proveedor ON compras.id_proveedor = proveedor.id
					  WHERE DATE(compras.fecha_alta) BETWEEN DATE(:fechaInicial) AND DATE(:fechaFinal) AND compras.estado=1";
			
			// Añadir condiciones según proveedor y categoría
			if ($idProveedor != 0) {
				$query .= " AND compras.id_proveedor = :idProveedor";
			}
			if ($idCategoria != 0) {
				$query .= " AND c.id = :idCategoria";
			}
	
			$query .= " GROUP BY compras.codigo, compras.fecha_alta, usuarios.nombre, proveedor.nombre
						ORDER BY compras.fecha_alta ASC;";
	
			$stmt = Conexion::conectar()->prepare($query);
	
			// Asignar parámetros
			$stmt->bindParam(":fechaInicial", $fechaInicial, PDO::PARAM_STR);
			$stmt->bindParam(":fechaFinal", $fechaFinal, PDO::PARAM_STR);
			if ($idProveedor != 0) {
				$stmt->bindParam(":idProveedor", $idProveedor, PDO::PARAM_INT);
			}
			if ($idCategoria != 0) {
				$stmt->bindParam(":idCategoria", $idCategoria, PDO::PARAM_INT);
			}

			$stmt->execute();
	
			return $stmt->fetchAll();
		}
		return [];
	}
	

	static public function mdlComprasRealizadas($tabla, $estado=1){

		$query = "SELECT compras.id ,compras.codigo, compras.fecha_alta, usuarios.nombre as usuario, proveedor.nombre as proveedor, compras.total
		FROM $tabla 
		JOIN proveedor ON compras.id_proveedor = proveedor.id
		JOIN usuarios ON compras.id_usuario = usuarios.id
		WHERE compras.estado=:estado
		ORDER BY fecha_alta  DESC 
		";
	
		$stmt = Conexion::conectar()->prepare($query);
		$stmt->bindParam(":estado", $estado, PDO::PARAM_INT);

		$stmt -> execute();

		return $stmt -> fetchAll();

	}


}