
<?php
// Establecer la zona horaria de Bolivia
date_default_timezone_set('America/La_Paz');

// Obtener la fecha y hora actual en Bolivia
$fechaActual = date('Y-m-d');
?>


<div class="content-wrapper text-uppercase">

    <section class="content-header">
        <h1>REPORTE DE PRODUCTOS MAS VENDIDOS</h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Top Producto Más Vendidos</li>
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
                        <form id="report-form">
                            <input type="hidden" id="id_usuario" name="id_usuario" value="<?php echo $_SESSION['id']; ?>">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><i class="text-danger">*</i> Fecha de inicio:</label>
                                        <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?php echo date('Y-m-01'); ?>" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><i class="text-danger">*</i> Fecha de fin:</label>
                                        <input type="date" id="fecha_fin" name="fecha_fin" value="<?php echo date('Y-m-d'); ?>" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-4 text-right">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="button" class="btn btn-primary btn-block" onclick="generatePDF()">
                                            <i class="fa fa-print"></i> Generar PDF
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <img src="vistas/img/plantilla/cat.webp" class="responsive-image" style="display: block; margin: 0 auto; max-width: 100%; height: auto; object-fit: contain;">
                </div>
            </div>
        </div>

    </section>

</div>

<script>
    // Asignar la fecha actual a una variable global en JavaScript
const fechaActual = "<?php echo $fechaActual; ?>";
const fechaInicio = document.getElementById('fecha_inicio');
const fechaFin = document.getElementById('fecha_fin');
fechaInicio.setAttribute('max', fechaActual);
fechaFin.setAttribute('max', fechaActual);

    var popupWindow = null;

    function generatePDF() {

        const idUsuario = document.getElementById('id_usuario').value;




        if (!fechaInicio.value || !fechaFin.value) {
            swal({
                icon: 'warning',
                title: 'Advertencia',
                text: 'Por favor, seleccione las fechas requeridas.',
            });
            return;
        }
        if (fechaFin.value > fechaActual) {
            swal({
                icon: 'warning',
                title: 'Advertencia',
                text: 'Por favor, la fecha fin seleccionada no puede ser mayor a la fecha actual: ' + fechaActual,
            });
            return;
        }
        if (fechaInicio.value > fechaFin.value) {
            swal({
                icon: 'warning',
                title: 'Advertencia',
                text: 'Por favor, seleccione una fecha de inicio menor a la fecha fin.',
            });
            return;
        }

        const width = 800;
        const height = 600;
        const left = (screen.width / 2) - (width / 2);
        const top = (screen.height / 2) - (height / 2);
        const windowFeatures = `menubar=no,toolbar=no,status=no,width=${width},height=${height},left=${left},top=${top}`;

        if (popupWindow && !popupWindow.closed) {
            popupWindow.close();
        }

        popupWindow = window.open(
            "extensiones/tcpdf/pdf/top-productos-mas-vendidos.php?fechaInicio=" + encodeURIComponent(fechaInicio.value) +
            "&fechaFin=" + encodeURIComponent(fechaFin.value) +
            "&idUsuario=" + encodeURIComponent(idUsuario),
            "_blank",
            windowFeatures
        );
    }
</script>
