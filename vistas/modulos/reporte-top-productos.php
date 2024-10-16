

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
                                        <input type="date" id="fecha_fin" name="fecha_fin" value="<?php echo date('Y-m-t'); ?>" class="form-control" required>
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
                </div>
            </div>
        </div>

    </section>

</div>

<script>
    var popupWindow = null;

    function generatePDF() {
        const fechaInicio = document.getElementById('fecha_inicio').value;
        const fechaFin = document.getElementById('fecha_fin').value;
        const idUsuario = document.getElementById('id_usuario').value;

        const fechaInicioObj = new Date(fechaInicio);
        const fechaFinObj = new Date(fechaFin);

        if (fechaInicioObj > fechaFinObj) {
            swal({
                icon: 'warning',
                title: 'Advertencia',
                text: 'Por favor, seleccione una fecha de inicio menor a la fecha fin.',
            });
            return;
        }

        if (!fechaInicio || !fechaFin) {
            swal({
                icon: 'warning',
                title: 'Advertencia',
                text: 'Por favor, seleccione las fechas requeridas.',
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
            "extensiones/tcpdf/pdf/top-productos-mas-vendidos.php?fechaInicio=" + encodeURIComponent(fechaInicio) +
            "&fechaFin=" + encodeURIComponent(fechaFin) +
            "&idUsuario=" + encodeURIComponent(idUsuario),
            "_blank",
            windowFeatures
        );
    }
</script>