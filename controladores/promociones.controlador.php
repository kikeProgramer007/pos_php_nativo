<?php

class ControladorPromociones
{
	/*=============================================
	LISTAR / MOSTRAR
	=============================================*/
	static public function ctrMostrarPromociones($item = null, $valor = null, $filtros = [])
	{
		return ModeloPromociones::mdlMostrarPromociones($item, $valor, $filtros);
	}

	static public function ctrMostrarProductosPromocion($idPromocion)
	{
		return ModeloPromociones::mdlMostrarProductosPromocion($idPromocion);
	}

	static public function ctrMostrarIntervalos($idPromocion)
	{
		return ModeloPromociones::mdlMostrarIntervalos($idPromocion);
	}

	/*=============================================
	CREAR / EDITAR CABECERA
	=============================================*/
	static public function ctrCrearPromocion()
	{
		if (!isset($_POST["nuevaPromocion"])) {
			return;
		}

		if (!Permisos::tiene("promociones.crear")) {
			Permisos::requiere("promociones.crear");
			return;
		}

		$validacion = self::validarDatosCabecera($_POST);
		if ($validacion !== true) {
			self::alerta("error", $validacion, "agregar-promocion");
			return;
		}

		$datos = self::mapearDatosCabecera($_POST);
		$conexion = Conexion::conectar();
		try {
			$conexion->beginTransaction();
			$id = ModeloPromociones::mdlCrearPromocionTx($conexion, $datos);
			if (!$id) {
				throw new Exception("No se pudo crear la promoción");
			}
			$conexion->commit();
			echo '<script>
				swal({
					type: "success",
					title: "Promoción creada",
					text: "Ahora agregue intervalos y productos vinculados",
					showConfirmButton: true,
					confirmButtonText: "Continuar"
				}).then(function(result){
					window.location = "index.php?ruta=editar-promocion&idPromocion=' . intval($id) . '";
				});
			</script>';
		} catch (Exception $e) {
			$conexion->rollBack();
			self::alerta("error", "Error al crear la promoción", "agregar-promocion");
		}
	}

	static public function ctrEditarPromocion()
	{
		if (!isset($_POST["editarPromocion"])) {
			return;
		}

		if (!Permisos::tiene("promociones.editar")) {
			Permisos::requiere("promociones.editar");
			return;
		}

		$id = intval($_POST["idPromocion"]);
		$validacion = self::validarDatosCabecera($_POST);
		if ($validacion !== true) {
			self::alerta("error", $validacion, "index.php?ruta=editar-promocion&idPromocion=" . $id);
			return;
		}

		$datos = self::mapearDatosCabecera($_POST);
		$datos["id"] = $id;

		// Si se intenta habilitar, exigir productos e intervalos
		if (intval($datos["estado"]) === 1) {
			$productos = ModeloPromociones::mdlMostrarProductosPromocion($id);
			$intervalos = ModeloPromociones::mdlMostrarIntervalos($id);
			if (count($productos) === 0) {
				self::alerta("warning", "No se puede habilitar sin productos vinculados", "index.php?ruta=editar-promocion&idPromocion=" . $id);
				return;
			}
			if (count($intervalos) === 0) {
				self::alerta("warning", "No se puede habilitar sin al menos un intervalo válido", "index.php?ruta=editar-promocion&idPromocion=" . $id);
				return;
			}
		}

		$respuesta = ModeloPromociones::mdlEditarPromocion($datos);
		if ($respuesta === "ok") {
			self::alerta("success", "Promoción actualizada correctamente", "index.php?ruta=editar-promocion&idPromocion=" . $id);
		} else {
			self::alerta("error", "No se pudo actualizar la promoción", "index.php?ruta=editar-promocion&idPromocion=" . $id);
		}
	}

