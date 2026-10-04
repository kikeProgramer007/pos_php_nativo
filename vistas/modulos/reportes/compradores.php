<?php

$ventasMeseros = ControladorVentas::ctrReporteVentasPorMesero($fechaInicial, $fechaFinal);
if (!is_array($ventasMeseros)) {
	$ventasMeseros = [];
}

$serieMeseros = array();
foreach ($ventasMeseros as $fila) {
	$serieMeseros[] = array(
		"y" => (string) ($fila["nombre"] ?? "Sin mesero"),
		"a" => floatval($fila["total"] ?? 0)
	);
}

if (count($serieMeseros) === 0) {
	$serieMeseros[] = array("y" => "Sin ventas", "a" => 0);
}

?>

<!--=====================================
VENDEDORES
======================================-->

<div class="box box-gray">
	
	<div class="box-header with-border">
    
    	<h3 class="box-title">Meseros</h3>
  
  	</div>

  	<div class="box-body">
  		
		<div class="chart-responsive">
			
			<div class="chart" id="bar-chart2" style="height: 300px;"></div>

		</div>

  	</div>

</div>

<script>
	
//BAR CHART
var bar = new Morris.Bar({
  element: 'bar-chart2',
  resize: true,
  data: <?php echo json_encode($serieMeseros, JSON_UNESCAPED_UNICODE); ?>,
  barColors: ['#808080'],
  xkey: 'y',
  ykeys: ['a'],
  labels: ['ventas'],
  preUnits: 'Bs',
  yLabelFormat: function (y) { return (Number(y) || 0).toFixed(2); },
  hideHover: 'auto'
});


</script>
