<?php

class ControladorOtrosIngresos
{

    static private function parsearDatosDesdePost($post, $prefijo = "")
    {
        $campoDesc = $prefijo !== "" ? "editar_descripcion_otro_ingreso" : "descripcion_otro_ingreso";
        $campoTipo = $prefijo !== "" ? "editar_tipo_entrada_otro_ingreso" : "tipo_entrada_otro_ingreso";
        $campoMonto = $prefijo !== "" ? "editar_monto_otro_ingreso" : "monto_otro_ingreso";
        $campoEfectivo = $prefijo !== "" ? "editar_monto_efectivo_otro_ingreso" : "monto_efectivo_otro_ingreso";
        $campoQr = $prefijo !== "" ? "editar_monto_qr_otro_ingreso" : "monto_qr_otro_ingreso";

        $descripcion = isset($post[$campoDesc]) ? trim($post[$campoDesc]) : "";
        $tipoEntrada = isset($post[$campoTipo])
            ? strtoupper(trim($post[$campoTipo]))
            : "EFECTIVO";

        if ($descripcion === "" || !preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ.,;:()\-\s]+$/u', $descripcion)) {
            return ["ok" => false, "mensaje" => "La observación es obligatoria y no debe contener caracteres especiales."];
        }

        $largoDescripcion = function_exists("mb_strlen") ? mb_strlen($descripcion) : strlen($descripcion);
        if ($largoDescripcion > 255) {
            return ["ok" => false, "mensaje" => "La observación no puede superar 255 caracteres."];
        }

        if (!in_array($tipoEntrada, ["QR", "EFECTIVO", "MIXTO"], true)) {
            return ["ok" => false, "mensaje" => "Seleccione un tipo de entrada válido (QR, Efectivo o Mixto)."];
        }

        $montoEfectivo = 0.0;
        $montoQr = 0.0;
        $monto = 0.0;

        if ($tipoEntrada === "MIXTO") {
            $efectivoRaw = isset($post[$campoEfectivo]) ? str_replace(",", ".", trim($post[$campoEfectivo])) : "";
            $qrRaw = isset($post[$campoQr]) ? str_replace(",", ".", trim($post[$campoQr])) : "";

            if ($efectivoRaw === "" || !is_numeric($efectivoRaw) || $qrRaw === "" || !is_numeric($qrRaw)) {
                return ["ok" => false, "mensaje" => "Ingrese montos numéricos válidos en efectivo y QR."];
            }

            $montoEfectivo = round(floatval($efectivoRaw), 2);
            $montoQr = round(floatval($qrRaw), 2);

            if ($montoEfectivo < 0 || $montoQr < 0) {
                return ["ok" => false, "mensaje" => "Los montos no pueden ser negativos."];
            }
            if ($montoEfectivo <= 0 && $montoQr <= 0) {
                return ["ok" => false, "mensaje" => "En mixto al menos uno de los montos debe ser mayor a 0."];
            }

            $monto = round($montoEfectivo + $montoQr, 2);
        } else {
            $montoRaw = isset($post[$campoMonto]) ? str_replace(",", ".", trim($post[$campoMonto])) : "";
            if ($montoRaw === "" || !is_numeric($montoRaw)) {
                return ["ok" => false, "mensaje" => "El monto debe ser un número válido."];
            }

            $monto = round(floatval($montoRaw), 2);
            if ($monto <= 0) {
                return ["ok" => false, "mensaje" => "El monto debe ser mayor a 0."];
            }

            if ($tipoEntrada === "EFECTIVO") {
                $montoEfectivo = $monto;
                $montoQr = 0;
            } else {
                $montoEfectivo = 0;
                $montoQr = $monto;
            }
        }

