<?php

require_once "../../../controladores/gastos.controlador.php";
require_once "../../../modelos/gastos.modelo.php";
require_once "../../../controladores/tipo_gasto.controlador.php";
require_once "../../../modelos/tipo_gasto.modelo.php";
require_once "../../../controladores/usuarios.controlador.php";
require_once "../../../modelos/usuarios.modelo.php";

class reporteGastos
{
    public $fechaInicio;
    public $fechaFin;
    public $idTipoGasto;
    public $formaPago;
    public $idUsuarioFiltro;
    public $idUsuario;

    private $nombreTienda = "El Gato Rico ";
    private $direccionTienda = "Heroes Del Chaco 9,Cotoca";

    private function etiquetaFormaPago($valor)
    {
        $mapa = [
            0 => "Todas",
            1 => "Efectivo",
            2 => "QR",
            3 => "Transferencia",
            4 => "QR y Efectivo (Mixto)"
        ];
        $clave = intval($valor);
        return $mapa[$clave] ?? "Todas";
    }

    public function generarPdfGastos()
    {
        date_default_timezone_set('America/La_Paz');

        $fechaInicio = $this->fechaInicio;
        $fechaFin = $this->fechaFin;
        $idTipoGasto = intval($this->idTipoGasto);
        $formaPago = intval($this->formaPago);
        $idUsuarioFiltro = intval($this->idUsuarioFiltro);
        $idUsuario = intval($this->idUsuario);

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaInicio) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaFin)) {
            echo "Fechas no válidas.";
            return;
        }

        if ($fechaInicio > $fechaFin) {
            echo "La fecha de inicio no puede ser mayor a la fecha fin.";
            return;
        }

        $gastos = ControladorGastos::ctrReporteGastosEntreFechas(
            $fechaInicio,
            $fechaFin,
            $idTipoGasto,
            $formaPago,
            $idUsuarioFiltro
        );

        $respuestaUsuario = ControladorUsuarios::ctrMostrarUsuarios("id", $idUsuario);
        $nombreUsuarioSesion = $respuestaUsuario["nombre"] ?? "Usuario";

        if ($idTipoGasto > 0) {
            $tipo = ControladorTipoGasto::ctrMostrarTipoGasto("id", $idTipoGasto);
            $nombreTipo = $tipo["nombre"] ?? "Todos";
        } else {
            $nombreTipo = "Todos";
        }

        if ($idUsuarioFiltro > 0) {
            $usuarioFiltro = ControladorUsuarios::ctrMostrarUsuarios("id", $idUsuarioFiltro);
            $nombreUsuarioFiltro = $usuarioFiltro["nombre"] ?? "Todos";
        } else {
            $nombreUsuarioFiltro = "Todos";
        }

        $nombreFormaPago = $this->etiquetaFormaPago($formaPago);

        require_once('tcpdf_include.php');

        $pdf = new TCPDF('P', 'mm', 'LETTER', true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetTitle('Reporte de Gastos');
        $pdf->AddPage();

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 5, 'REPORTE DE GASTOS ENTRE FECHAS', 0, 1, 'C');
        $pdf->Image('images/logo-negro-bloque.jpg', 90, 25, 30, 20, 'jpg');
        $pdf->Ln(10);

        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(23, 5, 'Restaurante:', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(50, 5, $this->nombreTienda, 0, 1, 'L');

        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(23, 5, 'Dirección:', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(50, 5, $this->direccionTienda, 0, 1, 'L');

        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(23, 5, 'Usuario: ', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(50, 5, $nombreUsuarioSesion, 0, 1, 'L');

        $pdf->SetY(30);
        $pdf->SetX(140);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(28, 5, 'Tipo gasto: ', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(40, 5, $nombreTipo, 0, 1, 'L');

        $pdf->SetX(140);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(28, 5, 'Forma pago: ', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(40, 5, $nombreFormaPago, 0, 1, 'L');

        $pdf->SetX(140);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(28, 5, 'Registró: ', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(40, 5, $nombreUsuarioFiltro, 0, 1, 'L');

        $pdf->SetX(140);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(28, 5, 'Periodo: ', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(60, 5, date("d-m-Y", strtotime($fechaInicio)) . " al " . date("d-m-Y", strtotime($fechaFin)), 0, 1, 'L');

        $DateAndTime = date('d-m-Y h:i:s a', time());
        $pdf->SetY(40);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(23, 5, 'Fecha y hora:', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(50, 5, $DateAndTime, 0, 1, 'L');

        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->SetFillColor(0, 0, 0);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(0, 5, 'Detalle de gastos', 1, 1, 'C', 1);
        $pdf->SetTextColor(0, 0, 0);

        $pdf->Cell(10, 5, '#', 1, 0, 'C');
        $pdf->Cell(22, 5, 'Fecha', 1, 0, 'C');
        $pdf->Cell(32, 5, 'Tipo', 1, 0, 'C');
        $pdf->Cell(50, 5, 'Descripción', 1, 0, 'C');
        $pdf->Cell(28, 5, 'Forma pago', 1, 0, 'C');
        $pdf->Cell(30, 5, 'Usuario', 1, 0, 'C');
        $pdf->Cell(24, 5, 'Monto', 1, 1, 'C');
        $pdf->SetFont('helvetica', '', 8);

        $contador = 0;
        $sumTotal = 0;

        if (!$gastos || count($gastos) === 0) {
            $pdf->Cell(196, 8, 'No se encontraron gastos para los filtros seleccionados.', 1, 1, 'C');
        } else {
            foreach ($gastos as $item) {
                $contador++;
                $monto = floatval($item["monto"]);
                $sumTotal += $monto;

                $descripcion = $item["descripcion"] ?? "";
                if (function_exists("mb_substr") && function_exists("mb_strlen")) {
                    if (mb_strlen($descripcion) > 34) {
                        $descripcion = mb_substr($descripcion, 0, 34) . "...";
                    }
                } elseif (strlen($descripcion) > 34) {
                    $descripcion = substr($descripcion, 0, 34) . "...";
                }

                $tipoNombre = $item["nombre_tipo_gasto"] ?? "";
                if (function_exists("mb_substr") && function_exists("mb_strlen")) {
                    if (mb_strlen($tipoNombre) > 18) {
                        $tipoNombre = mb_substr($tipoNombre, 0, 18) . "...";
                    }
                } elseif (strlen($tipoNombre) > 18) {
                    $tipoNombre = substr($tipoNombre, 0, 18) . "...";
                }

                $usuarioNombre = $item["nombre_usuario"] ?? "";
                if (function_exists("mb_substr") && function_exists("mb_strlen")) {
                    if (mb_strlen($usuarioNombre) > 16) {
                        $usuarioNombre = mb_substr($usuarioNombre, 0, 16) . "...";
                    }
                } elseif (strlen($usuarioNombre) > 16) {
                    $usuarioNombre = substr($usuarioNombre, 0, 16) . "...";
                }

                $pdf->Cell(10, 5, $contador, 1, 0, 'C');
                $pdf->Cell(22, 5, date("d-m-Y", strtotime($item["fecha"])), 1, 0, 'C');
                $pdf->Cell(32, 5, $tipoNombre, 1, 0, 'L');
                $pdf->Cell(50, 5, $descripcion, 1, 0, 'L');
                $pdf->Cell(28, 5, $item["forma_pago_descripcion"] ?? "", 1, 0, 'C');
                $pdf->Cell(30, 5, $usuarioNombre, 1, 0, 'L');
                $pdf->Cell(24, 5, number_format($monto, 2, '.', ',') . ' Bs', 1, 1, 'R');
            }
        }

        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(172, 5, 'Total ', 0, 0, 'R');
        $pdf->Cell(24, 5, number_format($sumTotal, 2, '.', ',') . ' Bs.', 1, 1, 'R');

        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(30, 5, 'Número de gastos:  ' . $contador, 0, 0, 'L');

        $pdf->Output('ReporteDeGastos.pdf', 'I');
    }
}

$factura = new reporteGastos();
$factura->fechaInicio = $_GET["fechaInicio"] ?? "";
$factura->fechaFin = $_GET["fechaFin"] ?? "";
$factura->idTipoGasto = $_GET["idTipoGasto"] ?? 0;
$factura->formaPago = $_GET["formaPago"] ?? 0;
$factura->idUsuarioFiltro = $_GET["idUsuarioFiltro"] ?? 0;
$factura->idUsuario = $_GET["idUsuario"] ?? 0;
$factura->generarPdfGastos();
