<?php

require_once __DIR__ . "/empresa-pdf.php";

// Arriba del archivo
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

require_once "../../../controladores/ventas.controlador.php";
require_once "../../../modelos/ventas.modelo.php";

require_once "../../../controladores/categorias.controlador.php";
require_once "../../../modelos/categorias.modelo.php";

require_once "../../../controladores/clientes.controlador.php";
require_once "../../../modelos/clientes.modelo.php";

require_once "../../../controladores/meseros.controlador.php";
require_once "../../../modelos/meseros.modelo.php";

require_once "../../../controladores/usuarios.controlador.php";
require_once "../../../modelos/usuarios.modelo.php";
require_once "../../../modelos/mesas.modelo.php";
require_once('tcpdf_include.php');
require_once('fila-tabla.php');

class reporteVenta extends TCPDF
{
    public $fechaInicio;
    public $fechaFin;
    public $idMesero;
    public $idUsuario;
    public $idCategoria;
    public $idCliente;
    public $registroEliminados;
    public $tipoPago;
    public $estadoPago;
    public $formaAtencion = "0";
    public $idsMesas = array();
    private $gestionMesas = false;
    private $nombreTienda = "El Gato Rico ";
    private $direccionTienda = "Heroes Del Chaco 9,Cotoca";
    private $respuestaUsuario;
    private $respuestaMesero;
    private $respuestaCategoria;
    private $respuestaCliente;
    private $etiquetaEstadoPago;
    private $etiquetaFormaAtencion;
    private $etiquetaMesas = "Todas";
    private $DateAndTime;
    private $yInicioTabla = 42;

    private function etiquetaEstadoPagoFiltro()
    {
        if ($this->estadoPago == "1") {
            return "Pendiente";
        }
        if ($this->estadoPago == "2") {
            return "Pagado";
        }
        return "Todos";
    }

    private function etiquetaFormaAtencionFiltro()
    {
        if ($this->formaAtencion == "1") {
            return "Mesa";
        }
        if ($this->formaAtencion == "2") {
            return "Llevar";
        }
        return "Todos";
    }

    private function etiquetaEstadoPagoFila($estadoPago)
    {
        if ($estadoPago === "PENDIENTE") {
            return "PENDIENTE";
        }
        if ($estadoPago === "PAGADA") {
            return "PAGADO";
        }
        return $estadoPago ?: "-";
    }

    /** Parte el mixto en 2 líneas limpias dentro de T. Pago. */
    private function textoTipoPagoCorto($tipoPago)
    {
        $tipoPago = trim((string) $tipoPago);
        if ($tipoPago === "") {
            return "-";
        }
        if (stripos($tipoPago, "Mixto") !== false) {
            return "Qr y Efectivo\n(Mixto)";
        }
        return $tipoPago;
    }

    /**
     * Anchos exactos al margen derecho (pageWidth - márgenes).
     * T. Pago lleva más peso para "Qr y Efectivo(Mixto)" en hasta 2 líneas.
     */
    private function anchosColumnas()
    {
        $disponible = $this->getPageWidth() - $this->lMargin - $this->rMargin;
        // Con mesas: # Ticket Fecha Usuario Mesero Cliente Mesa Pers Estado F.Aten T.Pago Efectivo Qr Total
        // Sin mesas:  # Ticket Fecha Usuario Mesero Cliente Estado F.Aten T.Pago Efectivo Qr Total
        $pesos = $this->gestionMesas
            ? array(3.2, 4.8, 7.2, 6.5, 6.5, 7.0, 7.5, 4.0, 5.8, 6.2, 7.5, 6.5, 6.0, 7.0)
            : array(3.2, 4.8, 7.2, 7.0, 7.0, 7.5, 6.0, 6.5, 8.0, 7.0, 6.5, 7.5);
        $sumaPesos = array_sum($pesos);
        $anchos = array();
        $acumulado = 0.0;
        $ultimo = count($pesos) - 1;
        for ($i = 0; $i < $ultimo; $i++) {
            $w = round($disponible * ($pesos[$i] / $sumaPesos), 2);
            $anchos[] = $w;
            $acumulado += $w;
        }
        $anchos[] = round($disponible - $acumulado, 2);
        return $anchos;
    }

