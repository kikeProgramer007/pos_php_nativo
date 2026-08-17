<?php
if (!Permisos::tiene("perfiles.editar")) {
  echo '<script>window.location = "no-autorizado";</script>';
  return;
}

$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
$perfil = ControladorPerfiles::ctrMostrarPerfil("id", $id);
if (!$perfil || intval($perfil["activo"]) !== 1) {
  echo '<script>window.location = "perfiles";</script>';
  return;
}
?>

<div class="content-wrapper text-uppercase">
  <section class="content-header">
    <h1>Editar perfil</h1>
    <ol class="breadcrumb">
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li><a href="perfiles">Perfiles</a></li>
      <li class="active">Editar perfil</li>
    </ol>
  </section>

  <section class="content">
    <div class="box">
      <div class="box-body">
        <form role="form" method="post">
          <input type="hidden" name="idPerfil" value="<?php echo intval($perfil["id"]); ?>">
          <div class="form-group">
            <label>Nombre</label>
            <input type="text" class="form-control input-lg" name="editarNombrePerfil" value="<?php echo htmlspecialchars($perfil["nombre"]); ?>" required>
          </div>
          <div class="form-group">
            <label>Descripción</label>
            <input type="text" class="form-control input-lg" name="editarDescripcionPerfil" value="<?php echo htmlspecialchars($perfil["descripcion"]); ?>">
          </div>
          <div class="box-footer">
            <button type="submit" class="btn btn-primary">
              <i class="fa fa-floppy-o"></i> Guardar
            </button>
            <a href="perfiles" class="btn btn-default">Cancelar</a>
          </div>
          <?php
          $editarPerfil = new ControladorPerfiles();
          $editarPerfil->ctrEditarPerfil();
          ?>
        </form>
      </div>
    </div>
  </section>
</div>
