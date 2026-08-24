<?php

if (!Permisos::tiene("gastos.ver")) {
  echo '<script>window.location = "no-autorizado";</script>';
  return;

}

?>

<style>
  .dataTables_wrapper .dataTables_filter {
    float: right;
    margin: 0 0 12px;
  }

  .dataTables_wrapper .dataTables_filter label {
    display: flex;
    align-items: center;
    width: 360px;
    height: 42px;
    margin: 0;
    background: #fff;
    border: 2px solid #2ec76d;
    border-radius: 10px;
    overflow: hidden;
    font-size: 0;
    box-shadow: 0 2px 6px rgba(0,0,0,.08);
  }

  .dataTables_wrapper .dataTables_filter label:focus-within {
    box-shadow: 0 0 0 3px rgba(46, 199, 109, .15);
  }

  .dataTables_wrapper .dataTables_filter label::before {
    content: "\f002";
    font-family: "FontAwesome";
    display: flex;
    align-items: center;
    justify-content: center;
    width: 46px;
    height: 100%;
    background: #2ec76d;
    color: #fff;
    font-size: 18px;
    flex-shrink: 0;
  }

  .dataTables_wrapper .dataTables_filter input[type="search"] {
    width: 100%;
    height: 100%;
    border: 0;
    outline: none;
    background: #fff;
    padding: 0 12px;
    font-size: 15px;
    color: #1f2937;
    box-sizing: border-box;
  }

  .dataTables_wrapper .dataTables_filter input[type="search"]::placeholder {
    color: #6b7280;
    opacity: 1;
  }

  @media (max-width: 768px) {
    .dataTables_wrapper .dataTables_filter {
      float: none;
      width: 100%;
      margin-bottom: 15px;
    }

    .dataTables_wrapper .dataTables_filter label {
      width: 100%;
    }
  }
</style>

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
        if (Permisos::tiene("gastos.crear")) {
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
      <form role="form" method="post" class="form-gasto-pago" id="formEditarGasto">
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
                <span class="input-group-addon">FECHA</span>
                <input type="date" id="editarFecha" name="editarFecha" class="form-control input-lg" required />
              </div>
            </div>

            <div class="form-group">
              <label style="display:block; margin-bottom:8px;">FORMA DE PAGO:</label>
              <div class="gasto-pago-opciones">
                <label class="gasto-pago-opcion" data-tipo="2">
                  <input type="radio" name="editarTipoPago" value="2">
                  <span class="gasto-pago-icono"><i class="fa fa-qrcode"></i></span>
                  <span class="gasto-pago-texto">QR</span>
                </label>
                <label class="gasto-pago-opcion active" data-tipo="1">
                  <input type="radio" name="editarTipoPago" value="1" checked>
                  <span class="gasto-pago-icono"><i class="fa fa-money"></i></span>
                  <span class="gasto-pago-texto">EFECTIVO</span>
                </label>
                <label class="gasto-pago-opcion" data-tipo="4">
                  <input type="radio" name="editarTipoPago" value="4">
                  <span class="gasto-pago-icono"><i class="fa fa-exchange"></i></span>
                  <span class="gasto-pago-texto">MIXTO</span>
                </label>
                <label class="gasto-pago-opcion" data-tipo="3">
                  <input type="radio" name="editarTipoPago" value="3">
                  <span class="gasto-pago-icono"><i class="fa fa-university"></i></span>
                  <span class="gasto-pago-texto">TRANSF.</span>
                </label>
              </div>
            </div>

            <div class="form-group grupo-monto-gasto-simple">
              <div class="input-group">
                <span class="input-group-addon">MONTO BS.</span>
                <input type="number" step="0.01" min="0.01" class="form-control input-lg" id="editarMonto" name="editarMonto" placeholder="0.00" required>
              </div>
            </div>

            <div class="grupo-monto-gasto-mixto" style="display:none;">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">EFECTIVO BS.</span>
                  <input type="number" step="0.01" min="0" class="form-control input-lg" id="editarMontoEfectivo" name="editarMontoEfectivo" placeholder="0.00">
                </div>
              </div>
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">QR BS.</span>
                  <input type="number" step="0.01" min="0" class="form-control input-lg" id="editarMontoQr" name="editarMontoQr" placeholder="0.00">
                </div>
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

<style>
  #modalEditarGasto .gasto-pago-opciones,
  .modal .gasto-pago-opciones {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
  }
  #modalEditarGasto .gasto-pago-opcion,
  .modal .gasto-pago-opcion {
    flex: 1;
    min-width: 90px;
    margin: 0;
    border: 1px solid #d2d6de;
    border-radius: 4px;
    padding: 10px 8px;
    text-align: center;
    cursor: pointer;
    background: #fff;
    font-weight: normal;
  }
  #modalEditarGasto .gasto-pago-opcion.active,
  .modal .gasto-pago-opcion.active {
    border-color: #28a745;
    box-shadow: 0 0 0 2px rgba(40, 167, 69, .18);
    background: #f3fff6;
  }
  #modalEditarGasto .gasto-pago-opcion input[type="radio"],
  .modal .gasto-pago-opcion input[type="radio"] {
    float: left;
    margin: 2px 0 0 2px;
  }
  #modalEditarGasto .gasto-pago-icono,
  .modal .gasto-pago-icono {
    display: block;
    font-size: 26px;
    margin: 4px 0 6px;
    color: #28a745;
  }
  #modalEditarGasto .gasto-pago-opcion[data-tipo="4"] .gasto-pago-icono,
  #modalEditarGasto .gasto-pago-opcion[data-tipo="4"] .gasto-pago-texto,
  .modal .gasto-pago-opcion[data-tipo="4"] .gasto-pago-icono,
  .modal .gasto-pago-opcion[data-tipo="4"] .gasto-pago-texto {
    color: #17a2b8;
  }
  #modalEditarGasto .gasto-pago-texto,
  .modal .gasto-pago-texto {
    display: block;
    font-size: 11px;
    font-weight: 700;
    color: #28a745;
  }
</style>

<?php
  $eliminarGasto = new ControladorGastos();
  $eliminarGasto -> ctrEliminarGasto();

?>

<script>
  $(document).ready(function () {
    $('.dataTables_filter input[type="search"]').attr('placeholder', 'Buscar gasto');
  });
</script>
