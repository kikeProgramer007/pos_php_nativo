<?php

/**
 * Dibuja una fila de tabla. Si el texto no cabe, la fila crece hacia abajo
 * y todas las celdas quedan con la misma altura.
 *
 * $celdas: [ ['w' => mm, 'txt' => '', 'align' => 'L'], ... ]
 * $alSalto: se llama después de abrir una página nueva, para repetir el encabezado.
 */
function pdfDibujarFila($pdf, $celdas, $altoLinea = 5, $alSalto = null)
{
	$paddings = $pdf->getCellPaddings();
	$relleno = 1;
	$pdf->setCellPaddings($relleno, $relleno, $relleno, $relleno);

	$alto = $altoLinea;
	foreach ($celdas as $celda) {
		$altoTexto = $pdf->getStringHeight($celda['w'], (string) $celda['txt'], false, true, '', 0);
		if ($altoTexto > $alto) {
			$alto = $altoTexto;
		}
	}

	$limite = $pdf->getPageHeight() - $pdf->getBreakMargin();
	if ($pdf->GetY() + $alto > $limite) {
		$pdf->setCellPaddings($paddings['L'], $paddings['T'], $paddings['R'], $paddings['B']);
		$pdf->AddPage();
		if (is_callable($alSalto)) {
			$alSalto($pdf);
		}
		$pdf->setCellPaddings($relleno, $relleno, $relleno, $relleno);
	}

	$x = $pdf->GetX();
	$y = $pdf->GetY();
	$xInicio = $x;
	foreach ($celdas as $celda) {
		$pdf->MultiCell(
			$celda['w'],
			$alto,
			(string) $celda['txt'],
			isset($celda['border']) ? $celda['border'] : 1,
			isset($celda['align']) ? $celda['align'] : 'L',
			!empty($celda['fill']),
			0,
			$x,
			$y,
			true,
			0,
			false,
			true,
			0,
			'M'
		);
		$x += $celda['w'];
	}
	$pdf->setCellPaddings($paddings['L'], $paddings['T'], $paddings['R'], $paddings['B']);
	$pdf->SetXY($xInicio, $y + $alto);
}

function fpdfPartirTexto($pdf, $ancho, $texto)
{
	$texto = str_replace("\r", '', (string) $texto);
	$usable = $ancho - 2;
	if ($usable < 1) {
		$usable = 1;
	}
	$salida = '';
	$partes = preg_split('/(\s+)/', $texto, -1, PREG_SPLIT_DELIM_CAPTURE);
	foreach ($partes as $parte) {
		if ($parte === '' || preg_match('/^\s+$/', $parte)) {
			$salida .= $parte;
			continue;
		}
		while ($pdf->GetStringWidth($parte) > $usable && strlen($parte) > 1) {
			$corte = 1;
			$largo = strlen($parte);
			while ($corte < $largo && $pdf->GetStringWidth(substr($parte, 0, $corte + 1)) <= $usable) {
				$corte++;
			}
			$salida .= substr($parte, 0, $corte) . "\n";
			$parte = substr($parte, $corte);
		}
		$salida .= $parte;
	}
	return $salida;
}

function fpdfLineasTexto($pdf, $ancho, $texto)
{
	$texto = fpdfPartirTexto($pdf, $ancho, $texto);
	if ($texto === '') {
		return 1;
	}
	return substr_count($texto, "\n") + 1;
}

function fpdfDibujarFila($pdf, $celdas, $altoLinea = 5)
{
	$relleno = 1;
	$paso = 4;
	$lineasPorCelda = array();
	$lineasMax = 1;
	foreach ($celdas as $i => $celda) {
		$celdas[$i]['txt'] = fpdfPartirTexto($pdf, $celda['w'] - ($relleno * 2), $celda['txt']);
		$lineas = substr_count($celdas[$i]['txt'], "\n") + 1;
		if ($celdas[$i]['txt'] === '') {
			$lineas = 1;
		}
		$lineasPorCelda[$i] = $lineas;
		$lineasMax = max($lineasMax, $lineas);
	}
	$alto = max($altoLinea, ($lineasMax * $paso) + ($relleno * 2));
	$altoTexto = $paso;

	$limite = $pdf->GetPageHeight() - 18;
	if ($pdf->GetY() + $alto > $limite) {
		$pdf->AddPage();
	}

	$x = $pdf->GetX();
	$y = $pdf->GetY();
	$x0 = $x;
	foreach ($celdas as $i => $celda) {
		$pdf->Rect($x, $y, $celda['w'], $alto);
		$espacio = ($alto - ($lineasPorCelda[$i] * $altoTexto)) / 2;
		if ($espacio < 0) {
			$espacio = 0;
		}
		$pdf->SetXY($x, $y + $espacio);
		$pdf->MultiCell($celda['w'], $altoTexto, (string) $celda['txt'], 0, isset($celda['align']) ? $celda['align'] : 'L');
		$x += $celda['w'];
	}
	$pdf->SetXY($x0, $y + $alto);
}
