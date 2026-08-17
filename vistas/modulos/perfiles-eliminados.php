<?php
if (!Permisos::tiene("perfiles.eliminados")) {
  echo '<script>window.location = "no-autorizado";</script>';
  return;
}
ControladorPerfiles::ctrRestaurarPerfil();
?>

<div class="content-wrapper text-uppercase">
  <section class="content-header">
    <h1>Perfiles eliminados</h1>
    <ol class="breadcrumb">
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li><a href="perfiles">Perfiles</a></li>
      <li class="active">Eliminados</li>
    </ol>
  </section>

  <section class="content">
    <div class="box">
      <div class="box-header with-border">
        <a href="perfiles" class="btn btn-default">
          <i class="fa fa-arrow-left"></i> Volver
        </a>
      </div>
      <div class="box-body">
        <table class="table table-bordered table-striped dt-responsive tablasPerfilesEliminados text-uppercase" width="100%">
          <thead>
            <tr>
              <th style="width:10px">#</th>
              <th>Nombre</th>
              <th>Descripción</th>
              <th>Acciones</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </section>
</div>