        return [
            "ok" => true,
            "datos" => [
                "descripcion" => $descripcion,
                "monto" => number_format($monto, 2, ".", ""),
                "tipo_entrada" => $tipoEntrada,
                "monto_efectivo" => number_format($montoEfectivo, 2, ".", ""),
                "monto_qr" => number_format($montoQr, 2, ".", "")
            ]
        ];
    }

    static public function ctrRegistrarOtroIngreso()
    {
        if (!isset($_SESSION["iniciarSesion"]) || $_SESSION["iniciarSesion"] !== "ok") {
            return ["status" => "error", "mensaje" => "Debe iniciar sesión."];
        }

        if (!Permisos::tiene("caja.otros_ingresos")) {
            return ["status" => "error", "error" => "no-autorizado", "mensaje" => "No tiene permiso para registrar otros ingresos."];
        }

        $parseado = self::parsearDatosDesdePost($_POST);
        if (!$parseado["ok"]) {
            return ["status" => "error", "mensaje" => $parseado["mensaje"]];
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

        $datos = array_merge($parseado["datos"], [
            "id_arqueo_caja" => $idArqueo,
            "id_usuario" => $idUsuario
        ]);

        $respuesta = ModeloOtrosIngresos::mdlRegistrarOtroIngreso($datos);

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

    static public function ctrEditarOtroIngreso()
    {
        if (!isset($_SESSION["iniciarSesion"]) || $_SESSION["iniciarSesion"] !== "ok") {
            return ["status" => "error", "mensaje" => "Debe iniciar sesión."];
        }

        if (!Permisos::tiene("caja.otros_ingresos")) {
            return ["status" => "error", "mensaje" => "No tiene permiso para editar otros ingresos."];
        }

        $id = intval($_POST["id_otro_ingreso"] ?? 0);
        if ($id <= 0) {
            return ["status" => "error", "mensaje" => "Ingreso no válido."];
        }

        $ingresoOld = ModeloOtrosIngresos::mdlMostrarOtrosIngresos("id", $id);
        if (!$ingresoOld || intval($ingresoOld["estado"] ?? 0) !== 1) {
            return ["status" => "error", "mensaje" => "El ingreso no existe o ya fue anulado."];
        }

        $idArqueo = intval($ingresoOld["id_arqueo_caja"] ?? 0);
        if (!ModeloArqueo::mdlVerificarCajaAbiertaPorIdArqueo($idArqueo)) {
            return [
                "status" => "error",
                "codigo" => "caja_cerrada",
                "mensaje" => "No se puede editar: la caja de este ingreso está cerrada."
            ];
        }

        $parseado = self::parsearDatosDesdePost($_POST, "editar");
        if (!$parseado["ok"]) {
            return ["status" => "error", "mensaje" => $parseado["mensaje"]];
        }

        $datos = array_merge($parseado["datos"], ["id" => $id]);
        $respuesta = ModeloOtrosIngresos::mdlEditarOtroIngreso($datos);

        if ($respuesta !== "ok") {
            return ["status" => "error", "mensaje" => "Error al editar el ingreso."];
        }

        ModeloArqueo::mdlSincronizarMontosArqueo($idArqueo);

        return [
            "status" => "ok",
            "mensaje" => "El ingreso se editó correctamente."
        ];
    }

    static public function ctrAnularOtroIngreso()
    {
        if (!isset($_SESSION["iniciarSesion"]) || $_SESSION["iniciarSesion"] !== "ok") {
            return ["status" => "error", "mensaje" => "Debe iniciar sesión."];
        }

        if (!Permisos::tiene("caja.otros_ingresos")) {
            return ["status" => "error", "mensaje" => "No tiene permiso para anular otros ingresos."];
        }

        $id = intval($_POST["id_otro_ingreso"] ?? 0);
        if ($id <= 0) {
            return ["status" => "error", "mensaje" => "Ingreso no válido."];
        }

        $ingresoOld = ModeloOtrosIngresos::mdlMostrarOtrosIngresos("id", $id);
        if (!$ingresoOld || intval($ingresoOld["estado"] ?? 0) !== 1) {
            return ["status" => "error", "mensaje" => "El ingreso no existe o ya fue anulado."];
        }

        $idArqueo = intval($ingresoOld["id_arqueo_caja"] ?? 0);
        if (!ModeloArqueo::mdlVerificarCajaAbiertaPorIdArqueo($idArqueo)) {
            return [
                "status" => "error",
                "codigo" => "caja_cerrada",
                "mensaje" => "No se puede anular: la caja de este ingreso está cerrada."
            ];
        }

        $respuesta = ModeloOtrosIngresos::mdlAnularOtroIngreso($id);
        if ($respuesta !== "ok") {
            return ["status" => "error", "mensaje" => "Error al anular el ingreso."];
        }

        ModeloArqueo::mdlSincronizarMontosArqueo($idArqueo);

        return [
            "status" => "ok",
            "mensaje" => "El ingreso fue anulado correctamente."
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
