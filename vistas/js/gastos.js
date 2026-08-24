/*=============================================
FORMA DE PAGO GASTO (radios + mixto)
=============================================*/
function tipoPagoGastoSeleccionado($contexto) {
  var $radio = $contexto.find("input[name='tipo_pago_gasto']:checked, input[name='editarTipoPago']:checked");
  return String($radio.val() || "1");
}

function actualizarUITipoPagoGasto($contexto) {
  var tipo = tipoPagoGastoSeleccionado($contexto);
  $contexto.find(".gasto-pago-opcion").removeClass("active");
  $contexto.find(".gasto-pago-opcion[data-tipo='" + tipo + "']").addClass("active");

  var esMixto = tipo === "4";
  var $simple = $contexto.find(".grupo-monto-gasto-simple");
  var $mixto = $contexto.find(".grupo-monto-gasto-mixto");
  var $montoSimple = $contexto.find("input[name='monto_gasto'], input[name='editarMonto']");
  var $montoEfectivo = $contexto.find("input[name='monto_efectivo_gasto'], input[name='editarMontoEfectivo']");
  var $montoQr = $contexto.find("input[name='monto_qr_gasto'], input[name='editarMontoQr']");

  if (esMixto) {
    $simple.hide();
    $montoSimple.prop("required", false);
    $mixto.show();
    $montoEfectivo.prop("required", true);
    $montoQr.prop("required", true);
  } else {
    $mixto.hide();
    $montoEfectivo.prop("required", false);
    $montoQr.prop("required", false);
    $simple.show();
    $montoSimple.prop("required", true);
  }
}

function validarMontosGastoFormulario($form) {
  var tipo = tipoPagoGastoSeleccionado($form);
  var monto = 0;
  var montoEfectivo = 0;
  var montoQr = 0;
  var $montoSimple = $form.find("input[name='monto_gasto'], input[name='editarMonto']");
  var $montoEfectivo = $form.find("input[name='monto_efectivo_gasto'], input[name='editarMontoEfectivo']");
  var $montoQr = $form.find("input[name='monto_qr_gasto'], input[name='editarMontoQr']");

  if (tipo === "4") {
    montoEfectivo = parseFloat(String($montoEfectivo.val() || "").replace(",", "."));
    montoQr = parseFloat(String($montoQr.val() || "").replace(",", "."));
    if (isNaN(montoEfectivo) || montoEfectivo < 0 || isNaN(montoQr) || montoQr < 0) {
      swal({
        type: "error",
        title: "Montos inválidos",
        text: "Ingrese montos numéricos válidos en efectivo y QR."
      });
      return false;
    }
    if (montoEfectivo <= 0 && montoQr <= 0) {
      swal({
        type: "error",
        title: "Monto inválido",
        text: "En mixto al menos uno de los montos debe ser mayor a 0."
      });
      return false;
    }
    monto = Math.round((montoEfectivo + montoQr) * 100) / 100;
    $montoSimple.val(monto.toFixed(2));
  } else {
    monto = parseFloat(String($montoSimple.val() || "").replace(",", "."));
    if (isNaN(monto) || monto <= 0) {
      swal({
        type: "error",
        title: "Monto inválido",
        text: "El monto debe ser un número mayor a 0."
      });
      return false;
    }
  }

  return true;
}

$(document).on("change", "input[name='tipo_pago_gasto'], input[name='editarTipoPago']", function () {
  actualizarUITipoPagoGasto($(this).closest("form"));
});

$(document).on("click", ".gasto-pago-opcion", function () {
  $(this).find("input[type='radio']").prop("checked", true).trigger("change");
});

$(document).on("submit", ".form-gasto-pago", function (e) {
  if (!validarMontosGastoFormulario($(this))) {
    e.preventDefault();
    return false;
  }
});

$("#modalAgregarMesero, #modalEditarGasto").on("shown.bs.modal", function () {
  actualizarUITipoPagoGasto($(this).find("form"));
});

$(document).ready(function () {
  $(".form-gasto-pago").each(function () {
    actualizarUITipoPagoGasto($(this));
  });
});

/*=============================================
EDITAR GASTO
=============================================*/
$(".tabla-gastos").on("click", ".btnEditarGasto", function(){

	var idGasto = $(this).attr("idGasto");

	var datos = new FormData();
    datos.append("idGasto", idGasto);

    $.ajax({

      url:"ajax/gastos.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType:"json",
      success:function(respuesta){
      	 $("#idGasto").val(respuesta["id"]);
	       $("#editarIdTipoGasto").val(respuesta["id_tipo_gasto"]);
	       $("#editarDescripcion").val(respuesta["descripcion"]);
         $("#editarFecha").val(respuesta["fecha"]);
	       $("#editarMonto").val(respuesta["monto"]);

         var formaPago = String(respuesta["forma_pago"] || "1");
         $("input[name='editarTipoPago'][value='" + formaPago + "']").prop("checked", true);

         var montoEfectivo = respuesta["monto_efectivo"];
         var montoQr = respuesta["monto_qr"];
         if (formaPago === "4") {
           if (montoEfectivo === undefined || montoEfectivo === null || montoEfectivo === "") {
             montoEfectivo = (parseFloat(respuesta["monto"]) / 2).toFixed(2);
             montoQr = (parseFloat(respuesta["monto"]) - parseFloat(montoEfectivo)).toFixed(2);
           }
           $("#editarMontoEfectivo").val(montoEfectivo);
           $("#editarMontoQr").val(montoQr);
         } else {
           $("#editarMontoEfectivo").val("");
           $("#editarMontoQr").val("");
         }

         actualizarUITipoPagoGasto($("#formEditarGasto"));
	    }

  	})

})

/*=============================================
ELIMINAR GASTO
=============================================*/
$(".tabla-gastos").on("click", ".btnEliminarGasto", function(){

	var idGasto = $(this).attr("idGasto");
	
	swal({
        title: '¿Está seguro de borrar al Gasto?',
        text: "¡Si no lo está puede cancelar la acción!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, borrar el Gasto!'
      }).then(function(result){
        if (result.value) {
          
            window.location = "index.php?ruta=gastos&idGasto="+idGasto;
        }

  })

});
