<?php

require_once "../../../controladores/ventas.controlador.php";
require_once "../../../modelos/ventas.modelo.php";

require_once "../../../controladores/clientes.controlador.php";
require_once "../../../modelos/clientes.modelo.php";

require_once "../../../controladores/meseros.controlador.php";
require_once "../../../modelos/meseros.modelo.php";

require_once "../../../controladores/usuarios.controlador.php";
require_once "../../../modelos/usuarios.modelo.php";

require_once "../../../controladores/productos.controlador.php";
require_once "../../../modelos/productos.modelo.php";

class imprimirFacturaComanda
{

    public $codigo;

    public function pdfToBase64(TCPDF $pdf): string {
        return base64_encode($pdf->Output('', 'S')); // S = string
    }

    /**
     * Genera el HTML de la tabla de productos
     */
    private function generarTablaProductos($productos, $total, $totalPagado, $cambio, $notaGeneral = '', $esComanda = false) {
        $html = '<table border="0" cellpadding="0" style="width:100%; font-size: 7px; ">
            <tbody>
            <tr>
                <th style="width:41%; border-top: 0.5px solid #000000; border-bottom: 0.5px solid #000000; text-align:left; font-weight: bold;">DETALLE</th>
                <th style="width:9%; border-top: 0.5px solid #000000; border-bottom: 0.5px solid #000000; text-align:center; font-weight: bold;">F.A</th>
                <th style="width:8%; border-top: 0.5px solid #000000; border-bottom: 0.5px solid #000000; text-align:center; font-weight: bold;">CNT</th>
                <th style="width:17%; border-top: 0.5px solid #000000; border-bottom: 0.5px solid #000000; text-align:right; font-weight: bold;">PRECIO</th>
                <th style="width:23%; border-top: 0.5px solid #000000; border-bottom: 0.5px solid #000000; text-align:right; font-weight: bold;">SUBTOTAL</th>
            </tr>';

        foreach ($productos as $item) {
            $valorUnitario = number_format($item["precio_venta"], 2);
            $precioTotal = number_format($item["subtotal"], 2);
            $preferencias = $item['preferencias'] ?? '';
            $notaItem = $item['nota_adicional'] ?? '';
            
            $preferencias = preg_replace('/[\x{1F300}-\x{1F9FF}]/u', '', $preferencias);
            $preferencias = preg_replace('/[❌✅]/u', '', $preferencias);
            $preferencias = trim($preferencias);
            
            $texto = implode(' - ', array_filter([$preferencias, $notaItem]));
            $preferenciasYNotaAdicional = $texto 
                ? '<br><span style="font-size: 9px; color: #666666;">(' . $texto . ')</span>' 
                : '';
          
            $html .= '
                <tr>
                    <td style="font-size: 10px; padding: 3px 0;">' . $item["producto"] . $preferenciasYNotaAdicional . '</td>
                    <td style="text-align:center; font-size: 9px; padding: 3px 0;">' . $item["forma_atencion"] . '</td>
                    <td style="text-align:center; font-size: 9px; padding: 3px 0;">' . $item["cantidad"] . '</td>
                    <td style="text-align:right; font-size: 9px; padding: 3px 0;">' . $valorUnitario . '</td>
                    <td style="text-align:right; font-size: 9px; padding: 3px 0;">' . $precioTotal . '</td>
                </tr>';
        }

        // Solo añadir totales si no es comanda
        if (!$esComanda) {
            $html .= '
            <tr>
                <td colspan="3" style="border-top: 0.5px solid #000000; text-align:left; font-size: 9px;"><strong>TOTAL:</strong></td>
                <td colspan="2" style="border-top: 0.5px solid #000000; text-align:right; font-size: 9px;">Bs ' . $total . '</td>
            </tr>
            <tr>
                <td colspan="3" style="text-align:left; font-size: 9px;"><strong>PAGADO:</strong></td>
                <td colspan="2" style="text-align:right; font-size: 9px;">Bs ' . $totalPagado . '</td>
            </tr>
            <tr>
                <td colspan="3" style="text-align:left; font-size: 9px;"><strong>CAMBIO:</strong></td>
                <td colspan="2" style="text-align:right; font-size: 9px;">Bs ' . $cambio . '</td>
            </tr>';
        } else {
            $html .= '<tr><td colspan="5" style="border-top: 0.5px solid #000000; font-size: 9px;"></td></tr>';
        }

        // Añadir nota general si existe y es comanda
        if ($esComanda && !empty($notaGeneral)) {
            $html .= '<tr>
                <td width="13%"><strong>NOTA:</strong></td>
                <td width="86%">' . $notaGeneral . '</td>
            </tr>';
        }

        $html .= '</tbody></table>';
        
        if (!$esComanda) {
            $html .= '<p style="font-size: 9px; text-align: center;">¡GRACIAS POR SU COMPRA!<br>PEDIDOS AL 75620296</p>';
        }

        return $html;
    }

