/*=============================================
RANGO DE FECHAS DE REPORTES DE VENTAS
=============================================*/

if ($.fn.daterangepicker && $("#daterange-btn2").length) {

	var $btnRango = $("#daterange-btn2");
	var inicioRango = moment($btnRango.data("inicio"), "YYYY-MM-DD");
	var finRango = moment($btnRango.data("fin"), "YYYY-MM-DD");

	$btnRango.daterangepicker(
		{
			ranges: {
				'Hoy': [moment(), moment()],
				'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
				'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
				'Este mes': [moment().startOf('month'), moment().endOf('month')],
				'Último mes': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
			},
			startDate: inicioRango,
			endDate: finRango
		},
		function (start, end) {
			var fechaInicial = start.format('YYYY-MM-DD');
			var fechaFinal = end.format('YYYY-MM-DD');
			window.location = "index.php?ruta=reportes&fechaInicial=" + fechaInicial + "&fechaFinal=" + fechaFinal;
		}
	);

	var pickerReportes = $btnRango.data("daterangepicker");
	if (pickerReportes) {
		pickerReportes.container.on("click", ".cancelBtn", function () {
			window.location = "reportes";
		});
	}

}
