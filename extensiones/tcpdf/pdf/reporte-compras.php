<?php

require_once __DIR__ . "/empresa-pdf.php";
require_once "../../../controladores/compras.controlador.php";
require_once "../../../modelos/compras.modelo.php";

require_once "../../../controladores/categorias.controlador.php";
require_once "../../../modelos/categorias.modelo.php";

require_once "../../../controladores/proveedor.controlador.php";
require_once "../../../modelos/proveedor.modelo.php";

require_once "../../../controladores/usuarios.controlador.php";
require_once "../../../modelos/usuarios.modelo.php";

require_once "../../../controladores/productos.controlador.php";
require_once "../../../modelos/productos.modelo.php";

class reporteCompra
{


    public $fechaInicio;
    public $fechaFin;
    public $idProveedor;
    public $idUsuario;
    public $idCategoria;
    public $tipoPago = 0;
    public $descontarCaja = 0;
    private $nombreTienda = "El Gato Rico ";
    private $direccionTienda = "Heroes Del Chaco 9,Cotoca";

    /** Anchos fijos: título, cabecera y filas usan el mismo total. */
    private function anchosColumnas()
    {
        return array(
            array('w' => 8,  'txt' => '#', 'align' => 'C'),
            array('w' => 16, 'txt' => 'Recibo Nº', 'align' => 'C'),
            array('w' => 28, 'txt' => 'Fecha', 'align' => 'C'),
            array('w' => 28, 'txt' => 'Usuario', 'align' => 'C'),
            array('w' => 32, 'txt' => 'Proveedor', 'align' => 'C'),
            array('w' => 28, 'txt' => 'F. Pago', 'align' => 'C'),
            array('w' => 18, 'txt' => 'Efectivo', 'align' => 'C'),
            array('w' => 18, 'txt' => 'Qr', 'align' => 'C'),
            array('w' => 19, 'txt' => 'Total', 'align' => 'C'),
        );
    }

    private function anchoTabla()
    {
        $total = 0;
        foreach ($this->anchosColumnas() as $celda) {
            $total += $celda['w'];
        }
        return $total;
    }

    private function dibujarTituloTabla($pdf)
    {
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->SetFillColor(0, 0, 0);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell($this->anchoTabla(), 5, 'Detalle de compras', 1, 1, 'C', 1);
        $pdf->SetTextColor(0, 0, 0);
    }

    private function dibujarCabeceraTabla($pdf)
    {
        // Sin fondo negro: solo bordes y texto negro, mismo ancho que las filas.
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(255, 255, 255);
        pdfDibujarFila($pdf, $this->anchosColumnas(), 6);
        $pdf->SetFont('helvetica', '', 7);
    }

