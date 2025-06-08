<?php

$item = null;
$valor = null;
$orden = "id";





$categorias = ControladorCategorias::ctrMostrarCategorias($item, $valor);
$totalCategorias = count($categorias);

$meseros = ControladorMeseros::ctrMostrarMeseros($item, $valor);
$totalMeseros = count($meseros);

$cliente =ControladorClientes::ctrMostrarClientes($item, $valor);
$totalcliente = count($cliente);

$usuario = ControladorUsuarios::ctrMostrarUsuarios($item, $valor);
$totalusuarios=count($usuario);


$proveedor = ControladorProveedors::ctrMostrarProveedors($item, $valor);
$totalproveedor=count($proveedor);


$productos = ControladorProductos::ctrMostrarProductos($item, $valor, $orden);
$totalProductos = count($productos);

$ventas = ControladorVentas::ctrSumaTotalVentas();
$ventasTotalMesActual = ControladorVentas::ctrVentasTotalMes();
$ventasTotaldDiaActual = ControladorVentas::ctrVentasTotalDia();
?>



<div class="col-lg-3 col-xs-6 text-uppercase ">

  <div class="small-box bg-green monto-dia-box">

    <div class="inner">

      <h3><?php echo number_format($ventasTotaldDiaActual["total"], 2) . 'BS'; ?></h3>


      <p>Monto del Día</p>

    </div>

    <div class="icon">
      
     <img src="./vistas/img/plantilla/bs.webp" alt="">

    </div>
    <a href="reporte-venta" role="button" class="small-box-footer">
      <?php echo date('d-m-Y'); ?>
    </a>

  </div>

</div>

<style>
  .bg-cafe {
    background-color: #6f4e28;
    /* Color café */
    color: #fff;
    /* Texto en blanco para contraste */
  }
</style>

<style>
.monto-dia-box .icon img {
  max-width: 200px;
  max-height: 110px;
  object-fit: contain;
  position: absolute;
  right: 20px;
  top: 0%;
  transform: translateY(-80%);
  opacity: 0.85;
}
.monto-dia-box .icon {
  position: relative;
  height: 0px;
}
.monto-dia2-box .icon img{
  max-width: 300px;
  max-height: 150px;
  object-fit: contain;
  position: absolute;
  right: 20px;
  top: 0%;
  transform: translateY(-70%);
  opacity: 0.85;
}
.monto-dia2-box .icon {
  position: relative;
  height: 0px;
}

</style>


<div class="col-lg-3 col-xs-6 text-uppercase ">

<div class="small-box bg-cafe monto-dia-box">

    <div class="inner">

      <h3><?php echo number_format($ventasTotalMesActual["total"], 2) . 'BS';; ?></h3>

      <p>Total Venta del Mes</p>

    </div>

    <div class="icon">

    <img src="./vistas/img/plantilla/bsmes.webp" alt="">

    </div>

    <?php
    date_default_timezone_set('America/La_Paz');
    ?>

    <a href="ganancias-ventas" role="button" class="small-box-footer">
      <?php echo date('F-Y'); ?>
    </a>

  </div>




</div>

<div class="col-lg-3 col-xs-6 text-uppercase ">

<div class="small-box bg-blue monto-dia-box">

    <div class="inner">

      <h3><?php echo number_format($ventas["total"], 2) . 'BS';; ?></h3>

      <p> TOTAL DE VENTAS POR AÑO</p>

    </div>

    <div class="icon">

    <img src="./vistas/img/plantilla/bsyear.webp" alt="">

    </div>

    <?php
    date_default_timezone_set('America/La_Paz');
    ?>

    <a href="ganancias-ventas" role="button" class="small-box-footer">

      <?php echo date('Y'); ?>
    </a>
  </div>

</div>


<div class="col-lg-3 col-xs-5 text-uppercase ">

<div class="small-box bg-red monto-dia-box">

    <div class="inner">

      <h3><?php echo number_format($totalProductos); ?></h3>

      <p>Productos</p>

    </div>

    <div class="icon">

    <img src="./pagina/assets/img/food/22.webp" alt="">

    </div>

    <a href="productos" class="small-box-footer">

      Más info <i class="fa fa-arrow-circle-right"></i>

    </a>

  </div>

</div>




<div class="col-lg-3 col-xs-6 text-uppercase">

  <div class="small-box bg-yellow">

    <div class="inner">

      <h3><?php echo number_format($totalCategorias); ?></h3>

      <p>Categorías</p>

    </div>

    <div class="icon">

      <i class="ion ion-clipboard"></i>

    </div>

    <a href="categorias" class="small-box-footer">

      Más info <i class="fa fa-arrow-circle-right"></i>

    </a>

  </div>

</div>

<div class="col-lg-2 col-xs-6 text-uppercase">

  <div class="small-box bg-black">

    <div class="inner">

      <h3><?php echo number_format($totalMeseros); ?></h3>

      <p>Meseros</p>

    </div>

    <div class="icon">

     <img src="data:image/png;base64,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">

    </div>

    <a href="meseros" class="small-box-footer">

      Más info <i class="fa fa-arrow-circle-right"></i>

    </a>

  </div>

</div>


<div class="col-lg-2 col-xs-6 text-uppercase">

  <div class="small-box bg-blue">

    <div class="inner">

      <h3><?php echo number_format($totalusuarios); ?></h3>

      <p>USUARIOS</p>

    </div>

    <div class="icon">

     
    <i class="fa fa-users"></i>


    </div>

    <a href="usuarios" class="small-box-footer">

      Más info <i class="fa fa-arrow-circle-right"></i>

    </a>

  </div>

</div>




<div class="col-lg-2 col-xs-6 text-uppercase">

  <div class="small-box bg-cafe">

    <div class="inner">

    <h3><?php echo number_format($totalproveedor); ?></h3>

      <p>PROVEEDOR</p>

    </div>

    <div class="icon">

     
    <i class="fa fa-truck"></i>


    </div>

    <a href="proveedor" class="small-box-footer">

      Más info <i class="fa fa-arrow-circle-right"></i>

    </a>

  </div>

</div>




<div class="col-lg-3 col-xs-6 text-uppercase">

<div class="small-box bg-yellow monto-dia2-box">

    <div class="inner">

      <h3><?php echo number_format($totalcliente); ?></h3>

      <p>CLIENTES</p>

    </div>

    <div class="icon">

    <img src="./vistas/img/plantilla/cliente.webp" alt="">

    </div>

    <a href="clientes" class="small-box-footer">

      Más info <i class="fa fa-arrow-circle-right"></i>

    </a>

  </div>

</div>