<?php

require_once "../../../controladores/compras.controlador.php";
require_once "../../../modelos/compras.modelo.php";

require_once "../../../controladores/proveedor.controlador.php";
require_once "../../../modelos/proveedor.modelo.php";

require_once "../../../controladores/usuarios.controlador.php";
require_once "../../../modelos/usuarios.modelo.php";

require_once "../../../controladores/productos.controlador.php";
require_once "../../../modelos/productos.modelo.php";

class imprimirCompra {

    public $codigo;

    public function traerImpresionCompra() {

        // TRAEMOS LA INFORMACIÓN DE LA VENTA
        $itemCompra = "codigo";
        $valorCompra = $this->codigo;
        $respuestaCompra = ControladorCompras::ctrMostrarCompras($itemCompra, $valorCompra);
        if($respuestaCompra!=null){
        $detalleCompra = ControladorCompras::ctrMostrarDetalleCompras($respuestaCompra['id']);

        // Convertir la fecha y hora a un objeto DateTime
        $fechaHoraObj = new DateTime($respuestaCompra["fecha_alta"]);

        // Obtener fecha y hora por separado
        $fechaSolo = $fechaHoraObj->format('d/m/Y');
        $horaSolo = $fechaHoraObj->format('H:i a');
   
        // Formatear la fecha y hora
        $fechaFormateada = $fechaHoraObj->format('d/m/Y h:i A');

        // Limpiar y convertir el total a float
        $total = str_replace(',', '', $respuestaCompra["total"]); 
        $total = (float) $total;
        $total = number_format($total, 2, '.', ',');

        // TRAEMOS LA INFORMACIÓN DEL MESERO
        $itemUsuario = "id";
        $valorUsuario = $respuestaCompra["id_usuario"];
        $respuestaUsuario = ControladorUsuarios::ctrMostrarUsuariosActivoInactivo($itemUsuario, $valorUsuario);

        // TRAEMOS LA INFORMACIÓN DEL PROVEEDOR
        $itemVendedor = "id";
        $valorVendedor = $respuestaCompra["id_proveedor"];
        $respuestaProveedor = ControladorProveedors::ctrMostrarProveedorsActivosInactivos($itemVendedor, $valorVendedor);

        // REQUERIMOS LA CLASE TCPDF
        require_once('tcpdf_include.php');

        // Configuración del PDF para UTF-8
        $pdf = new TCPDF('P', 'mm', 'LETTER', true, 'UTF-8', false);

        // Desactivar encabezado y pie de página
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Ajustar márgenes a cero
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetTitle('Compra de productos');
        $pdf->AddPage();

        // Definir variables de restaurante y dirección
        $nombreTienda = "Pollos Rossy"; // Puedes cambiarlo por una variable si lo deseas
        $direccionTienda = "Refineria"; // Puedes cambiarlo por una variable si lo deseas

        // --- ENCABEZADO TIPO TABLA DE TRES COLUMNAS ---
        $y_inicial = $pdf->GetY();

        // RECIBO N° GRANDE Y AL PRINCIPIO
        $pdf->SetXY(10, $y_inicial);
        $pdf->SetFont('helvetica', 'B', 15);
        $pdf->Cell(0, 10, 'Recibo N°: ' . $respuestaCompra["codigo"], 0, 1, 'L');
        $y_inicial = $pdf->GetY(); // Actualizar Y para el resto del encabezado

        // COLUMNA IZQUIERDA
        $pdf->SetXY(10, $y_inicial);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(28, 5, 'Restaurante:', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(42, 5, $nombreTienda, 0, 1, 'L');

        $pdf->SetX(10);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(28, 5, 'Dirección:', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(42, 5, $direccionTienda, 0, 1, 'L');

        $pdf->SetX(10);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(28, 5, 'Proveedor:', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(42, 5, $respuestaProveedor["nombre"], 0, 1, 'L');

        $pdf->SetX(10);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(28, 5, 'Concepto:', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(42, 5, 'Compra de productos', 0, 1, 'L');

        // COLUMNA CENTRAL (LOGO)
        $pdf->SetXY(100, $y_inicial);
        $pdf->Image('images/logo-negro-bloque.jpg', 100, $y_inicial, 25, 20, 'jpg', '', 'T', false, 300, '', false, false, 0, false, false, false);

        // COLUMNA DERECHA
        $pdf->SetXY(150, $y_inicial);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(20, 5, 'Usuario:', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(40, 5, $respuestaUsuario["nombre"], 0, 1, 'L');

        $pdf->SetX(150);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(20, 5, 'Fecha:', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(40, 5, $fechaSolo, 0, 1, 'L');

        $pdf->SetX(150);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(20, 5, 'Hora:', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(40, 5, $horaSolo, 0, 1, 'L');

        // Salto de línea para dejar espacio después del encabezado
        $pdf->Ln(10);

        // Título centrado
        $pdf->SetFont('helvetica', 'B', 11);
   
        $pdf->Ln(5);

        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->SetFillColor(0, 0, 0);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(0, 5, 'Detalle De roductos Comprados', 1, 1, 'C', 1);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(14, 5, '#', 1, 0, 'L');
        $pdf->Cell(90, 5, 'Nombre', 1, 0, 'L');
        $pdf->Cell(28, 5, 'Precio', 1, 0, 'C');
        $pdf->Cell(25, 5, 'Cantidad', 1, 0, 'C');
        $pdf->Cell(39, 5, 'Subtotal', 1, 1, 'C');
        $pdf->SetFont('helvetica', '', 8);

        // Imprimir los detalles de los productos
        $contador = 1;
        $totalFilas = count($detalleCompra);
        foreach ($detalleCompra as $index => $item) {
            $descripcion = isset($item["producto"]) ? $item["producto"] : '';
            $precio = isset($item["precio_compra"]) ? str_replace(',', '', $item["precio_compra"]) : 0;
            $precio = (float) $precio;
            $importe = number_format($precio * $item["cantidad"], 2, '.', ',');

            // Imprimir el número de ítem
            $pdf->Cell(14, 5, $contador, 1, 0, 'L');

            $pdf->Cell(90, 5, $descripcion, 1, 0, 'L');
            $pdf->Cell(28, 5, number_format($precio, 2, '.', ','). ' Bs', 1, 0, 'C');
            $pdf->Cell(25, 5, $item["cantidad"], 1, 0, 'C');
            $pdf->Cell(39, 5, $importe . ' Bs', 1, 1, 'C');
            $contador++;
        }

        // Total de la compra
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(166, 5, 'Total ', 0, 0, 'R');
        $pdf->Cell(30, 5, $total . ' Bs.', 1, 1, 'R');

        // Nro de compras
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(30, 5, 'Número Total De Compras:  ' . ($contador - 1), 0, 0, 'L');

        $pdf->Output('factura.pdf', 'I');
    }else{
   
        echo'<script>
                window.location = "../../../crear-venta";
            </script>'; 

    }
}
}
$factura = new imprimirCompra();
$factura->codigo = $_GET["codigo"];
$factura->traerImpresionCompra();

?>