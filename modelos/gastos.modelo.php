<?php

require_once "conexion.php";

class ModeloGastos{

    /*=============================================
    REGISTRAR GASTO
    =============================================*/
    
    static public function mdlRegistrarGasto($tabla, $datos){
        
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(id_tipo_gasto, descripcion, fecha, monto, forma_pago, id_usuario, id_arqueo)
         VALUES (:id_tipo_gasto, :descripcion, :fecha, :monto, :forma_pago, :id_usuario, :id_arqueo)");
        
        $stmt->bindParam(":id_tipo_gasto", $datos["id_tipo_gasto"], PDO::PARAM_INT);
        $stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
        $stmt->bindParam(":fecha", $datos["fecha"], PDO::PARAM_STR);
        $stmt->bindParam(":monto", $datos["monto"], PDO::PARAM_STR);
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
        
        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET id_tipo_gasto = :id_tipo_gasto, descripcion = :descripcion, fecha = :fecha_gasto, monto = :monto, forma_pago = :tipo_pago WHERE id = :id");
        
        $stmt->bindParam(":id", $datos["id"], PDO::PARAM_INT);
        $stmt->bindParam(":id_tipo_gasto", $datos["id_tipo_gasto"], PDO::PARAM_INT);
        $stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
        $stmt->bindParam(":fecha_gasto", $datos["fecha_gasto"], PDO::PARAM_STR);
        $stmt->bindParam(":monto", $datos["monto"], PDO::PARAM_STR);
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