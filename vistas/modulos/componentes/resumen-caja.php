
<!-- Resumen de Movimientos de Caja -->
<h4 class="text-center"><strong>Resumen de Movimientos de Caja</strong></h4>



<table class="dt-responsive tabla-resumen-arbol">
    <tbody>
        <tr class="row-dark">
            <td>INGRESOS</td>
            <td class="text-right total-column" id="total_ingresos">0.00</td>
        </tr>

        <tr>
            <td class="tree-cell">
                <ul class="tree-view">
                    <li>Saldo Inicial en Caja</li>
                    <li>
                        Ventas
                        <ul>
                            <li>Qr</li>
                            <li>Efectivo</li>
                        </ul>
                    </li>
                </ul>
            </td>
            <td class="tree-values">
                <div id="monto_apertura" class="text-bold">0.00</div>
                <div id="monto_ventas" class="text-bold">0.00</div>
                <div id="monto_ventas_qr">0.00</div>
                <div id="monto_ventas_efectivo">0.00</div>
            </td>
        </tr>

        <tr class="row-dark">
            <td>EGRESOS</td>
            <td class="text-right total-column" id="total_egresos">0.00</td>
        </tr>

        <tr>
            <td class="tree-cell">
                <ul class="tree-view">
                    <li>Gastos</li>
                    <li>Compras</li>
                </ul>
            </td>
            <td class="tree-values">
                <div id="gastos_operativos" class="text-bold">0.00</div>
                <div id="monto_compras" class="text-bold">0.00</div>
            </td>
        </tr>

        <tr class="row-dark">
            <td>SALDO NETO</td>
            <td class="text-right total-column" id="resultado_neto">0.00</td>
        </tr>
    </tbody>
</table>

<!-- Comparación: Efectivo vs Sistema -->
<div class="summary-table">
    <h4 class="text-center" ><strong>Efectivo/QR <span class="vs-text">VS</span> Sistema</strong></h4>
    <table class="dt-responsive" >
        <tr>
            <td class="text-right"  style="padding-top: 6px !important;">TOTAL MOVIMIENTO EN CAJA (-)</td>
            <td class="text-right cell-value bold-value total-column" style="padding-top: 6px !important;" id="total_ganancia_perdida">0.00</td>
        </tr>
        <tr>
            <td class="cell-padded text-right  ">TOTAL <span class="text-bold">EFECTIVO/QR</span> EN CAJA (+)</td>
            <td class="text-right cell-value bold-value total-column border-bottom" id="total_efectivo_qr_en_caja">0.00</td>
        </tr>

        <tr class="row-dark">
            <td class="text-right">DIFERENCIA</td>
            <td class="text-right total-column cell-padded" id="diferencia">0.00</td>
        </tr>
    </table>
</div>
<!-- Botones -->

    <div class="row" style="text-align: right; margin-top:10px; margin-right:2px;">
            <button type="button"  onclick="arqueoCaja.imprimirMovimientosEnCaja()" id="imprimirMovimientos" class="btn btn-default btn-sm" >  <i class="fa fa-print"></i> Imprimir</button>
    </div>