    public function generarPdfCompras()
    {
        list($this->nombreTienda, $this->direccionTienda) = empresaPdfTextos($this->nombreTienda, $this->direccionTienda);
        date_default_timezone_set('America/La_Paz');
        $fechaInicio = $this->fechaInicio;
        $fechaFin = $this->fechaFin;
        $idProveedor = $this->idProveedor;
        $idUsuario = $this->idUsuario;
        $idCategoria = $this->idCategoria;
        $tipoPago = $this->tipoPago;
        $descontarCaja = $this->descontarCaja;

        $respuestaCompra = ControladorCompras::ctrRangoFechasComprasPdf($fechaInicio, $fechaFin, $idProveedor, $idCategoria, $tipoPago, $descontarCaja);

        $itemUsuario = "id";

        $respuestaUsuario = ControladorUsuarios::ctrMostrarUsuarios($itemUsuario, $idUsuario);

        $itemVendedor = "id";

        if ($idProveedor != 0) {
            $respuestaProveedor = ControladorProveedors::ctrMostrarProveedors($itemVendedor, $idProveedor);
        } else {
            $respuestaProveedor["nombre"] = "Todos";
        }
        if ($idCategoria != 0) {
            $respuestaCategoria = ControladorCategorias::ctrMostrarCategorias("id", $idCategoria);
        } else {
            $respuestaCategoria["categoria"] = "Todos";
        }
        $etiquetaTipoPago = "Todos";
        if (in_array((string) $tipoPago, array("1", "2", "4"), true)) {
            $etiquetaTipoPago = ControladorCompras::etiquetaFormaPagoCompra($tipoPago);
        }
        $etiquetaDescontarCaja = ControladorCompras::etiquetaDescontarCajaFiltro($descontarCaja);

        require_once('tcpdf_include.php');
        require_once('fila-tabla.php');

        $pdf = new TCPDF('P', 'mm', 'LETTER', true, 'UTF-8', false);

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->SetMargins(10, 10, 10);
        $pdf->SetAutoPageBreak(true, 12);
        $pdf->SetTitle('Reporte De Compra de productos');
        $pdf->AddPage();

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 5, 'REPORTES DE COMPRAS ENTRE FECHA', 0, 1, 'C');
        $pdf->Image(empresaPdfLogo(), 90, 25, 30, 20);
        $pdf->Ln(10);

        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(23, 5, 'Restaurante:', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(50, 5, $this->nombreTienda, 0, 1, 'L');

        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(23, 5, 'Dirección:', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(50, 5, $this->direccionTienda, 0, 1, 'L');

        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(23, 5, 'Usuario: ', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(50, 5, $respuestaUsuario["nombre"], 0, 1, 'L');
        $pdf->SetY(30);
        $pdf->SetX(140);

        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(23, 5, 'Categoria: ', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(50, 5, $respuestaCategoria["categoria"], 0, 1, 'L');
        $pdf->SetX(140);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(23, 5, 'Proveedor: ', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(50, 5, $respuestaProveedor["nombre"], 0, 1, 'L');
        $pdf->SetX(140);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(23, 5, 'Periodo: ', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(100, 5, date("d-m-Y ", strtotime($fechaInicio)) . " al " . date("d-m-Y", strtotime($fechaFin)), 0, 1, 'L');
        $pdf->SetX(140);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(23, 5, 'F. Pago: ', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(50, 5, $etiquetaTipoPago, 0, 1, 'L');
        $pdf->SetX(140);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(23, 5, 'Impacto: ', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(50, 5, $etiquetaDescontarCaja, 0, 1, 'L');

        $DateAndTime = date('d-m-Y h:i:s a', time());
        $pdf->SetY(50);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(23, 5, utf8_decode('Fecha y hora:'), 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(50, 5, $DateAndTime, 0, 1, 'L');

        $pdf->Ln(5);
        $this->dibujarTituloTabla($pdf);
        $this->dibujarCabeceraTabla($pdf);

        $contador = 1;
        $sumTotal = 0;
        $sumEfectivo = 0;
        $sumQr = 0;
        $anchos = $this->anchosColumnas();

        foreach ($respuestaCompra as $item) {
            $montos = ControladorCompras::montosReporteCompra($item);
            $fechaFormateada = date('Y-m-d H:i', strtotime($item["fecha_alta"]));

            pdfDibujarFila($pdf, array(
                array('w' => $anchos[0]['w'], 'txt' => $contador, 'align' => 'C'),
                array('w' => $anchos[1]['w'], 'txt' => $item["codigo"], 'align' => 'C'),
                array('w' => $anchos[2]['w'], 'txt' => $fechaFormateada, 'align' => 'C'),
                array('w' => $anchos[3]['w'], 'txt' => $item["usuario"], 'align' => 'L'),
                array('w' => $anchos[4]['w'], 'txt' => $item["proveedor"], 'align' => 'L'),
                array('w' => $anchos[5]['w'], 'txt' => ControladorCompras::etiquetaFormaPagoCompra($item["tipo_pago"] ?? ""), 'align' => 'C'),
                array('w' => $anchos[6]['w'], 'txt' => number_format($montos["efectivo"], 2, '.', ','), 'align' => 'R'),
                array('w' => $anchos[7]['w'], 'txt' => number_format($montos["qr"], 2, '.', ','), 'align' => 'R'),
                array('w' => $anchos[8]['w'], 'txt' => number_format($montos["total"], 2, '.', ','), 'align' => 'R'),
            ), 5, function ($pdfRef) {
                $this->dibujarTituloTabla($pdfRef);
                $this->dibujarCabeceraTabla($pdfRef);
            });
            $contador++;
            $sumTotal += $montos["total"];
            $sumEfectivo += $montos["efectivo"];
            $sumQr += $montos["qr"];
        }

        $anchoIzquierda = $anchos[0]['w'] + $anchos[1]['w'] + $anchos[2]['w'] + $anchos[3]['w'] + $anchos[4]['w'] + $anchos[5]['w'];
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell($anchoIzquierda, 5, 'Totales (Bs)', 0, 0, 'R');
        $pdf->Cell($anchos[6]['w'], 5, number_format($sumEfectivo, 2, '.', ','), 1, 0, 'R');
        $pdf->Cell($anchos[7]['w'], 5, number_format($sumQr, 2, '.', ','), 1, 0, 'R');
        $pdf->Cell($anchos[8]['w'], 5, number_format($sumTotal, 2, '.', ','), 1, 1, 'R');

        $pdf->Ln(2);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(30, 5, 'Número de compras:  ' . ($contador - 1), 0, 0, 'L');

        $pdf->Output('ReporteDeCompras.pdf', 'I');
    }
}

$factura = new reporteCompra();
$factura->fechaInicio = $_GET["fechaInicio"];
$factura->fechaFin = $_GET["fechaFin"];
$factura->idProveedor = $_GET["idProveedor"];
$factura->idUsuario = $_GET["idUsuario"];
$factura->idCategoria = $_GET["idCategoria"];
$factura->tipoPago = isset($_GET["tipoPago"]) ? $_GET["tipoPago"] : 0;
$factura->descontarCaja = isset($_GET["descontarCaja"]) ? $_GET["descontarCaja"] : 0;
$factura->generarPdfCompras();
