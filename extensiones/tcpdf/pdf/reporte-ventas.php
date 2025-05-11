<?php

require_once "../../../controladores/ventas.controlador.php";
require_once "../../../modelos/ventas.modelo.php";

require_once "../../../controladores/categorias.controlador.php";
require_once "../../../modelos/categorias.modelo.php";

require_once "../../../controladores/clientes.controlador.php";
require_once "../../../modelos/clientes.modelo.php";

require_once "../../../controladores/meseros.controlador.php";
require_once "../../../modelos/meseros.modelo.php";

require_once "../../../controladores/usuarios.controlador.php";
require_once "../../../modelos/usuarios.modelo.php";
require_once('tcpdf_include.php');

class reporteVenta 
{
    public $fechaInicio;
    public $fechaFin;
    public $idMesero;
    public $idUsuario;
    public $idCategoria;
    public $idCliente;
    public $registroEliminados;
    public $tipoPago;
    private $nombreTienda = "Pollos Rosy";
    private $direccionTienda = "Refineria";

    public function generarPdfVentas()
    {
        // Establecer la zona horaria de Bolivia
        date_default_timezone_set('America/La_Paz');
        $fechaInicio = $this->fechaInicio;
        $fechaFin = $this->fechaFin;
        $idMesero = $this->idMesero;
        $idUsuario = $this->idUsuario;
        $idCategoria= $this->idCategoria;
        $idCliente = $this->idCliente;
        $registroEliminados = $this->registroEliminados;
        $tipoPago = $this->tipoPago;
        
        $respuestaVentas = ControladorVentas::ctrRangoFechasVentasPdf($fechaInicio, $fechaFin, $idMesero,$idCategoria,$idCliente,$registroEliminados, $tipoPago);

        $itemUsuario = "id";

        $respuestaUsuario = ControladorUsuarios::ctrMostrarUsuariosActivoInactivo($itemUsuario, $idUsuario);

        //TRAEMOS LA INFORMACIÓN DEL VENDEDOR

        $itemMesero = "id";
        // $valorVendedor = $respuestaVentas["id_proveedor"];

        if ($idMesero != 0) {
            $respuestaMesero = ControladorMeseros::ctrMostrarMeserosActivoInactivo($itemMesero, $idMesero);
        } else {
            $respuestaMesero["nombre"] = "Todos";
        }
        if ($idCategoria != 0) {
            $respuestaCategoria = ControladorCategorias::ctrMostrarCategoriasActivasInactivas("id", $idCategoria);
        } else {
            $respuestaCategoria["categoria"] = "Todos";
        }
        if ($idCliente != 0) {
            $respuestaCliente = ControladorClientes::ctrMostrarClientesActivoInactivos("id", $idCliente);
        } else {
            $respuestaCliente["nombre"] = "Todos";
        }


        //REQUERIMOS LA CLASE TCPDF

        require_once('tcpdf_include.php');

        // Configuración del PDF para UTF-8
        $pdf = new TCPDF('P', 'mm', 'LETTER', true, 'UTF-8', false);


        // Desactivar encabezado y pie de página
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Ajustar márgenes a cero
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetTitle('Reporte de venta');
        $pdf->AddPage();

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(0, 5, 'REPORTE DE VENTAS ENTRE FECHA', 0, 1, 'C');
       /*  $pdf->Image('images/logo-negro-bloque.jpg', 150, 10, 60, 51, 'jpg', '', 'T', false, 300, '', false, false, 0, false, false, false); */
       $pdf->Image('images/logo-negro-bloque.jpg', 90, 25, 30, 20, 'jpg');
        $pdf->Ln(10); // Espacio después de la imagen


         // Nombre y dirección del restaurante
         $pdf->SetFont('helvetica', 'B', 9);
         $pdf->Cell(23, 5, 'Restaurante:', 0, 0, 'L');
         $pdf->SetFont('helvetica', '', 9);
         $pdf->Cell(50, 5, $this->nombreTienda, 0, 1, 'L');
 
         $pdf->SetFont('helvetica', 'B', 9);
         $pdf->Cell(23, 5, 'Dirección:', 0, 0, 'L');
         $pdf->SetFont('helvetica', '', 9);
         $pdf->Cell(50, 5, $this->direccionTienda, 0, 1, 'L');
         
         
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(23, 5, 'Usuario: ', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(50, 5, $respuestaUsuario["nombre"], 0, 1, 'L');
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(23, 5, 'Tipo de Pagos: ', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(50, 5, $tipoPago == 0 ? 'Todos' : $tipoPago, 0, 1, 'L');

        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(23, 5, 'Categorias: ', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(50, 5, $respuestaCategoria["categoria"], 0, 1, 'L');
        $pdf->SetY(25);  // Ajusta este valor según sea necesario altura
        $pdf->SetX(140); // Ajusta este valor según sea necesario ancho
        $pdf->SetFont('helvetica', 'B', 9);

        

        $pdf->Cell(23, 5, 'Clientes: ', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(50, 5, $respuestaCliente["nombre"], 0, 1, 'L');

        $pdf->SetX(140);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(23, 5, 'Meseros: ', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(50, 5, $respuestaMesero["nombre"], 0, 1, 'L');
        $pdf->SetX(140);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(23, 5, 'Periodo: ', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        // Obtener el mes, día y año de la fecha de inicio y fin
        $meses = [
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre'
        ];
        $mesInicio = date('m', strtotime($fechaInicio));
        $diaInicio = date('d', strtotime($fechaInicio));
        $anioInicio = date('Y', strtotime($fechaInicio));
        $nombreMesInicio = isset($meses[$mesInicio]) ? $meses[$mesInicio] : $mesInicio;
        $mesFin = date('m', strtotime($fechaFin));
        $diaFin = date('d', strtotime($fechaFin));
        $anioFin = date('Y', strtotime($fechaFin));
        $nombreMesFin = isset($meses[$mesFin]) ? $meses[$mesFin] : $mesFin;
        $periodo = 'Desde ' . $nombreMesInicio . ' ' . $diaInicio . ' del ' . $anioInicio . "\nHasta " . $nombreMesFin . ' ' . $diaFin . ' del ' . $anioFin;
        $pdf->MultiCell(38, 8, $periodo, 0, 'L');
        $pdf->SetX(140);
        $DateAndTime = date('d-m-Y h:i:s a', time());
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(23, 5, utf8_decode('Fecha y hora:'), 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);



//---------------------------------------------------------

        $pdf->Cell(50, 5, $DateAndTime, 0, 1, 'L');

        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->SetFillColor(0, 0, 0);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(0, 5, 'Detalle Del Reporte', 1, 1, 'C', 1);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(10, 5, '#', 1, 0, 'L');
        $pdf->Cell(14, 5, 'Ticket', 1, 0, 'C');
        $pdf->Cell(34, 5, 'Fecha Y Hora', 1, 0, 'C');
        $pdf->Cell(30, 5, 'Usuario', 1, 0, 'C');
        $pdf->Cell(30, 5, 'Mesero', 1, 0, 'C');
        $pdf->Cell(30, 5, 'Cliente', 1, 0, 'C');
        $pdf->Cell(27, 5, 'Tipo de Pago', 1, 0, 'C');
        $pdf->Cell(21, 5, 'Monto', 1, 1, 'C');
        $pdf->SetFont('helvetica', '', 8);

        //Imprimir los detalles de los productos
        $contador = 1;
        $sumTotal = 0;
        foreach ($respuestaVentas as $item) {
            $total =  $item["total"];
            $pdf->Cell(10, 5,  $contador, 1, 0, 'L');
            $pdf->Cell(14, 5, ltrim($item["codigo"], '0'), 1, 0, 'C');
            // Mostrar fecha y hora con segundos y am/pm
            $fechaCompleta = $item["fecha"];
            $fechaFormateada = date('Y-m-d h:i:s a', strtotime($fechaCompleta));
            $pdf->Cell(34, 5, $fechaFormateada, 1, 0, 'C');
            $pdf->Cell(30, 5, $item["usuario"], 1, 0, 'C');
            $pdf->Cell(30, 5, strtolower($item["mesero"]), 1, 0, 'C');
            $pdf->Cell(30, 5, strtolower($item["cliente"]), 1, 0, 'C');
            $pdf->Cell(27, 5, $item["tipo_pago"], 1, 0, 'C');
            $pdf->Cell(21, 5, $total . ' Bs', 1, 1, 'C');
            $contador++;
            $sumTotal += $total;
        }

        // Total de la Venta
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(175, 5, 'Total ', 0, 0, 'R');
        $pdf->Cell(21, 5, number_format($sumTotal, 2, '.', ',') . ' Bs.', 1, 1, 'R');

        // Nro de compras
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(30, 5, 'Número de ventas:  ' . $contador - 1, 0, 0, 'L');

        // Salida del archivo PDF
        $pdf->Output('ReporteVentas.pdf', 'I');
    }
}

$factura = new reporteVenta();
$factura->fechaInicio = $_GET["fechaInicio"];
$factura->fechaFin = $_GET["fechaFin"];
$factura->idMesero = $_GET["idMesero"];
$factura->idUsuario = $_GET["idUsuario"];
$factura->idCategoria = $_GET["idCategoria"];
$factura->idCliente = $_GET["idCliente"];
$factura->registroEliminados = $_GET["registroEliminados"];
$factura->tipoPago = $_GET["tipoPago"];
$factura->generarPdfVentas();
