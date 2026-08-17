$(document).ready(function () {

  var enviandoOtroIngreso = false;

  $(document).on("submit", "#formAgregarOtroIngreso", function (e) {
    e.preventDefault();

    if (enviandoOtroIngreso) {
      return;
    }

    var descripcion = $.trim($("#descripcion_otro_ingreso").val() || "");
    var monto = parseFloat(String($("#monto_otro_ingreso").val() || "").replace(",", "."));

    if (descripcion === "") {
      swal({
        type: "error",
        title: "Observación obligatoria",
        text: "Ingrese una observación para identificar el origen del ingreso."
      });
      return;
    }

    if (isNaN(monto) || monto <= 0) {
      swal({
        type: "error",
        title: "Monto inválido",
        text: "El monto debe ser un número mayor a 0."
      });
      return;
    }

    enviandoOtroIngreso = true;
    var $boton = $("#btnGuardarOtroIngreso");
    $boton.prop("disabled", true);

    $.ajax({
      url: "ajax/otros_ingresos.ajax.php",
      method: "POST",
      dataType: "json",
      data: {
        accion: "registrarOtroIngreso",
        descripcion_otro_ingreso: descripcion,
        monto_otro_ingreso: monto.toFixed(2)
      }
    }).done(function (respuesta) {
      if (respuesta && respuesta.status === "ok") {
        $("#modalAgregarOtroIngreso").modal("hide");
        $("#formAgregarOtroIngreso")[0].reset();
        swal({
          type: "success",
          title: "Ingreso registrado",
          text: respuesta.mensaje || "El ingreso se registró correctamente.",
          showConfirmButton: true,
          confirmButtonText: "Cerrar"
        });
        if ($.fn.DataTable && $(".tabla-otros-ingresos").length) {
          $(".tabla-otros-ingresos").DataTable().ajax.reload(null, false);
        }
        return;
      }

      if (respuesta && respuesta.codigo === "caja_cerrada") {
        swal({
          title: "Caja Cerrada",
          text: "La caja está cerrada. Es necesario realizar la apertura de caja antes de continuar. ¿Desea redirigirse a la vista de arqueo de caja para abrirla?",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Sí, ir a apertura de caja",
          cancelButtonText: "No"
        }).then(function (result) {
          if (result.value) {
            window.location.href = "arqueo-de-caja";
          }
        });
        return;
      }

      swal({
        type: "error",
        title: "No se pudo registrar",
        text: (respuesta && respuesta.mensaje) ? respuesta.mensaje : "Ocurrió un error al registrar el ingreso."
      });
    }).fail(function () {
      swal({
        type: "error",
        title: "Error",
        text: "No se pudo conectar con el servidor."
      });
    }).always(function () {
      enviandoOtroIngreso = false;
      $boton.prop("disabled", false);
    });
  });

  $("#modalAgregarOtroIngreso").on("hidden.bs.modal", function () {
    enviandoOtroIngreso = false;
    $("#btnGuardarOtroIngreso").prop("disabled", false);
    if ($("#formAgregarOtroIngreso")[0]) {
      $("#formAgregarOtroIngreso")[0].reset();
    }
  });

});
