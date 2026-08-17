<?php

if (!Permisos::tiene("proveedores.ver")) {
  echo '<script>window.location = "no-autorizado";</script>';
  return;

}

?>
<style>
        body {
            text-transform: uppercase;
        }
    </style>

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

<div class="content-wrapper">

  <section class="content-header">
    
  <h1 style="font-family: Arial, sans-serif; font-weight: bold;">
    ADMINISTRAR PROVEEDOR

</h1>
    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Administrar proveedor</li>
    
    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">
  
      <!--   <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarProveedor">
          
          Agregar proveedor

        </button> -->

        <?php if (Permisos::tiene("proveedores.crear")) { ?>
        <a href="agregar-proveedor" class="btn btn-primary">
        <i class="fa fa-plus"></i>
       Agregar Proveedor
       </a>
        <?php } ?>
      &nbsp;
        <a class="btn btn-primary" target="_blank" href="reporte_proveedor.php">
                                       <i class="material-icons"></i>
                                       <i class="fa fa-print"></i>
                                      <span class="icon-name"> Imprimir </span>
                                    </a>

       &nbsp;

      <?php if (Permisos::tiene("proveedores.eliminados")) { ?>
      <a class="btn btn-danger" href="proveedor-eliminados">
      <i class="fa fa-trash"></i>
      <span> Eliminados </span>
      </a>
      <?php } ?>

      </div>

      <div class="box-body">
        
       <table class="table table-bordered table-striped dt-responsive tablasProveedor" width="100%">
         
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

<!--=====================================
MODAL AGREGAR PROVEEDOR
======================================-->

