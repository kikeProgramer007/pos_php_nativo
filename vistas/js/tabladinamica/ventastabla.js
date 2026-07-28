
if ($.fn.DataTable.isDataTable('.tablaVentasRealizadas')) {
    $('.tablaVentasRealizadas').DataTable().destroy();
}
var perfilOculto = $("#perfilOculto").val();

if ($.fn.DataTable) { 
$('.tablaVentasRealizadas').DataTable( {
    "ajax": {
        "url": "ajax/datatable-ventas-realizadas.ajax.php",
        "data": function(d) {
            d.perfilOculto = perfilOculto;
            d.estadoPago = $("#filtroEstadoPago").length ? $("#filtroEstadoPago").val() : "todos";
            d.idMesero = $("#filtroMesero").length ? $("#filtroMesero").val() : "0";
        }
    },
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
