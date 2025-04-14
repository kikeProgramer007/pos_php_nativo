<?php
require "fpdf/conexion.php";
require "fpdf/fpdf.php";

class PDF extends FPDF
{
    // Encabezado de página
    function Header()
    {
        // Añadir imagen en la esquina superior izquierda
        $this->Image('vistas/img/texturas/log.png', 12, 4.5, 31);

        // Añadir imagen en la esquina superior derecha (opcional)
        $this->Image('vistas/img/texturas/log2.jpg', 170, 10, 28); // Ajusta la posición si es necesario

        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, 'POLLOS ROSSY', 0, 1, 'C');

        $this->Ln(7); // Mayor separación después del título

        // Título en cada página
        $this->SetFont("Arial", "B", 12);
        $this->Cell(0, 10, "LISTA DE CLIENTES", 0, 1, "C");
        $this->Ln(5); // Espacio entre el título y la tabla
    }

    // Encabezado de la tabla
    function TablaHeader()
    {
        $this->SetFont("Arial", "", 10);
        $this->SetFillColor(200, 200, 200); // Color de fondo de las cabeceras

        // Ajustar el ancho total de la tabla (restar márgenes izquierdo y derecho)
        $tablaAncho = 100; // Ancho total de la tabla en mm (210 mm - 2 * 10 mm de márgenes)
        $anchoColumna = array(60, 40); // Ancho de cada columna

        // Calcular la posición inicial para centrar la tabla
        $posX = (210 - $tablaAncho) / 2; // 210 mm es el ancho de A4
        $this->SetX($posX);

        // Imprimir cabeceras de la tabla centradas
        foreach ($anchoColumna as $ancho) {
            $this->Cell($ancho, 10, strtoupper($this->headerTitle($ancho)), 1, 0, "C", true);
        }
        $this->Ln(); // Salto de línea después de las cabeceras
    }

    // Método para obtener el título de la cabecera basado en el ancho
    function headerTitle($ancho)
    {
        switch ($ancho) {
            case 60:
                return "NOMBRE";
            case 40:
                return "FECHA";
            default:
                return "";
        }
    }

    // Pie de página
    function Footer()
    {
        $this->SetY(-15); // Posición a 1.5 cm del final
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
        date_default_timezone_set('America/La_Paz');
        $this->Cell(0, 10, 'Fecha: ' . date('d-m-Y'), 0, 0, 'R');
    }
}

// Crear una instancia de FPDF y agregar una página
$pdf = new PDF("P", "mm", "A4");
$pdf->AliasNbPages();
$pdf->AddPage();

// Imprimir el encabezado de la tabla
$pdf->TablaHeader();

// Establecer la fuente para los datos
$pdf->SetFont("Arial", "", 9);

// Ajustar el ancho total de la tabla (restar márgenes izquierdo y derecho)
$anchoColumna = array(60, 40); // Ancho de cada columna

// Ejecutar la consulta y agregar los datos al PDF
$sql = "SELECT nombre, fecha FROM clientes WHERE estado=1 ";
if ($resultado = $mysqli->query($sql)) {
    while ($fila = $resultado->fetch_assoc()) {
        if ($pdf->GetY() > 250) { // Verifica si la posición Y está cerca del final de la página
            $pdf->AddPage(); // Añade una nueva página
            $pdf->TablaHeader(); // Imprime el encabezado de la tabla en la nueva página
        }

        $pdf->SetX((210 - array_sum($anchoColumna)) / 2); // Asegurarse de estar en la posición correcta

        // Imprimir datos de la fila
        $pdf->Cell($anchoColumna[0], 10, strtoupper($fila['nombre']), 1, 0, "L");
        $pdf->Cell($anchoColumna[1], 10, strtoupper($fila['fecha']), 1, 1, "L"); // Cambiado a 1 para salto de línea
    }
    $resultado->free();
} else {
    $pdf->Cell(0, 10, "Error en la consulta: " . $mysqli->error, 0, 1, "C");
}

// Cerrar y generar el PDF
$pdf->Output();
?>