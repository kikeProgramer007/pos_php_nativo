<?php

require_once "conexion.php";

/**
 * Modelo para gestionar las operaciones de arqueo de caja en la base de datos
 */
class ModeloArqueo {

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
    public static function mdlRegistrarIngreso($Arqueo, $nroTicket, $totalVentas) {
        $db = Conexion::conectar(); // Obtener la conexión PDO
        $db->beginTransaction(); // Iniciar transacción
        try {

            // Preparar y ejecutar la actualización en la tabla arqueo_caja
            $stmtArqueo = $db->prepare("UPDATE arqueo_caja 
            SET nroTicket = :nroTicket,
             monto_ventas = monto_ventas + (:totalVentas), 
             total_ingresos = total_ingresos + (:totalVentas), 
             resultado_neto = total_ingresos - total_egresos 
             WHERE id = :idArqueo");
            $stmtArqueo->bindParam(":idArqueo", $Arqueo["id"], PDO::PARAM_INT);
            $stmtArqueo->bindParam(":nroTicket", $nroTicket, PDO::PARAM_STR);
            $stmtArqueo->bindParam(":totalVentas", $totalVentas, PDO::PARAM_STR); 

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
    public static function mdlEliminarIngreso($idArqueo, $totalIngreso) {
        $db = Conexion::conectar(); // Obtener la conexión PDO
        $db->beginTransaction(); // Iniciar transacción
        try {

            // Preparar y ejecutar la actualización en la tabla arqueo_caja
            $stmtArqueo = $db->prepare("UPDATE arqueo_caja 
            SET monto_ventas = monto_ventas - (:totalCompra),
             total_ingresos = total_ingresos - (:totalCompra),
             resultado_neto = (total_ingresos - total_egresos),
             resultado_neto = (total_ingresos - total_egresos),
             resultado_neto = (total_ingresos - total_egresos)
             WHERE id = :idArqueo");
            $stmtArqueo->bindParam(":idArqueo", $idArqueo, PDO::PARAM_INT);
            $stmtArqueo->bindParam(":totalCompra", $totalIngreso, PDO::PARAM_STR); 

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

    public static function mdlRegistrarEgreso($idArqueo, $totalEgreso) {
        $db = Conexion::conectar(); // Obtener la conexión PDO
        $db->beginTransaction(); // Iniciar transacción
        try {

            // Preparar y ejecutar la actualización en la tabla arqueo_caja
            $stmtArqueo = $db->prepare("UPDATE arqueo_caja 
            SET monto_compras = monto_compras + (:totalCompras), 
             total_egresos = total_egresos + (:totalCompras), 
             resultado_neto = (total_ingresos - total_egresos) 
             WHERE id = :idArqueo");
            $stmtArqueo->bindParam(":idArqueo", $idArqueo, PDO::PARAM_INT);
            $stmtArqueo->bindParam(":totalCompras", $totalEgreso, PDO::PARAM_STR); 

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
     * @return string Resultado de la operación ('ok' o 'error')
     */
    static public function mdlRegistrarCierreCaja($datos) {
        try {
            $stmt = Conexion::conectar()->prepare("UPDATE arqueo_caja SET 
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
                monto_ventas = :monto_ventas,
                total_egresos = :total_egresos,
                gastos_operativos = :gastos_operativos,
                monto_compras = :monto_compras,
                resultado_neto = :resultado_neto,
                efectivo_en_caja = :efectivo_en_caja,
                diferencia = :diferencia,
                estado = :estado
                WHERE id = :id_arqueo");
             
        

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
            $stmt->bindParam(":monto_ventas", $datos["monto_ventas"], PDO::PARAM_STR);
            $stmt->bindParam(":total_egresos", $datos["total_egresos"], PDO::PARAM_STR);
            $stmt->bindParam(":gastos_operativos", $datos["gastos_operativos"], PDO::PARAM_STR);
            $stmt->bindParam(":monto_compras", $datos["monto_compras"], PDO::PARAM_STR);
            $stmt->bindParam(":resultado_neto", $datos["resultado_neto"], PDO::PARAM_STR);
            $stmt->bindParam(":efectivo_en_caja", $datos["efectivo_en_caja"], PDO::PARAM_STR);
            $stmt->bindParam(":diferencia", $datos["diferencia"], PDO::PARAM_STR);
            $stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_STR);
            $stmt->bindParam(":id_arqueo", $datos["id_arqueo"], PDO::PARAM_INT);

            if($stmt->execute()) {
                // Reiniciar el número de ticket al cerrar la caja
                self::mdlActualizarNroCaja($datos["id_caja"], 0);
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION["idArqueoCaja"] = null; // Elimina solo esa clave
                $_SESSION["idCaja"] = null;

                return "ok";
            }
            
            return "error";
        } catch (PDOException $e) {
            error_log("Error en mdlRegistrarCierreCaja: " . $e->getMessage());
            return "error";
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
            AND date(fecha_apertura) = date(now())
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
    
} 