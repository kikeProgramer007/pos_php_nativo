<?php

if (!function_exists('etiquetaPeriodoGrafico')) {
	function etiquetaPeriodoGrafico($fechaInicial, $fechaFinal)
	{
		$meses = array(
			1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril',
			5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto',
			9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre'
		);
		$ini = DateTime::createFromFormat('Y-m-d', (string) $fechaInicial);
		$fin = DateTime::createFromFormat('Y-m-d', (string) $fechaFinal);
		if (!$ini || !$fin) {
			return '';
		}

		$mesIni = $meses[(int) $ini->format('n')];
		$mesFin = $meses[(int) $fin->format('n')];

		if ($ini->format('Y-m-d') === $fin->format('Y-m-d')) {
			return $ini->format('j') . ' de ' . $mesIni . ' de ' . $ini->format('Y');
		}
		if ($ini->format('Y-m') === $fin->format('Y-m')) {
			if ($ini->format('d') === '01' && $fin->format('d') === $fin->format('t')) {
				return ucfirst($mesFin) . ' ' . $fin->format('Y');
			}
			return 'del ' . $ini->format('j') . ' al ' . $fin->format('j') . ' de ' . $mesFin . ' de ' . $fin->format('Y');
		}

		return $ini->format('d/m/Y') . ' al ' . $fin->format('d/m/Y');
	}
}
