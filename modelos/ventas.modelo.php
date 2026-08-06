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
			$stmt = $conexion->prepare("INSERT INTO $tabla(codigo, id_mesero,id_cliente, id_vendedor, total, total_bruto, total_descuento, total_efectivo,total_qr,total_pagado,nota,tipo_pago,cambio, forma_atencion, id_arqueo_caja, estado_pago, fecha_pago) VALUES (:codigo, :id_mesero,:id_cliente, :id_vendedor, :total, :total_bruto, :total_descuento, :total_efectivo,:total_qr, :total_pagado, :nota, :tipo_pago,:cambio,:forma_atencion, :id_arqueo_caja, :estado_pago, :fecha_pago)");

			$stmt->bindParam(":codigo", $datos["codigo"], PDO::PARAM_INT);
			$stmt->bindParam(":id_mesero", $datos["id_mesero"], PDO::PARAM_INT);
			$stmt->bindParam(":id_cliente", $datos["id_cliente"], PDO::PARAM_INT);
			$stmt->bindParam(":id_vendedor", $datos["id_vendedor"], PDO::PARAM_INT);
			$stmt->bindParam(":total", $datos["total"], PDO::PARAM_STR);
			$totalBruto = $datos["total_bruto"] ?? $datos["total"];
			$totalDescuento = $datos["total_descuento"] ?? 0;
			$stmt->bindParam(":total_bruto", $totalBruto, PDO::PARAM_STR);
			$stmt->bindParam(":total_descuento", $totalDescuento, PDO::PARAM_STR);
			$stmt->bindParam(":total_efectivo", $datos["total_efectivo"], PDO::PARAM_STR);
			$stmt->bindParam(":total_qr", $datos["total_qr"], PDO::PARAM_STR);
			$stmt->bindParam(":total_pagado", $datos["total_pagado"], PDO::PARAM_STR);
			$stmt->bindParam(":nota", $datos["nota"], PDO::PARAM_STR);
			$stmt->bindParam(":tipo_pago", $datos["tipo_pago"], PDO::PARAM_STR);
			$stmt->bindParam(":cambio", $datos["cambio"], PDO::PARAM_STR);
			$stmt->bindParam(":forma_atencion", $datos["forma_atencion"], PDO::PARAM_STR);
			$stmt->bindParam(":id_arqueo_caja", $datos["id_arqueo_caja"], PDO::PARAM_INT);
			$stmt->bindParam(":estado_pago", $datos["estado_pago"], PDO::PARAM_STR);
			$stmt->bindParam(":fecha_pago", $datos["fecha_pago"], PDO::PARAM_STR);

			if (!$stmt->execute()) {
				throw new Exception("Error al registrar la venta");
			}

			// Obtener el ID de la venta recién registrada
			$idVenta = $conexion->lastInsertId();

			// 2. Preparar el statement para insertar los productos en "detalle_venta"
			$stmtDetalle = $conexion->prepare("INSERT INTO detalle_venta(id_venta, id_producto, producto, cantidad, precio_venta, precio_original, tipo_descuento, valor_descuento, descuento_unitario, descuento_total, id_promocion, id_intervalo_promocion, nombre_promocion, precio_compra, subtotal, preferencias, nota_adicional, forma_atencion) 
											   VALUES (:id_venta, :id_producto, :producto, :cantidad, :precio_venta, :precio_original, :tipo_descuento, :valor_descuento, :descuento_unitario, :descuento_total, :id_promocion, :id_intervalo_promocion, :nombre_promocion, :precio_compra, :subtotal, :preferencias, :nota_adicional, :forma_atencion)");

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
				self::bindPromocionDetalle($stmtDetalle, $producto);
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
		// Solo ventas cobradas deben alimentar gráficos y totales monetarios
		$filtroPago = ($estado == 1) ? " AND $tabla.estado_pago = 'PAGADA'" : "";

		if ($fechaInicial == null) {

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla  WHERE $tabla.estado=$estado$filtroPago ORDER BY id DESC");

			$stmt->execute();

			return $stmt->fetchAll();
		} else if ($fechaInicial == $fechaFinal) {

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE fecha like '%$fechaFinal%' AND $tabla.estado=$estado$filtroPago");

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

				$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE fecha BETWEEN '$fechaInicial' AND '$fechaFinalMasUno' AND $tabla.estado=$estado$filtroPago");
			} else {


				$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE fecha BETWEEN '$fechaInicial' AND '$fechaFinal' AND $tabla.estado=$estado$filtroPago ORDER BY id DESC");
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

		$stmt = Conexion::conectar()->prepare("SELECT SUM(total) as total FROM $tabla WHERE YEAR(fecha) = :anio AND $tabla.estado=1 AND $tabla.estado_pago = 'PAGADA'");

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
		$stmt = Conexion::conectar()->prepare("SELECT SUM(total) as total FROM $tabla WHERE MONTH(fecha)='$mesActual' AND YEAR(fecha) = '$yearactual' AND $tabla.estado=1 AND $tabla.estado_pago = 'PAGADA'");

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
		$stmt = Conexion::conectar()->prepare("SELECT SUM(total) as total, SUM(total_qr) as total_qr, SUM(total_efectivo) as total_efectivo FROM $tabla WHERE DATE(fecha)='$hoy' AND $tabla.estado=1 AND $tabla.estado_pago = 'PAGADA'");

		$stmt->execute();

		return $stmt->fetch();

		$stmt->close();

		$stmt = null;
	}

	/*=============================================
	RANGO DE VENTAS - POR MESERO
	=============================================*/

	static public  function mdlRangoFechasVentasPdf($tabla, $fechaInicial, $fechaFinal, $idMesero, $idCategoria, $idCliente, $soloEliminados, $tipoPago = "0", $estadoPago = "0")
	{
		$query = "SELECT 
						ventas.codigo, 
						ventas.fecha, 
						usuarios.nombre AS usuario, 
						meseros.nombre AS mesero, 
						clientes.nombre AS cliente, 
						ventas.tipo_pago,
						ventas.estado_pago,
						ventas.total_qr,
						ventas.total_efectivo,
						SUM(dv.subtotal) AS total,
						SUM(COALESCE(dv.descuento_total, 0)) AS total_descuento,
						SUM(COALESCE(dv.precio_original, dv.precio_venta, 0) * dv.cantidad) AS total_bruto
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
		if ($tipoPago != "0") {
			$query .= " AND ventas.tipo_pago = :tipoPago";
		}
		// echo $soloEliminados;
		if ($soloEliminados=='true'){
			$query .= " AND ventas.estado =0 ";
		}else{
			$query .= " AND ventas.estado =1 ";
		}

		// 0 = Todos, 1 = Pendiente, 2 = Pagado
		if ($estadoPago == "1") {
			$query .= " AND ventas.estado_pago = 'PENDIENTE'";
		} elseif ($estadoPago == "2") {
			$query .= " AND ventas.estado_pago = 'PAGADA'";
		}
		
		$query .= " GROUP BY 
                ventas.codigo, 
                ventas.fecha, 
                usuarios.nombre, 
                meseros.nombre, 
                clientes.nombre, 
                ventas.tipo_pago,
                ventas.estado_pago,
                ventas.total_qr,
                ventas.total_efectivo
            ORDER BY ventas.fecha ASC;";

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
		if ($tipoPago != "0") {
			$stmt->bindParam(":tipoPago", $tipoPago, PDO::PARAM_STR);
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
					AND ventas.estado_pago = 'PAGADA'
					GROUP BY meseros.nombre ORDER BY SUM(ventas.total) DESC;";

			$stmt = Conexion::conectar()->prepare($query);

			$stmt->execute();

			return $stmt->fetchAll();
		}
	}

	/*=============================================
	TOP PRODUCTO MAS VENDIDOS SEGUNN RANGO FECHAS
	=============================================*/
	static public function mdlRangoFechasTopProductoVendidos($tabla, $fechaInicial, $fechaFinal, $idCategoria = 0)
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
						AND ventas.estado_pago = 'PAGADA'";

			if ($idCategoria != 0) {
				$query .= " AND p.id_categoria = :idCategoria";
			}

			$query .= " GROUP BY dv.id_producto, p.descripcion
						ORDER BY SUM(dv.cantidad) DESC;";

			$stmt = Conexion::conectar()->prepare($query);
			// Vincular los parámetros de las fechas
			$stmt->bindParam(':fechaInicio', $fechaInicial);
			$stmt->bindParam(':fechaFin', $fechaFinal);

			if ($idCategoria != 0) {
				$stmt->bindParam(':idCategoria', $idCategoria, PDO::PARAM_INT);
			}

			$stmt->execute();
			// Obtener los resultados
			$ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);

			// Devolver los productos más vendidos
			return $ventas;
			// return $stmt->fetchAll();
		}
	}

	static public function mdlRangoFechaVentasRealizadas($tabla, $fechaInicial, $fechaFinal, $estado = 1, $estadoPago = null, $idMesero = null)
	{
		date_default_timezone_set('America/La_Paz');

		$query = "SELECT ventas.id, ventas.codigo, ventas.fecha, ventas.estado_pago, ventas.id_mesero,
				  ventas.id_arqueo_caja, arqueo_caja.estado AS estado_arqueo,
				  usuarios.nombre as usuario, meseros.nombre as mesero, ventas.total, ventas.tipo_pago, clientes.nombre as cliente 
				  FROM $tabla 
				  JOIN usuarios ON ventas.id_vendedor = usuarios.id
				  JOIN clientes ON ventas.id_cliente = clientes.id
				  JOIN meseros ON ventas.id_mesero = meseros.id
				  LEFT JOIN arqueo_caja ON ventas.id_arqueo_caja = arqueo_caja.id
				  WHERE ventas.estado = :estado";

		if ($estadoPago !== null && $estadoPago !== "" && $estadoPago !== "todos") {
			$query .= " AND ventas.estado_pago = :estado_pago";
		}

		if ($idMesero !== null && $idMesero !== "" && $idMesero !== "0") {
			$query .= " AND ventas.id_mesero = :id_mesero";
		}

		if ($fechaInicial != null && $fechaFinal != null) {
			if ($fechaInicial == $fechaFinal) {
				$query .= " AND DATE(ventas.fecha) = :fecha";
			} else {
				$query .= " AND DATE(ventas.fecha) BETWEEN DATE(:fechaInicial) AND DATE(:fechaFinal)";
			}
		}

		$query .= " ORDER BY ventas.fecha DESC";

		$stmt = Conexion::conectar()->prepare($query);
		$stmt->bindParam(":estado", $estado, PDO::PARAM_STR);

		if ($estadoPago !== null && $estadoPago !== "" && $estadoPago !== "todos") {
			$stmt->bindParam(":estado_pago", $estadoPago, PDO::PARAM_STR);
		}

		if ($idMesero !== null && $idMesero !== "" && $idMesero !== "0") {
			$stmt->bindParam(":id_mesero", $idMesero, PDO::PARAM_INT);
		}

		if ($fechaInicial != null && $fechaFinal != null) {
			if ($fechaInicial == $fechaFinal) {
				$stmt->bindParam(":fecha", $fechaFinal, PDO::PARAM_STR);
			} else {
				$stmt->bindParam(":fechaInicial", $fechaInicial, PDO::PARAM_STR);
				$stmt->bindParam(":fechaFinal", $fechaFinal, PDO::PARAM_STR);
			}
		}

		$stmt->execute();
		return $stmt->fetchAll();
	}

	/*=============================================
	RESUMEN DE CUENTAS PENDIENTES (informativo / global)
	=============================================*/
	static public function mdlResumenCuentasPendientes()
	{
		$stmt = Conexion::conectar()->prepare(
			"SELECT COUNT(*) AS cantidad, COALESCE(SUM(total), 0) AS total_por_cobrar
			 FROM ventas
			 WHERE estado = 1 AND estado_pago = 'PENDIENTE'"
		);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	/*=============================================
	RESUMEN DE CUENTAS PENDIENTES POR ARQUEO
	=============================================*/
	static public function mdlResumenCuentasPendientesPorArqueo($idArqueo)
	{
		$stmt = Conexion::conectar()->prepare(
			"SELECT COUNT(*) AS cantidad, COALESCE(SUM(total), 0) AS total_por_cobrar
			 FROM ventas
			 WHERE id_arqueo_caja = :id_arqueo_caja
			   AND estado = 1
			   AND estado_pago = 'PENDIENTE'"
		);
		$stmt->bindParam(":id_arqueo_caja", $idArqueo, PDO::PARAM_INT);
		$stmt->execute();
		$resumen = $stmt->fetch(PDO::FETCH_ASSOC);

		return [
			"cantidad" => intval($resumen["cantidad"] ?? 0),
			"total_por_cobrar" => floatval($resumen["total_por_cobrar"] ?? 0)
		];
	}

	/*=============================================
	RESUMEN DE CUENTAS PENDIENTES DE CAJAS CERRADAS
	=============================================*/
	static public function mdlResumenCuentasPendientesCajasCerradas()
	{
		$stmt = Conexion::conectar()->prepare(
			"SELECT COUNT(*) AS cantidad, COALESCE(SUM(v.total), 0) AS total_por_cobrar
			 FROM ventas v
			 LEFT JOIN arqueo_caja a ON a.id = v.id_arqueo_caja
			 WHERE v.estado = 1
			   AND v.estado_pago = 'PENDIENTE'
			   AND (a.id IS NULL OR a.estado = 'cerrada')"
		);
		$stmt->execute();
		$resumen = $stmt->fetch(PDO::FETCH_ASSOC);

		return [
			"cantidad" => intval($resumen["cantidad"] ?? 0),
			"total_por_cobrar" => floatval($resumen["total_por_cobrar"] ?? 0)
		];
	}

	/*=============================================
	ACTUALIZAR CUENTA PENDIENTE
	=============================================*/
	static public function mdlActualizarCuentaPendiente($datos)
	{
		$conexion = Conexion::conectar();

		try {
			$conexion->beginTransaction();

			$stmtVenta = $conexion->prepare(
				"SELECT * FROM ventas WHERE id = :id AND estado = 1 AND estado_pago = 'PENDIENTE' FOR UPDATE"
			);
			$stmtVenta->bindParam(":id", $datos["id_venta"], PDO::PARAM_INT);
			$stmtVenta->execute();
			$ventaActual = $stmtVenta->fetch(PDO::FETCH_ASSOC);

			if (!$ventaActual) {
				throw new Exception("La cuenta no existe o ya fue cobrada.");
			}

			if ($datos["id_cliente"] == 0) {
				if (empty($datos["cliente"])) {
					$datos["id_cliente"] = 1;
				} else {
					$stmtCliente = $conexion->prepare("INSERT INTO clientes(nombre) VALUES (:nombre)");
					$stmtCliente->bindParam(":nombre", $datos["cliente"], PDO::PARAM_STR);
					if ($stmtCliente->execute()) {
						$datos["id_cliente"] = $conexion->lastInsertId();
					}
				}
			}

			$stmtUpdate = $conexion->prepare(
				"UPDATE ventas SET id_mesero = :id_mesero, id_cliente = :id_cliente, total = :total,
				 total_bruto = :total_bruto, total_descuento = :total_descuento,
				 nota = :nota, forma_atencion = :forma_atencion
				 WHERE id = :id_venta AND estado_pago = 'PENDIENTE'"
			);
			$stmtUpdate->bindParam(":id_mesero", $datos["id_mesero"], PDO::PARAM_INT);
			$stmtUpdate->bindParam(":id_cliente", $datos["id_cliente"], PDO::PARAM_INT);
			$stmtUpdate->bindParam(":total", $datos["total"], PDO::PARAM_STR);
			$totalBrutoUpd = $datos["total_bruto"] ?? $datos["total"];
			$totalDescUpd = $datos["total_descuento"] ?? 0;
			$stmtUpdate->bindParam(":total_bruto", $totalBrutoUpd, PDO::PARAM_STR);
			$stmtUpdate->bindParam(":total_descuento", $totalDescUpd, PDO::PARAM_STR);
			$stmtUpdate->bindParam(":nota", $datos["nota"], PDO::PARAM_STR);
			$stmtUpdate->bindParam(":forma_atencion", $datos["forma_atencion"], PDO::PARAM_STR);
			$stmtUpdate->bindParam(":id_venta", $datos["id_venta"], PDO::PARAM_INT);

			if (!$stmtUpdate->execute()) {
				throw new Exception("Error al actualizar la cuenta pendiente.");
			}

			$detalleActual = self::mdlMostrarDetalleVentas($datos["id_venta"]);
			$productosNuevos = json_decode($datos["productos"], true);
			if (!is_array($productosNuevos)) {
				throw new Exception("Lista de productos inválida.");
			}

			$idsDetalleUsados = [];
			$idsDetalleNuevos = [];

			$stmtDetalle = $conexion->prepare(
				"INSERT INTO detalle_venta(id_venta, id_producto, producto, cantidad, precio_venta, precio_original, tipo_descuento, valor_descuento, descuento_unitario, descuento_total, id_promocion, id_intervalo_promocion, nombre_promocion, precio_compra, subtotal, preferencias, nota_adicional, forma_atencion)
				 VALUES (:id_venta, :id_producto, :producto, :cantidad, :precio_venta, :precio_original, :tipo_descuento, :valor_descuento, :descuento_unitario, :descuento_total, :id_promocion, :id_intervalo_promocion, :nombre_promocion, :precio_compra, :subtotal, :preferencias, :nota_adicional, :forma_atencion)"
			);
			$stmtDetalle->bindParam(":id_venta", $datos["id_venta"], PDO::PARAM_INT);

			$stmtUpdateDetalle = $conexion->prepare(
				"UPDATE detalle_venta SET id_producto = :id_producto, producto = :producto, cantidad = :cantidad,
				 precio_venta = :precio_venta, precio_original = :precio_original, tipo_descuento = :tipo_descuento,
				 valor_descuento = :valor_descuento, descuento_unitario = :descuento_unitario, descuento_total = :descuento_total,
				 id_promocion = :id_promocion, id_intervalo_promocion = :id_intervalo_promocion, nombre_promocion = :nombre_promocion,
				 precio_compra = :precio_compra, subtotal = :subtotal,
				 preferencias = :preferencias, nota_adicional = :nota_adicional, forma_atencion = :forma_atencion
				 WHERE id = :id_detalle AND id_venta = :id_venta"
			);
			$stmtUpdateDetalle->bindParam(":id_venta", $datos["id_venta"], PDO::PARAM_INT);

			foreach ($productosNuevos as $producto) {
				if (!isset($producto["id"], $producto["descripcion"], $producto["cantidad"], $producto["precio"], $producto["precioCompra"], $producto["total"])) {
					throw new Exception("Producto incompleto en la lista.");
				}

				$formaAtencion = "";
				switch ($producto["forma_atencion"]) {
					case 1:
					case "1":
						$formaAtencion = "M";
						break;
					case 2:
					case "2":
						$formaAtencion = "LL";
						break;
					default:
						$formaAtencion = "";
						break;
				}

				$idDetalle = isset($producto["idDetalle"]) ? intval($producto["idDetalle"]) : 0;

				if ($idDetalle > 0) {
					$idsDetalleUsados[] = $idDetalle;
					$stmtUpdateDetalle->bindValue(":id_producto", $producto["id"], PDO::PARAM_INT);
					$stmtUpdateDetalle->bindValue(":producto", $producto["descripcion"], PDO::PARAM_STR);
					$stmtUpdateDetalle->bindValue(":cantidad", $producto["cantidad"], PDO::PARAM_INT);
					$stmtUpdateDetalle->bindValue(":precio_venta", $producto["precio"], PDO::PARAM_STR);
					self::bindPromocionDetalle($stmtUpdateDetalle, $producto);
					$stmtUpdateDetalle->bindValue(":precio_compra", $producto["precioCompra"], PDO::PARAM_STR);
					$stmtUpdateDetalle->bindValue(":subtotal", $producto["total"], PDO::PARAM_STR);
					$stmtUpdateDetalle->bindValue(":preferencias", isset($producto["preferencias"]) ? $producto["preferencias"] : null, PDO::PARAM_STR);
					$stmtUpdateDetalle->bindValue(":nota_adicional", isset($producto["nota_adicional"]) ? $producto["nota_adicional"] : null, PDO::PARAM_STR);
					$stmtUpdateDetalle->bindValue(":forma_atencion", $formaAtencion, PDO::PARAM_STR);
					$stmtUpdateDetalle->bindValue(":id_detalle", $idDetalle, PDO::PARAM_INT);

					if (!$stmtUpdateDetalle->execute()) {
						throw new Exception("Error al actualizar detalle de venta.");
					}
				} else {
					$stmtDetalle->bindValue(":id_producto", $producto["id"], PDO::PARAM_INT);
					$stmtDetalle->bindValue(":producto", $producto["descripcion"], PDO::PARAM_STR);
					$stmtDetalle->bindValue(":cantidad", $producto["cantidad"], PDO::PARAM_INT);
					$stmtDetalle->bindValue(":precio_venta", $producto["precio"], PDO::PARAM_STR);
					self::bindPromocionDetalle($stmtDetalle, $producto);
					$stmtDetalle->bindValue(":precio_compra", $producto["precioCompra"], PDO::PARAM_STR);
					$stmtDetalle->bindValue(":subtotal", $producto["total"], PDO::PARAM_STR);
					$stmtDetalle->bindValue(":preferencias", isset($producto["preferencias"]) ? $producto["preferencias"] : null, PDO::PARAM_STR);
					$stmtDetalle->bindValue(":nota_adicional", isset($producto["nota_adicional"]) ? $producto["nota_adicional"] : null, PDO::PARAM_STR);
					$stmtDetalle->bindValue(":forma_atencion", $formaAtencion, PDO::PARAM_STR);

					if (!$stmtDetalle->execute()) {
						throw new Exception("Error al registrar nuevo detalle de venta.");
					}

					$idsDetalleNuevos[] = $conexion->lastInsertId();
				}
			}

			foreach ($detalleActual as $linea) {
				if (!in_array($linea["id"], $idsDetalleUsados)) {
					$stmtDelete = $conexion->prepare("DELETE FROM detalle_venta WHERE id = :id AND id_venta = :id_venta");
					$stmtDelete->bindParam(":id", $linea["id"], PDO::PARAM_INT);
					$stmtDelete->bindParam(":id_venta", $datos["id_venta"], PDO::PARAM_INT);
					$stmtDelete->execute();
				}
			}

			$conexion->commit();

			return [
				"status" => "ok",
				"idVenta" => $datos["id_venta"],
				"idsDetalleNuevos" => $idsDetalleNuevos
			];
		} catch (Exception $e) {
			$conexion->rollBack();
			return "error: " . $e->getMessage();
		}
	}

	/*=============================================
	COBRAR CUENTA PENDIENTE
	=============================================*/
	static public function mdlCobrarCuentaPendiente($datos)
	{
		$conexion = Conexion::conectar();

		try {
			$conexion->beginTransaction();

			$stmtVenta = $conexion->prepare(
				"SELECT v.*, a.estado AS estado_arqueo
				 FROM ventas v
				 LEFT JOIN arqueo_caja a ON a.id = v.id_arqueo_caja
				 WHERE v.id = :id AND v.estado = 1 AND v.estado_pago = 'PENDIENTE'
				 FOR UPDATE"
			);
			$stmtVenta->bindParam(":id", $datos["id_venta"], PDO::PARAM_INT);
			$stmtVenta->execute();
			$venta = $stmtVenta->fetch(PDO::FETCH_ASSOC);

			if (!$venta) {
				throw new Exception("La cuenta no existe o ya fue cobrada.");
			}

			if (empty($venta["id_arqueo_caja"])) {
				throw new Exception("Esta cuenta no tiene una caja asociada y no puede cobrarse.");
			}

			if (($venta["estado_arqueo"] ?? "") !== "abierta") {
				throw new Exception("Esta cuenta pertenece a una caja cerrada y no puede cobrarse.");
			}

			if (intval($venta["id_arqueo_caja"]) !== intval($datos["id_arqueo_caja"])) {
				throw new Exception("Esta cuenta pertenece a una caja diferente y no puede cobrarse en la caja actual.");
			}

			$stmtArqueo = $conexion->prepare(
				"SELECT id FROM arqueo_caja WHERE id = :id AND estado = 'abierta' FOR UPDATE"
			);
			$stmtArqueo->bindParam(":id", $datos["id_arqueo_caja"], PDO::PARAM_INT);
			$stmtArqueo->execute();
			if (!$stmtArqueo->fetch(PDO::FETCH_ASSOC)) {
				throw new Exception("Esta cuenta pertenece a una caja cerrada y no puede cobrarse.");
			}

			$stmtUpdate = $conexion->prepare(
				"UPDATE ventas SET estado_pago = 'PAGADA', fecha_pago = :fecha_pago,
				 tipo_pago = :tipo_pago, total_efectivo = :total_efectivo, total_qr = :total_qr,
				 total_pagado = :total_pagado, cambio = :cambio, total = :total
				 WHERE id = :id_venta
				   AND estado = 1
				   AND estado_pago = 'PENDIENTE'
				   AND id_arqueo_caja = :id_arqueo_caja"
			);

			$stmtUpdate->bindParam(":fecha_pago", $datos["fecha_pago"], PDO::PARAM_STR);
			$stmtUpdate->bindParam(":tipo_pago", $datos["tipo_pago"], PDO::PARAM_STR);
			$stmtUpdate->bindParam(":total_efectivo", $datos["total_efectivo"], PDO::PARAM_STR);
			$stmtUpdate->bindParam(":total_qr", $datos["total_qr"], PDO::PARAM_STR);
			$stmtUpdate->bindParam(":total_pagado", $datos["total_pagado"], PDO::PARAM_STR);
			$stmtUpdate->bindParam(":cambio", $datos["cambio"], PDO::PARAM_STR);
			$stmtUpdate->bindParam(":total", $datos["total"], PDO::PARAM_STR);
			$stmtUpdate->bindParam(":id_arqueo_caja", $datos["id_arqueo_caja"], PDO::PARAM_INT);
			$stmtUpdate->bindParam(":id_venta", $datos["id_venta"], PDO::PARAM_INT);

			if (!$stmtUpdate->execute() || $stmtUpdate->rowCount() === 0) {
				throw new Exception("Error al cobrar la cuenta.");
			}

			$conexion->commit();

			return [
				"status" => "ok",
				"idVenta" => $datos["id_venta"],
				"total" => $datos["total"],
				"total_efectivo" => $datos["total_efectivo"],
				"total_qr" => $datos["total_qr"],
				"codigo" => $venta["codigo"]
			];
		} catch (Exception $e) {
			$conexion->rollBack();
			return "error: " . $e->getMessage();
		}
	}

	/*=============================================
	EXTRAER / BINDEAR DATOS DE PROMOCIÓN EN DETALLE
	=============================================*/
	static private function extraerDatosPromocionProducto($producto)
	{
		$promo = isset($producto["promo"]) && is_array($producto["promo"]) ? $producto["promo"] : [];
		$precioOriginal = isset($producto["precioOriginal"])
			? $producto["precioOriginal"]
			: ($promo["precio_original"] ?? ($producto["precio"] ?? null));

		$idPromocion = !empty($promo["id_promocion"]) ? intval($promo["id_promocion"]) : null;
		$idIntervalo = !empty($promo["id_intervalo_promocion"]) ? intval($promo["id_intervalo_promocion"]) : null;

		return [
			"precio_original" => $precioOriginal !== null ? round(floatval($precioOriginal), 2) : null,
			"tipo_descuento" => $idPromocion ? ($promo["tipo_descuento"] ?? null) : null,
			"valor_descuento" => $idPromocion ? round(floatval($promo["valor_descuento"] ?? 0), 2) : null,
			"descuento_unitario" => round(floatval($promo["descuento_unitario"] ?? 0), 2),
			"descuento_total" => round(floatval($promo["descuento_total"] ?? 0), 2),
			"id_promocion" => $idPromocion,
			"id_intervalo_promocion" => $idIntervalo,
			"nombre_promocion" => $idPromocion ? ($promo["nombre_promocion"] ?? null) : null
		];
	}

	static private function bindPromocionDetalle($stmt, $producto)
	{
		$promo = self::extraerDatosPromocionProducto($producto);
		$stmt->bindValue(":precio_original", $promo["precio_original"], PDO::PARAM_STR);
		$stmt->bindValue(":tipo_descuento", $promo["tipo_descuento"], PDO::PARAM_STR);
		$stmt->bindValue(":valor_descuento", $promo["valor_descuento"], PDO::PARAM_STR);
		$stmt->bindValue(":descuento_unitario", $promo["descuento_unitario"], PDO::PARAM_STR);
		$stmt->bindValue(":descuento_total", $promo["descuento_total"], PDO::PARAM_STR);
		if ($promo["id_promocion"] === null) {
			$stmt->bindValue(":id_promocion", null, PDO::PARAM_NULL);
		} else {
			$stmt->bindValue(":id_promocion", $promo["id_promocion"], PDO::PARAM_INT);
		}
		if ($promo["id_intervalo_promocion"] === null) {
			$stmt->bindValue(":id_intervalo_promocion", null, PDO::PARAM_NULL);
		} else {
			$stmt->bindValue(":id_intervalo_promocion", $promo["id_intervalo_promocion"], PDO::PARAM_INT);
		}
		$stmt->bindValue(":nombre_promocion", $promo["nombre_promocion"], PDO::PARAM_STR);
	}
}
