/*=============================================
OFERTAS Y PROMOCIONES
=============================================*/

function cargarTablaPromociones() {
  if (!$.fn.DataTable) return;
  if ($.fn.DataTable.isDataTable('.tablasPromociones')) {
    $('.tablasPromociones').DataTable().destroy();
  }

  var nombre = $("#filtroNombrePromo").val() || "";
  var estado = $("#filtroEstadoPromo").val() || "todos";
  var vigencia = $("#filtroVigenciaPromo").val() || "";

  $('.tablasPromociones').DataTable({
    ajax: "ajax/tabladinamica/datatable-promociones.ajax.php?nombre=" + encodeURIComponent(nombre) +
          "&estado=" + encodeURIComponent(estado) +
          "&vigencia=" + encodeURIComponent(vigencia),
    deferRender: true,
    retrieve: true,
    processing: true,
    initComplete: function () {
      var $input = $(this).closest('.dataTables_wrapper').find('.dataTables_filter input[type="search"]');
      if ($input.length) {
        $input.attr('placeholder', 'Buscar promoción');
        $input.css('font-size', '15px');
      }
    },
    language: {
      sProcessing: "Procesando...",
      sLengthMenu: "Mostrar _MENU_ registros",
      sZeroRecords: "No se encontraron resultados",
      sEmptyTable: "Ningún dato disponible en esta tabla",
      sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
      sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0",
      sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
      sSearch: "",
      oPaginate: {
        sFirst: "Primero",
        sLast: "Último",
        sNext: "Siguiente",
        sPrevious: "Anterior"
      }
    }
  });
}

$(document).ready(function() {
  if ($('.tablasPromociones').length) {
    cargarTablaPromociones();
  }

  $("#btnFiltrarPromos").on("click", cargarTablaPromociones);
  $("#filtroNombrePromo").on("keyup", function(e) {
    if (e.key === "Enter") cargarTablaPromociones();
  });

  if ($("#idPromocionActual").length) {
    cargarIntervalosPromo();
    cargarProductosPromo();
  }
});

$(document).on("click", ".btnTogglePromo", function() {
  var id = $(this).attr("idPromocion");
  var estado = $(this).attr("estado");
  $.post("ajax/promociones.ajax.php", {
    accion: "cambiarEstado",
    idPromocion: id,
    estado: estado
  }, function(resp) {
    var data = typeof resp === "string" ? JSON.parse(resp) : resp;
    swal({
      type: data.status === "ok" ? "success" : "warning",
      title: data.mensaje,
      confirmButtonText: "Cerrar"
    }).then(function() {
      if (data.status === "ok") cargarTablaPromociones();
    });
  });
});

$(document).on("click", ".btnEliminarPromo", function() {
  var id = $(this).attr("idPromocion");
  swal({
    title: "¿Deshabilitar promoción?",
    text: "La promoción dejará de aplicarse en nuevas ventas",
    type: "warning",
    showCancelButton: true,
    confirmButtonText: "Sí, deshabilitar",
    cancelButtonText: "Cancelar"
  }).then(function(result) {
    if (!result.value) return;
    $.post("ajax/promociones.ajax.php", {
      accion: "eliminarPromocion",
      idPromocion: id
    }, function(resp) {
      var data = typeof resp === "string" ? JSON.parse(resp) : resp;
      swal({ type: data.status === "ok" ? "success" : "error", title: data.mensaje }).then(function() {
        if (data.status === "ok") cargarTablaPromociones();
      });
    });
  });
});

/*=============================================
DETALLE: INTERVALOS
=============================================*/
function idPromocionActual() {
  return $("#idPromocionActual").val();
}

