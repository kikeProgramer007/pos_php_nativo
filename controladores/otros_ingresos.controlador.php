<?php

class ControladorOtrosIngresos
{

    static public function ctrRegistrarOtroIngreso()
    {
        if (!isset($_SESSION["iniciarSesion"]) || $_SESSION["iniciarSesion"] !== "ok") {
            return ["status" => "error", "mensaje" => "Debe iniciar sesión."];
        }

        if (!Permisos::tiene("caja.otros_ingresos")) {
            return ["status" => "error", "error" => "no-autorizado", "mensaje" => "No tiene permiso para registrar otros ingresos."];
        }

        $descripcion = isset($_POST["descripcion_otro_ingreso"]) ? trim($_POST["descripcion_otro_ingreso"]) : "";
        $montoRaw = isset($_POST["monto_otro_ingreso"]) ? str_replace(",", ".", trim($_POST["monto_otro_ingreso"])) : "";

        if ($descripcion === "" || !preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ.,;:()\-\s]+$/u', $descripcion)) {
            return ["status" => "error", "mensaje" => "La observación es obligatoria y no debe contener caracteres especiales."];
        }

        $largoDescripcion = function_exists("mb_strlen") ? mb_strlen($descripcion) : strlen($descripcion);
        if ($largoDescripcion > 255) {
            return ["status" => "error", "mensaje" => "La observación no puede superar 255 caracteres."];
        }

        if ($montoRaw === "" || !is_numeric($montoRaw)) {
            return ["status" => "error", "mensaje" => "El monto debe ser un número válido."];
        }

        $monto = round(floatval($montoRaw), 2);
        if ($monto <= 0) {
            return ["status" => "error", "mensaje" => "El monto debe ser mayor a 0."];
        }

        $idArqueo = isset($_SESSION["idArqueoCaja"]) ? intval($_SESSION["idArqueoCaja"]) : 0;
        $idUsuario = isset($_SESSION["id"]) ? intval($_SESSION["id"]) : 0;

        if ($idArqueo <= 0 || $idUsuario <= 0) {
            return ["status" => "error", "mensaje" => "No hay una caja abierta asociada al usuario."];
        }

        if (!ModeloArqueo::mdlVerificarCajaAbiertaPorIdArqueo($idArqueo)) {
            return [
                "status" => "error",
                "codigo" => "caja_cerrada",
                "mensaje" => "La caja está cerrada. Es necesario realizar la apertura de caja antes de continuar."
            ];
        }

        $respuesta = ModeloOtrosIngresos::mdlRegistrarOtroIngreso([
            "id_arqueo_caja" => $idArqueo,
            "id_usuario" => $idUsuario,
            "descripcion" => $descripcion,
            "monto" => number_format($monto, 2, ".", "")
        ]);

        if ($respuesta !== "ok") {
            return ["status" => "error", "mensaje" => "Error al registrar el ingreso."];
        }

        $arqueo = ModeloArqueo::mdlSincronizarMontosArqueo($idArqueo);

        return [
            "status" => "ok",
            "mensaje" => "El ingreso se registró correctamente.",
            "otros_ingresos" => $arqueo["otros_ingresos"] ?? ModeloOtrosIngresos::mdlSumarPorArqueo($idArqueo),
            "total_ingresos" => $arqueo["total_ingresos"] ?? null,
            "resultado_neto" => $arqueo["resultado_neto"] ?? null
        ];
    }

    static public function ctrMostrarOtrosIngresos($item, $valor)
    {
        return ModeloOtrosIngresos::mdlMostrarOtrosIngresos($item, $valor);
    }

    static public function ctrMostrarOtrosIngresosPorArqueo($idArqueo)
    {
        return ModeloOtrosIngresos::mdlMostrarPorArqueo($idArqueo);
    }
}
