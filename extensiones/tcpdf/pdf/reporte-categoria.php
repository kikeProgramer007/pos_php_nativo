<?php

require_once "../../../controladores/ventas.controlador.php";
require_once "../../../modelos/ventas.modelo.php";

require_once "../../../controladores/categorias.controlador.php";
require_once "../../../modelos/categorias.modelo.php";

require_once "../../../controladores/productos.controlador.php";
require_once "../../../modelos/productos.modelo.php";

require_once "../../../controladores/usuarios.controlador.php";
require_once "../../../modelos/usuarios.modelo.php";

require_once "../../../fpdf/fpdf.php";

class reporteProductoPorCategoria
{
    public $idCategoria;
    public $idUsuario;
    private $nombreTienda = "Pollos Rosy";
    private $direccionTienda = "Refineria";

    public function generarPdfProductoPorCategoria()
    {
        // Establecer la zona horaria de Bolivia
        date_default_timezone_set('America/La_Paz');
        $idCategoria = $this->idCategoria;
        $idUsuario = $this->idUsuario;

        $respuestaProductos = ControladorProductos::ctrMostrarProductosSegunCategoria($idCategoria);

        $itemUsuario = "id";
        $respuestaUsuario = ControladorUsuarios::ctrMostrarUsuarios($itemUsuario, $idUsuario);

        // Traemos la información de la categoría
        $itemCategoria = "id";
        if ($idCategoria != 0) {
            $respuestaCategoria = ControladorCategorias::ctrMostrarCategorias($itemCategoria, $idCategoria);
        } else {
            $respuestaCategoria["categoria"] = "Todos";
        }

        $DateAndTime = date('d-m-Y h:i:s a', time());

        $pdf = new FPDF('P', 'mm', 'letter');
        $pdf->AddPage();
        $pdf->SetMargins(12, 12, 12);
        $pdf->SetTitle (utf8_decode("Reporte De Producto Según Categoría"));

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(0, 5, utf8_decode('Reporte De Producto Según Categoría'), 0, 1, 'C');

        $pdf->Image('images/logo-negro-bloque.jpg', 90, 25, 30, 20, 'jpg');

        $pdf->Ln(10); // Espacio después de la imagen
        $pdf->SetX(140); // Ajusta este valor según sea necesario ancho
        $pdf->SetY(30);  // Ajusta este valor según sea necesario altura
            // Nombre y dirección del restaurante
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->Cell(23, 5, 'Restaurante:', 0, 0, 'L');
            $pdf->SetFont('helvetica', '', 9);
            $pdf->Cell(23, 5, utf8_decode($this->nombreTienda), 0, 1, 'L'); 
    
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->Cell(23, 5, utf8_decode('Dirección:'), 0, 0, 'L'); // Convierte el texto a formato compatible con FPDF
            $pdf->SetFont('helvetica', '', 9);
            $pdf->Cell(50, 5, $this->direccionTienda, 0, 1, 'L');

        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(23, 5, 'Usuario: ', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(50, 5, $respuestaUsuario["nombre"], 0, 1, 'L');
        $pdf->SetY(35);  // Ajusta este valor según sea necesario altura
        $pdf->SetX(140); // Ajusta este valor según sea necesario ancho
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(23, 5, 'Categoria: ', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(50,5,$respuestaCategoria["categoria"],0,1,'L');

        $DateAndTime = date('d-m-Y h:i:s a', time());
        
        $pdf->SetX(140); // Ajusta este valor según sea necesario ancho
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(23, 5, utf8_decode('Fecha y hora:'), 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(50, 5, $DateAndTime, 0, 1, 'L');
        
        $pdf->Ln(5);

        // Títulos de las columnas
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(12, 5, utf8_decode('Nº'), 1, 0, 'L');
        $pdf->Cell(28, 5, utf8_decode('Ticket'), 1, 0, 'L');
        $pdf->Cell(72, 5, utf8_decode('Nombre'), 1, 0, 'L');
        $pdf->Cell(26, 5, utf8_decode('Precio Venta'), 1, 0, 'L');
        $pdf->Cell(30, 5, utf8_decode('Precio Compra'), 1, 0, 'L');
        $pdf->Cell(24, 5, utf8_decode('Stock'), 1, 1, 'L');

        $pdf->SetFont('Arial', '', 11);

        $contador = 1;
        $sum = 0;
        $cortar = 47;
        $pdf->SetAutoPageBreak(false);

        // Detalles de los productos
        foreach ($respuestaProductos as $producto) {
            $pdf->Cell(12, 5, $contador, 1, 0, 'L');
            $pdf->Cell(28, 5, utf8_decode($producto['codigo']), 1, 0, 'L');
            $pdf->Cell(72, 5, utf8_decode($producto['descripcion']), 1, 0, 'L');
            $pdf->Cell(26, 5, utf8_decode($producto['precio_venta']), 1, 0, 'L');
            $pdf->Cell(30, 5, utf8_decode($producto['precio_compra']), 1, 0, 'L');
            $pdf->Cell(24, 5, utf8_decode($producto['stock']), 1, 1, 'L');
            $contador++;

            if ($contador == $cortar) {
                $pdf->AddPage();
                $cortar = $cortar + 50;
            }
        }

        $pdf->Ln();

        // Salida del archivo PDF
        $pdf->Output('factura.pdf', 'I');
    }
}

$factura = new reporteProductoPorCategoria();
$factura->idCategoria = $_GET["idCategoria"];
$factura->idUsuario = $_GET["idUsuario"];
$factura->generarPdfProductoPorCategoria();
