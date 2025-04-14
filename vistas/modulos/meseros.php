
<?php

if($_SESSION["perfil"] == "Especial"){

  echo '<script>

    window.location = "inicio";

  </script>';

  return;

}

?>



<div class="content-wrapper  text-uppercase ">

  <section class="content-header">
    
    <h1 style="font-family: Arial, sans-serif; font-weight: bold;">
      Administrar Meseros
    </h1>

    <ol class="breadcrumb">
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active">Administrar Meseros</li>
    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">
      <!--   <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarMesero">
          Agregar Meseros
        </button> -->
                <?php
       if($_SESSION["perfil"] ==  'Administrador') {
            echo '<a href="agregar-mesero" class="btn btn-primary">
                <i class="fa fa-plus"></i>
                Agregar Meseros
            </a>';
        }
        ?>



&nbsp;

        <a class="btn btn-primary" target="_blank" href="reporte_mesero.php">
        <i class="fa fa-print"></i>
            <i class="material-icons"></i>
            
            <span class="icon-name"> Imprimir </span>
              </a>


              &nbsp;
              <a class="btn btn-danger" href="meseros-eliminados">
            <i class="fa fa-trash"></i>
            <span> Eliminados </span>
        </a>

      </div>

      <div class="box-body">
        <table class="table table-bordered table-striped dt-responsive text-uppercase tablasmesero " width="100%">
          <thead>
            <tr>
              <th style="width:10px">#</th>
              <th>Nombre</th>
              <th>N° De Carnet</th>
              <th>Teléfono</th>
              <th>Dirección</th>
              <th>Total de ventas</th>
              <th>Ingreso al sistema</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <input type="hidden" value="<?php echo $_SESSION['perfil']; ?>" id="perfilOculto">
        </table>
      </div>
    </div>

  </section>

</div>

<!--=====================================
MODAL AGREGAR MESERO
======================================-->
<div id="modalAgregarMesero" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <form role="form" method="post">
        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->
        <div class="modal-header" style="background:#6c757d; color:white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Agregar Meseros</h4>
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
                <input type="text" class="form-control input-lg" name="nuevoMesero" id="nuevoMesero" placeholder="Ingresar Nombre" required>
              </div>
            </div>
            <!-- ENTRADA PARA EL CI/NIT -->
            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon">
                  <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAACXBIWXMAAAsTAAALEwEAmpwYAAABQklEQVR4nO3UPUoDURQF4A8bQYm/VTobcQMuwDbaxeAK1C2kFhUUJBYBdQEK7kAsUpgtiIXaGS0EQdEFRB48wzBM0JgZsPDAYX7OnXfeve/O5R9/FcvooJsjO6gkTfI26EY+JE26BbKHfgGXOMBTUSbthD6KqyJMTpIBOEzp87IxP4jJWerj8yIyaaVM2in9CHsZPB704KtRX83QGqhnsDGoSTPqzSK66xU7mI56uO7iLa+Dv0W5zwJl3OWRSbtPreuR6QboxrFUQymymtjMt2fyE3Ywk8p4BAt4xFIeA7KWUdZ9nGINF18vK3Fi/sakhDmMxbXWcYMpTOLZkHjHBLZwjc242WAqmoSYodBK/LAbeMFiQq9lTI6BsRJbfjY+jye00Az3MWZobMcuCoccShcYMggGQcsNYbehLB+R4b6XwSerJzNlafpJlwAAAABJRU5ErkJggg==">
                </span>
                
                <input type="text" class="form-control input-lg" name="nuevoDocumentoId" placeholder="Ingresar N° De Carnet" required>
              </div>
            </div>


                 <!-- ENTRADA PARA EL TELÉFONO -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAACXBIWXMAAAsTAAALEwEAmpwYAAABH0lEQVR4nO3VsUoDQRAG4E877fI4omBnJwiSLoWVr2GnFlqHNNaSB1CIPoEE7BQ0NtHKQgmCJjbKwgjHYQjkNsHCgZ+df2bY/3Znd4/qto4vNMzQtuchsltVZAmbOMAZrvAQY+L7OK0isoG3mCDhE+0CPgu5qUUapUmGOCxgmFOkV1pBu4ReDpHmhLrmv8jct6s3puHnuMBjDpF3jErHtXis33OdrkWs4T5iaVzFQq7tSl/7EvhZ0agQG+YQuUELr+iH3w/einy2y3gbzRZj4nJt1zO6+MAg/EHwbuQri3RQxxOuw78OXo98ZZET1HCHy/Avg9cin+2p/5qAv7+Szix7svXLL3YcUl2qn8qWsYId7OE4LuBR8BRP+VQ31r4BxB63UdWtCsYAAAAASUVORK5CYII=">
                
                </span> 

                <input type="text" class="form-control input-lg" name="nuevoTelefono" placeholder="Ingresar teléfono" data-inputmask="'mask':' 999-99-999'" data-mask required>


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
          <button type="submit" class="btn btn-"style="background:#6c757d; color:white">Guardar Mesero</button>
        </div>
        <?php
          $crearMesero = new ControladorMeseros();
          $crearMesero -> ctrCrearMesero();
        ?>
      </form>
    </div>
  </div>
