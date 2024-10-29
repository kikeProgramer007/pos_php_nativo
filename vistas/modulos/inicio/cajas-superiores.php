<?php

$item = null;
$valor = null;
$orden = "id";


$ventas = ControladorVentas::ctrSumaTotalVentas();

$categorias = ControladorCategorias::ctrMostrarCategorias($item, $valor);
$totalCategorias = count($categorias);

$clientes = ControladorClientes::ctrMostrarClientes($item, $valor);
$totalClientes = count($clientes);

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

  <div class="small-box bg-green">

    <div class="inner">

      <h3><?php echo number_format($ventasTotaldDiaActual["total"], 2) . 'BS'; ?></h3>


      <p>Monto del Día</p>

    </div>

    <div class="icon">
      <img src="data:image/png;base64,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">


    </div>
    <a href="#" role="button" class="small-box-footer">
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


<div class="col-lg-3 col-xs-6 text-uppercase ">

  <div class="small-box bg-cafe">

    <div class="inner">

      <h3><?php echo number_format($ventasTotalMesActual["total"], 2) . 'BS';; ?></h3>

      <p>Total Venta del Mes</p>

    </div>

    <div class="icon">

      <img src="data:image/png;base64,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">

    </div>

    <?php
    date_default_timezone_set('America/La_Paz');
    ?>

    <a href="#" role="button" class="small-box-footer">
      <?php echo date('F-Y'); ?>
    </a>

  </div>




</div>

<div class="col-lg-3 col-xs-6 text-uppercase ">

  <div class="small-box bg-blue">

    <div class="inner">

      <h3><?php echo number_format($ventas["total"], 2) . 'BS';; ?></h3>

      <p> TOTAL DE VENTAS POR AÑO</p>

    </div>

    <div class="icon">

      <img src="data:image/png;base64,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">

    </div>

    <?php
    date_default_timezone_set('America/La_Paz');
    ?>

    <a href="ventas" role="button" class="small-box-footer">

      <?php echo date('Y'); ?>
    </a>
  </div>

</div>


<div class="col-lg-3 col-xs-5 text-uppercase ">

  <div class="small-box bg-red">

    <div class="inner">

      <h3><?php echo number_format($totalProductos); ?></h3>

      <p>Productos</p>

    </div>

    <div class="icon">

      <img src="data:image/png;base64,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">

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

      <img src="data:image/png;base64,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">

    </div>

    <a href="categorias" class="small-box-footer">

      Más info <i class="fa fa-arrow-circle-right"></i>

    </a>

  </div>

</div>

<div class="col-lg-3 col-xs-6 text-uppercase">

  <div class="small-box bg-black">

    <div class="inner">

      <h3><?php echo number_format($totalClientes); ?></h3>

      <p>Meseros</p>

    </div>

    <div class="icon">

      <img src="data:image/png;base64,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">

    </div>

    <a href="clientes" class="small-box-footer">

      Más info <i class="fa fa-arrow-circle-right"></i>

    </a>

  </div>

</div>


<div class="col-lg-3 col-xs-6 text-uppercase">

  <div class="small-box bg-blue">

    <div class="inner">

      <h3><?php echo number_format($totalusuarios); ?></h3>

      <p>USUARIOS</p>

    </div>

    <div class="icon">

    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAACXBIWXMAAAsTAAALEwEAmpwYAAADyUlEQVR4nO2dW4hVZRiGHw9ppWhggYiHQqILS9AkVMQgLywHKuqiqDu96kSXhVdeCA4JooSnBoTCGhirO00wIiioiw4oSlBGlJqGqVimaTlf/PgNyG7WoDNrr//b878PPDDMDLPX/7177bXWfxoQQgghhBBCCCGEGC7jgCXAWuAD4AhwFrjipq8P+8/S7ywGxqrc9TML6AaOA3aTHgM2ADMVzMi5C9gJXB5GEK2mv7EduFPBDI/ngDM1BNHq78CzCuXGuQXoaUMQre7w1xJDcDuwr4EwBtzrrykqzox9DYYx4AFgghL5Pz0ZwhgwXezFdTyfMQxzn1Ei15gGnA4QyBndEl9jZ4AwzN1a+lkys6aHPqvJdCyzKZjuACFYi6mbpUjGej+TBfO4d2IWx5IAxbcKH6JA1gYovFX4OgXyYYDCW4V7KJDDAQpvFR6iQNrRtW41mR5UiyPS84e1+DcFYsEtjtwFNwWiQELzb4CzwCpMx1Yc5wMU3io8R4H8FKDwVuGPFMiBAIW3CvdTIJsCFN4q3EiBPB6g8FZhFwUyBbgYoPjW4l/AZAqlN0AA1uK7FMzyAAFYi8sonM8ChGDup7mLEYFFwNUAYVwtdeh2MN4MEMjm3EWIxK3ANxnDOAjclrsI0bgH+DVDGCeAObkbH5X5wG8NhnEKeCB3o6NzL3C0gTC+B+bmbmyncAfwfhvD6POeAnGTPA38UmMQPwNPKYWR34G9CPwwwo+nF4CJCqM+xgBLvWv8a+CfIQJIuzp8Bbzhc4hFQ4tE7wNWAE+4j/j3tNxZCCGEEEIIIYRogruBVcBLwDpgm+8i1Of2+PYY67z/6zENPtXXb3U/8IoX+UvgjxF0LqaZ9l8AbwEvA/NqOs5R/+5fA7znI3ntHqA6CewGVpe+v8n1zABeBT4H+hsIYSjTHsCv+TEVxWQ/Ez4JMh/LBlk99bGfOZMYxUz3i23k9ek2yLVni2/kPKpmJvb6oJF1qFf8erOADia9q94JcG2wGu332+s0h6yjrhHpo+lSgAJam7zsH2VTCU5XwxPdLLPp1vlRgs4M2TLKPp7sBu33jTzDzA2e69sZWeF+G+HaMt9P29zFsCCeyNktM9sPIHcRLOAmmlmeW/YHaLwF9aOmw3g4QKMtuI0uHl0foMEW3PQs1hhvB2iwBXdXk4FE3qfEStwvpStAgy24K5sMJM0u/y5Aoy2oabBrPA2TFtlfCNB4C+afwINkHOtoYoGmdYhppdZCMjPO1+11+0BUX2H2etufLPXfXAghhBBCCCGEEEIIIcjPf+JHqeavtrRiAAAAAElFTkSuQmCC">

    </div>

    <a href="usuarios" class="small-box-footer">

      Más info <i class="fa fa-arrow-circle-right"></i>

    </a>

  </div>

</div>

<div class="col-lg-3 col-xs-6 text-uppercase">

<div class="small-box" style="background-color: #d2b48c;">

    <div class="inner">

      <h3><?php echo number_format($totalproveedor); ?></h3>

      <p>PROVEEDOR</p>

    </div>

    <div class="icon">

   <img src="data:image/png;base64,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">

    </div>

    <a href="proveedor" class="small-box-footer">

      Más info <i class="fa fa-arrow-circle-right"></i>

    </a>

  </div>

</div>