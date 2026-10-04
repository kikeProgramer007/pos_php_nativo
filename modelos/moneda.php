<?php

/**
 * Montos en centavos. Evita que un float se vea o se sume distinto en cada pantalla.
 */
class Moneda {

	static public function texto($valor) {
		$centavos = self::centavos($valor);
		return self::desdeCentavos($centavos);
	}

	static public function centavos($valor) {
		if ($valor === null || $valor === "") {
			return 0;
		}

		$texto = trim((string) $valor);
		$negativo = isset($texto[0]) && $texto[0] === "-";
		if ($negativo) {
			$texto = substr($texto, 1);
		}

		$texto = str_replace(",", "", $texto);
		if (!is_numeric($texto)) {
			return 0;
		}

		if (strpos($texto, ".") === false) {
			$centavos = intval($texto) * 100;
		} else {
			$partes = explode(".", $texto, 2);
			$enteros = intval($partes[0]);
			$decimales = substr(str_pad($partes[1], 3, "0"), 0, 3);
			$centavos = ($enteros * 100) + intval(substr($decimales, 0, 2));
			if (intval(substr($decimales, 2, 1)) >= 5) {
				$centavos++;
			}
		}

		return $negativo ? -$centavos : $centavos;
	}

	static public function desdeCentavos($centavos) {
		$centavos = intval($centavos);
		$negativo = $centavos < 0;
		$centavos = abs($centavos);
		$texto = intdiv($centavos, 100) . "." . str_pad((string) ($centavos % 100), 2, "0", STR_PAD_LEFT);
		return $negativo ? "-" . $texto : $texto;
	}

	/**
	 * En una venta cobrada, efectivo + QR queda igual al total.
	 * Si falta un centavo por el redondeo, se ajusta la parte que ya tiene dinero.
	 */
	static public function cuadrarPago($total, $efectivo, $qr, $cuadrarPartes = true) {
		$totalC = self::centavos($total);
		$efectivoC = self::centavos($efectivo);
		$qrC = self::centavos($qr);

		if ($cuadrarPartes && ($efectivoC !== 0 || $qrC !== 0)) {
			$diferencia = $totalC - ($efectivoC + $qrC);
			if ($efectivoC !== 0 || $qrC === 0) {
				$efectivoC += $diferencia;
			} else {
				$qrC += $diferencia;
			}
		}

		return [
			"total" => self::desdeCentavos($totalC),
			"total_efectivo" => self::desdeCentavos($efectivoC),
			"total_qr" => self::desdeCentavos($qrC)
		];
	}
}
