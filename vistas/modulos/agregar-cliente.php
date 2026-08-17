<?php
if (!Permisos::tiene("clientes.crear")) {
  echo '<script>window.location = "no-autorizado";</script>';
  return;
}
?>
<div class="content-wrapper text-uppercase">
    <!-- Header -->
    <section class="content-header">
        <h1>AGREGAR CLIENTES</h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>

            <li class="active">AGREGAR CLIENTES</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="box">
            <div class="box-body">
                <form role="form" method="post">
                    <div class="row">
                        <!-- ENTRADA PARA EL NOMBRE -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <h4><strong>NOMBRE</strong></h4>
                                <input type="text" class="form-control input-lg" name="nuevoCliente" id="nuevoCliente" placeholder="Ingresar Nombre" required>
                            </div>
                        </div>
                    
                    </div>
              
                    <!-- Botones -->
                    <div class="text-right">
                        <button type="submit" class="btn btn-success btn-sm">GUARDAR</button>&nbsp;
                        <a href="clientes"  class="btn btn- btn-sm" style="background:#6c757d; color:white">REGRESAR</a>
                    </div>
                    <img src="vistas/img/plantilla/cliente.jpg" class="responsive-image" style="display: block; margin: 0 auto; max-width: 100%; height: auto; object-fit: contain;">
                    <!-- Controlador para crear el cliente -->
                    <?php
                        $crearCliente = new ControladorClientes();
                        $crearCliente->ctrCrearCliente();
                    ?>
                </form>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </section>
</div>
