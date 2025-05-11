<?php


require_once "../../../controladores/ventas.controlador.php";
require_once "../../../modelos/ventas.modelo.php";

require_once "../../../controladores/usuarios.controlador.php";
require_once "../../../modelos/usuarios.modelo.php";


class reporteTopProductosMasVendidos
{

    public $fechaInicio;
    public $fechaFin;
    public $idUsuario;
    private $nombreTienda = "Pollos Rosy";
    private $direccionTienda = "Refineria";

    public function generarPdfVentasTopProducto()
    {
        // Establecer la zona horaria de Bolivia
        date_default_timezone_set('America/La_Paz');
        $fechaInicio = $this->fechaInicio;
        $fechaFin = $this->fechaFin;
        $idUsuario = $this->idUsuario;

        $respuestaDatos = ControladorVentas::ctrRangoFechasTopProductoMasVendidosPdf($fechaInicio, $fechaFin);
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
        $pdf->SetTitle('Reporte De Producto Mas Vendidos');
        $pdf->AddPage();

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 5, 'Reporte De Producto Mas Vendidos', 0, 1, 'C');
        $pdf->Image('images/logo-negro-bloque.jpg', 90, 25, 30, 20, 'jpg');

        $pdf->Ln(10); // Espacio después de la imagen
        $pdf->SetX(140); // Ajusta este valor según sea necesario ancho
        $pdf->SetY(30);  // Ajusta este valor según sea necesario altura

            // Nombre y dirección del restaurante
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->Cell(23, 5, 'Restaurante:', 0, 0, 'L');
            $pdf->SetFont('helvetica', '', 9);
            $pdf->Cell(50, 5, $this->nombreTienda, 0, 1, 'L');

            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->Cell(23, 5, 'Dirección:', 0, 0, 'L');
            $pdf->SetFont('helvetica', '', 9);
            $pdf->Cell(50, 5, $this->direccionTienda, 0, 1, 'L');

            $pdf->SetY(35);  // Ajusta este valor según sea necesario altura
            $pdf->SetX(140); // Ajusta este valor según sea necesario ancho
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(23, 5, 'Usuario: ', 0, 0, 'L');

        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(50, 5, $respuestaUsuario["nombre"], 0, 1, 'L');
        $pdf->SetX(140); // Ajusta este valor según sea necesario ancho
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(23, 5, 'Periodo: ', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(100, 5, date("d-m-Y ", strtotime($fechaInicio)) . " al " . date("d-m-Y", strtotime($fechaFin)), 0, 1, 'L');

        $DateAndTime = date('d-m-Y h:i:s a', time());
        $pdf->SetY(40);  // Ajusta este valor según sea necesario altura
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(23, 5, utf8_decode('Fecha y hora:'), 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(50, 5, $DateAndTime, 0, 1, 'L');

        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->SetFillColor(0, 0, 0);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(0, 5, 'Detalle de Top', 1, 1, 'C', 1);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(18, 5, '#', 1, 0, 'L');
        
        $pdf->Cell(140, 5, 'Producto', 1, 0, 'L');
        $pdf->Cell(38, 5, 'Cantidad Vendida', 1, 1, 'C');
        // $pdf->Cell(60, 5, 'Monto Ventas', 1, 1, 'L');
        $pdf->SetFont('helvetica', '', 8);

        //Imprimir los detalles de los productos
        $contador = 1;
        $sumTotal = 0;

        foreach ($respuestaDatos as $item) {
            $total =  $item['cantidad'];
            $pdf->Cell(18, 5,  $contador, 1, 0, 'L');
            $pdf->Cell(140, 5,  $item['descripcion'], 1, 0, 'L');
            $pdf->Cell(38, 5, $total, 1, 1, 'C');
    
            $contador++;
            $sumTotal += $total;
        }

        // Total de la compra
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(158, 5, 'Total ', 0, 0, 'R');
        $pdf->Cell(38, 5, $sumTotal, 1, 1, 'R');




        // Salida del archivo PDF
        $pdf->Output('ReporteDeProductosMasVendidos.pdf', 'I');
    }
}

$factura = new reporteTopProductosMasVendidos();
$factura->fechaInicio = $_GET["fechaInicio"];
$factura->fechaFin = $_GET["fechaFin"];
$factura->idUsuario = $_GET["idUsuario"];
$factura->generarPdfVentasTopProducto();
