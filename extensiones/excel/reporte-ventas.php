<?php

require_once __DIR__ . '/_bootstrap.php';
require_once __DIR__ . '/../../controladores/ventas.controlador.php';
require_once __DIR__ . '/../../modelos/ventas.modelo.php';
require_once __DIR__ . '/../../controladores/usuarios.controlador.php';
require_once __DIR__ . '/../../modelos/usuarios.modelo.php';
require_once __DIR__ . '/../../modelos/mesas.modelo.php';

date_default_timezone_set('America/La_Paz');

$fechaInicio = isset($_GET['fechaInicio']) ? trim((string) $_GET['fechaInicio']) : '';
$fechaFin = isset($_GET['fechaFin']) ? trim((string) $_GET['fechaFin']) : '';
$idMesero = isset($_GET['idMesero']) ? $_GET['idMesero'] : 0;
$idUsuario = isset($_GET['idUsuario']) ? $_GET['idUsuario'] : 0;
$idCategoria = isset($_GET['idCategoria']) ? $_GET['idCategoria'] : 0;
$idCliente = isset($_GET['idCliente']) ? $_GET['idCliente'] : 0;
$tipoPago = isset($_GET['tipoPago']) ? $_GET['tipoPago'] : '0';
$estadoPago = isset($_GET['estadoPago']) ? $_GET['estadoPago'] : '0';
$formaAtencion = isset($_GET['formaAtencion']) ? $_GET['formaAtencion'] : '0';
$registroEliminados = isset($_GET['registroEliminados']) ? $_GET['registroEliminados'] : 'false';
$gestionMesas = ModeloEmpresa::mdlFuncionActiva('venta.gestion_mesas');
$idsMesas = isset($_GET['idsMesas']) ? $_GET['idsMesas'] : array();
if (!is_array($idsMesas)) {
	$idsMesas = array($idsMesas);
}
if (!$gestionMesas) {
	$idsMesas = array();
}

if ($fechaInicio === '' || $fechaFin === '' || $fechaInicio > $fechaFin) {
	header('Content-Type: text/plain; charset=utf-8');
	echo 'Fechas inválidas.';
	exit;
}

$usuario = ControladorUsuarios::ctrMostrarUsuarios('id', $idUsuario);
$nombreUsuario = is_array($usuario) && isset($usuario['nombre']) ? $usuario['nombre'] : '';
$etiquetaFormaAtencion = 'Todos';
if ((string) $formaAtencion === '1') {
	$etiquetaFormaAtencion = 'Mesa';
} elseif ((string) $formaAtencion === '2') {
	$etiquetaFormaAtencion = 'Llevar';
}

$etiquetaMesas = 'Todas';
if ($gestionMesas && $idsMesas) {
	$nombres = array();
	foreach ($idsMesas as $idM) {
		$idM = intval($idM);
		if ($idM <= 0) {
			continue;
		}
		$txt = ModeloMesas::mdlTextoMesasVenta(array('id_mesa' => $idM));
		if ($txt !== '') {
			$nombres[] = $txt;
		}
	}
	if ($nombres) {
		$etiquetaMesas = implode('; ', $nombres);
	}
}

