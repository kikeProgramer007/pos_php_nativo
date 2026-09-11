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
require_once "../../../modelos/otros_ingresos.modelo.php";

class imprimirFactura
{

    public $codigo;

    private function formatearFechaHora($valor)
    {
        if (empty($valor)) {
            return '';
        }

        $fecha = date_create($valor);
        if ($fecha !== false) {
            return $fecha->format('d-m-Y H:i a');
        }

        return $valor;
    }

    public function traerMovimientoCaja()
    {

    
        //Obtener informacion del arqueo por idArqueo
        $arqueo = ControladorArqueo::ctrObtenerArqueoPorId($this->codigo);
        if($arqueo!=null){
        $fechaSolo = date('d-m-Y');
        $horaSolo = date('H:i a');


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
                    + ($arqueo["Bs1"] * 1)
                    + ($arqueo["Bs050"] * 0.5)
                    + ($arqueo["Bs020"] * 0.20);
   
        $totalGeneral = $totalBilletes + $totalMonedas;

        // Recalcular montos desde tablas fuente (compras/gastos/ventas pagadas)
        $arqueoSincronizado = ModeloArqueo::mdlSincronizarMontosArqueo($arqueo["id"]);
        if ($arqueoSincronizado) {
            $arqueo = array_merge($arqueo, $arqueoSincronizado);
        }

        // Egresos se muestran como valores positivos; la resta solo aplica al saldo neto
        $gastosOperativos = abs(floatval($arqueo["gastos_operativos"] ?? 0));
        $gastosEfectivo = abs(floatval($arqueo["gastos_efectivo"] ?? ModeloArqueo::mdlSumarGastosEfectivoPorArqueo($arqueo["id"])));
        $gastosQr = abs(floatval($arqueo["gastos_qr"] ?? ModeloArqueo::mdlSumarGastosQrPorArqueo($arqueo["id"])));
        $montoCompras = abs(floatval($arqueo["monto_compras"] ?? 0));
        $comprasInformativo = floatval($arqueo["monto_compras_informativo"] ?? ModeloArqueo::mdlSumarComprasInformativasPorArqueo($arqueo["id"]));
        $totalEgresos = abs(floatval($arqueo["total_egresos"] ?? ($gastosOperativos + $montoCompras)));
        $resultadoNeto = floatval($arqueo["resultado_neto"] ?? 0);
        $totalOtrosIngresos = floatval($arqueo["otros_ingresos"] ?? 0);
        $detalleOtrosIngresos = [];
        try {
            $detalleOtrosIngresos = ModeloOtrosIngresos::mdlMostrarPorArqueo($arqueo["id"]);
            $totalOtrosIngresos = ModeloOtrosIngresos::mdlSumarPorArqueo($arqueo["id"]);
        } catch (Exception $e) {
            $detalleOtrosIngresos = [];
        }

       $ResultadoMessage = "TODO CUADRA";
        $dineroEnCaja = floatval($arqueo["total_efectivo_qr_en_caja"] ?? 0);
        if($resultadoNeto > $dineroEnCaja){
            $ResultadoMessage = "TE FALTA DINERO";
        }else if($resultadoNeto < $dineroEnCaja){
            $ResultadoMessage = "TE SOBRA DINERO";
        }

        // Altura del ticket según filas (evita corte al agregar secciones)
        $filasFijas = 86;
        $filasDetalleOtros = 0;
        if (is_array($detalleOtrosIngresos) && count($detalleOtrosIngresos) > 1 && count($detalleOtrosIngresos) <= 6) {
            $filasDetalleOtros = count($detalleOtrosIngresos);
        }
        $alturaPorFila = 4.2;
        $alturaMargenExtra = 55;
        $alturaTotal = $alturaMargenExtra + (($filasFijas + $filasDetalleOtros) * $alturaPorFila);
        $alturaTotal = max(400, ceil($alturaTotal));

        // Configuración del PDF para impresora térmica
        require_once('tcpdf_include.php');

        // Crear el documento con la altura calculada
        $pdf = new TCPDF('P', 'mm', array(72, $alturaTotal), true, 'UTF-8', false);

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
                    <span style="font-size: 10px;">Pollos 360 </span><br>
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
                <td style="width:53%;">' . $this->formatearFechaHora($arqueo["fecha_apertura"] ?? '') . '</td>
            </tr>
            <tr>
                <td style="width:42%;"><strong>F. CIERRE</strong></td>
                <td style="width:3%;"><strong>:</strong></td>
                <td style="width:53%;">' . $this->formatearFechaHora($arqueo["fecha_cierre"] ?? '') . '</td>
            </tr>
            <tr  border="1">
                <td style="width:42%;"><strong>ULTIMO Nº TICKET</strong></td>
                <td style="width:3%;"><strong>:</strong></td>
                <td style="width:53%;">' . $arqueo["nroTicket"] . '</td>
            </tr>
            <tr> <td ></td> </tr> 
           
            <tr>
                <td colspan="3" style="border-top: 0.5px solid #000000; border-bottom: 0.5px solid  #000000;  text-align:center;font-weight: bold; ">MOVIMIENTOS EN SISTEMA</td>
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
                <td style="text-align:left; "> <strong>VENTAS</strong></td>
                <td style="text-align:right; "></td>
            </tr>

            <tr>
                <td style="text-align:left; ">&nbsp;&nbsp;&nbsp; QR:</td>
                <td style="text-align:right; ">' . $arqueo["monto_ventas_qr"] . '</td>
            </tr>
            <tr>
                <td style="text-align:left; ">&nbsp;&nbsp;&nbsp; EFECTIVO:</td>
                <td style="text-align:right; ">' . $arqueo["monto_ventas_efectivo"] . '</td>
            </tr>
            <tr>
                <td style="text-align:left; "> OTROS INGRESOS:</td>
                <td style="text-align:right; ">' . number_format($totalOtrosIngresos, 2) . '</td>
            </tr>';

        if (is_array($detalleOtrosIngresos) && count($detalleOtrosIngresos) > 1 && count($detalleOtrosIngresos) <= 6) {
            foreach ($detalleOtrosIngresos as $otroIngresoItem) {
                $descTicket = $otroIngresoItem["descripcion"] ?? "";
                if (function_exists("mb_substr") && function_exists("mb_strlen")) {
                    if (mb_strlen($descTicket) > 22) {
                        $descTicket = mb_substr($descTicket, 0, 22) . "...";
                    }
                } elseif (strlen($descTicket) > 22) {
                    $descTicket = substr($descTicket, 0, 22) . "...";
                }
                $tipoEntrada = strtoupper($otroIngresoItem["tipo_entrada"] ?? "EFECTIVO");
                $html .= '
            <tr>
                <td style="text-align:left; ">&nbsp;&nbsp;&nbsp; [' . htmlspecialchars($tipoEntrada) . '] ' . htmlspecialchars($descTicket) . '</td>
                <td style="text-align:right; ">' . number_format(floatval($otroIngresoItem["monto"] ?? 0), 2) . '</td>
            </tr>';
            }
        }

        $html .= '
            <tr>
                <td style="text-align:left; "> DESCUENTOS (REF.):</td>
                <td style="text-align:right; ">' . number_format(floatval($arqueo["total_descuentos_ventas"] ?? 0), 2) . '</td>
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
                <td style="text-align:left; "> GASTOS:</td>
                <td style="text-align:right; ">' . number_format($gastosOperativos,2) . '</td>
            </tr>
              <tr>
                <td style="text-align:left;font-size:7px;">&nbsp;&nbsp;&nbsp; QR:</td>
                <td style="text-align:right; ">' . number_format($gastosQr,2) . '</td>
            </tr>
              <tr>
                <td style="text-align:left;font-size:7px;">&nbsp;&nbsp;&nbsp; EFECTIVO:</td>
                <td style="text-align:right; ">' . number_format($gastosEfectivo,2) . '</td>
            </tr>
              <tr>
                <td style="text-align:left;"> COMPRAS PAGADAS CON CAJA:</td>
                <td style="text-align:right; ">' . number_format($montoCompras,2) . '</td>
            </tr>
            <tr>
                <td style="width:70%; text-align:left;"><strong>TOTAL EGRESOS:</strong></td>
                <td style="width:28%; text-align:right;text-align:right; border-top: 0.5px solid #000000;"><strong>' . number_format($totalEgresos,2) . '</strong></td>
            </tr>
            <tr><td colspan="2"></td></tr>
            <tr>
                <td style="text-align:left; "><strong>SALDO NETO:</strong></td>
                <td style="text-align:right; "><strong>' . number_format($resultadoNeto, 2) . '</strong></td>
            </tr>
             <tr> <td colspan="2" ></td> </tr>';

        $comprasSoloInventario = max(0, round($comprasInformativo - $montoCompras, 2));

        if (($arqueo["estado"] ?? "") === "abierta") {
            $resumenPendientes = ControladorVentas::ctrResumenCuentasPendientes($arqueo["id"]);
            $cantidadPendientes = intval($resumenPendientes["cantidad"] ?? 0);
            $totalPendientes = number_format(floatval($resumenPendientes["total_por_cobrar"] ?? 0), 2);
        } elseif (isset($arqueo["cuentas_pendientes_cantidad"]) || isset($arqueo["cuentas_pendientes_total"])) {
            $cantidadPendientes = intval($arqueo["cuentas_pendientes_cantidad"] ?? 0);
            $totalPendientes = number_format(floatval($arqueo["cuentas_pendientes_total"] ?? 0), 2);
        } else {
            $cantidadPendientes = 0;
            $totalPendientes = "0.00";
        }

        $html .= '
                <tr>
                <td colspan="2" style="border-top: 0.5px solid #000000; border-bottom: 0.5px solid  #000000;  text-align:center;font-weight: bold; ">DESGLOSE DEL CIERRE DE CAJA</td>
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
                <td style="text-align:left; "> ' . $arqueo["Bs1"] . ' MONEDAS DE BS. 1:</td>
                <td style="text-align:right; ">' .number_format($arqueo["Bs1"] * 1, 2). '</td>
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
                <td style="width:70%; text-align:left;">EFECTIVO:</td>
                <td style="width:28%; text-align:right;"><strong>' . number_format($totalGeneral, 2) . '</strong></td>
            </tr>
            <tr>
                <td style="text-align:left;">QR:</td>
                <td style="text-align:right;"><strong>' . $arqueo["qr_en_caja"] . '</strong></td>
            </tr>
            <tr>
                <td style="text-align:left;"><strong>DINERO EN CAJA:</strong></td>
                <td style="text-align:right;border-top: 0.5px solid #000000;"><strong>' . $arqueo["total_efectivo_qr_en_caja"] . '</strong></td>
            </tr>

           <tr> <td colspan="2" ></td> </tr> 
         
           <tr>
                <td colspan="2" style="border-top: 0.5px solid #000000; border-bottom: 0.5px solid  #000000;  text-align:center;font-weight: bold; ">CUADRE DE CAJA</td>
           </tr>
           <tr><td colspan="2"></td></tr>
            <tr>
                <td style="text-align:left; ">MOVIMIENTOS EN  SISTEMA: </td>
                <td style="text-align:right; ">' . number_format($resultadoNeto, 2) . '</td>
            </tr>
            
      
            <tr>
                <td style="text-align:left;">DINERO EN CAJA:</td>
                <td style="text-align:right;">' . $arqueo["total_efectivo_qr_en_caja"] . '</td>
            </tr>

            <tr>
                <td style="text-align:left;"><strong>'.$ResultadoMessage.':</strong></td>
                <td style="border-top: 0.5px solid #000000; text-align:right;"><strong>' . number_format($arqueo["diferencia"],2) . '</strong></td>
            </tr>
            <tr> <td colspan="2" ></td> </tr>
            <tr>
                <td colspan="2" style="border-top: 0.5px solid #000000; border-bottom: 0.5px solid #000000; text-align:center;font-weight: bold;">REFERENCIA</td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:center;font-size:7px;">Solo informativo — no afecta el cuadre</td>
            </tr>
            <tr><td colspan="2"></td></tr>
            <tr>
                <td style="width:70%; text-align:left;"><strong>COMPRAS</strong></td>
                <td style="width:28%; text-align:right;"></td>
            </tr>
            <tr>
                <td style="text-align:left;">&nbsp;&nbsp;&nbsp; Total registrado:</td>
                <td style="text-align:right;">' . number_format($comprasInformativo, 2) . '</td>
            </tr>
            <tr style="display:none;">
                <td style="text-align:left;">&nbsp;&nbsp;&nbsp; Pagadas con caja:</td>
                <td style="text-align:right;">' . number_format($montoCompras, 2) . '</td>
            </tr>
            <tr style="display:none;">
                <td style="text-align:left;font-size:7px;">&nbsp;&nbsp;&nbsp; (ya en egresos)</td>
                <td style="text-align:right;"></td>
            </tr>
            <tr style="display:none;">
                <td style="text-align:left;">&nbsp;&nbsp;&nbsp; Otro medio:</td>
                <td style="text-align:right;">' . number_format($comprasSoloInventario, 2) . '</td>
            </tr>
            <tr style="display:none;">
                <td style="text-align:left;font-size:7px;">&nbsp;&nbsp;&nbsp; (bolsillo u otro medio)</td>
                <td style="text-align:right;"></td>
            </tr>
            <tr><td colspan="2"></td></tr>
            <tr>
                <td style="width:70%; text-align:left;"><strong>CUENTAS POR COBRAR</strong></td>
                <td style="width:28%; text-align:right;"></td>
            </tr>
            <tr>
                <td style="text-align:left;font-size:7px;">&nbsp;&nbsp;&nbsp; Ventas aun no cobradas</td>
                <td style="text-align:right;"></td>
            </tr>
            <tr>
                <td style="text-align:left;">&nbsp;&nbsp;&nbsp; Cantidad:</td>
                <td style="text-align:right;">' . $cantidadPendientes . '</td>
            </tr>
            <tr>
                <td style="text-align:left;">&nbsp;&nbsp;&nbsp; Total por cobrar:</td>
                <td style="text-align:right;"><strong>' . $totalPendientes . '</strong></td>
            </tr>
            </tbody>
        </table>
        <table border="0" cellpadding="0" style="width:100%; font-size: 9px;">
            <tbody>
            <tr>
                <td style="height:22mm;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align:center;">
                    <strong>FIRMA Y SELLO</strong>
                </td>
            </tr>
            </tbody>
        </table>
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
