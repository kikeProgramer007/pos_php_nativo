<?php
/**
 * Componente de tabla de efectivo
 * Muestra los campos para ingresar las cantidades de billetes y monedas
 */
?>
<div class="tabla-efectivo">
    <div style="float:left;">
        <table class="table-bordered dt-responsive text-uppercase no-footer dtr-inline">
            <thead>
                <tr>
                    <th colspan="3" class="text-center">
                        <h4><strong>EFECTIVO EN CAJA</strong></h4>
                    </th>
                </tr>
                <tr>
                    <th width="30%">Efectivo</th>
                    <th>Cantidad</th>
                    <th width="20%" class="text-right">SubTotal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $denominaciones = [
                    ['valor' => 200, 'etiqueta' => '200 Bs'],
                    ['valor' => 100, 'etiqueta' => '100 Bs'],
                    ['valor' => 50, 'etiqueta' => '50 Bs'],
                    ['valor' => 20, 'etiqueta' => '20 Bs'],
                    ['valor' => 10, 'etiqueta' => '10 Bs'],
                    ['valor' => 5, 'etiqueta' => '5 Bs'],
                    ['valor' => 2, 'etiqueta' => '2 Bs'],
                    ['valor' => 1, 'etiqueta' => '1 Bs'],
                    ['valor' => 0.50, 'etiqueta' => '0.50 Bs'],
                    ['valor' => 0.20, 'etiqueta' => '0.20 Bs']
                ];

                foreach ($denominaciones as $denominacion) {
                    $valor = $denominacion['valor'];
                    $etiqueta = $denominacion['etiqueta'];
                    $id = str_replace('.', '', $valor);
                    
                    echo "<tr>
                        <td>{$etiqueta}</td>
                        <td>
                            <input type='number' 
                                   class='form-control input-sm cantidad-input' 
                                   id='cantidad_{$id}' 
                                   name='cantidad_{$id}' 
                                   data-valor='{$valor}'
                                   pattern='\\d*'
                                   min='0'
                                   inputmode='numeric'
                                   step='1'>
                        </td>
                        <td class='text-right'>
                            <span id='Total_{$id}bs'>0.00</span>
                        </td>
                    </tr>";
                }
                ?>
                <tr>
                    <td colspan="3" style="height: 5px;"></td>
                </tr>
                <tfoot style="font-weight: bold; font-size: 16px;">
                    <tr style="border-top: 2px solid #333;">
                        <td colspan="2">Total Efectivo en Caja</td>
                        <td class="text-right">
                            <p id="total_efectivo_en_caja_tabla">0.00</p>
                        </td>
                    </tr>
                </tfoot>
            </tbody>
        </table>
        <input type="hidden" id="total_efectivo_en_caja" name="total_efectivo_en_caja" value="0">
    </div>
</div> 