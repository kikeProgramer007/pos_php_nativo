<?php


require_once "../../../controladores/ventas.controlador.php";
require_once "../../../modelos/ventas.modelo.php";

require_once "../../../controladores/usuarios.controlador.php";
require_once "../../../modelos/usuarios.modelo.php";

require_once "../../../controladores/meseros.controlador.php";
require_once "../../../modelos/meseros.modelo.php";

require_once "../../../controladores/categorias.controlador.php";
require_once "../../../modelos/categorias.modelo.php";

require_once "../../../controladores/productos.controlador.php";
require_once "../../../modelos/productos.modelo.php";

class reporteTopProductosMasVendidos
{

    public $fechaInicio;
    public $fechaFin;
    public $idUsuario;
    public $idCategoria;
    public $idMesero;
    public $idProductos = array();
    private $nombreTienda = "Pollos 360 ";
    private $direccionTienda = "Ave.Miguel Servet ";

    private function textoProductos()
    {
        $ids = is_array($this->idProductos) ? $this->idProductos : array();
        if (count($ids) === 0) {
            return "Todos los productos";
        }
        if (count($ids) === 1) {
            $producto = ControladorProductos::ctrMostrarProductos("id", $ids[0], "id");
            if (is_array($producto) && isset($producto["descripcion"])) {
                return $producto["descripcion"];
            }
        }
        return count($ids) . " productos";
    }

    private function monto($valor)
    {
        return number_format((float) $valor, 2, ',', '.');
    }

