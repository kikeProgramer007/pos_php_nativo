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

class reporteProductoPorCategoria extends FPDF
{
    public $idCategoria;
    public $idUsuario;
    private $nombreTienda = "Pollos Rosy";
    private $direccionTienda = "Refineria";
    private $respuestaUsuario;
    private $respuestaCategoria;
    private $DateAndTime;

    // Sobrescribimos el método Header para que se repita en cada página
    function Header()
    {
        $this->SetMargins(12, 12, 12);
        $this->SetTitle(utf8_decode("Reporte De Producto Según Categoría"));
        $this->SetFont('helvetica', 'B', 11);
        $this->Cell(0, 5, utf8_decode('Reporte De Producto Según Categoría'), 0, 1, 'C');
        $this->Image('images/logo-negro-bloque.jpg', 90, 25, 30, 20, 'jpg');
        $this->Ln(10);
        $this->SetX(140);
        $this->SetY(30);
        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(23, 5, 'Restaurante:', 0, 0, 'L');
        $this->SetFont('helvetica', '', 9);
        $this->Cell(23, 5, utf8_decode($this->nombreTienda), 0, 1, 'L');
        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(23, 5, utf8_decode('Dirección:'), 0, 0, 'L');
        $this->SetFont('helvetica', '', 9);
        $this->Cell(50, 5, $this->direccionTienda, 0, 1, 'L');
        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(23, 5, 'Usuario: ', 0, 0, 'L');
        $this->SetFont('helvetica', '', 9);
        $this->Cell(50, 5, $this->respuestaUsuario["nombre"], 0, 1, 'L');
        $this->SetY(35);
        $this->SetX(140);
        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(23, 5, 'Categoria: ', 0, 0, 'L');
        $this->SetFont('helvetica', '', 9);
        $this->Cell(50, 5, $this->respuestaCategoria["categoria"], 0, 1, 'L');
        $this->SetX(140);
        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(23, 5, utf8_decode('Fecha y hora:'), 0, 0, 'L');
        $this->SetFont('helvetica', '', 9);
        $this->Cell(50, 5, $this->DateAndTime, 0, 1, 'L');
        $this->Ln(5);
        // Títulos de las columnas
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(12, 5, utf8_decode('#'), 1, 0, 'C');
        $this->Cell(25, 5, utf8_decode('Codigo'), 1, 0, 'C');
        $this->Cell(70, 5, utf8_decode('Nombre'), 1, 0, 'C');
        $this->Cell(32, 5, utf8_decode('Precio Compra'), 1, 0, 'C');
        $this->Cell(28, 5, utf8_decode('Precio Venta'), 1, 0, 'C');

        $this->Cell(24, 5, utf8_decode('Stock'), 1, 1, 'C');
    }

    public function generarPdfProductoPorCategoria()
    {
        // Establecer la zona horaria de Bolivia
        date_default_timezone_set('America/La_Paz');
        $idCategoria = $this->idCategoria;
        $idUsuario = $this->idUsuario;

        $respuestaProductos = ControladorProductos::ctrMostrarProductosSegunCategoria($idCategoria);

        $itemUsuario = "id";
        $this->respuestaUsuario = ControladorUsuarios::ctrMostrarUsuarios($itemUsuario, $idUsuario);

        // Traemos la información de la categoría
        $itemCategoria = "id";
        if ($idCategoria != 0) {
            $this->respuestaCategoria = ControladorCategorias::ctrMostrarCategorias($itemCategoria, $idCategoria);
        } else {
            $this->respuestaCategoria["categoria"] = "Todos";
        }

        $this->DateAndTime = date('d-m-Y h:i:s a', time());

        $this->AddPage();
        $this->SetAutoPageBreak(true, 12);
        $this->SetFont('Arial', '', 11);

        $contador = 1;
        // Detalles de los productos
        foreach ($respuestaProductos as $producto) {
            $this->Cell(12, 5, $contador, 1, 0, 'C');
            $this->Cell(25, 5, utf8_decode($producto['codigo']), 1, 0, 'C');
            $this->Cell(70, 5, utf8_decode($producto['descripcion']), 1, 0, 'C');
            $this->Cell(32, 5, utf8_decode($producto['precio_compra']) . ' Bs', 1, 0, 'C');
            $this->Cell(28, 5, utf8_decode($producto['precio_venta']) . ' Bs', 1, 0, 'C');
            $this->Cell(24, 5, utf8_decode($producto['stock']), 1, 1, 'C');
            $contador++;
        }
        $this->Ln();
        // Salida del archivo PDF
        $this->Output('factura.pdf', 'I');
    }
}

$factura = new reporteProductoPorCategoria();
$factura->idCategoria = $_GET["idCategoria"];
$factura->idUsuario = $_GET["idUsuario"];
$factura->generarPdfProductoPorCategoria();
