<?php

require_once __DIR__ . '/_bootstrap.php';
require_once __DIR__ . '/../../controladores/ventas.controlador.php';
require_once __DIR__ . '/../../modelos/ventas.modelo.php';
require_once __DIR__ . '/../../controladores/usuarios.controlador.php';
require_once __DIR__ . '/../../modelos/usuarios.modelo.php';

date_default_timezone_set('America/La_Paz');

$fechaInicio = isset($_GET['fechaInicio']) ? trim((string) $_GET['fechaInicio']) : '';
$fechaFin = isset($_GET['fechaFin']) ? trim((string) $_GET['fechaFin']) : '';
$idUsuario = isset($_GET['idUsuario']) ? $_GET['idUsuario'] : 0;
$idMesero = isset($_GET['idMesero']) ? intval($_GET['idMesero']) : 0;
$idCategoria = isset($_GET['idCategoria']) ? $_GET['idCategoria'] : array();

if ($fechaInicio === '' || $fechaFin === '' || $fechaInicio > $fechaFin) {
	header('Content-Type: text/plain; charset=utf-8');
	echo 'Fechas inválidas.';
	exit;
}

if (!is_array($idCategoria)) {
	$idCategoria = ($idCategoria === '' || $idCategoria === null) ? array() : array($idCategoria);
}

$usuario = ControladorUsuarios::ctrMostrarUsuarios('id', $idUsuario);
$nombreUsuario = is_array($usuario) && isset($usuario['nombre']) ? $usuario['nombre'] : '';

$filas = ControladorVentas::ctrRangoFechasVentasTopMeserosPdf($fechaInicio, $fechaFin, $idMesero, $idCategoria);
if (!is_array($filas)) {
	$filas = [];
}

list($spreadsheet, $sheet, $headerRow) = excelNuevoLibro(
	'Top meseros',
	'REPORTE TOP MESEROS VENTAS',
	[
		'Periodo: ' . date('d/m/Y', strtotime($fechaInicio)) . ' - ' . date('d/m/Y', strtotime($fechaFin)),
		'Usuario: ' . $nombreUsuario,
		'Generado: ' . date('d-m-Y h:i:s a'),
	],
	4
);

excelEscribirCabeceras($sheet, ['Nro Top', 'Mesero', 'Cantidad Ventas', 'Monto Ventas'], $headerRow);

$rowNum = $headerRow + 1;
$n = 0;
$sum = 0.0;
foreach ($filas as $item) {
	$n++;
	$total = floatval($item['total'] ?? 0);
	$sum += $total;
	$sheet->setCellValue('A' . $rowNum, $n);
	$sheet->setCellValue('B' . $rowNum, (string) ($item['mesero'] ?? ''));
	$sheet->setCellValue('C' . $rowNum, floatval($item['cantidad'] ?? 0));
	$sheet->setCellValue('D' . $rowNum, $total);
	$rowNum++;
}

if ($n > 0) {
	excelBordesRango($sheet, $headerRow + 1, $rowNum - 1, 4);
	$sheet->getStyle('D' . ($headerRow + 1) . ':D' . ($rowNum - 1))->getNumberFormat()->setFormatCode('#,##0.00');
}

$rowNum++;
$sheet->setCellValue('A' . $rowNum, 'Total monto:');
$sheet->setCellValue('D' . $rowNum, $sum);
$sheet->getStyle('A' . $rowNum)->getFont()->setBold(true);
$sheet->getStyle('D' . $rowNum)->getFont()->setBold(true);
$sheet->getStyle('D' . $rowNum)->getNumberFormat()->setFormatCode('#,##0.00" Bs."');
$rowNum++;
$sheet->setCellValue('A' . $rowNum, 'Total meseros: ' . $n);

excelAutoSize($sheet, 4);
excelDescargarXls($spreadsheet, 'top-meseros_' . $fechaInicio . '_' . $fechaFin . '.xls');
