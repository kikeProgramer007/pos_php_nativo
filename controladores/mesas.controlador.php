<?php

class ControladorMesas
{
	static public function ctrMostrarMesas($item = null, $valor = null)
	{
		return ModeloMesas::mdlMostrarMesas($item, $valor);
	}

	static private function normalizarEstado($estado)
	{
		$estado = strtolower(trim((string) $estado));
		if (in_array($estado, array("disponible", "ocupada", "deshabilitada"), true)) {
			return $estado;
		}
		return "disponible";
	}

	static public function ctrCrearMesa()
	{
		if (!isset($_POST["nuevaMesa"])) {
			return;
		}
		if (!Permisos::tiene("mesas.crear")) {
			Permisos::requiere("mesas.crear");
			return;
		}

		$numero = intval($_POST["nuevoNumeroMesa"] ?? 0);
		$descripcion = trim((string) ($_POST["nuevaDescripcionMesa"] ?? ""));
		$capacidad = intval($_POST["nuevaCapacidadMesa"] ?? 0);
		$estado = self::normalizarEstado($_POST["nuevoEstadoMesa"] ?? "disponible");

		if ($numero < 1 || $numero > 9999) {
			echo '<script>
				swal({type:"error",title:"Número inválido",text:"Indique el número de mesa (1 a 9999)."})
				.then(function(){ window.location="mesas"; });
			</script>';
			return;
		}
		if (strlen($descripcion) > 120) {
			echo '<script>
				swal({type:"error",title:"Descripción inválida",text:"Máximo 120 caracteres."})
				.then(function(){ window.location="mesas"; });
			</script>';
			return;
		}
		if ($capacidad < 1 || $capacidad > 99) {
			echo '<script>
				swal({type:"error",title:"Capacidad inválida",text:"La capacidad debe ser entre 1 y 99."})
				.then(function(){ window.location="mesas"; });
			</script>';
			return;
		}
		if ($estado === "ocupada") {
			$estado = "disponible";
		}
		if (ModeloMesas::mdlExisteNumero($numero)) {
			echo '<script>
				swal({type:"error",title:"Mesa duplicada",text:"Ya existe una mesa con ese número."})
				.then(function(){ window.location="mesas"; });
			</script>';
			return;
		}

		$respuesta = ModeloMesas::mdlCrear(array(
			"numero" => $numero,
			"descripcion" => $descripcion,
			"capacidad" => $capacidad,
			"estado" => $estado
		));

		if ($respuesta === "ok") {
			echo '<script>
				swal({type:"success",title:"Mesa creada",text:"La mesa se registró correctamente."})
				.then(function(){ window.location="mesas"; });
			</script>';
		} else {
			echo '<script>
				swal({type:"error",title:"Error",text:"No se pudo crear la mesa."})
				.then(function(){ window.location="mesas"; });
			</script>';
		}
	}

	static public function ctrEditarMesa()
	{
		if (!isset($_POST["editarMesa"])) {
			return;
		}
		if (!Permisos::tiene("mesas.editar")) {
			Permisos::requiere("mesas.editar");
			return;
		}

		$id = intval($_POST["idMesa"] ?? 0);
		$numero = intval($_POST["editarNumeroMesa"] ?? 0);
		$descripcion = trim((string) ($_POST["editarDescripcionMesa"] ?? ""));
		$capacidad = intval($_POST["editarCapacidadMesa"] ?? 0);
		$estado = self::normalizarEstado($_POST["editarEstadoMesa"] ?? "disponible");
		$actual = ModeloMesas::mdlMostrarMesas("id", $id);

		if (!$actual) {
			echo '<script>
				swal({type:"error",title:"No encontrada",text:"La mesa no existe."})
				.then(function(){ window.location="mesas"; });
			</script>';
			return;
		}
		if ($numero < 1 || $numero > 9999) {
			echo '<script>
				swal({type:"error",title:"Número inválido",text:"Indique el número de mesa (1 a 9999)."})
				.then(function(){ window.location="mesas"; });
			</script>';
			return;
		}
		if (strlen($descripcion) > 120) {
			echo '<script>
				swal({type:"error",title:"Descripción inválida",text:"Máximo 120 caracteres."})
				.then(function(){ window.location="mesas"; });
			</script>';
			return;
		}
		if ($capacidad < 1 || $capacidad > 99) {
			echo '<script>
				swal({type:"error",title:"Capacidad inválida",text:"La capacidad debe ser entre 1 y 99."})
				.then(function(){ window.location="mesas"; });
			</script>';
			return;
		}
		if (ModeloMesas::mdlExisteNumero($numero, $id)) {
			echo '<script>
				swal({type:"error",title:"Mesa duplicada",text:"Ya existe otra mesa con ese número."})
				.then(function(){ window.location="mesas"; });
			</script>';
			return;
		}

		// Desde admin solo Disponible o Deshabilitada; Ocupada la gestiona la atención.
		if ($actual["estado"] !== "ocupada" && $estado === "ocupada") {
			$estado = "disponible";
		}

		$respuesta = ModeloMesas::mdlEditar(array(
			"id" => $id,
			"numero" => $numero,
			"descripcion" => $descripcion,
			"capacidad" => $capacidad,
			"estado" => $estado
		));

		if ($respuesta === "ok") {
			echo '<script>
				swal({type:"success",title:"Mesa actualizada",text:"Los cambios se guardaron."})
				.then(function(){ window.location="mesas"; });
			</script>';
		} else {
			echo '<script>
				swal({type:"error",title:"Error",text:"No se pudo actualizar la mesa."})
				.then(function(){ window.location="mesas"; });
			</script>';
		}
	}

