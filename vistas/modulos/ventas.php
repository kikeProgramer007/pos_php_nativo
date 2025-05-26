<?php

if ($_SESSION["perfil"] == "") {

  echo '<script>

    window.location = "inicio";

  </script>';

  return;
}

?>


<div class="content-wrapper  text-uppercase ">

  <section class="content-header">
    <h1 style="font-family: Arial, sans-serif; font-weight: bold;">

      Administrar ventas


    </h1>

    <ol class="breadcrumb">

      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio </a></li>

      <li class="active">Administrar ventas</li>

    </ol>

  </section>

  <section class="content">
    <input type="hidden" value="<?php echo $_SESSION['perfil']; ?>" id="perfilOculto">
    <div class="box">

      <div class="box-header with-border">

        <a href="crear-venta">
          <button class="btn btn-primary">
            <i class="fa fa-plus"></i>
            Agregar venta
          </button>
        </a>
        &nbsp;  
        <a class="btn btn-danger" href="ventas-eliminadas">
          <i class="fa fa-trash"></i>
          <span>VENTAS ELIMINADAS </span>
        </a>

        <button type="button" class="btn btn-default pull-right" id="daterange-btn">

          <span>
            <i class="fa fa-calendar"></i> Rango de fecha
          </span>

          <i class="fa fa-caret-down"></i>

        </button>

      </div>

      <div class="box-body">

        <table class="table table-bordered table-striped dt-responsive tablas text-uppercase tablaVentasRealizadas " width="100%">

          <thead>

            <tr>

              <th style="width:10px">#</th>
              <th>N° TICKET</th>
              <th>Meseros</th>
              <th>Clientes</th>
              <th>Tipo de Pago</th>
              <th>Usuario</th>
              <th>Total</th>
              <th>Fecha</th>
              <th>Acciones</th>


            </tr>

          </thead>

          <tbody>

            <?php

        
 
            ?>

          </tbody>

        </table>

        <?php

        $eliminarVenta = new ControladorVentas();
        $eliminarVenta->ctrEliminarVenta();

        ?>

      </div>

    </div>

  </section>

</div>