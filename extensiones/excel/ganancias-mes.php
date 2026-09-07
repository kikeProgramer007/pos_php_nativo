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

$month = isset($_GET['month']) ? (int) $_GET['month'] : 0;
$year = isset($_GET['year']) ? (int) $_GET['year'] : 0;
$idUsuario = isset($_GET['idUsuario']) ? $_GET['idUsuario'] : 0;

if ($month < 1 || $month > 12 || $year < 2000) {
	header('Content-Type: text/plain; charset=utf-8');
	echo 'Mes o año inválidos.';
	exit;
}

$meses = [
	1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
	5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
	9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
];
$nombreMes = isset($meses[$month]) ? $meses[$month] : (string) $month;

$usuario = ControladorUsuarios::ctrMostrarUsuarios('id', $idUsuario);
$nombreUsuario = is_array($usuario) && isset($usuario['nombre']) ? $usuario['nombre'] : '';

$filas = ModeloReportes::mdlObtenerGanancias($month, $year);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Ganancias mes');

$sheet->setCellValue('A1', 'REPORTE DE GANANCIAS POR MES');
$sheet->mergeCells('A1:G1');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$sheet->setCellValue('A2', 'Periodo: ' . $nombreMes . ' de ' . $year);
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
$sheet->getStyle('A6:G6')->applyFromArray([
	'font' => ['bold' => true],
	'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
	'fill' => [
		'fillType' => Fill::FILL_SOLID,
		'startColor' => ['rgb' => 'D9E1F2'],
	],
	'borders' => [
		'allBorders' => ['borderStyle' => Border::BORDER_THIN],
	],
]);

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

$fileName = 'ganancias-por-mes_' . str_pad((string) $month, 2, '0', STR_PAD_LEFT) . '_' . $year . '.xls';

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="' . $fileName . '"');
header('Cache-Control: max-age=0');

$writer = new Xls($spreadsheet);
$writer->save('php://output');
$spreadsheet->disconnectWorksheets();
unset($spreadsheet);
exit;
