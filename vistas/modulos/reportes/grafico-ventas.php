<?php

require_once __DIR__ . "/etiqueta-periodo.php";

$porDia = ControladorVentas::ctrReporteVentasPorDia($fechaInicial, $fechaFinal);
$mapaDias = array();

foreach ($porDia as $fila) {
	$mapaDias[$fila["dia"]] = floatval($fila["ventas"]);
}

$serieVentas = array();
$cursor = new DateTime($fechaInicial);
$limite = new DateTime($fechaFinal);

while ($cursor <= $limite) {
	$dia = $cursor->format('Y-m-d');
	$serieVentas[] = array(
		"y" => $dia,
		"ventas" => isset($mapaDias[$dia]) ? $mapaDias[$dia] : 0
	);
	$cursor->modify('+1 day');
}

$periodoGrafico = etiquetaPeriodoGrafico($fechaInicial, $fechaFinal);

?>

<!--=====================================
GRÁFICO DE VENTAS
======================================-->


<div class="box box-solid bg-teal-gradient">
	
	<div class="box-header">
		
 		<i class="fa fa-th"></i>

  		<h3 class="box-title">Ventas cobradas por día<?php if ($periodoGrafico !== '') { echo ' <small style="color:#fff;">' . htmlspecialchars($periodoGrafico) . '</small>'; } ?></h3>

	</div>

	<div class="box-body border-radius-none nuevoGraficoVentas">

		<div class="chart" id="line-chart-ventas" style="height: 250px;"></div>

  </div>

</div>

<script>
	
 var line = new Morris.Line({
    element          : 'line-chart-ventas',
    resize           : true,
    data             : <?php echo json_encode($serieVentas); ?>,
    xkey             : 'y',
    ykeys            : ['ventas'],
    labels           : ['ventas'],
    lineColors       : ['#efefef'],
    lineWidth        : 2,
    hideHover        : 'auto',
    gridTextColor    : '#fff',
    gridStrokeWidth  : 0.4,
    pointSize        : 4,
    pointStrokeColors: ['#efefef'],
    gridLineColor    : '#efefef',
    gridTextFamily   : 'Open Sans',
    preUnits         : 'Bs',
    yLabelFormat     : function (y) { return (Number(y) || 0).toFixed(2); },
    gridTextSize     : 10
  });

</script>
