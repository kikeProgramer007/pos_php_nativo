<?php

require_once "conexion.php";

class ModeloOtrosIngresos
{

    static public function mdlRegistrarOtroIngreso($datos)
    {
        $stmt = Conexion::conectar()->prepare(
            "INSERT INTO otros_ingresos (id_arqueo_caja, id_usuario, descripcion, monto, fecha, estado)
             VALUES (:id_arqueo_caja, :id_usuario, :descripcion, :monto, NOW(), 1)"
        );

        $stmt->bindParam(":id_arqueo_caja", $datos["id_arqueo_caja"], PDO::PARAM_INT);
        $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
        $stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
        $stmt->bindParam(":monto", $datos["monto"], PDO::PARAM_STR);

        if ($stmt->execute()) {
            return "ok";
        }

        return "error";
    }

    static public function mdlMostrarOtrosIngresos($item, $valor)
    {
        if ($item != null) {
            $stmt = Conexion::conectar()->prepare(
                "SELECT oi.*, u.nombre AS nombre_usuario
                 FROM otros_ingresos oi
                 INNER JOIN usuarios u ON oi.id_usuario = u.id
                 WHERE oi.$item = :valor
                 LIMIT 1"
            );
            $stmt->bindParam(":valor", $valor, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        $stmt = Conexion::conectar()->prepare(
            "SELECT oi.*, u.nombre AS nombre_usuario
             FROM otros_ingresos oi
             INNER JOIN usuarios u ON oi.id_usuario = u.id
             WHERE oi.estado = 1
             ORDER BY oi.id DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    static public function mdlMostrarPorArqueo($idArqueo)
    {
        $stmt = Conexion::conectar()->prepare(
            "SELECT oi.*, u.nombre AS nombre_usuario
             FROM otros_ingresos oi
             INNER JOIN usuarios u ON oi.id_usuario = u.id
             WHERE oi.id_arqueo_caja = :id_arqueo_caja
               AND oi.estado = 1
             ORDER BY oi.id ASC"
        );
        $stmt->bindValue(":id_arqueo_caja", intval($idArqueo), PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    static public function mdlSumarPorArqueo($idArqueo, $pdo = null)
    {
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
            error_log("Error en mdlSumarPorArqueo otros_ingresos: " . $e->getMessage());
            return 0;
        }
    }
}
