/*=============================================
Helpers presentaciones en crear-venta
El input .nuevaCantidadProducto = cantidad de PRESENTACIONES.
Las UNIDADES REALES = presentaciones × factor.
=============================================*/
window.PresentacionesVenta = {
  filaDe: function ($el) {
    return $el.closest(".linea-venta");
  },

  factorDeFila: function ($fila) {
    var $input = $fila.find(".nuevaCantidadProducto").first();
    var factor = parseInt($input.attr("data-factor"), 10);
    return factor > 0 ? factor : 1;
  },

  qtyPresentaciones: function ($input) {
    var n = parseInt($input.val(), 10);
    return isNaN(n) || n < 1 ? 1 : n;
  },

  unidadesDeInput: function ($input) {
    var factor = parseInt($input.attr("data-factor"), 10);
    if (!factor || factor < 1) factor = 1;
    return this.qtyPresentaciones($input) * factor;
  },

  metaDeInput: function ($input) {
    var factor = parseInt($input.attr("data-factor"), 10) || 1;
    var idPres = parseInt($input.attr("data-id-presentacion"), 10) || 0;
    return {
      id_presentacion: idPres > 0 ? idPres : null,
      nombre_presentacion: $input.attr("data-nombre-presentacion") || "Unidad",
      cantidad_presentaciones: this.qtyPresentaciones($input),
      unidades_por_presentacion: factor,
      cantidad: this.qtyPresentaciones($input) * factor
    };
  },

  actualizarEtiqueta: function ($fila) {
    var $input = $fila.find(".nuevaCantidadProducto").first();
    var $lbl = $fila.find(".lbl-unidades-reales");
    if (!$lbl.length) return;
    var und = this.unidadesDeInput($input);
    $lbl.text("= " + und + " und.");
  },

  aplicarPresentacionEnFila: function ($fila, id, nombre, factor) {
    var $input = $fila.find(".nuevaCantidadProducto").first();
    factor = parseInt(factor, 10) || 1;
    id = parseInt(id, 10) || 0;
    $input.attr("data-factor", factor);
    $input.attr("data-id-presentacion", id > 0 ? id : "");
    $input.attr("data-nombre-presentacion", nombre || "Unidad");
    if (!$input.val() || Number($input.val()) < 1) {
      $input.val(1);
    }
    this.actualizarEtiqueta($fila);
  },

  opcionesHtml: function (presentaciones, selectedId) {
    var html = '<option value="0" data-factor="1" data-nombre="Unidad">Unidad (1 und.)</option>';
    (presentaciones || []).forEach(function (p) {
      if (Number(p.estado) === 0) return;
      var sel = String(selectedId || "") === String(p.id) ? " selected" : "";
      var und = Number(p.cantidad_unidades) || 1;
      html += '<option value="' + p.id + '" data-factor="' + und + '" data-nombre="' +
        $("<div>").text(p.nombre).html() + '"' + sel + ">" +
        $("<div>").text(p.nombre).html() + " (" + und + " und.)" +
        "</option>";
    });
    return html;
  },

  /** Select de presentación; se oculta si solo existe Unidad (1 und.) */
  bloqueSelectorHtml: function (presentaciones, selectedId) {
    var lista = presentaciones || [];
    var extras = 0;
    lista.forEach(function (p) {
      if (Number(p.estado) === 0) return;
      extras++;
    });
    var ocultar = extras < 1;
    var cls = "form-control input-sm select-presentacion-venta" + (ocultar ? " lv-pres-solo-unidad" : "");
    var wrapCls = "lv-presentacion" + (ocultar ? " lv-pres-solo-unidad" : "");
    return '<div class="' + wrapCls + '">' +
      '<select class="' + cls + '">' +
      this.opcionesHtml(lista, selectedId) +
      "</select></div>";
  }
};

$(document).on("change", ".select-presentacion-venta", function () {
  var $sel = $(this);
  var $opt = $sel.find("option:selected");
  var $fila = PresentacionesVenta.filaDe($sel);
  PresentacionesVenta.aplicarPresentacionEnFila(
    $fila,
    $sel.val(),
    $opt.data("nombre") || $opt.attr("data-nombre"),
    $opt.data("factor") || $opt.attr("data-factor")
  );
  var $input = $fila.find(".nuevaCantidadProducto");
  if (typeof actualizarCantidadProducto === "function") {
    actualizarCantidadProducto($input);
  } else {
    $input.trigger("input");
  }
});
