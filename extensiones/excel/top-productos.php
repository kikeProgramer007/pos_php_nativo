<?php

require_once __DIR__ . '/_bootstrap.php';
require_once __DIR__ . '/../../controladores/ventas.controlador.php';
require_once __DIR__ . '/../../modelos/ventas.modelo.php';
require_once __DIR__ . '/../../controladores/usuarios.controlador.php';
require_once __DIR__ . '/../../modelos/usuarios.modelo.php';
require_once __DIR__ . '/../../controladores/productos.controlador.php';
require_once __DIR__ . '/../../modelos/productos.modelo.php';

date_default_timezone_set('America/La_Paz');

$fechaInicio = isset($_GET['fechaInicio']) ? trim((string) $_GET['fechaInicio']) : '';
$fechaFin = isset($_GET['fechaFin']) ? trim((string) $_GET['fechaFin']) : '';
$idUsuario = isset($_GET['idUsuario']) ? $_GET['idUsuario'] : 0;
$idCategoria = isset($_GET['idCategoria']) ? intval($_GET['idCategoria']) : 0;
$idMesero = (isset($_GET['idMesero']) && $_GET['idMesero'] !== '') ? intval($_GET['idMesero']) : null;
$formaAtencion = isset($_GET['formaAtencion']) ? $_GET['formaAtencion'] : '0';
$idProductos = isset($_GET['idProducto']) ? $_GET['idProducto'] : array();
if (!is_array($idProductos)) {
	$idProductos = ($idProductos === '' || $idProductos === null) ? array() : array($idProductos);
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

$idsLimpios = array();
foreach ($idProductos as $idProducto) {
	$idProducto = intval($idProducto);
	if ($idProducto > 0) {
		$idsLimpios[$idProducto] = $idProducto;
	}
}
$idsLimpios = array_values($idsLimpios);

if (count($idsLimpios) === 0) {
	$textoProductos = 'Todos los productos';
} elseif (count($idsLimpios) === 1) {
	$producto = ControladorProductos::ctrMostrarProductos('id', $idsLimpios[0], 'id');
	$textoProductos = is_array($producto) && isset($producto['descripcion']) ? $producto['descripcion'] : '1 producto';
} else {
	$textoProductos = count($idsLimpios) . ' productos';
}

$filas = ControladorVentas::ctrRangoFechasTopProductoMasVendidosPdf($fechaInicio, $fechaFin, $idCategoria, $idMesero, $idsLimpios, $formaAtencion);
if (!is_array($filas)) {
	$filas = [];
}

$columnas = 11;
list($spreadsheet, $sheet, $headerRow) = excelNuevoLibro(
	'Productos vendidos',
	'REPORTE DE PRODUCTOS VENDIDOS',
	[
		'Periodo: ' . date('d/m/Y', strtotime($fechaInicio)) . ' - ' . date('d/m/Y', strtotime($fechaFin)),
		'Usuario: ' . $nombreUsuario,
		'Producto: ' . $textoProductos,
		'Forma de atención: ' . $etiquetaFormaAtencion,
		'Generado: ' . date('d-m-Y h:i:s a'),
	],
	$columnas
);

excelEscribirCabeceras($sheet, ['#', 'Fecha', 'Mesero', 'Producto', 'F. Atención', 'Cantidad', 'Precio venta', 'Costo', 'Descuento', 'Venta neta', 'Ganancia'], $headerRow);

$rowNum = $headerRow + 1;
$n = 0;
$sumCantidad = 0.0;
$sumVenta = 0.0;
$sumCosto = 0.0;
$sumDescuento = 0.0;
$sumNeta = 0.0;
$sumGanancia = 0.0;
foreach ($filas as $item) {
	$n++;
	$cantidad = floatval($item['cantidad'] ?? 0);
	$precioVenta = floatval($item['precio_venta'] ?? 0);
	$costo = floatval($item['costo'] ?? 0);
	$descuento = floatval($item['descuento'] ?? 0);
	$ventaNeta = floatval($item['venta_neta'] ?? 0);
	$ganancia = floatval($item['ganancia'] ?? 0);

	$sheet->setCellValue('A' . $rowNum, $n);
	$sheet->setCellValue('B' . $rowNum, date('d/m/Y', strtotime($item['fecha'])));
	$sheet->setCellValue('C' . $rowNum, (string) ($item['mesero'] ?? 'Sin mesero'));
	$sheet->setCellValue('D' . $rowNum, (string) ($item['descripcion'] ?? ''));
	$sheet->setCellValue('E' . $rowNum, ControladorVentas::etiquetaFormaAtencion($item['forma_atencion'] ?? ''));
	$sheet->setCellValue('F' . $rowNum, $cantidad);
	$sheet->setCellValue('G' . $rowNum, $precioVenta);
	$sheet->setCellValue('H' . $rowNum, $costo);
	$sheet->setCellValue('I' . $rowNum, $descuento);
	$sheet->setCellValue('J' . $rowNum, $ventaNeta);
	$sheet->setCellValue('K' . $rowNum, $ganancia);

	$sumCantidad += $cantidad;
	$sumVenta += $precioVenta;
	$sumCosto += $costo;
	$sumDescuento += $descuento;
	$sumNeta += $ventaNeta;
	$sumGanancia += $ganancia;
	$rowNum++;
}

if ($n > 0) {
	excelBordesRango($sheet, $headerRow + 1, $rowNum - 1, 11);
	$sheet->getStyle('F' . ($headerRow + 1) . ':F' . ($rowNum - 1))->getNumberFormat()->setFormatCode('#,##0');
	$sheet->getStyle('G' . ($headerRow + 1) . ':K' . ($rowNum - 1))->getNumberFormat()->setFormatCode('#,##0.00');
}

$rowNum++;
$sheet->setCellValue('A' . $rowNum, 'Totales:');
$sheet->setCellValue('F' . $rowNum, $sumCantidad);
$sheet->setCellValue('G' . $rowNum, $sumVenta);
$sheet->setCellValue('H' . $rowNum, $sumCosto);
$sheet->setCellValue('I' . $rowNum, $sumDescuento);
$sheet->setCellValue('J' . $rowNum, $sumNeta);
$sheet->setCellValue('K' . $rowNum, $sumGanancia);
$sheet->getStyle('A' . $rowNum . ':K' . $rowNum)->getFont()->setBold(true);
$sheet->getStyle('F' . $rowNum)->getNumberFormat()->setFormatCode('#,##0');
$sheet->getStyle('G' . $rowNum . ':K' . $rowNum)->getNumberFormat()->setFormatCode('#,##0.00');

$rowNum += 2;
$resumen = array(
	array('Vendiste:', $sumNeta),
	array('Te costó:', $sumCosto),
	array('Te quedó (ganancia):', $sumGanancia),
);
foreach ($resumen as $linea) {
	$sheet->setCellValue('A' . $rowNum, $linea[0]);
	$sheet->setCellValue('B' . $rowNum, $linea[1]);
	$sheet->getStyle('A' . $rowNum)->getFont()->setBold(true);
	$sheet->getStyle('B' . $rowNum)->getFont()->setBold(true);
	$sheet->getStyle('B' . $rowNum)->getNumberFormat()->setFormatCode('#,##0.00" Bs."');
	$rowNum++;
}

excelAutoSize($sheet, 11);
excelDescargarXls($spreadsheet, 'productos-vendidos_' . $fechaInicio . '_' . $fechaFin . '.xls');