	static public function ctrEliminarMesa()
	{
		if (!isset($_GET["idMesa"])) {
			return;
		}
		if (!Permisos::tiene("mesas.eliminar")) {
			Permisos::requiere("mesas.eliminar");
			return;
		}

		$resultado = ModeloMesas::mdlEliminar(intval($_GET["idMesa"]));
		if (!empty($resultado["ok"])) {
			echo '<script>
				swal({type:"success",title:"Mesa eliminada",text:"Se eliminó correctamente."})
				.then(function(){ window.location="mesas"; });
			</script>';
		} else {
			$msg = isset($resultado["mensaje"]) ? addslashes($resultado["mensaje"]) : "No se pudo eliminar.";
			echo '<script>
				swal({type:"error",title:"No se puede eliminar",text:"' . $msg . '"})
				.then(function(){ window.location="mesas"; });
			</script>';
		}
	}

	static public function etiquetaEstado($estado)
	{
		switch (strtolower((string) $estado)) {
			case "disponible":
				return '<span class="label label-success">Disponible</span>';
			case "ocupada":
				return '<span class="label label-danger">Ocupada</span>';
			case "deshabilitada":
				return '<span class="label label-default">Deshabilitada</span>';
			default:
				return '<span class="label label-default">' . htmlspecialchars($estado) . '</span>';
		}
	}

	static public function ctrDetalleAtencion($idMesa)
	{
		return ModeloMesas::mdlDetalleAtencionMesa(intval($idMesa));
	}

	/** Liberar/cambiar mesa: con Gestión de Mesas + permiso de venta (offcanvas crear-venta). */
	static public function puedeOperarMesaEnVenta()
	{
		if (!class_exists("ModeloEmpresa")) {
			require_once __DIR__ . "/../modelos/empresa.modelo.php";
		}
		if (!ModeloEmpresa::mdlFuncionActiva("venta.gestion_mesas")) {
			return false;
		}
		return Permisos::tiene("ventas.crear")
			|| Permisos::tiene("ventas.editar")
			|| Permisos::tiene("ventas.cobrar");
	}

	static public function ctrLiberarMesaAjax()
	{
		if (!self::puedeOperarMesaEnVenta()) {
			echo json_encode(array("ok" => false, "mensaje" => "No autorizado"));
			return;
		}
		$idMesa = intval($_POST["idMesaLiberar"] ?? 0);
		$detalle = ModeloMesas::mdlDetalleAtencionMesa($idMesa);
		if (empty($detalle["ok"])) {
			echo json_encode($detalle);
			return;
		}
		if (!empty($detalle["tiene_deuda"])) {
			echo json_encode(array(
				"ok" => false,
				"mensaje" => "Hay saldos pendientes. Cobrar o resolver la deuda antes de liberar."
			));
			return;
		}
		$res = ModeloMesas::mdlLiberarAtencion($idMesa, intval($_SESSION["id"] ?? 0), false);
		echo json_encode($res);
	}

	/** Liberación desde selector / Ver mesa. No libera si hay deuda pendiente. */
	static public function ctrLiberarMesaDesdeSelectorAjax()
	{
		if (!self::puedeOperarMesaEnVenta()) {
			echo json_encode(array("ok" => false, "mensaje" => "No autorizado o Gestión de mesas desactivada."));
			return;
		}

		$idMesa = intval($_POST["liberarMesaDesdeSelector"] ?? 0);
		if ($idMesa <= 0) {
			echo json_encode(array("ok" => false, "mensaje" => "Mesa no válida."));
			return;
		}

		$detalle = ModeloMesas::mdlDetalleAtencionMesa($idMesa);
		if (empty($detalle["ok"])) {
			echo json_encode($detalle);
			return;
		}
		if (!empty($detalle["tiene_deuda"])) {
			echo json_encode(array(
				"ok" => false,
				"mensaje" => "Hay saldos pendientes. Cobrar antes de liberar la mesa."
			));
			return;
		}

		$res = ModeloMesas::mdlLiberarAtencion($idMesa, intval($_SESSION["id"] ?? 0), false);
		echo json_encode($res);
	}

	static public function ctrCambiarMesaAjax()
	{
		if (!self::puedeOperarMesaEnVenta()) {
			echo json_encode(array("ok" => false, "mensaje" => "No autorizado o Gestión de mesas desactivada."));
			return;
		}
		$idOrigen = intval($_POST["idMesaOrigen"] ?? 0);
		$idDestino = intval($_POST["idMesaDestino"] ?? 0);
		$res = ModeloMesas::mdlCambiarMesaAtencion($idOrigen, $idDestino, intval($_SESSION["id"] ?? 0));
		echo json_encode($res);
	}

	static public function ctrMesasDisponiblesAjax()
	{
		$todas = ModeloMesas::mdlMostrarMesas();
		$disponibles = array();
		if (is_array($todas)) {
			foreach ($todas as $m) {
				if (($m["estado"] ?? "") === "disponible") {
					$nombre = !empty($m["numero"])
						? ModeloMesas::mdlNombreDesdeNumero($m["numero"])
						: ($m["nombre"] ?? "");
					$disponibles[] = array(
						"id" => intval($m["id"]),
						"nombre" => $nombre,
						"capacidad" => intval($m["capacidad"]),
						"numero" => intval($m["numero"] ?? 0)
					);
				}
			}
		}
		echo json_encode(array("ok" => true, "mesas" => $disponibles));
	}
}