    public function generarPdfVentasTopProducto()
    {
        date_default_timezone_set('America/La_Paz');
        $fechaInicio = $this->fechaInicio;
        $fechaFin = $this->fechaFin;
        $idUsuario = $this->idUsuario;
        $idCategoria = $this->idCategoria;
        $idMesero = isset($this->idMesero) ? $this->idMesero : null;
        $idProductos = is_array($this->idProductos) ? $this->idProductos : array();

        $respuestaDatos = ControladorVentas::ctrRangoFechasTopProductoMasVendidosPdf($fechaInicio, $fechaFin, $idCategoria, $idMesero, $idProductos);
        if (!is_array($respuestaDatos)) {
            $respuestaDatos = array();
        }

        $sumCantidad = 0;
        $sumVenta = 0;
        $sumCosto = 0;
        $sumDescuento = 0;
        $sumNeta = 0;
        $sumGanancia = 0;
        foreach ($respuestaDatos as $item) {
            $sumCantidad += floatval($item['cantidad'] ?? 0);
            $sumVenta += floatval($item['precio_venta'] ?? 0);
            $sumCosto += floatval($item['costo'] ?? 0);
            $sumDescuento += floatval($item['descuento'] ?? 0);
            $sumNeta += floatval($item['venta_neta'] ?? 0);
            $sumGanancia += floatval($item['ganancia'] ?? 0);
        }
        $itemUsuario = "id";
        $respuestaUsuario = ControladorUsuarios::ctrMostrarUsuarios($itemUsuario, $idUsuario);

        if ($idCategoria != 0) {
            $respuestaCategoria = ControladorCategorias::ctrMostrarCategorias("id", $idCategoria);
            $categoriaTexto = $respuestaCategoria["categoria"];
        } else {
            $categoriaTexto = "Todas las categorías";
        }

        if (!empty($idMesero) && $idMesero !== "0") {
            $respuestaMesero = ControladorMeseros::ctrMostrarMeseros("id", $idMesero, 1);
            $meseroTexto = is_array($respuestaMesero) && isset($respuestaMesero["nombre"]) ? $respuestaMesero["nombre"] : ($respuestaMesero ? $respuestaMesero[0]["nombre"] : "Todos los meseros");
        } else {
            $meseroTexto = "Todos los meseros";
        }

        require_once('tcpdf_include.php');

        $pdf = new TCPDF('P', 'mm', 'LETTER', true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(6, 6, 6);
        $pdf->SetAutoPageBreak(true, 6);
        $pdf->SetTitle('Reporte de productos vendidos');
        $pdf->AddPage();

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(0, 5, 'Reporte de productos vendidos', 0, 1, 'C');
        $pdf->Ln(1);

        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(22, 4, 'Restaurante:', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(68, 4, $this->nombreTienda, 0, 0, 'L');
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(20, 4, 'Usuario:', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(0, 4, $respuestaUsuario["nombre"], 0, 1, 'L');

        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(22, 4, 'Direccion:', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(68, 4, $this->direccionTienda, 0, 0, 'L');
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(20, 4, 'Categoria:', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(0, 4, $categoriaTexto, 0, 1, 'L');

        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(22, 4, 'Generado:', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(68, 4, date('d-m-Y h:i a'), 0, 0, 'L');
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(20, 4, 'Mesero:', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(0, 4, $meseroTexto, 0, 1, 'L');

        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(22, 4, 'Periodo:', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(68, 4, date("d-m-Y", strtotime($fechaInicio)) . " al " . date("d-m-Y", strtotime($fechaFin)), 0, 0, 'L');
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(20, 4, 'Producto:', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(0, 4, $this->textoProductos(), 0, 1, 'L');

        $pdf->Ln(2);
        $alto = 5;
        $cNum = 6;
        $cFecha = 18;
        $cMesero = 24;
        $cProducto = 40;
        $cCant = 12;
        $cVenta = 20;
        $cCosto = 18;
        $cDesc = 20;
        $cNeta = 22;
        $cGan = 20;
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->SetFillColor(0, 0, 0);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell($cNum, $alto, '#', 1, 0, 'C', 1);
        $pdf->Cell($cFecha, $alto, 'Fecha', 1, 0, 'C', 1);
        $pdf->Cell($cMesero, $alto, 'Mesero', 1, 0, 'C', 1);
        $pdf->Cell($cProducto, $alto, 'Producto', 1, 0, 'C', 1);
        $pdf->Cell($cCant, $alto, 'Cant.', 1, 0, 'C', 1);
        $pdf->Cell($cVenta, $alto, 'P. venta', 1, 0, 'C', 1);
        $pdf->Cell($cCosto, $alto, 'Costo', 1, 0, 'C', 1);
        $pdf->Cell($cDesc, $alto, 'Descuento', 1, 0, 'C', 1);
        $pdf->Cell($cNeta, $alto, 'Venta neta', 1, 0, 'C', 1);
        $pdf->Cell($cGan, $alto, 'Ganancia', 1, 1, 'C', 1);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('helvetica', '', 8);

        $contador = 1;

        foreach ($respuestaDatos as $item) {
            $cantidad = floatval($item['cantidad'] ?? 0);
            $precioVenta = floatval($item['precio_venta'] ?? 0);
            $costo = floatval($item['costo'] ?? 0);
            $descuento = floatval($item['descuento'] ?? 0);
            $ventaNeta = floatval($item['venta_neta'] ?? 0);
            $ganancia = floatval($item['ganancia'] ?? 0);

            $pdf->Cell($cNum, $alto, $contador, 1, 0, 'C');
            $pdf->Cell($cFecha, $alto, date('d/m/Y', strtotime($item['fecha'])), 1, 0, 'C');
            $pdf->Cell($cMesero, $alto, isset($item['mesero']) ? $item['mesero'] : 'Sin mesero', 1, 0, 'L');
            $pdf->Cell($cProducto, $alto, $item['descripcion'], 1, 0, 'L');
            $pdf->Cell($cCant, $alto, $cantidad, 1, 0, 'C');
            $pdf->Cell($cVenta, $alto, $this->monto($precioVenta), 1, 0, 'R');
            $pdf->Cell($cCosto, $alto, $this->monto($costo), 1, 0, 'R');
            $pdf->Cell($cDesc, $alto, $this->monto($descuento), 1, 0, 'R');
            $pdf->Cell($cNeta, $alto, $this->monto($ventaNeta), 1, 0, 'R');
            $pdf->Cell($cGan, $alto, $this->monto($ganancia), 1, 1, 'R');

            $contador++;
        }

        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell($cNum + $cFecha + $cMesero + $cProducto, $alto, 'Total', 'TR', 0, 'R');
        $pdf->Cell($cCant, $alto, $sumCantidad, 1, 0, 'C');
        $pdf->Cell($cVenta, $alto, $this->monto($sumVenta), 1, 0, 'R');
        $pdf->Cell($cCosto, $alto, $this->monto($sumCosto), 1, 0, 'R');
        $pdf->Cell($cDesc, $alto, $this->monto($sumDescuento), 1, 0, 'R');
        $pdf->Cell($cNeta, $alto, $this->monto($sumNeta), 1, 0, 'R');
        $pdf->Cell($cGan, $alto, $this->monto($sumGanancia), 1, 1, 'R');

        $pdf->Ln(3);
        $pdf->SetFont('helvetica', 'B', 9);
        $resumen = array(
            array('Vendiste:', $sumNeta),
            array('Te costó:', $sumCosto),
            array('Te quedó (ganancia):', $sumGanancia),
        );
        $anchoEtiqueta = 0;
        foreach ($resumen as $linea) {
            $anchoEtiqueta = max($anchoEtiqueta, $pdf->GetStringWidth($linea[0]));
        }
        $anchoEtiqueta += 2;
        foreach ($resumen as $linea) {
            $pdf->Cell($anchoEtiqueta, 4.5, $linea[0], 0, 0, 'L');
            $pdf->Cell(28, 4.5, $this->monto($linea[1]) . ' Bs.', 0, 1, 'L');
        }

        $pdf->Output('ReporteDeProductosVendidos.pdf', 'I');
    }
}

$idsProducto = isset($_GET["idProducto"]) ? $_GET["idProducto"] : array();
if (!is_array($idsProducto)) {
    $idsProducto = ($idsProducto === '' || $idsProducto === null) ? array() : array($idsProducto);
}

$factura = new reporteTopProductosMasVendidos();
$factura->fechaInicio = $_GET["fechaInicio"];
$factura->fechaFin = $_GET["fechaFin"];
$factura->idUsuario = $_GET["idUsuario"];
$factura->idCategoria = isset($_GET["idCategoria"]) ? intval($_GET["idCategoria"]) : 0;
$factura->idMesero = isset($_GET["idMesero"]) && $_GET["idMesero"] !== "" ? intval($_GET["idMesero"]) : null;
$factura->idProductos = $idsProducto;
$factura->generarPdfVentasTopProducto();
