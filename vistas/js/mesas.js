/*=============================================
EDITAR MESA
=============================================*/
$(".tablas").on("click", ".btnEditarMesa", function () {
	var idMesa = $(this).attr("idMesa");
	var datos = new FormData();
	datos.append("idMesa", idMesa);

	$.ajax({
		url: "ajax/mesas.ajax.php",
		method: "POST",
		data: datos,
		cache: false,
		contentType: false,
		processData: false,
		dataType: "json",
		success: function (respuesta) {
			if (!respuesta) {
				return;
			}
			$("#idMesa").val(respuesta["id"]);
			$("#editarNumeroMesa").val(respuesta["numero"] || "");
			$("#editarDescripcionMesa").val(respuesta["descripcion"] || "");
			$("#editarCapacidadMesa").val(respuesta["capacidad"]);
			$("#editarEstadoMesa").val(respuesta["estado"]);

			if (respuesta["estado"] === "ocupada") {
				$("#editarEstadoMesa option[value='disponible']").prop("disabled", true);
				$("#editarEstadoMesa option[value='ocupada']").prop("disabled", false);
			} else {
				$("#editarEstadoMesa option[value='disponible']").prop("disabled", false);
				$("#editarEstadoMesa option[value='ocupada']").prop("disabled", true);
			}
		}
	});
});

/*=============================================
ELIMINAR MESA
=============================================*/
$(".tablas").on("click", ".btnEliminarMesa", function () {
	var idMesa = $(this).attr("idMesa");
	var nombre = $(this).attr("nombreMesa") || "";

	swal({
		title: "¿Eliminar " + nombre + "?",
		text: "Si tiene historial o está ocupada no se podrá borrar. Puede deshabilitarla.",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#3085d6",
		cancelButtonColor: "#d33",
		cancelButtonText: "Cancelar",
		confirmButtonText: "Sí, eliminar"
	}).then(function (result) {
		if (result.value) {
			window.location = "index.php?ruta=mesas&idMesa=" + idMesa;
		}
	});
});
