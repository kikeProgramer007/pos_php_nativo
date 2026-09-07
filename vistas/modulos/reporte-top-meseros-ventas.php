
<?php
// Establecer la zona horaria de Bolivia
date_default_timezone_set('America/La_Paz');

// Obtener la fecha y hora actual en Bolivia
$fechaActual = date('Y-m-d');
?>

<div class="content-wrapper text-uppercase">

    <section class="content-header">
        <h1>REPORTE DE MESEROS CON MAS VENTAS</h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Top Ventas por Meseros</li>
        </ol>
    </section>

    <section class="content">

        <div class="box">



            <div class="box-body">

                <div class="card card-secondary card-outline">
                    <div class="card-body">
                        <input type="hidden" id="id_usuario" name="id_usuario" value="<?php echo $_SESSION["id"]; ?>">
                        <div class="row">
                            <div class="col-12 col-sm-2">
                                <div class="form-group">
                                    <label><i class="text-danger">*</i> Fecha de inicio:</label>
                                    <div class="input-group date">
                                        <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?php echo date('Y-m-d'); ?>" class="form-control" required />
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-2">
                                <div class="form-group">
                                    <label><i class="text-danger">*</i> Fecha de fin:</label>
                                    <div class="input-group date">
                                        <input type="date" id="fecha_fin" name="fecha_fin" value="<?php echo date('Y-m-d'); ?>" class="form-control" required />
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-3">
                                <div class="form-group">
                                    <label>Mesero</label>
                                    <select class="form-control select2" id="id_mesero" name="id_mesero">
                                        <option value="0">Todos los meseros</option>
                                        <?php
                                        $itemMesero = null;
                                        $valorMesero = null;
                                        $meseros = ControladorMeseros::ctrMostrarMeseros($itemMesero, $valorMesero);
                                        foreach ($meseros as $key => $value) {
                                            echo '<option value="' . $value["id"] . '">' . $value["nombre"] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-sm-3">
                                <div class="form-group">
                                    <label>Categoría</label>
                                    <select class="form-control select2" id="id_categoria" name="id_categoria[]" multiple="multiple" data-placeholder="Seleccionar categorías">
                                        <option value="0" selected>Todas las categorías</option>
                                        <?php
                                        $itemCat = null;
                                        $valorCat = null;
                                        $categorias = ControladorCategorias::ctrMostrarCategorias($itemCat, $valorCat);
                                        foreach ($categorias as $key => $value) {
                                            echo '<option value="' . $value["id"] . '">' . $value["categoria"] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                
                            </div>
                            <div class="col-12 col-sm-2 text-right">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn btn-warning btn-block" onclick="generatePDF()" style="margin-bottom:6px;">
                                        <i class="fa fa-print"></i> PDF
                                    </button>
                                    <button type="button" class="btn btn-success btn-block" onclick="generateExcelTopMeseros()">
                                        <i class="fa fa-file-excel-o"></i> Excel
                                    </button>
                                </div>
                        </div>
                      
                            
                            
                           

                        <img src="vistas/img/plantilla/mesero-1.webp" class="responsive-image" style="display: block; margin: 0 auto; max-width: 100%; height: auto; object-fit: contain;">
                    </div><!--/body card-->
                </div><!--/CARD FIN-->

            </div>

        </div>

    </section>

</div>




<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #28a745 !important;
        border-color: #28a745 !important;
        color: #fff !important;
    }
    .select2-container--default .select2-results__option[aria-selected="true"] {
        background-color: #28a745 !important;
        color: #fff !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #218838 !important;
        color: #fff !important;
    }
</style>

<script>

    // Asignar la fecha actual a una variable global en JavaScript
    const fechaActual = "<?php echo $fechaActual; ?>";
    const fechaInicio = document.getElementById('fecha_inicio');
    const fechaFin = document.getElementById('fecha_fin');
    fechaInicio.setAttribute('max', fechaActual);
    fechaFin.setAttribute('max', fechaActual);

    // Variable global para mantener la referencia de la ventana emergente
    var popupWindow = null;

    function actualizarCategoriasSeleccionadas(categoriaSelect) {
        let selectedValues = Array.from(categoriaSelect.selectedOptions).map(option => option.value);
        const zeroOption = categoriaSelect.querySelector('option[value="0"]');

        if (selectedValues.includes('0') && selectedValues.length > 1) {
            selectedValues = selectedValues.filter(value => value !== '0');
            Array.from(categoriaSelect.options).forEach(option => {
                option.selected = selectedValues.includes(option.value);
            });
            if (zeroOption) {
                zeroOption.selected = false;
            }
        }

        if (selectedValues.length === 0 && zeroOption) {
            Array.from(categoriaSelect.options).forEach(option => {
                option.selected = option.value === '0';
            });
            selectedValues = ['0'];
        }

        if (typeof $ !== 'undefined' && $(categoriaSelect).data('select2')) {
            $(categoriaSelect).val(selectedValues).trigger('change.select2');
        }
    }

    $(document).ready(function () {
        var $categoriaSelect = $('#id_categoria');
        if ($categoriaSelect.length) {
            var actualizarZero = function () {
                var selectedValues = $categoriaSelect.val() || [];
                if (selectedValues.indexOf('0') !== -1 && selectedValues.length > 1) {
                    selectedValues = selectedValues.filter(function (value) {
                        return value !== '0';
                    });
                    $categoriaSelect.val(selectedValues);
                    $categoriaSelect.find('option[value="0"]').prop('selected', false);
                    $categoriaSelect.trigger('change.select2');
                }
                if (!selectedValues.length) {
                    $categoriaSelect.val(['0']);
                    $categoriaSelect.trigger('change.select2');
                }
            };

            $categoriaSelect.on('change select2:select select2:unselect', function () {
                setTimeout(actualizarZero, 0);
            });
        }
    });

    function generatePDF() {
        // Capturar valores de los inputs
        const idUsuario = document.getElementById('id_usuario').value;

        // Validar campos
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

        // Tamaño de la ventana emergente
        const width = 800;
        const height = 600;

        // Configuración de la ventana emergente
        const left = (screen.width / 2) - (width / 2);
        const top = (screen.height / 2) - (height / 2);
        const windowFeatures = `menubar=no,toolbar=no,status=no,width=${width},height=${height},left=${left},top=${top}`;

        // Cierra la ventana emergente existente si está abierta
        if (popupWindow && !popupWindow.closed) {
            popupWindow.close();
        }

        const idMesero = document.getElementById('id_mesero').value;
        const categoriaSelect = document.getElementById('id_categoria');
        let selectedCategorias = Array.from(categoriaSelect.selectedOptions).map(option => option.value);

        const allCategoriesSelected = selectedCategorias.includes('0');
        selectedCategorias = selectedCategorias.filter(categoriaId => categoriaId !== '0');

        let queryString = "extensiones/tcpdf/pdf/top-ventas-meseros.php?fechaInicio=" + encodeURIComponent(fechaInicio.value) +
            "&fechaFin=" + encodeURIComponent(fechaFin.value) +
            "&idUsuario=" + encodeURIComponent(idUsuario) +
            "&idMesero=" + encodeURIComponent(idMesero);

        if (allCategoriesSelected && selectedCategorias.length === 0) {
            queryString += "&idCategoria[]=0";
        } else {
            selectedCategorias.forEach(categoriaId => {
                queryString += "&idCategoria[]=" + encodeURIComponent(categoriaId);
            });
        }

        // Abre la URL en una nueva ventana (popup)
        popupWindow = window.open(
            queryString,
            "_blank",
            windowFeatures
        );
    }

    function generateExcelTopMeseros() {
        const idUsuario = document.getElementById('id_usuario').value;

        if (!fechaInicio.value || !fechaFin.value) {
            swal({ icon: 'warning', title: 'Advertencia', text: 'Por favor, seleccione las fechas requeridas.' });
            return;
        }
        if (fechaFin.value > fechaActual) {
            swal({ icon: 'warning', title: 'Advertencia', text: 'Por favor, la fecha fin seleccionada no puede ser mayor a la fecha actual: ' + fechaActual });
            return;
        }
        if (fechaInicio.value > fechaFin.value) {
            swal({ icon: 'warning', title: 'Advertencia', text: 'Por favor, seleccione una fecha de inicio menor a la fecha fin.' });
            return;
        }

        const idMesero = document.getElementById('id_mesero').value;
        const categoriaSelect = document.getElementById('id_categoria');
        let selectedCategorias = Array.from(categoriaSelect.selectedOptions).map(option => option.value);
        const allCategoriesSelected = selectedCategorias.includes('0');
        selectedCategorias = selectedCategorias.filter(categoriaId => categoriaId !== '0');

        let queryString = "extensiones/excel/top-meseros.php?fechaInicio=" + encodeURIComponent(fechaInicio.value) +
            "&fechaFin=" + encodeURIComponent(fechaFin.value) +
            "&idUsuario=" + encodeURIComponent(idUsuario) +
            "&idMesero=" + encodeURIComponent(idMesero);

        if (allCategoriesSelected && selectedCategorias.length === 0) {
            queryString += "&idCategoria[]=0";
        } else {
            selectedCategorias.forEach(categoriaId => {
                queryString += "&idCategoria[]=" + encodeURIComponent(categoriaId);
            });
        }

        window.location.href = queryString;
    }
</script>