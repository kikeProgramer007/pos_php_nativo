<?php

if ($_SESSION["perfil"] == "") {

  echo '<script>

    window.location = "inicio";

  </script>';

  return;
}

?>


<div class="content-wrapper  text-uppercase ">

  <section class="content-header">
    <h1 style="font-family: Arial, sans-serif; font-weight: bold;">

      Administrar ventas


    </h1>

    <ol class="breadcrumb">

      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio </a></li>

      <li class="active">Administrar ventas</li>

    </ol>

  </section>

  <section class="content">
    <input type="hidden" value="<?php echo $_SESSION['perfil']; ?>" id="perfilOculto">
    <div class="box">

      <div class="box-header with-border">

        <a href="crear-venta">
          <button class="btn btn-primary">
            <i class="fa fa-plus"></i>
            Agregar venta
          </button>
        </a>

        <a class="btn btn-danger" href="ventas-eliminadas">
          <i class="fa fa-trash"></i>
          <span> Eliminados </span>
        </a>

        <button type="button" class="btn btn-default pull-right" id="daterange-btn">

          <span>
            <i class="fa fa-calendar"></i> Rango de fecha
          </span>

          <i class="fa fa-caret-down"></i>

        </button>

      </div>

      <div class="box-body">

        <table class="table table-bordered table-striped dt-responsive tablas text-uppercase tablaVentasRealizadas " width="100%">

          <thead>

            <tr>

              <th style="width:10px">#</th>
              <th>N° TICKET</th>
              <th>Meseros</th>
              <th>Usuario</th>
              <th>Total</th>
              <th>Fecha</th>
              <th>Acciones</th>


            </tr>

          </thead>

          <tbody>

            <?php

            /*    if(isset($_GET["fechaInicial"])){

                $fechaInicial = $_GET["fechaInicial"];
                $fechaFinal = $_GET["fechaFinal"];

              }else{

                $fechaInicial = null;
                $fechaFinal = null;

              }

          $respuesta = ControladorVentas::ctrRangoFechasVentas($fechaInicial, $fechaFinal);


          foreach ($respuesta as $key => $value) {
           
           echo '<tr>

                  <td>'.($key+1).'</td>

                  <td>'.$value["codigo"].'</td>';

                  $itemMesero = "id";
                  $valorMesero = $value["id_mesero"];

                  $respuestaMesero = ControladorMeseros::ctrMostrarMeseros($itemMesero, $valorMesero);

                  echo '<td>'.$respuestaMesero["nombre"].'</td>';

                  $itemUsuario = "id";
                  $valorUsuario = $value["id_vendedor"];

                  $respuestaUsuario = ControladorUsuarios::ctrMostrarUsuarios($itemUsuario, $valorUsuario);

                  echo '<td>'.$respuestaUsuario["nombre"].'</td>

                

                 

                  <td>Bs '.number_format($value["total"],2).'</td>

                  <td>'.$value["fecha"].'</td>

                  <td>

                    <div class="btn-group">';
                      
                      echo '<button class="btn btn-info btnImprimirFactura" codigoVenta="'.$value["codigo"].'">
                        <i class="fa fa-print"></i>
                      </button>';
                      
                      // Comprobar el rol del usuario
                      if ($_SESSION["perfil"] == "Administrador") {
                        
                        echo '<button class="btn btn-danger btnEliminarVenta" idVenta="'.$value["id"].'"><i class="fa fa-times"></i></button>';
                      }

                    echo '</div>  

                  </td>

                </tr>';
            }
 */
            ?>

          </tbody>

        </table>

        <?php

        $eliminarVenta = new ControladorVentas();
        $eliminarVenta->ctrEliminarVenta();

        ?>

      </div>

    </div>

  </section>

</div>