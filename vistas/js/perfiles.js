/*=============================================
TABLA PERFILES
=============================================*/
if ($.fn.DataTable && $(".tablasPerfiles").length) {
  $(".tablasPerfiles").DataTable({
    ajax: "ajax/tabladinamica/datatable-perfiles.ajax.php",
    deferRender: true,
    retrieve: true,
    processing: true,
    language: {
      sProcessing: "Procesando...",
      sLengthMenu: "Mostrar _MENU_ registros",
      sZeroRecords: "No se encontraron resultados",
      sEmptyTable: "Ningún dato disponible en esta tabla",
      sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
      sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0",
      sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
      sSearch: "Buscar:",
      oPaginate: { sFirst: "Primero", sLast: "Último", sNext: "Siguiente", sPrevious: "Anterior" }
    }
  });
}

if ($.fn.DataTable && $(".tablasPerfilesEliminados").length) {
  $(".tablasPerfilesEliminados").DataTable({
    ajax: "ajax/tabladinamica/datatable-perfiles.ajax.php?eliminados=1",
    deferRender: true,
    retrieve: true,
    processing: true,
    language: {
      sProcessing: "Procesando...",
      sLengthMenu: "Mostrar _MENU_ registros",
      sZeroRecords: "No se encontraron resultados",
      sEmptyTable: "Ningún dato disponible en esta tabla",
      sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
      sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0",
      sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
      sSearch: "Buscar:",
      oPaginate: { sFirst: "Primero", sLast: "Último", sNext: "Siguiente", sPrevious: "Anterior" }
    }
  });
}

$(document).on("click", ".btnEliminarPerfil", function () {
  var idPerfil = $(this).attr("idPerfil");
  swal({
    title: "¿Está seguro de borrar el perfil?",
    text: "¡Si no lo está, puede cancelar la acción!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    cancelButtonText: "Cancelar",
    confirmButtonText: "Sí, borrar perfil"
  }).then(function (result) {
    if (result.value) {
      window.location = "index.php?ruta=perfiles&idPerfilEliminar=" + idPerfil;
    }
  });
});

$(document).on("click", ".btnRestaurarPerfil", function () {
  var idPerfil = $(this).attr("idPerfil");
  swal({
    title: "¿Restaurar este perfil?",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    cancelButtonText: "Cancelar",
    confirmButtonText: "Sí, restaurar"
  }).then(function (result) {
    if (result.value) {
      window.location = "index.php?ruta=perfiles-eliminados&idPerfilRestaurar=" + idPerfil;
    }
  });
});

function setCheckState($input, checked, indeterminate) {
  if ($.fn.iCheck && $input.data("iCheck")) {
    if (indeterminate) {
      $input.iCheck("indeterminate");
    } else if (checked) {
      $input.iCheck("check");
    } else {
      $input.iCheck("uncheck");
      $input.iCheck("determinate");
    }
    return;
  }
  $input.prop("indeterminate", !!indeterminate);
  $input.prop("checked", !!checked && !indeterminate);
}

function actualizarChecksModulo() {
  if (window._syncPermisos) {
    return;
  }
  window._syncPermisos = true;

  $(".check-modulo").each(function () {
    var modulo = $(this).data("modulo");
    var $items = $('.check-permiso[data-modulo="' + modulo + '"]');
    var total = $items.length;
    var marcados = $items.filter(":checked").length;
    var $modulo = $(this);
    var all = total > 0 && marcados === total;
    var none = marcados === 0;

    setCheckState($modulo, all, !all && !none);

    var $badge = $('.permiso-modulo-count[data-modulo="' + modulo + '"]');
    if ($badge.length) {
      $badge
        .text(marcados + "/" + total)
        .removeClass("label-default label-warning label-success")
        .addClass(all ? "label-success" : (none ? "label-default" : "label-warning"));
    }

    var $box = $modulo.closest(".box");
    $box.removeClass("box-success box-warning box-default");
    $box.addClass(all ? "box-success" : (none ? "box-default" : "box-warning"));
  });

  var $todos = $(".check-permiso");
  var t = $todos.length;
  var m = $todos.filter(":checked").length;
  var $master = $("#seleccionarTodoPermisos");
  if ($master.length) {
    setCheckState($master, t > 0 && m === t, m > 0 && m < t);
  }

  $("#permisosMarcados").text(m);
  window._syncPermisos = false;
}

function filtrarPermisos(termino) {
  var q = (termino || "").toLowerCase().trim();
  $(".permisos-modulo").each(function () {
    var $card = $(this);
    var modulo = ($card.data("modulo") || "").toString().toLowerCase();
    var visibleRows = 0;

    $card.find(".permiso-item").each(function () {
      var texto = $(this).data("text") || "";
      var match = !q || modulo.indexOf(q) !== -1 || texto.indexOf(q) !== -1;
      $(this).toggleClass("hidden", !match);
      if (match) visibleRows++;
    });

    var showCard = !q || modulo.indexOf(q) !== -1 || visibleRows > 0;
    $card.toggleClass("hidden", !showCard);
  });
}

$(document).on("input", "#buscarPermisos", function () {
  filtrarPermisos(this.value);
});

$(document).on("ifChanged change", "#seleccionarTodoPermisos", function () {
  if (window._syncPermisos) {
    return;
  }
  var checked = $(this).is(":checked");
  if ($.fn.iCheck) {
    $(".check-permiso, .check-modulo").iCheck(checked ? "check" : "uncheck");
  } else {
    $(".check-permiso, .check-modulo").prop("checked", checked).prop("indeterminate", false);
  }
  actualizarChecksModulo();
});

$(document).on("ifChanged change", ".check-modulo", function () {
  if (window._syncPermisos) {
    return;
  }
  var modulo = $(this).data("modulo");
  var checked = $(this).is(":checked");
  if ($.fn.iCheck) {
    $('.check-permiso[data-modulo="' + modulo + '"]').iCheck(checked ? "check" : "uncheck");
  } else {
    $('.check-permiso[data-modulo="' + modulo + '"]').prop("checked", checked);
  }
  actualizarChecksModulo();
});

$(document).on("ifChanged change", ".check-permiso", function () {
  if (window._syncPermisos) {
    return;
  }
  actualizarChecksModulo();
});

$(document).ready(function () {
  if ($.fn.iCheck) {
    $("input.minimal, input.minimal-red, input.minimal-orange").iCheck({
      checkboxClass: "icheckbox_square-orange",
      radioClass: "iradio_square-orange",
      increaseArea: "20%"
    });
  }
  if ($(".check-permiso").length) {
    actualizarChecksModulo();
  }
});
