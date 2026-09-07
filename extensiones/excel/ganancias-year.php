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

$yearIni = isset($_GET['yearini']) ? (int) $_GET['yearini'] : 0;
$yearFin = isset($_GET['yearfin']) ? (int) $_GET['yearfin'] : 0;
$idUsuario = isset($_GET['idUsuario']) ? $_GET['idUsuario'] : 0;

if ($yearIni < 2000 || $yearFin < 2000 || $yearIni > $yearFin) {
	header('Content-Type: text/plain; charset=utf-8');
	echo 'Rango de años inválido.';
	exit;
}

$mesesNom = [
	1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
	5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
	9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
];

$usuario = ControladorUsuarios::ctrMostrarUsuarios('id', $idUsuario);
$nombreUsuario = is_array($usuario) && isset($usuario['nombre']) ? $usuario['nombre'] : '';

$datos = ModeloReportes::mdlObtenerGananciasYear($yearIni, $yearFin);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Ganancias año');

$sheet->setCellValue('A1', 'REPORTE DE GANANCIAS POR AÑO');
$sheet->mergeCells('A1:F1');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$sheet->setCellValue('A2', 'Periodo: ' . $yearIni . ' hasta ' . $yearFin);
$sheet->mergeCells('A2:F2');
$sheet->setCellValue('A3', 'Usuario: ' . $nombreUsuario);
$sheet->mergeCells('A3:F3');
$sheet->setCellValue('A4', 'Generado: ' . date('d-m-Y h:i:s a'));
$sheet->mergeCells('A4:F4');

$headers = ['Año', 'Mes', 'Cobrado', 'Costo', 'Ganancia', 'Meseros'];
$col = 'A';
foreach ($headers as $header) {
	$sheet->setCellValue($col . '6', $header);
	$col++;
}
$sheet->getStyle('A6:F6')->applyFromArray([
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
$sumCobradoGlobal = 0.0;
$sumCostoGlobal = 0.0;
$sumGananciaGlobal = 0.0;
$sumMeserosGlobal = 0;

$sumCobradoYear = 0.0;
$sumCostoYear = 0.0;
$sumGananciaYear = 0.0;
$sumMeserosYear = 0;
$yearActual = null;

foreach ($datos as $dato) {
	$anio = (int) $dato['year'];
	$mes = (int) $dato['mes'];
	$cobrado = floatval($dato['ventas'] ?? 0);
	$meseros = (int) ($dato['meseros'] ?? 0);

	if ($yearActual === null) {
		$yearActual = $anio;
	}

	if ($anio !== $yearActual) {
		$sheet->setCellValue('A' . $rowNum, 'TOTAL ' . $yearActual);
		$sheet->setCellValue('C' . $rowNum, $sumCobradoYear);
		$sheet->setCellValue('D' . $rowNum, $sumCostoYear);
		$sheet->setCellValue('E' . $rowNum, $sumGananciaYear);
		$sheet->setCellValue('F' . $rowNum, $sumMeserosYear);
		$sheet->getStyle('A' . $rowNum . ':F' . $rowNum)->getFont()->setBold(true);
		$sheet->getStyle('C' . $rowNum . ':E' . $rowNum)->getNumberFormat()->setFormatCode('#,##0.00');
		$rowNum++;

		$sumCobradoYear = 0.0;
		$sumCostoYear = 0.0;
		$sumGananciaYear = 0.0;
		$sumMeserosYear = 0;
		$yearActual = $anio;
	}

	$detalle = ModeloReportes::mdlObtenerGanancias($mes, $anio);
	$ganancia = 0.0;
	$costo = 0.0;
	foreach ($detalle as $d) {
		$ganancia += floatval($d['ganancias'] ?? 0);
		$costo += floatval($d['costo'] ?? 0);
	}

	$nombreMes = isset($mesesNom[$mes]) ? $mesesNom[$mes] : (string) $mes;

	$sheet->setCellValue('A' . $rowNum, $anio);
	$sheet->setCellValue('B' . $rowNum, $nombreMes);
	$sheet->setCellValue('C' . $rowNum, $cobrado);
	$sheet->setCellValue('D' . $rowNum, $costo);
	$sheet->setCellValue('E' . $rowNum, $ganancia);
	$sheet->setCellValue('F' . $rowNum, $meseros);

	$sumCobradoYear += $cobrado;
	$sumCostoYear += $costo;
	$sumGananciaYear += $ganancia;
	$sumMeserosYear += $meseros;

	$sumCobradoGlobal += $cobrado;
	$sumCostoGlobal += $costo;
	$sumGananciaGlobal += $ganancia;
	$sumMeserosGlobal += $meseros;

	$rowNum++;
}

if ($yearActual !== null) {
	$sheet->setCellValue('A' . $rowNum, 'TOTAL ' . $yearActual);
	$sheet->setCellValue('C' . $rowNum, $sumCobradoYear);
	$sheet->setCellValue('D' . $rowNum, $sumCostoYear);
	$sheet->setCellValue('E' . $rowNum, $sumGananciaYear);
	$sheet->setCellValue('F' . $rowNum, $sumMeserosYear);
	$sheet->getStyle('A' . $rowNum . ':F' . $rowNum)->getFont()->setBold(true);
	$sheet->getStyle('C' . $rowNum . ':E' . $rowNum)->getNumberFormat()->setFormatCode('#,##0.00');
	$rowNum++;
}

if ($rowNum > 7) {
	$lastDataRow = $rowNum - 1;
	$sheet->getStyle('A7:F' . $lastDataRow)->applyFromArray([
		'borders' => [
			'allBorders' => ['borderStyle' => Border::BORDER_THIN],
		],
	]);
	$sheet->getStyle('C7:E' . $lastDataRow)->getNumberFormat()->setFormatCode('#,##0.00');
}

$rowNum++;
$sheet->setCellValue('A' . $rowNum, 'Vendiste:');
$sheet->setCellValue('C' . $rowNum, $sumCobradoGlobal);
$sheet->getStyle('A' . $rowNum)->getFont()->setBold(true);
$sheet->getStyle('C' . $rowNum)->getFont()->setBold(true);
$sheet->getStyle('C' . $rowNum)->getNumberFormat()->setFormatCode('#,##0.00" Bs."');
$rowNum++;

$sheet->setCellValue('A' . $rowNum, 'Te costó:');
$sheet->setCellValue('C' . $rowNum, $sumCostoGlobal);
$sheet->getStyle('A' . $rowNum)->getFont()->setBold(true);
$sheet->getStyle('C' . $rowNum)->getFont()->setBold(true);
$sheet->getStyle('C' . $rowNum)->getNumberFormat()->setFormatCode('#,##0.00" Bs."');
$rowNum++;

$sheet->setCellValue('A' . $rowNum, 'Te quedó (ganancia):');
$sheet->setCellValue('C' . $rowNum, $sumGananciaGlobal);
$sheet->getStyle('A' . $rowNum)->getFont()->setBold(true);
$sheet->getStyle('C' . $rowNum)->getFont()->setBold(true);
$sheet->getStyle('C' . $rowNum)->getNumberFormat()->setFormatCode('#,##0.00" Bs."');

foreach (range('A', 'F') as $c) {
	$sheet->getColumnDimension($c)->setAutoSize(true);
}

$fileName = 'ganancias-por-anio_' . $yearIni . '_' . $yearFin . '.xls';

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="' . $fileName . '"');
header('Cache-Control: max-age=0');

$writer = new Xls($spreadsheet);
$writer->save('php://output');
$spreadsheet->disconnectWorksheets();
unset($spreadsheet);
exit;