	static public function ctrCambiarEstado($id, $estado)
	{
		if (!Permisos::tiene("promociones.editar")) {
			return ["status" => "error", "mensaje" => "Acceso no autorizado"];
		}

		$id = intval($id);
		$estado = intval($estado) === 1 ? 1 : 0;

		if ($estado === 1) {
			$productos = ModeloPromociones::mdlMostrarProductosPromocion($id);
			$intervalos = ModeloPromociones::mdlMostrarIntervalos($id);
			if (count($productos) === 0 || count($intervalos) === 0) {
				return ["status" => "error", "mensaje" => "Para habilitar necesita productos e intervalos configurados"];
			}
		}

		$ok = ModeloPromociones::mdlCambiarEstado($id, $estado);
		return $ok === "ok"
			? ["status" => "ok", "mensaje" => $estado ? "Promoción habilitada" : "Promoción deshabilitada"]
			: ["status" => "error", "mensaje" => "No se pudo cambiar el estado"];
	}

	static public function ctrEliminarPromocion($id)
	{
		if (!Permisos::tiene("promociones.eliminar")) {
			return ["status" => "error", "mensaje" => "Acceso no autorizado"];
		}

		$ok = ModeloPromociones::mdlEliminarPromocion(intval($id));
		return $ok === "ok"
			? ["status" => "ok", "mensaje" => "Promoción deshabilitada"]
			: ["status" => "error", "mensaje" => "No se pudo eliminar"];
	}

	/*=============================================
	PRODUCTOS
	=============================================*/
	static public function ctrVincularProductos($idPromocion, $idsProductos)
	{
		$idPromocion = intval($idPromocion);
		if ($idPromocion <= 0 || empty($idsProductos) || !is_array($idsProductos)) {
			return ["status" => "error", "mensaje" => "Datos inválidos"];
		}

		$promo = ModeloPromociones::mdlMostrarPromociones("id", $idPromocion);
		if (!$promo) {
			return ["status" => "error", "mensaje" => "Promoción no encontrada"];
		}

		$idsLimpios = [];
		foreach ($idsProductos as $idP) {
			$idP = intval($idP);
			if ($idP > 0) {
				$producto = ControladorProductos::ctrMostrarProductos("id", $idP, "id");
				if ($producto && intval($producto["estado"]) === 1) {
					$idsLimpios[] = $idP;
				}
			}
		}
		$idsLimpios = array_values(array_unique($idsLimpios));
		if (empty($idsLimpios)) {
			return ["status" => "error", "mensaje" => "No hay productos válidos para vincular"];
		}

		$ok = ModeloPromociones::mdlVincularProductos($idPromocion, $idsLimpios);
		return $ok === "ok"
			? ["status" => "ok", "mensaje" => "Productos vinculados correctamente"]
			: ["status" => "error", "mensaje" => "Error al vincular productos"];
	}

	static public function ctrQuitarProducto($idVinculo)
	{
		$ok = ModeloPromociones::mdlQuitarProductoPromocion(intval($idVinculo));
		return $ok === "ok"
			? ["status" => "ok", "mensaje" => "Producto desvinculado"]
			: ["status" => "error", "mensaje" => "No se pudo desvincular"];
	}

	/*=============================================
	INTERVALOS
	=============================================*/
	static public function ctrGuardarIntervalo($datos)
	{
		$idPromocion = intval($datos["id_promocion"] ?? 0);
		if ($idPromocion <= 0) {
			return ["status" => "error", "mensaje" => "Promoción inválida"];
		}

		$min = intval($datos["cantidad_minima"] ?? 0);
		$maxRaw = $datos["cantidad_maxima"] ?? null;
		$max = ($maxRaw === null || $maxRaw === "" ) ? null : intval($maxRaw);
		$tipo = ($datos["tipo_descuento"] ?? "") === "porcentaje" ? "porcentaje" : "fijo";
		$valor = round(floatval($datos["valor_descuento"] ?? 0), 2);
		$idIntervalo = !empty($datos["id"]) ? intval($datos["id"]) : null;

		if ($min <= 0) {
			return ["status" => "error", "mensaje" => "La cantidad mínima debe ser mayor que cero"];
		}
		if ($max !== null && $max < $min) {
			return ["status" => "error", "mensaje" => "La cantidad máxima no puede ser menor que la mínima"];
		}
		if ($valor <= 0) {
			return ["status" => "error", "mensaje" => "El valor del descuento debe ser mayor que cero"];
		}
		if ($tipo === "porcentaje" && ($valor <= 0 || $valor > 100)) {
			return ["status" => "error", "mensaje" => "El porcentaje debe ser mayor a 0 y menor o igual a 100"];
		}

		$existentes = ModeloPromociones::mdlMostrarIntervalos($idPromocion);
		$validacion = self::validarIntervalosSinSolape($existentes, $min, $max, $idIntervalo);
		if ($validacion !== true) {
			return ["status" => "error", "mensaje" => $validacion];
		}

		$payload = [
			"id" => $idIntervalo,
			"id_promocion" => $idPromocion,
			"cantidad_minima" => $min,
			"cantidad_maxima" => $max,
			"tipo_descuento" => $tipo,
			"valor_descuento" => $valor
		];

		$ok = ModeloPromociones::mdlGuardarIntervalo($payload);
		return $ok === "ok"
			? ["status" => "ok", "mensaje" => "Intervalo guardado"]
			: ["status" => "error", "mensaje" => "No se pudo guardar el intervalo"];
	}

