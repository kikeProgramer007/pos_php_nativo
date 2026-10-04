<?php

$ventasUsuarios = ControladorVentas::ctrReporteVentasPorUsuario($fechaInicial, $fechaFinal);
if (!is_array($ventasUsuarios)) {
	$ventasUsuarios = [];
}

$serieUsuarios = array();
foreach ($ventasUsuarios as $fila) {
	$serieUsuarios[] = array(
		"y" => (string) ($fila["nombre"] ?? "Sin usuario"),
		"a" => floatval($fila["total"] ?? 0)
	);
}

if (count($serieUsuarios) === 0) {
	$serieUsuarios[] = array("y" => "Sin ventas", "a" => 0);
}

?>


<!--=====================================
VENDEDORES
======================================-->

<div class="box box-success">
	
	<div class="box-header with-border">
    
    	<h3 class="box-title">Usuarios</h3>
  
  	</div>

  	<div class="box-body">
  		
		<div class="chart-responsive">
			
			<div class="chart" id="bar-chart1" style="height: 300px;"></div>

		</div>

  	</div>

</div>

<script>
	
//BAR CHART
var bar = new Morris.Bar({
  element: 'bar-chart1',
  resize: true,
  data: <?php echo json_encode($serieUsuarios, JSON_UNESCAPED_UNICODE); ?>,
  barColors: ['#006400'],
  xkey: 'y',
  ykeys: ['a'],
  labels: ['ventas'],
  preUnits: 'Bs',
  yLabelFormat: function (y) { return (Number(y) || 0).toFixed(2); },
  hideHover: 'auto'
});


</script>
