
if ($.fn.DataTable.isDataTable('.tablaVentasRealizadas')) {
    $('.tablaVentasRealizadas').DataTable().destroy();
}
var perfilOculto = $("#perfilOculto").val();

// Filtros desde URL (solo listar; NUNCA usar idVenta en URL → anula la venta)
var codigoTicketUrl = "";
(function aplicarFiltrosVentasDesdeUrl() {
	var qs = window.location.search || "";
	var estadoPago = (qs.match(/[?&]estadoPago=([^&]*)/) || [])[1];
	var idMesa = (qs.match(/[?&]idMesa=([^&]*)/) || [])[1];
	var codigoTicket = (qs.match(/[?&]codigoTicket=([^&]*)/) || [])[1];
	if (estadoPago && $("#filtroEstadoPago").length) {
		$("#filtroEstadoPago").val(decodeURIComponent(estadoPago));
	}
	if (idMesa && $("#filtroMesa").length) {
		$("#filtroMesa").val(decodeURIComponent(idMesa));
	}
	if (codigoTicket) {
		codigoTicketUrl = decodeURIComponent(codigoTicket.replace(/\+/g, " "));
	}
})();

if ($.fn.DataTable) { 
$('.tablaVentasRealizadas').DataTable( {
    "ajax": {
        "url": "ajax/datatable-ventas-realizadas.ajax.php",
        "data": function(d) {
            d.perfilOculto = perfilOculto;
            d.estadoPago = $("#filtroEstadoPago").length ? $("#filtroEstadoPago").val() : "todos";
            d.idMesero = $("#filtroMesero").length ? $("#filtroMesero").val() : "0";
            d.idMesa = $("#filtroMesa").length ? ($("#filtroMesa").val() || "0") : "0";
        }
    },
    "deferRender": true,
    "retrieve": true,
    "processing": true,
    "initComplete": function () {
        if (codigoTicketUrl) {
            this.api().search(codigoTicketUrl).draw();
        }
    },
	 "language": {

			"sProcessing":     "Procesando...",
			"sLengthMenu":     "Mostrar _MENU_ registros",
			"sZeroRecords":    "No se encontraron resultados",
			"sEmptyTable":     "Ningún dato disponible en esta tabla",
			"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
			"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0",
			"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
			"sInfoPostFix":    "",
			"sSearch":         "Buscar:",
			"sUrl":            "",
			"sInfoThousands":  ",",
			"sLoadingRecords": "Cargando...",
			"oPaginate": {
			"sFirst":    "Primero",
			"sLast":     "Último",
			"sNext":     "Siguiente",
			"sPrevious": "Anterior"
			},
			"oAria": {
				"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
				"sSortDescending": ": Activar para ordenar la columna de manera descendente"
			}

	}

} );
};


if ($.fn.DataTable.isDataTable('.tablaVentasEliminadas')) {
    $('.tablaVentasEliminadas').DataTable().destroy();
}

 $('.tablaVentasEliminadas').DataTable( {
    "ajax": "ajax/tabladinamica/datatable-ventas-realizadas-eliminadas.ajax.php?perfilOculto="+perfilOculto,
    "deferRender": true,
    "retrieve": true,
    "processing": true,
	 "language": {

			"sProcessing":     "Procesando...",
			"sLengthMenu":     "Mostrar _MENU_ registros",
			"sZeroRecords":    "No se encontraron resultados",
			"sEmptyTable":     "Ningún dato disponible en esta tabla",
			"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
			"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0",
			"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
			"sInfoPostFix":    "",
			"sSearch":         "Buscar:",
			"sUrl":            "",
			"sInfoThousands":  ",",
			"sLoadingRecords": "Cargando...",
			"oPaginate": {
			"sFirst":    "Primero",
			"sLast":     "Último",
			"sNext":     "Siguiente",
			"sPrevious": "Anterior"
			},
			"oAria": {
				"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
				"sSortDescending": ": Activar para ordenar la columna de manera descendente"
			}

	}

} );
