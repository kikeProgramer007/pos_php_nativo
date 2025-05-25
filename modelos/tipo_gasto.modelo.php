<?php

require_once "conexion.php";

class ModeloTipoGasto{

    /*=============================================
    CREAR TIPO DE GASTO
    =============================================*/

    static public function mdlIngresarTipoGasto($tabla, $datos){

        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(tipo_gasto) VALUES (:tipo_gasto)");

        $stmt->bindParam(":tipo_gasto", $datos["tipo_gasto"], PDO::PARAM_STR);

        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }

        $stmt->close();
        $stmt = null;
    }


    /*=============================================
    MOSTRAR TIPO DE GASTO
    =============================================*/

    static public function mdlMostrarTipoGasto($tabla, $item, $valor){

        if($item != null){
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
            $stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);
        }
        else{
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
        }
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt->close();
        $stmt = null;
    }


    /*=============================================
    BORRAR TIPO DE GASTO
    =============================================*/

    static public function mdlBorrarTipoGasto($tabla, $datos){

        $stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id = :id");

        $stmt->bindParam(":id", $datos["id"], PDO::PARAM_INT);

        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }

        $stmt->close();
        $stmt = null;
    }

    /*=============================================
    EDITAR TIPO DE GASTO
    =============================================*/     

    static public function mdlEditarTipoGasto($tabla, $datos){

        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET tipo_gasto = :tipo_gasto WHERE id = :id");

        $stmt->bindParam(":tipo_gasto", $datos["tipo_gasto"], PDO::PARAM_STR);

        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }

        $stmt->close();
        $stmt = null;
    }
    
}