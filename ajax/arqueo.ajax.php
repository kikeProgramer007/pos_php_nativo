<?php

require_once "../includes/sesion-permisos.php";
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
                $arqueoSincronizado = ModeloArqueo::mdlSincronizarMontosArqueo($respuesta["id"]);
                if ($arqueoSincronizado) {
                    $respuesta = array_merge($respuesta, $arqueoSincronizado);
                }
                ModeloArqueo::mdlSincronizarCuentasPendientesEnArqueoAbierto($respuesta["id"]);
                $respuesta["cuentas_pendientes"] = ControladorVentas::ctrResumenCuentasPendientes($respuesta["id"]);
                $respuesta["cuentas_pendientes_cerradas"] = ControladorVentas::ctrResumenCuentasPendientesCajasCerradas();
            }
            echo json_encode(self::redondearMontos($respuesta));
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
     * Deja los montos en 2 decimales como texto para que json_encode
     * no imprima el error binario del float.
     */
    private static function redondearMontos($datos) {
        if (!is_array($datos)) {
            return $datos;
        }

        $claves = [
            "monto_apertura",
            "monto_apertura_efectivo",
            "monto_apertura_qr",
            "monto_ventas",
            "monto_ventas_efectivo",
            "monto_ventas_qr",
            "otros_ingresos",
            "otros_ingresos_efectivo",
            "otros_ingresos_qr",
            "total_descuentos_ventas",
            "total_bruto_ventas",
            "gastos_operativos",
            "gastos_efectivo",
            "gastos_qr",
            "monto_compras",
            "monto_compras_informativo",
            "total_ingresos",
            "total_egresos",
            "resultado_neto",
            "total_efectivo_qr_en_caja",
            "efectivo_en_caja",
            "diferencia",
            "qr_en_caja"
        ];

        foreach ($claves as $clave) {
            if (isset($datos[$clave]) && is_numeric($datos[$clave])) {
                $datos[$clave] = number_format(round(floatval($datos[$clave]), 2), 2, ".", "");
            }
        }

        if (isset($datos["cuentas_pendientes"]) && is_array($datos["cuentas_pendientes"])) {
            $datos["cuentas_pendientes"] = self::redondearMontos($datos["cuentas_pendientes"]);
        }
        if (isset($datos["total_por_cobrar"]) && is_numeric($datos["total_por_cobrar"])) {
            $datos["total_por_cobrar"] = number_format(round(floatval($datos["total_por_cobrar"]), 2), 2, ".", "");
        }

        return $datos;
    }

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
            $idArqueo = isset($_GET["idArqueo"]) ? intval($_GET["idArqueo"]) : 0;
            $cajaActual = [
                "cantidad" => 0,
                "total_por_cobrar" => 0
            ];

            if ($idArqueo > 0 && ModeloArqueo::mdlVerificarCajaAbiertaPorIdArqueo($idArqueo)) {
                ModeloArqueo::mdlSincronizarCuentasPendientesEnArqueoAbierto($idArqueo);
                $cajaActual = ControladorVentas::ctrResumenCuentasPendientes($idArqueo);
            } else {
                $arqueoAbierto = ModeloArqueo::mdlObtnerArqueoPorIDUsuario(0);
                if ($arqueoAbierto) {
                    ModeloArqueo::mdlSincronizarCuentasPendientesEnArqueoAbierto($arqueoAbierto["id"]);
                    $cajaActual = ControladorVentas::ctrResumenCuentasPendientes($arqueoAbierto["id"]);
                }
            }

            echo json_encode([
                "caja_actual" => $cajaActual,
                "cajas_cerradas" => ControladorVentas::ctrResumenCuentasPendientesCajasCerradas(),
                // Compatibilidad con consumidores anteriores
                "cantidad" => intval($cajaActual["cantidad"] ?? 0),
                "total_por_cobrar" => floatval($cajaActual["total_por_cobrar"] ?? 0)
            ]);
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