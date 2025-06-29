<?php

if ($_SESSION["perfil"] == "Vendedor") {

  echo '<script>

    window.location = "inicio";

  </script>';

  return;
}

?>




<div class="content-wrapper text-uppercase ">

  <section class="content-header">


    <h1 style="font-family: Arial, sans-serif; font-weight: bold;">
      Administrar productos

    </h1>

    <ol class="breadcrumb">

      <li><a href="inicio"><i class="f-a fa-dashboard"></i> Inicio</a></li>

      <li class="active">Administrar productos</li>

    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">
        <!--  <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarProducto">
    

          Agregar producto

        </button> -->

        <a href="agregar-producto" class="btn btn-primary">
          <i class="fa fa-plus"></i>
          Agregar Producto
        </a>
        &nbsp;

        <a class="btn btn-primary" target="_blank" href="reporte_producto.php">
          <i class="fa fa-print"></i>
          <span class="icon-name"> Imprimir </span>
        </a>
        &nbsp;

        <a class="btn btn-danger" href="productos-eliminados">
          <i class="fa fa-trash"></i>
          <span> Eliminados </span>
        </a>

        <div class="box-body">

          <table class="table table-bordered table-striped dt-responsive tablaProductos text-uppercase " width="100%">

            <thead>

              <tr>

                <th style="width:10px">#</th>
                <th style="width:10px">Imagen</th>
                <th>Código</th>
                <th>Descripcion</th>
                <th>Categoria</th>
                <th>Cantidad</th>
                <th>Precio Compra</th>
                <th>Precio Venta</th>
                <th>Agregado</th>
                <th>Acciones</th>

              </tr>

            </thead>



          </table>
          <input type="hidden" value="<?php echo $_SESSION['perfil']; ?>" id="perfilOculto">
        </div>

      </div>

  </section>

</div>



<!--=====================================
MODAL EDITAR PRODUCTO
======================================-->

<div id="modalEditarProducto" class="modal fade" role="dialog">

  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post" enctype="multipart/form-data">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#6c757d; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Editar producto</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <!-- ENTRADA PARA SELECCIONAR LA CATEGORIA -->
             <input type="hidden" id="idProductoEditar" name="idProductoEditar" required>

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon">CATEGORIA</span>

                <select class="form-control input-lg" name="editarCategoria" readonly required>

                  <option id="editarCategoria"></option>

                </select>

              </div>

            </div>

            <!-- ENTRADA PARA EL CÓDIGO -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon">CODIGO</span>

                <input type="text" class="form-control input-lg" id="editarCodigo" name="editarCodigo" placeholder="Ingresar código" readonly required>

              </div>

            </div>

            <!-- ENTRADA PARA LA DESCRIPCIÓN -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon">DESCRIPCIÓN</span>

                <input type="text" class="form-control input-lg" id="editarDescripcion" name="editarDescripcion" required>

              </div>

            </div>


            <!-- ENTRADA PARA La STOCK -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon">CANTIDAD</span>

                <input class="form-control input-lg" id="editarStock" name="editarStock" min="0" required>

              </div>

            </div>

            <!-- ENTRADA PARA PRECIO COMPRA -->

            <div class="form-group row">
              <div class="col-xs-6">
                <div class="input-group">
                  <span class="input-group-addon">PRECIO_COMPRA</span>

                  <input type="number" class="form-control input-lg" id="editarPrecioCompra" name="editarPrecioCompra" min="0" step="any" placeholder="Precio Compra" required>
                </div>

              </div>

              <!-- ENTRADA PARA PRECIO VENTA -->

              <div class="col-xs-6">

                <div class="input-group">
                  <span class="input-group-addon">PRECIO_VENTA</span>

                  <input type="number" class="form-control input-lg" id="editarPrecioVenta" name="editarPrecioVenta" min="0" step="any" placeholder="Precio Venta" required>

                </div>

              </div>

            </div>

            <div class=" form-group row">
              <div class="col-md-6">
                <div class="input-group">
                  <span class="input-group-addon">ES INVETARIABLE?</span>
                  <select class="form-control input-lg" id="editarInventariable" name="editarInventariable" required>
                    <option value="" disabled>Es inventariable:</option>
                    <option value="0">NO</option>
                    <option value="1" selected>SI</option>
                  </select>
                </div>
              </div>

              <div class="col-md-6">
                <!-- CHECKBOX PARA PORCENTAJE -->
                <div class="col-xs-6">
                  <div class="form-group">
                    <label>
                      <input type="checkbox" class="minimal porcentaje">
                      Utilizar porcentaje
                      (Opcional)
                    </label>
                  </div>
                </div>

                <!-- ENTRADA PARA PORCENTAJE -->
                <div class="col-xs-6" style="padding:0">
                  <div class="input-group">
                    <input type="number" class="form-control input-lg nuevoPorcentaje" min="0" value="0" required>
                    <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                  </div>
                </div>
              </div>

            </div>
            <!-- ENTRADA PARA SUBIR FOTO -->

            <div class="form-group">

              <div class="panel">SUBIR IMAGEN</div>

              <input type="file" class="nuevaImagen" name="editarImagen">

              <p class="help-block">Peso máximo de la imagen 2MB. Formatos permitidos: JPG, PNG y WEBP</p>

              <img src="vistas/img/productos/default/anonymous.webp" class="img-thumbnail previsualizar" width="100px">

              <input type="hidden" name="imagenActual" id="imagenActual">

            </div>

          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-" style="background:#6c757d; color:white">Guardar cambios</button>

        </div>

      </form>

      <?php


      $editarProducto = new ControladorProductos();
      $editarProducto->ctrEditarProducto();



      ?>

    </div>

  </div>

