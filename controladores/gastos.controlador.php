<?php

class ControladorGastos{

    /*=============================================
    RESUELVE MONTO TOTAL + EFECTIVO/QR SEGÚN FORMA DE PAGO
    =============================================*/
    static private function resolverMontosFormaPago($formaPago, $monto, $montoEfectivo, $montoQr){

        $formaPago = intval($formaPago);
        $monto = floatval(str_replace(",", ".", (string)$monto));
        $montoEfectivo = floatval(str_replace(",", ".", (string)$montoEfectivo));
        $montoQr = floatval(str_replace(",", ".", (string)$montoQr));

        if (!in_array($formaPago, [1, 2, 3, 4], true)) {
            return ["ok" => false, "mensaje" => "Seleccione una forma de pago válida."];
        }

        if ($formaPago === 4) {
            if ($montoEfectivo < 0 || $montoQr < 0) {
                return ["ok" => false, "mensaje" => "Ingrese montos numéricos válidos en efectivo y QR."];
            }
            if ($montoEfectivo <= 0 && $montoQr <= 0) {
                return ["ok" => false, "mensaje" => "En mixto al menos uno de los montos debe ser mayor a 0."];
            }
            $monto = round($montoEfectivo + $montoQr, 2);
        } else {
            if ($monto <= 0) {
                return ["ok" => false, "mensaje" => "El monto debe ser un número mayor a 0."];
            }
            if ($formaPago === 1) {
                $montoEfectivo = $monto;
                $montoQr = 0;
            } elseif ($formaPago === 2) {
                $montoEfectivo = 0;
                $montoQr = $monto;
            } else {
                // Transferencia: no afecta efectivo de caja
                $montoEfectivo = 0;
                $montoQr = 0;
            }
        }

        return [
            "ok" => true,
            "forma_pago" => $formaPago,
            "monto" => number_format($monto, 2, ".", ""),
            "monto_efectivo" => number_format($montoEfectivo, 2, ".", ""),
            "monto_qr" => number_format($montoQr, 2, ".", ""),
            "monto_float" => $monto,
            "monto_efectivo_float" => $montoEfectivo
        ];
    }

    static private function urlRetornoGasto(){
        if (!empty($_POST["redirigir_gasto"]) && preg_match('/^[a-z0-9\-]+$/i', $_POST["redirigir_gasto"])) {
            return $_POST["redirigir_gasto"];
        }
        return "gastos";
    }

    /*=============================================
    CREAR GASTOS
    =============================================*/

