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

$filas = ControladorVentas::ctrRangoFechasTopProductoMasVendidosPdf($fechaInicio, $fechaFin, $idCategoria, $idMesero, $idsLimpios);
if (!is_array($filas)) {
	$filas = [];
}

$columnas = 10;
list($spreadsheet, $sheet, $headerRow) = excelNuevoLibro(
	'Productos vendidos',
	'REPORTE DE PRODUCTOS VENDIDOS',
	[
		'Periodo: ' . date('d/m/Y', strtotime($fechaInicio)) . ' - ' . date('d/m/Y', strtotime($fechaFin)),
		'Usuario: ' . $nombreUsuario,
		'Producto: ' . $textoProductos,
		'Generado: ' . date('d-m-Y h:i:s a'),
	],
	$columnas
);

excelEscribirCabeceras($sheet, ['#', 'Fecha', 'Mesero', 'Producto', 'Cantidad', 'Precio venta', 'Costo', 'Descuento', 'Venta neta', 'Ganancia'], $headerRow);

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
	$sheet->setCellValue('E' . $rowNum, $cantidad);
	$sheet->setCellValue('F' . $rowNum, $precioVenta);
	$sheet->setCellValue('G' . $rowNum, $costo);
	$sheet->setCellValue('H' . $rowNum, $descuento);
	$sheet->setCellValue('I' . $rowNum, $ventaNeta);
	$sheet->setCellValue('J' . $rowNum, $ganancia);

	$sumCantidad += $cantidad;
	$sumVenta += $precioVenta;
	$sumCosto += $costo;
	$sumDescuento += $descuento;
	$sumNeta += $ventaNeta;
	$sumGanancia += $ganancia;
	$rowNum++;
}

if ($n > 0) {
	excelBordesRango($sheet, $headerRow + 1, $rowNum - 1, 10);
	$sheet->getStyle('E' . ($headerRow + 1) . ':E' . ($rowNum - 1))->getNumberFormat()->setFormatCode('#,##0');
	$sheet->getStyle('F' . ($headerRow + 1) . ':J' . ($rowNum - 1))->getNumberFormat()->setFormatCode('#,##0.00');
}

$rowNum++;
$sheet->setCellValue('A' . $rowNum, 'Totales:');
$sheet->setCellValue('E' . $rowNum, $sumCantidad);
$sheet->setCellValue('F' . $rowNum, $sumVenta);
$sheet->setCellValue('G' . $rowNum, $sumCosto);
$sheet->setCellValue('H' . $rowNum, $sumDescuento);
$sheet->setCellValue('I' . $rowNum, $sumNeta);
$sheet->setCellValue('J' . $rowNum, $sumGanancia);
$sheet->getStyle('A' . $rowNum . ':J' . $rowNum)->getFont()->setBold(true);
$sheet->getStyle('E' . $rowNum)->getNumberFormat()->setFormatCode('#,##0');
$sheet->getStyle('F' . $rowNum . ':J' . $rowNum)->getNumberFormat()->setFormatCode('#,##0.00');

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

excelAutoSize($sheet, 10);
excelDescargarXls($spreadsheet, 'productos-vendidos_' . $fechaInicio . '_' . $fechaFin . '.xls');