	static public function ctrEliminarIntervalo($id)
	{
		$ok = ModeloPromociones::mdlEliminarIntervalo(intval($id));
		return $ok === "ok"
			? ["status" => "ok", "mensaje" => "Intervalo eliminado"]
			: ["status" => "error", "mensaje" => "No se pudo eliminar"];
	}

	/*=============================================
	CÁLCULO CENTRAL PARA VENTAS
	items: [ {id, cantidad, precio}, ... ]
	Retorna mapa por id_producto con descuento aplicado
	=============================================*/
	static public function ctrCalcularPromocionesParaVenta($items)
	{
		$resultado = [];
		if (empty($items) || !is_array($items)) {
			return $resultado;
		}

		// Acumular cantidad por producto (individual)
		$cantidades = [];
		$precios = [];
		foreach ($items as $item) {
			$id = intval($item["id"] ?? 0);
			if ($id <= 0) continue;
			$cant = intval($item["cantidad"] ?? 0);
			$precio = floatval($item["precio"] ?? ($item["precioReal"] ?? 0));
			$cantidades[$id] = ($cantidades[$id] ?? 0) + $cant;
			if (!isset($precios[$id]) || $precio > 0) {
				$precios[$id] = $precio;
			}
		}

		$ids = array_keys($cantidades);
		$filas = ModeloPromociones::mdlObtenerPromocionesVigentesPorProductos($ids);

		// Agrupar por producto -> promociones -> intervalos
		$porProducto = [];
		foreach ($filas as $fila) {
			$idProd = intval($fila["id_producto"]);
			$idPromo = intval($fila["id_promocion"]);
			if (!isset($porProducto[$idProd][$idPromo])) {
				$porProducto[$idProd][$idPromo] = [
					"id_promocion" => $idPromo,
					"nombre_promocion" => $fila["nombre_promocion"],
					"prioridad" => intval($fila["prioridad"]),
					"fecha_creacion" => $fila["fecha_creacion"],
					"intervalos" => []
				];
			}
			$porProducto[$idProd][$idPromo]["intervalos"][] = [
				"id_intervalo" => intval($fila["id_intervalo"]),
				"cantidad_minima" => intval($fila["cantidad_minima"]),
				"cantidad_maxima" => $fila["cantidad_maxima"] !== null ? intval($fila["cantidad_maxima"]) : null,
				"tipo_descuento" => $fila["tipo_descuento"],
				"valor_descuento" => floatval($fila["valor_descuento"])
			];
		}

		foreach ($cantidades as $idProducto => $cantidad) {
			$precioOriginal = round(floatval($precios[$idProducto] ?? 0), 2);
			$resultado[$idProducto] = [
				"id_producto" => $idProducto,
				"cantidad" => $cantidad,
				"precio_original" => $precioOriginal,
				"precio_final" => $precioOriginal,
				"descuento_unitario" => 0,
				"descuento_total" => 0,
				"tipo_descuento" => null,
				"valor_descuento" => null,
				"id_promocion" => null,
				"id_intervalo_promocion" => null,
				"nombre_promocion" => null,
				"mensaje" => null
			];

			if ($precioOriginal <= 0 || empty($porProducto[$idProducto])) {
				continue;
			}

			$candidatos = [];
			foreach ($porProducto[$idProducto] as $promo) {
				$intervalo = self::seleccionarIntervalo($promo["intervalos"], $cantidad);
				if (!$intervalo) {
					continue;
				}
				$calc = self::calcularDescuentoUnitario($precioOriginal, $intervalo["tipo_descuento"], $intervalo["valor_descuento"]);
				if ($calc["descuento_unitario"] <= 0) {
					continue;
				}
				$candidatos[] = [
					"prioridad" => $promo["prioridad"],
					"descuento_unitario" => $calc["descuento_unitario"],
					"precio_final" => $calc["precio_final"],
					"tipo_descuento" => $intervalo["tipo_descuento"],
					"valor_descuento" => $intervalo["valor_descuento"],
					"id_promocion" => $promo["id_promocion"],
					"id_intervalo_promocion" => $intervalo["id_intervalo"],
					"nombre_promocion" => $promo["nombre_promocion"],
					"fecha_creacion" => $promo["fecha_creacion"],
					"cantidad_minima" => $intervalo["cantidad_minima"]
				];
			}

			if (empty($candidatos)) {
				continue;
			}

			usort($candidatos, function ($a, $b) {
				if ($a["prioridad"] !== $b["prioridad"]) {
					return $b["prioridad"] <=> $a["prioridad"];
				}
				if ($a["descuento_unitario"] !== $b["descuento_unitario"]) {
					return $b["descuento_unitario"] <=> $a["descuento_unitario"];
				}
				return strcmp($b["fecha_creacion"], $a["fecha_creacion"]);
			});

			$mejor = $candidatos[0];
			$descuentoTotal = round($mejor["descuento_unitario"] * $cantidad, 2);
			$resultado[$idProducto] = [
				"id_producto" => $idProducto,
				"cantidad" => $cantidad,
				"precio_original" => $precioOriginal,
				"precio_final" => $mejor["precio_final"],
				"descuento_unitario" => $mejor["descuento_unitario"],
				"descuento_total" => $descuentoTotal,
				"tipo_descuento" => $mejor["tipo_descuento"],
				"valor_descuento" => $mejor["valor_descuento"],
				"id_promocion" => $mejor["id_promocion"],
				"id_intervalo_promocion" => $mejor["id_intervalo_promocion"],
				"nombre_promocion" => $mejor["nombre_promocion"],
				"mensaje" => "Promoción por volumen: Bs " . number_format($mejor["precio_final"], 2) . " por unidad desde " . $mejor["cantidad_minima"] . " unidades (" . $mejor["nombre_promocion"] . ")"
			];
		}

		return $resultado;
	}

