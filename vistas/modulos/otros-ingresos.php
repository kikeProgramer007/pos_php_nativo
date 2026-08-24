<?php

if (!Permisos::tiene("caja.otros_ingresos")) {
  echo '<script>window.location = "no-autorizado";</script>';
  return;
}

$cajaArqueoAbierta = !empty($_SESSION["idArqueoCaja"]) && ModeloArqueo::mdlVerificarCajaAbiertaPorIdArqueo($_SESSION["idArqueoCaja"]);

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
</style>

<div class="content-wrapper text-uppercase">

  <section class="content-header">
    <h1 style="font-family: Arial, sans-serif; font-weight: bold;">
      Otros Ingresos
    </h1>
    <ol class="breadcrumb">
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active">Otros Ingresos</li>
    </ol>
  </section>

  <section class="content">
    <div class="box">
      <div class="box-header with-border">
        <?php
        if ($cajaArqueoAbierta) {
          echo '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarOtroIngreso">
                  <i class="fa fa-plus"></i> Agregar otro ingreso
                </button>';
        }
        ?>
        &nbsp;
        <a class="btn btn-primary" target="_blank" href="reporte_otros_ingresos.php">
          <i class="fa fa-print"></i> Imprimir
        </a>
      </div>
      <div class="box-body">
        <table class="table table-bordered table-striped dt-responsive text-uppercase tabla-otros-ingresos" width="100%">
          <thead>
            <tr>
              <th style="width:10px">#</th>
              <th>FECHA</th>
              <th>OBSERVACIÓN</th>
              <th>ENTRADA</th>
              <th>MONTO</th>
              <th>USUARIO</th>
              <th>ARQUEO</th>
              <th>ACCIONES</th>
            </tr>
          </thead>
        </table>
        <input type="hidden" value="<?php echo $_SESSION['perfil']; ?>" id="perfilOculto">
      </div>
    </div>
  </section>

</div>

<?php include "componentes/modal-otro-ingreso.php"; ?>
<?php include "componentes/modal-editar-otro-ingreso.php"; ?>
