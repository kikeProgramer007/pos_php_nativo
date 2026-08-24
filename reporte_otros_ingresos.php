<?php
require_once "modelos/conexion.php";
require_once "fpdf/fpdf.php";
require_once "modelos/otros_ingresos.modelo.php";

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

        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 15, iconv('UTF-8', 'ISO-8859-1', 'El Gato Rico -Churrasqueria'), 0, 1, 'C');

        $this->SetFont('Arial', 'B', 12);
        $textoAncho = 90;
        $posX = ($this->GetPageWidth() - $textoAncho) / 2;
        $this->Rect($posX, $this->GetY() + 2, $textoAncho, 8);
        $this->Cell(0, 12, iconv('UTF-8', 'ISO-8859-1', 'LISTA DE OTROS INGRESOS'), 0, 1, 'C');
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
        $this->SetFont('Arial', 'B', 9);
        $this->SetFillColor(173, 216, 230);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.2);

        $anchoColumna = array(28, 52, 22, 22, 35, 16);
        $this->SetX((210 - array_sum($anchoColumna)) / 2);

        $this->Cell($anchoColumna[0], 10, iconv('UTF-8', 'ISO-8859-1', 'FECHA'), 1, 0, 'C', true);
        $this->Cell($anchoColumna[1], 10, iconv('UTF-8', 'ISO-8859-1', 'OBSERVACION'), 1, 0, 'C', true);
        $this->Cell($anchoColumna[2], 10, iconv('UTF-8', 'ISO-8859-1', 'ENTRADA'), 1, 0, 'C', true);
        $this->Cell($anchoColumna[3], 10, iconv('UTF-8', 'ISO-8859-1', 'MONTO'), 1, 0, 'C', true);
        $this->Cell($anchoColumna[4], 10, iconv('UTF-8', 'ISO-8859-1', 'USUARIO'), 1, 0, 'C', true);
        $this->Cell($anchoColumna[5], 10, iconv('UTF-8', 'ISO-8859-1', 'ARQ.'), 1, 1, 'C', true);
    }

    function NbLines($w, $txt)
    {
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0) {
            $w = $this->w - $this->rMargin - $this->x;
        }
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 && $s[$nb - 1] == "\n") {
            $nb--;
        }
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ') {
                $sep = $i;
            }
            if (isset($cw[$c])) {
                $l += $cw[$c];
            }
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j) {
                        $i++;
                    }
                } else {
                    $i = $sep + 1;
                }
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else {
                $i++;
            }
        }
        return $nl;
    }

    function Row($data, $widths, $aligns = array(), $fill = false)
    {
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($widths[$i], $data[$i]));
        }
        $h = 5 * $nb;

        if ($this->GetY() + $h > $this->PageBreakTrigger) {
            $this->AddPage($this->CurOrientation);
            $this->TablaHeader();
            $this->SetFont('Arial', '', 8);
        }

        for ($i = 0; $i < count($data); $i++) {
            $w = $widths[$i];
            $a = isset($aligns[$i]) ? $aligns[$i] : 'L';
            $x = $this->GetX();
            $y = $this->GetY();

            if ($fill) {
                $this->SetFillColor(250, 250, 250);
                $this->Rect($x, $y, $w, $h, 'F');
                $this->SetDrawColor(0, 0, 0);
                $this->Rect($x, $y, $w, $h);
            } else {
                $this->Rect($x, $y, $w, $h);
            }

            $this->SetXY($x + 1, $y + 1);
            $this->MultiCell($w - 2, 5, $data[$i], 0, $a);
            $this->SetXY($x + $w, $y);
        }
        $this->Ln($h);
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
        $this->Cell(70, 10, iconv('UTF-8', 'ISO-8859-1', 'Fecha de impresion: ') . date('d/m/Y H:i:s'), 0, 0, 'L');
        $this->Cell(70, 10, iconv('UTF-8', 'ISO-8859-1', 'Pagina ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
        $this->Cell(50, 10, 'El Gato Rico -Churrasqueria', 0, 0, 'R');
    }
}

$pdf = new PDF('P', 'mm', 'A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetMargins(15, 15, 15);
$pdf->TablaHeader();

$pdf->SetFont('Arial', '', 8);
$anchoColumna = array(28, 52, 22, 22, 35, 16);
$tablaAncho = array_sum($anchoColumna);
$resul = ModeloOtrosIngresos::mdlMostrarOtrosIngresos(null, null);

$totalMonto = 0;
$colorFila = false;

if (is_array($resul)) {
    foreach ($resul as $fila) {
        if ($pdf->GetY() > 230) {
            $pdf->AddPage();
            $pdf->TablaHeader();
            $pdf->SetFont('Arial', '', 8);
        }

        $pdf->SetX((210 - $tablaAncho) / 2);
        $monto = floatval($fila['monto']);
        $totalMonto += $monto;

        $dataRow = array(
            iconv('UTF-8', 'ISO-8859-1', strtoupper(substr($fila['fecha'], 0, 16))),
            iconv('UTF-8', 'ISO-8859-1', strtoupper($fila['descripcion'])),
            iconv('UTF-8', 'ISO-8859-1', strtoupper($fila['tipo_entrada'] ?? 'EFECTIVO')),
            iconv('UTF-8', 'ISO-8859-1', number_format($monto, 2, '.', '')),
            iconv('UTF-8', 'ISO-8859-1', strtoupper($fila['nombre_usuario'])),
            iconv('UTF-8', 'ISO-8859-1', strval($fila['id_arqueo_caja']))
        );

        $aligns = array('C', 'L', 'C', 'R', 'C', 'C');
        $pdf->Row($dataRow, $anchoColumna, $aligns, $colorFila);
        $colorFila = !$colorFila;
    }
}

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 8, iconv('UTF-8', 'ISO-8859-1', 'TOTAL: Bs ' . number_format($totalMonto, 2, '.', '')), 0, 1, 'R');

$pdf->Output();
