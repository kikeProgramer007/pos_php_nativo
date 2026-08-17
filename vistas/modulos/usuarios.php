<?php

if (!Permisos::tiene("usuarios.ver")) {
  echo '<script>window.location = "no-autorizado";</script>';
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

<div class="content-wrapper text-uppercase ">

  <section class="content-header">
    
  <h1 style="font-family: Arial, sans-serif; font-weight: bold;">
  Administrar usuarios

</h1>


    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Administrar usuarios</li>
    
    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">
  
    <!--    <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarUsuario">
      
          Agregar Usuario
        </button>  -->
        <?php if (Permisos::tiene("usuarios.crear")) { ?>
        <a href="agregar-usuario" class="btn btn-primary">
        <i class="fa fa-plus"></i>
       Agregar Usuarios
       </a>
        <?php } ?>
      &nbsp;
  
        <a class="btn btn-primary" target="_blank" href="reporte_usuario.php">
            <i class="material-icons"></i>
            <i class="fa fa-print"></i>
            <span class="icon-name"> Imprimir </span>
              </a>
              &nbsp;
              <?php if (Permisos::tiene("usuarios.eliminados")) { ?>
              <a class="btn btn-danger" href="usuarios-eliminados">
    <i class="fa fa-trash"></i>
    <span> Eliminados </span>
</a>
              <?php } ?>


      </div>

      <div class="box-body">
        
       <table class="table table-bordered table-striped dt-responsive tablasusuarios text-uppercase" width="100%">
         
        <thead>
         
         <tr>
           
           <th style="width:10px">#</th>
           <th>Nombre</th>
           <th>Usuario</th>
           <th>Foto</th>
           <th>Perfil</th>
           <th>Estado</th>
           <th>Último login</th>
           <th>Acciones</th>

         </tr> 

        </thead>

        
       </table>

      </div>

    </div>

  </section>

</div>

<!--=====================================
MODAL EDITAR USUARIO
======================================-->

<div id="modalEditarUsuario" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post" enctype="multipart/form-data">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#6c757d; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Editar usuario</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <!-- ENTRADA PARA EL NOMBRE -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                 
                <span class="input-group-addon">NOMBRE</span> 
                  
                <input type="text" class="form-control input-lg" id="editarNombre" name="editarNombre" value="" required>

              </div>

            </div>

                 <!-- ENTRADA PARA EL USUARIO -->

                     <div class="form-group">
              
                     <div class="input-group">

                     <span class="input-group-addon">USUARIO</span> 
                        <input type="text" class="form-control input-lg" id="editarUsuario" name="editarUsuario" value="" readonly>

                      </div>

                     </div>

            <!-- ENTRADA PARA LA CONTRASEÑA -->

            
            
                   <div class="form-group">

              
                      <div class="input-group">
              
                      <span class="input-group-addon">CONTRASEÑA</span> 

                <input type="password" class="form-control input-lg" name="editarPassword" placeholder=" nuevo password">

                <input type="hidden" id="passwordActual" name="passwordActual">

                </div>

               </div>

            <!-- ENTRADA PARA SELECCIONAR SU PERFIL -->

            <div class="form-group">
              
              <div class="input-group">
              <span class="input-group-addon">ROL</span> 

                <select class="form-control input-lg" name="editarPerfil" id="editarPerfil">
                  <option value="">Seleccione un perfil</option>
                  <?php foreach (Permisos::perfilesSelect() as $opcionPerfil) { ?>
                  <option value="<?php echo intval($opcionPerfil["id"]); ?>"><?php echo htmlspecialchars($opcionPerfil["nombre"]); ?></option>
                  <?php } ?>
                </select>

              </div>

            </div>

            <!-- ENTRADA PARA SUBIR FOTO -->

             <div class="form-group">
              
              <div class="panel">SUBIR FOTO</div>

              <input type="file" class="nuevaFoto" name="editarFoto">

              <p class="help-block">Peso máximo de la foto 2MB</p>

              <img src="vistas/img/usuarios/default/anonymous.webp" class="img-thumbnail previsualizar" width="100px">

              <input type="hidden" name="fotoActual" id="fotoActual">

            </div>

          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-" style="background:#6c757d; color:white">Modificar usuario</button>

        </div>



        <?php

        $editarUsuario = new ControladorUsuarios();
        $editarUsuario -> ctrEditarUsuario();

        ?> 

          </form>

         </div>

      </div>

   </div>



<?php

  $borrarUsuario = new ControladorUsuarios();
  $borrarUsuario -> ctrBorrarUsuario();
  $RestaurarUsuario = new ControladorUsuarios();
  $RestaurarUsuario -> ctrRestaurarUsuario();

?>

<script>
  $(document).ready(function () {
    $('.dataTables_filter input[type="search"]').attr('placeholder', 'Buscar usuario');
  });
</script> 




