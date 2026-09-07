<?php

require_once __DIR__ . "/conexion.php";

class ModeloGastos{

    /*=============================================
    REGISTRAR GASTO
    =============================================*/
    
    static public function mdlRegistrarGasto($tabla, $datos){
        
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(id_tipo_gasto, descripcion, fecha, monto, monto_efectivo, monto_qr, forma_pago, id_usuario, id_arqueo)
         VALUES (:id_tipo_gasto, :descripcion, :fecha, :monto, :monto_efectivo, :monto_qr, :forma_pago, :id_usuario, :id_arqueo)");
        
        $stmt->bindParam(":id_tipo_gasto", $datos["id_tipo_gasto"], PDO::PARAM_INT);
        $stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
        $stmt->bindParam(":fecha", $datos["fecha"], PDO::PARAM_STR);
        $stmt->bindParam(":monto", $datos["monto"], PDO::PARAM_STR);
        $stmt->bindParam(":monto_efectivo", $datos["monto_efectivo"], PDO::PARAM_STR);
        $stmt->bindParam(":monto_qr", $datos["monto_qr"], PDO::PARAM_STR);
        $stmt->bindParam(":forma_pago", $datos["forma_pago"], PDO::PARAM_INT);
        $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
        $stmt->bindParam(":id_arqueo", $datos["id_arqueo"], PDO::PARAM_INT);
        
        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }
        
