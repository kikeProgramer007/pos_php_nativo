<?php

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
require_once('tcpdf_include.php');

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
    private $nombreTienda = "Pollos Rosy";
    private $direccionTienda = "Refineria";
    private $respuestaUsuario;
    private $respuestaMesero;
    private $respuestaCategoria;
    private $respuestaCliente;
    private $DateAndTime;

    // Método Header que se repetirá en cada página
    public function Header()
    {
        // Establecer el margen superior
        $this->SetY(10);
        
        $this->SetFont('helvetica', 'B', 11);
        $this->Cell(0, 5, 'REPORTE DE VENTAS ENTRE FECHA', 0, 1, 'C');
        $this->Image('images/logo-negro-bloque.jpg', 90, 25, 30, 20, 'jpg');
        $this->Ln(10);

        // Nombre y dirección del restaurante
        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(23, 5, 'Restaurante:', 0, 0, 'L');
        $this->SetFont('helvetica', '', 9);
        $this->Cell(50, 5, $this->nombreTienda, 0, 1, 'L');

        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(23, 5, 'Dirección:', 0, 0, 'L');
        $this->SetFont('helvetica', '', 9);
        $this->Cell(50, 5, $this->direccionTienda, 0, 1, 'L');

        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(23, 5, 'Usuario: ', 0, 0, 'L');
        $this->SetFont('helvetica', '', 9);
        $this->Cell(50, 5, $this->respuestaUsuario["nombre"], 0, 1, 'L');

        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(23, 5, 'Pagos: ', 0, 0, 'L');
        $this->SetFont('helvetica', '', 9);
        $this->Cell(50, 5, $this->tipoPago == 0 ? 'Todos' : $this->tipoPago, 0, 1, 'L');

        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(23, 5, 'Categorias: ', 0, 0, 'L');
        $this->SetFont('helvetica', '', 9);
        $this->Cell(50, 5, $this->respuestaCategoria["categoria"], 0, 1, 'L');

        $this->SetY(25);
        $this->SetX(140);
        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(23, 5, 'Clientes: ', 0, 0, 'L');
        $this->SetFont('helvetica', '', 9);
        $this->Cell(50, 5, $this->respuestaCliente["nombre"], 0, 1, 'L');

        $this->SetX(140);
        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(23, 5, 'Meseros: ', 0, 0, 'L');
        $this->SetFont('helvetica', '', 9);
        $this->Cell(50, 5, $this->respuestaMesero["nombre"], 0, 1, 'L');

        $this->SetX(140);
        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(23, 5, 'Periodo: ', 0, 0, 'L');
        $this->SetFont('helvetica', '', 9);
        
        $meses = [
            '01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril',
            '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto',
            '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
        ];
        
        $mesInicio = date('m', strtotime($this->fechaInicio));
        $diaInicio = date('d', strtotime($this->fechaInicio));
        $anioInicio = date('Y', strtotime($this->fechaInicio));
        $nombreMesInicio = isset($meses[$mesInicio]) ? $meses[$mesInicio] : $mesInicio;
        
        $mesFin = date('m', strtotime($this->fechaFin));
        $diaFin = date('d', strtotime($this->fechaFin));
        $anioFin = date('Y', strtotime($this->fechaFin));
        $nombreMesFin = isset($meses[$mesFin]) ? $meses[$mesFin] : $mesFin;
        
        $periodo = 'Desde ' . $nombreMesInicio . ' ' . $diaInicio . ' del ' . $anioInicio . "\nHasta " . $nombreMesFin . ' ' . $diaFin . ' del ' . $anioFin;
        $this->MultiCell(38, 8, $periodo, 0, 'L');

        $this->SetX(140);
        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(23, 5, utf8_decode('Fecha y hora:'), 0, 0, 'L');
        $this->SetFont('helvetica', '', 9);
        $this->Cell(50, 5, $this->DateAndTime, 0, 1, 'L');

        // Agregar espacio después del encabezado
        $this->Ln(8);
        
        // Encabezado de la tabla
        $this->SetFont('helvetica', 'B', 9);
        $this->SetFillColor(0, 0, 0);
        $this->SetTextColor(255, 255, 255);
        $this->Cell(0, 5, 'Detalle De las ventas', 1, 1, 'C', 1);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(10, 5, '#', 1, 0, 'L');
        $this->Cell(12, 5, 'Ticket', 1, 0, 'C');
        $this->Cell(18, 5, 'Fecha', 1, 0, 'C');
        $this->Cell(22, 5, 'Usuario', 1, 0, 'C');
        $this->Cell(22, 5, 'Mesero', 1, 0, 'C');
        $this->Cell(25, 5, 'Cliente', 1, 0, 'C');
        $this->Cell(21, 5, 'T. Pago', 1, 0, 'C');
        $this->Cell(20, 5, 'Efectivo', 1, 0, 'C');
        $this->Cell(20, 5, 'Qr', 1, 0, 'C');
        $this->Cell(20, 5, 'Total', 1, 1, 'C');
    }

    public function generarPdfVentas()
    {
        // Establecer la zona horaria de Bolivia
        date_default_timezone_set('America/La_Paz');
        
        $respuestaVentas = ControladorVentas::ctrRangoFechasVentasPdf($this->fechaInicio, $this->fechaFin, $this->idMesero, $this->idCategoria, $this->idCliente, $this->registroEliminados, $this->tipoPago);

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

        $this->DateAndTime = date('d-m-Y h:i:s a', time());

        // Configuración del PDF para UTF-8
        $this->setPrintHeader(true);
        $this->setPrintFooter(false);

        // Configurar salto de página automático
        $this->SetAutoPageBreak(true, 20);

        // Ajustar márgenes
        $this->SetMargins(10, 10, 10);
        
        // Agregar primera página
        $this->AddPage();

        // Establecer la posición Y después del encabezado
        $this->SetY(66);

        $this->SetFont('helvetica', '', 8);

        //Imprimir los detalles de los productos
        $contador = 1;
        $sumTotal = 0;
        $sumTotalEfectivo = 0;
        $sumTotalQr = 0;

        foreach ($respuestaVentas as $item) {

            $total = $item["total"];
            $totalEfectivo = $item["total_efectivo"];
            $totalQr = $item["total_qr"];

            $fechaCompleta = $item["fecha"];
            $fechaFormateada = date('Y-m-d h:i:s a', strtotime($fechaCompleta));

            $fila = array(
                $contador,
                ltrim($item["codigo"], '0'),
                $fechaFormateada,
                $item["usuario"],
                strtolower($item["mesero"]),
                strtolower($item["cliente"]),
                $item["tipo_pago"],
                number_format($item["total_efectivo"], 2, '.', ','),
                number_format($item["total_qr"], 2, '.', ','),
                number_format($total, 2, '.', ',')
            );

            $anchos = array(10, 12, 18, 22, 22, 25, 21, 20, 20, 20);

            // Calcular altura automática según el texto más largo
            $altura = 4;
            foreach ($fila as $i => $texto) {
                $numLineas = $this->getNumLines($texto, $anchos[$i]);
                $altura = max($altura, $numLineas * 4);
            }

            // Salto de página si no entra la fila
            $limiteInferior = $this->getPageHeight() - $this->getBreakMargin();
            if (($this->GetY() + $altura) > $limiteInferior) {
                $this->AddPage();
                $this->SetY(66);
            }

            $x = $this->GetX();
            $y = $this->GetY();

            // Imprimir columnas con altura dinámica
            $this->MultiCell($anchos[0], $altura, $fila[0], 1, 'L', false, 0, $x, $y);
            $this->MultiCell($anchos[1], $altura, $fila[1], 1, 'C', false, 0);
            $this->MultiCell($anchos[2], $altura, $fila[2], 1, 'C', false, 0);
            $this->MultiCell($anchos[3], $altura, $fila[3], 1, 'C', false, 0);
            $this->MultiCell($anchos[4], $altura, $fila[4], 1, 'C', false, 0);
            $this->MultiCell($anchos[5], $altura, $fila[5], 1, 'C', false, 0);
            $this->MultiCell($anchos[6], $altura, $fila[6], 1, 'C', false, 0);
            $this->MultiCell($anchos[7], $altura, $fila[7], 1, 'R', false, 0);
            $this->MultiCell($anchos[8], $altura, $fila[8], 1, 'R', false, 0);
            $this->MultiCell($anchos[9], $altura, $fila[9], 1, 'R', false, 1);
            $contador++;
            $sumTotal += $total;
            $sumTotalEfectivo += $totalEfectivo;
            $sumTotalQr += $totalQr;
        }

        // Altura aproximada necesaria para imprimir totales
        $alturaTotales = 4;

        // Obtener límite inferior real de la página
        $limiteInferior  = $this->getPageHeight() - $this->getBreakMargin();

        // Si no entra, crear nueva página
        if (($this->GetY() + $alturaTotales) > $limiteInferior) {
            $this->AddPage();
            $this->SetY(66);
        }
        // Total general
        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(130, 5, 'Totales (Bs)', 0, 0, 'R');
        $this->Cell(20, 5, number_format($sumTotalEfectivo, 2, '.', ','), 1, 0, 'R');
        $this->Cell(20, 5, number_format($sumTotalQr, 2, '.', ','), 1, 0, 'R');
        $this->Cell(20, 5, number_format($sumTotal, 2, '.', ',') , 1, 1, 'R');

        // Nro de compras
        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(30, 5, 'Número de ventas:  ' . ($contador - 1), 0, 0, 'L');

        // Salida del archivo PDF
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
$factura->generarPdfVentas();
