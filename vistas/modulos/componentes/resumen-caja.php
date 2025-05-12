
<!-- Resumen de Movimientos de Caja -->
<h4 class="text-center"><strong>Resumen de Movimientos de Caja</strong></h4>
<table class="dt-responsive">
    <thead>
        <tr class="row-dark">
            <th>INGRESOS</th>
            <th class="text-right total-column" id="total_ingresos">0.00</th>
        </tr>
    </thead>
    <tbody>
        <!-- Ingresos -->
        <tr>
            <td class="cell-padded">Saldo Inicial en Caja</td>
            <td class="text-right cell-value total-column" id="monto_apertura">0.00</td>
        </tr>
        <tr>
            <td class="cell-padded">Ventas</td>
            <td class="text-right cell-value total-column" id="monto_ventas">0.00</td>
        </tr>

        <!-- Egresos -->
        <tr class="row-dark">
            <td>Egresos</td>
            <td class="text-right total-column" id="total_egresos">0.00</td>
        </tr>
        <tr>
            <td class="cell-padded">Gastos</td>
            <td class="text-right cell-value total-column" id="gastos_operativos">0.00</td>
        </tr>
        <tr>
            <td class="cell-padded">Compras</td>
            <td class="text-right cell-value total-column" id="monto_compras">0.00</td>
        </tr>
        <tr class="row-dark">
            <td>SALDO NETO</td>
            <td class="text-right total-column" id="resultado_neto">0.00</td>
        </tr>
    </tbody>
</table>

<!-- Comparación: Efectivo vs Sistema -->
<div class="summary-table">
    <h4 class="text-center" ><strong>Efectivo <span class="vs-text">VS</span> Sistema</strong></h4>
    <table class="dt-responsive" >
        <tr>
            <td class="text-right"  style="padding-top: 6px !important;">TOTAL MOVIMIENTO EN CAJA</td>
            <td class="text-right cell-value bold-value total-column" style="padding-top: 6px !important;" id="total_ganancia_perdida">0.00</td>
        </tr>
        <tr>
            <td class="cell-padded text-right text-bold">TOTAL EFECTIVO EN CAJA </td>
            <td class="text-right cell-value bold-value border-bottom total-column" id="efectivo_en_caja">0.00</td>
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