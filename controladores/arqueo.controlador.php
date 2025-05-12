<?php

/**
 * Controlador para gestionar el arqueo de caja
 * @author Claude AI
 */
class ControladorArqueo {

    static public function obtenerArqueoPorId($idArqueo) {
        return ModeloArqueo::mdlObtnerArqueoPorIDArqueo($idArqueo);
    }
    
    /**
     * Registra una apertura o cierre de caja
     * @return string|array Respuesta de la operación
     */
    static public function ctrRegistrarArqueo() {
        if(!isset($_POST["accion"])) {
            return json_encode(["status" => "error", "mensaje" => "Acción no especificada"]);
        }

        try {
            if($_POST["accion"] == "AperturarCaja") {
                return self::registrarAperturaCaja();
            } else if($_POST["accion"] == "CerrarCaja") {
                return self::registrarCierreCaja();
            } else {
                return json_encode(["status" => "error", "mensaje" => "Acción no válida"]);
            }
        } catch(Exception $e) {
            return json_encode([
                "status" => "error", 
                "mensaje" => "Error al procesar la operación 21: " . $e->getMessage()
            ]);
        }
    }

    /**
     * Registra la apertura de una caja
     * @return string JSON con el resultado de la operación
     */
    private static function registrarAperturaCaja() {
        // Validar datos requeridos
        $camposRequeridos = ["fechaApertura", "estado", "idCaja", "idUsuario"];
        foreach($camposRequeridos as $campo) {
            if(!isset($_POST[$campo]) || empty($_POST[$campo])) {
                return json_encode([
                    "status" => "error",
                    "mensaje" => "El campo {$campo} es requerido"
                ]);
            }
        }

        $datos = array(
            "fecha_apertura" => self::sanitizarInput($_POST["fechaApertura"]),
            "monto_apertura" => floatval($_POST["montoApertura"]),
            "nro_ticket" => floatval($_POST["nroTicket"]),
            "total_ingresos" => floatval($_POST["totalIngresos"]),
            "resultado_neto" => floatval($_POST["resultadoNeto"]),
            "estado" => self::sanitizarInput($_POST["estado"]),
            "id_caja" => intval($_POST["idCaja"]),
            "id_usuario" => intval($_POST["idUsuario"])
        );

        $respuesta = ModeloArqueo::mdlRegistraAperturaCaja($datos);
        
        return json_encode(["status" => $respuesta]);
    }

    /**
     * Registra el cierre de una caja
     * @return string JSON con el resultado de la operación
     */
    private static function registrarCierreCaja() {
        // Validar datos requeridos
        $camposRequeridos = ["idArqueo", "fechaCierre", "estado"];
        foreach($camposRequeridos as $campo) {
            if(!isset($_POST[$campo]) || empty($_POST[$campo])) {
                return json_encode([
                    "status" => "error",
                    "mensaje" => "El campo {$campo} es requerido"
                ]);
            }
        }
        
        $datos = array(
            "id_arqueo" => intval($_POST["idArqueo"]),
            "fecha_cierre" => self::sanitizarInput($_POST["fechaCierre"]),
            "Bs200" => self::sanitizarCantidad($_POST["cantidad_200"]),
            "Bs100" => self::sanitizarCantidad($_POST["cantidad_100"]),
            "Bs50" => self::sanitizarCantidad($_POST["cantidad_50"]),
            "Bs20" => self::sanitizarCantidad($_POST["cantidad_20"]),
            "Bs10" => self::sanitizarCantidad($_POST["cantidad_10"]),
            "Bs5" => self::sanitizarCantidad($_POST["cantidad_5"]),
            "Bs2" => self::sanitizarCantidad($_POST["cantidad_2"]),
            "Bs1" => self::sanitizarCantidad($_POST["cantidad_1"]),
            "Bs050" => self::sanitizarCantidad($_POST["cantidad_05"]),
            "Bs020" => self::sanitizarCantidad($_POST["cantidad_02"]),
            "total_ingresos" => floatval($_POST["totalIngresos"]),
            "monto_ventas" => floatval($_POST["montoVentas"]),
            "total_egresos" => floatval($_POST["totalEgresos"]),
            "gastos_operativos" => floatval($_POST["gastosOperativos"]),
            "monto_compras" => floatval($_POST["montoCompras"]),
            "resultado_neto" => floatval($_POST["resultadoNeto"]),
            "efectivo_en_caja" => floatval($_POST["totalEfectivoEnCaja"]),
            "diferencia" => floatval($_POST["diferencia"]),
            "estado" => self::sanitizarInput($_POST["estado"]),
            "id_caja" => intval($_POST["idCaja"])
        );

        $respuesta = ModeloArqueo::mdlRegistrarCierreCaja($datos);
        return json_encode(["status" => $respuesta]);
    }

    /**
     * Verifica si existe una caja abierta para un usuario
     * @param int $idUsuario ID del usuario
     * @return array|false Datos de la caja o false si no existe
     */
    static public function ctrVerificarCajaAbierta($idUsuario) {
        try {
            if (!is_numeric($idUsuario)) {
                throw new Exception("ID de usuario no válido");
            }

            return ModeloArqueo::mdlVerificarCajaAbierta($idUsuario);
        } catch (Exception $e) {
            error_log("Error en ctrVerificarCajaAbierta: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Sanitiza un valor de entrada para prevenir XSS
     * @param string $input Valor a sanitizar
     * @return string Valor sanitizado
     */
    private static function sanitizarInput($input) {
        return htmlspecialchars(strip_tags(trim($input)));
    }

    /**
     * Sanitiza y valida un monto
     * @param mixed $monto Monto a sanitizar
     * @return int Monto sanitizado
     */
    private static function sanitizarCantidad($monto) {
        return intval($monto ?? 0);
    }

    /**
     * Obtiene el número de ticket actual de la apertura de caja
     * @return int
     */
    static public function ctrObtenerUltimoNroTicket($idArqueo) {
        return ModeloArqueo::mdlObtenerUltimoNroTicket($idArqueo);
    }
} 