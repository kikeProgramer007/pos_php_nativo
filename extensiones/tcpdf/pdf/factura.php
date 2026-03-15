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

class imprimirFactura
{
    public $codigo;

    public function pdfToBase64(TCPDF $pdf): string {
        return base64_encode($pdf->Output('', 'S')); // S = string
    }
    
    public function traerImpresionFactura()
    {

        // Obtener información de la venta
        $itemVenta = "id";
        $valorVenta = $this->codigo;

        $respuestaVenta = ControladorVentas::ctrMostrarVentas($itemVenta, $valorVenta);
        if($respuestaVenta!=null){
        $fecha = date('d-m-Y H:i:s a', strtotime($respuestaVenta["fecha"]));
        $fechaSolo = date('d-m-Y', strtotime($respuestaVenta["fecha"]));
        $horaSolo = date('H:i:s a', strtotime($respuestaVenta["fecha"]));
        $productos = ControladorVentas::ctrMostrarDetalleVentas($respuestaVenta['id']);

        $total = number_format($respuestaVenta["total"], 2);
        $cambio = number_format($respuestaVenta["cambio"], 2);
        $tipoPago = $respuestaVenta["tipo_pago"];
        $totalPagado = number_format($respuestaVenta["total_pagado"], 2);
        $notaGeneral = trim($respuestaVenta["nota"] ?? '');
        // Obtener información del cliente
        $itemCliente = "id";
        $valorCliente = $respuestaVenta["id_cliente"];
        $respuestaCliente = ControladorClientes::ctrMostrarClientesActivoInactivos($itemCliente, $valorCliente);

        // Obtener información del mesero
        
        $itemMesero = "id";
        $valorMesero = $respuestaVenta["id_mesero"];

        $respuestaMesero = ControladorMeseros::ctrMostrarMeserosActivoInactivo($itemMesero, $valorMesero);

        // Obtener información del vendedor
        $itemVendedor = "id";
        $valorVendedor = $respuestaVenta["id_vendedor"];
        $respuestaVendedor = ControladorUsuarios::ctrMostrarUsuariosActivoInactivo($itemVendedor, $valorVendedor);



        // Configuración del PDF para impresora térmica
        require_once('tcpdf_include.php');

        // Definir altura base (en mm) para encabezado y márgenes.
        $alturaBase = 80;
        // Definir altura por fila (puedes ajustarlo según el contenido).
        $alturaPorFila = 10;

        // Calcular la altura dinámica en base al número de filas
        $cantidadFilas = count($productos); // Obtener la cantidad de productos
        $alturaTotal = $alturaBase + ($alturaPorFila * $cantidadFilas); // Altura total

        // Crear el documento con la altura calculada
        $pdfFactura = new TCPDF('P', 'mm', array(72, $alturaTotal), true, 'UTF-8', false);

        $pdfFactura->SetMargins(1, 1, 0);
      
        $pdfFactura->setPrintHeader(false);
        $pdfFactura->setPrintFooter(false);
        $pdfFactura->SetAutoPageBreak(false, 0); // Desactivar el salto de página automático

        // Configuración adicional para caracteres especiales
        $pdfFactura->setFontSubsetting(true);

        $pdfFactura->AddPage();
        $pdfFactura->SetFont('helvetica', '', 8);

        // PRIMERO: Factura
        $htmlFactura = '<table border="0">
            <tbody>
            <tr>
                <td style="text-align:center;">
                    <span style="font-size: 10px;">POLLOS ROSSY</span><br>
                    <span style="font-size: 14px;"><strong>N° PEDIDO:' . ltrim($respuestaVenta["codigo"], '0') . '</strong></span><br>
                    <span style="font-size: 8px;">Fecha: ' . $fechaSolo . ' &nbsp;&nbsp; Hora: ' . $horaSolo . '</span>
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
            <tr >
                <td width="25%"><strong>CLIENTE</strong></td>
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

        $pdfFactura->writeHTML($htmlFactura, false, false, false, false, '');

        // Productos para la factura
        $htmlFactura = '<table border="0" cellpadding="0" style="width:100%; font-size: 7px; ">
            <tbody>
            <tr>
                <th style="width:41%; border-top: 0.5px solid #000000; border-bottom: 0.5px solid  #000000;  text-align:left;font-weight: bold; ">DETALLE</th>
                <th style="width:9%; border-top: 0.5px solid #000000; border-bottom: 0.5px solid  #000000; text-align:center; font-weight: bold;">F.A</th>
                <th style="width:8%; border-top: 0.5px solid #000000; border-bottom: 0.5px solid  #000000; text-align:center; font-weight: bold;">CNT</th>
                <th style="width:17%; border-top: 0.5px solid #000000; border-bottom: 0.5px solid  #000000;text-align:right; font-weight: bold;">PRECIO</th>
                <th style="width:23%; border-top: 0.5px solid #000000; border-bottom: 0.5px solid  #000000; text-align:right; font-weight: bold;">SUBTOTAL</th>
            </tr>
             ';

        foreach ($productos as $item) {
            $valorUnitario = number_format($item["precio_venta"], 2);
            $precioTotal = number_format($item["subtotal"], 2);
            $preferencias = $item['preferencias'] ?? '';
            $nota = $item['nota_adicional'] ?? '';
            
            // Eliminar emojis de las preferencias
            $preferencias = preg_replace('/[\x{1F300}-\x{1F9FF}]/u', '', $preferencias);
            $preferencias = preg_replace('/[❌✅]/u', '', $preferencias);
            $preferencias = trim($preferencias);
            
            $texto = implode(' - ', array_filter([$preferencias, $nota]));
            $preferenciasYNotaAdicional = $texto 
                ? '<br><span style="font-size: 9px; color: #666666;">(' . $texto . ')</span>' 
                : '';
          
            $htmlFactura .= '
                <tr>
                    <td style="font-size: 10px; padding: 3px 0;">' . $item["producto"] . $preferenciasYNotaAdicional . '</td>
                    <td style="text-align:center; font-size: 9px; padding: 3px 0;">' . $item["forma_atencion"] . '</td>
                    <td style="text-align:center; font-size: 9px; padding: 3px 0;">' . $item["cantidad"] . '</td>
                    <td style="text-align:right; font-size: 9px; padding: 3px 0;">' . $valorUnitario . '</td>
                    <td style="text-align:right; font-size: 9px; padding: 3px 0;">' . $precioTotal . '</td>
                </tr>';
        }

        $htmlFactura .= '
            <tr>
                <td colspan="3" style="border-top: 0.5px solid #000000; text-align:left; font-size: 9px;"> <strong>TOTAL:</strong></td>
                <td colspan="2" style="border-top: 0.5px solid #000000; text-align:right; font-size: 9px;"> Bs ' . $total . '</td>
            </tr>
            <tr>
                <td colspan="3" style="text-align:left; font-size: 9px;"> <strong>PAGADO:</strong></td>
                <td colspan="2" style="text-align:right; font-size: 9px;">Bs ' . $totalPagado . '</td>
            </tr>
            <tr>
                <td colspan="3" style="text-align:left; font-size: 9px;"> <strong>CAMBIO:</strong></td>
                <td colspan="2" style="text-align:right; font-size: 9px;">Bs ' . $cambio . '</td>
            </tr>
            </tbody>
        </table>
         <p style="font-size: 9px; text-align: center;">¡GRACIAS POR SU COMPRA!<br>SISTEMAS AL 73982982</p>
        ';

        $pdfFactura->writeHTML($htmlFactura, false, false, false, false, '');
        // Convertir el PDF de la factura a Base64
        $facturaBase64 = $this->pdfToBase64($pdfFactura);
        
        header('Content-Type: application/json');    
        echo json_encode([
            'success' => true,
            'facturaBase64' => $facturaBase64
        ]);
        
        exit;

        }else{
       
            echo'<script>
                    window.location = "../../../crear-venta";
                </script>'; 

        }
    }
}

$factura = new imprimirFactura();
$factura->codigo = $_GET["codigo"];
$factura->traerImpresionFactura();