    /**
     * Genera el encabezado de la factura
     */
    private function generarEncabezadoFactura($respuestaVenta, $respuestaCliente, $respuestaVendedor, $tipoPago) {
        $fechaSolo = date('d-m-Y', strtotime($respuestaVenta["fecha"]));
        $horaSolo = date('H:i:s a', strtotime($respuestaVenta["fecha"]));

        return '<table border="0">
            <tbody>
            <tr>
                <td style="text-align:center;">
                    <span style="font-size: 10px;">POLLOS ROSSY</span><br>
                    <span style="font-size: 14px;"><strong>N° PEDIDO:' . ltrim($respuestaVenta["codigo"], '0') . '</strong></span><br>
                    <span style="font-size: 8px;">Fecha: ' . $fechaSolo . ' &nbsp;&nbsp; Hora: ' . $horaSolo . '</span>
                </td>
            </tr>
            </tbody>
        </table>
        <table>
            <thead>
            <tr>
                <th width="25%"></th>
                <th width="3%"></th>
                <th width="72%"></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td width="25%"><strong>CLIENTE</strong></td>
                <td width="3%"><strong>:</strong></td>
                <td width="72%">' . $respuestaCliente["nombre"] . '</td>
            </tr>
            <tr>
                <td width="25%"><strong>CAJERO/A</strong></td>
                <td width="3%"><strong>:</strong></td>
                <td width="72%">' . $respuestaVendedor["nombre"] . '</td>
            </tr>
            <tr>
                <td width="25%"><strong>VÍA PAGO</strong></td>
                <td width="3%"><strong>:</strong></td>
                <td width="72%">' . $tipoPago . '</td>
            </tr>
            <tr><td colspan="2"></td></tr>
            </tbody>
        </table>';
    }

    /**
     * Genera el encabezado de la comanda
     */
    private function generarEncabezadoComanda($respuestaVenta, $respuestaCliente, $respuestaMesero, $fecha, $formaAtencion) {
        return '<table border="0" cellpadding="1" style="font-size: 9px; padding:0px; width:100%;">
            <tbody>
            <tr>
                <td style="text-align:center;">
                    <span style="font-size: 16px;"><strong>&lt;&lt; COCINA &gt;&gt;</strong></span><br>
                    <span style="font-size: 15px; font-weight: bold;">' . ltrim($respuestaVenta["codigo"], '0') . '</span>
                </td>
            </tr>
            </tbody>
        </table>
        <table>
            <thead>
            <tr>
                <th width="25%"></th>
                <th width="3%"></th>
                <th width="72%"></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td width="25%"><strong>CLIENTE</strong></td>
                <td width="3%"><strong>:</strong></td>
                <td width="72%">' . $respuestaCliente["nombre"] . '</td>
            </tr>
            <tr>
                <td width="25%"><strong>MESERO/A</strong></td>
                <td width="3%"><strong>:</strong></td>
                <td width="72%">' . $respuestaMesero["nombre"] . '</td>
            </tr>
            <tr>
                <td width="25%"><strong>FECHA</strong></td>
                <td width="3%"><strong>:</strong></td>
                <td width="72%">' . $fecha . '</td>
            </tr>
            <tr>
                <td width="25%"><strong>ATENCIÓN</strong></td>
                <td width="3%"><strong>:</strong></td>
                <td width="72%">' . $formaAtencion . '</td>
            </tr>
            <tr><td colspan="3"></td></tr>
            </tbody>
        </table>';
    }
    
