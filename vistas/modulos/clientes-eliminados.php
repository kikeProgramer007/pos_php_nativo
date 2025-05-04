
<?php

if($_SESSION["perfil"] == "Especial" || $_SESSION["perfil"] == "Vendedor"){

  echo '<script>

    window.location = "inicio";

  </script>';

  return;

}

?>



<div class="content-wrapper  text-uppercase ">

  <section class="content-header">
    
    <h1 style="font-family: Arial, sans-serif; font-weight: bold;">
      Administrar Clientes Eliminados
    </h1>

    <ol class="breadcrumb">
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active">Administrar Clientes Eliminados</li>
    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">
      <!--   <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarCliente">
          Agregar Meseros
        </button> -->
    <!--     <a href="agregar-cliente" class="btn btn-primary">
       Agregar Cliente
       </a>
      &nbsp; -->

                <a class="btn btn-primary" href="clientes">
            <i class="fa fa-arrow-left"></i>
            <span>Volver</span>
            </a>


      </div>

      <div class="box-body">
        <table class="table table-bordered table-striped dt-responsive text-uppercase tablasclienteEliminados " width="100%">
          <thead>
            <tr>
              <th style="width:10px">#</th>
              <th>Nombre</th>
              <th>Fecha</th>
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
  $eliminarCliente = new ControladorClientes();
  $eliminarCliente -> ctrEliminarCliente();
?>
