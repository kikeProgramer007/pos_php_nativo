<?php


$report = new ControladorReportes();

$item = null;
$valor = null;
$datos = $report -> ctrGananciasMesesAnios();

// Array de nombres de meses en español
$mesesEspanol = [
    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 
    6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 
    10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
];
?>

<div class="content-wrapper text-uppercase">

    <section class="content-header">
        <h1>Reportes de Ganancias</h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Reportes de Ganancias</li>
        </ol>
    </section>

    <section class="content">

        <div class="box">
            <div class="box-body">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title" style="text-align: center;">GANANCIAS POR MES</h4>
                    </div>
                    <div class="panel-body">
                        <form id="report-form">
                            <input type="hidden" id="id_usuario" name="id_usuario" value="<?php echo $_SESSION['id']; ?>">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><i class="text-danger">*</i> Mes:</label>
                                        <select class="form-control" id="month" name="month" required>
                                            <option value="" disabled selected>Seleccionar mes</option>
                                            <?php foreach ($datos['meses'] as $mes) { ?>
                                                <option value="<?php echo $mes['mes']; ?>">
                                                    <?php echo $mesesEspanol[$mes['mes']]; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><i class="text-danger">*</i> Año:</label>
                                        <select id="year" name="year" class="form-control" required>
                                            <option value="" disabled selected>Seleccionar año</option>
                                            <?php foreach ($datos['years'] as $year) { ?>
                                                <option value="<?php echo $year['years']; ?>"><?php echo $year['years']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <div class="row">
                                            <div class="col-xs-6" style="padding-right:5px;">
                                                <button type="button" class="btn btn-primary btn-block" onclick="GanaciasgeneratePDF()">
                                                    <i class="fa fa-print"></i> PDF
                                                </button>
                                            </div>
                                            <div class="col-xs-6" style="padding-left:5px;">
                                                <button type="button" class="btn btn-success btn-block" onclick="GananciasgenerateExcelMes()">
                                                    <i class="fa fa-file-excel-o"></i> Excel
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>

        <div class="box mt-3">
            <div class="box-body">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title" style="text-align: center;">GANANCIAS POR AÑO</h4>
                    </div>
                    <div class="panel-body">
                        <form id="year-report-form">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><i class="text-danger">*</i> Año inicio:</label>
                                        <select class="form-control" id="startyear" name="startyear" required>
                                            <option value="" disabled selected>Seleccionar año inicio</option>
                                            <?php foreach ($datos['years'] as $year) { ?>
                                                <option value="<?php echo $year['years']; ?>"><?php echo $year['years']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><i class="text-danger">*</i> Año fin:</label>
                                        <select class="form-control" id="endyear" name="endyear" required>
                                            <option value="" disabled selected>Seleccionar año fin</option>
                                            <?php foreach ($datos['years'] as $year) { ?>
                                                <option value="<?php echo $year['years']; ?>"><?php echo $year['years']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <div class="row">
                                            <div class="col-xs-6" style="padding-right:5px;">
                                                <button type="button" class="btn btn-primary btn-block" onclick="GanaciasgeneratePDFYear()">
                                                    <i class="fa fa-print"></i> PDF
                                                </button>
                                            </div>
                                            <div class="col-xs-6" style="padding-left:5px;">
                                                <button type="button" class="btn btn-success btn-block" onclick="GananciasgenerateExcelYear()">
                                                    <i class="fa fa-file-excel-o"></i> Excel
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="box mt-3">
            <div class="box-body">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title" style="text-align: center;">GANANCIAS ENTRE FECHAS</h4>
                    </div>
                    <div class="panel-body">
                        <form id="fechas-report-form">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><i class="text-danger">*</i> Fecha inicio:</label>
                                        <input type="date" class="form-control" id="fecha_inicio_ganancia" name="fecha_inicio_ganancia" value="<?php echo date('Y-m-d'); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><i class="text-danger">*</i> Fecha fin:</label>
                                        <input type="date" class="form-control" id="fecha_fin_ganancia" name="fecha_fin_ganancia" value="<?php echo date('Y-m-d'); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <div class="row">
                                            <div class="col-xs-6" style="padding-right:5px;">
                                                <button type="button" class="btn btn-primary btn-block" onclick="GanaciasgeneratePDFFechas()">
                                                    <i class="fa fa-print"></i> PDF
                                                </button>
                                            </div>
                                            <div class="col-xs-6" style="padding-left:5px;">
                                                <button type="button" class="btn btn-success btn-block" onclick="GananciasgenerateExcelFechas()">
                                                    <i class="fa fa-file-excel-o"></i> Excel
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <img src="vistas/img/plantilla/4.webp" class="responsive-image" style="display: block; margin: 0 auto; max-width: 100%; height: auto; object-fit: contain;">
                </div>
            </div>
        </div>

    </section>
</div>

<script>
    var popupWindow = null;
    const fechaActualGanancia = "<?php echo date('Y-m-d'); ?>";
    (function () {
        var fi = document.getElementById('fecha_inicio_ganancia');
        var ff = document.getElementById('fecha_fin_ganancia');
        if (fi) fi.setAttribute('max', fechaActualGanancia);
        if (ff) ff.setAttribute('max', fechaActualGanancia);
    })();

    function GanaciasgeneratePDF() {
        const mes = document.getElementById('month').value;
        const anio = document.getElementById('year').value;
        const idUsuario = document.getElementById('id_usuario').value;

        if (!mes || !anio) {
            swal({
                icon: 'warning',
                title: 'Advertencia',
                text: 'Por favor, seleccione un Mes y un Año.',
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
            "extensiones/tcpdf/pdf/pdf-ganancias.php?month=" + encodeURIComponent(mes) +
            "&year=" + encodeURIComponent(anio) +
            "&idUsuario=" + encodeURIComponent(idUsuario),
            "_blank",
            windowFeatures
        );
    }

    function GananciasgenerateExcelMes() {
        const mes = document.getElementById('month').value;
        const anio = document.getElementById('year').value;
        const idUsuario = document.getElementById('id_usuario').value;

        if (!mes || !anio) {
            swal({
                icon: 'warning',
                title: 'Advertencia',
                text: 'Por favor, seleccione un Mes y un Año.',
            });
            return;
        }

        window.location.href =
            "extensiones/excel/ganancias-mes.php?month=" + encodeURIComponent(mes) +
            "&year=" + encodeURIComponent(anio) +
            "&idUsuario=" + encodeURIComponent(idUsuario);
    }

    function GanaciasgeneratePDFYear() {
        const startyear = document.getElementById('startyear').value;
        const endyear = document.getElementById('endyear').value;
        const idUsuario = document.getElementById('id_usuario').value;

        if (!startyear || !endyear || (startyear > endyear)) {
            swal({
                icon: 'warning',
                title: 'Advertencia',
                text: 'Por favor, seleccione un rango de años correcto.',
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
            "extensiones/tcpdf/pdf/pdf-ganancias-year.php?yearini=" + encodeURIComponent(startyear) +
            "&yearfin=" + encodeURIComponent(endyear) +
            "&idUsuario=" + encodeURIComponent(idUsuario),
            "_blank",
            windowFeatures
        );
    }

    function GananciasgenerateExcelYear() {
        const startyear = document.getElementById('startyear').value;
        const endyear = document.getElementById('endyear').value;
        const idUsuario = document.getElementById('id_usuario').value;

        if (!startyear || !endyear || (startyear > endyear)) {
            swal({
                icon: 'warning',
                title: 'Advertencia',
                text: 'Por favor, seleccione un rango de años correcto.',
            });
            return;
        }

        window.location.href =
            "extensiones/excel/ganancias-year.php?yearini=" + encodeURIComponent(startyear) +
            "&yearfin=" + encodeURIComponent(endyear) +
            "&idUsuario=" + encodeURIComponent(idUsuario);
    }

    function validarFechasGanancia() {
        const fechaInicio = document.getElementById('fecha_inicio_ganancia').value;
        const fechaFin = document.getElementById('fecha_fin_ganancia').value;

        if (!fechaInicio || !fechaFin) {
            swal({
                icon: 'warning',
                title: 'Advertencia',
                text: 'Por favor, seleccione fecha inicio y fecha fin.',
            });
            return null;
        }
        if (fechaFin > fechaActualGanancia) {
            swal({
                icon: 'warning',
                title: 'Advertencia',
                text: 'La fecha fin no puede ser mayor a la fecha actual: ' + fechaActualGanancia,
            });
            return null;
        }
        if (fechaInicio > fechaFin) {
            swal({
                icon: 'warning',
                title: 'Advertencia',
                text: 'La fecha de inicio debe ser menor o igual a la fecha fin.',
            });
            return null;
        }
        return { fechaInicio: fechaInicio, fechaFin: fechaFin };
    }

    function GanaciasgeneratePDFFechas() {
        const fechas = validarFechasGanancia();
        if (!fechas) return;
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
            "extensiones/tcpdf/pdf/pdf-ganancias-fechas.php?fechaInicio=" + encodeURIComponent(fechas.fechaInicio) +
            "&fechaFin=" + encodeURIComponent(fechas.fechaFin) +
            "&idUsuario=" + encodeURIComponent(idUsuario),
            "_blank",
            windowFeatures
        );
    }

    function GananciasgenerateExcelFechas() {
        const fechas = validarFechasGanancia();
        if (!fechas) return;
        const idUsuario = document.getElementById('id_usuario').value;

        window.location.href =
            "extensiones/excel/ganancias-fechas.php?fechaInicio=" + encodeURIComponent(fechas.fechaInicio) +
            "&fechaFin=" + encodeURIComponent(fechas.fechaFin) +
            "&idUsuario=" + encodeURIComponent(idUsuario);
    }
</script>
