<?php
require_once "modelos/conexion.php";
require_once "fpdf/fpdf.php";
require_once "modelos/gastos.modelo.php";

$conexion = Conexion::conectar();

class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial','',12);
        $this->SetFillColor(245, 245, 245);
        $this->Rect(0, 0, 210, 40, 'F');
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.4);
        $this->Line(0, 40, 210, 40);

        $this->Image('vistas/img/plantilla/logo-blanco-bloque.png', 12, 4.5, 31);
        $this->Image('vistas/img/plantilla/log2.jpg', 170, 10, 28);

        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 15, iconv('UTF-8', 'ISO-8859-1', 'POLLOS ROSSY'), 0, 1, 'C');

        $this->SetFont('Arial', 'B', 12);
        $textoAncho = 80;
        $posX = ($this->GetPageWidth() - $textoAncho) / 2;
        $this->Rect($posX, $this->GetY() + 2, $textoAncho, 8);
        $this->Cell(0, 12, iconv('UTF-8', 'ISO-8859-1', 'LISTA DE GASTOS'), 0, 1, 'C');
        $this->Ln(5);

        $this->SetFont('Arial', '', 10);
        $this->SetX(15);
        date_default_timezone_set('America/La_Paz');
        $this->Cell(40, 8, 'Fecha: ' . date('d/m/Y'), 0, 0, 'L');
        $this->SetX(165);
        $this->Cell(40, 8, 'Hora: ' . date('H:i:s'), 0, 1, 'L');
        $this->Ln(5);
    }

    function TablaHeader()
    {
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(173, 216, 230);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.2);

        // Seis columnas
        $anchoColumna = array(20, 65, 22, 37, 40); // Total: 185 mm
        $this->SetX((210 - array_sum($anchoColumna)) / 2);

        $this->Cell($anchoColumna[0], 10, iconv('UTF-8', 'ISO-8859-1', 'FECHA'), 1, 0, 'C', true);
        $this->Cell($anchoColumna[1], 10, iconv('UTF-8', 'ISO-8859-1', 'TIPO DE GASTO'), 1, 0, 'C', true);
        $this->Cell($anchoColumna[2], 10, iconv('UTF-8', 'ISO-8859-1', 'MONTO'), 1, 0, 'C', true);
        $this->Cell($anchoColumna[3], 10, iconv('UTF-8', 'ISO-8859-1', 'FORMA DE PAGO'), 1, 0, 'C', true);
        $this->Cell($anchoColumna[4], 10, iconv('UTF-8', 'ISO-8859-1', 'USUARIO'), 1, 1, 'C', true);
    }

    function Footer()
    {
        $this->SetFillColor(245, 245, 245);
        $this->Rect(0, $this->GetPageHeight()-25, 210, 25, 'F');
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.4);
        $this->Line(0, $this->GetPageHeight()-25, 210, $this->GetPageHeight()-25);

        $this->SetY(-20);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(70, 10, iconv('UTF-8', 'ISO-8859-1', 'Fecha de impresión: ') . date('d/m/Y H:i:s'), 0, 0, 'L');
        $this->Cell(70, 10, iconv('UTF-8', 'ISO-8859-1', 'Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
        $this->Cell(50, 10, 'POLLOS ROSSY', 0, 0, 'R');
    }
}

$pdf = new PDF('P', 'mm', 'A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetMargins(15, 15, 15);
$pdf->TablaHeader();

$pdf->SetFont('Arial', '', 9);
$anchoColumna = array(20, 65, 22, 37, 40); // mismo orden que en encabezado
$tablaAncho = array_sum($anchoColumna);
$resul = ModeloGastos::mdlMostrarGastos("gastos", null, null);

$colorFila = false;
foreach ($resul as $fila) {
    if ($pdf->GetY() > 230) {
        $pdf->AddPage();
        $pdf->TablaHeader();
        $pdf->SetFont('Arial', '', 9);
    }

    $pdf->SetFillColor($colorFila ? 250 : 255, 255, 255);
    $pdf->SetX((210 - $tablaAncho) / 2);

    $pdf->Cell($anchoColumna[0], 8, iconv('UTF-8', 'ISO-8859-1', strtoupper($fila['fecha'])), 1, 0, 'C', true);
    $pdf->Cell($anchoColumna[1], 8, iconv('UTF-8', 'ISO-8859-1', strtoupper($fila['nombre_tipo_gasto'])), 1, 0, 'C', true);
    $pdf->Cell($anchoColumna[2], 8, iconv('UTF-8', 'ISO-8859-1', $fila['monto']), 1, 0, 'R', true);
    $pdf->Cell($anchoColumna[3], 8, iconv('UTF-8', 'ISO-8859-1', strtoupper($fila['forma_pago_descripcion'])), 1, 0, 'C', true);
    $pdf->Cell($anchoColumna[4], 8, iconv('UTF-8', 'ISO-8859-1', strtoupper($fila['nombre_usuario'])), 1, 1, 'C', true);

    $colorFila = !$colorFila;
}

$pdf->Output();