    static public function ctrCrearGasto(){

        if(isset($_POST["id_arqueo_caja_gasto"]) && (isset($_POST["monto_gasto"]) || isset($_POST["tipo_pago_gasto"]))){
            if (!Permisos::tiene("gastos.crear")) {
                Permisos::requiere("gastos.crear");
                return;
            }
            $urlRetorno = self::urlRetornoGasto();

            if(ModeloArqueo::mdlVerificarCajaAbiertaPorIdArqueo($_POST["id_arqueo_caja_gasto"])){
            // Validar los campos del formulario
            if(preg_match('/^[0-9]+$/', $_POST["id_tipo_gasto"]) &&
               preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ.,\-\s]+$/', $_POST["descripcion_gasto"]) &&
               preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $_POST["fecha_gasto"]) &&
               preg_match('/^[1-4]$/', $_POST["tipo_pago_gasto"])){

                $montos = self::resolverMontosFormaPago(
                    $_POST["tipo_pago_gasto"],
                    $_POST["monto_gasto"] ?? 0,
                    $_POST["monto_efectivo_gasto"] ?? 0,
                    $_POST["monto_qr_gasto"] ?? 0
                );

                if (!$montos["ok"]) {
                    echo'<script>
                        swal({
                            type: "error",
                            title: "'.addslashes($montos["mensaje"]).'",
                            showConfirmButton: true,
                            confirmButtonText: "Cerrar"
                            }).then(function(result){
                                if (result.value) {
                                    window.location = "'.$urlRetorno.'";
                                }
                            })
                        </script>';
                    return;
                }

                if ($montos["monto_efectivo_float"] > 0) {
                    $disp = ModeloArqueo::mdlCalcularEfectivoDisponible($_POST["id_arqueo_caja_gasto"]);
                    if (empty($disp["ok"]) || floatval($disp["disponible"]) + 0.001 < $montos["monto_efectivo_float"]) {
                        $disponibleTxt = number_format(floatval($disp["disponible"] ?? 0), 2, ".", "");
                        echo'<script>
                            swal({
                                type: "error",
                                title: "Efectivo insuficiente",
                                text: "El monto en efectivo del gasto supera el disponible en caja (Bs. '.$disponibleTxt.').",
                                showConfirmButton: true,
                                confirmButtonText: "Cerrar"
                                }).then(function(result){
                                    if (result.value) {
                                        window.location = "'.$urlRetorno.'";
                                    }
                                })
                            </script>';
                        return;
                    }
                }

                $tabla = "gastos";
                
                $datos = array(
                    "id_tipo_gasto" => $_POST["id_tipo_gasto"],
                    "descripcion" => $_POST["descripcion_gasto"],
                    "fecha" => $_POST["fecha_gasto"],
                    "monto" => $montos["monto"],
                    "monto_efectivo" => $montos["monto_efectivo"],
                    "monto_qr" => $montos["monto_qr"],
                    "forma_pago" => $montos["forma_pago"],
                    "id_usuario" => $_POST["id_usuario_gasto"],
                    "id_arqueo" => $_POST["id_arqueo_caja_gasto"]
                );

                $respuesta = ModeloGastos::mdlRegistrarGasto($tabla, $datos);

                if($respuesta == "ok"){
                    ModeloArqueo::mdlRegistrarEgreso($_POST["id_arqueo_caja_gasto"], $montos["monto"],"gastos_operativos");
                    echo'<script>
                        swal({
                            type: "success",
                            title: "El Gasto ha sido registrado correctamente",
                            showConfirmButton: true,
                            confirmButtonText: "Cerrar"
                            }).then(function(result){
                                if (result.value) {
                                    window.location = "'.$urlRetorno.'";
                                }
                            })
                        </script>';

                }else{
                    
                    echo'<script>

                    swal({
                        type: "error",
                        title: "Error al registrar el gasto",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                        }).then(function(result){
                            if (result.value) {
                                window.location = "'.$urlRetorno.'";
                            }
                        })

                    </script>';
                }
                
            }else{
                
                echo'<script>

                swal({
                    type: "error",
                    title: "¡Los campos no pueden ir vacíos o llevar caracteres especiales!",
                    showConfirmButton: true,
                    confirmButtonText: "Cerrar"
                    }).then(function(result){
                        if (result.value) {
                            window.location = "'.$urlRetorno.'";
                        }
                    })

                </script>';
            }
        }else{
            echo'<script>
                swal({
                        title:"Caja Cerrada",
                        text: "La caja está cerrada. Es necesario realizar la apertura de caja antes de continuar. ¿Desea redirigirse a la vista de arqueo de caja para abrirla?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Sí, ir a apertura de caja",
                        cancelButtonText: "No"
                    }).then((result) => {
                        if (result.value) {
                            window.location.href = "arqueo-de-caja";
                        }
                    });
            </script>';
        }
        }
    }
    
    /*=============================================
    MOSTRAR GASTOS
    =============================================*/
   
    static public function ctrMostrarGastos($item, $valor){
        
        $tabla = "gastos";
        
        $respuesta = ModeloGastos::mdlMostrarGastos($tabla, $item, $valor);
        
        return $respuesta;
    }

    /*=============================================
    REPORTE DE GASTOS ENTRE FECHAS
    =============================================*/

    static public function ctrReporteGastosEntreFechas($fechaInicio, $fechaFin, $idTipoGasto = 0, $formaPago = 0, $idUsuarioFiltro = 0){
        return ModeloGastos::mdlReporteGastosEntreFechas($fechaInicio, $fechaFin, $idTipoGasto, $formaPago, $idUsuarioFiltro);
    }
    
    /*=============================================
    EDITAR GASTO
    =============================================*/
    
    static public function ctrEditarGasto() {

        if (!isset($_POST["editarMonto"]) && !isset($_POST["editarTipoPago"])) return;

        if (!Permisos::tiene("gastos.editar")) {
            Permisos::requiere("gastos.editar");
            return;
        }
    
        // Validaciones
        $validaciones = [
            'id_tipo_gasto' => preg_match('/^[0-9]+$/', $_POST["editarIdTipoGasto"]),
            'descripcion' => preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ.,\-\s]+$/', $_POST["editarDescripcion"]),
            'fecha' => preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $_POST["editarFecha"]),
            'tipo_pago' => preg_match('/^[1-4]$/', $_POST["editarTipoPago"]),
        ];
    
        if (in_array(false, $validaciones, true)) return;

        $montos = self::resolverMontosFormaPago(
            $_POST["editarTipoPago"],
            $_POST["editarMonto"] ?? 0,
            $_POST["editarMontoEfectivo"] ?? 0,
            $_POST["editarMontoQr"] ?? 0
        );

        if (!$montos["ok"]) {
            echo '<script>
                swal({
                    type: "error",
                    title: "'.addslashes($montos["mensaje"]).'",
                    showConfirmButton: true,
                    confirmButtonText: "Cerrar"
                }).then(function(result){
                    if (result.value) {
                        window.location = "gastos";
                    }
                });
            </script>';
            return;
        }
    
        $tabla = "gastos";
        $datos = [
            "id" => $_POST["idGasto"],
            "id_tipo_gasto" => $_POST["editarIdTipoGasto"],
            "descripcion" => $_POST["editarDescripcion"],
            "fecha_gasto" => $_POST["editarFecha"],
            "monto" => $montos["monto"],
            "monto_efectivo" => $montos["monto_efectivo"],
            "monto_qr" => $montos["monto_qr"],
            "tipo_pago" => $montos["forma_pago"]
        ];
    
        $gastoOld = ControladorGastos::ctrMostrarGastos("id", $_POST["idGasto"]);
        $montoNuevo = $montos["monto"];
        $idArqueo = $gastoOld["id_arqueo"];
        $montoViejo = $gastoOld["monto"];
        $montoEfectivoNuevo = $montos["monto_efectivo_float"];
        $montoEfectivoViejo = floatval($gastoOld["monto_efectivo"] ?? (($gastoOld["forma_pago"] == 1 || $gastoOld["forma_pago"] == "1") ? $gastoOld["monto"] : 0));
    
        // Si nada de montos/forma cambió de forma relevante, solo actualizar datos
        if ($montoNuevo == $montoViejo &&
            floatval($datos["monto_efectivo"]) == floatval($gastoOld["monto_efectivo"] ?? 0) &&
            floatval($datos["monto_qr"]) == floatval($gastoOld["monto_qr"] ?? 0) &&
            intval($datos["tipo_pago"]) == intval($gastoOld["forma_pago"])) {
            self::mostrarResultado(ModeloGastos::mdlEditarGasto($tabla, $datos));
            return;
        }
    
        // Si la caja está abierta
        if (ModeloArqueo::mdlVerificarCajaAbiertaPorIdArqueo($idArqueo)) {
            if ($montoEfectivoNuevo > $montoEfectivoViejo) {
                $extraEfectivo = $montoEfectivoNuevo - $montoEfectivoViejo;
                $disp = ModeloArqueo::mdlCalcularEfectivoDisponible($idArqueo);
                if (empty($disp["ok"]) || floatval($disp["disponible"]) + 0.001 < $extraEfectivo) {
                    $disponibleTxt = number_format(floatval($disp["disponible"] ?? 0), 2, ".", "");
                    echo '<script>
                        swal({
                            type: "error",
                            title: "Efectivo insuficiente",
                            text: "El incremento en efectivo supera el disponible en caja (Bs. '.$disponibleTxt.').",
                            showConfirmButton: true,
                            confirmButtonText: "Cerrar"
                        }).then(function(result){
                            if (result.value) {
                                window.location = "gastos";
                            }
                        });
                    </script>';
                    return;
                }
            }

            $respuesta = ModeloGastos::mdlEditarGasto($tabla, $datos);
    
            if ($respuesta == "ok" && floatval($montoNuevo) != floatval($montoViejo)) {
                ModeloArqueo::mdlEliminarEgreso($idArqueo, $montoViejo, "gastos_operativos");
                ModeloArqueo::mdlRegistrarEgreso($idArqueo, $montoNuevo, "gastos_operativos");
            }
    
            self::mostrarResultado($respuesta);
            return;
        }
    
        // Caja cerrada
        self::mostrarErrorCajaCerrada($idArqueo);
    }
    
    private static function mostrarResultado($respuesta) {
        if ($respuesta == "ok") {
            echo '<script>
                swal({
                    type: "success",
                    title: "El gasto ha sido editado correctamente",
                    showConfirmButton: true,
                    confirmButtonText: "Cerrar"
                }).then(function(result){
                    if (result.value) {
                        window.location = "gastos";
                    }
                });
            </script>';
        }
    }
    
    private static function mostrarErrorCajaCerrada($idArqueo) {
        echo '<script>
            function abrirMovimientosCaja(codigo) {
                var width = 800;
                var height = 600;
                var left = (screen.width / 2) - (width / 2);
                var top = (screen.height / 2) - (height / 2);
                var windowFeatures = "menubar=no,toolbar=no,status=no,width=" + width + ",height=" + height + ",left=" + left + ",top=" + top;
                window.open("extensiones/tcpdf/pdf/movimientos-caja.php?codigo=" + codigo, "_blank", windowFeatures);
            }
    
            swal({
                type: "error",
                title: "¡Este registro no puede ser editado!",
                html: "La caja a la que pertenece este gasto está cerrada.<br><a class=\'btn btn-default btn-xs\' href=\'javascript:void(0);\' onclick=\'abrirMovimientosCaja(' . $idArqueo . ')\'> <i class=\'fa fa-print\'></i> Visualizar caja</a>",
                showConfirmButton: true,
                confirmButtonText: "Cerrar"
            }).then(function(result){
                if (result.value) {
                    window.location = "gastos";
                }
            });
        </script>';
    }
    /*=============================================
    ELIMINAR GASTO
    =============================================*/

    static public function ctrEliminarGasto(){
        
        if(isset($_GET["idGasto"])){

            if (!Permisos::tiene("gastos.eliminar")) {
                Permisos::requiere("gastos.eliminar");
                return;
            }          
            $tabla = "gastos";
            $datos = $_GET["idGasto"];
            $gastoOld = ControladorGastos::ctrMostrarGastos("id",$datos);

            if(ModeloArqueo::mdlVerificarCajaAbiertaPorIdArqueo($gastoOld["id_arqueo"])){

                $respuesta = ModeloGastos::mdlEliminarGasto($tabla, $datos);
                
                if($respuesta == "ok"){
                    ModeloArqueo::mdlEliminarEgreso($gastoOld["id_arqueo"],$gastoOld["monto"], "gastos_operativos");
                    echo'<script>
                    swal({
                        type: "success",
                        title: "El gasto ha sido eliminado correctamente",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                        }).then(function(result){
                            if (result.value) {
                                window.location = "gastos";
                            }
                        })

                    </script>';
                }
            }else{
                echo'<script>

                swal({
                    type: "error",
                    title: "¡Este registro no puedo ser eliminado dado que la caja a la que pertenece esta cerrada!",
                    showConfirmButton: true,
                    confirmButtonText: "Cerrar"
                    }).then(function(result){
                        if (result.value) {
                            window.location = "gastos";
                        }
                    })

                </script>';
            }
        }
    }
    
    /*=============================================
    SUMA TOTAL GASTOS
    =============================================*/
    
    static public function ctrSumaTotalGastos(){
        
        $tabla = "gastos";
        
        $respuesta = ModeloGastos::mdlSumaTotalGastos($tabla);
        
        return $respuesta;
    }
}
