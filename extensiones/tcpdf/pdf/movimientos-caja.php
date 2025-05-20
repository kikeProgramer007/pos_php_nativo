<?php

require_once "../../../controladores/ventas.controlador.php";
require_once "../../../modelos/ventas.modelo.php";
require_once "../../../controladores/arqueo.controlador.php";
require_once "../../../modelos/arqueo.modelo.php";

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

    public function traerMovimientoCaja()
    {

    
        //Obtener informacion del arqueo por idArqueo
        $arqueo = ControladorArqueo::ctrObtenerArqueoPorId($this->codigo);
        if($arqueo!=null){
        $fechaSolo = date('d-m-Y');
        $horaSolo = date('H:i:s a');


        // Obtener información del vendedor
        $id = "id";
        $idUsuario = $arqueo["id_usuario"];
        $respuestaVendedor = ControladorUsuarios::ctrMostrarUsuariosActivoInactivo($id, $idUsuario);
        // Calculamos totales antes de generar la tabla
        $totalBilletes = ($arqueo["Bs200"] * 200) 
                    + ($arqueo["Bs100"] * 100)
                    + ($arqueo["Bs50"] * 50)
                    + ($arqueo["Bs20"] * 20)
                    + ($arqueo["Bs10"] * 10);

        $totalMonedas = ($arqueo["Bs5"] * 5)
                    + ($arqueo["Bs2"] * 2)
                    + ($arqueo["Bs050"] * 0.5)
                    + ($arqueo["Bs020"] * 0.20);
   
        $totalGeneral = $totalBilletes + $totalMonedas;
    
       if($arqueo["total_egresos"]>0){
            $arqueo["gastos_operativos"] *= $arqueo["gastos_operativos"]>0? (-1): 1;
            $arqueo["monto_compras"] *= $arqueo["gastos_operativos"]>0? (-1) :1;
            $arqueo["total_egresos"] *= (-1);
       }

       $ResultadoMessage = "NO HAY DIFENCIAS";
        if($arqueo["resultado_neto"]>$arqueo["efectivo_en_caja"]){
            $ResultadoMessage = "FALTANTE";
        }else if($arqueo["resultado_neto"]<$arqueo["efectivo_en_caja"]){
            $ResultadoMessage = "SOBRANTE";
        }

        // Configuración del PDF para impresora térmica
        require_once('tcpdf_include.php');

        // Definir altura base (en mm) para encabezado y márgenes.
        $alturaBase = 230;
  

        // Crear el documento con la altura calculada
        $pdf = new TCPDF('P', 'mm', array(72, $alturaBase), true, 'UTF-8', false);

        $pdf->SetMargins(1, 1, 0);
      
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetAutoPageBreak(false, 0); // Desactivar el salto de página automático

        // Configuración adicional para caracteres especiales
        $pdf->setFontSubsetting(true);

        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 8);

        // PRIMERO: Factura
        $html = '<table border="0">
            <tbody>
            <tr>
                <td style="text-align:center;">
                    <span style="font-size: 10px;">POLLOS ROSSY</span><br>
                    <span style="font-size: 14px;"><strong>ARQUEO DE CAJA</strong></span><br>
                    <span style="font-size: 8px;">Fecha: ' . $fechaSolo . ' &nbsp;&nbsp; Hora: ' . $horaSolo . '</span>
                </td>
            </tr>
            <tbody>
        </table>
        <br><br>
     ';

        $pdf->writeHTML($html, false, false, false, false, '');

        // Productos para la factura
        $html = '<table border="0" cellpadding="0" style="width:100%;  font-size: 9px;">
            <tbody>
            <tr>
                <th colspan="3" style="width:98%; border-top: 0.5px solid #000000; border-bottom: 0.5px solid  #000000;  text-align:center;font-weight: bold; ">INFORMACIÓN</th>
            </tr>
             ';

       
        $html .= '
            <tr><td colspan="3"></td></tr>
            <tr >
                <td width="42%"><strong>RESPONSABLE</strong></td>
                <td style="width:3%;"><strong>:</strong></td>
                <td width="53%">' . strtoupper($respuestaVendedor["nombre"]) . '</td>
            </tr>

            <tr>
                <td style="width:42%;"><strong>ESTADO</strong></td>
                <td style="width:3%;"><strong>:</strong></td>
                <td style="width:53%;">CAJA ' . strtoupper($arqueo["estado"]) . '</td>
            </tr>
            <tr>
                <td style="width:42%;"><strong>F. APERTURA</strong></td>
                <td style="width:3%;"><strong>:</strong></td>
                <td style="width:53%;">' . $arqueo["fecha_apertura"] . '</td>
            </tr>
            <tr>
                <td style="width:42%;"><strong>F. CIERRE</strong></td>
                <td style="width:3%;"><strong>:</strong></td>
                <td style="width:53%;">' . $arqueo["fecha_cierre"] . '</td>
            </tr>
            <tr  border="1">
                <td style="width:42%;"><strong>ULTIMO Nº TICKET</strong></td>
                <td style="width:3%;"><strong>:</strong></td>
                <td style="width:53%;">' . $arqueo["nroTicket"] . '</td>
            </tr>
            <tr> <td ></td> </tr> 
            <tr>
                <td colspan="3" style="border-top: 0.5px solid #000000; border-bottom: 0.5px solid  #000000;  text-align:center;font-weight: bold; ">DESGLOSE</td>
            </tr>
            <tr><td colspan="3"></td></tr>
            <tr>
                <td style="width:70%; text-align:left;"><strong>BILLETES:</strong></td>
                <td style="width:28%; text-align:right;"><strong></strong></td>
            </tr>
            <tr>
                <td style="text-align:left; "> '.$arqueo["Bs200"].' BILLETES DE BS. 200</td>
                <td style="text-align:right; ">' . number_format($arqueo["Bs200"] * 200, 2) . '</td>
            </tr>
             <tr>
                <td style="text-align:left; "> '.$arqueo["Bs100"].' BILLETES DE BS. 100:</td>
                <td style="text-align:right; ">' . number_format($arqueo["Bs100"] * 100, 2) . '</td>
            </tr>
            <tr>
                <td style="text-align:left; "> '.$arqueo["Bs50"].' BILLETES DE BS. 50:</td>
                <td style="text-align:right; ">' .number_format($arqueo["Bs50"] * 50, 2). '</td>
            </tr>
            <tr>
                <td style="text-align:left; "> '.$arqueo["Bs20"].' BILLETES DE BS. 20:</td>
                <td style="text-align:right; ">' .number_format($arqueo["Bs20"] * 20, 2). '</td>
            </tr>
            <tr>
                <td style="text-align:left; "> '.$arqueo["Bs10"].' BILLETES DE BS. 10:</td>
                <td style="text-align:right; ">' .number_format($arqueo["Bs10"] * 10, 2). '</td>
            </tr>
            <tr>
                <td style="width:70%; text-align:left;"><strong> TOTAL BILLETES:</strong></td>
                <td style="width:28%; text-align:right;text-align:right; border-top: 0.5px solid #000000;"><strong>' . number_format($totalBilletes, 2) . '</strong></td>
            </tr>
        
            <tr >
                <td colspan="2" style="padding: 0; margin: 0; height: 4px; line-height: 4px;">&nbsp;</td>
            </tr>
            <tr>
                <td style="width:70%; text-align:left;"><strong>MONEDAS:</strong></td>
                <td style="width:28%; text-align:right;"><strong></strong></td>
            </tr>
            <tr>
                <td style="text-align:left; "> ' . $arqueo["Bs5"] . ' MONEDAS DE BS. 5:</td>
                <td style="text-align:right; ">' .number_format($arqueo["Bs5"] * 5, 2). '</td>
            </tr>
            <tr>
                <td style="text-align:left; "> ' . $arqueo["Bs2"] . ' MONEDAS DE BS. 2:</td>
                <td style="text-align:right; ">' .number_format($arqueo["Bs2"] * 2, 2). '</td>
            </tr>
             <tr>
                <td style="text-align:left; "> ' . $arqueo["Bs050"] . ' MONEDAS DE BS. 0.50:</td>
                <td style="text-align:right; ">' .number_format($arqueo["Bs050"] * 0.5, 2). '</td>
            </tr>
            <tr>
                <td style="text-align:left; "> ' . $arqueo["Bs020"] . ' MONEDAS DE BS. 0.20:</td>
                <td style="text-align:right; ">' .number_format($arqueo["Bs020"] * 0.20, 2). '</td>
            </tr>
            <tr>
                <td style="width:70%; text-align:left;"><strong> TOTAL MONEDAS:</strong></td>
                <td style="width:28%; text-align:right; border-top: 0.5px solid #000000;"><strong>' . number_format($totalMonedas, 2) . '</strong></td>
            </tr>

            <tr> <td colspan="2" ></td> </tr> 
            <tr>
                <td style="width:70%; text-align:left;"><strong>DINERO EN LA CAJA:</strong></td>
                <td style="width:28%; text-align:right;"><strong>' . number_format($totalGeneral, 2) . '</strong></td>
            </tr>
           <tr> <td colspan="2" ></td> </tr> 
            <tr>
                <td colspan="2" style="border-top: 0.5px solid #000000; border-bottom: 0.5px solid  #000000;  text-align:center;font-weight: bold; ">MOVIMIENTOS</td>
            </tr>
            <tr><td colspan="2"></td></tr>
            <tr>
                <td style="width:70%; text-align:left;"><strong>INGRESOS</strong></td>
                <td style="width:28%; text-align:right;"><strong></strong></td>
            </tr>
             <tr>
                <td style="text-align:left; "> SALDO INICIAL EN CAJA:</td>
                <td style="text-align:right; ">' . $arqueo["monto_apertura"] . '</td>
            </tr>
            <tr>
                <td style="text-align:left; "> VENTAS:</td>
                <td style="text-align:right; ">' . $arqueo["monto_ventas"] . '</td>
            </tr>
            <tr>
                <td style="width:70%; text-align:left;"><strong>TOTAL INGRESOS:</strong></td>
                <td style="width:28%; text-align:right;text-align:right; border-top: 0.5px solid #000000;"><strong>' . $arqueo["total_ingresos"] . '</strong></td>
            </tr>
            <tr><td colspan="2"></td></tr>
            <tr>
                <td style="text-align:left;"><strong>EGRESOS</strong></td>
                <td style="text-align:right; "><strong></strong></td>
            </tr>
              <tr>
                <td style="text-align:left; "> GASTO:</td>
                <td style="text-align:right; ">' . number_format($arqueo["gastos_operativos"],2) . '</td>
            </tr>
              <tr>
                <td style="text-align:left;"> COMPRAS:</td>
                <td style="text-align:right; ">' . number_format($arqueo["monto_compras"],2) . '</td>
            </tr>
            <tr>
                <td style="width:70%; text-align:left;"><strong>TOTAL EGRESOS:</strong></td>
                <td style="width:28%; text-align:right;text-align:right; border-top: 0.5px solid #000000;"><strong>' . number_format($arqueo["total_egresos"],2) . '</strong></td>
            </tr>
            <tr><td colspan="2"></td></tr>
            <tr>
                <td style="text-align:left; "><strong>DINERO EN EL SISTEMA:</strong></td>
                <td style="text-align:right; "><strong>' . $arqueo["resultado_neto"] . '</strong></td>
            </tr>
               
           <tr><td colspan="2"></td></tr>
           <tr>
                <td colspan="2" style="border-top: 0.5px solid #000000; border-bottom: 0.5px solid  #000000;  text-align:center;font-weight: bold; ">CUADRE DE CAJA</td>
           </tr>
           <tr><td colspan="2"></td></tr>
            <tr>
                <td style="text-align:left; ">DINERO EN EL SISTEMA:</td>
                <td style="text-align:right; ">' . $arqueo["resultado_neto"] . '</td>
            </tr>
            <tr>
                <td style="text-align:left;">DINERO EN LA CAJA:</td>
                <td style="text-align:right;">' . $arqueo["efectivo_en_caja"] . '</td>
            </tr>
            <tr>
                <td style="text-align:left;"><strong>'.$ResultadoMessage.':</strong></td>
                <td style="border-top: 0.5px solid #000000; text-align:right;"><strong>' . number_format($arqueo["diferencia"],2) . '</strong></td>
            </tr>
            </tbody>
        </table>
        <br><br><br><br><br><br>
        <p style="text-align: center;">¡FIRMA Y SELLO!</p>
        ';

        $pdf->writeHTML($html, false, false, false, false, '');


        // Generar el PDF
        $pdf->Output('MovimientoEnCaja.pdf', 'I');
        }else{
       
            echo'<script>
                    window.location = "../../../arqueo-de-caja";
                </script>'; 

        }
    }
}

$factura = new imprimirFactura();
$factura->codigo = $_GET["codigo"];
$factura->traerMovimientoCaja();
