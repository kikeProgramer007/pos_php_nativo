<?php

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
        $this->Cell(14, 5, 'Ticket', 1, 0, 'C');
        $this->Cell(34, 5, 'Fecha Y Hora', 1, 0, 'C');
        $this->Cell(30, 5, 'Usuario', 1, 0, 'C');
        $this->Cell(30, 5, 'Mesero', 1, 0, 'C');
        $this->Cell(30, 5, 'Cliente', 1, 0, 'C');
        $this->Cell(27, 5, 'Tipo de Pago', 1, 0, 'C');
        $this->Cell(15, 5, 'Monto', 1, 1, 'C');
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
        foreach ($respuestaVentas as $item) {
            // Verificar si hay espacio suficiente para la siguiente fila
            if ($this->GetY() > 250) {
                $this->AddPage();
                // Establecer la posición Y después del encabezado en la nueva página
                $this->SetY(66);
            }
            
            $total =  $item["total"];
            $this->Cell(10, 5,  $contador, 1, 0, 'L');
            $this->Cell(14, 5, ltrim($item["codigo"], '0'), 1, 0, 'C');
            // Mostrar fecha y hora con segundos y am/pm
            $fechaCompleta = $item["fecha"];
            $fechaFormateada = date('Y-m-d h:i:s a', strtotime($fechaCompleta));
            $this->Cell(34, 5, $fechaFormateada, 1, 0, 'C');
            $this->Cell(30, 5, $item["usuario"], 1, 0, 'C');
            $this->Cell(30, 5, strtolower($item["mesero"]), 1, 0, 'C');
            $this->Cell(30, 5, strtolower($item["cliente"]), 1, 0, 'C');
            $this->Cell(27, 5, $item["tipo_pago"], 1, 0, 'C');
            $this->Cell(15, 5, $total . ' Bs', 1, 1, 'C');
            $contador++;
            $sumTotal += $total;
        }

        // Verificar si hay espacio suficiente para los totales
        if ($this->GetY() > 250) {
            $this->AddPage();
            // Establecer la posición Y después del encabezado en la nueva página
            $this->SetY(40);
        }

        // Total de la Venta
        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(175, 5, 'Total ', 0, 0, 'R');
        $this->Cell(15, 5, number_format($sumTotal, 2, '.', ',') . ' Bs.', 1, 1, 'R');

        // Nro de compras
        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(30, 5, 'Número de ventas:  ' . $contador - 1, 0, 0, 'L');

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
