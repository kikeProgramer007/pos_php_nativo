<?php
if (!Permisos::tiene("reportes.ventas")) {
  echo '<script>window.location = "no-autorizado";</script>';
  return;
}
?>
<?php
date_default_timezone_set('America/La_Paz');

$fechaInicialReporte = isset($_GET["fechaInicial"]) ? $_GET["fechaInicial"] : "";
$fechaFinalReporte = isset($_GET["fechaFinal"]) ? $_GET["fechaFinal"] : "";
$fechasValidas = preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaInicialReporte)
	&& preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaFinalReporte)
	&& $fechaInicialReporte <= $fechaFinalReporte;

if (!$fechasValidas) {
	$fechaInicialReporte = date('Y-m-01');
	$fechaFinalReporte = date('Y-m-t');
}

$esMesActual = ($fechaInicialReporte === date('Y-m-01') && $fechaFinalReporte === date('Y-m-t'));
$textoRangoReporte = $esMesActual
	? 'Este mes'
	: date('d/m/Y', strtotime($fechaInicialReporte)) . ' - ' . date('d/m/Y', strtotime($fechaFinalReporte));
?>
<div class="content-wrapper text-uppercase">

  <section class="content-header">
    
    <h1>
      
      Reportes de ventas
    
    </h1>

    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Reportes de ventas</li>
    
    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">

        <div class="input-group">

          <button type="button" class="btn btn-default" id="daterange-btn2" data-inicio="<?php echo $fechaInicialReporte; ?>" data-fin="<?php echo $fechaFinalReporte; ?>">
           
            <span>
              <i class="fa fa-calendar"></i> <?php echo $textoRangoReporte; ?>
            </span>

            <i class="fa fa-caret-down"></i>

          </button>

        </div>

        <div class="box-tools pull-right">

        <?php

          echo '<a href="vistas/modulos/descargar-reporte.php?reporte=reporte&fechaInicial='.$fechaInicialReporte.'&fechaFinal='.$fechaFinalReporte.'">';

        ?>
           
           <button class="btn btn-success" style="margin-top:5px">Descargar reporte en Excel</button>

          </a>

        </div>
         
      </div>

      <div class="box-body">
        
        <div class="row">

          <div class="col-xs-12">
            
            <?php

            $fechaInicial = $fechaInicialReporte;
            $fechaFinal = $fechaFinalReporte;

            include "reportes/grafico-ventas.php";

            ?>

          </div>

           <div class="col-md-6 col-xs-12">
             
            <?php

            include "reportes/productos-mas-vendidos.php";

            ?>

           </div>

            <div class="col-md-6 col-xs-12 ">
             
            <?php

            include "reportes/vendedores.php";

            ?>

           </div>

           <div class="col-md-6 col-xs-12">
             
            <?php

            include "reportes/compradores.php";

            ?>

           </div>
          
        </div>

      </div>
      
    </div>

  </section>
 
 </div>