    /** Índices de Efectivo / Qr / Total según flag de mesas. */
    private function indicesMontos()
    {
        $n = count($this->anchosColumnas());
        return array($n - 3, $n - 2, $n - 1);
    }

    private function anchoTabla()
    {
        return array_sum($this->anchosColumnas());
    }

    /** Línea compacta etiqueta: valor en (x, y). */
    private function lineaFiltro($x, $y, $etiqueta, $valor, $wEtiqueta = 22, $wValor = 68)
    {
        $this->SetXY($x, $y);
        $this->SetFont('helvetica', 'B', 8);
        $this->Cell($wEtiqueta, 4, $etiqueta, 0, 0, 'L');
        $this->SetFont('helvetica', '', 8);
        $this->Cell($wValor, 4, $valor, 0, 0, 'L');
    }

    // Método Header que se repetirá en cada página
    public function Header()
    {
        list($this->nombreTienda, $this->direccionTienda) = empresaPdfTextos($this->nombreTienda, $this->direccionTienda);

        $pageW = $this->getPageWidth();
        $margenIzq = $this->lMargin;
        $margenDer = $this->rMargin;
        $usable = $pageW - $margenIzq - $margenDer;

        // Logo arriba a la derecha
        $logoW = 26;
        $logoH = 16;
        $logoX = $pageW - $margenDer - $logoW;
        $logoY = 5;
        $this->Image(empresaPdfLogo(), $logoX, $logoY, $logoW, $logoH);

        // Título centrado dejando hueco al logo
        $this->SetXY($margenIzq, 7);
        $this->SetFont('helvetica', 'B', 11);
        $this->Cell($usable - $logoW - 3, 5, 'REPORTE DE VENTAS ENTRE FECHA', 0, 1, 'C');

        $pagos = ($this->tipoPago == 0 || $this->tipoPago === "0") ? "Todos" : $this->tipoPago;
        $usuario = isset($this->respuestaUsuario["nombre"]) ? $this->respuestaUsuario["nombre"] : "";
        $categoria = isset($this->respuestaCategoria["categoria"]) ? $this->respuestaCategoria["categoria"] : "Todos";
        $cliente = isset($this->respuestaCliente["nombre"]) ? $this->respuestaCliente["nombre"] : "Todos";
        $mesero = isset($this->respuestaMesero["nombre"]) ? $this->respuestaMesero["nombre"] : "Todos";
        $periodo = date('d/m/Y', strtotime($this->fechaInicio)) . ' - ' . date('d/m/Y', strtotime($this->fechaFin));

        // 3 columnas × 4 filas, debajo del bloque título/logo
        $colW = $usable / 3;
        $x1 = $margenIzq;
        $x2 = $margenIzq + $colW;
        $x3 = $margenIzq + (2 * $colW);
        $wLab = 20;
        $wVal = $colW - $wLab - 2;
        $y0 = max($logoY + $logoH + 1, 22);
        $lh = 4;

        // Columna 1
        $this->lineaFiltro($x1, $y0, 'Restaurante:', $this->nombreTienda, $wLab, $wVal);
        $this->lineaFiltro($x1, $y0 + $lh, 'Direccion:', $this->direccionTienda, $wLab, $wVal);
        $this->lineaFiltro($x1, $y0 + (2 * $lh), 'Usuario:', $usuario, $wLab, $wVal);
        $this->lineaFiltro($x1, $y0 + (3 * $lh), 'Estado:', $this->etiquetaEstadoPago, $wLab, $wVal);

        // Columna 2
        $this->lineaFiltro($x2, $y0, 'Pagos:', $pagos, $wLab, $wVal);
        $this->lineaFiltro($x2, $y0 + $lh, 'F. Aten.:', $this->etiquetaFormaAtencion, $wLab, $wVal);
        if ($this->gestionMesas) {
            $this->lineaFiltro($x2, $y0 + (2 * $lh), 'Mesas:', $this->etiquetaMesas, $wLab, $wVal);
            $this->lineaFiltro($x2, $y0 + (3 * $lh), 'Categorias:', $categoria, $wLab, $wVal);
        } else {
            $this->lineaFiltro($x2, $y0 + (2 * $lh), 'Categorias:', $categoria, $wLab, $wVal);
        }

        // Columna 3
        $this->lineaFiltro($x3, $y0, 'Clientes:', $cliente, $wLab, $wVal);
        $this->lineaFiltro($x3, $y0 + $lh, 'Meseros:', $mesero, $wLab, $wVal);
        $this->lineaFiltro($x3, $y0 + (2 * $lh), 'Periodo:', $periodo, $wLab, $wVal);
        $this->lineaFiltro($x3, $y0 + (3 * $lh), 'Fecha/hora:', $this->DateAndTime, $wLab, $wVal);

        $this->SetY($y0 + (4 * $lh) + 1);
    }

