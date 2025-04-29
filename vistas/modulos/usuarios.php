
<?php

if($_SESSION["perfil"] == "Especial" || $_SESSION["perfil"] == "Vendedor"){

  echo '<script>

    window.location = "inicio";

  </script>';

  return;

}

?>


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
        <a href="agregar-usuario" class="btn btn-primary">
        <i class="fa fa-plus"></i>
       Agregar Usuarios
       </a>
      &nbsp;
  
        <a class="btn btn-primary" target="_blank" href="reporte_usuario.php">
            <i class="material-icons"></i>
            <i class="fa fa-print"></i>
            <span class="icon-name"> Imprimir </span>
              </a>
              &nbsp;
              <a class="btn btn-danger" href="usuarios-eliminados">
    <i class="fa fa-trash"></i>
    <span> Eliminados </span>
</a>


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

                <select class="form-control input-lg" name="editarPerfil">
                  
                  <option value="" id="editarPerfil"></option>

                  <option value="Administrador">Administrador</option>

                  <option value="Especial">Especial</option>

                  <option value="Vendedor">Vendedor</option>

                </select>

              </div>

            </div>

            <!-- ENTRADA PARA SUBIR FOTO -->

             <div class="form-group">
              
              <div class="panel">SUBIR FOTO</div>

              <input type="file" class="nuevaFoto" name="editarFoto">

              <p class="help-block">Peso máximo de la foto 2MB</p>

              <img src="vistas/img/usuarios/default/anonymous.png" class="img-thumbnail previsualizar" width="100px">

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




