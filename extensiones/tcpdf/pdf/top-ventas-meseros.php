<?php

require_once "../../../controladores/ventas.controlador.php";
require_once "../../../modelos/ventas.modelo.php";

require_once "../../../controladores/clientes.controlador.php";
require_once "../../../modelos/clientes.modelo.php";

require_once "../../../controladores/usuarios.controlador.php";
require_once "../../../modelos/usuarios.modelo.php";


class reporteTopVentasMeseros
{


    public $fechaInicio;
    public $fechaFin;
    public $idMesero;
    public $idUsuario;

    public function generarPdfVentasTopMeseros()
    {
        // Establecer la zona horaria de Bolivia
        date_default_timezone_set('America/La_Paz');
        $fechaInicio = $this->fechaInicio;
        $fechaFin = $this->fechaFin;
        $idUsuario = $this->idUsuario;

        $respuestaVentas = ControladorVentas::ctrRangoFechasVentasTopMeserosPdf($fechaInicio, $fechaFin);
        $itemUsuario = "id";
        $respuestaUsuario = ControladorUsuarios::ctrMostrarUsuarios($itemUsuario, $idUsuario);

        require_once('tcpdf_include.php');

        // Configuración del PDF para UTF-8
        $pdf = new TCPDF('P', 'mm', 'LETTER', true, 'UTF-8', false);


        // Desactivar encabezado y pie de página
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Ajustar márgenes a cero
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetTitle('Top Ventas por meseros');
        $pdf->AddPage();

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(0, 5, 'Top Ventas por Meseros', 0, 1, 'C');
        $pdf->Image('images/logo-negro-bloque.jpg', 185, 10, 18, 16, 'jpg', '', 'T', false, 300, '', false, false, 0, false, false, false);

        $pdf->Ln(10); // Espacio después de la imagen

        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(23, 5, 'Cajero/a: ', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(50, 5, $respuestaUsuario["nombre"], 0, 1, 'L');

        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(23, 5, 'Periodo: ', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(100, 5, date("d-m-Y ", strtotime($fechaInicio)) . " al " . date("d-m-Y", strtotime($fechaFin)), 0, 1, 'L');

        $DateAndTime = date('d-m-Y h:i:s a', time());
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(23, 5, utf8_decode('Fecha y hora:'), 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(50, 5, $DateAndTime, 0, 1, 'L');

        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->SetFillColor(0, 0, 0);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(0, 5, 'Detalle de Top Ventas por Meseros', 1, 1, 'C', 1);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(18, 5, 'Nro Top', 1, 0, 'L');
        $pdf->Cell(70, 5, 'Mesero', 1, 0, 'L');
        $pdf->Cell(48, 5, 'Cantidad Ventas', 1, 0, 'L');
        $pdf->Cell(60, 5, 'Monto Ventas', 1, 1, 'L');
        $pdf->SetFont('helvetica', '', 8);

        //Imprimir los detalles de los productos
        $contador = 1;
        $sumTotal = 0;
        foreach ($respuestaVentas as $item) {
            $total =  $item["total"];
            $pdf->Cell(18, 5,  $contador, 1, 0, 'L');
            $pdf->Cell(70, 5, $item["mesero"], 1, 0, 'L');
            $pdf->Cell(48, 5, $item["cantidad"], 1, 0, 'L');
            $pdf->Cell(60, 5, $item["total"], 1, 1, 'R');
    
            $contador++;
            $sumTotal += $total;
        }

        // Total de la compra
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(166, 5, 'Total ', 0, 0, 'R');
        $pdf->Cell(30, 5, number_format($sumTotal, 2, '.', ',') . ' Bs.', 1, 1, 'R');


        // Nro de compras
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(30, 5, 'Total Meseros:  ' . $contador - 1, 0, 0, 'L');

        // Salida del archivo PDF
        $pdf->Output('factura.pdf', 'I');
    }
}

$factura = new reporteTopVentasMeseros();
$factura->fechaInicio = $_GET["fechaInicio"];
$factura->fechaFin = $_GET["fechaFin"];
$factura->idUsuario = $_GET["idUsuario"];
$factura->generarPdfVentasTopMeseros();
