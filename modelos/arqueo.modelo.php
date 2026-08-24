<?php

require_once "conexion.php";

/**
 * Modelo para gestionar las operaciones de arqueo de caja en la base de datos
 */
class ModeloArqueo {

    	/*=============================================
	MOSTRAR ARQUEOS DE CAJA
	=============================================*/

	static public function mdlMostrarArqueos($tabla, $item, $valor){

		if($item != null){
			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item ORDER BY id  ASC ");
			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
			$stmt -> execute();
			return $stmt -> fetch();
		}else{
			$stmt = Conexion::conectar()->prepare(
				"SELECT $tabla.*, usuarios.usuario,
					COALESCE(pend.monto_por_cobrar, 0) AS monto_por_cobrar
				 FROM $tabla
				 JOIN usuarios ON ($tabla.id_usuario = usuarios.id)
				 LEFT JOIN (
					SELECT id_arqueo_caja, COALESCE(SUM(total), 0) AS monto_por_cobrar
					FROM ventas
					WHERE estado = 1 AND estado_pago = 'PENDIENTE'
					GROUP BY id_arqueo_caja
				 ) pend ON pend.id_arqueo_caja = $tabla.id
				 ORDER BY fecha_apertura DESC"
			);
			$stmt -> execute();
			return $stmt -> fetchAll();
		}
		
		$stmt -> close();
		$stmt = null;
	}