    public function traerImpresionFacturaComanda()
    {

        // Obtener información de la venta
        $itemVenta = "id";
        $valorVenta = $this->codigo;

        $respuestaVenta = ControladorVentas::ctrMostrarVentas($itemVenta, $valorVenta);
        if($respuestaVenta!=null){
            $fecha = date('d-m-Y H:i:s a', strtotime($respuestaVenta["fecha"]));
            $productos = ControladorVentas::ctrMostrarDetalleVentas($respuestaVenta['id']);

            $total = number_format($respuestaVenta["total"], 2);
            $cambio = number_format($respuestaVenta["cambio"], 2);
            $tipoPago = $respuestaVenta["tipo_pago"];
            $totalPagado = number_format($respuestaVenta["total_pagado"], 2);
            $notaGeneral = trim($respuestaVenta["nota"] ?? '');
            
            // Obtener información del cliente
            $respuestaCliente = ControladorClientes::ctrMostrarClientesActivoInactivos("id", $respuestaVenta["id_cliente"]);

            // Obtener información del mesero
            $respuestaMesero = ControladorMeseros::ctrMostrarMeserosActivoInactivo("id", $respuestaVenta["id_mesero"]);

            // Obtener información del vendedor
            $respuestaVendedor = ControladorUsuarios::ctrMostrarUsuariosActivoInactivo("id", $respuestaVenta["id_vendedor"]);

            // Configuración del PDF para impresora térmica
            require_once('tcpdf_include.php');

            // Calcular altura dinámica
            $alturaBase = 80;
            $alturaPorFila = 10;
            $cantidadFilas = count($productos);
            $alturaTotal = $alturaBase + ($alturaPorFila * $cantidadFilas);

            // Generar encabezados reutilizables
            $encabezadoFactura = $this->generarEncabezadoFactura($respuestaVenta, $respuestaCliente, $respuestaVendedor, $tipoPago);
            $tablaProductos = $this->generarTablaProductos($productos, $total, $totalPagado, $cambio, '', false);
            
            $encabezadoComanda = $this->generarEncabezadoComanda($respuestaVenta, $respuestaCliente, $respuestaMesero, $fecha, $respuestaVenta["forma_atencion"]);
            $tablaProductosComanda = $this->generarTablaProductos($productos, $total, $totalPagado, $cambio, $notaGeneral, true);

            // ===== PDF FACTURA (individual) =====
            $pdfFactura = new TCPDF('P', 'mm', array(72, $alturaTotal), true, 'UTF-8', false);
            $pdfFactura->SetMargins(1, 1, 0);
            $pdfFactura->setPrintHeader(false);
            $pdfFactura->setPrintFooter(false);
            $pdfFactura->SetAutoPageBreak(false, 0);
            $pdfFactura->setFontSubsetting(true);
            $pdfFactura->AddPage();
            $pdfFactura->SetFont('helvetica', '', 8);
            $pdfFactura->writeHTML($encabezadoFactura . $tablaProductos, false, false, false, false, '');
            $facturaBase64 = $this->pdfToBase64($pdfFactura);

            // ===== PDF COMANDA (individual) =====
            $pdfComanda = new TCPDF('P', 'mm', array(72, $alturaTotal), true, 'UTF-8', false);
            $pdfComanda->SetMargins(1, 1, 0);
            $pdfComanda->setPrintHeader(false);
            $pdfComanda->setPrintFooter(false);
            $pdfComanda->SetAutoPageBreak(false, 0);
            $pdfComanda->AddPage();
            $pdfComanda->SetFont('helvetica', '', 9);
            $pdfComanda->writeHTML($encabezadoComanda . $tablaProductosComanda, false, false, false, false, '');
            $comandaBase64 = $this->pdfToBase64($pdfComanda);

            // ===== PDF COMBINADO (Factura + Comanda) =====
            $pdfCombinado = new TCPDF('P', 'mm', array(72, $alturaTotal), true, 'UTF-8', false);
            $pdfCombinado->SetMargins(1, 1, 0);
            $pdfCombinado->setPrintHeader(false);
            $pdfCombinado->setPrintFooter(false);
            $pdfCombinado->SetAutoPageBreak(false, 0);
            
            // Página 1: Factura
            $pdfCombinado->AddPage();
            $pdfCombinado->SetFont('helvetica', '', 8);
            $pdfCombinado->writeHTML($encabezadoFactura . $tablaProductos, false, false, false, false, '');
            
            // Página 2: Comanda
            $pdfCombinado->AddPage();
            $pdfCombinado->SetFont('helvetica', '', 9);
            $pdfCombinado->writeHTML($encabezadoComanda . $tablaProductosComanda, false, false, false, false, '');
            
            $facturaComandaBase64 = $this->pdfToBase64($pdfCombinado);

            // Retornar JSON con los tres PDFs
            header('Content-Type: application/json');    
            echo json_encode([
                'success' => true,
                'facturaBase64' => $facturaBase64,
                'comandaBase64' => $comandaBase64,
                'facturaComandaBase64' => $facturaComandaBase64
            ]);
            exit;

        } else {
            echo'<script>
                    window.location = "../../../crear-venta";
                </script>'; 
        }
    }
}

$factura = new imprimirFacturaComanda();
$factura->codigo = $_GET["codigo"];
$factura->traerImpresionFacturaComanda();