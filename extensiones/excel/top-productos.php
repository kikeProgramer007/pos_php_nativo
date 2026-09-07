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
$idCategoria = isset($_GET['idCategoria']) ? intval($_GET['idCategoria']) : 0;
$idMesero = (isset($_GET['idMesero']) && $_GET['idMesero'] !== '') ? intval($_GET['idMesero']) : null;

if ($fechaInicio === '' || $fechaFin === '' || $fechaInicio > $fechaFin) {
	header('Content-Type: text/plain; charset=utf-8');
	echo 'Fechas inválidas.';
	exit;
}

$usuario = ControladorUsuarios::ctrMostrarUsuarios('id', $idUsuario);
$nombreUsuario = is_array($usuario) && isset($usuario['nombre']) ? $usuario['nombre'] : '';

$filas = ControladorVentas::ctrRangoFechasTopProductoMasVendidosPdf($fechaInicio, $fechaFin, $idCategoria, $idMesero);
if (!is_array($filas)) {
	$filas = [];
}

list($spreadsheet, $sheet, $headerRow) = excelNuevoLibro(
	'Top productos',
	'REPORTE TOP PRODUCTOS MÁS VENDIDOS',
	[
		'Periodo: ' . date('d/m/Y', strtotime($fechaInicio)) . ' - ' . date('d/m/Y', strtotime($fechaFin)),
		'Usuario: ' . $nombreUsuario,
		'Generado: ' . date('d-m-Y h:i:s a'),
	],
	4
);

excelEscribirCabeceras($sheet, ['#', 'Mesero', 'Producto', 'Cantidad'], $headerRow);

$rowNum = $headerRow + 1;
$n = 0;
$sum = 0.0;
foreach ($filas as $item) {
	$n++;
	$cant = floatval($item['cantidad'] ?? 0);
	$sum += $cant;
	$sheet->setCellValue('A' . $rowNum, $n);
	$sheet->setCellValue('B' . $rowNum, (string) ($item['mesero'] ?? 'Sin mesero'));
	$sheet->setCellValue('C' . $rowNum, (string) ($item['descripcion'] ?? ''));
	$sheet->setCellValue('D' . $rowNum, $cant);
	$rowNum++;
}

if ($n > 0) {
	excelBordesRango($sheet, $headerRow + 1, $rowNum - 1, 4);
}

$rowNum++;
$sheet->setCellValue('A' . $rowNum, 'Total cantidad:');
$sheet->setCellValue('D' . $rowNum, $sum);
$sheet->getStyle('A' . $rowNum)->getFont()->setBold(true);
$sheet->getStyle('D' . $rowNum)->getFont()->setBold(true);

excelAutoSize($sheet, 4);
excelDescargarXls($spreadsheet, 'top-productos_' . $fechaInicio . '_' . $fechaFin . '.xls');
