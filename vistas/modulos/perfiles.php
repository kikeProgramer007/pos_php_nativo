<?php
if (!Permisos::tiene("perfiles.ver")) {
  echo '<script>window.location = "no-autorizado";</script>';
  return;
}

ControladorPerfiles::ctrEliminarPerfil();
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
    <h1 style="font-family: Arial, sans-serif; font-weight: bold;">Perfiles</h1>
    <ol class="breadcrumb">
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active">Perfiles</li>
    </ol>
  </section>

  <section class="content">
    <div class="box">
      <div class="box-header with-border">
        <?php if (Permisos::tiene("perfiles.crear")) { ?>
        <a href="agregar-perfil" class="btn btn-primary">
          <i class="fa fa-plus"></i> Agregar perfil
        </a>
        &nbsp;
        <?php } ?>
        <?php if (Permisos::tiene("perfiles.eliminados")) { ?>
        <a class="btn btn-danger" href="perfiles-eliminados">
          <i class="fa fa-trash"></i> <span>Eliminados</span>
        </a>
        <?php } ?>
      </div>
      <div class="box-body">
        <table class="table table-bordered table-striped dt-responsive tablasPerfiles text-uppercase" width="100%">
          <thead>
            <tr>
              <th style="width:10px">#</th>
              <th>Nombre</th>
              <th>Descripción</th>
              <th>Estado</th>
              <th>Acciones</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </section>
</div>
