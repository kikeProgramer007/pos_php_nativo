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

class PdfGananciasFechas extends FPDF
{
    public $idUsuario;
    public $fechaInicio = null;
    public $fechaFin = null;
    private $nombreTienda = "El Gato Rico ";
    private $direccionTienda = "Heroes Del Chaco 9,Cotoca";

    function Header()
    {
        $this->SetFont('helvetica', 'B', 12);
        $this->Cell(0, 5, 'REPORTE DE GANANCIAS ENTRE FECHAS', 0, 1, 'C');
        $this->Image('images/logo-negro-bloque.jpg', 90, 25, 30, 20, 'jpg');

        $this->Ln(5);
        $this->SetX(140);
        $this->SetY(30);

        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(23, 5, 'Restaurante:', 0, 0, 'L');
        $this->SetFont('helvetica', '', 9);
        $this->Cell(50, 5, utf8_decode($this->nombreTienda), 0, 1, 'L');

        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(23, 5, utf8_decode('Dirección:'), 0, 0, 'L');
        $this->SetFont('helvetica', '', 9);
        $this->Cell(50, 5, $this->direccionTienda, 0, 1, 'L');

        $this->SetY(35);
        $this->SetX(140);
        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(23, 5, 'Usuario:', 0, 0, 'L');
        $this->SetFont('helvetica', '', 9);
        $usuario = ControladorUsuarios::ctrMostrarUsuarios('id', $this->idUsuario);
        $this->Cell(50, 5, utf8_decode($usuario["nombre"]), 0, 1, 'L');

        $this->SetX(140);
        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(23, 5, 'Periodo:', 0, 0, 'L');
        $this->SetFont('helvetica', '', 9);
        $ini = date('d/m/Y', strtotime($this->fechaInicio));
        $fin = date('d/m/Y', strtotime($this->fechaFin));
        $this->Cell(100, 5, $ini . ' - ' . $fin, 0, 1, 'L');

        $DateAndTime = date('d-m-Y h:i:s a', time());
        $this->SetY(40);
        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(23, 5, utf8_decode('Fecha y hora:'), 0, 0, 'L');
        $this->SetFont('helvetica', '', 9);
        $this->Cell(50, 5, $DateAndTime, 0, 1, 'L');

        $this->Ln(5);

        // Ticket | Fecha | Mesero | Cobrado | Descuento | Costo | Ganancia  (~190)
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(20, 6, utf8_decode('Ticket'), 1, 0, 'C');
        $this->Cell(24, 6, utf8_decode('Fecha'), 1, 0, 'C');
        $this->Cell(50, 6, utf8_decode('Mesero'), 1, 0, 'C');
        $this->Cell(24, 6, utf8_decode('Cobrado'), 1, 0, 'C');
        $this->Cell(24, 6, utf8_decode('Descuento'), 1, 0, 'C');
        $this->Cell(24, 6, utf8_decode('Costo'), 1, 0, 'C');
        $this->Cell(24, 6, utf8_decode('Ganancia'), 1, 1, 'C');
        $this->SetFont('Arial', '', 8);
    }

    private function fmtBs($n)
    {
        return number_format((float) $n, 2, '.', ',') . ' Bs.';
    }

    public function generarpdfganancia()
    {
        date_default_timezone_set('America/La_Paz');
        $pdf = $this;
        $pdf->AddPage();
        $ganancias = ModeloReportes::mdlObtenerGananciasEntreFechas($this->fechaInicio, $this->fechaFin);

        $sumCobrado = 0;
        $sumCosto = 0;
        $sumGanancias = 0;

        foreach ($ganancias as $row) {
            $cobrado = floatval($row['total'] ?? 0);
            $descuento = floatval($row['total_descuento'] ?? 0);
            $costo = floatval($row['costo'] ?? 0);
            $ganancia = floatval($row['ganancias'] ?? 0);

            $mesero = (string) ($row['mesero'] ?? '');
            if (strlen($mesero) > 28) {
                $mesero = substr($mesero, 0, 27) . '.';
            }

            $pdf->Cell(20, 5, ltrim((string) $row['codigo'], '0'), 1, 0, 'C');
            $pdf->Cell(24, 5, utf8_decode($row['fecha']), 1, 0, 'C');
            $pdf->Cell(50, 5, utf8_decode($mesero), 1, 0, 'L');
            $pdf->Cell(24, 5, $this->fmtBs($cobrado), 1, 0, 'R');
            $pdf->Cell(24, 5, $this->fmtBs($descuento), 1, 0, 'R');
            $pdf->Cell(24, 5, $this->fmtBs($costo), 1, 0, 'R');
            $pdf->Cell(24, 5, $this->fmtBs($ganancia), 1, 1, 'R');

            $sumCobrado += $cobrado;
            $sumCosto += $costo;
            $sumGanancias += $ganancia;
        }

        $pdf->Ln(6);
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(40, 6, utf8_decode('Vendiste:'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(50, 6, $this->fmtBs($sumCobrado), 0, 1, 'L');

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(40, 6, utf8_decode('Te costó:'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(50, 6, $this->fmtBs($sumCosto), 0, 1, 'L');

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(40, 6, utf8_decode('Te quedó (ganancia):'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(50, 6, $this->fmtBs($sumGanancias), 0, 1, 'L');

        $pdf->Output('ReporteDeGananciasEntreFechas.pdf', 'I');
    }
}

$factura = new PdfGananciasFechas();
$factura->fechaInicio = isset($_GET["fechaInicio"]) ? $_GET["fechaInicio"] : '';
$factura->fechaFin = isset($_GET["fechaFin"]) ? $_GET["fechaFin"] : '';
$factura->idUsuario = isset($_GET["idUsuario"]) ? $_GET["idUsuario"] : 0;
$factura->generarpdfganancia();