	static public function seleccionarIntervalo($intervalos, $cantidad)
	{
		$cantidad = intval($cantidad);
		foreach ($intervalos as $intervalo) {
			$min = intval($intervalo["cantidad_minima"]);
			$max = $intervalo["cantidad_maxima"];
			if ($cantidad >= $min && ($max === null || $cantidad <= intval($max))) {
				return $intervalo;
			}
		}
		return null;
	}

	static public function calcularDescuentoUnitario($precioOriginal, $tipo, $valor)
	{
		$precioOriginal = round(floatval($precioOriginal), 2);
		$valor = round(floatval($valor), 2);
		$descuento = 0;

		if ($tipo === "porcentaje") {
			$descuento = round($precioOriginal * $valor / 100, 2);
		} else {
			// Monto fijo = importe descontado por unidad (no precio final)
			$descuento = $valor;
		}

		if ($descuento < 0) {
			$descuento = 0;
		}
		if ($descuento > $precioOriginal) {
			$descuento = $precioOriginal;
		}

		$precioFinal = round($precioOriginal - $descuento, 2);
		if ($precioFinal < 0) {
			$precioFinal = 0;
			$descuento = $precioOriginal;
		}

		return [
			"descuento_unitario" => $descuento,
			"precio_final" => $precioFinal
		];
	}

