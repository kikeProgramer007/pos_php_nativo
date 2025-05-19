<?php

if($_SESSION["perfil"] == "Supervisor" || $_SESSION["perfil"] == "Vendedor"){

  echo '<script>

    window.location = "inicio";

  </script>';

  return;

}

?>


<div class="content-wrapper text-uppercase ">

  <section class="content-header">
    
  <h1 style="font-family: Arial, sans-serif; font-weight: bold;">
  Usuarios eliminados

</h1>


    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Usuarios eliminados</li>
    
    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">
  
   
        <a href="usuarios" class="btn btn-primary">
        <i class="fa fa-arrow-left"></i>
      Volver
       </a>
    
  
       


      </div>

      <div class="box-body">
        
       <table class="table table-bordered table-striped dt-responsive tablasusuariosEliminados text-uppercase" width="100%">
         
        <thead>
         
         <tr>
           
           <th style="width:10px">#</th>
           <th>Nombre</th>
           <th>Usuario</th>
           <th>Foto</th>
           <th>Perfil</th>
        
           <th>Último login</th>
           <th>Acción</th>

         </tr> 

        </thead>

        
       </table>

      </div>

    </div>

  </section>

</div>





<?php

  $borrarUsuario = new ControladorUsuarios();
  $borrarUsuario -> ctrBorrarUsuario();

?> 