</div>

<!--=====================================
MODAL EDITAR Mesero
======================================-->
<div id="modalEditarMesero" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <form role="form" method="post">
        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->
        <div class="modal-header" style="background:#6c757d; color:white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Editar Meseros</h4>
        </div>
        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->
        <div class="modal-body">
          <div class="box-body">
            <!-- ENTRADA PARA EL NOMBRE -->
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAACXBIWXMAAAsTAAALEwEAmpwYAAAA7ElEQVR4nMXUwSqEURQH8B9Zi2RjOytZDAtLXsGSKWvvIUuyoMGChWnK2htYKmXlHUQRK5GiW3dqFue75Q459a9b5/Trfl+nyz/UBFax/hvYGu7xlXOIdi22jI8hbJB3LNaA5wE2SLcGvCqAlzXgaQHs1YBHBXC/BuwWwIMacBJPAZbWaFplHQfgnhFqLgCnRgHbAdiqgcawggXs4AGP2MU8ln7yEGziLt/mDWf501NO8Jp7t/nBGG/CZnHTsCbbOVHvGjMReFHYu42cpn4/Al8ahj/zDdLupXM08xyBHWwFGX5Y0zmaSf/9b+obeeR7t6oVkbEAAAAASUVORK5CYII="></span>
                <input type="text" class="form-control input-lg" name="editarMesero" id="editarMesero" required>
                <input type="hidden" id="idMesero" name="idMesero" id="nuevoMesero">
              </div>
            </div>
            <!-- ENTRADA PARA EL CI/NIT -->
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAACXBIWXMAAAsTAAALEwEAmpwYAAABQklEQVR4nO3UPUoDURQF4A8bQYm/VTobcQMuwDbaxeAK1C2kFhUUJBYBdQEK7kAsUpgtiIXaGS0EQdEFRB48wzBM0JgZsPDAYX7OnXfeve/O5R9/FcvooJsjO6gkTfI26EY+JE26BbKHfgGXOMBTUSbthD6KqyJMTpIBOEzp87IxP4jJWerj8yIyaaVM2in9CHsZPB704KtRX83QGqhnsDGoSTPqzSK66xU7mI56uO7iLa+Dv0W5zwJl3OWRSbtPreuR6QboxrFUQymymtjMt2fyE3Ywk8p4BAt4xFIeA7KWUdZ9nGINF18vK3Fi/sakhDmMxbXWcYMpTOLZkHjHBLZwjc242WAqmoSYodBK/LAbeMFiQq9lTI6BsRJbfjY+jye00Az3MWZobMcuCoccShcYMggGQcsNYbehLB+R4b6XwSerJzNlafpJlwAAAABJRU5ErkJggg=="></span>
                <input type="text" class="form-control input-lg" name="editarDocumentoId" id="editarDocumentoId" required>
              </div>
            </div>

               <!-- ENTRADA PARA EL TELÉFONO -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAACXBIWXMAAAsTAAALEwEAmpwYAAABH0lEQVR4nO3VsUoDQRAG4E877fI4omBnJwiSLoWVr2GnFlqHNNaSB1CIPoEE7BQ0NtHKQgmCJjbKwgjHYQjkNsHCgZ+df2bY/3Znd4/qto4vNMzQtuchsltVZAmbOMAZrvAQY+L7OK0isoG3mCDhE+0CPgu5qUUapUmGOCxgmFOkV1pBu4ReDpHmhLrmv8jct6s3puHnuMBjDpF3jErHtXis33OdrkWs4T5iaVzFQq7tSl/7EvhZ0agQG+YQuUELr+iH3w/einy2y3gbzRZj4nJt1zO6+MAg/EHwbuQri3RQxxOuw78OXo98ZZET1HCHy/Avg9cin+2p/5qAv7+Szix7svXLL3YcUl2qn8qWsYId7OE4LuBR8BRP+VQ31r4BxB63UdWtCsYAAAAASUVORK5CYII=">
                </span> 

                <input type="text" class="form-control input-lg" name="editarTelefono" id="editarTelefono"data-inputmask="'mask':' 999-99-999'" data-mask required>

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
          <button type="submit" class="btn btn-"style="background:#6c757d; color:white">Guardar Mesero</button>
        </div>
        <?php
          $editarMesero = new ControladorMeseros();
          $editarMesero -> ctrEditarMesero();
        ?>
      </form>
    </div>
  </div>
</div>

<?php
  $eliminarMesero = new ControladorMeseros();
  $eliminarMesero -> ctrEliminarMesero();


  //sexto paso
  $RestaurarMesero = new ControladorMeseros();
  $RestaurarMesero -> ctrRestaurarMesero();
?>
