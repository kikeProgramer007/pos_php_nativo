<?php

if($_SESSION["perfil"] == "Supervisor" || $_SESSION["perfil"] == "Vendedor"){

  echo '<script>

    window.location = "inicio";

  </script>';

  return;

}

?>



<div class="content-wrapper  text-uppercase ">

  <section class="content-header">
    
    <h1 style="font-family: Arial, sans-serif; font-weight: bold;">
      Administrar Meseros Eliminados
    </h1>

    <ol class="breadcrumb">
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active">Administrar Meseros Eliminados</li>
    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">
      


            <a class="btn btn-primary" href="meseros">
        <i class="fa fa-arrow-left"></i>
        <span>Volver</span>
      </a>


    
      </div>

      <div class="box-body">
        <table class="table table-bordered table-striped dt-responsive text-uppercase tablasmeseroEliminados " width="100%">
          <thead>
            <tr>
              <th style="width:10px">#</th>
              <th>Nombre</th>
              <th>N° De Carnet</th>
              <th>Telefono</th>
              <th>Dirección</th>
              <th>Total compras</th>
        
              <th>Ingreso al sistema</th>
              <th>Acción</th>
            </tr>
          </thead>
          <input type="hidden" value="<?php echo $_SESSION['perfil']; ?>" id="perfilOculto">
        </table>
      </div>
    </div>

  </section>

</div>




<?php
  $eliminarMesero = new ControladorMeseros();
  $eliminarMesero -> ctrEliminarMesero();
?>
