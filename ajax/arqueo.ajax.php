<?php

require_once "../controladores/arqueo.controlador.php";
require_once "../modelos/arqueo.modelo.php";
require_once "../controladores/cajas.controlador.php";
require_once "../modelos/cajas.modelo.php";
require_once "../controladores/ventas.controlador.php";
require_once "../modelos/ventas.modelo.php";

/**
 * Clase para manejar las peticiones AJAX relacionadas con el arqueo de caja
 */
class AjaxArqueo {
    
    /**
     * Verifica si existe una caja abierta para un usuario
     * @return void
     */
    public function ajaxVerificarCaja() {
        if(!isset($_GET["idUsuario"]) || !is_numeric($_GET["idUsuario"])) {
            echo json_encode([
                "error" => true,
                "mensaje" => "ID de usuario no válido"
            ]);
            return;
        }

        try {
            $idUsuario = intval($_GET["idUsuario"]);
            $respuesta = ControladorArqueo::ctrVerificarCajaAbierta($idUsuario);
            if ($respuesta) {
                ModeloArqueo::mdlSincronizarCuentasPendientesEnArqueoAbierto();
                $arqueoActualizado = ModeloArqueo::mdlObtnerArqueoPorIDArqueo($respuesta["id"]);
                if ($arqueoActualizado) {
                    $respuesta["cuentas_pendientes"] = [
                        "cantidad" => intval($arqueoActualizado["cuentas_pendientes_cantidad"] ?? 0),
                        "total_por_cobrar" => floatval($arqueoActualizado["cuentas_pendientes_total"] ?? 0)
                    ];
                } else {
                    $respuesta["cuentas_pendientes"] = ControladorVentas::ctrResumenCuentasPendientes();
                }
            }
            echo json_encode($respuesta);
        } catch(Exception $e) {
            error_log("Error en ajaxVerificarCaja: " . $e->getMessage());
            echo json_encode([
                "error" => true,
                "mensaje" => "Error al verificar la caja"
            ]);
        }
    }

    /**
     * Obtiene el número de ticket actual de una caja
     * @return void
     */
    public function ajaxObtenerNroTicket() {
        if(!isset($_GET["idCaja"]) || !is_numeric($_GET["idCaja"])) {
            echo json_encode([
                "error" => true,
                "mensaje" => "ID de caja no válido"
            ]);
            return;
        }

        try {
            $idCaja = intval($_GET["idCaja"]);
            $respuesta = ControladorCajas::ctrObtenerNroTicket($idCaja);
            echo json_encode(["nroTicket" => $respuesta]);
        } catch(Exception $e) {
            error_log("Error en ajaxObtenerNroTicket: " . $e->getMessage());
            echo json_encode([
                "error" => true,
                "mensaje" => "Error al obtener el número de ticket"
            ]);
        }
    }

    /**
     * Procesa las operaciones de apertura y cierre de caja
     * @return void
     */
    public function procesarOperacionCaja() {
        if(!isset($_POST["accion"])) {
            echo json_encode([
                "error" => true,
                "mensaje" => "Acción no especificada"
            ]);
            return;
        }
        try {
            $respuesta = ControladorArqueo::ctrRegistrarArqueo();
            echo $respuesta;
        } catch(Exception $e) {
            error_log("Error en procesarOperacionCaja: " . $e->getMessage());
            echo json_encode([
                "error" => true,
                "mensaje" => "Error al procesar la operación"
            ]);
        }
    }
}

// Procesar las peticiones AJAX
if(isset($_GET["accion"])) {
    $arqueo = new AjaxArqueo();
    
    switch($_GET["accion"]) {
        case "verificarCaja":
            $arqueo->ajaxVerificarCaja();
            break;
        case "obtenerNroTicket":
            $arqueo->ajaxObtenerNroTicket();
            break;
        case "cuentasPendientes":
            ModeloArqueo::mdlSincronizarCuentasPendientesEnArqueoAbierto();
            echo json_encode(ControladorVentas::ctrResumenCuentasPendientes());
            break;
        default:
            echo json_encode([
                "error" => true,
                "mensaje" => "Acción no válida"
            ]);
    }
} elseif(isset($_POST["accion"])) {
    $arqueo = new AjaxArqueo();
    $arqueo->procesarOperacionCaja();
}