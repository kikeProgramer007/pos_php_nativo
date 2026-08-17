<?php

if (!Permisos::tiene("proveedores.eliminados")) {
  echo '<script>window.location = "no-autorizado";</script>';
  return;
}

?>
<style>
        body {
            text-transform: uppercase;
        }
    </style>


<div class="content-wrapper">

  <section class="content-header">
    
  <h1 style="font-family: Arial, sans-serif; font-weight: bold;">
    ADMINISTRAR PROVEEDOR ELIMINADOS

</h1>
    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Administrar proveedor eliminados</li>
    
    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">
  
      <a class="btn btn-primary" href="proveedor">
        <i class="fa fa-arrow-left"></i>
        <span>Volver</span>
      </a>

      </div>

      <div class="box-body">
        
       <table class="table table-bordered table-striped dt-responsive tablasProveedorEliminados" width="100%">
         
        <thead>
         
         <tr>
           
           <th style="width:10px">#</th>
           <th>Nombre</th>
           <th>Empresa</th>
           <th>Telefono</th>
           <th>Direccion</th>
           <th>Ingreso al sistema</th>
           <th>Acciones</th>

         </tr> 

        </thead>
        <input type="hidden" value="<?php echo $_SESSION['perfil']; ?>" id="perfilOculto">

        <tbody>

       
   
        </tbody>

       </table>

      </div>

    </div>

  </section>

</div>





<?php

  $eliminarProveedor = new ControladorProveedors();
  $eliminarProveedor -> ctrEliminarProveedor();

?>