<div id="modalAgregarProveedor" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#6c757d; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Agregar proveedor</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <!-- ENTRADA PARA EL NOMBRE -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAACXBIWXMAAAsTAAALEwEAmpwYAAAA7ElEQVR4nMXUwSqEURQH8B9Zi2RjOytZDAtLXsGSKWvvIUuyoMGChWnK2htYKmXlHUQRK5GiW3dqFue75Q459a9b5/Trfl+nyz/UBFax/hvYGu7xlXOIdi22jI8hbJB3LNaA5wE2SLcGvCqAlzXgaQHs1YBHBXC/BuwWwIMacBJPAZbWaFplHQfgnhFqLgCnRgHbAdiqgcawggXs4AGP2MU8ln7yEGziLt/mDWf501NO8Jp7t/nBGG/CZnHTsCbbOVHvGjMReFHYu42cpn4/Al8ahj/zDdLupXM08xyBHWwFGX5Y0zmaSf/9b+obeeR7t6oVkbEAAAAASUVORK5CYII=">
                </span> 

                <input type="text" class="form-control input-lg" name="nuevoProveedor" placeholder="Ingresar nombre" required>

              </div>

            </div>

            <!-- ENTRADA PARA LA EMPRESA -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAmklEQVR4nGNgGCoggYGBoYEIrECsgQcYGBj+MzAwfICysWGQvAOpBh7Ao4YiA/9DNTegieHDB/AZCDJMABpmBkgGFkDl0PECQgbCIsABGmHIrkYWa4CqayBk4AGoy0AaJ6AZiCx2AKqOoIGkRkoDIQNxRQoxYiRFigOamANUHUEDRyNlEESKEwMDQwSUxgVA8mI45HQI6B1EAADBTHKxNASDWwAAAABJRU5ErkJggg==">
                </span> 

                <input type="text" class="form-control input-lg" name="nuevaEmpresa" placeholder="Ingresar empresa" required>

              </div>

            </div>

            <!-- ENTRADA PARA EL TELÉFONO -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAACXBIWXMAAAsTAAALEwEAmpwYAAABH0lEQVR4nO3VsUoDQRAG4E877fI4omBnJwiSLoWVr2GnFlqHNNaSB1CIPoEE7BQ0NtHKQgmCJjbKwgjHYQjkNsHCgZ+df2bY/3Znd4/qto4vNMzQtuchsltVZAmbOMAZrvAQY+L7OK0isoG3mCDhE+0CPgu5qUUapUmGOCxgmFOkV1pBu4ReDpHmhLrmv8jct6s3puHnuMBjDpF3jErHtXis33OdrkWs4T5iaVzFQq7tSl/7EvhZ0agQG+YQuUELr+iH3w/einy2y3gbzRZj4nJt1zO6+MAg/EHwbuQri3RQxxOuw78OXo98ZZET1HCHy/Avg9cin+2p/5qAv7+Szix7svXLL3YcUl2qn8qWsYId7OE4LuBR8BRP+VQ31r4BxB63UdWtCsYAAAAASUVORK5CYII=">
                </span> 

                <input type="text" class="form-control input-lg" name="nuevoTelefono" placeholder="Ingresar teléfono" data-inputmask="'mask':'99999999'" data-mask required>

              </div>

            </div>

            <!-- ENTRADA PARA LA DIRECCIÓN -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAACXBIWXMAAAsTAAALEwEAmpwYAAABbklEQVR4nK2WTSsFURjHf7lsKTbiA0jJwku3fAPKgpXylkSy8ta1lh17PoEkScpGsqB0LdhStnRtZOflRkanHnV7OnNe7p1/PZ2m+f/nN3POzHMG/GoH1oEroASUZTTHBaCNGtQAbALvQOIoc34DqI8FNAEXnosnqs4lF6QccBoJSKTOJO/VmiX8BewAg0AvMATsyvpo73LINL2p0BPQleLvBp6V/xVodEGmVOAH6PHcWJ/4KnMTrsC+Mh8QpkOV23OZb5R5NhAyp3JFl/lRmUcCIaMqZ66TqjtlXgqErKjcbcyaXAdCijFrsmB576c9gBlLZt4VaLF8YGWB1ymvOV5M8Tf7Hv0kpWXcA1sy/9vAQ4rvmADlgd8qe1cCDIRAXE+TeOqICHVKU4wBfAIdRKoQCVmlCuVkiw0BXFrevmC1Sqt3AUryH1CT8jLfiaU+gH4y0jDwrQBmDzGNMVNNVmxOZhzPGvCvMeBFxmD9Ac+v9APTJwF8AAAAAElFTkSuQmCC">
                </span> 

                <input type="text" class="form-control input-lg" name="nuevaDireccion" placeholder="Ingresar dirección" required>

              </div>

            </div>
  
          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-"style="background:#6c757d; color:white">Guardar Proveedor</button>

        </div>

      </form>

      <?php

        $crearProveedor = new ControladorProveedors();
        $crearProveedor -> ctrCrearProveedor();

      ?>

    </div>

  </div>

</div>

<!--=====================================
MODAL EDITAR PROVEEDOR
======================================-->

