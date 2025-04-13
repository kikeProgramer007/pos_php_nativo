<?php

// Mostrar errores para depuración (puedes quitar esto en producción)
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once "../../controladores/ventas.controlador.php";
require_once "../../modelos/ventas.modelo.php";

class TablaProductosVentasEliminadas {

    /*=============================================
     MOSTRAR LA TABLA DE VENTAS ELIMINADAS
    =============================================*/ 
    public function mostrarTablaProductosVentasEliminadas() {
        
        // Obtenemos el rango de fechas si existen
        if (isset($_GET["fechaInicial"]) && isset($_GET["fechaFinal"])) {
            $fechaInicial = $_GET["fechaInicial"];
            $fechaFinal = $_GET["fechaFinal"];
        } else {
            $fechaInicial = null;
            $fechaFinal = null;
        }

        date_default_timezone_set('America/La_Paz');

        // Obtenemos las ventas con estado 0 (eliminadas)
        $ventas = ControladorVentas::ctrRangoFechasVentasRealizadas($fechaInicial, $fechaFinal, estado:0);

        // Si no hay ventas, devolvemos un JSON vacío
        if (empty($ventas)) {
            header('Content-Type: application/json');
            echo json_encode(["data" => []]);
            return;
        }

        // Armamos el array de datos
        $datos = [];

        for ($i = 0; $i < count($ventas); $i++) {

            $botones = "<div class='btn-group'>";
            $botones .= "<button class='btn btn-info btnImprimirFactura' codigoVenta='" . $ventas[$i]["id"] . "'><i class='fa fa-print'></i></button>"; 
            $botones .= "</div>";

            $datos[] = [
                ($i + 1),
                $ventas[$i]["codigo"],
                $ventas[$i]["mesero"],
                $ventas[$i]["usuario"],
                number_format($ventas[$i]["total"], 2),
                $ventas[$i]["fecha"],
                $botones
            ];
        }

        // Devolvemos el JSON con encabezado correcto
        header('Content-Type: application/json');
        echo json_encode(["data" => $datos]);
    }
}

/*=============================================
ACTIVAR TABLA DE VENTAS ELIMINADAS
=============================================*/ 
$activarProductosVentasEliminadas = new TablaProductosVentasEliminadas();
$activarProductosVentasEliminadas->mostrarTablaProductosVentasEliminadas();

?>