</div>

<?php

$eliminarProducto = new ControladorProductos();
$eliminarProducto->ctrEliminarProducto();
$restaurarProducto = new ControladorProductos();
$restaurarProducto->ctrRestaurarProducto();

?>

<script>
    // Obtener los elementos del DOM
    const inventariableSelect = document.getElementById('editarInventariable');
    const stockInput = document.getElementById('editarStock');

    // Escuchar el evento 'change' del select
    inventariableSelect.addEventListener('change', function () {
        if (this.value === "0") { // Si selecciona "NO"
            stockInput.removeAttribute('readonly'); // Activar el campo
        } else { // Si selecciona "SI"
            stockInput.setAttribute('readonly', true); // Deshabilitar el campo
        }
    });
</script>
<script>
// Manejo del porcentaje en el modal de editar producto
// Se asegura de que funcione correctamente al abrir el modal
$(document).ready(function() {
    const checkPorcentajeEditar = document.querySelector('#modalEditarProducto .porcentaje');
    const precioCompraEditar = document.getElementById('editarPrecioCompra');
    const precioVentaEditar = document.getElementById('editarPrecioVenta');
    const porcentajeInputEditar = document.querySelector('#modalEditarProducto .nuevoPorcentaje');

    function calcularPrecioVentaEditar() {
        if (checkPorcentajeEditar && checkPorcentajeEditar.checked) {
            let porcentaje = parseFloat(porcentajeInputEditar.value);
            let precioCompraValor = parseFloat(precioCompraEditar.value);
            if (!isNaN(precioCompraValor) && !isNaN(porcentaje)) {
                let nuevoPrecioVenta = precioCompraValor + (precioCompraValor * porcentaje / 100);
                precioVentaEditar.value = nuevoPrecioVenta.toFixed(2);
            }
        }
    }

    function actualizarReadonlyPrecioVentaEditar() {
        if (checkPorcentajeEditar && checkPorcentajeEditar.checked) {
            precioVentaEditar.setAttribute('readonly', true);
        } else if (precioVentaEditar) {
            precioVentaEditar.removeAttribute('readonly');
        }
    }

    if (checkPorcentajeEditar && precioCompraEditar && precioVentaEditar && porcentajeInputEditar) {
        checkPorcentajeEditar.addEventListener('change', function() {
            actualizarReadonlyPrecioVentaEditar();
            calcularPrecioVentaEditar();
        });
        precioCompraEditar.addEventListener('input', calcularPrecioVentaEditar);
        porcentajeInputEditar.addEventListener('input', calcularPrecioVentaEditar);

        // Al abrir el modal, actualizar el estado
        $('#modalEditarProducto').on('shown.bs.modal', function () {
            actualizarReadonlyPrecioVentaEditar();
            calcularPrecioVentaEditar();
        });
    }
});
</script>