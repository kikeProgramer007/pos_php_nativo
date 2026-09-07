<?php

require_once __DIR__ . '/_bootstrap.php';
require_once __DIR__ . '/../../controladores/productos.controlador.php';
require_once __DIR__ . '/../../modelos/productos.modelo.php';
require_once __DIR__ . '/../../controladores/categorias.controlador.php';
require_once __DIR__ . '/../../modelos/categorias.modelo.php';
require_once __DIR__ . '/../../controladores/usuarios.controlador.php';
require_once __DIR__ . '/../../modelos/usuarios.modelo.php';

date_default_timezone_set('America/La_Paz');

$idCategoria = isset($_GET['idCategoria']) ? $_GET['idCategoria'] : 0;
$idUsuario = isset($_GET['idUsuario']) ? $_GET['idUsuario'] : 0;

$usuario = ControladorUsuarios::ctrMostrarUsuarios('id', $idUsuario);
$nombreUsuario = is_array($usuario) && isset($usuario['nombre']) ? $usuario['nombre'] : '';

$nombreCategoria = 'Todas';
if (!empty($idCategoria) && (int) $idCategoria > 0) {
	$cat = ControladorCategorias::ctrMostrarCategorias('id', $idCategoria);
	if (is_array($cat) && isset($cat['categoria'])) {
		$nombreCategoria = $cat['categoria'];
	}
}

$filas = ControladorProductos::ctrMostrarProductosSegunCategoria($idCategoria);
if (!is_array($filas)) {
	$filas = [];
}

list($spreadsheet, $sheet, $headerRow) = excelNuevoLibro(
	'Productos',
	'REPORTE DE PRODUCTOS POR CATEGORÍA',
	[
		'Categoría: ' . $nombreCategoria,
		'Usuario: ' . $nombreUsuario,
		'Generado: ' . date('d-m-Y h:i:s a'),
	],
	6
);

excelEscribirCabeceras($sheet, ['#', 'Código', 'Nombre', 'Precio Compra', 'Precio Venta', 'Stock'], $headerRow);

$rowNum = $headerRow + 1;
$n = 0;
foreach ($filas as $producto) {
	$n++;
	$sheet->setCellValue('A' . $rowNum, $n);
	$sheet->setCellValue('B' . $rowNum, (string) ($producto['codigo'] ?? ''));
	$sheet->setCellValue('C' . $rowNum, (string) ($producto['descripcion'] ?? ''));
	$sheet->setCellValue('D' . $rowNum, floatval($producto['precio_compra'] ?? 0));
	$sheet->setCellValue('E' . $rowNum, floatval($producto['precio_venta'] ?? 0));
	$sheet->setCellValue('F' . $rowNum, floatval($producto['stock'] ?? 0));
	$rowNum++;
}

if ($n > 0) {
	excelBordesRango($sheet, $headerRow + 1, $rowNum - 1, 6);
	$sheet->getStyle('D' . ($headerRow + 1) . ':E' . ($rowNum - 1))->getNumberFormat()->setFormatCode('#,##0.00');
}

$rowNum++;
$sheet->setCellValue('A' . $rowNum, 'Número de productos: ' . $n);

excelAutoSize($sheet, 6);
excelDescargarXls($spreadsheet, 'reporte-productos-categoria_' . $idCategoria . '.xls');
