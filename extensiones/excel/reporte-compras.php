<?php

require_once __DIR__ . '/_bootstrap.php';
require_once __DIR__ . '/../../controladores/compras.controlador.php';
require_once __DIR__ . '/../../modelos/compras.modelo.php';
require_once __DIR__ . '/../../controladores/usuarios.controlador.php';
require_once __DIR__ . '/../../modelos/usuarios.modelo.php';

date_default_timezone_set('America/La_Paz');

$fechaInicio = isset($_GET['fechaInicio']) ? trim((string) $_GET['fechaInicio']) : '';
$fechaFin = isset($_GET['fechaFin']) ? trim((string) $_GET['fechaFin']) : '';
$idProveedor = isset($_GET['idProveedor']) ? $_GET['idProveedor'] : 0;
$idUsuario = isset($_GET['idUsuario']) ? $_GET['idUsuario'] : 0;
$idCategoria = isset($_GET['idCategoria']) ? $_GET['idCategoria'] : 0;
$tipoPago = isset($_GET['tipoPago']) ? $_GET['tipoPago'] : 0;
$descontarCaja = isset($_GET['descontarCaja']) ? $_GET['descontarCaja'] : 0;

if ($fechaInicio === '' || $fechaFin === '' || $fechaInicio > $fechaFin) {
	header('Content-Type: text/plain; charset=utf-8');
	echo 'Fechas inválidas.';
	exit;
}

$usuario = ControladorUsuarios::ctrMostrarUsuarios('id', $idUsuario);
$nombreUsuario = is_array($usuario) && isset($usuario['nombre']) ? $usuario['nombre'] : '';
$etiquetaTipoPago = 'Todos';
if (in_array((string) $tipoPago, array('1', '2', '4'), true)) {
	$etiquetaTipoPago = ControladorCompras::etiquetaFormaPagoCompra($tipoPago);
}
$etiquetaDescontarCaja = ControladorCompras::etiquetaDescontarCajaFiltro($descontarCaja);

$filas = ControladorCompras::ctrRangoFechasComprasPdf($fechaInicio, $fechaFin, $idProveedor, $idCategoria, $tipoPago, $descontarCaja);
if (!is_array($filas)) {
	$filas = [];
}

list($spreadsheet, $sheet, $headerRow) = excelNuevoLibro(
	'Compras',
	'REPORTE DE COMPRAS ENTRE FECHAS',
	[
		'Periodo: ' . date('d/m/Y', strtotime($fechaInicio)) . ' - ' . date('d/m/Y', strtotime($fechaFin)),
		'Usuario: ' . $nombreUsuario,
		'Forma de pago: ' . $etiquetaTipoPago,
		'Impacto en caja: ' . $etiquetaDescontarCaja,
		'Generado: ' . date('d-m-Y h:i:s a'),
	],
	9
);

excelEscribirCabeceras($sheet, ['#', 'Recibo Nº', 'Fecha', 'Usuario', 'Proveedor', 'F. Pago', 'Efectivo', 'Qr', 'Total'], $headerRow);

$rowNum = $headerRow + 1;
$n = 0;
$sum = 0.0;
$sumEf = 0.0;
$sumQr = 0.0;
foreach ($filas as $item) {
	$n++;
	$montos = ControladorCompras::montosReporteCompra($item);
	$sum += $montos['total'];
	$sumEf += $montos['efectivo'];
	$sumQr += $montos['qr'];
	$sheet->setCellValue('A' . $rowNum, $n);
	$sheet->setCellValue('B' . $rowNum, (string) ($item['codigo'] ?? ''));
	$sheet->setCellValue('C' . $rowNum, date('Y-m-d H:i', strtotime($item['fecha_alta'])));
	$sheet->setCellValue('D' . $rowNum, (string) ($item['usuario'] ?? ''));
	$sheet->setCellValue('E' . $rowNum, (string) ($item['proveedor'] ?? ''));
	$sheet->setCellValue('F' . $rowNum, ControladorCompras::etiquetaFormaPagoCompra($item['tipo_pago'] ?? ''));
	$sheet->setCellValue('G' . $rowNum, $montos['efectivo']);
	$sheet->setCellValue('H' . $rowNum, $montos['qr']);
	$sheet->setCellValue('I' . $rowNum, $montos['total']);
	$rowNum++;
}

if ($n > 0) {
	excelBordesRango($sheet, $headerRow + 1, $rowNum - 1, 9);
	$sheet->getStyle('G' . ($headerRow + 1) . ':I' . ($rowNum - 1))->getNumberFormat()->setFormatCode('#,##0.00');
}

$rowNum++;
$sheet->setCellValue('A' . $rowNum, 'Totales Efectivo / QR / Total:');
$sheet->setCellValue('G' . $rowNum, $sumEf);
$sheet->setCellValue('H' . $rowNum, $sumQr);
$sheet->setCellValue('I' . $rowNum, $sum);
$sheet->getStyle('A' . $rowNum . ':I' . $rowNum)->getFont()->setBold(true);
$sheet->getStyle('G' . $rowNum . ':I' . $rowNum)->getNumberFormat()->setFormatCode('#,##0.00');
$rowNum++;
$sheet->setCellValue('A' . $rowNum, 'Número de compras: ' . $n);

excelAutoSize($sheet, 9);
excelDescargarXls($spreadsheet, 'reporte-compras_' . $fechaInicio . '_' . $fechaFin . '.xls');