    private function dibujarCabeceraTabla()
    {
        $w = $this->anchosColumnas();
        $this->SetFont('helvetica', 'B', 8);
        $this->SetFillColor(0, 0, 0);
        $this->SetTextColor(255, 255, 255);
        $this->Cell($this->anchoTabla(), 5, 'Detalle De las ventas', 1, 1, 'C', 1);
        $this->SetTextColor(0, 0, 0);
        $labels = array('#', 'Ticket', 'Fecha', 'Usuario', 'Mesero', 'Cliente');
        if ($this->gestionMesas) {
            $labels[] = 'Mesa';
            $labels[] = 'Pers.';
        }
        $labels = array_merge($labels, array('Estado', 'F. Aten.', 'T. Pago', 'Efectivo', 'Qr', 'Total'));
        $ultimo = count($labels) - 1;
        for ($i = 0; $i < $ultimo; $i++) {
            $this->Cell($w[$i], 5, $labels[$i], 1, 0, 'C');
        }
        $this->Cell($w[$ultimo], 5, $labels[$ultimo], 1, 1, 'C');

        $this->SetFont('helvetica', '', 6);
    }

    public function generarPdfVentas()
    {
        // Establecer la zona horaria de Bolivia
        date_default_timezone_set('America/La_Paz');

        $this->gestionMesas = ModeloEmpresa::mdlFuncionActiva("venta.gestion_mesas");
        $this->etiquetaEstadoPago = $this->etiquetaEstadoPagoFiltro();
        $this->etiquetaFormaAtencion = $this->etiquetaFormaAtencionFiltro();
        if (!is_array($this->idsMesas)) {
            $this->idsMesas = array();
        }
        $this->idsMesas = $this->gestionMesas
            ? array_values(array_unique(array_filter(array_map('intval', $this->idsMesas))))
            : array();
        if ($this->gestionMesas && $this->idsMesas) {
            $nombresMesas = array();
            foreach ($this->idsMesas as $idM) {
                $txt = ModeloMesas::mdlTextoMesasVenta(array("id_mesa" => $idM));
                if ($txt !== "") {
                    $nombresMesas[] = $txt;
                }
            }
            $this->etiquetaMesas = $nombresMesas ? implode("; ", $nombresMesas) : "Seleccionadas";
        } else {
            $this->etiquetaMesas = "Todas";
        }
        $respuestaVentas = ControladorVentas::ctrRangoFechasVentasPdf($this->fechaInicio, $this->fechaFin, $this->idMesero, $this->idCategoria, $this->idCliente, $this->registroEliminados, $this->tipoPago, $this->estadoPago, $this->formaAtencion, $this->idsMesas);

        $itemUsuario = "id";
        $this->respuestaUsuario = ControladorUsuarios::ctrMostrarUsuariosActivoInactivo($itemUsuario, $this->idUsuario);

        //TRAEMOS LA INFORMACIÓN DEL VENDEDOR
        $itemMesero = "id";

        if ($this->idMesero != 0) {
            $this->respuestaMesero = ControladorMeseros::ctrMostrarMeserosActivoInactivo($itemMesero, $this->idMesero);
        } else {
            $this->respuestaMesero["nombre"] = "Todos";
        }
        
        if ($this->idCategoria != 0) {
            $this->respuestaCategoria = ControladorCategorias::ctrMostrarCategoriasActivasInactivas("id", $this->idCategoria);
        } else {
            $this->respuestaCategoria["categoria"] = "Todos";
        }
        
        if ($this->idCliente != 0) {
            $this->respuestaCliente = ControladorClientes::ctrMostrarClientesActivoInactivos("id", $this->idCliente);
        } else {
            $this->respuestaCliente["nombre"] = "Todos";
        }

        $this->DateAndTime = date('d-m-Y H:i:s', time());

        // Configuración del PDF para UTF-8
        $this->setPrintHeader(true);
        $this->setPrintFooter(false);

        // Configurar salto de página automático
        $this->SetAutoPageBreak(true, 8);

        // Márgenes; top deja espacio al header compacto
        $this->SetMargins(8, 40, 8);
        
        // Agregar primera página
        $this->AddPage('L');
        $this->SetY($this->yInicioTabla);
        $this->dibujarCabeceraTabla();

        $this->SetFont('helvetica', '', 6);

        //Imprimir los detalles de los productos
        $contador = 1;
        $sumTotal = 0;
        $sumTotalEfectivo = 0;
        $sumTotalQr = 0;
        $sumTotalBruto = 0;
        $sumTotalDescuento = 0;
        $w = $this->anchosColumnas();
        list($iEf, $iQr, $iTot) = $this->indicesMontos();
        $anchoAntesMontos = 0.0;
        for ($i = 0; $i < $iEf; $i++) {
            $anchoAntesMontos += $w[$i];
        }
        $anchoMontos = $w[$iEf] + $w[$iQr] + $w[$iTot];

        foreach ($respuestaVentas as $item) {

            $total = $item["total"];
            $totalEfectivo = $item["total_efectivo"];
            $totalQr = $item["total_qr"];
            $totalBruto = floatval($item["total_bruto"] ?? $total);
            $totalDescuento = floatval($item["total_descuento"] ?? 0);

            $fechaCompleta = $item["fecha"];
            $fechaFormateada = date('Y-m-d H:i', strtotime($fechaCompleta));
            $tipoPagoTexto = $this->textoTipoPagoCorto($item["tipo_pago"] ?? "");

            $celdas = array(
                array('w' => $w[0], 'txt' => $contador, 'align' => 'C'),
                array('w' => $w[1], 'txt' => ltrim($item["codigo"], '0'), 'align' => 'C'),
                array('w' => $w[2], 'txt' => $fechaFormateada, 'align' => 'C'),
                array('w' => $w[3], 'txt' => $item["usuario"], 'align' => 'L'),
                array('w' => $w[4], 'txt' => $item["mesero"], 'align' => 'L'),
                array('w' => $w[5], 'txt' => $item["cliente"], 'align' => 'L'),
            );
            if ($this->gestionMesas) {
                $textoMesa = ModeloMesas::mdlTextoMesasVenta($item);
                if ($textoMesa === "") {
                    $textoMesa = "-";
                }
                $personas = (isset($item["cantidad_personas"]) && $item["cantidad_personas"] !== null && $item["cantidad_personas"] !== "")
                    ? intval($item["cantidad_personas"])
                    : "-";
                $celdas[] = array('w' => $w[6], 'txt' => $textoMesa, 'align' => 'L');
                $celdas[] = array('w' => $w[7], 'txt' => $personas, 'align' => 'C');
            }
            $celdas[] = array('w' => $w[$iEf - 3], 'txt' => $this->etiquetaEstadoPagoFila($item["estado_pago"] ?? ''), 'align' => 'C');
            $celdas[] = array('w' => $w[$iEf - 2], 'txt' => ControladorVentas::etiquetaFormaAtencion($item["forma_atencion"] ?? ''), 'align' => 'C');
            $celdas[] = array('w' => $w[$iEf - 1], 'txt' => $tipoPagoTexto, 'align' => 'C');
            $celdas[] = array('w' => $w[$iEf], 'txt' => number_format($item["total_efectivo"], 2, '.', ','), 'align' => 'R');
            $celdas[] = array('w' => $w[$iQr], 'txt' => number_format($item["total_qr"], 2, '.', ','), 'align' => 'R');
            $celdas[] = array('w' => $w[$iTot], 'txt' => number_format($total, 2, '.', ','), 'align' => 'R');

            pdfDibujarFila($this, $celdas, 4, function () {
                $this->SetY($this->yInicioTabla);
                $this->dibujarCabeceraTabla();
                $this->SetFont('helvetica', '', 6);
            });
            $contador++;
            $sumTotal += $total;
            $sumTotalEfectivo += $totalEfectivo;
            $sumTotalQr += $totalQr;
            $sumTotalBruto += $totalBruto;
            $sumTotalDescuento += $totalDescuento;
        }

        $alturaTotales = 28;
        $limiteInferior = $this->getPageHeight() - $this->getBreakMargin();

        if (($this->GetY() + $alturaTotales) > $limiteInferior) {
            $this->AddPage('L');
            $this->SetY($this->yInicioTabla);
        }

        $this->SetFont('helvetica', 'B', 9);
        $this->Cell($anchoAntesMontos, 5, 'Totales (Bs)', 0, 0, 'R');
        $this->Cell($w[$iEf], 5, number_format($sumTotalEfectivo, 2, '.', ','), 1, 0, 'R');
        $this->Cell($w[$iQr], 5, number_format($sumTotalQr, 2, '.', ','), 1, 0, 'R');
        $this->Cell($w[$iTot], 5, number_format($sumTotal, 2, '.', ','), 1, 1, 'R');

        $this->Ln(2);
        $this->SetFont('helvetica', '', 8);
        $this->Cell($anchoAntesMontos, 5, 'Total items (bruto):', 0, 0, 'R');
        $this->Cell($anchoMontos, 5, 'Bs ' . number_format($sumTotalBruto, 2, '.', ','), 0, 1, 'R');
        $this->Cell($anchoAntesMontos, 5, 'Total descuentos:', 0, 0, 'R');
        $this->Cell($anchoMontos, 5, 'Bs ' . number_format($sumTotalDescuento, 2, '.', ','), 0, 1, 'R');
        $this->SetFont('helvetica', 'B', 8);
        $this->Cell($anchoAntesMontos, 5, 'Total neto cobrado:', 0, 0, 'R');
        $this->Cell($anchoMontos, 5, 'Bs ' . number_format($sumTotal, 2, '.', ','), 0, 1, 'R');

        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(30, 5, 'Número de ventas:  ' . ($contador - 1), 0, 0, 'L');

        $this->Output('ReporteVentas.pdf', 'I');
    }
}

$factura = new reporteVenta();
$factura->fechaInicio = $_GET["fechaInicio"];
$factura->fechaFin = $_GET["fechaFin"];
$factura->idMesero = $_GET["idMesero"];
$factura->idUsuario = $_GET["idUsuario"];
$factura->idCategoria = $_GET["idCategoria"];
$factura->idCliente = $_GET["idCliente"];
$factura->registroEliminados = $_GET["registroEliminados"];
$factura->tipoPago = $_GET["tipoPago"];
$factura->estadoPago = isset($_GET["estadoPago"]) ? $_GET["estadoPago"] : "0";
$factura->formaAtencion = isset($_GET["formaAtencion"]) ? $_GET["formaAtencion"] : "0";
$factura->idsMesas = isset($_GET["idsMesas"]) ? $_GET["idsMesas"] : array();
$factura->generarPdfVentas();
