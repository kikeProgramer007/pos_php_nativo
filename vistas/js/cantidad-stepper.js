/*=============================================
STEPPER GLOBAL (+ / −) para inputs numéricos
=============================================*/
(function ($) {
  "use strict";

  function resolverInput($btn) {
    var target = $btn.attr("data-target");
    if (target) {
      return $("#" + target);
    }
    return $btn.closest(".cantidad-stepper").find("input").first();
  }

  $(document).on("click", ".btn-cantidad-ajuste", function (e) {
    e.preventDefault();

    var $btn = $(this);
    if ($btn.is(":disabled") || $btn.prop("disabled")) {
      return;
    }

    var $input = resolverInput($btn);
    if (!$input.length || $input.prop("readonly") || $input.prop("disabled")) {
      return;
    }

    var valorActual = Number($input.val());
    if (isNaN(valorActual)) {
      valorActual = 0;
    }

    var minimoAttr = $input.attr("min");
    var maximoAttr = $input.attr("max");
    var pasoAttr = $input.attr("step");

    var minimo = minimoAttr !== undefined && minimoAttr !== "" ? Number(minimoAttr) : 0;
    var maximo = maximoAttr !== undefined && maximoAttr !== "" ? Number(maximoAttr) : null;
    var paso = pasoAttr && pasoAttr !== "any" ? Number(pasoAttr) : 1;
    if (!paso || isNaN(paso)) {
      paso = 1;
    }

    var accion = $btn.data("action") || $btn.attr("data-action");
    var nuevoValor = accion === "incrementar" ? valorActual + paso : valorActual - paso;

    if (nuevoValor < minimo) {
      nuevoValor = minimo;
    }
    if (maximo !== null && !isNaN(maximo) && nuevoValor > maximo) {
      nuevoValor = maximo;
    }

    // Evitar decimales raros en pasos enteros
    if (paso % 1 === 0) {
      nuevoValor = Math.round(nuevoValor);
    }

    var el = $input[0];
    el.value = nuevoValor;

    // Evento nativo: necesario para listeners addEventListener (ej. arqueo.js)
    // bubbles: true también dispara handlers jQuery delegados (ventas/compras)
    try {
      el.dispatchEvent(new Event("input", { bubbles: true }));
      el.dispatchEvent(new Event("change", { bubbles: true }));
    } catch (err) {
      $input.trigger("input").trigger("change");
    }

    $input.focus();
  });
})(jQuery);
