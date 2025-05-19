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
                                <div class="col-md-4 text-right">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="button" class="btn btn-primary btn-block" onclick="GanaciasgeneratePDF()">
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
                                <div class="col-md-4 text-right">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="button" class="btn btn-primary btn-block" onclick="GanaciasgeneratePDFYear()">
                                            <i class="fa fa-print"></i> Generar PDF
                                        </button>
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
</script>
