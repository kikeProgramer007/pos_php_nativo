<?php

require_once "../../../controladores/ventas.controlador.php";
require_once "../../../modelos/ventas.modelo.php";

require_once "../../../controladores/clientes.controlador.php";
require_once "../../../modelos/clientes.modelo.php";

require_once "../../../controladores/usuarios.controlador.php";
require_once "../../../modelos/usuarios.modelo.php";

require_once "../../../controladores/productos.controlador.php";
require_once "../../../modelos/productos.modelo.php";

class imprimirFactura
{

    public $codigo;

    public function traerImpresionFactura()
    {

        // Obtener información de la venta
        $itemVenta = "codigo";
        $valorVenta = $this->codigo;

        $respuestaVenta = ControladorVentas::ctrMostrarVentas($itemVenta, $valorVenta);
        $fecha = date('d/m/Y H:i', strtotime($respuestaVenta["fecha"]));
        $productos = ControladorVentas::ctrMostrarDetalleVentas($respuestaVenta['id']);

        $total = number_format($respuestaVenta["total"], 2);
        $cambio = number_format($respuestaVenta["cambio"], 2);
        $tipoPago = $respuestaVenta["tipo_pago"];
        $totalPagado = number_format($respuestaVenta["total_pagado"], 2);
        $nota = $respuestaVenta["nota"];
        // Obtener información del cliente
        $itemCliente = "id";
        $valorCliente = $respuestaVenta["id_cliente"];
        $respuestaCliente = ControladorClientes::ctrMostrarClientes($itemCliente, $valorCliente);

        // Obtener información del vendedor
        $itemVendedor = "id";
        $valorVendedor = $respuestaVenta["id_vendedor"];
        $respuestaVendedor = ControladorUsuarios::ctrMostrarUsuarios($itemVendedor, $valorVendedor);

        // Configuración del PDF para impresora térmica
        require_once('tcpdf_include.php');

        // Definir altura base (en mm) para encabezado y márgenes.
        $alturaBase = 70;
        // Definir altura por fila (puedes ajustarlo según el contenido).
        $alturaPorFila = 5;

        // Calcular la altura dinámica en base al número de filas
        $cantidadFilas = count($productos); // Obtener la cantidad de productos
        $alturaTotal = $alturaBase + ($alturaPorFila * $cantidadFilas); // Altura total

        // Crear el documento con la altura calculada
         $pdf = new TCPDF('P', 'mm', array(80, $alturaTotal), true, 'UTF-8', false);

        //$pdf = new TCPDF('P', 'mm', array(80, 60), true, 'UTF-8', false);

        $pdf->SetMargins(5, 5, 5); // Establece el margen inferior en 0
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetAutoPageBreak(true, 5); // Desactiva el salto de página automático y margen inferior

        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 8);

        // Encabezado
        $html = '<table border="0" cellpadding="1" style="font-size: 9px; padding:0px;text-align:center; ">
            <tbody>
            <tr>
                <td>
                    <strong>COMANDA</strong><br>
                    <span style="font-size: 12px;font-weight: bold;">' . $valorVenta . '</span>
                </td>
            </tr>
            <tbody>
        </table>
        <table >
            <thead>
            <tr>
             <th width="20%"></th>
             <th width="3%"></th>
             <th width="77%"></th>
            </tr>
            </thead>
          <tbody >
         
            <tr >
                <td width="20%"><strong>FECHA</strong></td>
                <td width="3%"><strong>:</strong></td>
                <td width="77%">' . $fecha . '</td>
            </tr>
            <tr >
                <td width="20%"><strong>NOTA</strong></td>
                <td width="3%"><strong>:</strong></td>
                <td width="77%">' . $nota . '</td>
            </tr>
            <tr><td colspan="2"></td></tr>
          </tbody>
        </table>';
        $pdf->writeHTML($html, false, false, false, false, '');

        // Productos
        $html = '<table border="0" cellpadding="1" style="width:100%; font-size: 7px; ">
            <tbody>
            <tr>
                <th style="border-top: 0.5px solid #000000; border-bottom: 0.5px solid  #000000; width:40%; text-align:left;font-weight: bold; ">PRODUCTO</th>
                <th style="border-top: 0.5px solid #000000; border-bottom: 0.5px solid  #000000; width:20%; text-align:center; font-weight: bold;">CANT.</th>
                <th style="border-top: 0.5px solid #000000; border-bottom: 0.5px solid  #000000; width:15%; text-align:right; font-weight: bold;">PRECIO</th>
                <th style="border-top: 0.5px solid #000000; border-bottom: 0.5px solid  #000000; width:25%; text-align:right; font-weight: bold;">SUBTOTAL</th>
            </tr>
             ';

        foreach ($productos as $item) {
            $valorUnitario = number_format($item["precio_venta"], 2);
            $precioTotal = number_format($item["subtotal"], 2);
            $html .= '
                <tr>
                    <td>' . $item["producto"] . '</td>
                    <td style="text-align:center;">' . $item["cantidad"] . '</td>
                    <td style="text-align:right;">' . $valorUnitario . '</td>
                    <td style="text-align:right;">' . $precioTotal . '</td>
                </tr>';
        }

