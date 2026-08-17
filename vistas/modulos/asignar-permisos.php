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
foreach ($catalogo as $perm) {
  $modulos[$perm["modulo"]][] = $perm;
}
?>

<style>
  .permisos-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 15px;
  }
  .permisos-modulo .box-header {
    cursor: default;
  }
  .permisos-modulo .checkbox {
    margin: 6px 0;
  }
  .permiso-item {
    margin-left: 22px;
  }
</style>

<div class="content-wrapper">
  <section class="content-header">
    <h1>Asignar permisos</h1>
    <ol class="breadcrumb">
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li><a href="perfiles">Perfiles</a></li>
      <li class="active">Asignar permisos</li>
    </ol>
  </section>

  <section class="content">
    <form method="post" id="formPermisosPerfil">
      <input type="hidden" name="idPerfilPermisos" value="<?php echo intval($perfil["id"]); ?>">

      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Perfil: <strong><?php echo htmlspecialchars($perfil["nombre"]); ?></strong></h3>
        </div>
        <div class="box-body">
          <div class="permisos-toolbar">
            <label class="checkbox-inline" style="font-weight:600;">
              <input type="checkbox" id="seleccionarTodoPermisos"> Seleccionar todo
            </label>
            <div>
              <button type="submit" class="btn btn-success">
                <i class="fa fa-floppy-o"></i> Guardar
              </button>
              <a href="perfiles" class="btn btn-default">Cancelar</a>
            </div>
          </div>

          <div class="row">
            <?php foreach ($modulos as $nombreModulo => $permisosModulo) { ?>
            <div class="col-md-6">
              <div class="box box-primary permisos-modulo">
                <div class="box-header with-border">
                  <label class="checkbox-inline" style="font-weight:700; text-transform:uppercase;">
                    <input type="checkbox" class="check-modulo" data-modulo="<?php echo htmlspecialchars($nombreModulo); ?>">
                    <?php echo htmlspecialchars($nombreModulo); ?>
                  </label>
                </div>
                <div class="box-body">
                  <?php foreach ($permisosModulo as $perm) {
                    $checked = in_array(intval($perm["id"]), $asignados, true) ? "checked" : "";
                  ?>
                  <div class="checkbox permiso-item">
                    <label>
                      <input type="checkbox"
                             class="check-permiso"
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
        </div>
      </div>
      <?php
      $guardarPermisos = new ControladorPerfiles();
      $guardarPermisos->ctrGuardarPermisosPerfil();
      ?>
    </form>
  </section>
</div>