    /**
     * Registra la apertura de una caja
     * @param array $datos Datos de la apertura
     * @return string Resultado de la operación ('ok' o 'error')
     */
  
    
    static public function mdlRegistraAperturaCaja($datos) {
        try {
            $pdo = Conexion::conectar(); // Guardamos la conexión en una variable
            $stmt = $pdo->prepare("INSERT INTO arqueo_caja (
                fecha_apertura,
                monto_apertura,
                total_ingresos,
                resultado_neto, 
                estado,
                id_caja,
                id_usuario,
                nroTicket
            ) VALUES (
                :fecha_apertura,
                :monto_apertura,
                :total_ingresos,
                :resultado_neto,
                :estado,
                :id_caja,
                :id_usuario,
                :nro_ticket
            )");

            $stmt->bindParam(":fecha_apertura", $datos["fecha_apertura"], PDO::PARAM_STR);
            $stmt->bindParam(":monto_apertura", $datos["monto_apertura"], PDO::PARAM_STR);
            $stmt->bindParam(":total_ingresos", $datos["total_ingresos"], PDO::PARAM_STR);
            $stmt->bindParam(":resultado_neto", $datos["resultado_neto"], PDO::PARAM_STR);
            $stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_STR);
            $stmt->bindParam(":id_caja", $datos["id_caja"], PDO::PARAM_INT);
            $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
            $stmt->bindParam(":nro_ticket", $datos["nro_ticket"], PDO::PARAM_INT);

            if($stmt->execute()){
                self::mdlActualizarNroCaja($datos["id_caja"], $datos["nro_ticket"]);
                 // Obtenemos el id recién insertado
                $idRecienCreado = $pdo->lastInsertId();
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                // Guardamos el id en la variable de sesión
                $_SESSION["idArqueoCaja"] = $idRecienCreado;
                $_SESSION["idCaja"] = $datos["id_caja"];

                return "ok";
            }
            
            return "error";
        } catch (PDOException $e) {
            error_log("Error en mdlRegistraAperturaCaja: " . $e->getMessage());
            return "error";
        } finally {
            if(isset($stmt)) {
                $stmt->closeCursor();
                $stmt = null;
            }
        }
    }

    /**
     * Verifica si existe una caja abierta para un usuario
     * @param int $id_usuario ID del usuario
     * @return array|false Datos de la caja o false si no existe
     */
    static public function mdlVerificarCajaAbierta($id_usuario) {
        try {
            $stmt = Conexion::conectar()->prepare("SELECT ac.*, u.nombre AS nameUsuario 
                                                    FROM arqueo_caja ac 
                                                    JOIN usuarios u ON ac.id_usuario = u.id 
                                                    WHERE ac.estado = 'abierta' 
                                                    ORDER BY ac.id DESC 
                                                    LIMIT 1");
                 
        
            //$stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en mdlVerificarCajaAbierta: " . $e->getMessage());
            return false;
        } finally {
            if(isset($stmt)) {
                $stmt->closeCursor();
                $stmt = null;
            }
        }
    }
    static public function mdlVerificarCajaAbiertaPorIdArqueo($id_arqueo) {
        try {
            $stmt = Conexion::conectar()->prepare("
                SELECT 1
                FROM arqueo_caja ac
                WHERE ac.estado = 'abierta'
                  AND ac.id = :id_arqueo
                LIMIT 1
            ");
            $stmt->bindParam(":id_arqueo", $id_arqueo, PDO::PARAM_INT);
            $stmt->execute();
    
            return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en mdlVerificarCajaAbiertaPorIdArqueo: " . $e->getMessage());
            return false;
        } finally {
            if (isset($stmt)) {
                $stmt->closeCursor();
                $stmt = null;
            }
        }
    }
    
    /**
     * Obtiene el último número de ticket registrado
     * @return int Último número de ticket o false en caso de error
     */
    static public function mdlObtenerUltimoNroTicket($id_arqueo) {
        try {
            $pdo = Conexion::conectar();
            $stmt = $pdo->prepare("SELECT nroTicket as ultimo FROM arqueo_caja WHERE id = :id_arqueo");
            $stmt->bindParam(":id_arqueo", $id_arqueo, PDO::PARAM_INT);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado && $resultado["ultimo"] !== null ? (int)($resultado["ultimo"]) : 0;
        } catch (PDOException $e) {
            error_log("Error en mdlObtenerUltimoNroTicket: " . $e->getMessage());
            return 0;
        } finally {
            if (isset($stmt)) {
                $stmt->closeCursor();
                $stmt = null;
            }
        }
    }
/**
     * Obtiene el último número de ticket registrado
     * @return int Último número de ticket o false en caso de error
     */
    static public function mdlObtenerUltimoNroTicketDeVentas($id_arqueo) {
        try {
            $pdo = Conexion::conectar();
            $stmt = $pdo->prepare("SELECT codigo as ultimo FROM ventas WHERE id_arqueo_caja = :id_arqueo ORDER BY codigo DESC LIMIT 1");
            $stmt->bindParam(":id_arqueo", $id_arqueo, PDO::PARAM_INT);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado && $resultado["ultimo"] !== null ? (int)($resultado["ultimo"]) : 0;
        } catch (PDOException $e) {
            error_log("Error en mdlObtenerUltimoNroTicket: " . $e->getMessage());
            return 0;
        } finally {
            if (isset($stmt)) {
                $stmt->closeCursor();
                $stmt = null;
            }
        }
    }
    /**
     * Actualiza el número de ticket de una caja
     * @param int $id_caja ID de la caja
     * @param int $nro_ticket Nuevo número de ticket
     * @return string Resultado de la operación ('ok' o 'error')
     */
    public static function mdlActualizarNroCaja($id_caja, $nro_ticket) {
        try {
            $stmt = Conexion::conectar()->prepare(
                "UPDATE cajas SET nro_ticket = :nro_ticket WHERE id = :id_caja"
            );
            
            $stmt->bindParam(":nro_ticket", $nro_ticket, PDO::PARAM_INT);
            $stmt->bindParam(":id_caja", $id_caja, PDO::PARAM_INT);
            
            return $stmt->execute() ? "ok" : "error";
        } catch (PDOException $e) {
            error_log("Error en mdlActualizarNroCaja: " . $e->getMessage());
            return "error";
        } finally {
            if(isset($stmt)) {
                $stmt->closeCursor();
                $stmt = null;
            }
        }
    }
     /**
      * Registra el egreso em arqueo de caja 
      * @param int $Arqueo ID de la caja
      * @param int $nroTicket Nuevo número de ticket
      * @param int $totalVentas Total de Ingresos
      * @return string Resultado de la operación ('ok' o 'error')
      */
    public static function mdlRegistrarIngreso($Arqueo, $nroTicket, $totalVentas, $totalEfectivo, $totalQR) {
        $db = Conexion::conectar(); // Obtener la conexión PDO
        $db->beginTransaction(); // Iniciar transacción
        try {

            // Preparar y ejecutar la actualización en la tabla arqueo_caja
            $stmtArqueo = $db->prepare("UPDATE arqueo_caja 
            SET nroTicket = :nroTicket,
             monto_ventas_efectivo = monto_ventas_efectivo + (:totalEfectivo),
             monto_ventas_qr = monto_ventas_qr + (:totalQR),
             monto_ventas = monto_ventas + (:totalVentas), 
             total_ingresos = total_ingresos + (:totalVentas), 
             resultado_neto = total_ingresos - total_egresos 
             WHERE id = :idArqueo");
            $stmtArqueo->bindParam(":idArqueo", $Arqueo["id"], PDO::PARAM_INT);
            $stmtArqueo->bindParam(":nroTicket", $nroTicket, PDO::PARAM_STR);
            $stmtArqueo->bindParam(":totalVentas", $totalVentas, PDO::PARAM_STR);
            $stmtArqueo->bindParam(":totalEfectivo", $totalEfectivo, PDO::PARAM_STR);
            $stmtArqueo->bindParam(":totalQR", $totalQR, PDO::PARAM_STR);

            $actualizacionExitosa = $stmtArqueo->execute();

            if (!$actualizacionExitosa) {
                throw new Exception("Fallo al actualizar el número de ticket en arqueo_caja.");
            }
            // Llamar a la segunda actualización en la otra tabla
            $resultadoSegundaActualizacion = self::mdlActualizarNroCaja($Arqueo["id_caja"], $nroTicket);

            if ($resultadoSegundaActualizacion !== "ok") {
                throw new Exception("Fallo al actualizar el número de ticket en la segunda tabla.");
            }

            // Si todo está bien, confirmar la transacción
            $db->commit();
            return [
                'status' => 'ok',
                'message' => 'Actualización exitosa de ambas tablas.'
            ];

        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            $db->rollBack();
            error_log("Error en mdlActualizarNroCajaDelArqueo: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Error al actualizar: ' . $e->getMessage()
            ];
        } finally {
            // Liberar recursos (opcional, PDO lo hace automáticamente)
            if (isset($stmtArqueo)) {
                $stmtArqueo = null;
            }
            $db = null; // Cerrar la conexión (opcional si usas un singleton)
        }
    }

     /**
      * Elimina el egreso en arqueo de caja 
      * @param int $Arqueo ID de la caja
      * @param int $totalIngreso Total de Ingresos
      * @return string Resultado de la operación ('ok' o 'error')
      */
    public static function mdlEliminarIngreso($idArqueo, $totalIngreso, $totalEfectivo, $totalQR) {
        $db = Conexion::conectar(); // Obtener la conexión PDO
        $db->beginTransaction(); // Iniciar transacción
        try {

            // Preparar y ejecutar la actualización en la tabla arqueo_caja
            $stmtArqueo = $db->prepare("UPDATE arqueo_caja 
            SET monto_ventas = monto_ventas - (:totalCompra),
             monto_ventas_efectivo = monto_ventas_efectivo - (:totalEfectivo),
             monto_ventas_qr = monto_ventas_qr - (:totalQR),
             total_ingresos = total_ingresos - (:totalCompra),
             resultado_neto = (total_ingresos - total_egresos)
             WHERE id = :idArqueo");
            $stmtArqueo->bindParam(":idArqueo", $idArqueo, PDO::PARAM_INT);
            $stmtArqueo->bindParam(":totalCompra", $totalIngreso, PDO::PARAM_STR);
            $stmtArqueo->bindParam(":totalEfectivo", $totalEfectivo, PDO::PARAM_STR);
            $stmtArqueo->bindParam(":totalQR", $totalQR, PDO::PARAM_STR); 

            $actualizacionExitosa = $stmtArqueo->execute();

            if (!$actualizacionExitosa) {
                throw new Exception("Fallo al actualizar el ingreso en arqueo_caja.");
            }

            // Si todo está bien, confirmar la transacción
            $db->commit();
            return [
                'status' => 'ok',
                'message' => 'Actualización exitosa del total de ingreso.'
            ];

        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            $db->rollBack();
            error_log("Error en mdlEliminarIngreso: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Error al actualizar: ' . $e->getMessage()
            ];
        } finally {
            // Liberar recursos (opcional, PDO lo hace automáticamente)
            if (isset($stmtArqueo)) {
                $stmtArqueo = null;
            }
            $db = null; // Cerrar la conexión (opcional si usas un singleton)
        }
    }

/**
     * Suma compras activas que descuentan de caja (egresos / efectivo disponible)
     */
    static public function mdlSumarComprasPorArqueo($idArqueo, $pdo = null) {
        $conexion = $pdo ?: Conexion::conectar();
        try {
            $stmt = $conexion->prepare(
                "SELECT COALESCE(SUM(total), 0) AS total
                 FROM compras
                 WHERE id_arqueo_caja = :id_arqueo_caja
                   AND estado = 1
                   AND descontar_caja = 1"
            );
        } catch (PDOException $e) {
            $stmt = $conexion->prepare(
                "SELECT COALESCE(SUM(total), 0) AS total
                 FROM compras
                 WHERE id_arqueo_caja = :id_arqueo_caja
                   AND estado = 1"
            );
        }
        $stmt->bindValue(":id_arqueo_caja", intval($idArqueo), PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return floatval($row["total"] ?? 0);
    }

    /**
     * Suma todas las compras activas del arqueo (bloque informativo, no afecta cuadre)
     */
    static public function mdlSumarComprasInformativasPorArqueo($idArqueo, $pdo = null) {
        $conexion = $pdo ?: Conexion::conectar();
        $stmt = $conexion->prepare(
            "SELECT COALESCE(SUM(total), 0) AS total
             FROM compras
             WHERE id_arqueo_caja = :id_arqueo_caja
               AND estado = 1"
        );
        $stmt->bindValue(":id_arqueo_caja", intval($idArqueo), PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return floatval($row["total"] ?? 0);
    }

    /**
     * Suma gastos asociados a un arqueo
     */
    static public function mdlSumarGastosPorArqueo($idArqueo, $pdo = null) {
        $conexion = $pdo ?: Conexion::conectar();
        $stmt = $conexion->prepare(
            "SELECT COALESCE(SUM(monto), 0) AS total
             FROM gastos
             WHERE id_arqueo = :id_arqueo"
        );
        $stmt->bindValue(":id_arqueo", intval($idArqueo), PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return floatval($row["total"] ?? 0);
    }

    /**
     * Suma solo la parte en efectivo de gastos (para disponibilidad de caja)
     * forma_pago: 1=Efectivo, 2=QR, 3=Transferencia, 4=Mixto
     */
    static public function mdlSumarGastosEfectivoPorArqueo($idArqueo, $pdo = null) {
        try {
            $conexion = $pdo ?: Conexion::conectar();
            $stmt = $conexion->prepare(
                "SELECT COALESCE(SUM(
                    CASE
                        WHEN forma_pago IN (1, '1') THEN COALESCE(NULLIF(monto_efectivo, 0), monto)
                        WHEN forma_pago IN (4, '4') THEN COALESCE(monto_efectivo, 0)
                        ELSE 0
                    END
                 ), 0) AS total
                 FROM gastos
                 WHERE id_arqueo = :id_arqueo"
            );
            $stmt->bindValue(":id_arqueo", intval($idArqueo), PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return floatval($row["total"] ?? 0);
        } catch (PDOException $e) {
            error_log("Error en mdlSumarGastosEfectivoPorArqueo: " . $e->getMessage());
            // Fallback si aún no existen columnas monto_efectivo/monto_qr
            try {
                $conexion = $pdo ?: Conexion::conectar();
                $stmt = $conexion->prepare(
                    "SELECT COALESCE(SUM(monto), 0) AS total
                     FROM gastos
                     WHERE id_arqueo = :id_arqueo
                       AND forma_pago IN (1, '1')"
                );
                $stmt->bindValue(":id_arqueo", intval($idArqueo), PDO::PARAM_INT);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                return floatval($row["total"] ?? 0);
            } catch (PDOException $e2) {
                return self::mdlSumarGastosPorArqueo($idArqueo, $pdo);
            }
        }
    }

    /**
     * Suma otros ingresos (no ventas) asociados a un arqueo
     */
    static public function mdlSumarOtrosIngresosPorArqueo($idArqueo, $pdo = null) {
        try {
            $conexion = $pdo ?: Conexion::conectar();
            $stmt = $conexion->prepare(
                "SELECT COALESCE(SUM(monto), 0) AS total
                 FROM otros_ingresos
                 WHERE id_arqueo_caja = :id_arqueo_caja
                   AND estado = 1"
            );
            $stmt->bindValue(":id_arqueo_caja", intval($idArqueo), PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return floatval($row["total"] ?? 0);
        } catch (PDOException $e) {
            error_log("Error en mdlSumarOtrosIngresosPorArqueo: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Suma solo la parte en efectivo de otros ingresos (para disponibilidad de caja)
     */
    static public function mdlSumarOtrosIngresosEfectivoPorArqueo($idArqueo, $pdo = null) {
        try {
            $conexion = $pdo ?: Conexion::conectar();
            $stmt = $conexion->prepare(
                "SELECT COALESCE(SUM(COALESCE(monto_efectivo, monto)), 0) AS total
                 FROM otros_ingresos
                 WHERE id_arqueo_caja = :id_arqueo_caja
                   AND estado = 1"
            );
            $stmt->bindValue(":id_arqueo_caja", intval($idArqueo), PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return floatval($row["total"] ?? 0);
        } catch (PDOException $e) {
            error_log("Error en mdlSumarOtrosIngresosEfectivoPorArqueo: " . $e->getMessage());
            return self::mdlSumarOtrosIngresosPorArqueo($idArqueo, $pdo);
        }
    }

    /**
     * Suma ventas pagadas en efectivo de un arqueo
     */
    static public function mdlSumarVentasEfectivoPorArqueo($idArqueo, $pdo = null) {
        $conexion = $pdo ?: Conexion::conectar();
        $stmt = $conexion->prepare(
            "SELECT COALESCE(SUM(total_efectivo), 0) AS total
             FROM ventas
             WHERE id_arqueo_caja = :id_arqueo_caja
               AND estado = 1
               AND estado_pago = 'PAGADA'"
        );
        $stmt->bindValue(":id_arqueo_caja", intval($idArqueo), PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return floatval($row["total"] ?? 0);
    }

    /**
     * Suma ventas pagadas (todas) de un arqueo
     */
    static public function mdlSumarVentasPagadasPorArqueo($idArqueo, $pdo = null) {
        $conexion = $pdo ?: Conexion::conectar();
        $stmt = $conexion->prepare(
            "SELECT
                COALESCE(SUM(total), 0) AS total,
                COALESCE(SUM(total_efectivo), 0) AS total_efectivo,
                COALESCE(SUM(total_qr), 0) AS total_qr,
                COALESCE(SUM(COALESCE(total_bruto, total)), 0) AS total_bruto,
                COALESCE(SUM(COALESCE(total_descuento, 0)), 0) AS total_descuento
             FROM ventas
             WHERE id_arqueo_caja = :id_arqueo_caja
               AND estado = 1
               AND estado_pago = 'PAGADA'"
        );
        $stmt->bindValue(":id_arqueo_caja", intval($idArqueo), PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return [
            "total" => floatval($row["total"] ?? 0),
            "total_efectivo" => floatval($row["total_efectivo"] ?? 0),
            "total_qr" => floatval($row["total_qr"] ?? 0),
            "total_bruto" => floatval($row["total_bruto"] ?? 0),
            "total_descuento" => floatval($row["total_descuento"] ?? 0)
        ];
    }

    /**
     * Efectivo disponible para egresos en efectivo (compras/gastos)
     */
    static public function mdlCalcularEfectivoDisponible($idArqueo, $pdo = null) {
        $conexion = $pdo ?: Conexion::conectar();
        $forUpdate = ($pdo && $pdo->inTransaction()) ? " FOR UPDATE" : "";

        $stmt = $conexion->prepare(
            "SELECT monto_apertura, estado
             FROM arqueo_caja
             WHERE id = :id" . $forUpdate
        );
        $stmt->bindValue(":id", intval($idArqueo), PDO::PARAM_INT);
        $stmt->execute();
        $arqueo = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$arqueo) {
            return [
                "ok" => false,
                "mensaje" => "La caja no existe.",
                "disponible" => 0,
                "arqueo" => null
            ];
        }

        if (($arqueo["estado"] ?? "") !== "abierta") {
            return [
                "ok" => false,
                "mensaje" => "La caja no está abierta.",
                "disponible" => 0,
                "arqueo" => $arqueo
            ];
        }

        $montoApertura = floatval($arqueo["monto_apertura"] ?? 0);
        $ventasEfectivo = self::mdlSumarVentasEfectivoPorArqueo($idArqueo, $conexion);
        $otrosIngresosEfectivo = self::mdlSumarOtrosIngresosEfectivoPorArqueo($idArqueo, $conexion);
        $compras = self::mdlSumarComprasPorArqueo($idArqueo, $conexion);
        $gastos = self::mdlSumarGastosEfectivoPorArqueo($idArqueo, $conexion);
        $disponible = $montoApertura + $ventasEfectivo + $otrosIngresosEfectivo - $gastos - $compras;

        return [
            "ok" => true,
            "disponible" => round($disponible, 2),
            "monto_apertura" => $montoApertura,
            "ventas_efectivo" => $ventasEfectivo,
            "otros_ingresos" => $otrosIngresosEfectivo,
            "compras" => $compras,
            "gastos" => $gastos,
            "arqueo" => $arqueo
        ];
    }

    /**
     * Recalcula y sincroniza montos denormalizados del arqueo desde tablas fuente
     */
    static public function mdlSincronizarMontosArqueo($idArqueo) {
        try {
            $pdo = Conexion::conectar();
            $stmt = $pdo->prepare("SELECT * FROM arqueo_caja WHERE id = :id LIMIT 1");
            $stmt->bindValue(":id", intval($idArqueo), PDO::PARAM_INT);
            $stmt->execute();
            $arqueo = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$arqueo) {
                return null;
            }

            $ventas = self::mdlSumarVentasPagadasPorArqueo($idArqueo, $pdo);
            $compras = self::mdlSumarComprasPorArqueo($idArqueo, $pdo);
            $gastos = self::mdlSumarGastosPorArqueo($idArqueo, $pdo);
            $otrosIngresos = self::mdlSumarOtrosIngresosPorArqueo($idArqueo, $pdo);
            $montoApertura = floatval($arqueo["monto_apertura"] ?? 0);
            $totalIngresos = $montoApertura + $ventas["total"] + $otrosIngresos;
            $totalEgresos = $compras + $gastos;
            $resultadoNeto = $totalIngresos - $totalEgresos;

            $stmtUpdate = $pdo->prepare(
                "UPDATE arqueo_caja SET
                    monto_ventas = :monto_ventas,
                    monto_ventas_efectivo = :monto_ventas_efectivo,
                    monto_ventas_qr = :monto_ventas_qr,
                    monto_compras = :monto_compras,
                    gastos_operativos = :gastos_operativos,
                    total_ingresos = :total_ingresos,
                    total_egresos = :total_egresos,
                    resultado_neto = :resultado_neto
                 WHERE id = :id"
            );
            $stmtUpdate->bindValue(":monto_ventas", $ventas["total"], PDO::PARAM_STR);
            $stmtUpdate->bindValue(":monto_ventas_efectivo", $ventas["total_efectivo"], PDO::PARAM_STR);
            $stmtUpdate->bindValue(":monto_ventas_qr", $ventas["total_qr"], PDO::PARAM_STR);
            $stmtUpdate->bindValue(":monto_compras", $compras, PDO::PARAM_STR);
            $stmtUpdate->bindValue(":gastos_operativos", $gastos, PDO::PARAM_STR);
            $stmtUpdate->bindValue(":total_ingresos", $totalIngresos, PDO::PARAM_STR);
            $stmtUpdate->bindValue(":total_egresos", $totalEgresos, PDO::PARAM_STR);
            $stmtUpdate->bindValue(":resultado_neto", $resultadoNeto, PDO::PARAM_STR);
            $stmtUpdate->bindValue(":id", intval($idArqueo), PDO::PARAM_INT);
            $stmtUpdate->execute();

            $arqueo["monto_ventas"] = $ventas["total"];
            $arqueo["monto_ventas_efectivo"] = $ventas["total_efectivo"];
            $arqueo["monto_ventas_qr"] = $ventas["total_qr"];
            $arqueo["monto_compras"] = $compras;
            $arqueo["gastos_operativos"] = $gastos;
            $arqueo["total_ingresos"] = $totalIngresos;
            $arqueo["total_egresos"] = $totalEgresos;
            $arqueo["resultado_neto"] = $resultadoNeto;
            $arqueo["otros_ingresos"] = $otrosIngresos;
            // Informativo comercial: no afecta ingresos/egresos de caja
            $arqueo["total_bruto_ventas"] = $ventas["total_bruto"];
            $arqueo["total_descuentos_ventas"] = $ventas["total_descuento"];
            $arqueo["monto_compras_informativo"] = self::mdlSumarComprasInformativasPorArqueo($idArqueo, $pdo);

            return $arqueo;
        } catch (PDOException $e) {
            error_log("Error en mdlSincronizarMontosArqueo: " . $e->getMessage());
            return null;
        }
    }

    public static function mdlRegistrarEgreso($idArqueo, $totalEgreso, $nombreCampo = "monto_compras") {
        $db = Conexion::conectar(); // Obtener la conexión PDO
        $db->beginTransaction(); // Iniciar transacción
        try {

            // Preparar y ejecutar la actualización en la tabla arqueo_caja
            $stmtArqueo = $db->prepare("UPDATE arqueo_caja 
            SET $nombreCampo = $nombreCampo + (:egresoNuevo), 
             total_egresos = total_egresos + (:egresoNuevo), 
             resultado_neto = (total_ingresos - total_egresos) 
             WHERE id = :idArqueo");
            $stmtArqueo->bindParam(":idArqueo", $idArqueo, PDO::PARAM_INT);
            $stmtArqueo->bindParam(":egresoNuevo", $totalEgreso, PDO::PARAM_STR); 

            $actualizacionExitosa = $stmtArqueo->execute();

            if (!$actualizacionExitosa) {
                throw new Exception("Fallo al actualizar el número de ticket en arqueo_caja.");
            }

            // Si todo está bien, confirmar la transacción
            $db->commit();
            return [
                'status' => 'ok',
                'message' => 'Actualización exitosa del total de egreso.'
            ];

        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            $db->rollBack();
            error_log("Error en mdlRegistrarEgreso: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Error al actualizar: ' . $e->getMessage()
            ];
        } finally {
            // Liberar recursos (opcional, PDO lo hace automáticamente)
            if (isset($stmtArqueo)) {
                $stmtArqueo = null;
            }
            $db = null; // Cerrar la conexión (opcional si usas un singleton)
        }
    }
    public static function mdlEliminarEgreso($idArqueo, $totalEgreso, $nombreCampo = "monto_compras") {
        $db = Conexion::conectar(); // Obtener la conexión PDO
        $db->beginTransaction(); // Iniciar transacción
        try {
            // Preparar y ejecutar la actualización en la tabla arqueo_caja
            $stmtArqueo = $db->prepare("UPDATE arqueo_caja 
            SET $nombreCampo = $nombreCampo - (:egresoDescontar), 
             total_egresos = total_egresos - (:egresoDescontar), 
             resultado_neto = (total_ingresos - total_egresos) 
             WHERE id = :idArqueo");
            $stmtArqueo->bindParam(":idArqueo", $idArqueo, PDO::PARAM_INT);
            $stmtArqueo->bindParam(":egresoDescontar", $totalEgreso, PDO::PARAM_STR); 

            $actualizacionExitosa = $stmtArqueo->execute();

            if (!$actualizacionExitosa) {
                throw new Exception("Fallo al actualizar el número de ticket en arqueo_caja.");
            }

            // Si todo está bien, confirmar la transacción
            $db->commit();
            return [
                'status' => 'ok',
                'message' => 'Actualización exitosa del total de egreso.'
            ];

        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            $db->rollBack();
            error_log("Error en mdlRegistrarEgreso: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Error al actualizar: ' . $e->getMessage()
            ];
        } finally {
            // Liberar recursos (opcional, PDO lo hace automáticamente)
            if (isset($stmtArqueo)) {
                $stmtArqueo = null;
            }
            $db = null; // Cerrar la conexión (opcional si usas un singleton)
        }
    }
    /**
     * Registra el cierre de una caja
     * @param array $datos Datos del cierre
     * @return array Resultado de la operación
     */
    static public function mdlRegistrarCierreCaja($datos) {
        $pdo = null;
        try {
            $pdo = Conexion::conectar();
            $pdo->beginTransaction();

            $stmtArqueo = $pdo->prepare(
                "SELECT id, id_caja, estado
                 FROM arqueo_caja
                 WHERE id = :id_arqueo
                 FOR UPDATE"
            );
            $stmtArqueo->bindParam(":id_arqueo", $datos["id_arqueo"], PDO::PARAM_INT);
            $stmtArqueo->execute();
            $arqueo = $stmtArqueo->fetch(PDO::FETCH_ASSOC);

            if (!$arqueo || $arqueo["estado"] !== "abierta") {
                $pdo->rollBack();
                return [
                    "status" => "error",
                    "mensaje" => "La caja no está abierta o no existe."
                ];
            }

            $stmtPendientes = $pdo->prepare(
                "SELECT id, total
                 FROM ventas
                 WHERE id_arqueo_caja = :id_arqueo_caja
                   AND estado = 1
                   AND estado_pago = 'PENDIENTE'
                 FOR UPDATE"
            );
            $stmtPendientes->bindParam(":id_arqueo_caja", $datos["id_arqueo"], PDO::PARAM_INT);
            $stmtPendientes->execute();
            $pendientes = $stmtPendientes->fetchAll(PDO::FETCH_ASSOC);
            $cantidadPendientes = count($pendientes);
            $totalPendiente = array_reduce($pendientes, function ($carry, $item) {
                return $carry + floatval($item["total"]);
            }, 0);

            if ($cantidadPendientes > 0) {
                $pdo->rollBack();
                return [
                    "status" => "error",
                    "codigo" => "cuentas_pendientes",
                    "cantidad" => $cantidadPendientes,
                    "total_pendiente" => $totalPendiente,
                    "mensaje" => "No se puede cerrar la caja porque existen cuentas pendientes de cobro.\n\n"
                        . "Cantidad de cuentas: " . $cantidadPendientes . "\n"
                        . "Total pendiente: Bs " . number_format($totalPendiente, 2, '.', '') . "\n\n"
                        . "Debe cobrar o anular estas cuentas antes de cerrar la caja."
                ];
            }

            $datos["cuentas_pendientes_cantidad"] = 0;
            $datos["cuentas_pendientes_total"] = 0;

            $stmt = $pdo->prepare("UPDATE arqueo_caja SET 
                fecha_cierre = :fecha_cierre,
                Bs200 = :Bs200,
                Bs100 = :Bs100,
                Bs50 = :Bs50,
                Bs20 = :Bs20,
                Bs10 = :Bs10,
                Bs5 = :Bs5,
                Bs2 = :Bs2,
                Bs1 = :Bs1,
                Bs050 = :Bs050,
                Bs020 = :Bs020,
                total_ingresos = :total_ingresos,
                monto_ventas_efectivo = :monto_ventas_efectivo,
                monto_ventas_qr = :monto_ventas_qr,
                monto_ventas = :monto_ventas,
                total_egresos = :total_egresos,
                gastos_operativos = :gastos_operativos,
                monto_compras = :monto_compras,
                resultado_neto = :resultado_neto,
                efectivo_en_caja = :efectivo_en_caja,
                qr_en_caja = :qr_en_caja,
                total_efectivo_qr_en_caja = :total_efectivo_qr_en_caja,
                diferencia = :diferencia,
                estado = :estado,
                cuentas_pendientes_cantidad = :cuentas_pendientes_cantidad,
                cuentas_pendientes_total = :cuentas_pendientes_total
                WHERE id = :id_arqueo
                  AND estado = 'abierta'");

            $stmt->bindParam(":fecha_cierre", $datos["fecha_cierre"], PDO::PARAM_STR);
            $stmt->bindParam(":Bs200", $datos["Bs200"], PDO::PARAM_INT);
            $stmt->bindParam(":Bs100", $datos["Bs100"], PDO::PARAM_INT);
            $stmt->bindParam(":Bs50", $datos["Bs50"], PDO::PARAM_INT);
            $stmt->bindParam(":Bs20", $datos["Bs20"], PDO::PARAM_INT);
            $stmt->bindParam(":Bs10", $datos["Bs10"], PDO::PARAM_INT);
            $stmt->bindParam(":Bs5", $datos["Bs5"], PDO::PARAM_INT);
            $stmt->bindParam(":Bs2", $datos["Bs2"], PDO::PARAM_INT);
            $stmt->bindParam(":Bs1", $datos["Bs1"], PDO::PARAM_INT);
            $stmt->bindParam(":Bs050", $datos["Bs050"], PDO::PARAM_INT);
            $stmt->bindParam(":Bs020", $datos["Bs020"], PDO::PARAM_INT);
            $stmt->bindParam(":total_ingresos", $datos["total_ingresos"], PDO::PARAM_STR);
            $stmt->bindParam(":monto_ventas_efectivo", $datos["monto_ventas_efectivo"], PDO::PARAM_STR);
            $stmt->bindParam(":monto_ventas_qr", $datos["monto_ventas_qr"], PDO::PARAM_STR);
            $stmt->bindParam(":monto_ventas", $datos["monto_ventas"], PDO::PARAM_STR);
            $stmt->bindParam(":total_egresos", $datos["total_egresos"], PDO::PARAM_STR);
            $stmt->bindParam(":gastos_operativos", $datos["gastos_operativos"], PDO::PARAM_STR);
            $stmt->bindParam(":monto_compras", $datos["monto_compras"], PDO::PARAM_STR);
            $stmt->bindParam(":resultado_neto", $datos["resultado_neto"], PDO::PARAM_STR);
            $stmt->bindParam(":efectivo_en_caja", $datos["efectivo_en_caja"], PDO::PARAM_STR);
            $stmt->bindParam(":qr_en_caja", $datos["qr_en_caja"], PDO::PARAM_STR);
            $stmt->bindParam(":total_efectivo_qr_en_caja", $datos["total_efectivo_qr_en_caja"], PDO::PARAM_STR);
            $stmt->bindParam(":diferencia", $datos["diferencia"], PDO::PARAM_STR);
            $stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_STR);
            $stmt->bindParam(":cuentas_pendientes_cantidad", $datos["cuentas_pendientes_cantidad"], PDO::PARAM_INT);
            $stmt->bindParam(":cuentas_pendientes_total", $datos["cuentas_pendientes_total"], PDO::PARAM_STR);
            $stmt->bindParam(":id_arqueo", $datos["id_arqueo"], PDO::PARAM_INT);

            if(!$stmt->execute() || $stmt->rowCount() === 0) {
                $pdo->rollBack();
                return [
                    "status" => "error",
                    "mensaje" => "No se pudo cerrar la caja."
                ];
            }

            $idCaja = intval($datos["id_caja"] ?? $arqueo["id_caja"]);
            self::mdlActualizarNroCaja($idCaja, 0);

            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION["idArqueoCaja"] = null;
            $_SESSION["idCaja"] = null;

            $pdo->commit();
            return ["status" => "ok"];
        } catch (PDOException $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log("Error en mdlRegistrarCierreCaja: " . $e->getMessage());
            return [
                "status" => "error",
                "mensaje" => "Error al cerrar la caja."
            ];
        } finally {
            if(isset($stmt)) {
                $stmt->closeCursor();
                $stmt = null;
            }
        }
    }


    static public function mdlObtnerArqueoPorIDUsuario($id_usuario) {
        try {
            $stmt = Conexion::conectar()->prepare("SELECT * 
            FROM arqueo_caja 
            /*WHERE id_usuario = :id_usuario*/ 
            WHERE estado = 'abierta' 
     
            ORDER BY id DESC 
            LIMIT 1");
            
            //$stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
            $stmt->execute();
    
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Verificar si se obtuvo algún resultado
            if ($resultado) {
                return $resultado; // Devuelve solo el ID
            }
            return null; // Si no hay resultado, devuelve null
        } catch (PDOException $e) {
            error_log("Error en mdlObtnerIdArqueoDelUsuario: " . $e->getMessage());
            return null;
        } finally {
            if (isset($stmt)) {
                $stmt->closeCursor();
                $stmt = null;
            }
        }
    }

    
    static public function mdlObtnerArqueoPorIDArqueo($idArqueo) {
        try {
            $stmt = Conexion::conectar()->prepare("SELECT * 
            FROM arqueo_caja 
            WHERE id = :idArqueo
            ORDER BY id DESC 
            LIMIT 1");
            
            $stmt->bindParam(":idArqueo", $idArqueo, PDO::PARAM_INT);
            $stmt->execute();
    
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Verificar si se obtuvo algún resultado
            if ($resultado) {
                return $resultado; // Devuelve solo el ID
            }
            return null; // Si no hay resultado, devuelve null
        } catch (PDOException $e) {
            error_log("Error en mdlObtnerArqueoPorIDArqueo: " . $e->getMessage());
            return null;
        } finally {
            if (isset($stmt)) {
                $stmt->closeCursor();
                $stmt = null;
            }
        }
    }

    /**
     * Sincroniza en el arqueo abierto el resumen informativo de cuentas pendientes
     * Solo considera ventas pendientes asociadas a esa misma caja (id_arqueo_caja).
     */
    static public function mdlSincronizarCuentasPendientesEnArqueoAbierto($idArqueo = null) {
        try {
            $pdo = Conexion::conectar();

            $obtenerResumen = function ($pdoConn, $id) {
                $stmtResumen = $pdoConn->prepare(
                    "SELECT COUNT(*) AS cantidad, COALESCE(SUM(total), 0) AS total_por_cobrar
                     FROM ventas
                     WHERE id_arqueo_caja = :id_arqueo_caja
                       AND estado = 1
                       AND estado_pago = 'PENDIENTE'"
                );
                $stmtResumen->bindValue(":id_arqueo_caja", intval($id), PDO::PARAM_INT);
                $stmtResumen->execute();
                $resumen = $stmtResumen->fetch(PDO::FETCH_ASSOC);
                return [
                    "cantidad" => intval($resumen["cantidad"] ?? 0),
                    "total_por_cobrar" => floatval($resumen["total_por_cobrar"] ?? 0)
                ];
            };

            if ($idArqueo !== null) {
                $resumen = $obtenerResumen($pdo, $idArqueo);
                $stmt = $pdo->prepare(
                    "UPDATE arqueo_caja
                     SET cuentas_pendientes_cantidad = :cantidad,
                         cuentas_pendientes_total = :total
                     WHERE id = :id_arqueo
                       AND estado = 'abierta'"
                );
                $stmt->bindValue(":cantidad", intval($resumen["cantidad"]), PDO::PARAM_INT);
                $stmt->bindValue(":total", $resumen["total_por_cobrar"], PDO::PARAM_STR);
                $stmt->bindValue(":id_arqueo", intval($idArqueo), PDO::PARAM_INT);
                $stmt->execute();
                return;
            }

            $stmtAbiertos = $pdo->query(
                "SELECT id FROM arqueo_caja WHERE estado = 'abierta'"
            );
            $abiertos = $stmtAbiertos->fetchAll(PDO::FETCH_ASSOC);

            foreach ($abiertos as $arqueo) {
                $resumen = $obtenerResumen($pdo, $arqueo["id"]);
                $stmt = $pdo->prepare(
                    "UPDATE arqueo_caja
                     SET cuentas_pendientes_cantidad = :cantidad,
                         cuentas_pendientes_total = :total
                     WHERE id = :id_arqueo
                       AND estado = 'abierta'"
                );
                $stmt->bindValue(":cantidad", intval($resumen["cantidad"]), PDO::PARAM_INT);
                $stmt->bindValue(":total", $resumen["total_por_cobrar"], PDO::PARAM_STR);
                $stmt->bindValue(":id_arqueo", intval($arqueo["id"]), PDO::PARAM_INT);
                $stmt->execute();
            }
        } catch (PDOException $e) {
            error_log("Error en mdlSincronizarCuentasPendientesEnArqueoAbierto: " . $e->getMessage());
        }
    }
    
}