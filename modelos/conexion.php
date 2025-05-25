<?php

class Conexion {

    static public function conectar() {
        try {
            // Crear conexión PDO
            $link = new PDO(
                "mysql:host=localhost;dbname=pos_php_nativo-main;charset=utf8",
                "root",
                ""
            );

            // Modo de errores con excepciones
            $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Establecer zona horaria PHP
            date_default_timezone_set('America/La_Paz');

            // Establecer zona horaria en MySQL
            $link->exec("SET time_zone = '-04:00'");

            return $link;

        } catch (PDOException $e) {
            die("Error en la conexión: " . $e->getMessage());
        }
    }

}
