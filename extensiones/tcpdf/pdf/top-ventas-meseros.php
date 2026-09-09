<?php

require_once "../../../controladores/ventas.controlador.php";
require_once "../../../modelos/ventas.modelo.php";

require_once "../../../controladores/meseros.controlador.php";
require_once "../../../modelos/meseros.modelo.php";

require_once "../../../controladores/categorias.controlador.php";
require_once "../../../modelos/categorias.modelo.php";

require_once "../../../controladores/usuarios.controlador.php";
require_once "../../../modelos/usuarios.modelo.php";


class reporteTopVentasMeseros
{


    public $fechaInicio;
    public $fechaFin;
    public $idUsuario;
    public $idMesero;
    public $idCategoria;
    private $nombreTienda = "Pollos 360 ";
    private $direccionTienda = "Ave.Miguel Servet ";

    public function generarPdfVentasTopMeseros()
    {
        // Establecer la zona horaria de Bolivia
        date_default_timezone_set('America/La_Paz');
        $fechaInicio = $this->fechaInicio;
        $fechaFin = $this->fechaFin;
        $idUsuario = $this->idUsuario;
        $idMesero = $this->idMesero;
        $idCategoria = $this->idCategoria;

        $respuestaVentas = ControladorVentas::ctrRangoFechasVentasTopMeserosPdf($fechaInicio, $fechaFin, $idMesero, $idCategoria);
        $itemUsuario = "id";
        $respuestaUsuario = ControladorUsuarios::ctrMostrarUsuariosActivoInactivo($itemUsuario, $idUsuario);

        if ($idMesero != 0) {
            $itemMesero = "id";
            $respuestaMesero = ControladorMeseros::ctrMostrarMeseros($itemMesero, $idMesero);
            $meseroTexto = $respuestaMesero["nombre"];
        } else {
            $meseroTexto = "Todos los meseros";
        }

        $categoriaTexto = "Todas las categorías";
        if (!empty($idCategoria)) {
            $categoriaIds = is_array($idCategoria) ? $idCategoria : array($idCategoria);
            $categoriaIds = array_filter($categoriaIds, function ($id) {
                return intval($id) !== 0;
            });

            if (count($categoriaIds) > 0) {
                $nombres = array();
                foreach ($categoriaIds as $categoriaId) {
                    $itemCategoria = "id";
                    $respuestaCategoria = ControladorCategorias::ctrMostrarCategorias($itemCategoria, intval($categoriaId));
                    if (!empty($respuestaCategoria["categoria"])) {
                        $nombres[] = $respuestaCategoria["categoria"];
                    }
                }
                if (count($nombres) > 0) {
                    $categoriaTexto = implode(', ', $nombres);
                }
            }
        }

        require_once('tcpdf_include.php');

        // Configuración del PDF para UTF-8
        $pdf = new TCPDF('P', 'mm', 'LETTER', true, 'UTF-8', false);


        // Desactivar encabezado y pie de página
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Ajustar márgenes a cero
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetTitle(' Reporte De Mesero Con Mas Ventas');
        $pdf->AddPage();

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(0, 5, 'Reporte De Mesero Con Mas Ventas', 0, 1, 'C');
        $pdf->Ln(5);

        $DateAndTime = date('d-m-Y h:i:s a', time());

        $pdf->SetFont('helvetica', '', 9);
        $htmlHeader = '<table cellpadding="4" cellspacing="0" border="0" width="100%" style="font-size:9px; font-family:helvetica;">'
            . '<tr>'
            . '<td width="35%" style="vertical-align:top; font-size:9px;">'
            . '<strong>Restaurante:</strong> <span style="font-weight:normal;">' . htmlspecialchars($this->nombreTienda) . '</span><br/>'
            . '<strong>Dirección:</strong> <span style="font-weight:normal;">' . htmlspecialchars($this->direccionTienda) . '</span><br/>'
            . '<strong>Usuario:</strong> <span style="font-weight:normal;">' . htmlspecialchars($respuestaUsuario["nombre"]) . '</span><br/>'
            . '</td>'
            . '<td width="30%" align="center" style="vertical-align:middle;">'
            . '<img src="images/logo-negro-bloque.jpg" width="80" />'
            . '</td>'
            . '<td width="35%" style="vertical-align:top; font-size:9px; white-space:normal; word-wrap:break-word;">'
            . '<strong>Mesero:</strong> <span style="font-weight:normal;">' . htmlspecialchars($meseroTexto) . '</span><br/>'
            . '<strong>Categoría:</strong> <span style="font-weight:normal;">' . htmlspecialchars($categoriaTexto) . '</span><br/>'
            . '<strong>Periodo:</strong> <span style="font-weight:normal;">' . htmlspecialchars(date("d-m-Y ", strtotime($fechaInicio)) . " al " . date("d-m-Y", strtotime($fechaFin))) . '</span><br/>'
            . '<strong>Fecha y hora:</strong> <span style="font-weight:normal;">' . htmlspecialchars($DateAndTime) . '</span><br/>'
            . '</td>'
            . '</tr>'
            . '</table>';

        $pdf->writeHTMLCell(0, 0, '', '', $htmlHeader, 0, 1, false, true, 'J', true);
        $pdf->Ln(3);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->SetFillColor(0, 0, 0);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(0, 5, 'Detalle de Top Ventas por Meseros', 1, 1, 'C', 1);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(18, 5, 'Nro Top', 1, 0, 'C');
        $pdf->Cell(70, 5, 'Mesero', 1, 0, 'C');
        $pdf->Cell(48, 5, 'Cantidad Ventas', 1, 0, 'C');
        $pdf->Cell(60, 5, 'Monto Ventas', 1, 1, 'C');
        $pdf->SetFont('helvetica', '', 8);

        //Imprimir los detalles de los productos
        $contador = 1;
        $sumTotal = 0;
        foreach ($respuestaVentas as $item) {
            $total =  $item["total"];
            $pdf->Cell(18, 5,  $contador, 1, 0, 'C');
            $pdf->Cell(70, 5, $item["mesero"], 1, 0, 'C');
            $pdf->Cell(48, 5, $item["cantidad"], 1, 0, 'C');
       
            $pdf->Cell(60, 5, $item["total"] . ' Bs', 1, 1, 'C');
            $contador++;
            $sumTotal += $total;
        }

        // Total de la compra
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(166, 5, 'Total ', 0, 0, 'R');
        $pdf->Cell(30, 5, number_format($sumTotal, 2, '.', ',') . ' Bs.', 1, 1, 'R');


        // Nro de compras
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(30, 5, 'Total Meseros:  ' . $contador - 1, 0, 0, 'L');

        // Salida del archivo PDF
        $pdf->Output('ReporteDeMeserosConMasVentas.pdf', 'I');
    }
}

$factura = new reporteTopVentasMeseros();
$factura->fechaInicio = $_GET["fechaInicio"];
$factura->fechaFin = $_GET["fechaFin"];
$factura->idUsuario = $_GET["idUsuario"];
$factura->idMesero = isset($_GET["idMesero"]) ? intval($_GET["idMesero"]) : 0;
$factura->idCategoria = isset($_GET["idCategoria"]) ? $_GET["idCategoria"] : array();
$factura->generarPdfVentasTopMeseros();
