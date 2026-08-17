<?php

if (!Permisos::tiene("categorias.eliminados")) {
  echo '<script>window.location = "no-autorizado";</script>';
  return;
}

?>



<div class="content-wrapper text-uppercase ">

<section class="content-header">

    <h1 style="font-family: Arial, sans-serif; font-weight: bold;">
    Administrar categorías Eliminadas

     </h1>

    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Administrar categorías Eliminadas</li>
    
    </ol>

  </section>

  <section class="content">

  <div class="box">



  <div class="box-header with-border">


      <a class="btn btn-primary" href="categorias">
  <i class="fa fa-arrow-left"></i>
  <span>Volver</span>
</a>


      </div>

      <div class="box-body">

        <table class="table table-bordered table-striped dt-responsive tablascategoriaEliminados" width="100%">

          <thead>

            <tr>

              <th style="width:10px">#</th>
              <th>Categoria</th>
              <th>Fecha</th>
              <th>Acciónes</th>

            </tr>

          </thead>

          <input type="hidden" value="<?php echo $_SESSION['perfil']; ?>" id="perfilOculto">
        </table>

      </div>

    </div>

  </section>

</div>





<?php





$restaurarCategoria = new ControladorCategorias();
$restaurarCategoria->ctrRestaurarCategoria();
?>