<div id="modalEditarProveedor" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#6c757d; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Editar proveedor</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <!-- ENTRADA PARA EL NOMBRE -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAACXBIWXMAAAsTAAALEwEAmpwYAAAA7ElEQVR4nMXUwSqEURQH8B9Zi2RjOytZDAtLXsGSKWvvIUuyoMGChWnK2htYKmXlHUQRK5GiW3dqFue75Q459a9b5/Trfl+nyz/UBFax/hvYGu7xlXOIdi22jI8hbJB3LNaA5wE2SLcGvCqAlzXgaQHs1YBHBXC/BuwWwIMacBJPAZbWaFplHQfgnhFqLgCnRgHbAdiqgcawggXs4AGP2MU8ln7yEGziLt/mDWf501NO8Jp7t/nBGG/CZnHTsCbbOVHvGjMReFHYu42cpn4/Al8ahj/zDdLupXM08xyBHWwFGX5Y0zmaSf/9b+obeeR7t6oVkbEAAAAASUVORK5CYII=">
                </span> 

                <input type="text" class="form-control input-lg" name="editarProveedor" id="editarProveedor" required>
                <input type="hidden" id="idProveedor" name="idProveedor">
              </div>

            </div>

            <!-- ENTRADA PARA LA EMPRESA -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAmklEQVR4nGNgGCoggYGBoYEIrECsgQcYGBj+MzAwfICysWGQvAOpBh7Ao4YiA/9DNTegieHDB/AZCDJMABpmBkgGFkDl0PECQgbCIsABGmHIrkYWa4CqayBk4AGoy0AaJ6AZiCx2AKqOoIGkRkoDIQNxRQoxYiRFigOamANUHUEDRyNlEESKEwMDQwSUxgVA8mI45HQI6B1EAADBTHKxNASDWwAAAABJRU5ErkJggg==">
                </span> 

                <input type="text" class="form-control input-lg" name="editarEmpresa" id="editarEmpresa" required>

              </div>

            </div>

            <!-- ENTRADA PARA EL TELÉFONO -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAACXBIWXMAAAsTAAALEwEAmpwYAAABH0lEQVR4nO3VsUoDQRAG4E877fI4omBnJwiSLoWVr2GnFlqHNNaSB1CIPoEE7BQ0NtHKQgmCJjbKwgjHYQjkNsHCgZ+df2bY/3Znd4/qto4vNMzQtuchsltVZAmbOMAZrvAQY+L7OK0isoG3mCDhE+0CPgu5qUUapUmGOCxgmFOkV1pBu4ReDpHmhLrmv8jct6s3puHnuMBjDpF3jErHtXis33OdrkWs4T5iaVzFQq7tSl/7EvhZ0agQG+YQuUELr+iH3w/einy2y3gbzRZj4nJt1zO6+MAg/EHwbuQri3RQxxOuw78OXo98ZZET1HCHy/Avg9cin+2p/5qAv7+Szix7svXLL3YcUl2qn8qWsYId7OE4LuBR8BRP+VQ31r4BxB63UdWtCsYAAAAASUVORK5CYII=">
                </span> 

                <input type="text" class="form-control input-lg" name="editarTelefono" id="editarTelefono" data-inputmask="'mask':'99999999'" data-mask required>

              </div>

            </div>

            <!-- ENTRADA PARA LA DIRECCIÓN -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAACXBIWXMAAAsTAAALEwEAmpwYAAABbklEQVR4nK2WTSsFURjHf7lsKTbiA0jJwku3fAPKgpXylkSy8ta1lh17PoEkScpGsqB0LdhStnRtZOflRkanHnV7OnNe7p1/PZ2m+f/nN3POzHMG/GoH1oEroASUZTTHBaCNGtQAbALvQOIoc34DqI8FNAEXnosnqs4lF6QccBoJSKTOJO/VmiX8BewAg0AvMATsyvpo73LINL2p0BPQleLvBp6V/xVodEGmVOAH6PHcWJ/4KnMTrsC+Mh8QpkOV23OZb5R5NhAyp3JFl/lRmUcCIaMqZ66TqjtlXgqErKjcbcyaXAdCijFrsmB576c9gBlLZt4VaLF8YGWB1ymvOV5M8Tf7Hv0kpWXcA1sy/9vAQ4rvmADlgd8qe1cCDIRAXE+TeOqICHVKU4wBfAIdRKoQCVmlCuVkiw0BXFrevmC1Sqt3AUryH1CT8jLfiaU+gH4y0jDwrQBmDzGNMVNNVmxOZhzPGvCvMeBFxmD9Ac+v9APTJwF8AAAAAElFTkSuQmCC">
                </span> 

                <input type="text" class="form-control input-lg" name="editarDireccion" id="editarDireccion"  required>

              </div>

            </div>
  
          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-"style="background:#6c757d; color:white">Guardar Cambios</button>

        </div>

      </form>

      <?php

        $editarProveedor = new ControladorProveedors();
        $editarProveedor -> ctrEditarProveedor();

      ?>

    

    </div>

  </div>

</div>

<?php

  $eliminarProveedor = new ControladorProveedors();
  $eliminarProveedor -> ctrEliminarProveedor();
  $RestaurarProveedor = new ControladorProveedors();
  $RestaurarProveedor -> ctrRestaurarProveedor();
?>

<script>
  $(document).ready(function () {
    $('.dataTables_filter input[type="search"]').attr('placeholder', 'Buscar proveedor');
  });
</script>


