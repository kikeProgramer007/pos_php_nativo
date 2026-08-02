<?php

$item = null;
$valor = null;
$orden = "ventas";

$productos = ControladorProductos::ctrMostrarProductos($item, $valor, $orden);
if (!is_array($productos)) {
	$productos = [];
}

$colores = array("red","green","yellow","aqua","purple","blue","cyan","magenta","orange","gold");
$coloresHex = array("#f56954","#00a65a","#f39c12","#00c0ef","#605ca8","#3c8dbc","#39cccc","#D81B60","#ff851b","#ffd700");

$totalVentas = ControladorProductos::ctrMostrarSumaVentas();
$totalVentasNum = floatval($totalVentas["total"] ?? 0);

$limiteLeyenda = min(10, count($productos));
$limiteLista = min(5, count($productos));

$pieData = [];
for ($i = 0; $i < $limiteLeyenda; $i++) {
	$pieData[] = [
		"value" => floatval($productos[$i]["ventas"] ?? 0),
		"color" => $coloresHex[$i],
		"highlight" => $coloresHex[$i],
		"label" => $productos[$i]["descripcion"] ?? ("Producto " . ($i + 1))
	];
}

?>

<!--=====================================
PRODUCTOS MÁS VENDIDOS
======================================-->

<div class="box box-danger text-uppercase">
	
	<div class="box-header with-border">
  
      <h3 class="box-title">Productos más vendidos</h3>

    </div>

	<div class="box-body">
    
      	<div class="row">

	        <div class="col-md-7">

	 			<div class="chart-responsive" style="height: 220px; position: relative;">
	            
	            	<canvas id="pieChart" height="220" width="220" style="width:100%; height:220px;"></canvas>
	          
	          	</div>

	        </div>

		    <div class="col-md-5">
		      	
		  	 	<ul class="chart-legend clearfix">

		  	 	<?php

					for($i = 0; $i < $limiteLeyenda; $i++){

					echo '   <li><i class="fa fa-circle-o text-'.$colores[$i].'"></i>   '.htmlspecialchars($productos[$i]["descripcion"]).'</li>';

					}


		  	 	?>


		  	 	</ul>

		    </div>

		</div>

    </div>

    <div class="box-footer no-padding">
    	
		<ul class="nav nav-pills nav-stacked">
			
			 <?php

          	for($i = 0; $i < $limiteLista; $i++){
				$porcentaje = $totalVentasNum > 0
					? ceil(floatval($productos[$i]["ventas"]) * 100 / $totalVentasNum)
					: 0;
			
          		echo '<li>
						 
						 <a>

						 <img src="'.htmlspecialchars($productos[$i]["imagen"]).'" class="img-thumbnail" width="60px" style="margin-right:10px"> 
						 '.htmlspecialchars($productos[$i]["descripcion"]).'

						 <span class="pull-right text-'.$colores[$i].'">   
						 '.$porcentaje.'%
						 </span>
							
						 </a>

      				</li>';

			}

			?>


		</ul>

    </div>

</div>

<script>
(function() {
  function renderPieChartProductos() {
    var canvas = document.getElementById('pieChart');
    if (!canvas || typeof Chart === 'undefined') {
      return;
    }

    var PieData = <?php echo json_encode($pieData, JSON_UNESCAPED_UNICODE); ?>;
    if (!PieData || !PieData.length) {
      return;
    }

    var ctx = canvas.getContext('2d');
    var pieChart = new Chart(ctx);
    pieChart.Doughnut(PieData, {
      segmentShowStroke: true,
      segmentStrokeColor: '#fff',
      segmentStrokeWidth: 2,
      percentageInnerCutout: 50,
      animationSteps: 100,
      animationEasing: 'easeOutBounce',
      animateRotate: true,
      animateScale: false,
      responsive: true,
      maintainAspectRatio: true,
      tooltipTemplate: '<%=value %> <%=label%>'
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', renderPieChartProductos);
  } else {
    renderPieChartProductos();
  }
})();
</script>
