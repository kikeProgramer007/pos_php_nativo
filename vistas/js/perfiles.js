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

function actualizarChecksModulo() {
  $(".check-modulo").each(function () {
    var modulo = $(this).data("modulo");
    var $items = $('.check-permiso[data-modulo="' + modulo + '"]');
    var total = $items.length;
    var marcados = $items.filter(":checked").length;
    this.indeterminate = marcados > 0 && marcados < total;
    this.checked = total > 0 && marcados === total;
  });

  var $todos = $(".check-permiso");
  var t = $todos.length;
  var m = $todos.filter(":checked").length;
  var master = document.getElementById("seleccionarTodoPermisos");
  if (master) {
    master.indeterminate = m > 0 && m < t;
    master.checked = t > 0 && m === t;
  }
}

$(document).on("change", "#seleccionarTodoPermisos", function () {
  $(".check-permiso, .check-modulo").prop("checked", this.checked).prop("indeterminate", false);
});

$(document).on("change", ".check-modulo", function () {
  var modulo = $(this).data("modulo");
  $('.check-permiso[data-modulo="' + modulo + '"]').prop("checked", this.checked);
  actualizarChecksModulo();
});

$(document).on("change", ".check-permiso", function () {
  actualizarChecksModulo();
});

$(document).ready(function () {
  if ($(".check-permiso").length) {
    actualizarChecksModulo();
  }
});
