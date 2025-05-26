<?php

if($_SESSION["perfil"] == "" || $_SESSION["perfil"] == "Vendedor"){

  echo '<script>

    window.location = "inicio";

  </script>';

  return;

}

?>



<div class="content-wrapper  text-uppercase ">

  <section class="content-header">
    
    <h1 style="font-family: Arial, sans-serif; font-weight: bold;">
      Administrar Gastos
    </h1>

    <ol class="breadcrumb">
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active">Administrar Gastos</li>
    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">
      <!--   <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarMesero">
          Agregar Gastos
        </button> -->
       <?php
        if(($_SESSION["perfil"] ==  'Administrador') || ($_SESSION["perfil"] ==  'Supervisor') ) {
            echo '<a href="agregar-gasto" class="btn btn-primary">
                <i class="fa fa-plus"></i>
                Agregar Gastos
            </a>';
        }
        ?>

        &nbsp;

        <a class="btn btn-primary" target="_blank" href="reporte_gastos.php">
        <i class="fa fa-print"></i>
            <i class="material-icons"></i>
            
            <span class="icon-name"> Imprimir </span>
              </a>&nbsp;
      </div>

      <div class="box-body">
        <table class="table table-bordered table-striped dt-responsive text-uppercase tabla-gastos " width="100%">
          <thead>
            <tr>
              <th style="width:10px">#</th>
              <th>FECHA</th>
              <th>TIPO DE GASTO</th>
              <th>DESCRIPCIÓN</th>
              <th>MONTO</th>
              <th>FORMA DE PAGO</th>
              <th>USUARIO</th>
              <th>ACCIONES</th>
            </tr>
          </thead>
          <input type="hidden" value="<?php echo $_SESSION['perfil']; ?>" id="perfilOculto">
        </table>
      </div>
    </div>

  </section>

</div>

<!--=====================================
MODAL EDITAR Mesero
======================================-->
<div id="modalEditarGasto" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <form role="form" method="post">
        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->
        <div class="modal-header" style="background:#6c757d; color:white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Editar Gastos</h4>
        </div>
        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->
        <div class="modal-body">
          <div class="box-body">
    
            <!-- ENTRADA PARA EL TIPO DE GASTO-->
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon">TIPO DE GASTO</span>
                <select class="form-control input-lg" id="editarIdTipoGasto" name="editarIdTipoGasto" required>

                    <?php
                    $item = null;
                    $valor = null;
                    $categorias = ControladorTipoGasto::ctrMostrarTipoGasto($item, $valor);
                    foreach ($categorias as $key => $value) {
                        echo '<option value="' . $value["id"] . '">' . $value["nombre"] . '</option>';
                    }
                    ?>
                </select>
            </div>
            </div>
            <!-- ENTRADA PARA LA DESCRIPCION -->
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon">DESCRIPCIÓN</span>
                <input type="text" class="form-control input-lg" name="editarDescripcion" id="editarDescripcion" placeholder="INGRESE LA DESCRIPCIÓN" required>
                <input type="hidden" id="idGasto" name="idGasto">
              </div>
            </div>

       
            <div class="form-group">
              <div class="input-group">

                <!-- ENTRADA PARA LA FECHA DE GASTO-->
                <span class="input-group-addon">
                FECHA
                </span>
                <div class="input-group date">
                  <input type="date" id="editarFecha" name="editarFecha" class="form-control input-lg" required />
                </div>

                <!-- ENTRADA PARA EL MONTO-->
                <span class="input-group-addon">
                MONTO
                </span>
                <input type="number" step="0.01" min="0" class="form-control input-lg" id="editarMonto" name="editarMonto" placeholder="INGRESAR EL MONTO" required>
           
              </div>
            </div>
        

            <!-- ENTRADA PARA LA DIRECCIÓN -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon">
              TIPO DE PAGO
                </span> 

                <select class="form-control input-lg" id="editarTipoPago" name="editarTipoPago">
                        <option value="1">Efectivo</option>
                        <option value="2">QR</option>
                        <option value="3">Transferencia</option>
                        <option value="4">Qr y Efectivo(Mixto)</option>
                      </select>
              </div>

            </div>


          </div>
        </div>
        <!--=====================================
        PIE DEL MODAL
        ======================================-->
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
          <button type="submit" class="btn btn-"style="background:#6c757d; color:white">Guardar Gasto</button>
        </div>
        <?php
          $editarGasto = new ControladorGastos();
          $editarGasto -> ctrEditarGasto();
        ?>
      </form>
    </div>
  </div>
</div>

<?php
  $eliminarGasto = new ControladorGastos();
  $eliminarGasto -> ctrEliminarGasto();

?>
