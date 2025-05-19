
<?php
// Establecer la zona horaria de Bolivia
date_default_timezone_set('America/La_Paz');

// Obtener la fecha y hora actual en Bolivia
$fechaActual = date('Y-m-d');
?>

<div class="content-wrapper text-uppercase">

    <section class="content-header">
        <h1>REPORTE DE CATEGORIA SEGUN PRODUCTO</h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Reporte según Categoria</li>
        </ol>
    </section>

    <section class="content">

        <div class="box">
            <div class="box-body">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title" style="text-align: center;">FILTRAR REPORTE</h4>
                    </div>
                    <div class="panel-body">
                        <input type="hidden" id="id_usuario" name="id_usuario" value="<?php echo $_SESSION['id']; ?>">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><i class="text-danger">*</i> Categoria</label>
                                    <select class="select2" class="form-control text-uppercase" id="id_categoria" name="id_categoria" required>
                                        <option value="0">Todas</option>
                                        <?php
                                            $item = null;
                                            $valor = null;
                                            $categorias = ControladorCategorias::ctrMostrarCategorias($item, $valor);
                                            foreach ($categorias as $key => $value) {
                                                echo '<option value="'.$value["id"].'">'.$value["categoria"].'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group" style="margin-top: 24px;">
                                    <button type="button" class="btn btn-primary btn-block" onclick="generateProductoCategoriaPDF()">
                                        <i class="fa fa-print"></i> Generar PDF
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div><!--/body panel-->
                    <img src="vistas/img/plantilla/3.webp" class="responsive-image" style="display: block; margin: 0 auto; max-width: 100%; height: auto; object-fit: contain;">

                </div><!--/PANEL FIN-->
            </div><!--/box body-->
        </div><!--/box-->

    </section>

</div>

<script>
    var popupWindow = null;

    function generateProductoCategoriaPDF() {
        const idCategoria = document.getElementById('id_categoria').value;
        const idUsuario = document.getElementById('id_usuario').value;

        const width = 800;
        const height = 600;
        const left = (screen.width / 2) - (width / 2);
        const top = (screen.height / 2) - (height / 2);
        const windowFeatures = `menubar=no,toolbar=no,status=no,width=${width},height=${height},left=${left},top=${top}`;

        if (popupWindow && !popupWindow.closed) {
            popupWindow.close();
        }

        popupWindow = window.open(
            "extensiones/tcpdf/pdf/reporte-categoria.php?idCategoria=" + encodeURIComponent(idCategoria) +
            "&idUsuario=" + encodeURIComponent(idUsuario),
            "_blank",
            windowFeatures
        );
    }
</script>
