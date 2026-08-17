<?php

if (!Permisos::tiene("ventas.ver")) {
  echo '<script>window.location = "no-autorizado";</script>';
  return;
}

?>

<style>
  .ventas-header-actions {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
  }

  .ventas-header-left {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 8px;
  }

  .ventas-toolbar {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-end;
    align-items: center;
    gap: 10px;
  }

  .ventas-filter-card {
    display: flex;
    align-items: center;
    gap: 8px;
    min-width: 220px;
    padding: 8px 12px;
    border: 1px solid #dfe6ee;
    border-radius: 12px;
    background: linear-gradient(180deg, #ffffff 0%, #f7fafc 100%);
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
  }

  .ventas-filter-card.filter-highlight {
    border-color: #c6d8ef;
    box-shadow: 0 6px 18px rgba(43, 108, 176, 0.12);
  }

  .ventas-filter-card i {
    color: #5b6b7f;
    font-size: 14px;
  }

  .ventas-filter-card select {
    border: 0;
    box-shadow: none;
    background: transparent;
    font-weight: 600;
    color: #334155;
    padding-left: 0;
  }

  .ventas-filter-card select:focus {
    box-shadow: none;
  }

  .acciones-ventas-wrap {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 6px;
  }

  .tablaVentasRealizadas .action-charge-button {
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(40, 167, 69, 0.2);
    font-weight: 600;
  }

  .tablaVentasRealizadas .action-toggle {
    border-radius: 8px;
    border: 1px solid #cfd6df;
    background: linear-gradient(180deg, #ffffff 0%, #edf1f5 100%);
    color: #3c4b64;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.12);
    padding: 6px 10px;
  }

  .tablaVentasRealizadas .action-toggle:hover,
  .tablaVentasRealizadas .action-toggle:focus {
    background: linear-gradient(180deg, #ffffff 0%, #e3ebf3 100%);
    color: #1f2d3d;
  }

  .tablaVentasRealizadas .dropdown-menu {
    border-radius: 10px;
    border: 1px solid #dfe6ee;
    box-shadow: 0 10px 24px rgba(0, 0, 0, 0.14);
    padding: 6px;
  }

  .tablaVentasRealizadas .dropdown-menu > li > a {
    display: block;
    padding: 9px 14px;
    white-space: nowrap;
    border-radius: 8px;
    color: #425466;
    font-weight: 600;
    transition: all 0.15s ease;
  }

  .tablaVentasRealizadas .dropdown-menu > li > a i {
    width: 18px;
    margin-right: 8px;
  }

  .tablaVentasRealizadas .btn-group.open .dropdown-menu {
    min-width: 190px;
  }

  .tablaVentasRealizadas .dropdown-menu > li > a:hover {
    background: #f4f7fb;
    color: #22303d;
  }

  .tablaVentasRealizadas .dropdown-menu > li > a.action-view i {
    color: #2b7dbc;
  }

  .tablaVentasRealizadas .dropdown-menu > li > a.action-print i {
    color: #b7791f;
  }

  .tablaVentasRealizadas .dropdown-menu > li > a.action-edit {
    background: #eef5ff;
    color: #1d4f91;
  }

  .tablaVentasRealizadas .dropdown-menu > li > a.action-edit i {
    color: #2b6cb0;
  }

  .tablaVentasRealizadas .dropdown-menu > li > a.action-charge {
    background: #ecfff3;
    color: #1f7a45;
  }

  .tablaVentasRealizadas .dropdown-menu > li > a.action-charge i {
    color: #1f9d55;
  }

  .tablaVentasRealizadas .dropdown-menu > li > a.action-delete {
    background: #fff1f1;
    color: #a94442;
  }

  .tablaVentasRealizadas .dropdown-menu > li > a.action-delete i {
    color: #c0392b;
  }
</style>


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
        <div class="ventas-header-actions">
          <div class="ventas-header-left">
            <a href="crear-venta">
              <button class="btn btn-primary">
                <i class="fa fa-plus"></i>
                Agregar venta
              </button>
            </a>

            <a class="btn btn-danger" href="ventas-eliminadas">
              <i class="fa fa-trash"></i>
              <span>VENTAS ELIMINADAS </span>
            </a>
          </div>

          <div class="ventas-toolbar">
            <div class="ventas-filter-card filter-highlight">
            <i class="fa fa-ticket"></i>
            <select class="form-control input-sm" id="filtroEstadoPago">
              <option value="todos">Todos</option>
              <option value="PENDIENTE">Cuenta pendiente</option>
              <option value="PAGADA">Cuenta pagada</option>
            </select>
            </div>

            <div class="ventas-filter-card filter-highlight">
              <i class="fa fa-user"></i>
              <select class="form-control input-sm" id="filtroMesero">
                <option value="0">Todos los meseros</option>
                <?php
                  $meserosFiltro = ControladorMeseros::ctrMostrarMeseros(null, null);
                  foreach ($meserosFiltro as $meseroFiltro) {
                    echo "<option value='".$meseroFiltro["id"]."'>".$meseroFiltro["nombre"]."</option>";
                  }
                ?>
              </select>
            </div>

            <button type="button" class="btn btn-default" id="daterange-btn">
              <span>
                <i class="fa fa-calendar"></i> Rango de fecha
              </span>
              <i class="fa fa-caret-down"></i>
            </button>
          </div>
        </div>

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
              <th>Estado del pago</th>
              <th>Fecha</th>
              <th style="width:10px">Acciones</th>

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

<!-- Modal cobrar cuenta pendiente -->
<div id="modalCobrarCuenta" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-uppercase">Cobrar cuenta</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" id="idVentaCobrar" value="">
        <input type="hidden" id="idVendedorCobro" value="<?php echo $_SESSION['id']; ?>">
        <div class="form-group">
          <label>N° Ticket</label>
          <input type="text" class="form-control" id="ticketCobrar" readonly>
        </div>
        <div class="form-group">
          <label>Total a cobrar (Bs)</label>
          <input type="text" class="form-control" id="totalVentaCobro" readonly>
        </div>
        <div class="form-group">
          <label>Tipo de pago</label>
          <select class="form-control" id="tipoPagoCobro">
            <option value="1">Efectivo</option>
            <option value="2">QR</option>
            <option value="4">Qr y Efectivo (Mixto)</option>
          </select>
        </div>
        <div class="form-group" id="grupoEfectivoCobro">
          <label>Pago en efectivo</label>
          <input type="number" class="form-control" id="nuevoValorEfectivoCobro" min="0" step="0.01" value="0">
        </div>
        <div class="form-group" id="grupoQRCobro" style="display:none;">
          <label>Pago en QR</label>
          <input type="number" class="form-control" id="nuevoValorQRCobro" min="0" step="0.01" value="0">
        </div>
        <div class="form-group" id="grupoCambioCobro">
          <label>Cambio</label>
          <input type="text" class="form-control" id="nuevoCambioEfectivoCobro" readonly value="0.00">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success" id="btnConfirmarCobro">Cobrar cuenta</button>
      </div>
    </div>
  </div>
</div>