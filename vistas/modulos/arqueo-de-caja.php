<!-- Estilos CSS -->
<link rel="stylesheet" href="vistas/plugins/toastr/css/toastr.min.css">
<style>
    .custom-radio-group {
        display: flex;
        gap: 20px; /* Espaciado entre opciones */
        align-items: center;
    }

    .custom-radio {
        display: inline-block;
        position: relative;
    }

    .custom-radio input[type="radio"] {
        display: none; /* Oculta el radio nativo */
    }

    .custom-radio label {
        padding-left: 30px;
        position: relative;
        cursor: pointer;
        font-size: 16px;
    }

    .custom-radio label:before {
        content: "";
        width: 20px;
        height: 20px;
        border: 2px solid #007bff;
        border-radius: 50%;
        position: absolute;
        left: 0;
        top: 2px;
        background-color: white;
    }
    .custom-radio label[for="radio_apertura"]:before {
        content: "";
        width: 20px;
        height: 20px;
        border: 2px solid #007bff;
        border-radius: 50%;
        position: absolute;
        left: 0;
        top: 2px;
        background-color: white;
    }
    .custom-radio label[for="radio_cierre"]:before {
        content: "";
        width: 20px;
        height: 20px;
        border: 2px solid red;
        border-radius: 50%;
        position: absolute;
        left: 0;
        top: 2px;
        background-color: white;
    }
    .custom-radio input[type="radio"]:checked + label:before {
        background-color: #007bff;
        box-shadow: inset 0 0 0 4px white;
    }
    .custom-radio input[id="radio_cierre"]:checked + label:before {
        background-color:red;
        box-shadow: inset 0 0 0 4px white;
    }
/* Estilos generales de tabla */
.dt-responsive {
    border-collapse: collapse;
    width: 100%;
    border: 1px solid #333;
}
/* Estilos de filas y celdas */
.row-dark {
    font-weight: bold;
}

.row-dark th,
.row-dark td {
    padding-left: 6px;
    padding-right: 6px;
    padding-top: 6px;
    padding-bottom: 6px;
}

.cell-padded {
    padding-left: 6px;
}

.cell-value {
    padding-right: 6px;
}

/* Alineación de texto */
.text-right {
    text-align: right;
    padding-right: 6px;
}

.text-center {
    text-align: center;
}

/* Ancho de columnas */
.total-column {
    width: 20%;
}

/* Estilos para la tabla de resumen (tfoot/div) */
.summary-table {
    float: right;
    width: 100%;
    margin-top: 15px; /* Reemplaza <br> con margen */
}

.bold-value {
    font-weight: bold;
}

.border-bottom {
    border-bottom: 2px solid #333; /* Borde más grueso */
}
/* Estilos para el texto "VS" */
.vs-text {
    color: red;
    font-size: 14px;
}
    /* Agregar estos estilos para los inputs en la tabla */
    .tabla-efectivo input.form-control.input-sm {
        height: auto;
        padding: 2px 5px;
        line-height: 20px;
        border-radius: 2px;
        font-size: 15px;
    }

    .tabla-efectivo td {
        padding: 0px !important;
        vertical-align: middle !important;
    }

    .tabla-efectivo p {
        margin: 0;
        padding: 0;
    }

</style>


<div class="content-wrapper text-uppercase">
    <!-- Header -->
    <section class="content-header">
        <h1>APERTURA DE CAJA</h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">APERTURA DE CAJA</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="box">
            <div class="box-body">
                <form role="form" method="post" id="form-arqueo" enctype="multipart/form-data">
                    <div class="row">
                        <input type="hidden" name="idArqueo" id="idArqueo">
                        <!-- Entrada para seleccionar la categoría -->
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4><strong>FECHA Y HORA</strong></h4>
                                        <div class="input-group date">
                                            <input type="datetime-local" id="fecha_apertura_cierre" name="fecha_apertura_cierre" class="form-control" required readonly/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <h4><strong>Apertura realizada por Usuario</strong></h4>
                                        <input type="text" class="form-control text-uppercase " id="usuario" value="<?php echo $_SESSION["nombre"]; ?>" readonly>
                                        <input type="hidden" id="idVendedor" name="idVendedor" value="<?php echo $_SESSION["id"]; ?>">
                                    </div>
                           
                                    <div class="form-group">
                                        <h4><strong>Estado</strong></h4>
                                        <div class="custom-radio">
                                            <input type="radio" id="radio_apertura" value="abierta" checked disabled readonly >
                                            <label for="radio_apertura" > <span class="label label-primary">Abierto</span></label>
                                        </div>
                                        <div class="custom-radio">
                                            <input type="radio" id="radio_cierre" value="cerrada" disabled readonly>
                                            <label for="radio_cierre"> <span class="label label-danger">Cerrado</span> </label>
                                        </div>
                                        <input type="hidden" id="estado_caja" name="opcion" value="abierta">
                                    </div>
                                    <div class="form-group">
                                        <h4><strong>Seleccione la Caja</strong></h4>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-cogs"></i></span>
                                            <select class="form-control" id="idCaja" name="idCaja" required>
                                                <option value="">Seleccione la Caja</option>
                                                <?php
                                                $item = null;
                                                $valor = null;
                                                $cajas = ControladorCajas::ctrMostrarCajas($item, $valor);
                                                foreach ($cajas as $key => $value) {
                                                    $idCaja = $value["id"];
                                                    $selected = ($idCaja == "1") ? ' selected' : '';
                                                    echo '<option value="' . $idCaja . '"' . $selected . '>' . $value["nombre"] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <h4><strong>Nro de Ticket</strong></h4>
                                        <input type="number" class="form-control text-uppercase " id="nro_ticket" name="nro_ticket" value="0" min="0" >
                                    </div>

                                </div>
                                <div class="col-md-6" >
                                    <?php include "vistas/modulos/componentes/tabla-efectivo.php"; ?>
                                </div>
                            </div>
                     
                        </div>

                        <div class="col-md-4">
                         <?php include "vistas/modulos/componentes/resumen-caja.php"; ?>
                        </div>

                    </div>

                    <!-- Botones -->
                    <div class="row" style="text-align: right; padding-right: 20px;">
                        <button type="button" onclick="arqueoCaja.guardarAperturaCierreCaja()" id="aperturar_cierre_caja" class="btn btn-primary btn-sm" style="margin-right: 3px;">Aperturar Caja</button>
                    </div>

                    <!-- Controlador para crear el arqueo de caja-->
                    <?php
                    $registrarArqueo = new ControladorArqueo();
                    $registrarArqueo->ctrRegistrarArqueo();
                    ?>
               
                </form>


            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </section>
</div>

<!-- Estilos adicionales para mejorar la apariencia del checkbox -->
<style>
    .minimal {
        width: 20px;
        height: 20px;
    }

    label {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        padding-right: 6px;
    }

    .minimal {
        margin-right: 5px;
    }

</style>
 <!-- Toastr -->
 <script src="vistas/plugins/toastr/js/jquery-3.6.0.min.js"></script>
 <script src="vistas/plugins/toastr/js/toastr.min.js"></script>
  
<script>
  const idUsuario = <?php echo $_SESSION["id"]; ?>;
</script>
<script src="vistas/js/arqueo.js"></script>