$filas = ControladorVentas::ctrRangoFechasVentasPdf(
	$fechaInicio,
	$fechaFin,
	$idMesero,
	$idCategoria,
	$idCliente,
	$registroEliminados,
	$tipoPago,
	$estadoPago,
	$formaAtencion,
	$idsMesas
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

$meta = array(
	'Periodo: ' . date('d/m/Y', strtotime($fechaInicio)) . ' - ' . date('d/m/Y', strtotime($fechaFin)),
	'Usuario: ' . $nombreUsuario,
	'Forma de atención: ' . $etiquetaFormaAtencion,
);
if ($gestionMesas) {
	$meta[] = 'Mesas: ' . $etiquetaMesas;
}
$meta[] = 'Generado: ' . date('d-m-Y h:i:s a');

$headers = array('#', 'Ticket', 'Fecha', 'Usuario', 'Mesero', 'Cliente');
if ($gestionMesas) {
	$headers[] = 'Mesa';
	$headers[] = 'Personas';
}
$headers = array_merge($headers, array('Estado', 'F. Atención', 'T. Pago', 'Efectivo', 'Qr', 'Total'));
$numCols = count($headers);

list($spreadsheet, $sheet, $headerRow) = excelNuevoLibro(
	'Ventas',
	'REPORTE DE VENTAS ENTRE FECHAS',
	$meta,
	$numCols
);

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

	$vals = array(
		$n,
		ltrim((string) ($item['codigo'] ?? ''), '0'),
		date('Y-m-d H:i', strtotime($item['fecha'])),
		(string) ($item['usuario'] ?? ''),
		(string) ($item['mesero'] ?? ''),
		(string) ($item['cliente'] ?? ''),
	);
	if ($gestionMesas) {
		$textoMesa = ModeloMesas::mdlTextoMesasVenta($item);
		if ($textoMesa === '') {
			$textoMesa = '-';
		}
		$personas = isset($item['cantidad_personas']) && $item['cantidad_personas'] !== null && $item['cantidad_personas'] !== ''
			? intval($item['cantidad_personas'])
			: '-';
		$vals[] = $textoMesa;
		$vals[] = $personas;
	}
	$vals[] = excelEtiquetaEstadoPago($item['estado_pago'] ?? '');
	$vals[] = ControladorVentas::etiquetaFormaAtencion($item['forma_atencion'] ?? '');
	$vals[] = (string) ($item['tipo_pago'] ?? '');
	$vals[] = $ef;
	$vals[] = $qr;
	$vals[] = $total;

	$col = 'A';
	foreach ($vals as $v) {
		$sheet->setCellValue($col . $rowNum, $v);
		$col++;
	}

	$sumEf += $ef;
	$sumQr += $qr;
	$sumTotal += $total;
	$sumBruto += $bruto;
	$sumDesc += $desc;
	$rowNum++;
}

$colEf = chr(ord('A') + $numCols - 3);
$colQr = chr(ord('A') + $numCols - 2);
$colTot = chr(ord('A') + $numCols - 1);

if ($n > 0) {
	excelBordesRango($sheet, $headerRow + 1, $rowNum - 1, $numCols);
	$sheet->getStyle($colEf . ($headerRow + 1) . ':' . $colTot . ($rowNum - 1))->getNumberFormat()->setFormatCode('#,##0.00');
}

$rowNum++;
$sheet->setCellValue('A' . $rowNum, 'Totales Efectivo / QR / Total:');
$sheet->setCellValue($colEf . $rowNum, $sumEf);
$sheet->setCellValue($colQr . $rowNum, $sumQr);
$sheet->setCellValue($colTot . $rowNum, $sumTotal);
$sheet->getStyle('A' . $rowNum . ':' . $colTot . $rowNum)->getFont()->setBold(true);
$sheet->getStyle($colEf . $rowNum . ':' . $colTot . $rowNum)->getNumberFormat()->setFormatCode('#,##0.00');
$rowNum++;
$sheet->setCellValue('A' . $rowNum, 'Total items (bruto):');
$sheet->setCellValue($colTot . $rowNum, $sumBruto);
$sheet->getStyle($colTot . $rowNum)->getNumberFormat()->setFormatCode('#,##0.00" Bs."');
$rowNum++;
$sheet->setCellValue('A' . $rowNum, 'Total descuentos:');
$sheet->setCellValue($colTot . $rowNum, $sumDesc);
$sheet->getStyle($colTot . $rowNum)->getNumberFormat()->setFormatCode('#,##0.00" Bs."');
$rowNum++;
$sheet->setCellValue('A' . $rowNum, 'Total neto cobrado:');
$sheet->setCellValue($colTot . $rowNum, $sumTotal);
$sheet->getStyle('A' . $rowNum)->getFont()->setBold(true);
$sheet->getStyle($colTot . $rowNum)->getFont()->setBold(true);
$sheet->getStyle($colTot . $rowNum)->getNumberFormat()->setFormatCode('#,##0.00" Bs."');
$rowNum++;
$sheet->setCellValue('A' . $rowNum, 'Número de ventas: ' . $n);

excelAutoSize($sheet, $numCols);
excelDescargarXls($spreadsheet, 'reporte-ventas_' . $fechaInicio . '_' . $fechaFin . '.xls');
