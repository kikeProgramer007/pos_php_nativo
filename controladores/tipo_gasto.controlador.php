<?php

class ControladorTipoGasto{

    /*=============================================
    CREAR TIPO DE GASTO
    =============================================*/

    static public function ctrCrearTipoGasto(){

        if(isset($_POST["nuevoTipoGasto"])){

            $tabla = "tipo_gasto";
            
            
        }
    }

    /*=============================================
    MOSTRAR TIPO DE GASTO
    =============================================*/

    static public function ctrMostrarTipoGasto($item, $valor){

        $tabla = "tipo_gasto";

        $respuesta = ModeloTipoGasto::mdlMostrarTipoGasto($tabla, $item, $valor);

        return $respuesta;

    }

    /*=============================================
    BORRAR TIPO DE GASTO
    =============================================*/

    static public function ctrBorrarTipoGasto(){

        if(isset($_POST["idTipoGasto"])){

            $tabla = "tipo_gasto";  
            
            $datos = array("id" => $_POST["idTipoGasto"]);

            $respuesta = ModeloTipoGasto::mdlBorrarTipoGasto($tabla, $datos);

            if($respuesta == "ok"){
                echo '<script>  
                    swal({
                        title: "¡Eliminado!",
                        text: "El tipo de gasto ha sido eliminado correctamente",
                        icon: "success"
                    }).then(function(){
                        window.location = "index.php?ruta=tipo_gasto";
                    });
                </script>';
            }else{
                echo '<script>
                    swal({
                        title: "¡Error!",
                        text: "El tipo de gasto no ha sido eliminado correctamente",
                        icon: "error"
                    });
                </script>';
            }

        }

    }
    

}