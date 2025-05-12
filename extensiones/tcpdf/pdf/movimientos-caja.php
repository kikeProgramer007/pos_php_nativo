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

   

        // Configuración del PDF para impresora térmica
        require_once('tcpdf_include.php');

        // Definir altura base (en mm) para encabezado y márgenes.
        $alturaBase = 130;
  

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
                <th colspan="3" style="width:98%; border-top: 0.5px solid #000000; border-bottom: 0.5px solid  #000000;  text-align:center;font-weight: bold; ">INFORMACION</th>
            </tr>
             ';

       
        $html .= '
            <tr><td colspan="3"></td></tr>
            <tr >
                <td width="42%"><strong>CAJERO/A</strong></td>
                <td style="width:3%;"><strong>:</strong></td>
                <td width="53%">' . strtoupper($respuestaVendedor["nombre"]) . '</td>
            </tr>

            <tr>
                <td style="width:42%;"><strong>ESTADO</strong></td>
                <td style="width:3%;"><strong>:</strong></td>
                <td style="width:53%;">' . strtoupper($arqueo["estado"])  . '</td>
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
           <tr> <td colspan="3" ></td> </tr> 
            <tr>
                <td colspan="3" style="border-top: 0.5px solid #000000; border-bottom: 0.5px solid  #000000;  text-align:center;font-weight: bold; ">MOVIMIENTOS</td>
            </tr>
            <tr><td colspan="3"></td></tr>
            <tr>
                <td style="width:70%; text-align:left;"><strong>INGRESOS:</strong></td>
                <td style="width:28%; text-align:right;"><strong>' . $arqueo["total_ingresos"] . '</strong></td>
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
                <td style="text-align:left;"><strong>EGRESOS:</strong></td>
                <td style="text-align:right; "><strong>' . $arqueo["total_egresos"] . '</strong></td>
            </tr>
              <tr>
                <td style="text-align:left; "> GASTO:</td>
                <td style="text-align:right; ">' . $arqueo["gastos_operativos"] . '</td>
            </tr>
              <tr>
                <td style="text-align:left;"> COMPRAS:</td>
                <td style="text-align:right; ">' . $arqueo["monto_compras"] . '</td>
            </tr>
            <tr>
                <td style="text-align:left; "><strong>SALDO NETO:</strong></td>
                <td style="text-align:right; "><strong>' . $arqueo["resultado_neto"] . '</strong></td>
            </tr>
               
           <tr><td colspan="2"></td></tr>
           <tr>
                <td colspan="2" style="border-top: 0.5px solid #000000; border-bottom: 0.5px solid  #000000;  text-align:center;font-weight: bold; ">CUADRE DE CAJA</td>
           </tr>
           <tr><td colspan="2"></td></tr>
            <tr>
                <td style="text-align:left; ">TOTAL MOVIMIENTO EN CAJA:</td>
                <td style="text-align:right; ">' . $arqueo["resultado_neto"] . '</td>
            </tr>
            <tr>
                <td style="text-align:left;">TOTAL EFECTIVO EN CAJA:</td>
                <td style="text-align:right;">' . $arqueo["efectivo_en_caja"] . '</td>
            </tr>
            <tr>
                <td style="text-align:left;"><strong>DIFERENCIA:</strong></td>
                <td style="border-top: 0.5px solid #000000; text-align:right;"><strong>' . $arqueo["diferencia"] . '</strong></td>
            </tr>
            </tbody>
        </table>
        <br><br>
        <br>
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
