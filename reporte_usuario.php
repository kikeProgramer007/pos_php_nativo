<?php
require "fpdf/conexion.php";
require "fpdf/fpdf.php";

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
        $this->Image('vistas/img/texturas/log.png', 12, 4.5, 31);
        $this->Image('vistas/img/texturas/log2.jpg', 170, 10, 28);

        // Título principal
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 15, iconv('UTF-8', 'ISO-8859-1', 'POLLOS ROSSY'), 0, 1, 'C');
        
        // Subtítulo en un recuadro
        $this->SetFont('Arial', 'B', 12);
        
        // Crear un recuadro centrado
        $textoAncho = 80; // Ancho del recuadro
        $posX = ($this->GetPageWidth() - $textoAncho) / 2;
        $this->Rect($posX, $this->GetY() + 2, $textoAncho, 8);
        
        // Texto centrado dentro del recuadro
        $this->Cell(0, 12, iconv('UTF-8', 'ISO-8859-1', 'LISTA DE USUARIOS'), 0, 1, 'C');
        
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

    function TablaHeader()
    {
        // Configuración de la tabla
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(173, 216, 230); // Celeste

        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.2);

        $tablaAncho = 180;
        $anchoColumna = array(60, 30, 35, 60);
        $posX = (210 - $tablaAncho) / 2;
        $this->SetX($posX);

        // Cabeceras con bordes completos
        $this->Cell($anchoColumna[0], 10, 'NOMBRE', 1, 0, 'C', true);
        $this->Cell($anchoColumna[1], 10, 'USUARIO', 1, 0, 'C', true);
        $this->Cell($anchoColumna[2], 10, 'PERFIL', 1, 0, 'C', true);
        $this->Cell($anchoColumna[3], 10, iconv('UTF-8', 'ISO-8859-1', 'ÚLTIMO INICIO DE SESIÓN'), 1, 1, 'C', true);
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
        $this->Cell(50, 10, 'POLLOS ROSSY', 0, 0, 'R');
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
$tablaAncho = 180;
$anchoColumna = array(60, 30, 35, 60);

// Consulta y datos
$sql = "SELECT nombre, usuario, perfil, ultimo_login FROM usuarios WHERE activo=1";
if ($resultado = $mysqli->query($sql)) {
    $colorFila = false;
    while ($fila = $resultado->fetch_assoc()) {
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
        $pdf->Cell($anchoColumna[0], 8, iconv('UTF-8', 'ISO-8859-1', strtoupper($fila['nombre'])), 1, 0, 'L', true);
        $pdf->Cell($anchoColumna[1], 8, strtoupper($fila['usuario']), 1, 0, 'C', true);
        $pdf->Cell($anchoColumna[2], 8, strtoupper($fila['perfil']), 1, 0, 'C', true);
        $pdf->Cell($anchoColumna[3], 8, strtoupper($fila['ultimo_login']), 1, 1, 'C', true);
        
        $colorFila = !$colorFila;
    }
    $resultado->free();
} else {
    $pdf->SetTextColor(255, 0, 0);
    $pdf->Cell(0, 10, "Error en la consulta: " . $mysqli->error, 0, 1, 'C');
}

$pdf->Output();
?>
