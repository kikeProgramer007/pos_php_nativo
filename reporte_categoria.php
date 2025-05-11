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
        // Configuración para caracteres especiales - usando helvetica que viene por defecto
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
       $this->Image('vistas/img/plantilla/log2.jpg', 170, 10, 28);


        // Título principal
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 15, iconv('UTF-8', 'ISO-8859-1', 'POLLOS ROSSY'), 0, 1, 'C');
        
        // Línea horizontal debajo del título
       
        
        // Subtítulo en un recuadro
        $this->SetFont('Arial', 'B', 12);
        
        // Crear un recuadro centrado
        $textoAncho = 80; // Ancho del recuadro
        $posX = ($this->GetPageWidth() - $textoAncho) / 2;
        $this->Rect($posX, $this->GetY() + 2, $textoAncho, 8);
        
        // Texto centrado dentro del recuadro
        $this->Cell(0, 12, iconv('UTF-8', 'ISO-8859-1', 'LISTA DE CATEGORÍAS'), 0, 1, 'C');
        
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
        $anchoColumna = array(30, 100, 50);
        $posX = (210 - $tablaAncho) / 2;
        $this->SetX($posX);

        // Cabeceras con bordes completos
        $this->Cell($anchoColumna[0], 10, 'ID', 1, 0, 'C', true);
        $this->Cell($anchoColumna[1], 10, iconv('UTF-8', 'ISO-8859-1', 'CATEGORÍAS'), 1, 0, 'C', true);
        $this->Cell($anchoColumna[2], 10, 'FECHA', 1, 1, 'C', true);
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

        $this->SetY(-20); // Ajustamos la posición Y del footer
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
$anchoColumna = array(30, 100, 50);

// Consulta y datos
$sql = "SELECT id, categoria, fecha FROM categorias WHERE estado=1";
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
    $pdf->Cell($anchoColumna[0], 8, strtoupper($fila['id']), 1, 0, 'C', true);
    $pdf->Cell($anchoColumna[1], 8, iconv('UTF-8', 'ISO-8859-1', strtoupper($fila['categoria'])), 1, 0, 'L', true);
    $pdf->Cell($anchoColumna[2], 8, strtoupper($fila['fecha']), 1, 1, 'C', true);
    
    $colorFila = !$colorFila;
}

$pdf->Output();
?>
