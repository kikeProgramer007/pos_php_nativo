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

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(0, 5, 'Compra de productos', 0, 1, 'C');
        $pdf->Image('images/logo-negro-bloque.jpg', 185, 10, 18, 16, 'jpg', '', 'T', false, 300, '', false, false, 0, false, false, false);

        $pdf->Ln(10); // Espacio después de la imagen
  
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(23, 5, 'Cajero/a: ', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(50, 5, $respuestaUsuario["nombre"], 0, 1, 'L');

        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(23, 5, 'Proveedor: ', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(50, 5, $respuestaProveedor["nombre"], 0, 1, 'L');

        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(23, 5, 'Fecha: ', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(50, 5, $fechaFormateada, 0, 1, 'L');

        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->SetFillColor(0, 0, 0);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(0, 5, 'Detalle de productos', 1, 1, 'C', 1);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(14, 5, 'No', 1, 0, 'L');
        $pdf->Cell(22, 5, 'Recibo N°', 1, 0, 'L'); 
        $pdf->Cell(80, 5, 'Nombre', 1, 0, 'L');
        $pdf->Cell(25, 5, 'Precio', 1, 0, 'L');
        $pdf->Cell(25, 5, 'Cantidad', 1, 0, 'L');
        $pdf->Cell(30, 5, 'Subtotal', 1, 1, 'L');
        $pdf->SetFont('helvetica', '', 8);

        // Imprimir los detalles de los productos
        $contador = 1;
        foreach ($detalleCompra as $item) {

            $descripcion = isset($item["producto"]) ? $item["producto"] : '';
            $precio = isset($item["precio_compra"]) ? str_replace(',', '', $item["precio_compra"]) : 0; // Asignar 0 si no está definido
            $precio = (float) $precio; // Convertir a float
            $importe = number_format($precio * $item["cantidad"], 2, '.', ',');
            $pdf->Cell(14, 5, $contador, 1, 0, 'L');
            $pdf->Cell(22, 5, $respuestaCompra["codigo"], 1, 0, 'L'); 
            $pdf->Cell(80, 5, $descripcion, 1, 0, 'L');
            $pdf->Cell(25, 5, number_format($precio, 2, '.', ','), 1, 0, 'L');
            $pdf->Cell(25, 5, $item["cantidad"], 1, 0, 'L');
            $pdf->Cell(30, 5, $importe, 1, 1, 'R');
            $contador++;
        }

        // Total de la compra
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(166, 5, 'Total ', 0, 0, 'R');
        $pdf->Cell(30, 5, $total . ' Bs.', 1, 1, 'R');

        // Nro de compras
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(30, 5, 'Número de compras:  ' . ($contador - 1), 0, 0, 'L');

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