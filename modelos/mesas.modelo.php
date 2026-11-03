<?php

require_once __DIR__ . "/conexion.php";

class ModeloMesas
{
	static public function mdlMostrarMesas($item = null, $valor = null)
	{
		if ($item !== null) {
			$stmt = Conexion::conectar()->prepare(
				"SELECT * FROM mesas WHERE $item = :valor LIMIT 1"
			);
			$stmt->bindValue(":valor", $valor, PDO::PARAM_STR);
			$stmt->execute();
			return $stmt->fetch(PDO::FETCH_ASSOC);
		}

		try {
			$stmt = Conexion::conectar()->prepare(
				"SELECT * FROM mesas
				 ORDER BY COALESCE(numero, 999999) ASC, id ASC"
			);
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (Exception $e) {
			$stmt = Conexion::conectar()->prepare(
				"SELECT * FROM mesas ORDER BY id ASC"
			);
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
	}

	/** Normaliza CSV/array de IDs de mesas extras (máx. 4 → total 5 con la principal). */
	static public function mdlNormalizarIdsMesasExtra($ids, $idMesaPrincipal = 0)
	{
		if (is_string($ids)) {
			$ids = preg_split('/\s*,\s*/', trim($ids));
		}
		if (!is_array($ids)) {
			$ids = array();
		}
		$idMesaPrincipal = intval($idMesaPrincipal);
		$out = array();
		foreach ($ids as $id) {
			$id = intval($id);
			if ($id <= 0 || ($idMesaPrincipal > 0 && $id === $idMesaPrincipal)) {
				continue;
			}
			if (!in_array($id, $out, true)) {
				$out[] = $id;
			}
			if (count($out) >= 4) {
				break;
			}
		}
		return $out;
	}

	static public function mdlCsvIdsMesasExtra($ids)
	{
		$ids = self::mdlNormalizarIdsMesasExtra($ids);
		return $ids ? implode(",", $ids) : null;
	}

	/** Todos los IDs de mesa de una atención/venta (principal + extras). */
	static public function mdlIdsMesasDeRegistro($row)
	{
		$ids = array();
		if (!empty($row["id_mesa"])) {
			$ids[] = intval($row["id_mesa"]);
		}
		if (!empty($row["ids_mesas_extra"])) {
			foreach (self::mdlNormalizarIdsMesasExtra($row["ids_mesas_extra"]) as $id) {
				$ids[] = $id;
			}
		}
		return array_values(array_unique(array_filter($ids)));
	}

	/** Mesas para el selector de Vender (incluye deshabilitadas para filtro Todas). */
	static public function mdlListarParaSelector()
	{
		$stmt = Conexion::conectar()->prepare(
			"SELECT m.id, m.numero, m.nombre, m.capacidad, m.estado,
				(
					SELECT a.id FROM atencion_mesa a
					WHERE a.estado = 'abierta'
					  AND (
						a.id_mesa = m.id
						OR FIND_IN_SET(m.id, IFNULL(a.ids_mesas_extra, ''))
					  )
					ORDER BY a.id DESC LIMIT 1
				) AS id_atencion_abierta,
				(
					SELECT a.cantidad_personas FROM atencion_mesa a
					WHERE a.estado = 'abierta'
					  AND (
						a.id_mesa = m.id
						OR FIND_IN_SET(m.id, IFNULL(a.ids_mesas_extra, ''))
					  )
					ORDER BY a.id DESC LIMIT 1
				) AS personas_atencion
			 FROM mesas m
			 ORDER BY COALESCE(m.numero, 999999) ASC, m.id ASC"
		);
		try {
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (Exception $e) {
			$stmt = Conexion::conectar()->prepare(
				"SELECT id, nombre, capacidad, estado FROM mesas ORDER BY id ASC"
			);
			$stmt->execute();
			$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach ($rows as &$r) {
				$r["numero"] = null;
				$r["id_atencion_abierta"] = null;
				$r["personas_atencion"] = null;
			}
			return $rows;
		}
	}

	static public function mdlNombreDesdeNumero($numero)
	{
		return "Mesa " . intval($numero);
	}

	static public function mdlNombreMesa($idMesa)
	{
		$mesa = self::mdlMostrarMesas("id", intval($idMesa));
		if (!$mesa) {
			return "";
		}
		if (!empty($mesa["numero"])) {
			return self::mdlNombreDesdeNumero($mesa["numero"]);
		}
		return $mesa["nombre"] ?? "";
	}

	/** Número de mesa (numero o dígitos del nombre). */
	static public function mdlNumeroMesa($idMesa)
	{
		$mesa = self::mdlMostrarMesas("id", intval($idMesa));
		if (!$mesa) {
			return null;
		}
		if (!empty($mesa["numero"])) {
			return intval($mesa["numero"]);
		}
		if (preg_match('/(\d+)/', strval($mesa["nombre"] ?? ""), $m)) {
			return intval($m[1]);
		}
		return intval($idMesa);
	}

	/** Texto compacto para tickets/UI: "Mesa: 1" o "Mesa: 1,2,3". */
	static public function mdlTextoMesasVenta($venta)
	{
		$ids = self::mdlIdsMesasDeRegistro($venta);
		if (!$ids) {
			return "";
		}
		$nums = array();
		foreach ($ids as $id) {
			$n = self::mdlNumeroMesa($id);
			if ($n !== null) {
				$nums[] = $n;
			}
		}
		if (!$nums) {
			return "";
		}
		return "Mesa: " . implode(",", $nums);
	}

	static public function mdlAtencionAbiertaPorMesa($idMesa, $pdo = null)
	{
		$db = $pdo ?: Conexion::conectar();
		$idMesa = intval($idMesa);
		$stmt = $db->prepare(
			"SELECT * FROM atencion_mesa
			 WHERE id_mesa = :id_mesa AND estado = 'abierta'
			 ORDER BY id DESC LIMIT 1"
		);
		$stmt->bindValue(":id_mesa", $idMesa, PDO::PARAM_INT);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($row) {
			return $row;
		}
		$stmt2 = $db->prepare(
			"SELECT * FROM atencion_mesa
			 WHERE estado = 'abierta'
			   AND FIND_IN_SET(:id_mesa, IFNULL(ids_mesas_extra, ''))
			 ORDER BY id DESC LIMIT 1"
		);
		$stmt2->bindValue(":id_mesa", $idMesa, PDO::PARAM_INT);
		$stmt2->execute();
		return $stmt2->fetch(PDO::FETCH_ASSOC) ?: null;
	}

	static public function mdlMostrarAtencion($id, $pdo = null)
	{
		$db = $pdo ?: Conexion::conectar();
		$stmt = $db->prepare("SELECT * FROM atencion_mesa WHERE id = :id LIMIT 1");
		$stmt->bindValue(":id", intval($id), PDO::PARAM_INT);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row ?: null;
	}

	static public function mdlVentasDeAtencion($idAtencion)
	{
		$stmt = Conexion::conectar()->prepare(
			"SELECT v.id, v.codigo, v.total, v.estado_pago, v.fecha, v.forma_atencion,
				v.cantidad_personas, v.id_mesa, v.tipo_pago, v.fecha_pago,
				c.nombre AS cliente, m.nombre AS mesero, u.nombre AS cajero
			 FROM ventas v
			 LEFT JOIN clientes c ON c.id = v.id_cliente
			 LEFT JOIN meseros m ON m.id = v.id_mesero
			 LEFT JOIN usuarios u ON u.id = v.id_vendedor
			 WHERE v.id_atencion_mesa = :id AND v.estado = 1
			 ORDER BY v.id ASC"
		);
		$stmt->bindValue(":id", intval($idAtencion), PDO::PARAM_INT);
		$stmt->execute();
		$ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if (!class_exists("ModeloVentas")) {
			require_once __DIR__ . "/ventas.modelo.php";
		}
		foreach ($ventas as &$v) {
			$detalle = ModeloVentas::mdlMostrarDetalleVentas($v["id"]);
			$items = array();
			if (is_array($detalle)) {
				foreach ($detalle as $d) {
					$items[] = array(
						"producto" => $d["producto"] ?? "",
						"cantidad" => $d["cantidad"] ?? 0,
						"precio_venta" => $d["precio_venta"] ?? 0,
						"subtotal" => $d["subtotal"] ?? 0
					);
				}
			}
			$v["items"] = $items;
		}
		unset($v);
		return $ventas;
	}

	static public function mdlDetalleAtencionMesa($idMesa)
	{
		$mesa = self::mdlMostrarMesas("id", intval($idMesa));
		if (!$mesa) {
			return array("ok" => false, "mensaje" => "Mesa no encontrada");
		}
		$atencion = self::mdlAtencionAbiertaPorMesa($idMesa);
		$ventas = $atencion ? self::mdlVentasDeAtencion($atencion["id"]) : array();
		$pendientes = 0;
		$totalPendiente = 0;
		$totalConsumido = 0;
		$totalPagado = 0;
		$mesero = "s/n";
		foreach ($ventas as $v) {
			$total = floatval($v["total"] ?? 0);
			$totalConsumido += $total;
			if (($v["estado_pago"] ?? "") === "PENDIENTE") {
				$pendientes++;
				$totalPendiente += $total;
			} else {
				$totalPagado += $total;
			}
			if (!empty($v["mesero"]) && $mesero === "s/n") {
				$mesero = $v["mesero"];
			}
		}
		$nombreMesa = !empty($mesa["numero"])
			? self::mdlNombreDesdeNumero($mesa["numero"])
			: ($mesa["nombre"] ?? "");
		return array(
			"ok" => true,
			"mesa" => $mesa,
			"nombre_mesa" => $nombreMesa,
			"atencion" => $atencion,
			"ventas" => $ventas,
			"tiene_deuda" => $pendientes > 0,
			"cantidad_pendientes" => $pendientes,
			"total_pendiente" => $totalPendiente,
			"total_consumido" => $totalConsumido,
			"total_pagado" => $totalPagado,
			"mesero" => $mesero,
			"cantidad_pedidos" => count($ventas)
		);
	}

	/**
	 * Reserva / reutiliza atención dentro de una transacción de venta.
	 * $idsMesasExtra: int|array|string — hasta 4 mesas adicionales (total máx. 5).
	 */
	static public function mdlReservarAtencionEnTransaccion($pdo, $idMesa, $cantidadPersonas, $idUsuario, $idAtencionExistente = 0, $idsMesasExtra = array())
	{
		$idMesa = intval($idMesa);
		$cantidadPersonas = max(1, intval($cantidadPersonas));
		$idAtencionExistente = intval($idAtencionExistente);
		$idsExtra = self::mdlNormalizarIdsMesasExtra($idsMesasExtra, $idMesa);
		$csvExtra = self::mdlCsvIdsMesasExtra($idsExtra);

		$stmt = $pdo->prepare("SELECT * FROM mesas WHERE id = :id FOR UPDATE");
		$stmt->bindValue(":id", $idMesa, PDO::PARAM_INT);
		$stmt->execute();
		$mesa = $stmt->fetch(PDO::FETCH_ASSOC);
		if (!$mesa) {
			return array("ok" => false, "mensaje" => "La mesa seleccionada no existe.");
		}
		if ($mesa["estado"] === "deshabilitada") {
			return array("ok" => false, "mensaje" => "La mesa está deshabilitada.");
		}

		$capacidadMax = intval($mesa["capacidad"]);
		if ($idsExtra) {
			if ($idAtencionExistente > 0 || $mesa["estado"] !== "disponible") {
				return array("ok" => false, "mensaje" => "Para unir mesas todas deben estar disponibles.");
			}
			foreach ($idsExtra as $idExtra) {
				$stmtE = $pdo->prepare("SELECT * FROM mesas WHERE id = :id FOR UPDATE");
				$stmtE->bindValue(":id", $idExtra, PDO::PARAM_INT);
				$stmtE->execute();
				$mesaE = $stmtE->fetch(PDO::FETCH_ASSOC);
				if (!$mesaE) {
					return array("ok" => false, "mensaje" => "Una de las mesas unidas no existe.");
				}
				if ($mesaE["estado"] !== "disponible") {
					return array("ok" => false, "mensaje" => "La mesa " . ($mesaE["nombre"] ?? $idExtra) . " no está disponible.");
				}
				$capacidadMax += intval($mesaE["capacidad"]);
			}
		}

		if ($cantidadPersonas > $capacidadMax) {
			return array(
				"ok" => false,
				"mensaje" => "La cantidad de personas supera la capacidad (" . $capacidadMax . ")."
			);
		}

		if ($mesa["estado"] === "disponible") {
			$stmtIns = $pdo->prepare(
				"INSERT INTO atencion_mesa
				 (id_mesa, ids_mesas_extra, cantidad_personas, estado, id_usuario_apertura)
				 VALUES (:id_mesa, :ids_extra, :personas, 'abierta', :usuario)"
			);
			$stmtIns->bindValue(":id_mesa", $idMesa, PDO::PARAM_INT);
			$stmtIns->bindValue(":ids_extra", $csvExtra, $csvExtra ? PDO::PARAM_STR : PDO::PARAM_NULL);
			$stmtIns->bindValue(":personas", $cantidadPersonas, PDO::PARAM_INT);
			$stmtIns->bindValue(":usuario", $idUsuario ? intval($idUsuario) : null, PDO::PARAM_INT);
			if (!$stmtIns->execute()) {
				return array("ok" => false, "mensaje" => "No se pudo abrir la atención de mesa.");
			}
			$idAtencion = intval($pdo->lastInsertId());

			$stmtUp = $pdo->prepare("UPDATE mesas SET estado = 'ocupada' WHERE id = :id");
			$stmtUp->bindValue(":id", $idMesa, PDO::PARAM_INT);
			$stmtUp->execute();
			foreach ($idsExtra as $idExtra) {
				$stmtUpE = $pdo->prepare("UPDATE mesas SET estado = 'ocupada' WHERE id = :id");
				$stmtUpE->bindValue(":id", $idExtra, PDO::PARAM_INT);
				$stmtUpE->execute();
			}

			return array(
				"ok" => true,
				"id_atencion" => $idAtencion,
				"id_mesa" => $idMesa,
				"ids_mesas_extra" => $csvExtra,
				"cantidad_personas" => $cantidadPersonas,
				"nueva" => true
			);
		}

		if ($idsExtra) {
			return array("ok" => false, "mensaje" => "No se pueden unir mesas sobre una atención ya abierta.");
		}

		$atencion = null;
		if ($idAtencionExistente > 0) {
			$atencion = self::mdlMostrarAtencion($idAtencionExistente, $pdo);
			if (!$atencion || $atencion["estado"] !== "abierta" || intval($atencion["id_mesa"]) !== $idMesa) {
				return array("ok" => false, "mensaje" => "La atención de mesa ya no está activa.");
			}
		} else {
			$atencion = self::mdlAtencionAbiertaPorMesa($idMesa, $pdo);
		}
		if (!$atencion) {
			return array("ok" => false, "mensaje" => "La mesa está ocupada y no tiene atención abierta.");
		}

		$capAtencion = 0;
		foreach (self::mdlIdsMesasDeRegistro($atencion) as $idCap) {
			$mCap = self::mdlMostrarMesas("id", $idCap);
			if ($mCap) {
				$capAtencion += intval($mCap["capacidad"]);
			}
		}
		if ($capAtencion < 1) {
			$capAtencion = intval($mesa["capacidad"]);
		}
		if ($cantidadPersonas > $capAtencion) {
			return array(
				"ok" => false,
				"mensaje" => "La cantidad de personas supera la capacidad (" . $capAtencion . ")."
			);
		}

		$stmtUpPers = $pdo->prepare(
			"UPDATE atencion_mesa SET cantidad_personas = :personas WHERE id = :id"
		);
		$stmtUpPers->bindValue(":personas", $cantidadPersonas, PDO::PARAM_INT);
		$stmtUpPers->bindValue(":id", intval($atencion["id"]), PDO::PARAM_INT);
		$stmtUpPers->execute();

		return array(
			"ok" => true,
			"id_atencion" => intval($atencion["id"]),
			"id_mesa" => $idMesa,
			"ids_mesas_extra" => !empty($atencion["ids_mesas_extra"]) ? $atencion["ids_mesas_extra"] : null,
			"cantidad_personas" => $cantidadPersonas,
			"nueva" => false
		);
	}

	static public function mdlLiberarAtencion($idMesa, $idUsuario, $autorizadoConDeuda = false)
	{
		$conexion = Conexion::conectar();
		try {
			$conexion->beginTransaction();

			$stmt = $conexion->prepare("SELECT * FROM mesas WHERE id = :id FOR UPDATE");
			$stmt->bindValue(":id", intval($idMesa), PDO::PARAM_INT);
			$stmt->execute();
			$mesa = $stmt->fetch(PDO::FETCH_ASSOC);
			if (!$mesa) {
				throw new Exception("La mesa no existe.");
			}
			if ($mesa["estado"] !== "ocupada") {
				throw new Exception("La mesa no está ocupada.");
			}

			$atencion = self::mdlAtencionAbiertaPorMesa($idMesa, $conexion);
			if (!$atencion) {
				$stmtFree = $conexion->prepare("UPDATE mesas SET estado = 'disponible' WHERE id = :id");
				$stmtFree->bindValue(":id", intval($idMesa), PDO::PARAM_INT);
				$stmtFree->execute();
				$conexion->commit();
				return array("ok" => true, "mensaje" => "Mesa liberada (sin atención activa).");
			}

			$stmtVen = $conexion->prepare(
				"SELECT COUNT(*) AS total FROM ventas
				 WHERE id_atencion_mesa = :id AND estado = 1 AND estado_pago = 'PENDIENTE'"
			);
			$stmtVen->bindValue(":id", intval($atencion["id"]), PDO::PARAM_INT);
			$stmtVen->execute();
			$pend = intval(($stmtVen->fetch(PDO::FETCH_ASSOC)["total"] ?? 0));
			if ($pend > 0 && !$autorizadoConDeuda) {
				throw new Exception("Hay saldos pendientes. No se puede liberar la mesa.");
			}

			$stmtCierre = $conexion->prepare(
				"UPDATE atencion_mesa SET
					estado = 'finalizada',
					fecha_cierre = NOW(),
					id_usuario_cierre = :usuario
				 WHERE id = :id AND estado = 'abierta'"
			);
			$stmtCierre->bindValue(":usuario", intval($idUsuario), PDO::PARAM_INT);
			$stmtCierre->bindValue(":id", intval($atencion["id"]), PDO::PARAM_INT);
			$stmtCierre->execute();

			$idsLiberar = self::mdlIdsMesasDeRegistro($atencion);
			$stmtVenM = $conexion->prepare(
				"SELECT id_mesa, ids_mesas_extra FROM ventas
				 WHERE id_atencion_mesa = :id"
			);
			$stmtVenM->bindValue(":id", intval($atencion["id"]), PDO::PARAM_INT);
			$stmtVenM->execute();
			while ($vm = $stmtVenM->fetch(PDO::FETCH_ASSOC)) {
				foreach (self::mdlIdsMesasDeRegistro($vm) as $idV) {
					$idsLiberar[] = $idV;
				}
			}
			$idsLiberar = array_values(array_unique(array_filter($idsLiberar)));

			$stmtFree = $conexion->prepare("UPDATE mesas SET estado = 'disponible' WHERE id = :id");
			foreach ($idsLiberar as $idLib) {
				$stmtFree->bindValue(":id", $idLib, PDO::PARAM_INT);
				$stmtFree->execute();
			}

			$conexion->commit();
			$msg = count($idsLiberar) > 1
				? "Atención finalizada. Mesas liberadas."
				: "Atención finalizada. Mesa liberada.";
			return array("ok" => true, "mensaje" => $msg);
		} catch (Exception $e) {
			if ($conexion->inTransaction()) {
				$conexion->rollBack();
			}
			return array("ok" => false, "mensaje" => $e->getMessage());
		}
	}

	static public function mdlCambiarMesaAtencionEnConexion($conexion, $idMesaOrigen, $idMesaDestino)
	{
		$idMesaOrigen = intval($idMesaOrigen);
		$idMesaDestino = intval($idMesaDestino);
		if ($idMesaOrigen === $idMesaDestino) {
			throw new Exception("Seleccione una mesa distinta.");
		}

		$stmtO = $conexion->prepare("SELECT * FROM mesas WHERE id = :id FOR UPDATE");
		$stmtO->bindValue(":id", $idMesaOrigen, PDO::PARAM_INT);
		$stmtO->execute();
		$origen = $stmtO->fetch(PDO::FETCH_ASSOC);

		$stmtD = $conexion->prepare("SELECT * FROM mesas WHERE id = :id FOR UPDATE");
		$stmtD->bindValue(":id", $idMesaDestino, PDO::PARAM_INT);
		$stmtD->execute();
		$destino = $stmtD->fetch(PDO::FETCH_ASSOC);

		if (!$origen || !$destino) {
			throw new Exception("Mesa no encontrada.");
		}
		if ($origen["estado"] !== "ocupada") {
			throw new Exception("La mesa de origen no está ocupada.");
		}
		if ($destino["estado"] !== "disponible") {
			throw new Exception("La mesa destino no está disponible.");
		}

		$atencion = self::mdlAtencionAbiertaPorMesa($idMesaOrigen, $conexion);
		if (!$atencion) {
			throw new Exception("No hay atención abierta en la mesa de origen.");
		}
		if (count(self::mdlIdsMesasDeRegistro($atencion)) > 1) {
			throw new Exception(
				"Esta atención une varias mesas. Libere la atención y vuelva a abrirla en la mesa destino."
			);
		}
		if (intval($atencion["cantidad_personas"]) > intval($destino["capacidad"])) {
			throw new Exception(
				"La capacidad de la mesa destino (" . $destino["capacidad"] .
				") es menor a las personas de la atención (" . $atencion["cantidad_personas"] . ")."
			);
		}

		$stmtA = $conexion->prepare("UPDATE atencion_mesa SET id_mesa = :destino WHERE id = :id");
		$stmtA->bindValue(":destino", $idMesaDestino, PDO::PARAM_INT);
		$stmtA->bindValue(":id", intval($atencion["id"]), PDO::PARAM_INT);
		$stmtA->execute();

		$stmtV = $conexion->prepare(
			"UPDATE ventas SET id_mesa = :destino
			 WHERE id_atencion_mesa = :id AND estado = 1"
		);
		$stmtV->bindValue(":destino", $idMesaDestino, PDO::PARAM_INT);
		$stmtV->bindValue(":id", intval($atencion["id"]), PDO::PARAM_INT);
		$stmtV->execute();

		$stmtLibre = $conexion->prepare("UPDATE mesas SET estado = 'disponible' WHERE id = :id");
		$stmtLibre->bindValue(":id", $idMesaOrigen, PDO::PARAM_INT);
		$stmtLibre->execute();

		$stmtOcupa = $conexion->prepare("UPDATE mesas SET estado = 'ocupada' WHERE id = :id");
		$stmtOcupa->bindValue(":id", $idMesaDestino, PDO::PARAM_INT);
		$stmtOcupa->execute();

		return array(
			"ok" => true,
			"mensaje" => "Atención movida a " . $destino["nombre"] . ".",
			"nombre_destino" => $destino["nombre"],
			"id_atencion" => intval($atencion["id"])
		);
	}

	static public function mdlCambiarMesaAtencion($idMesaOrigen, $idMesaDestino, $idUsuario = 0)
	{
		$conexion = Conexion::conectar();
		try {
			$conexion->beginTransaction();
			$res = self::mdlCambiarMesaAtencionEnConexion($conexion, $idMesaOrigen, $idMesaDestino);
			$conexion->commit();
			$res["usuario"] = intval($idUsuario);
			return $res;
		} catch (Exception $e) {
			if ($conexion->inTransaction()) {
				$conexion->rollBack();
			}
			return array("ok" => false, "mensaje" => $e->getMessage());
		}
	}

	static public function mdlExisteNombre($nombre, $exceptoId = 0)
	{
		$stmt = Conexion::conectar()->prepare(
			"SELECT id FROM mesas WHERE LOWER(nombre) = LOWER(:nombre) AND id <> :id LIMIT 1"
		);
		$stmt->bindValue(":nombre", trim($nombre), PDO::PARAM_STR);
		$stmt->bindValue(":id", intval($exceptoId), PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
	}

	static public function mdlExisteNumero($numero, $exceptoId = 0)
	{
		$stmt = Conexion::conectar()->prepare(
			"SELECT id FROM mesas WHERE numero = :numero AND id <> :id LIMIT 1"
		);
		$stmt->bindValue(":numero", intval($numero), PDO::PARAM_INT);
		$stmt->bindValue(":id", intval($exceptoId), PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
	}

	static public function mdlCrear($datos)
	{
		$numero = intval($datos["numero"]);
		$nombre = self::mdlNombreDesdeNumero($numero);
		$descripcion = trim((string) ($datos["descripcion"] ?? ""));
		if ($descripcion === "") {
			$descripcion = null;
		}

		$stmt = Conexion::conectar()->prepare(
			"INSERT INTO mesas (numero, nombre, descripcion, capacidad, estado)
			 VALUES (:numero, :nombre, :descripcion, :capacidad, :estado)"
		);
		$stmt->bindValue(":numero", $numero, PDO::PARAM_INT);
		$stmt->bindValue(":nombre", $nombre, PDO::PARAM_STR);
		$stmt->bindValue(":descripcion", $descripcion, $descripcion === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
		$stmt->bindValue(":capacidad", intval($datos["capacidad"]), PDO::PARAM_INT);
		$stmt->bindValue(":estado", $datos["estado"], PDO::PARAM_STR);
		return $stmt->execute() ? "ok" : "error";
	}

	static public function mdlEditar($datos)
	{
		$actual = self::mdlMostrarMesas("id", $datos["id"]);
		if (!$actual) {
			return "error";
		}

		$estado = $datos["estado"];
		// No forzar a disponible una mesa ocupada desde el admin (la libera la atención).
		if ($actual["estado"] === "ocupada" && $estado === "disponible") {
			$estado = "ocupada";
		}

		$numero = intval($datos["numero"]);
		$nombre = self::mdlNombreDesdeNumero($numero);
		$descripcion = trim((string) ($datos["descripcion"] ?? ""));
		if ($descripcion === "") {
			$descripcion = null;
		}

		$stmt = Conexion::conectar()->prepare(
			"UPDATE mesas
			 SET numero = :numero, nombre = :nombre, descripcion = :descripcion,
			     capacidad = :capacidad, estado = :estado
			 WHERE id = :id"
		);
		$stmt->bindValue(":numero", $numero, PDO::PARAM_INT);
		$stmt->bindValue(":nombre", $nombre, PDO::PARAM_STR);
		$stmt->bindValue(":descripcion", $descripcion, $descripcion === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
		$stmt->bindValue(":capacidad", intval($datos["capacidad"]), PDO::PARAM_INT);
		$stmt->bindValue(":estado", $estado, PDO::PARAM_STR);
		$stmt->bindValue(":id", intval($datos["id"]), PDO::PARAM_INT);
		return $stmt->execute() ? "ok" : "error";
	}

	static public function mdlEliminar($id)
	{
		$mesa = self::mdlMostrarMesas("id", $id);
		if (!$mesa) {
			return array("ok" => false, "mensaje" => "La mesa no existe.");
		}
		if ($mesa["estado"] === "ocupada") {
			return array(
				"ok" => false,
				"mensaje" => "No se puede eliminar una mesa ocupada. Finalice la atención o deshabilítela."
			);
		}

		// Historial futuro (ventas / atenciones). Si la tabla aún no existe, se ignora.
		try {
			$stmtHist = Conexion::conectar()->prepare(
				"SELECT COUNT(*) AS total FROM atencion_mesa WHERE id_mesa = :id"
			);
			$stmtHist->bindValue(":id", intval($id), PDO::PARAM_INT);
			$stmtHist->execute();
			$row = $stmtHist->fetch(PDO::FETCH_ASSOC);
			if ($row && intval($row["total"]) > 0) {
				return array(
					"ok" => false,
					"mensaje" => "No se puede eliminar: la mesa tiene historial. Deshabilítela en su lugar."
				);
			}
		} catch (Exception $e) {
			// Sin tabla de historial todavía.
		}

		try {
			$stmtHist = Conexion::conectar()->prepare(
				"SELECT COUNT(*) AS total FROM ventas WHERE id_mesa = :id"
			);
			$stmtHist->bindValue(":id", intval($id), PDO::PARAM_INT);
			$stmtHist->execute();
			$row = $stmtHist->fetch(PDO::FETCH_ASSOC);
			if ($row && intval($row["total"]) > 0) {
				return array(
					"ok" => false,
					"mensaje" => "No se puede eliminar: la mesa tiene ventas asociadas. Deshabilítela en su lugar."
				);
			}
		} catch (Exception $e) {
			// Columna aún no existe (tarea 6).
		}

		$stmt = Conexion::conectar()->prepare("DELETE FROM mesas WHERE id = :id");
		$stmt->bindValue(":id", intval($id), PDO::PARAM_INT);
		if ($stmt->execute()) {
			return array("ok" => true, "mensaje" => "ok");
		}
		return array("ok" => false, "mensaje" => "No se pudo eliminar la mesa.");
	}
}
