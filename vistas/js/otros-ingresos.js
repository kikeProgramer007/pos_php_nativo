$(document).ready(function () {

  var enviandoOtroIngreso = false;
  var enviandoEditarOtroIngreso = false;

  function tipoEntradaSeleccionado(modalSelector, radioName) {
    return String($(modalSelector + " input[name='" + radioName + "']:checked").val() || "EFECTIVO").toUpperCase();
  }

  function actualizarUITipoEntrada(modalSelector, radioName, simpleSelector, mixtoSelector, montoSelector, efectivoSelector, qrSelector) {
    var tipo = tipoEntradaSeleccionado(modalSelector, radioName);
    $(modalSelector + " .entrada-opcion").removeClass("active");
    $(modalSelector + " .entrada-opcion[data-tipo='" + tipo + "']").addClass("active");

    if (tipo === "MIXTO") {
      $(simpleSelector).hide();
      $(montoSelector).prop("required", false);
      $(mixtoSelector).show();
      $(efectivoSelector + ", " + qrSelector).prop("required", true);
    } else {
      $(mixtoSelector).hide();
      $(efectivoSelector + ", " + qrSelector).prop("required", false);
      $(simpleSelector).show();
      $(montoSelector).prop("required", true);
    }
  }

  function recargarTablaOtrosIngresos() {
    if ($.fn.DataTable && $(".tabla-otros-ingresos").length) {
      $(".tabla-otros-ingresos").DataTable().ajax.reload(null, false);
    }
  }

  function actualizarUIAgregar() {
    actualizarUITipoEntrada(
      "#modalAgregarOtroIngreso",
      "tipo_entrada_otro_ingreso",
      "#modalAgregarOtroIngreso .grupo-monto-simple",
      "#modalAgregarOtroIngreso .grupo-monto-mixto",
      "#monto_otro_ingreso",
      "#monto_efectivo_otro_ingreso",
      "#monto_qr_otro_ingreso"
    );
  }

  function actualizarUIEditar() {
    actualizarUITipoEntrada(
      "#modalEditarOtroIngreso",
      "editar_tipo_entrada_otro_ingreso",
      "#modalEditarOtroIngreso .grupo-monto-simple-editar",
      "#modalEditarOtroIngreso .grupo-monto-mixto-editar",
      "#editar_monto_otro_ingreso",
      "#editar_monto_efectivo_otro_ingreso",
      "#editar_monto_qr_otro_ingreso"
    );
  }

  $(document).on("change", "input[name='tipo_entrada_otro_ingreso']", actualizarUIAgregar);
  $(document).on("change", "input[name='editar_tipo_entrada_otro_ingreso']", actualizarUIEditar);

  $(document).on("click", "#modalAgregarOtroIngreso .entrada-opcion", function () {
    $(this).find("input[type='radio']").prop("checked", true).trigger("change");
  });
  $(document).on("click", "#modalEditarOtroIngreso .entrada-opcion", function () {
    $(this).find("input[type='radio']").prop("checked", true).trigger("change");
  });

  $("#modalAgregarOtroIngreso").on("shown.bs.modal", actualizarUIAgregar);
  $("#modalEditarOtroIngreso").on("shown.bs.modal", actualizarUIEditar);

  $(document).on("submit", "#formAgregarOtroIngreso", function (e) {
    e.preventDefault();

    if (enviandoOtroIngreso) {
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
        descripcion_otro_ingreso: $.trim($("#descripcion_otro_ingreso").val() || ""),
        tipo_entrada_otro_ingreso: tipoEntradaSeleccionado("#modalAgregarOtroIngreso", "tipo_entrada_otro_ingreso"),
        monto_otro_ingreso: $("#monto_otro_ingreso").val(),
        monto_efectivo_otro_ingreso: $("#monto_efectivo_otro_ingreso").val(),
        monto_qr_otro_ingreso: $("#monto_qr_otro_ingreso").val()
      }
    }).done(function (respuesta) {
      if (respuesta && respuesta.status === "ok") {
        $("#modalAgregarOtroIngreso").modal("hide");
        $("#formAgregarOtroIngreso")[0].reset();
        $("input[name='tipo_entrada_otro_ingreso'][value='EFECTIVO']").prop("checked", true);
        actualizarUIAgregar();
        swal({
          toast: true,
          position: "top-right",
          type: "success",
          title: respuesta.mensaje || "El ingreso se registró correctamente.",
          showConfirmButton: false,
          timer: 3000,
          animation: true,
          backdrop: false
        });
        recargarTablaOtrosIngresos();
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

  $(".tabla-otros-ingresos").on("click", ".btnEditarOtroIngreso", function () {
    var idOtroIngreso = $(this).attr("idOtroIngreso");
    var datos = new FormData();
    datos.append("accion", "obtenerOtroIngreso");
    datos.append("idOtroIngreso", idOtroIngreso);

    $.ajax({
      url: "ajax/otros_ingresos.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType: "json",
      success: function (respuesta) {
        if (!respuesta || respuesta.status !== "ok" || !respuesta.data) {
          swal({
            type: "error",
            title: "No se pudo cargar",
            text: (respuesta && respuesta.mensaje) ? respuesta.mensaje : "Ingreso no encontrado."
          });
          return;
        }

        var ing = respuesta.data;
        var tipo = String(ing.tipo_entrada || "EFECTIVO").toUpperCase();

        $("#id_otro_ingreso").val(ing.id);
        $("#editar_descripcion_otro_ingreso").val(ing.descripcion);
        $("input[name='editar_tipo_entrada_otro_ingreso'][value='" + tipo + "']").prop("checked", true);

        if (tipo === "MIXTO") {
          $("#editar_monto_efectivo_otro_ingreso").val(parseFloat(ing.monto_efectivo || 0).toFixed(2));
          $("#editar_monto_qr_otro_ingreso").val(parseFloat(ing.monto_qr || 0).toFixed(2));
          $("#editar_monto_otro_ingreso").val("");
        } else {
          $("#editar_monto_otro_ingreso").val(parseFloat(ing.monto || 0).toFixed(2));
          $("#editar_monto_efectivo_otro_ingreso").val("");
          $("#editar_monto_qr_otro_ingreso").val("");
        }

        actualizarUIEditar();
      }
    });
  });

  $(document).on("submit", "#formEditarOtroIngreso", function (e) {
    e.preventDefault();

    if (enviandoEditarOtroIngreso) {
      return;
    }

    enviandoEditarOtroIngreso = true;
    var $boton = $("#btnGuardarEditarOtroIngreso");
    $boton.prop("disabled", true);

    $.ajax({
      url: "ajax/otros_ingresos.ajax.php",
      method: "POST",
      dataType: "json",
      data: {
        accion: "editarOtroIngreso",
        id_otro_ingreso: $("#id_otro_ingreso").val(),
        editar_descripcion_otro_ingreso: $.trim($("#editar_descripcion_otro_ingreso").val() || ""),
        editar_tipo_entrada_otro_ingreso: tipoEntradaSeleccionado("#modalEditarOtroIngreso", "editar_tipo_entrada_otro_ingreso"),
        editar_monto_otro_ingreso: $("#editar_monto_otro_ingreso").val(),
        editar_monto_efectivo_otro_ingreso: $("#editar_monto_efectivo_otro_ingreso").val(),
        editar_monto_qr_otro_ingreso: $("#editar_monto_qr_otro_ingreso").val()
      }
    }).done(function (respuesta) {
      if (respuesta && respuesta.status === "ok") {
        $("#modalEditarOtroIngreso").modal("hide");
        swal({
          toast: true,
          position: "top-right",
          type: "success",
          title: respuesta.mensaje || "El ingreso se editó correctamente.",
          showConfirmButton: false,
          timer: 3000,
          animation: true,
          backdrop: false
        });
        recargarTablaOtrosIngresos();
        return;
      }

      swal({
        type: "error",
        title: "No se pudo editar",
        text: (respuesta && respuesta.mensaje) ? respuesta.mensaje : "Ocurrió un error al editar el ingreso."
      });
    }).fail(function () {
      swal({
        type: "error",
        title: "Error",
        text: "No se pudo conectar con el servidor."
      });
    }).always(function () {
      enviandoEditarOtroIngreso = false;
      $boton.prop("disabled", false);
    });
  });

  $(".tabla-otros-ingresos").on("click", ".btnAnularOtroIngreso", function () {
    var idOtroIngreso = $(this).attr("idOtroIngreso");

    swal({
      title: "¿Anular este ingreso?",
      text: "El registro quedará anulado y se actualizará el arqueo de caja.",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#3085d6",
      cancelButtonText: "Cancelar",
      confirmButtonText: "Sí, anular"
    }).then(function (result) {
      if (!result.value) {
        return;
      }

      $.ajax({
        url: "ajax/otros_ingresos.ajax.php",
        method: "POST",
        dataType: "json",
        data: {
          accion: "anularOtroIngreso",
          id_otro_ingreso: idOtroIngreso
        }
      }).done(function (respuesta) {
        if (respuesta && respuesta.status === "ok") {
          swal({
            toast: true,
            position: "top-right",
            type: "success",
            title: respuesta.mensaje || "Ingreso anulado.",
            showConfirmButton: false,
            timer: 3000,
            animation: true,
            backdrop: false
          });
          recargarTablaOtrosIngresos();
          return;
        }

        swal({
          type: "error",
          title: "No se pudo anular",
          text: (respuesta && respuesta.mensaje) ? respuesta.mensaje : "Ocurrió un error."
        });
      }).fail(function () {
        swal({
          type: "error",
          title: "Error",
          text: "No se pudo conectar con el servidor."
        });
      });
    });
  });

  $("#modalAgregarOtroIngreso").on("hidden.bs.modal", function () {
    enviandoOtroIngreso = false;
    $("#btnGuardarOtroIngreso").prop("disabled", false);
    if ($("#formAgregarOtroIngreso")[0]) {
      $("#formAgregarOtroIngreso")[0].reset();
      $("input[name='tipo_entrada_otro_ingreso'][value='EFECTIVO']").prop("checked", true);
      actualizarUIAgregar();
    }
  });

  actualizarUIAgregar();

});
