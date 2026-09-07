<?php
/**
 * Bootstrap común para exportaciones Excel (PhpSpreadsheet).
 */
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../modelos/conexion.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

function excelNuevoLibro($tituloHoja, $tituloReporte, $metaFilas, $numCols)
{
	$ultimaCol = chr(ord('A') + max(0, $numCols - 1));
	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();
	$sheet->setTitle(mb_substr($tituloHoja, 0, 31));

	$sheet->setCellValue('A1', $tituloReporte);
	$sheet->mergeCells('A1:' . $ultimaCol . '1');
	$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
	$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

	$r = 2;
	foreach ($metaFilas as $texto) {
		$sheet->setCellValue('A' . $r, $texto);
		$sheet->mergeCells('A' . $r . ':' . $ultimaCol . $r);
		$r++;
	}
	return [$spreadsheet, $sheet, $r + 1];
}

function excelEscribirCabeceras($sheet, $headers, $row)
{
	$col = 'A';
	foreach ($headers as $header) {
		$sheet->setCellValue($col . $row, $header);
		$col++;
	}
	$ultima = chr(ord('A') + count($headers) - 1);
	$sheet->getStyle('A' . $row . ':' . $ultima . $row)->applyFromArray([
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
}

function excelBordesRango($sheet, $fromRow, $toRow, $cols)
{
	if ($toRow < $fromRow) {
		return;
	}
	$ultima = chr(ord('A') + $cols - 1);
	$sheet->getStyle('A' . $fromRow . ':' . $ultima . $toRow)->applyFromArray([
		'borders' => [
			'allBorders' => ['borderStyle' => Border::BORDER_THIN],
		],
	]);
}

function excelAutoSize($sheet, $cols)
{
	for ($i = 0; $i < $cols; $i++) {
		$sheet->getColumnDimension(chr(ord('A') + $i))->setAutoSize(true);
	}
}

function excelDescargarXls($spreadsheet, $fileName)
{
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename="' . $fileName . '"');
	header('Cache-Control: max-age=0');
	$writer = new Xls($spreadsheet);
	$writer->save('php://output');
	$spreadsheet->disconnectWorksheets();
	unset($spreadsheet);
	exit;
}
