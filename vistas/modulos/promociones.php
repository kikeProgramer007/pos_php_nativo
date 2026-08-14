<?php
if ($_SESSION["perfil"] == "Vendedor") {
  echo '<script>window.location = "inicio";</script>';
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

<script>
  $(function () {
    var applyPromoSearchPlaceholder = function () {
      var $input = $('.dataTables_wrapper .dataTables_filter input[type="search"]');
      if (!$input.length) return;
      $input.attr('placeholder', 'Buscar promoción');
      $input.css('font-size', '15px');
    };

    applyPromoSearchPlaceholder();

    // ocultar buscador del DataTable de promociones (porque ya hay otro buscador arriba)
    $('.tablasPromociones').each(function () {
      var $w = $(this).closest('.dataTables_wrapper');
      if ($w.length) $w.find('.dataTables_filter').hide();
    });

    $(document).on('draw.dt', '.tablasPromociones', function () {
      applyPromoSearchPlaceholder();
      var $w = $(this).closest('.dataTables_wrapper');
      if ($w.length) $w.find('.dataTables_filter').hide();
    });
  });
</script>