	/*=============================================
	VALIDACIONES INTERNAS
	=============================================*/
	private static function validarDatosCabecera($post)
	{
		$nombre = trim($post["nombrePromocion"] ?? ($post["editarNombrePromocion"] ?? ""));
		if ($nombre === "") {
			return "El nombre es obligatorio";
		}

		$inicio = $post["fechaInicioPromocion"] ?? ($post["editarFechaInicioPromocion"] ?? "");
		$fin = $post["fechaFinPromocion"] ?? ($post["editarFechaFinPromocion"] ?? "");
		if ($inicio === "" || $fin === "") {
			return "Las fechas de inicio y fin son obligatorias";
		}
		if (strtotime($fin) < strtotime($inicio)) {
			return "La fecha final no puede ser menor que la fecha inicial";
		}

		$prioridad = intval($post["prioridadPromocion"] ?? ($post["editarPrioridadPromocion"] ?? 1));
		if ($prioridad <= 0) {
			return "La prioridad debe ser mayor que cero";
		}

		return true;
	}

	private static function mapearDatosCabecera($post)
	{
		$esEdicion = isset($post["editarPromocion"]);
		return [
			"nombre" => trim($esEdicion ? $post["editarNombrePromocion"] : $post["nombrePromocion"]),
			"descripcion" => trim($esEdicion ? ($post["editarDescripcionPromocion"] ?? "") : ($post["descripcionPromocion"] ?? "")),
			"fecha_inicio" => date('Y-m-d H:i:s', strtotime($esEdicion ? $post["editarFechaInicioPromocion"] : $post["fechaInicioPromocion"])),
			"fecha_fin" => date('Y-m-d H:i:s', strtotime($esEdicion ? $post["editarFechaFinPromocion"] : $post["fechaFinPromocion"])),
			"prioridad" => intval($esEdicion ? $post["editarPrioridadPromocion"] : $post["prioridadPromocion"]),
			"estado" => intval($esEdicion ? ($post["editarEstadoPromocion"] ?? 0) : ($post["estadoPromocion"] ?? 0)),
			"modo_cantidad" => "individual",
			"observacion" => trim($esEdicion ? ($post["editarObservacionPromocion"] ?? "") : ($post["observacionPromocion"] ?? ""))
		];
	}

	private static function validarIntervalosSinSolape($existentes, $min, $max, $idExcluir = null)
	{
		$abiertos = 0;
		foreach ($existentes as $ex) {
			if ($idExcluir && intval($ex["id"]) === intval($idExcluir)) {
				continue;
			}
			$emin = intval($ex["cantidad_minima"]);
			$emax = $ex["cantidad_maxima"] !== null ? intval($ex["cantidad_maxima"]) : null;

			if ($emin === $min) {
				return "Ya existe un intervalo con la misma cantidad mínima";
			}

			if ($emax === null) {
				$abiertos++;
			}
			if ($max === null) {
				$abiertos++;
			}

			// Solape
			$nuevoMax = $max === null ? PHP_INT_MAX : $max;
			$exMax = $emax === null ? PHP_INT_MAX : $emax;
			if ($min <= $exMax && $nuevoMax >= $emin) {
				return "Los intervalos no deben superponerse";
			}
		}

		if ($abiertos > 1) {
			return "Solo puede haber un intervalo sin límite máximo y debe ser el último";
		}

		// Si hay abierto existente y se agrega otro después, o abierto que no es el último
		if ($max === null) {
			foreach ($existentes as $ex) {
				if ($idExcluir && intval($ex["id"]) === intval($idExcluir)) continue;
				if (intval($ex["cantidad_minima"]) > $min) {
					return "El intervalo sin límite máximo debe ser el último";
				}
			}
		} else {
			foreach ($existentes as $ex) {
				if ($idExcluir && intval($ex["id"]) === intval($idExcluir)) continue;
				if ($ex["cantidad_maxima"] === null && intval($ex["cantidad_minima"]) < $min) {
					// hay un abierto anterior: el nuevo no puede existir después... actually open is "from X onward" so nothing can come after
					return "Ya existe un intervalo abierto; no se pueden agregar intervalos posteriores";
				}
			}
		}

		return true;
	}

	private static function alerta($tipo, $mensaje, $ruta)
	{
		echo '<script>
			swal({
				type: "' . $tipo . '",
				title: "' . addslashes($mensaje) . '",
				showConfirmButton: true,
				confirmButtonText: "Cerrar"
			}).then(function(result){
				if(result.value){
					window.location = "' . $ruta . '";
				}
			});
		</script>';
	}
}
