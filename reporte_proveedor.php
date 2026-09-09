<?php
require_once "modelos/conexion.php";
require_once "fpdf/fpdf.php";

// Obtenemos la conexión usando la clase Conexion de la carpeta modelos y el archivo conexion.php //
$conexion = Conexion::conectar();
//fin de la conexión

class PDF extends FPDF
{
    function Header()
    {
        // Configuración para caracteres especiales
        $this->SetFont('Arial','',12);
        
        // Rectángulo superior con degradado suave
        $this->SetFillColor(245, 245, 245);
        $this->Rect(0, 0, 210, 40, 'F');
        
        // Línea decorativa superior
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.4);
        $this->Line(0, 40, 210, 40);

       // Logos
           $this->Image('vistas/img/plantilla/logo-blanco-bloque.png', 12, 4.5, 31);


        // Título principal
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 15, iconv('UTF-8', 'ISO-8859-1', 'Pollos 360'), 0, 1, 'C');
        
        // Subtítulo en un recuadro
        $this->SetFont('Arial', 'B', 12);
        
        // Crear un recuadro centrado
        $textoAncho = 80; // Ancho del recuadro
        $posX = ($this->GetPageWidth() - $textoAncho) / 2;
        $this->Rect($posX, $this->GetY() + 2, $textoAncho, 8);
        
        // Texto centrado dentro del recuadro
        $this->Cell(0, 12, iconv('UTF-8', 'ISO-8859-1', 'LISTA DE PROVEEDORES'), 0, 1, 'C');
        
        $this->Ln(5);

        // Información adicional en dos columnas
        $this->SetFont('Arial', '', 10);
        $this->SetX(15);
        date_default_timezone_set('America/La_Paz');
        $this->Cell(40, 8, 'Fecha: ' . date('d/m/Y'), 0, 0, 'L');
        $this->SetX(165);
        $this->Cell(40, 8, 'Hora: ' . date('H:i:s'), 0, 1, 'L');
        
        $this->Ln(5);
    }

    // Calcula cuántas líneas ocupará un texto dentro de un ancho dado
    function NbLines($w, $txt)
    {
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n")
            $nb--;
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
            if ($c == ' ')
                $sep = $i;
            if (isset($cw[$c]))
                $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                } else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else
                $i++;
        }
        return $nl;
    }

    // Imprime una fila con celdas que pueden contener salto de línea automático
    function Row($data, $widths, $aligns = array(), $fill = false)
    {
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($widths[$i], $data[$i]));
        }
        $h = 5 * $nb; // altura por línea 5 mm

        if ($this->GetY() + $h > $this->PageBreakTrigger) {
            $this->AddPage($this->CurOrientation);
            $this->TablaHeader();
            $this->SetFont('Arial', '', 9);
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

    function TablaHeader()
    {
        // Configuración de la tabla
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(173, 216, 230); // Celeste

        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.2);

        $tablaAncho = 195;
        $anchoColumna = array(60, 50, 25, 60);
        $posX = (210 - $tablaAncho) / 2;
        $this->SetX($posX);

        // Cabeceras con bordes completos
        $this->Cell($anchoColumna[0], 10, 'NOMBRE', 1, 0, 'C', true);
        $this->Cell($anchoColumna[1], 10, 'EMPRESA', 1, 0, 'C', true);
        $this->Cell($anchoColumna[2], 10, 'TEL.', 1, 0, 'C', true);
        $this->Cell($anchoColumna[3], 10, iconv('UTF-8', 'ISO-8859-1', 'DIRECCIÓN'), 1, 1, 'C', true);
    }

    function Footer()
    {
        // Rectángulo inferior con degradado suave
        $this->SetFillColor(245, 245, 245);
        $this->Rect(0, $this->GetPageHeight()-25, 210, 25, 'F');
        
        // Línea decorativa inferior
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.4);
        $this->Line(0, $this->GetPageHeight()-25, 210, $this->GetPageHeight()-25);

        $this->SetY(-20);
        $this->SetFont('Arial', 'I', 8);
        
        // Información del pie de página
        $this->Cell(70, 10, iconv('UTF-8', 'ISO-8859-1', 'Fecha de impresión: ') . date('d/m/Y H:i:s'), 0, 0, 'L');
        $this->Cell(70, 10, iconv('UTF-8', 'ISO-8859-1', 'Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
        $this->Cell(50, 10, 'Pollos 360', 0, 0, 'R');
    }
}

$pdf = new PDF('P', 'mm', 'A4');
$pdf->AliasNbPages();
$pdf->AddPage();

// Establecer márgenes
$pdf->SetMargins(15, 15, 15);

// Imprimir el encabezado de la tabla
$pdf->TablaHeader();

// Configuración para los datos
$pdf->SetFont('Arial', '', 9);
$tablaAncho = 195;
$anchoColumna = array(60, 50, 25, 60);

// Consulta y datos
$sql = "SELECT nombre, empresa, telefono, direccion FROM proveedor WHERE estado=1";
$stmt = $conexion->prepare($sql);
$stmt->execute();

$colorFila = false;
while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
    if ($pdf->GetY() > 230) {
        $pdf->AddPage();
        $pdf->TablaHeader();
        // Resetear la fuente después de TablaHeader
        $pdf->SetFont('Arial', '', 9);
    }

    // Alternar colores de fondo para las filas
    if($colorFila) {
        $pdf->SetFillColor(250, 250, 250);
    } else {
        $pdf->SetFillColor(255, 255, 255);
    }

    $posX = (210 - $tablaAncho) / 2;
    $pdf->SetX($posX);

    // Imprimir datos con bordes y fondo alternado
    $dataRow = array(
        iconv('UTF-8', 'ISO-8859-1', strtoupper($fila['nombre'])),
        iconv('UTF-8', 'ISO-8859-1', strtoupper($fila['empresa'])),
        strtoupper($fila['telefono']),
        iconv('UTF-8', 'ISO-8859-1', strtoupper($fila['direccion']))
    );

    $aligns = array('L','L','C','L');

    $pdf->SetX($posX);
    $pdf->Row($dataRow, $anchoColumna, $aligns, $colorFila);
    
    $colorFila = !$colorFila;
}

$pdf->Output();
?>