function cargarIntervalosPromo() {
  $.getJSON("ajax/promociones.ajax.php", {
    accion: "listarIntervalos",
    idPromocion: idPromocionActual()
  }, function(resp) {
    var tbody = $("#tablaIntervalosPromo tbody");
    tbody.empty();
    (resp.data || []).forEach(function(it) {
      var maxTxt = it.cantidad_maxima === null || it.cantidad_maxima === "" ? "Sin límite" : it.cantidad_maxima;
      var tipoTxt = it.tipo_descuento === "porcentaje" ? it.valor_descuento + " %" : "Bs " + parseFloat(it.valor_descuento).toFixed(2);
      tbody.append(
        "<tr>" +
        "<td>" + it.cantidad_minima + "</td>" +
        "<td>" + maxTxt + "</td>" +
        "<td>" + (it.tipo_descuento === "porcentaje" ? "Porcentaje" : "Monto fijo") + "</td>" +
        "<td>" + tipoTxt + "</td>" +
        "<td><span class='label label-success'>Activo</span></td>" +
        "<td>" +
          "<button class='btn btn-xs btn-primary btnEditarIntervalo' data-item='" + JSON.stringify(it).replace(/'/g, "&#39;") + "'><i class='fa fa-pencil'></i></button> " +
          "<button class='btn btn-xs btn-danger btnEliminarIntervalo' idIntervalo='" + it.id + "'><i class='fa fa-times'></i></button>" +
        "</td>" +
        "</tr>"
      );
    });
    actualizarVistaPreviaGeneral();
  });
}

$("#btnNuevoIntervalo").on("click", function() {
  $("#idIntervaloEdit").val("");
  $("#intCantMin").val("");
  $("#intCantMax").val("");
  $("#intTipoDescuento").val("fijo");
  $("#intValorDescuento").val("");
  llenarSelectPreviewProductos();
  $("#previewIntervaloTexto").text("Complete los datos para calcular.");
  $("#modalIntervaloPromo").modal("show");
});

$(document).on("click", ".btnEditarIntervalo", function() {
  var it = $(this).data("item");
  if (typeof it === "string") it = JSON.parse(it);
  $("#idIntervaloEdit").val(it.id);
  $("#intCantMin").val(it.cantidad_minima);
  $("#intCantMax").val(it.cantidad_maxima === null ? "" : it.cantidad_maxima);
  $("#intTipoDescuento").val(it.tipo_descuento);
  $("#intValorDescuento").val(it.valor_descuento);
  llenarSelectPreviewProductos();
  calcularPreviewIntervalo();
  $("#modalIntervaloPromo").modal("show");
});

$(document).on("click", ".btnEliminarIntervalo", function() {
  var id = $(this).attr("idIntervalo");
  swal({
    title: "¿Eliminar intervalo?",
    type: "warning",
    showCancelButton: true,
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar"
  }).then(function(result) {
    if (!result.value) return;
    $.post("ajax/promociones.ajax.php", {
      accion: "eliminarIntervalo",
      idIntervalo: id
    }, function(resp) {
      var data = typeof resp === "string" ? JSON.parse(resp) : resp;
      if (data.status === "ok") cargarIntervalosPromo();
      else swal({ type: "error", title: data.mensaje });
    });
  });
});

$("#btnGuardarIntervalo").on("click", function() {
  $.post("ajax/promociones.ajax.php", {
    accion: "guardarIntervalo",
    id: $("#idIntervaloEdit").val(),
    id_promocion: idPromocionActual(),
    cantidad_minima: $("#intCantMin").val(),
    cantidad_maxima: $("#intCantMax").val(),
    tipo_descuento: $("#intTipoDescuento").val(),
    valor_descuento: $("#intValorDescuento").val()
  }, function(resp) {
    var data = typeof resp === "string" ? JSON.parse(resp) : resp;
    if (data.status === "ok") {
      $("#modalIntervaloPromo").modal("hide");
      cargarIntervalosPromo();
    } else {
      swal({ type: "warning", title: data.mensaje });
    }
  });
});

$("#intCantMin, #intCantMax, #intTipoDescuento, #intValorDescuento, #intProductoPreview").on("input change", calcularPreviewIntervalo);

function llenarSelectPreviewProductos() {
  var sel = $("#intProductoPreview");
  sel.empty().append('<option value="">— Seleccione —</option>');
  $("#tablaProductosPromo tbody tr").each(function() {
    var id = $(this).attr("data-id-producto");
    var nombre = $(this).attr("data-nombre");
    var precio = $(this).attr("data-precio");
    if (id) {
      sel.append('<option value="' + id + '" data-precio="' + precio + '">' + nombre + ' (Bs ' + parseFloat(precio).toFixed(2) + ')</option>');
    }
  });
}

function calcularPreviewIntervalo() {
  var opt = $("#intProductoPreview option:selected");
  var precio = parseFloat(opt.data("precio") || 0);
  var tipo = $("#intTipoDescuento").val();
  var valor = parseFloat($("#intValorDescuento").val() || 0);
  var min = parseInt($("#intCantMin").val() || 0, 10);
  var max = $("#intCantMax").val();

  if (!precio || !valor || !min) {
    $("#previewIntervaloTexto").html(
      precio ? "Precio normal: Bs " + precio.toFixed(2) + ". Complete cantidad y descuento." :
      "Si hay varios productos con precios distintos, el precio final dependerá de cada producto."
    );
    return;
  }

  var desc = tipo === "porcentaje" ? round2(precio * valor / 100) : valor;
  if (desc > precio) desc = precio;
  var final = round2(precio - desc);
  if (final < 0) final = 0;
  var totalMin = round2(final * min);

  $("#previewIntervaloTexto").html(
    "<div>Precio original: <strong>Bs " + precio.toFixed(2) + "</strong></div>" +
    "<div>Descuento por unidad: <strong>Bs " + desc.toFixed(2) + "</strong></div>" +
    "<div>Precio promocional: <strong>Bs " + final.toFixed(2) + "</strong></div>" +
    "<div>Total por " + min + " unidades: <strong>Bs " + totalMin.toFixed(2) + "</strong></div>" +
    (max ? "<div>Hasta " + max + " unidades</div>" : "<div>Desde " + min + " unidades en adelante</div>")
  );
}

function round2(n) {
  return Math.round((n + Number.EPSILON) * 100) / 100;
}

/*=============================================
DETALLE: PRODUCTOS
=============================================*/
function cargarProductosPromo() {
  $.getJSON("ajax/promociones.ajax.php", {
    accion: "listarProductosPromocion",
    idPromocion: idPromocionActual()
  }, function(resp) {
    var tbody = $("#tablaProductosPromo tbody");
    tbody.empty();
    (resp.data || []).forEach(function(p) {
      var estado = parseInt(p.estado_producto, 10) === 1
        ? "<span class='label label-success'>Activo</span>"
        : "<span class='label label-default'>Inactivo</span>";
      tbody.append(
        "<tr data-id-producto='" + p.id_producto + "' data-nombre='" + $("<div>").text(p.descripcion).html() + "' data-precio='" + p.precio_venta + "'>" +
        "<td><img src='" + p.imagen + "' width='40' class='img-thumbnail'></td>" +
        "<td>" + p.codigo + "</td>" +
        "<td>" + p.descripcion + "</td>" +
        "<td>Bs " + parseFloat(p.precio_venta).toFixed(2) + "</td>" +
        "<td>" + estado + "</td>" +
        "<td><button class='btn btn-xs btn-danger btnQuitarProdPromo' idVinculo='" + p.id + "'><i class='fa fa-unlink'></i> Quitar</button></td>" +
        "</tr>"
      );
    });
    llenarSelectPreviewProductos();
    actualizarVistaPreviaGeneral();
  });
}

$(document).on("click", ".btnQuitarProdPromo", function() {
  var id = $(this).attr("idVinculo");
  swal({
    title: "¿Quitar vinculación?",
    text: "El producto no se elimina del catálogo",
    type: "warning",
    showCancelButton: true,
    confirmButtonText: "Sí, quitar",
    cancelButtonText: "Cancelar"
  }).then(function(result) {
    if (!result.value) return;
    $.post("ajax/promociones.ajax.php", {
      accion: "quitarProducto",
      idVinculo: id
    }, function(resp) {
      var data = typeof resp === "string" ? JSON.parse(resp) : resp;
      if (data.status === "ok") cargarProductosPromo();
      else swal({ type: "error", title: data.mensaje });
    });
  });
});

function buscarProductosParaPromo() {
  $.getJSON("ajax/promociones.ajax.php", {
    accion: "buscarProductos",
    idPromocion: idPromocionActual(),
    term: $("#buscarNombreProdPromo").val(),
    codigo: $("#buscarCodigoProdPromo").val(),
    idCategoria: $("#buscarCategoriaProdPromo").val()
  }, function(resp) {
    var tbody = $("#tablaBuscarProdPromo tbody");
    tbody.empty();
    (resp.data || []).forEach(function(p) {
      tbody.append(
        "<tr>" +
        "<td><input type='checkbox' class='checkProdPromo' value='" + p.id + "'></td>" +
        "<td><img src='" + p.imagen + "' width='35'></td>" +
        "<td>" + p.codigo + "</td>" +
        "<td>" + p.descripcion + "</td>" +
        "<td>Bs " + parseFloat(p.precio_venta).toFixed(2) + "</td>" +
        "</tr>"
      );
    });
  });
}

$("#modalAgregarProductosPromo").on("shown.bs.modal", buscarProductosParaPromo);
$("#buscarNombreProdPromo, #buscarCodigoProdPromo").on("keyup", function() {
  clearTimeout(window._promoBuscarTimer);
  window._promoBuscarTimer = setTimeout(buscarProductosParaPromo, 300);
});
$("#buscarCategoriaProdPromo").on("change", buscarProductosParaPromo);

$("#checkTodosVisiblePromo").on("change", function() {
  $(".checkProdPromo").prop("checked", $(this).is(":checked"));
});

$("#btnConfirmarProductosPromo").on("click", function() {
  var ids = [];
  $(".checkProdPromo:checked").each(function() {
    ids.push($(this).val());
  });
  if (!ids.length) {
    swal({ type: "warning", title: "Seleccione al menos un producto" });
    return;
  }
  $.post("ajax/promociones.ajax.php", {
    accion: "vincularProductos",
    idPromocion: idPromocionActual(),
    idsProductos: JSON.stringify(ids)
  }, function(resp) {
    var data = typeof resp === "string" ? JSON.parse(resp) : resp;
    if (data.status === "ok") {
      $("#modalAgregarProductosPromo").modal("hide");
      cargarProductosPromo();
    } else {
      swal({ type: "warning", title: data.mensaje });
    }
  });
});

function actualizarVistaPreviaGeneral() {
  var tip = $("#tablaProductosPromo tbody tr").length
    ? "La promoción tiene productos vinculados. Al crear/editar un intervalo puede ver el precio estimado por producto."
    : "Vincule productos para estimar precios finales.";
  $("#vistaPreviaPromo").html("<p>" + tip + "</p><p class='text-muted'>Modo de cantidad: individual por producto (no se suman productos distintos).</p>");
}

/*=============================================
APLICACIÓN EN VENTAS (API global)
=============================================*/
window.PromocionesVenta = {
  obtenerPrecioOriginal: function($precio) {
    var orig = parseFloat($precio.attr("precioOriginal"));
    if (!orig || isNaN(orig)) {
      orig = parseFloat($precio.attr("precioReal")) || 0;
      $precio.attr("precioOriginal", orig);
    }
    // precioReal siempre = unitario ORIGINAL (no se sobrescribe con precio promocional)
    $precio.attr("precioReal", orig);
    return orig;
  },

  recalcular: function(callback) {
    var items = [];
    $(".nuevaDescripcionProducto").each(function() {
      var $producto = $(this);
      var $fila = $producto.closest(".row");
      var $cantidad = $fila.find(".nuevaCantidadProducto");
      var $precio = $fila.find(".nuevoPrecioProducto");
      var precioOriginal = PromocionesVenta.obtenerPrecioOriginal($precio);
      items.push({
        id: $producto.attr("idProducto") || $producto.attr("data-idProducto"),
        cantidad: $cantidad.val(),
        precio: precioOriginal
      });
    });

    if (!items.length) {
      if (typeof sumarTotalPrecios === "function") sumarTotalPrecios();
      if (typeof callback === "function") callback({});
      return;
    }

    $.post("ajax/promociones.ajax.php", {
      accion: "calcularPromociones",
      items: JSON.stringify(items)
    }, function(resp) {
      var data = typeof resp === "string" ? JSON.parse(resp) : resp;
      var mapa = data.data || {};
      PromocionesVenta.aplicarEnUI(mapa);
      if (typeof callback === "function") callback(mapa);
    }).fail(function() {
      if (typeof callback === "function") callback({});
    });
  },

  aplicarEnUI: function(mapa) {
    $(".nuevaDescripcionProducto").each(function() {
      var $producto = $(this);
      var $fila = $producto.closest(".row");
      var id = String($producto.attr("idProducto") || $producto.attr("data-idProducto") || "");
      var $cantidad = $fila.find(".nuevaCantidadProducto");
      var $precio = $fila.find(".nuevoPrecioProducto");
      var cantLinea = parseFloat($cantidad.val() || 0);
      var precioBase = PromocionesVenta.obtenerPrecioOriginal($precio);
      var subtotalOriginal = round2(precioBase * cantLinea);

      var info = mapa[id] || mapa[parseInt(id, 10)] || null;
      var descUnit = 0;
      var descTotal = 0;
      var precioUnitFinal = precioBase;
      var subtotalFinal = subtotalOriginal;
      var promoData = {
        id_promocion: null,
        id_intervalo_promocion: null,
        nombre_promocion: null,
        tipo_descuento: null,
        valor_descuento: null,
        descuento_unitario: 0,
        descuento_total: 0,
        precio_original: precioBase,
        precio_unitario_final: precioBase,
        subtotal_original: subtotalOriginal,
        subtotal_final: subtotalOriginal
      };

      if (info && info.id_promocion && parseFloat(info.descuento_unitario) > 0) {
        descUnit = round2(parseFloat(info.descuento_unitario));
        if (descUnit > precioBase) {
          descUnit = precioBase;
        }
        precioUnitFinal = round2(precioBase - descUnit);
        if (precioUnitFinal < 0) {
          precioUnitFinal = 0;
          descUnit = precioBase;
        }
        descTotal = round2(descUnit * cantLinea);
        if (descTotal > subtotalOriginal) {
          descTotal = subtotalOriginal;
          precioUnitFinal = 0;
        }
        subtotalFinal = round2(subtotalOriginal - descTotal);
        if (subtotalFinal < 0) subtotalFinal = 0;

        promoData = {
          id_promocion: info.id_promocion,
          id_intervalo_promocion: info.id_intervalo_promocion,
          nombre_promocion: info.nombre_promocion,
          tipo_descuento: info.tipo_descuento,
          valor_descuento: info.valor_descuento,
          descuento_unitario: descUnit,
          descuento_total: descTotal,
          precio_original: precioBase,
          precio_unitario_final: precioUnitFinal,
          subtotal_original: subtotalOriginal,
          subtotal_final: subtotalFinal
        };
      }

      // El input visible muestra el SUBTOTAL ORIGINAL (qty × precio original)
      $precio.attr("precioReal", precioBase);
      $precio.attr("precioOriginal", precioBase);
      $precio.attr("data-subtotal-final", subtotalFinal);
      $precio.attr("data-precio-final", precioUnitFinal);
      $precio.attr("data-promo", JSON.stringify(promoData));
      $precio.val(subtotalOriginal.toFixed(2));

      var $badge = $fila.find(".promo-aplicada-info");
      if (!$badge.length) {
        $badge = $('<div class="promo-aplicada-info"></div>');
        $fila.find(".ingresoPrecio").append($badge);
      }

      // Destruir tooltip previo para no duplicar instancias
      $badge.find(".promo-etiqueta").each(function() {
        var $tip = $(this);
        if ($tip.data("bs.tooltip")) {
          $tip.tooltip("destroy");
        }
      });

      if (promoData.id_promocion && descTotal > 0) {
        var nombrePromo = promoData.nombre_promocion || "Promoción aplicada";
        var tooltipHtml =
          "<div class='promo-tooltip-detalle'>" +
          "<strong>" + PromocionesVenta.escaparHtml(nombrePromo) + "</strong><br>" +
          "Precio unitario: Bs " + precioBase.toFixed(2) + "<br>" +
          "Cantidad: " + cantLinea + "<br>" +
          "Subtotal original: Bs " + subtotalOriginal.toFixed(2) + "<br>" +
          "Descuento aplicado: Bs " + descTotal.toFixed(2) + "<br>" +
          "<hr style='margin:6px 0; border-top-color:rgba(255,255,255,0.25);'>" +
          "<strong>Subtotal final: Bs " + subtotalFinal.toFixed(2) + "</strong>" +
          "</div>";

        $badge.html(
          "<span class='promo-etiqueta' tabindex='0'>" +
          "Promo: - Bs " + descTotal.toFixed(2) +
          " <i class='fa fa-info-circle'></i></span>"
        ).show();

        $badge.find(".promo-etiqueta")
          .attr("title", tooltipHtml)
          .tooltip({
            container: "body",
            html: true,
            placement: "left",
            trigger: "hover focus"
          });
      } else {
        $badge.hide().empty();
      }
    });

    if (typeof sumarTotalPrecios === "function") {
      sumarTotalPrecios();
    }
  },

  escaparHtml: function(texto) {
    return String(texto == null ? "" : texto)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#39;");
  }
};

$("#intTipoDescuento").on("change", function() {
  if ($(this).val() === "porcentaje") {
    $("#ayudaTipoDescuento").text("El porcentaje se aplica sobre el precio unitario original de cada producto.");
  } else {
    $("#ayudaTipoDescuento").text("Monto fijo representa el importe que se descontará por cada unidad, no el precio final del producto.");
  }
});
