<?php

require_once __DIR__ . '/_bootstrap.php';
require_once __DIR__ . '/../../controladores/gastos.controlador.php';
require_once __DIR__ . '/../../modelos/gastos.modelo.php';
require_once __DIR__ . '/../../controladores/usuarios.controlador.php';
require_once __DIR__ . '/../../modelos/usuarios.modelo.php';

date_default_timezone_set('America/La_Paz');

$fechaInicio = isset($_GET['fechaInicio']) ? trim((string) $_GET['fechaInicio']) : '';
$fechaFin = isset($_GET['fechaFin']) ? trim((string) $_GET['fechaFin']) : '';
$idTipoGasto = isset($_GET['idTipoGasto']) ? $_GET['idTipoGasto'] : 0;
$formaPago = isset($_GET['formaPago']) ? $_GET['formaPago'] : 0;
$idUsuarioFiltro = isset($_GET['idUsuarioFiltro']) ? $_GET['idUsuarioFiltro'] : 0;
$idUsuario = isset($_GET['idUsuario']) ? $_GET['idUsuario'] : 0;

if ($fechaInicio === '' || $fechaFin === '' || $fechaInicio > $fechaFin) {
	header('Content-Type: text/plain; charset=utf-8');
	echo 'Fechas inválidas.';
	exit;
}

$usuario = ControladorUsuarios::ctrMostrarUsuarios('id', $idUsuario);
$nombreUsuario = is_array($usuario) && isset($usuario['nombre']) ? $usuario['nombre'] : '';

$filas = ControladorGastos::ctrReporteGastosEntreFechas($fechaInicio, $fechaFin, $idTipoGasto, $formaPago, $idUsuarioFiltro);
if (!is_array($filas)) {
	$filas = [];
}

list($spreadsheet, $sheet, $headerRow) = excelNuevoLibro(
	'Gastos',
	'REPORTE DE GASTOS ENTRE FECHAS',
	[
		'Periodo: ' . date('d/m/Y', strtotime($fechaInicio)) . ' - ' . date('d/m/Y', strtotime($fechaFin)),
		'Usuario: ' . $nombreUsuario,
		'Generado: ' . date('d-m-Y h:i:s a'),
	],
	7
);

excelEscribirCabeceras($sheet, ['#', 'Fecha', 'Tipo', 'Descripción', 'Forma pago', 'Usuario', 'Monto'], $headerRow);

$rowNum = $headerRow + 1;
$n = 0;
$sum = 0.0;
foreach ($filas as $item) {
	$n++;
	$monto = floatval($item['monto'] ?? 0);
	$sum += $monto;
	$sheet->setCellValue('A' . $rowNum, $n);
	$sheet->setCellValue('B' . $rowNum, date('d-m-Y', strtotime($item['fecha'])));
	$sheet->setCellValue('C' . $rowNum, (string) ($item['nombre_tipo_gasto'] ?? ''));
	$sheet->setCellValue('D' . $rowNum, (string) ($item['descripcion'] ?? ''));
	$sheet->setCellValue('E' . $rowNum, (string) ($item['forma_pago_descripcion'] ?? ''));
	$sheet->setCellValue('F' . $rowNum, (string) ($item['nombre_usuario'] ?? ''));
	$sheet->setCellValue('G' . $rowNum, $monto);
	$rowNum++;
}

if ($n > 0) {
	excelBordesRango($sheet, $headerRow + 1, $rowNum - 1, 7);
	$sheet->getStyle('G' . ($headerRow + 1) . ':G' . ($rowNum - 1))->getNumberFormat()->setFormatCode('#,##0.00');
}

$rowNum++;
$sheet->setCellValue('A' . $rowNum, 'Total:');
$sheet->setCellValue('G' . $rowNum, $sum);
$sheet->getStyle('A' . $rowNum)->getFont()->setBold(true);
$sheet->getStyle('G' . $rowNum)->getFont()->setBold(true);
$sheet->getStyle('G' . $rowNum)->getNumberFormat()->setFormatCode('#,##0.00" Bs."');
$rowNum++;
$sheet->setCellValue('A' . $rowNum, 'Número de gastos: ' . $n);

excelAutoSize($sheet, 7);
excelDescargarXls($spreadsheet, 'reporte-gastos_' . $fechaInicio . '_' . $fechaFin . '.xls');
