<?php

require_once __DIR__ . '/_bootstrap.php';
require_once __DIR__ . '/../../controladores/ventas.controlador.php';
require_once __DIR__ . '/../../modelos/ventas.modelo.php';
require_once __DIR__ . '/../../controladores/usuarios.controlador.php';
require_once __DIR__ . '/../../modelos/usuarios.modelo.php';

date_default_timezone_set('America/La_Paz');

$fechaInicio = isset($_GET['fechaInicio']) ? trim((string) $_GET['fechaInicio']) : '';
$fechaFin = isset($_GET['fechaFin']) ? trim((string) $_GET['fechaFin']) : '';
$idMesero = isset($_GET['idMesero']) ? $_GET['idMesero'] : 0;
$idUsuario = isset($_GET['idUsuario']) ? $_GET['idUsuario'] : 0;
$idCategoria = isset($_GET['idCategoria']) ? $_GET['idCategoria'] : 0;
$idCliente = isset($_GET['idCliente']) ? $_GET['idCliente'] : 0;
$tipoPago = isset($_GET['tipoPago']) ? $_GET['tipoPago'] : '0';
$estadoPago = isset($_GET['estadoPago']) ? $_GET['estadoPago'] : '0';
$registroEliminados = isset($_GET['registroEliminados']) ? $_GET['registroEliminados'] : 'false';

if ($fechaInicio === '' || $fechaFin === '' || $fechaInicio > $fechaFin) {
	header('Content-Type: text/plain; charset=utf-8');
	echo 'Fechas inválidas.';
	exit;
}

$usuario = ControladorUsuarios::ctrMostrarUsuarios('id', $idUsuario);
$nombreUsuario = is_array($usuario) && isset($usuario['nombre']) ? $usuario['nombre'] : '';

$filas = ControladorVentas::ctrRangoFechasVentasPdf(
	$fechaInicio,
	$fechaFin,
	$idMesero,
	$idCategoria,
	$idCliente,
	$registroEliminados,
	$tipoPago,
	$estadoPago
);
if (!is_array($filas)) {
	$filas = [];
}

function excelEtiquetaEstadoPago($v)
{
	$v = strtoupper(trim((string) $v));
	if ($v === 'PAGADA' || $v === '2') return 'Pagado';
	if ($v === 'PENDIENTE' || $v === '1') return 'Pendiente';
	return $v !== '' ? $v : '-';
}

list($spreadsheet, $sheet, $headerRow) = excelNuevoLibro(
	'Ventas',
	'REPORTE DE VENTAS ENTRE FECHAS',
	[
		'Periodo: ' . date('d/m/Y', strtotime($fechaInicio)) . ' - ' . date('d/m/Y', strtotime($fechaFin)),
		'Usuario: ' . $nombreUsuario,
		'Generado: ' . date('d-m-Y h:i:s a'),
	],
	11
);

$headers = ['#', 'Ticket', 'Fecha', 'Usuario', 'Mesero', 'Cliente', 'Estado', 'T. Pago', 'Efectivo', 'Qr', 'Total'];
excelEscribirCabeceras($sheet, $headers, $headerRow);

$rowNum = $headerRow + 1;
$n = 0;
$sumEf = 0.0;
$sumQr = 0.0;
$sumTotal = 0.0;
$sumBruto = 0.0;
$sumDesc = 0.0;

foreach ($filas as $item) {
	$n++;
	$total = floatval($item['total'] ?? 0);
	$ef = floatval($item['total_efectivo'] ?? 0);
	$qr = floatval($item['total_qr'] ?? 0);
	$bruto = floatval($item['total_bruto'] ?? $total);
	$desc = floatval($item['total_descuento'] ?? 0);

	$sheet->setCellValue('A' . $rowNum, $n);
	$sheet->setCellValue('B' . $rowNum, ltrim((string) ($item['codigo'] ?? ''), '0'));
	$sheet->setCellValue('C' . $rowNum, date('Y-m-d H:i', strtotime($item['fecha'])));
	$sheet->setCellValue('D' . $rowNum, (string) ($item['usuario'] ?? ''));
	$sheet->setCellValue('E' . $rowNum, (string) ($item['mesero'] ?? ''));
	$sheet->setCellValue('F' . $rowNum, (string) ($item['cliente'] ?? ''));
	$sheet->setCellValue('G' . $rowNum, excelEtiquetaEstadoPago($item['estado_pago'] ?? ''));
	$sheet->setCellValue('H' . $rowNum, (string) ($item['tipo_pago'] ?? ''));
	$sheet->setCellValue('I' . $rowNum, $ef);
	$sheet->setCellValue('J' . $rowNum, $qr);
	$sheet->setCellValue('K' . $rowNum, $total);

	$sumEf += $ef;
	$sumQr += $qr;
	$sumTotal += $total;
	$sumBruto += $bruto;
	$sumDesc += $desc;
	$rowNum++;
}

if ($n > 0) {
	excelBordesRango($sheet, $headerRow + 1, $rowNum - 1, 11);
	$sheet->getStyle('I' . ($headerRow + 1) . ':K' . ($rowNum - 1))->getNumberFormat()->setFormatCode('#,##0.00');
}

$rowNum++;
$sheet->setCellValue('A' . $rowNum, 'Totales Efectivo / QR / Total:');
$sheet->setCellValue('I' . $rowNum, $sumEf);
$sheet->setCellValue('J' . $rowNum, $sumQr);
$sheet->setCellValue('K' . $rowNum, $sumTotal);
$sheet->getStyle('A' . $rowNum . ':K' . $rowNum)->getFont()->setBold(true);
$sheet->getStyle('I' . $rowNum . ':K' . $rowNum)->getNumberFormat()->setFormatCode('#,##0.00');
$rowNum++;
$sheet->setCellValue('A' . $rowNum, 'Total items (bruto):');
$sheet->setCellValue('K' . $rowNum, $sumBruto);
$sheet->getStyle('K' . $rowNum)->getNumberFormat()->setFormatCode('#,##0.00" Bs."');
$rowNum++;
$sheet->setCellValue('A' . $rowNum, 'Total descuentos:');
$sheet->setCellValue('K' . $rowNum, $sumDesc);
$sheet->getStyle('K' . $rowNum)->getNumberFormat()->setFormatCode('#,##0.00" Bs."');
$rowNum++;
$sheet->setCellValue('A' . $rowNum, 'Total neto cobrado:');
$sheet->setCellValue('K' . $rowNum, $sumTotal);
$sheet->getStyle('A' . $rowNum)->getFont()->setBold(true);
$sheet->getStyle('K' . $rowNum)->getFont()->setBold(true);
$sheet->getStyle('K' . $rowNum)->getNumberFormat()->setFormatCode('#,##0.00" Bs."');
$rowNum++;
$sheet->setCellValue('A' . $rowNum, 'Número de ventas: ' . $n);

excelAutoSize($sheet, 11);
excelDescargarXls($spreadsheet, 'reporte-ventas_' . $fechaInicio . '_' . $fechaFin . '.xls');
