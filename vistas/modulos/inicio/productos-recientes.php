<?php

$item = null;
$valor = null;
$orden = "id";

$productos = ControladorProductos::ctrMostrarProductos($item, $valor, $orden);
if (!is_array($productos)) {
  $productos = [];
}
$limite = min(10, count($productos));

 ?>


<div class="box box-primary text-uppercase box-productos-recientes">

  <div class="box-header with-border">

    <h3 class="box-title">Productos agregados recientemente</h3>


  </div>
  
  <div class="box-body">

    <ul class="products-list product-list-in-box">

    <?php

    for($i = 0; $i < $limite; $i++){

      echo '<li class="item">

        <div class="product-img">

          <img src="'.htmlspecialchars($productos[$i]["imagen"]).'" alt="Product Image">

        </div>

        <div class="product-info">

          <a href="" class="product-title">

            '.htmlspecialchars($productos[$i]["descripcion"]).'

            <span class="label label-success pull-right">Bs'.htmlspecialchars($productos[$i]["precio_venta"]).'</span>

          </a>
    
       </div>

      </li>';

    }

    ?>

    </ul>

  </div>

  <div class="box-footer text-center">

    <a href="productos" class="uppercase">Ver todos los productos</a>
  
  </div>

</div>

<style>
  .box-productos-recientes > .box-header {
    position: relative;
    padding-right: 70px;
  }
  .box-productos-recientes > .box-header > .box-title {
    display: block;
    max-width: 100%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin: 0;
    line-height: 1.4;
  }
  .box-productos-recientes > .box-header > .box-tools {
    position: absolute !important;
    right: 10px !important;
    top: 8px !important;
    float: none !important;
    margin: 0 !important;
  }
</style>
