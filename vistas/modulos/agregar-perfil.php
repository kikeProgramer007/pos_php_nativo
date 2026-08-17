<?php
if (!Permisos::tiene("perfiles.crear")) {
  echo '<script>window.location = "no-autorizado";</script>';
  return;
}
?>

<div class="content-wrapper text-uppercase">
  <section class="content-header">
    <h1>Agregar perfil</h1>
    <ol class="breadcrumb">
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li><a href="perfiles">Perfiles</a></li>
      <li class="active">Agregar perfil</li>
    </ol>
  </section>

  <section class="content">
    <div class="box">
      <div class="box-body">
        <form role="form" method="post">
          <div class="form-group">
            <label>Nombre</label>
            <input type="text" class="form-control input-lg" name="nuevoNombrePerfil" placeholder="Ej. Cajero" required>
          </div>
          <div class="form-group">
            <label>Descripción</label>
            <input type="text" class="form-control input-lg" name="nuevaDescripcionPerfil" placeholder="Descripción opcional">
          </div>
          <div class="box-footer">
            <button type="submit" class="btn btn-primary">
              <i class="fa fa-floppy-o"></i> Guardar
            </button>
            <a href="perfiles" class="btn btn-default">Cancelar</a>
          </div>
          <?php
          $crearPerfil = new ControladorPerfiles();
          $crearPerfil->ctrCrearPerfil();
          ?>
        </form>
      </div>
    </div>
  </section>
</div>