        $html .= '
            <tr>
                <td colspan="2" width="65%" style="border-top: 0.5px solid #000000; text-align:right; font-size: 9px;"> <strong>TOTAL:</strong></td>
                <td colspan="2" width="35%" style="border-top: 0.5px solid #000000; text-align:right; font-size: 9px;"> Bs ' . $total . '</td>
            </tr>
            <tr>
                <td colspan="2" width="65%" style="text-align:right; font-size: 9px;"> <strong>PAGADO:</strong></td>
                <td colspan="2" width="35%" style="text-align:right; font-size: 9px;">Bs ' . $totalPagado . '</td>
            </tr>
            <tr>
                <td colspan="2" width="65%" style="text-align:right; font-size: 9px;"> <strong>CAMBIO:</strong></td>
                <td colspan="2" width="35%" style="text-align:right; font-size: 9px;">Bs ' . $cambio . '</td>
            </tr>
            </tbody>
        </table>';

        $pdf->writeHTML($html, false, false, false, false, '');

        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 8);

        // Encabezado
        $html = '<table border="0" cellpadding="1" style="font-size: 9px; padding:0px;text-align:center; ">
            <tbody>
            <tr>
                <td>
                    <strong>SISTEMA POS</strong><br>
                    <strong>Ticket de Venta: </strong>' . $valorVenta . '<br>
                    <strong>Fecha: </strong>' . $fecha . '
                </td>
            </tr>
            <tbody>
        </table>
        <table >
            <thead>
            <tr>
             <th width="25%"></th>
             <th width="3%"></th>
             <th width="72%"></th>
            </tr>
            </thead>
          <tbody >
            <tr>
                <td width="25%"><strong>MESERO/A</strong></td>
                <td width="3%"><strong>:</strong></td>
                <td width="72%">' . $respuestaCliente["nombre"] . '</td>
            </tr>
            <tr >
                <td width="25%"><strong>CAJERO/A</strong></td>
                <td width="3%"><strong>:</strong></td>
                <td width="72%">' . $respuestaVendedor["nombre"] . '</td>
            </tr>
            <tr >
                <td width="25%"><strong>VÍA PAGO</strong></td>
                <td width="3%"><strong>:</strong></td>
                <td width="72%">' . $tipoPago . '</td>
            </tr>
            <tr><td colspan="2"></td></tr>
          </tbody>
        </table>';
        $pdf->writeHTML($html, false, false, false, false, '');

        // Productos
        $html = '<table border="0" cellpadding="1" style="width:100%; font-size: 7px; ">
            <tbody>
            <tr>
                <th style="border-top: 0.5px solid #000000; border-bottom: 0.5px solid  #000000; width:40%; text-align:left;font-weight: bold; ">PRODUCTO</th>
                <th style="border-top: 0.5px solid #000000; border-bottom: 0.5px solid  #000000; width:20%; text-align:center; font-weight: bold;">CANT.</th>
                <th style="border-top: 0.5px solid #000000; border-bottom: 0.5px solid  #000000; width:15%; text-align:right; font-weight: bold;">PRECIO</th>
                <th style="border-top: 0.5px solid #000000; border-bottom: 0.5px solid  #000000; width:25%; text-align:right; font-weight: bold;">SUBTOTAL</th>
            </tr>
             ';

        foreach ($productos as $item) {
            $valorUnitario = number_format($item["precio_venta"], 2);
            $precioTotal = number_format($item["subtotal"], 2);
            $html .= '
                <tr>
                    <td>' . $item["producto"] . '</td>
                    <td style="text-align:center;">' . $item["cantidad"] . '</td>
                    <td style="text-align:right;">' . $valorUnitario . '</td>
                    <td style="text-align:right;">' . $precioTotal . '</td>
                </tr>';
        }

        $html .= '
            <tr>
                <td colspan="2" width="65%" style="border-top: 0.5px solid #000000; text-align:right; font-size: 9px;"> <strong>TOTAL:</strong></td>
                <td colspan="2" width="35%" style="border-top: 0.5px solid #000000; text-align:right; font-size: 9px;"> Bs ' . $total . '</td>
            </tr>
            <tr>
                <td colspan="2" width="65%" style="text-align:right; font-size: 9px;"> <strong>PAGADO:</strong></td>
                <td colspan="2" width="35%" style="text-align:right; font-size: 9px;">Bs ' . $totalPagado . '</td>
            </tr>
            <tr>
                <td colspan="2" width="65%" style="text-align:right; font-size: 9px;"> <strong>CAMBIO:</strong></td>
                <td colspan="2" width="35%" style="text-align:right; font-size: 9px;">Bs ' . $cambio . '</td>
            </tr>
            <tr><td colspan="4"></td></tr>
            <tr><td colspan="4"></td></tr>
            <tr>
                <td colspan="4"  style="text-align:center; font-size: 9px;">¡GRACIAS POR SU COMPRA!</td>
            </tr>
            </tbody>
        </table>';

        $pdf->writeHTML($html, false, false, false, false, '');

        // Generar el PDF
        $pdf->Output('factura.pdf', 'I');
    }
}

$factura = new imprimirFactura();
$factura->codigo = $_GET["codigo"];
$factura->traerImpresionFactura();
