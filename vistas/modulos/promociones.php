<?php
if ($_SESSION["perfil"] == "Vendedor") {
  echo '<script>window.location = "inicio";</script>';
  return;
}
?>

<div class="content-wrapper text-uppercase">
  <section class="content-header">
    <h1>Ofertas y Promociones</h1>
    <ol class="breadcrumb">
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active">Ofertas y Promociones</li>
    </ol>
  </section>

  <section class="content">
    <div class="box">
      <div class="box-header with-border">
        <a href="agregar-promocion" class="btn btn-primary">
          <i class="fa fa-plus"></i> Registrar promoción
        </a>
      </div>

      <div class="box-body">
        <div class="row" style="margin-bottom:15px;">
          <div class="col-md-3">
            <label>Buscar por nombre</label>
            <input type="text" id="filtroNombrePromo" class="form-control" placeholder="Nombre de la promoción">
          </div>
          <div class="col-md-3">
            <label>Estado</label>
            <select id="filtroEstadoPromo" class="form-control">
              <option value="todos">Todos</option>
              <option value="habilitada">Habilitada</option>
              <option value="deshabilitada">Deshabilitada</option>
              <option value="vencida">Vencida</option>
              <option value="programada">Programada</option>
            </select>
          </div>
          <div class="col-md-3">
            <label>Vigencia</label>
            <select id="filtroVigenciaPromo" class="form-control">
              <option value="">Todas</option>
              <option value="vigentes">Vigentes ahora</option>
              <option value="no_vigentes">No vigentes</option>
            </select>
          </div>
          <div class="col-md-3" style="padding-top:25px;">
            <button type="button" class="btn btn-default" id="btnFiltrarPromos">
              <i class="fa fa-filter"></i> Filtrar
            </button>
          </div>
        </div>

        <table class="table table-bordered table-striped dt-responsive tablasPromociones" width="100%">
          <thead>
            <tr>
              <th style="width:10px">#</th>
              <th>Nombre</th>
              <th>Tipo descuento</th>
              <th>Productos</th>
              <th>Intervalos</th>
              <th>Fecha inicio</th>
              <th>Fecha fin</th>
              <th>Estado</th>
              <th>Acciones</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </section>
</div>
