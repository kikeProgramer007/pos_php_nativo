<?php

require_once "../../../controladores/compras.controlador.php";
require_once "../../../modelos/compras.modelo.php";
require_once "../../../controladores/reportes.controlador.php";
require_once "../../../modelos/reportes.modelo.php";
require_once "../../../controladores/usuarios.controlador.php";
require_once "../../../modelos/usuarios.modelo.php";
require_once "../../../controladores/productos.controlador.php";
require_once "../../../modelos/productos.modelo.php";
require_once "../../../fpdf/fpdf.php";

class PdfGanancias extends FPDF
{
    public $idUsuario;
    
    public $month = null;
    public $year = null;
    private $nombreTienda = "Pollos Rossy";
    private $direccionTienda = "Refineria";

    // Sobrescribimos el método Header para añadir el encabezado
    function Header()
    {
        $this->SetFont('helvetica', 'B', 12);
        $this->Cell(0, 5, 'REPORTE DE  GANANCIAS POR MES', 0, 1, 'C');
        $this->Image('images/logo-negro-bloque.jpg', 90, 25, 30, 20, 'jpg');

        $this->Ln(5); // Espacio después de la imagen
        $this->SetX(140); // Ajusta este valor según sea necesario ancho
        $this->SetY(30);  // Ajusta este valor según sea necesario altura

        // Nombre y dirección del restaurante
        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(23, 5, 'Restaurante:', 0, 0, 'L');
        $this->SetFont('helvetica', '', 9);
        $this->Cell(50, 5, utf8_decode($this->nombreTienda), 0, 1, 'L');


        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(23, 5, utf8_decode('Dirección:'), 0, 0, 'L');

        $this->SetFont('helvetica', '', 9);
        $this->Cell(50, 5, $this->direccionTienda, 0, 1, 'L');

        $this->SetY(35);  // Ajusta este valor según sea necesario altura
        $this->SetX(140); // Ajusta este valor según sea necesario ancho
        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(23, 5, 'Usuario:', 0, 0, 'L');
        $this->SetFont('helvetica', '', 9);
        $usuario = ControladorUsuarios::ctrMostrarUsuarios('id', $this->idUsuario);
        $this->Cell(50, 5, utf8_decode($usuario["nombre"]), 0, 1, 'L');

        $this->SetX(140); // Ajusta este valor según sea necesario ancho
        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(23, 5, 'Periodo:', 0, 0, 'L');
        $this->SetFont('helvetica', '', 9);
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
        $mesConCero = str_pad($this->month, 2, '0', STR_PAD_LEFT);
        $nombreMes = isset($meses[$mesConCero]) ? $meses[$mesConCero] : $this->month;
        $this->Cell(100, 5, $nombreMes . ' de ' . $this->year, 0, 1, 'L');

        $DateAndTime = date('d-m-Y h:i:s a', time());
        $this->SetY(40);  // Ajusta este valor según sea necesario altura
        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(23, 5, utf8_decode('Fecha y hora:'), 0, 0, 'L');
        $this->SetFont('helvetica', '', 9);
        $this->Cell(50, 5, $DateAndTime, 0, 1, 'L');
        
        $this->Ln(5);

        $this->SetFont('Arial', 'B', 11);
        $this->Cell(12, 5, utf8_decode('#'), 1, 0, 'L');
        $this->Cell(26, 5, utf8_decode('Nº Ticket'), 1, 0, 'C');
        $this->Cell(26, 5, utf8_decode('Fecha'), 1, 0, 'C');
        $this->Cell(30, 5, utf8_decode('Usuario'), 1, 0, 'C');
        $this->Cell(50, 5, utf8_decode('Mesero'), 1, 0, 'C');
        $this->Cell(24, 5, utf8_decode('Monto'), 1, 0, 'C');
        $this->Cell(24, 5, utf8_decode('Ganancia'), 1, 1, 'C');
        $this->SetFont('Arial', '', 11);
    }

    public function generarpdfganancia()
    {
        // Establecer la zona horaria de Bolivia
        date_default_timezone_set('America/La_Paz');
        $pdf = $this;
        $pdf->AddPage();
        $ganancias = ModeloReportes::mdlObtenerGanancias($this->month, $this->year);

        $contador = 1;
        $sum = 0;
        $sum_ganancias = 0;

        foreach ($ganancias as $ganancia) {
            $pdf->Cell(12, 5, $contador, 1, 0, 'L');
            $pdf->Cell(26, 5, ltrim($ganancia['codigo'],'0'),1, 0, 'C');
            $pdf->Cell(26, 5, utf8_decode($ganancia['fecha']), 1, 0, 'C');
            $pdf->Cell(30, 5, utf8_decode($ganancia['vendedor']), 1, 0, 'C');
            $pdf->Cell(50, 5, utf8_decode($ganancia['mesero']), 1, 0, 'C');
            $pdf->Cell(24, 5, $ganancia['total']. ' Bs.', 1, 0, 'C');
            $pdf->Cell(24, 5, number_format($ganancia['ganancias'], 2, '.', ','). ' Bs.', 1, 1, 'C');
            $sum += $ganancia['total'];
            $sum_ganancias += $ganancia['ganancias'];
            $contador++;
        }

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(168, 5, 'Total Ganancias', 0, 0, 'R');
        $pdf->Cell(24, 5, number_format($sum_ganancias, 2, '.', ',') . ' Bs.', 1, 0, 'R');
        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(38, 5, utf8_decode('Número de ventas:'), 0, 0, 'L');
        $pdf->Cell(20, 5, $contador - 1, 0, 1, 'L');
        $pdf->Cell(38, 5, utf8_decode('Monto total de ventas:'), 0, 0, 'L');
        $pdf->Cell(20, 5, number_format($sum, 2, '.', ',') . ' Bs.', 0, 1, 'L');
        $pdf->Cell(38, 5, utf8_decode('Total Ganancia:'), 0, 0, 'L');
        $pdf->Cell(20, 5, number_format($sum_ganancias, 2, '.', ',') . ' Bs.', 0, 1, 'L');

        $pdf->Output('gananciaspormes.pdf', 'I');
    }
}

$factura = new PdfGanancias();
$factura->month = $_GET["month"];
$factura->year = $_GET["year"];
$factura->idUsuario = $_GET["idUsuario"];
$factura->generarpdfganancia();

?>
