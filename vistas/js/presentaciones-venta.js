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
    var factor = parseInt($input.attr("data-factor"), 10) || 1;
    // Solo visible si la presentación multiplica unidades (ej. Balde 5)
    if (factor <= 1) {
      $lbl.text("").hide();
      return;
    }
    var und = this.unidadesDeInput($input);
    $lbl.text("= " + und + " Unidades").show();
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

  /** Aplica la opción actualmente seleccionada del <select> a la fila. */
  sincronizarSelectConFila: function ($sel) {
    var $fila = this.filaDe($sel);
    var $opt = $sel.find("option:selected");
    this.aplicarPresentacionEnFila(
      $fila,
      $sel.val(),
      $opt.data("nombre") || $opt.attr("data-nombre"),
      $opt.data("factor") || $opt.attr("data-factor")
    );
  },

  /** Restaura el select a un valor previo (o Unidad) y sincroniza data-factor. */
  restaurarSelectPresentacion: function ($sel, valorAnterior) {
    var destino = valorAnterior != null && valorAnterior !== "" ? String(valorAnterior) : "0";
    var existe = $sel.find("option").filter(function () {
      return String($(this).val()) === destino;
    }).length > 0;
    if (!existe) {
      destino = "0";
    }
    $sel.val(destino);
    this.sincronizarSelectConFila($sel);
    $sel.data("valorAnterior", $sel.val());
  },

  forzarUnidadEnFila: function ($fila) {
    var $sel = $fila.find(".select-presentacion-venta");
    if ($sel.length) {
      this.restaurarSelectPresentacion($sel, "0");
    } else {
      this.aplicarPresentacionEnFila($fila, 0, "Unidad", 1);
    }
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

$(document).on("focus", ".select-presentacion-venta", function () {
  $(this).data("valorAnterior", $(this).val());
});

$(document).on("change", ".select-presentacion-venta", function () {
  var $sel = $(this);
  var valorAnterior = $sel.data("valorAnterior");
  if (valorAnterior === undefined || valorAnterior === null) {
    valorAnterior = "0";
  }
  PresentacionesVenta.sincronizarSelectConFila($sel);
  var $input = PresentacionesVenta.filaDe($sel).find(".nuevaCantidadProducto");
  if (typeof actualizarCantidadProducto === "function") {
    actualizarCantidadProducto($input, {
      revertirPresentacion: function () {
        PresentacionesVenta.restaurarSelectPresentacion($sel, valorAnterior);
      }
    });
  } else {
    $input.trigger("input");
  }
  $sel.data("valorAnterior", $sel.val());
});
