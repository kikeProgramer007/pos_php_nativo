<?php
if (!Permisos::tiene("perfiles.permisos")) {
  echo '<script>window.location = "no-autorizado";</script>';
  return;
}

$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
$perfil = ControladorPerfiles::ctrMostrarPerfil("id", $id);
if (!$perfil || intval($perfil["activo"]) !== 1) {
  echo '<script>window.location = "perfiles";</script>';
  return;
}

$catalogo = ModeloPerfiles::mdlMostrarPermisosAgrupados();
$asignados = ModeloPerfiles::mdlIdsPermisosPerfil($id);
$asignados = array_map("intval", $asignados);

$modulos = [];
$totalPermisos = 0;
foreach ($catalogo as $perm) {
  $modulos[$perm["modulo"]][] = $perm;
  $totalPermisos++;
}

$iconosModulo = [
  "Inicio" => "fa-home",
  "Usuarios" => "fa-users",
  "Perfiles" => "fa-id-badge",
  "Categorías" => "fa-tags",
  "Categorias" => "fa-tags",
  "Productos" => "fa-cube",
  "Ofertas y promociones" => "fa-percent",
  "Clientes" => "fa-user-circle",
  "Meseros" => "fa-cutlery",
  "Ventas" => "fa-shopping-cart",
  "Compras" => "fa-truck",
  "Gastos" => "fa-money",
  "Otros ingresos" => "fa-plus-circle",
  "Caja" => "fa-calculator",
  "Reportes" => "fa-bar-chart",
  "Proveedores" => "fa-industry",
];

function posIconoModulo($nombre, $mapa) {
  if (isset($mapa[$nombre])) {
    return $mapa[$nombre];
  }
  $clave = ucfirst(strtolower(trim($nombre)));
  return $mapa[$clave] ?? "fa-folder-open-o";
}
?>

<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Asignar permisos
      <small>Perfil</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li><a href="perfiles">Perfiles</a></li>
      <li class="active">Asignar permisos</li>
    </ol>
  </section>

  <section class="content">

    <div class="row">
      <div class="col-md-4">
        <div class="info-box bg-yellow">
          <span class="info-box-icon"><i class="fa fa-shield"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Perfil</span>
            <span class="info-box-number"><?php echo htmlspecialchars($perfil["nombre"]); ?></span>
            <span class="progress-description">Acciones permitidas en el sistema</span>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="info-box bg-green">
          <span class="info-box-icon"><i class="fa fa-check"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Permisos activos</span>
            <span class="info-box-number"><span id="permisosMarcados">0</span> / <?php echo $totalPermisos; ?></span>
            <span class="progress-description">Seleccionados en este perfil</span>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="info-box bg-aqua">
          <span class="info-box-icon"><i class="fa fa-th-large"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Módulos</span>
            <span class="info-box-number"><?php echo count($modulos); ?></span>
            <span class="progress-description">Grupos de permisos</span>
          </div>
        </div>
      </div>
    </div>

    <form method="post" id="formPermisosPerfil">
      <input type="hidden" name="idPerfilPermisos" value="<?php echo intval($perfil["id"]); ?>">

      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-filter"></i> Filtros</h3>
          <div class="box-tools pull-right">
            <button type="submit" class="btn btn-success btn-sm">
              <i class="fa fa-save"></i> Guardar
            </button>
            <a href="perfiles" class="btn btn-default btn-sm">Cancelar</a>
          </div>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-sm-4">
              <label>
                <input type="checkbox" class="minimal" id="seleccionarTodoPermisos">
                Seleccionar todo
              </label>
            </div>
            <div class="col-sm-8">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control" id="buscarPermisos" placeholder="Buscar módulo o permiso..." autocomplete="off">
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row" id="gridPermisos">
        <?php foreach ($modulos as $nombreModulo => $permisosModulo) {
          $icono = posIconoModulo($nombreModulo, $iconosModulo);
          $cantModulo = count($permisosModulo);
        ?>
        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 permisos-modulo" data-modulo="<?php echo htmlspecialchars($nombreModulo); ?>">
          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title" title="<?php echo htmlspecialchars($nombreModulo); ?>">
                <i class="fa <?php echo $icono; ?>"></i>
                <small><?php echo htmlspecialchars($nombreModulo); ?></small>
                <span class="label label-default permiso-modulo-count" data-modulo="<?php echo htmlspecialchars($nombreModulo); ?>">0/<?php echo $cantModulo; ?></span>
              </h3>
              <div class="box-tools pull-right">
                <label class="small" title="Marcar todo el módulo">
                  <input type="checkbox" class="minimal check-modulo" data-modulo="<?php echo htmlspecialchars($nombreModulo); ?>">
                  Todo
                </label>
              </div>
            </div>
            <div class="box-body">
              <?php foreach ($permisosModulo as $perm) {
                $checked = in_array(intval($perm["id"]), $asignados, true) ? "checked" : "";
              ?>
              <div class="checkbox permiso-item" data-text="<?php echo htmlspecialchars(strtolower($nombreModulo . ' ' . $perm["nombre"])); ?>">
                <label class="small">
                  <input type="checkbox"
                         class="minimal check-permiso"
                         name="permisos[]"
                         data-modulo="<?php echo htmlspecialchars($nombreModulo); ?>"
                         value="<?php echo intval($perm["id"]); ?>"
                         <?php echo $checked; ?>>
                  <?php echo htmlspecialchars($perm["nombre"]); ?>
                </label>
              </div>
              <?php } ?>
            </div>
          </div>
        </div>
        <?php } ?>
      </div>

      <div class="box-footer" style="background:transparent;border:none;padding-left:0;">
        <button type="submit" class="btn btn-success">
          <i class="fa fa-save"></i> Guardar cambios
        </button>
        <a href="perfiles" class="btn btn-default">Cancelar</a>
      </div>

      <?php
      $guardarPermisos = new ControladorPerfiles();
      $guardarPermisos->ctrGuardarPermisosPerfil();
      ?>
    </form>
  </section>
</div>
