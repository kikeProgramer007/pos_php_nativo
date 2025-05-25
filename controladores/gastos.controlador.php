<?php

class ControladorGastos{

    /*=============================================
    CREAR GASTOS
    =============================================*/

    static public function ctrCrearGasto(){

        if(isset($_POST["monto_gasto"]) && isset($_POST["id_arqueo_caja_gasto"])){
            if(ModeloArqueo::mdlVerificarCajaAbiertaPorIdArqueo($_POST["id_arqueo_caja_gasto"])){
            // Validar los campos del formulario
            if(preg_match('/^[0-9]+$/', $_POST["id_tipo_gasto"]) &&
               preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ.,\-\s]+$/', $_POST["descripcion_gasto"]) &&
               preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $_POST["fecha_gasto"]) &&
               preg_match('/^[0-9.]+$/', $_POST["monto_gasto"]) &&
               preg_match('/^[1-4]$/', $_POST["tipo_pago_gasto"])){
                
                $tabla = "gastos";
                
                $datos = array(
                    "id_tipo_gasto" => $_POST["id_tipo_gasto"],
                    "descripcion" => $_POST["descripcion_gasto"],
                    "fecha" => $_POST["fecha_gasto"],
                    "monto" => $_POST["monto_gasto"],
                    "forma_pago" => $_POST["tipo_pago_gasto"],
                    "id_usuario" => $_POST["id_usuario_gasto"],
                    "id_arqueo" => $_POST["id_arqueo_caja_gasto"]
                );

                $respuesta = ModeloGastos::mdlRegistrarGasto($tabla, $datos);

                if($respuesta == "ok"){
                    ModeloArqueo::mdlRegistrarEgreso($_POST["id_arqueo_caja_gasto"], $_POST["monto_gasto"],"gastos_operativos");
                    echo'<script>
                        swal({
                            type: "success",
                            title: "El Gasto ha sido registrado correctamente",
                            showConfirmButton: true,
                            confirmButtonText: "Cerrar"
                            }).then(function(result){
                                if (result.value) {
                                    window.location = "gastos";
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
                                window.location = "gastos";
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
                            window.location = "gastos";
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
    EDITAR GASTO
    =============================================*/
    
    static public function ctrEditarGasto() {

        if (!isset($_POST["editarMonto"])) return;
    
        // Validaciones
        $validaciones = [
            'id_tipo_gasto' => preg_match('/^[0-9]+$/', $_POST["editarIdTipoGasto"]),
            'descripcion' => preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ.,\-\s]+$/', $_POST["editarDescripcion"]),
            'fecha' => preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $_POST["editarFecha"]),
            'monto' => preg_match('/^\d+(\.\d{1,2})?$/', $_POST["editarMonto"]),
            'tipo_pago' => preg_match('/^[1-4]$/', $_POST["editarTipoPago"]),
        ];
    
        if (in_array(false, $validaciones, true)) return;
    
        $tabla = "gastos";
        $datos = [
            "id" => $_POST["idGasto"],
            "id_tipo_gasto" => $_POST["editarIdTipoGasto"],
            "descripcion" => $_POST["editarDescripcion"],
            "fecha_gasto" => $_POST["editarFecha"],
            "monto" => $_POST["editarMonto"],
            "tipo_pago" => $_POST["editarTipoPago"]
        ];
    
        $gastoOld = ControladorGastos::ctrMostrarGastos("id", $_POST["idGasto"]);
        $montoNuevo = $_POST["editarMonto"];
        $idArqueo = $gastoOld["id_arqueo"];
        $montoViejo = $gastoOld["monto"];
    
        // Si el monto no cambió
        if ($montoNuevo == $montoViejo) {
            self::mostrarResultado(ModeloGastos::mdlEditarGasto($tabla, $datos));
            return;
        }
    
        // Si la caja está abierta
        if (ModeloArqueo::mdlVerificarCajaAbiertaPorIdArqueo($idArqueo)) {
            $respuesta = ModeloGastos::mdlEditarGasto($tabla, $datos);
    
            if ($respuesta == "ok") {
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