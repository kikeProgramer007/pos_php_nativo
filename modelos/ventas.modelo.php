<?php

require_once "conexion.php";

class ModeloVentas
{

	/*=============================================
	MOSTRAR VENTAS
	=============================================*/

	static public function mdlMostrarVentas($tabla, $item, $valor)
	{
		if ($item != null) {
			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item ORDER BY id ASC");
			$stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);
			$stmt->execute();
			$result = $stmt->fetch();
			return $result ? $result : []; // Devuelve un array vacío si no hay resultados
		} else {
			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY id ASC");
			$stmt->execute();
			return $stmt->fetchAll() ?: []; // Devuelve un array vacío si no hay resultados
		}
	}


	static public function mdlMostrarDetalleVentas($idVenta)
	{
		if ($idVenta != null) {
			// Preparar la consulta SQL correctamente
			$stmt = Conexion::conectar()->prepare("SELECT * FROM detalle_venta WHERE id_venta = :id_venta ORDER BY id ASC");

			// Enlazar el parámetro correctamente
			$stmt->bindParam(":id_venta", $idVenta, PDO::PARAM_INT); // Cambiado a PDO::PARAM_INT si el ID es un entero

			// Ejecutar la consulta
			$stmt->execute();

			// Devolver todos los resultados en lugar de solo uno
			return $stmt->fetchAll(PDO::FETCH_ASSOC); // Cambiado a fetchAll para obtener todos los registros
		}

		// No se necesita cerrar el stmt aquí si no se ejecuta
		return null; // Devolver null si no hay ID de compra
	}


	/*=============================================
	REGISTRO DE VENTA
	=============================================*/

	static public function mdlIngresarVenta($tabla, $datos)
	{

		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(codigo, id_mesero, id_vendedor, productos, total) VALUES (:codigo, :id_mesero, :id_vendedor, :productos,  :total)");

		$stmt->bindParam(":codigo", $datos["codigo"], PDO::PARAM_INT);
		$stmt->bindParam(":id_mesero", $datos["id_mesero"], PDO::PARAM_INT);
		$stmt->bindParam(":id_vendedor", $datos["id_vendedor"], PDO::PARAM_INT);
		$stmt->bindParam(":productos", $datos["productos"], PDO::PARAM_STR);
		$stmt->bindParam(":total", $datos["total"], PDO::PARAM_STR);


		if ($stmt->execute()) {

			return "ok";
		} else {

			return "error";
		}

		$stmt->close();
		$stmt = null;
	}


	static public function mdlRegistrarVenta($tabla, $datos)
	{
		// Conexión a la base de datos
		$conexion = Conexion::conectar();

		try {
			// Iniciar la transacción
			$conexion->beginTransaction();

			if ($datos["id_cliente"] == 0) {
				if (empty($datos["cliente"])) {
					$datos["id_cliente"] = 1;
				} else {
					// Prepara la consulta de inserción
					$stmt = $conexion->prepare("INSERT INTO clientes(nombre) VALUES (:nombre)");
					$stmt->bindParam(":nombre", $datos["cliente"], PDO::PARAM_STR);
					// Ejecuta la consulta
					if ($stmt->execute()) {
						// Obtiene el último ID insertado
						$datos["id_cliente"] = $conexion->lastInsertId();
					}
				}
			}

			// 1. Registrar la venta principal en la tabla "ventas"
			$stmt = $conexion->prepare("INSERT INTO $tabla(codigo, id_mesero,id_cliente, id_vendedor, total,total_pagado,nota,tipo_pago,cambio, forma_atencion, id_arqueo_caja) VALUES (:codigo, :id_mesero,:id_cliente, :id_vendedor, :total,:total_pagado, :nota, :tipo_pago,:cambio,:forma_atencion, :id_arqueo_caja)");

			$stmt->bindParam(":codigo", $datos["codigo"], PDO::PARAM_INT);
			$stmt->bindParam(":id_mesero", $datos["id_mesero"], PDO::PARAM_INT);
			$stmt->bindParam(":id_cliente", $datos["id_cliente"], PDO::PARAM_INT);
			$stmt->bindParam(":id_vendedor", $datos["id_vendedor"], PDO::PARAM_INT);
			$stmt->bindParam(":total", $datos["total"], PDO::PARAM_STR);
			$stmt->bindParam(":total_pagado", $datos["total_pagado"], PDO::PARAM_STR);
			$stmt->bindParam(":nota", $datos["nota"], PDO::PARAM_STR);
			$stmt->bindParam(":tipo_pago", $datos["tipo_pago"], PDO::PARAM_STR);
			$stmt->bindParam(":cambio", $datos["cambio"], PDO::PARAM_STR);
			$stmt->bindParam(":forma_atencion", $datos["forma_atencion"], PDO::PARAM_STR);
			$stmt->bindParam(":id_arqueo_caja", $datos["id_arqueo_caja"], PDO::PARAM_INT);

			if (!$stmt->execute()) {
				throw new Exception("Error al registrar la venta");
			}

			// Obtener el ID de la venta recién registrada
			$idVenta = $conexion->lastInsertId();

			// 2. Preparar el statement para insertar los productos en "detalle_venta"
			$stmtDetalle = $conexion->prepare("INSERT INTO detalle_venta(id_venta, id_producto, producto, cantidad, precio_venta, precio_compra, subtotal, preferencias, nota_adicional, forma_atencion) 
											   VALUES (:id_venta, :id_producto, :producto, :cantidad, :precio_venta, :precio_compra, :subtotal, :preferencias, :nota_adicional, :forma_atencion)");

			// Enlazamos los parámetros estáticos (que no cambian en el bucle)
			$stmtDetalle->bindParam(":id_venta", $idVenta, PDO::PARAM_INT);

			$productos = json_decode($datos["productos"], true);  // true para convertir a array asociativo

			// 3. Iterar sobre los productos para registrar el detalle de la venta
			foreach ($productos as $producto) {
				// Validar que las claves necesarias están definidas
				if (!isset($producto["id"], $producto["descripcion"], $producto["cantidad"], $producto["precio"], $producto["precioCompra"], $producto["total"])) {
					throw new Exception("Error: Producto incompleto. Asegúrate de que contiene id, descripcion, cantidad, precioVenta, precioCompra y subtotal.");
				}
				// Enlazar los parámetros dinámicos
				$stmtDetalle->bindValue(":id_producto", $producto["id"], PDO::PARAM_INT);
				$stmtDetalle->bindValue(":producto", $producto["descripcion"], PDO::PARAM_STR);
				$stmtDetalle->bindValue(":cantidad", $producto["cantidad"], PDO::PARAM_INT);
				$stmtDetalle->bindValue(":precio_venta", $producto["precio"], PDO::PARAM_STR);
				$stmtDetalle->bindValue(":precio_compra", $producto["precioCompra"], PDO::PARAM_STR);
				$stmtDetalle->bindValue(":subtotal", $producto["total"], PDO::PARAM_STR);
				$stmtDetalle->bindValue(":preferencias", isset($producto["preferencias"]) ? $producto["preferencias"] : null, PDO::PARAM_STR);
				$stmtDetalle->bindValue(":nota_adicional", isset($producto["nota_adicional"]) ? $producto["nota_adicional"] : null, PDO::PARAM_STR);

				switch ($producto["forma_atencion"]) {
					case 1:
						$formaAtencion = "M";
						break;
					case 2:
						$formaAtencion = "LL";
						break;
					default:
						$formaAtencion = "";
						break;
				}

				$stmtDetalle->bindValue(":forma_atencion", $formaAtencion, PDO::PARAM_STR);

				// Ejecutar el registro para cada producto
				if (!$stmtDetalle->execute()) {
					throw new Exception("Error al registrar el detalle de la venta: " . implode(", ", $stmtDetalle->errorInfo()));
				}
			}

			// Confirmar la transacción si todo sale bien
			$conexion->commit();

			return ["status" =>"ok", "idVenta"=> $idVenta];
		} catch (Exception $e) {
			// Revertir la transacción en caso de error
			$conexion->rollBack();
			return "error: " . $e->getMessage();
		} finally {
			// Cerrar las conexiones
			$stmt = null;
			$stmtDetalle = null;
			$conexion = null;
		}
	}


	/*=============================================
	EDITAR VENTA
	=============================================*/

	static public function mdlEditarVenta($tabla, $datos)
	{

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET  id_mesero = :id_mesero, id_vendedor = :id_vendedor, productos = :productos = : =  total= :total  WHERE codigo = :codigo");

		$stmt->bindParam(":codigo", $datos["codigo"], PDO::PARAM_INT);
		$stmt->bindParam(":id_mesero", $datos["id_mesero"], PDO::PARAM_INT);
		$stmt->bindParam(":id_vendedor", $datos["id_vendedor"], PDO::PARAM_INT);
		$stmt->bindParam(":productos", $datos["productos"], PDO::PARAM_STR);


		$stmt->bindParam(":total", $datos["total"], PDO::PARAM_STR);


		if ($stmt->execute()) {

			return "ok";
		} else {

			return "error";
		}

		$stmt->close();
		$stmt = null;
	}

	/*=============================================
	ELIMINAR VENTA
	=============================================*/

	static public function mdlEliminarVenta($tabla, $datos)
	{

		// $stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id = :id");
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET estado=0 WHERE id = :id");

		$stmt->bindParam(":id", $datos, PDO::PARAM_INT);

		if ($stmt->execute()) {

			return "ok";
		} else {
			return "error";
		}

		$stmt->close();

		$stmt = null;
	}



	/*=============================================
	RANGO FECHAS
	=============================================*/

	static public function mdlRangoFechasVentas($tabla, $fechaInicial, $fechaFinal, $estado = 1)
	{

		if ($fechaInicial == null) {

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla  WHERE $tabla.estado=$estado ORDER BY id DESC");

			$stmt->execute();

			return $stmt->fetchAll();
		} else if ($fechaInicial == $fechaFinal) {

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE fecha like '%$fechaFinal%' AND $tabla.estado=$estado");

			/* $stmt -> bindParam(":fecha", $fechaFinal, PDO::PARAM_STR); */

			$stmt->execute();

			return $stmt->fetchAll();
		} else {

			$fechaActual = new DateTime();
			$fechaActual->add(new DateInterval("P1D"));
			$fechaActualMasUno = $fechaActual->format("Y-m-d");

			$fechaFinal2 = new DateTime($fechaFinal);
			$fechaFinal2->add(new DateInterval("P1D"));
			$fechaFinalMasUno = $fechaFinal2->format("Y-m-d");

			if ($fechaFinalMasUno == $fechaActualMasUno) {

				$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE fecha BETWEEN '$fechaInicial' AND '$fechaFinalMasUno' AND $tabla.estado=$estado");
			} else {


				$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE fecha BETWEEN '$fechaInicial' AND '$fechaFinal' AND $tabla.estado=$estado ORDER BY id DESC");
			}

			$stmt->execute();

			return $stmt->fetchAll();
		}
	}


	/*=============================================
	SUMAR EL TOTAL DE VENTAS
	=============================================*/

	static public function mdlSumaTotalVentas($tabla)
	{
	
		date_default_timezone_set('America/La_Paz');
		$anio = date('Y'); // Obtener el año actual

		$stmt = Conexion::conectar()->prepare("SELECT SUM(total) as total FROM $tabla WHERE YEAR(fecha) = :anio AND $tabla.estado=1");

		$stmt->bindParam(":anio", $anio, PDO::PARAM_INT);
		$stmt->execute();

		return $stmt->fetch();

		$stmt->close();
		$stmt = null;
	}
	/*=============================================
	SUMAR EL TOTAL DE VENTAS - MES
	=============================================*/

	static public function mdlVentasTotalMes($tabla)
	{
		date_default_timezone_set('America/La_Paz');
		$yearactual = date('Y');
		$mesActual = date('m');
		$stmt = Conexion::conectar()->prepare("SELECT SUM(total) as total FROM $tabla WHERE MONTH(fecha)='$mesActual' AND YEAR(fecha) = '$yearactual' AND $tabla.estado=1");

		$stmt->execute();

		return $stmt->fetch();

		$stmt->close();

		$stmt = null;
	}

	/*=============================================
	SUMAR EL TOTAL DE VENTAS - DIA
	=============================================*/

	static public function mdlVentasTotalDia($tabla)
	{
		date_default_timezone_set('America/La_Paz');
		$hoy = date('Y-m-d');
		$stmt = Conexion::conectar()->prepare("SELECT SUM(total) as total FROM $tabla WHERE DATE(fecha)='$hoy' AND $tabla.estado=1");

		$stmt->execute();

		return $stmt->fetch();

		$stmt->close();

		$stmt = null;
	}

	/*=============================================
	RANGO DE VENTAS - POR MESERO
	=============================================*/

	static public  function mdlRangoFechasVentasPdf($tabla, $fechaInicial, $fechaFinal, $idMesero, $idCategoria, $idCliente, $soloEliminados)
	{
		$query = "SELECT 
						ventas.codigo, 
						ventas.fecha, 
						usuarios.nombre AS usuario, 
						meseros.nombre AS mesero, 
						clientes.nombre AS cliente, 
						ventas.tipo_pago,
						SUM(dv.subtotal) AS total
					FROM $tabla
					JOIN detalle_venta dv ON ventas.id = dv.id_venta
					JOIN productos p ON dv.id_producto = p.id
					JOIN categorias c ON p.id_categoria = c.id
					JOIN meseros ON ventas.id_mesero = meseros.id
					JOIN usuarios ON ventas.id_vendedor = usuarios.id
					JOIN clientes ON ventas.id_cliente = clientes.id
					WHERE DATE(ventas.fecha) BETWEEN DATE(:fechaInicial) AND DATE(:fechaFinal) 
					";

		// Añadir la condición del mesero solo si $idMesero no es 0
		if ($idMesero != 0) {
			$query .= " AND ventas.id_mesero = :idMesero";
		}
		if ($idCategoria != 0) {
			$query .= " AND  c.id =:idCategoria";
		}
		if ($idCliente != 0) {
			$query .= " AND  ventas.id_cliente =:idCliente";
		}
		// echo $soloEliminados;
		if ($soloEliminados=='true'){
			$query .= " AND ventas.estado =0 ";
		}else{
			$query .= " AND ventas.estado =1 ";
		}
		
		$query .= " GROUP BY ventas.codigo, ventas.fecha, usuarios.nombre, meseros.nombre, clientes.nombre, ventas.tipo_pago ORDER BY ventas.fecha ASC;";

		// Preparar la consulta
		$stmt = Conexion::conectar()->prepare($query);

		// Enlazar parámetros
		$stmt->bindParam(":fechaInicial", $fechaInicial, PDO::PARAM_STR);
		$stmt->bindParam(":fechaFinal", $fechaFinal, PDO::PARAM_STR);

		// Si $idMesero no es 0, enlazar también el parámetro del mesero
		if ($idMesero != 0) {
			$stmt->bindParam(":idMesero", $idMesero, PDO::PARAM_INT);
		}
		if ($idCategoria != 0) {
			$stmt->bindParam(":idCategoria", $idCategoria, PDO::PARAM_INT);
		}
		if ($idCliente != 0) {
			$stmt->bindParam(":idCliente", $idCliente, PDO::PARAM_INT);
		}

		// Ejecutar la consulta
		$stmt->execute();

		// Retornar los resultados
		return $stmt->fetchAll();
	}

	/*=============================================
	RANGO DE VENTAS - TOP POR MESERO
	=============================================*/
	static public function mdlRangoFechasVentasTopMeseroPdf($tabla, $fechaInicial, $fechaFinal)
	{

		if ($fechaInicial <= $fechaFinal) {

			$query = "SELECT meseros.nombre as mesero, COUNT(ventas.id) as cantidad, SUM(ventas.total) as total
					FROM $tabla 
					JOIN meseros ON ventas.id_mesero = meseros.id
					WHERE DATE(ventas.fecha) BETWEEN DATE('$fechaInicial') AND DATE('$fechaFinal') 
					AND ventas.estado=1
					GROUP BY meseros.nombre ORDER BY SUM(ventas.total) DESC;";

			$stmt = Conexion::conectar()->prepare($query);

			$stmt->execute();

			return $stmt->fetchAll();
		}
	}

	/*=============================================
	TOP PRODUCTO MAS VENDIDOS SEGUNN RANGO FECHAS
	=============================================*/
	static public function mdlRangoFechasTopProductoVendidos($tabla, $fechaInicial, $fechaFinal)
	{
		if ($fechaInicial <= $fechaFinal) {

			// Consulta SQL para obtener las ventas en el rango de fechas
			$query	= 	"SELECT 
							COUNT(dv.id_producto) AS cant_ventas, 
							dv.id_producto, 
							SUM(dv.cantidad) AS cantidad, 
							p.descripcion
						FROM $tabla
						JOIN detalle_venta AS dv ON ventas.id = dv.id_venta
						JOIN productos AS p ON p.id = dv.id_producto
						WHERE DATE(ventas.fecha) BETWEEN DATE(:fechaInicio) AND DATE(:fechaFin)
						AND ventas.estado=1
						GROUP BY dv.id_producto, p.descripcion
						ORDER BY SUM(dv.cantidad) DESC;";

			$stmt = Conexion::conectar()->prepare($query);
			// Vincular los parámetros de las fechas
			$stmt->bindParam(':fechaInicio', $fechaInicial);
			$stmt->bindParam(':fechaFin', $fechaFinal);

			$stmt->execute();
			// Obtener los resultados
			$ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);

			// Devolver los productos más vendidos
			return $ventas;
			// return $stmt->fetchAll();
		}
	}

	static public function mdlRangoFechaVentasRealizadas($tabla, $fechaInicial, $fechaFinal, $estado=1)
	{
		date_default_timezone_set('America/La_Paz');

		$query = "SELECT ventas.id, ventas.codigo, ventas.fecha, usuarios.nombre as usuario, meseros.nombre as mesero, ventas.total
				  FROM $tabla 
				  JOIN usuarios ON ventas.id_vendedor = usuarios.id
				  JOIN meseros ON ventas.id_mesero = meseros.id";



		if ($fechaInicial == null && $fechaFinal == null) {
			$stmt = Conexion::conectar()->prepare($query . " WHERE ventas.estado =:estado ORDER BY ventas.fecha DESC");
			$stmt->bindParam(":estado", $estado, PDO::PARAM_STR);
			$stmt->execute();
			return $stmt->fetchAll();
		}

		if ($fechaInicial == null) {
			$stmt = Conexion::conectar()->prepare($query . " WHERE ventas.estado =:estado ORDER BY ventas.fecha DESC");
		} else if ($fechaInicial == $fechaFinal) {
			$stmt = Conexion::conectar()->prepare($query . " WHERE DATE(ventas.fecha) = :fecha AND ventas.estado =:estado ORDER BY ventas.fecha DESC");
			$stmt->bindParam(":fecha", $fechaFinal, PDO::PARAM_STR);
		} else {
			$stmt = Conexion::conectar()->prepare($query . " WHERE DATE(ventas.fecha) BETWEEN DATE(:fechaInicial) AND DATE(:fechaFinal) AND ventas.estado =:estado ORDER BY ventas.fecha DESC");
			$stmt->bindParam(":fechaInicial", $fechaInicial, PDO::PARAM_STR);
			$stmt->bindParam(":fechaFinal", $fechaFinal, PDO::PARAM_STR);
		}
		$stmt->bindParam(":estado", $estado, PDO::PARAM_STR);
		
		$stmt->execute();
		return $stmt->fetchAll();
	}
}
