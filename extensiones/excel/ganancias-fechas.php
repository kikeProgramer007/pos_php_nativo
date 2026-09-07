<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../modelos/conexion.php';
require_once __DIR__ . '/../../modelos/reportes.modelo.php';
require_once __DIR__ . '/../../controladores/usuarios.controlador.php';
require_once __DIR__ . '/../../modelos/usuarios.modelo.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

date_default_timezone_set('America/La_Paz');

$fechaInicio = isset($_GET["fechaInicio"]) ? trim((string) $_GET["fechaInicio"]) : '';
$fechaFin = isset($_GET["fechaFin"]) ? trim((string) $_GET["fechaFin"]) : '';
$idUsuario = isset($_GET["idUsuario"]) ? $_GET["idUsuario"] : 0;

if ($fechaInicio === '' || $fechaFin === '' || $fechaInicio > $fechaFin) {
	header('Content-Type: text/plain; charset=utf-8');
	echo 'Fechas inválidas. Seleccione un rango correcto.';
	exit;
}

$usuario = ControladorUsuarios::ctrMostrarUsuarios('id', $idUsuario);
$nombreUsuario = is_array($usuario) && isset($usuario['nombre']) ? $usuario['nombre'] : '';

$filas = ModeloReportes::mdlObtenerGananciasEntreFechas($fechaInicio, $fechaFin);

$iniFmt = date('d/m/Y', strtotime($fechaInicio));
$finFmt = date('d/m/Y', strtotime($fechaFin));

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Ganancias');

$sheet->setCellValue('A1', 'REPORTE DE GANANCIAS ENTRE FECHAS');
$sheet->mergeCells('A1:G1');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$sheet->setCellValue('A2', 'Periodo: ' . $iniFmt . ' - ' . $finFmt);
$sheet->mergeCells('A2:G2');
$sheet->setCellValue('A3', 'Usuario: ' . $nombreUsuario);
$sheet->mergeCells('A3:G3');
$sheet->setCellValue('A4', 'Generado: ' . date('d-m-Y h:i:s a'));
$sheet->mergeCells('A4:G4');

$headers = ['Ticket', 'Fecha', 'Mesero', 'Cobrado', 'Descuento', 'Costo', 'Ganancia'];
$col = 'A';
foreach ($headers as $header) {
	$sheet->setCellValue($col . '6', $header);
	$col++;
}

$headerStyle = [
	'font' => ['bold' => true],
	'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
	'fill' => [
		'fillType' => Fill::FILL_SOLID,
		'startColor' => ['rgb' => 'D9E1F2'],
	],
	'borders' => [
		'allBorders' => ['borderStyle' => Border::BORDER_THIN],
	],
];
$sheet->getStyle('A6:G6')->applyFromArray($headerStyle);

$rowNum = 7;
$sumCobrado = 0.0;
$sumCosto = 0.0;
$sumGanancias = 0.0;

foreach ($filas as $row) {
	$cobrado = floatval($row['total'] ?? 0);
	$descuento = floatval($row['total_descuento'] ?? 0);
	$costo = floatval($row['costo'] ?? 0);
	$ganancia = floatval($row['ganancias'] ?? 0);

	$sumCobrado += $cobrado;
	$sumCosto += $costo;
	$sumGanancias += $ganancia;

	$sheet->setCellValue('A' . $rowNum, ltrim((string) $row['codigo'], '0'));
	$sheet->setCellValue('B' . $rowNum, (string) ($row['fecha'] ?? ''));
	$sheet->setCellValue('C' . $rowNum, (string) ($row['mesero'] ?? ''));
	$sheet->setCellValue('D' . $rowNum, $cobrado);
	$sheet->setCellValue('E' . $rowNum, $descuento);
	$sheet->setCellValue('F' . $rowNum, $costo);
	$sheet->setCellValue('G' . $rowNum, $ganancia);
	$rowNum++;
}

if ($rowNum > 7) {
	$lastDataRow = $rowNum - 1;
	$sheet->getStyle('A7:G' . $lastDataRow)->applyFromArray([
		'borders' => [
			'allBorders' => ['borderStyle' => Border::BORDER_THIN],
		],
	]);
	$sheet->getStyle('D7:G' . $lastDataRow)->getNumberFormat()->setFormatCode('#,##0.00');
}

$rowNum++;
$sheet->setCellValue('A' . $rowNum, 'Vendiste:');
$sheet->setCellValue('D' . $rowNum, $sumCobrado);
$sheet->getStyle('A' . $rowNum)->getFont()->setBold(true);
$sheet->getStyle('D' . $rowNum)->getFont()->setBold(true);
$sheet->getStyle('D' . $rowNum)->getNumberFormat()->setFormatCode('#,##0.00" Bs."');
$rowNum++;

$sheet->setCellValue('A' . $rowNum, 'Te costó:');
$sheet->setCellValue('D' . $rowNum, $sumCosto);
$sheet->getStyle('A' . $rowNum)->getFont()->setBold(true);
$sheet->getStyle('D' . $rowNum)->getFont()->setBold(true);
$sheet->getStyle('D' . $rowNum)->getNumberFormat()->setFormatCode('#,##0.00" Bs."');
$rowNum++;

$sheet->setCellValue('A' . $rowNum, 'Te quedó (ganancia):');
$sheet->setCellValue('D' . $rowNum, $sumGanancias);
$sheet->getStyle('A' . $rowNum)->getFont()->setBold(true);
$sheet->getStyle('D' . $rowNum)->getFont()->setBold(true);
$sheet->getStyle('D' . $rowNum)->getNumberFormat()->setFormatCode('#,##0.00" Bs."');

foreach (range('A', 'G') as $c) {
	$sheet->getColumnDimension($c)->setAutoSize(true);
}

$fileName = 'ganancias-entre-fechas_' . $fechaInicio . '_' . $fechaFin . '.xls';

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="' . $fileName . '"');
header('Cache-Control: max-age=0');

$writer = new Xls($spreadsheet);
$writer->save('php://output');
$spreadsheet->disconnectWorksheets();
unset($spreadsheet);
exit;
