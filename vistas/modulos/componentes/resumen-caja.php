
<!-- Resumen de Movimientos de Caja -->
<h4 class="text-center"><strong>Cuadre de caja</strong></h4>
<p class="text-center text-muted" style="margin-top:-5px;margin-bottom:12px;font-size:12px;">
  Ingresos y egresos que afectan el arqueo
  <i class="fa fa-info-circle"
     data-toggle="tooltip"
     data-placement="bottom"
     title="Solo entra lo que suma o resta efectivo en caja. Lo demás va en Referencia."></i>
</p>

<table class="dt-responsive tabla-resumen-arbol">
    <tbody>
        <tr class="row-dark">
            <td>INGRESOS</td>
            <td class="text-right total-column" id="total_ingresos">0.00</td>
        </tr>

        <tr>
            <td class="tree-cell">
                <ul class="tree-view">
                    <li>Saldo inicial en caja</li>
                    <li>
                        Ventas
                        <ul>
                            <li>QR</li>
                            <li>Efectivo</li>
                        </ul>
                    </li>
                    <li>Otros ingresos</li>
                    <li class="text-muted">Descuentos (referencia)</li>
                </ul>
            </td>
            <td class="tree-values">
                <div id="monto_apertura" class="text-bold">0.00</div>
                <div id="monto_ventas" class="text-bold">0.00</div>
                <div id="monto_ventas_qr">0.00</div>
                <div id="monto_ventas_efectivo">0.00</div>
                <div id="otros_ingresos" class="text-bold">0.00</div>
                <div id="total_descuentos_ventas" class="text-muted">0.00</div>
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
                    <li>
                        Compras pagadas con caja
                        <i class="fa fa-info-circle text-muted"
                           data-toggle="tooltip"
                           title="Compras marcadas como Descontar de caja."></i>
                    </li>
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
<div class="summary-table" style="margin-top:15px;">
    <h4 class="text-center"><strong>Efectivo/QR <span class="vs-text">VS</span> Sistema</strong></h4>
    <table class="dt-responsive">
        <tr>
            <td class="text-right" style="padding-top: 6px !important;">Total en sistema (-)</td>
            <td class="text-right cell-value bold-value total-column" style="padding-top: 6px !important;" id="total_ganancia_perdida">0.00</td>
        </tr>
        <tr>
            <td class="cell-padded text-right">Total contado en caja (+)</td>
            <td class="text-right cell-value bold-value total-column border-bottom" id="total_efectivo_qr_en_caja">0.00</td>
        </tr>
        <tr class="row-dark">
            <td class="text-right">Diferencia</td>
            <td class="text-right total-column cell-padded" id="diferencia">0.00</td>
        </tr>
    </table>
</div>

<hr style="margin:18px 0;border-color:#eee;">

<!-- Referencia: no afecta cuadre -->
<h4 class="text-center"><strong>Referencia</strong></h4>
<p class="text-center text-muted" style="margin-top:-5px;margin-bottom:12px;font-size:12px;">
  No suma ni resta del arqueo
  <i class="fa fa-info-circle"
     data-toggle="tooltip"
     data-placement="bottom"
     title="Compras por otro medio y ventas aún no cobradas."></i>
</p>

<div class="summary-table" id="bloque_compras_informativo">
    <h5 class="text-center" style="margin-top:0;"><strong>Compras registradas</strong></h5>
    <table class="dt-responsive">
        <tr>
            <td>Total registrado:</td>
            <td class="text-right" id="monto_compras_informativo">Bs 0.00</td>
        </tr>
        <tr>
            <td>Pagadas con caja <small class="text-muted">(en egresos)</small>:</td>
            <td class="text-right" id="monto_compras_pagadas_caja">Bs 0.00</td>
        </tr>
        <tr>
            <td>Solo inventario <small class="text-muted">(otro medio)</small>:</td>
            <td class="text-right" id="monto_compras_solo_inventario">Bs 0.00</td>
        </tr>
    </table>
</div>

<div class="summary-table" id="bloque_cuentas_pendientes_actual" style="margin-top:15px;">
    <h5 class="text-center"><strong>Cuentas por cobrar</strong></h5>
    <p class="text-center text-muted" style="font-size:12px;margin-bottom:8px;">Ventas aún no cobradas</p>
    <table class="dt-responsive">
        <tr>
            <td>Cantidad:</td>
            <td class="text-right" id="cuentas_pendientes_cantidad">0</td>
        </tr>
        <tr>
            <td>Total por cobrar:</td>
            <td class="text-right" id="cuentas_pendientes_total">Bs 0.00</td>
        </tr>
    </table>
</div>

<div class="row" style="text-align: right; margin-top:10px; margin-right:2px;">
    <button type="button" onclick="arqueoCaja.imprimirMovimientosEnCaja()" id="imprimirMovimientos" class="btn btn-default btn-sm">
        <i class="fa fa-print"></i> Imprimir
    </button>
</div>
