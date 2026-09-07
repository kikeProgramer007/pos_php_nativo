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

if ($fechaInicio === '' || $fechaFin === '' || $fechaInicio > $fechaFin) {
	header('Content-Type: text/plain; charset=utf-8');
	echo 'Fechas inválidas.';
	exit;
}

$usuario = ControladorUsuarios::ctrMostrarUsuarios('id', $idUsuario);
$nombreUsuario = is_array($usuario) && isset($usuario['nombre']) ? $usuario['nombre'] : '';

$filas = ControladorCompras::ctrRangoFechasComprasPdf($fechaInicio, $fechaFin, $idProveedor, $idCategoria);
if (!is_array($filas)) {
	$filas = [];
}

list($spreadsheet, $sheet, $headerRow) = excelNuevoLibro(
	'Compras',
	'REPORTE DE COMPRAS ENTRE FECHAS',
	[
		'Periodo: ' . date('d/m/Y', strtotime($fechaInicio)) . ' - ' . date('d/m/Y', strtotime($fechaFin)),
		'Usuario: ' . $nombreUsuario,
		'Generado: ' . date('d-m-Y h:i:s a'),
	],
	6
);

excelEscribirCabeceras($sheet, ['#', 'Recibo Nº', 'Fecha', 'Usuario', 'Proveedor', 'Monto'], $headerRow);

$rowNum = $headerRow + 1;
$n = 0;
$sum = 0.0;
foreach ($filas as $item) {
	$n++;
	$total = floatval($item['total'] ?? 0);
	$sum += $total;
	$sheet->setCellValue('A' . $rowNum, $n);
	$sheet->setCellValue('B' . $rowNum, (string) ($item['codigo'] ?? ''));
	$sheet->setCellValue('C' . $rowNum, (string) ($item['fecha_alta'] ?? ''));
	$sheet->setCellValue('D' . $rowNum, (string) ($item['usuario'] ?? ''));
	$sheet->setCellValue('E' . $rowNum, (string) ($item['proveedor'] ?? ''));
	$sheet->setCellValue('F' . $rowNum, $total);
	$rowNum++;
}

if ($n > 0) {
	excelBordesRango($sheet, $headerRow + 1, $rowNum - 1, 6);
	$sheet->getStyle('F' . ($headerRow + 1) . ':F' . ($rowNum - 1))->getNumberFormat()->setFormatCode('#,##0.00');
}

$rowNum++;
$sheet->setCellValue('A' . $rowNum, 'Total:');
$sheet->setCellValue('F' . $rowNum, $sum);
$sheet->getStyle('A' . $rowNum)->getFont()->setBold(true);
$sheet->getStyle('F' . $rowNum)->getFont()->setBold(true);
$sheet->getStyle('F' . $rowNum)->getNumberFormat()->setFormatCode('#,##0.00" Bs."');
$rowNum++;
$sheet->setCellValue('A' . $rowNum, 'Número de compras: ' . $n);

excelAutoSize($sheet, 6);
excelDescargarXls($spreadsheet, 'reporte-compras_' . $fechaInicio . '_' . $fechaFin . '.xls');