        $stmt->close();
        $stmt = null;
    }
    
    /*=============================================
    MOSTRAR GASTOS
    =============================================*/
    
    static public function mdlMostrarGastos($tabla, $item, $valor){
        
        if($item != null){
            
            $stmt = Conexion::conectar()->prepare("SELECT g.*, tg.nombre as nombre_tipo_gasto, u.nombre as nombre_usuario 
                                                   FROM $tabla g 
                                                   INNER JOIN tipo_gasto tg ON g.id_tipo_gasto = tg.id 
                                                   INNER JOIN usuarios u ON g.id_usuario = u.id 
                                                   WHERE g.$item = :$item");
            
            $stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);
            
            $stmt->execute();
            
            return $stmt->fetch();
            
        }else{
            
            $stmt = Conexion::conectar()->prepare("SELECT g.*, tg.nombre as nombre_tipo_gasto, u.nombre as nombre_usuario,
                                                    CASE g.forma_pago
                                                        WHEN 1 THEN 'Efectivo'
                                                        WHEN 2 THEN 'QR'
                                                        WHEN 3 THEN 'Transferencia'
                                                        WHEN 4 THEN 'QR y Efectivo (Mixto)'
                                                        ELSE 'Desconocido'
                                                    END AS forma_pago_descripcion 
                                                   FROM $tabla g 
                                                   INNER JOIN tipo_gasto tg ON g.id_tipo_gasto = tg.id 
                                                   INNER JOIN usuarios u ON g.id_usuario = u.id 
                                                   ORDER BY g.id DESC");
            
            $stmt->execute();
            
            return $stmt->fetchAll();
        }
        
        $stmt->close();
        $stmt = null;
    }
    
    /*=============================================
    EDITAR GASTO
    =============================================*/
    
    static public function mdlEditarGasto($tabla, $datos){
        
        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET id_tipo_gasto = :id_tipo_gasto, descripcion = :descripcion, fecha = :fecha_gasto, monto = :monto, monto_efectivo = :monto_efectivo, monto_qr = :monto_qr, forma_pago = :tipo_pago WHERE id = :id");
        
        $stmt->bindParam(":id", $datos["id"], PDO::PARAM_INT);
        $stmt->bindParam(":id_tipo_gasto", $datos["id_tipo_gasto"], PDO::PARAM_INT);
        $stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
        $stmt->bindParam(":fecha_gasto", $datos["fecha_gasto"], PDO::PARAM_STR);
        $stmt->bindParam(":monto", $datos["monto"], PDO::PARAM_STR);
        $stmt->bindParam(":monto_efectivo", $datos["monto_efectivo"], PDO::PARAM_STR);
        $stmt->bindParam(":monto_qr", $datos["monto_qr"], PDO::PARAM_STR);
        $stmt->bindParam(":tipo_pago", $datos["tipo_pago"], PDO::PARAM_INT);
        
        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }
        
        $stmt->close();
        $stmt = null;
    }
    
    /*=============================================
    ELIMINAR GASTO
    =============================================*/
    
    static public function mdlEliminarGasto($tabla, $datos){
        
        $stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id = :id");
        
        $stmt->bindParam(":id", $datos, PDO::PARAM_INT);
        
        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }
        
        $stmt->close();
        $stmt = null;
    }
    
    /*=============================================
    SUMA TOTAL GASTOS
    =============================================*/
    
    static public function mdlSumaTotalGastos($tabla){
        
        $stmt = Conexion::conectar()->prepare("SELECT SUM(monto) as total FROM $tabla");
        
        $stmt->execute();
        
        return $stmt->fetch();
        
        $stmt->close();
        $stmt = null;
    }
    
    /*=============================================
    MOSTRAR GASTOS POR ARQUEO DE CAJA
    =============================================*/
    
    static public function mdlMostrarGastosPorArqueo($tabla, $idArqueo){
        
        $stmt = Conexion::conectar()->prepare("SELECT g.*, tg.nombre as nombre_tipo_gasto 
                                               FROM $tabla g 
                                               INNER JOIN tipo_gasto tg ON g.id_tipo_gasto = tg.id 
                                               WHERE g.id_arqueo_caja = :id_arqueo_caja 
                                               ORDER BY g.fecha_gasto DESC");
        
        $stmt->bindParam(":id_arqueo_caja", $idArqueo, PDO::PARAM_INT);
        
        $stmt->execute();
        
        return $stmt->fetchAll();
        
        $stmt->close();
        $stmt = null;
    }
    
    /*=============================================
    REPORTE DE GASTOS ENTRE FECHAS (+ filtros opcionales)
    =============================================*/

    static public function mdlReporteGastosEntreFechas($fechaInicio, $fechaFin, $idTipoGasto = 0, $formaPago = 0, $idUsuarioFiltro = 0){

        $sql = "SELECT g.*, tg.nombre AS nombre_tipo_gasto, u.nombre AS nombre_usuario,
                       CASE g.forma_pago
                           WHEN 1 THEN 'Efectivo'
                           WHEN 2 THEN 'QR'
                           WHEN 3 THEN 'Transferencia'
                           WHEN 4 THEN 'QR y Efectivo (Mixto)'
                           ELSE 'Desconocido'
                       END AS forma_pago_descripcion
                FROM gastos g
                INNER JOIN tipo_gasto tg ON g.id_tipo_gasto = tg.id
                INNER JOIN usuarios u ON g.id_usuario = u.id
                WHERE g.fecha BETWEEN :fecha_inicio AND :fecha_fin";

        if (intval($idTipoGasto) > 0) {
            $sql .= " AND g.id_tipo_gasto = :id_tipo_gasto";
        }
        if (intval($formaPago) > 0) {
            $sql .= " AND g.forma_pago = :forma_pago";
        }
        if (intval($idUsuarioFiltro) > 0) {
            $sql .= " AND g.id_usuario = :id_usuario_filtro";
        }

        $sql .= " ORDER BY g.fecha ASC, g.id ASC";

        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->bindValue(":fecha_inicio", $fechaInicio, PDO::PARAM_STR);
        $stmt->bindValue(":fecha_fin", $fechaFin, PDO::PARAM_STR);

        if (intval($idTipoGasto) > 0) {
            $stmt->bindValue(":id_tipo_gasto", intval($idTipoGasto), PDO::PARAM_INT);
        }
        if (intval($formaPago) > 0) {
            $stmt->bindValue(":forma_pago", intval($formaPago), PDO::PARAM_INT);
        }
        if (intval($idUsuarioFiltro) > 0) {
            $stmt->bindValue(":id_usuario_filtro", intval($idUsuarioFiltro), PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*=============================================
    MOSTRAR GASTOS POR FECHA
    =============================================*/
    
    static public function mdlMostrarGastosPorFecha($tabla, $fechaInicial, $fechaFinal){
        
        if($fechaFinal != null){
            
            $stmt = Conexion::conectar()->prepare("SELECT g.*, tg.nombre as nombre_tipo_gasto, u.nombre as nombre_usuario 
                                                   FROM $tabla g 
                                                   INNER JOIN tipo_gasto tg ON g.id_tipo_gasto = tg.id 
                                                   INNER JOIN usuarios u ON g.id_usuario = u.id 
                                                   WHERE g.fecha_gasto BETWEEN :fechaInicial AND :fechaFinal 
                                                   ORDER BY g.fecha_gasto DESC");
            
            $stmt->bindParam(":fechaInicial", $fechaInicial, PDO::PARAM_STR);
            $stmt->bindParam(":fechaFinal", $fechaFinal, PDO::PARAM_STR);
            
        }else{
            
            $stmt = Conexion::conectar()->prepare("SELECT g.*, tg.nombre as nombre_tipo_gasto, u.nombre as nombre_usuario 
                                                   FROM $tabla g 
                                                   INNER JOIN tipo_gasto tg ON g.id_tipo_gasto = tg.id 
                                                   INNER JOIN usuarios u ON g.id_usuario = u.id 
                                                   WHERE g.fecha_gasto = :fechaInicial 
                                                   ORDER BY g.fecha_gasto DESC");
            
            $stmt->bindParam(":fechaInicial", $fechaInicial, PDO::PARAM_STR);
        }
        
        $stmt->execute();
        
        return $stmt->fetchAll();
        
        $stmt->close();
        $stmt = null;
    }
}