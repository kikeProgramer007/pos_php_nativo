<?php

require_once "../../../controladores/ventas.controlador.php";
require_once "../../../modelos/ventas.modelo.php";

require_once "../../../controladores/categorias.controlador.php";
require_once "../../../modelos/categorias.modelo.php";

require_once "../../../controladores/productos.controlador.php";
require_once "../../../modelos/productos.modelo.php";

require_once "../../../controladores/usuarios.controlador.php";
require_once "../../../modelos/usuarios.modelo.php";

require_once "../../../fpdf/fpdf.php";
require_once __DIR__ . "/fila-tabla.php";

class reporteProductoFaltante
{



    public $idUsuario;
    private $nombreTienda = "El Gato Rico ";
    private $direccionTienda = "Heroes Del Chaco 9";

    public function generarPdfProductoFaltante()
    {
        // Establecer la zona horaria de Bolivia
        date_default_timezone_set('America/La_Paz');
        $idUsuario = $this->idUsuario;

        $respuestaProductos = ControladorProductos::ctrMostrarProductosFaltante();

        $itemUsuario = "id";

        $respuestaUsuario = ControladorUsuarios::ctrMostrarUsuarios($itemUsuario, $idUsuario);

        //TRAEMOS LA INFORMACIÓN DEL VENDEDOR


        $DateAndTime = date('d-m-Y h:i:s a', time());

        $pdf = new FPDF('P', 'mm', 'letter');
        $pdf->AddPage();
        $pdf->SetMargins(12, 12, 12);
        $pdf->SetTitle("Reporte de Producto Faltantes");
        $pdf->SetFont('Arial', 'B', 15);
        $pdf->Cell(0, 5, utf8_decode('Reporte De Producto Faltantes'), 0, 1, 'C');
        
        // Logo
        $pdf->Image('images/logo-negro-bloque.jpg', 90, 25, 30, 20, 'jpg');
        
        // Espacio después de la imagen
        $pdf->Ln(10);
        
        // Ajuste de la posición vertical inicial
        $pdf->SetY(30);  
        
        // Nombre y dirección del restaurante
        $pdf->SetY(35);  // Ajusta este valor según sea necesario altura
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(40, 5, utf8_decode('Restaurante:'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(50, 5, utf8_decode($this->nombreTienda), 0, 1, 'L');
        
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(40, 5, utf8_decode('Dirección:'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(50, 5, utf8_decode($this->direccionTienda), 0, 1, 'L');
        
        // Ajuste de la posición en X para alineación a la derecha
        $pdf->SetY(35);  // Ajusta este valor según sea necesario altura
        $pdf->SetX(120); // Ajusta este valor según sea necesario ancho
        
        // Usuario
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(40, 5, utf8_decode('Usuario:'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(50, 5, utf8_decode($respuestaUsuario["nombre"]), 0, 1, 'L');
        
        // Fecha y hora
        $pdf->SetX(120); // Alineación para mantener la coherencia en el diseño
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(40, 5, utf8_decode('Fecha y hora:'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(50, 5, utf8_decode($DateAndTime), 0, 1, 'L');
        
        $pdf->Ln(5);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(12, 5, utf8_decode('#'), 1, 0, 'L');
        $pdf->Cell(28, 5, utf8_decode('Codigo'), 1, 0, 'C');
        $pdf->Cell(70, 5, utf8_decode('Producto'), 1, 0, 'C');
        $pdf->Cell(30, 5, utf8_decode('Precio compra'), 1, 0, 'C');
        $pdf->Cell(28, 5, utf8_decode('Precio venta'), 1, 0, 'C');
        $pdf->Cell(24, 5, utf8_decode('Cantidad'), 1, 1, 'C');
        $pdf->SetFont('Arial', '', 11);





        $contador = 1;
        $pdf->SetAutoPageBreak(false);
        foreach ($respuestaProductos as $producto) {
            if ($pdf->GetY() > 250) {
                $pdf->AddPage();
                $pdf->SetFont('Arial', 'B', 11);
                $pdf->Cell(12, 5, utf8_decode('#'), 1, 0, 'C');
                $pdf->Cell(28, 5, utf8_decode('Codigo'), 1, 0, 'C');
                $pdf->Cell(70, 5, utf8_decode('Producto'), 1, 0, 'C');
                $pdf->Cell(30, 5, utf8_decode('Precio compra'), 1, 0, 'C');
                $pdf->Cell(28, 5, utf8_decode('Precio venta'), 1, 0, 'C');
                $pdf->Cell(24, 5, utf8_decode('Cantidad'), 1, 1, 'C');
                $pdf->SetFont('Arial', '', 11);
            }
            fpdfDibujarFila($pdf, array(
                array('w' => 12, 'txt' => $contador, 'align' => 'C'),
                array('w' => 28, 'txt' => $producto['codigo'], 'align' => 'C'),
                array('w' => 70, 'txt' => utf8_decode($producto['descripcion']), 'align' => 'L'),
                array('w' => 30, 'txt' => $producto['precio_compra'] . ' Bs', 'align' => 'R'),
                array('w' => 28, 'txt' => $producto['precio_venta'] . ' Bs', 'align' => 'R'),
                array('w' => 24, 'txt' => $producto['stock'], 'align' => 'C'),
            ), 5);
            $contador++;
        }
        $pdf->Ln();

        // Salida del archivo PDF
        $pdf->Output('ReporteDeProductosFaltantes.pdf', 'I');
    }
}

$factura = new reporteProductoFaltante();
$factura->idUsuario = $_GET["idUsuario"];
$factura->generarPdfProductoFaltante();
