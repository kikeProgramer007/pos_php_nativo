
<?php



require_once "../../../controladores/reportes.controlador.php";
require_once "../../../modelos/reportes.modelo.php";
require_once "../../../fpdf/fpdf.php";
require_once "../../../controladores/usuarios.controlador.php";
require_once "../../../modelos/usuarios.modelo.php";
require_once "../../../fpdf/fpdf.php";
class PdfGananciasYear
{

    public $idUsuario;
    public $yearini = null;
    public $yearfin = null;

    public function generarpdfgananciaYear()
    {
        date_default_timezone_set('America/La_Paz');

        $yearini = $this->yearini;
        $yearfin = $this->yearfin;
        // if (!isset($this->session->id_usuario)) {return redirect()->to(base_url());}
        $nombreTienda = "Pollos Rossy"; //$this->configuracion->select('valor')->where('nombre','tienda_nombre')->get()->getRow()->valor;
        $direccionTienda = "Refineria"; // $this->configuracion->select('valor')->where('nombre','tienda_direccion')->get()->getRow()->valor;

        $DateAndTime = date('d-m-Y h:i:s a', time());
        // $db     =\Config\Database::connect();
        $pdf = new FPDF('P', 'mm', 'letter');
        $pdf->AddPage();
        $pdf->SetMargins(12, 12, 12);
        $pdf->SetTitle (utf8_decode("Reporte de Ganancias por Año"));
        $pdf->SetFont('Arial', 'B', 15);
        // $pdf->image(base_url().'/images/logotipo.png',185,10,18,16,'PNG');
        $pdf->Cell(195, 5, utf8_decode("Reporte De Ganancias Por Año"), 0, 1, 'C');
        $pdf->Image('images/logo-negro-bloque.jpg', 100, 25, 30, 20, 'jpg');


        $pdf->Ln(10); // Espacio después de la imagen
        $pdf->SetX(140); // Ajusta este valor según sea necesario ancho
        $pdf->SetY(30);  // Ajusta este valor según sea necesario altura
        
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetX(20); // Ajusta este valor según sea necesario ancho
        $pdf->Cell(40, 5, utf8_decode('Usuario:'), 0, 0, 'L');
        $pdf->SetX(50); // Ajusta este valor según sea necesario ancho
        $pdf->SetFont('Arial', '', 10);
       // Obtenemos el usuario de la base de datos
       $usuario = ControladorUsuarios::ctrMostrarUsuarios('id', $this->idUsuario);
       $pdf->Cell(50, 5, utf8_decode($usuario["nombre"]), 0, 1, 'L'); // Nombre del usuario

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetX(20); // Ajusta este valor según sea necesario ancho
        $pdf->Cell(40, 5, utf8_decode('Restaurante:'), 0, 0, 'L');
        $pdf->SetX(50); // Ajusta este valor según sea necesario ancho
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(50, 5, utf8_decode($nombreTienda), 0, 1, 'L');


        $pdf->SetY(35);  // Ajusta este valor según sea necesario altura
        $pdf->SetX(140); // Ajusta este valor según sea necesario ancho

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(40, 5, utf8_decode('Dirección:'), 0, 0, 'L');
        $pdf->SetX(160); // Ajusta este valor según sea necesario ancho
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(50, 5, $direccionTienda, 0, 1, 'L');



        $pdf->SetX(140); // Ajusta este valor según sea necesario ancho
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(40, 5, utf8_decode('Periodo:'), 0, 0, 'L');
        $pdf->SetX(160); // Ajusta este valor según sea necesario ancho
        $pdf->SetFont('Arial','', 10);
        $pdf->Cell(50, 5, $yearini . ' hasta ' . $yearfin, 0, 1, 'L');
        $pdf->SetY(40);  // Ajusta este valor según sea necesario altur
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetX(20); // Ajusta este valor según sea necesario ancho
        $pdf->Cell(40, 5, utf8_decode('Fecha y hora:'), 0, 0, 'L');
        $pdf->SetX(50); // Ajusta este valor según sea necesario ancho
        $pdf->SetFont('Arial', '', 10);
        
        $pdf->Cell(50, 5, $DateAndTime, 0, 1, 'L');
       
       
        
        //**************************************************************************//

        $datos = ModeloReportes::mdlObtenerGananciasYear($yearini, $yearfin);
      
        $sum1 = 0;
        $sum2 = 0;
        $sum3 = 0;
        $primeravez = true;
        $nrotabla = 1;
        $aumentar = 10;
        $years = '';
        $x = 13;
        $y = 60;
        $pdf->SetAutoPageBreak(false);
        $ganancia = 0;
        foreach ($datos as $dato) {

            if ($primeravez) {
               
                $pdf->SetFont('', 'B', 8);
             
                $fill = True;
                
                $pdf->SetXY($x, $y);
                $posicion_MulticeldaDX = $pdf->GetX();
                $posicion_MulticeldaDY = $pdf->GetY();

                $pdf->SetFillColor(224, 235, 255);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetDrawColor(224, 235, 255);
            
                $pdf->SetXY($posicion_MulticeldaDX, $posicion_MulticeldaDY); //Aquí le indicas la posición de la esquina superior izquierda para el primer multicell que envuelve toda la tabla o recuadro
                $pdf->MultiCell(92, 75, '', 1);
                
                $pdf->SetXY($posicion_MulticeldaDX, $posicion_MulticeldaDY); // Esto posiciona cada etiqueta en base a la posición de la esquina 
               
                $pdf->Cell(92, 5, utf8_decode('GESTIÓN ') . $dato['year'], 1, 1, 'C', $fill);

                $pdf->SetXY($posicion_MulticeldaDX, $posicion_MulticeldaDY + 5);
                $pdf->Cell(23, 5, 'Meses', 0, 1, 'C');
                $pdf->SetXY($posicion_MulticeldaDX + 23, $posicion_MulticeldaDY + 5);
                $pdf->Cell(23, 5, utf8_decode('Ventas'), 0, 1, 'C', 0);
                $pdf->SetXY($posicion_MulticeldaDX + 46, $posicion_MulticeldaDY + 5);
                $pdf->Cell(23, 5, 'Ganancias', 0, 1, 'C');
                $pdf->SetXY($posicion_MulticeldaDX + 69, $posicion_MulticeldaDY + 5);
                $pdf->Cell(23, 5, utf8_decode('Meseros'), 0, 1, 'C', 0);

                $primeravez = false;
                $years = $dato['year'];
                $x = 110;
            }

            if ($dato['year'] == $years && $dato['mes'] <= '12') {

                switch ($dato['mes']) {
                    case 1:
                        $mes = "Enero";
                        break;
                    case 2:
                        $mes = "Febrero";
                        break;
                    case 3:
                        $mes = "Marzo";
                        break;
                    case 4:
                        $mes = "Abril";
                        break;
                    case 5:
                        $mes = "Mayo";
                        break;
                    case 6:
                        $mes = "Junio";
                        break;
                    case 7:
                        $mes = "Julio";
                        break;
                    case 8:
                        $mes = "Agosto";
                        break;
                    case 9:
                        $mes = "Septiembre";
                        break;
                    case 10:
                        $mes = "Octubre";
                        break;
                    case 11:
                        $mes = "Noviembre";
                        break;
                    case 12:
                        $mes = "Diciembre";
                        break;
                    default:
                        $mes = 'mes sin ventas';
                }

                $pdf->SetFont('', '', 8);
                $pdf->SetXY($posicion_MulticeldaDX, $posicion_MulticeldaDY + $aumentar);
                $pdf->Cell(23, 5, utf8_decode($mes), 0, 1, 'C');
                $pdf->SetXY($posicion_MulticeldaDX + 23, $posicion_MulticeldaDY + $aumentar);
                $pdf->Cell(23, 5, number_format($dato['ventas'], 2, '.', ','), 0, 1, 'C', 0);
                $pdf->SetXY($posicion_MulticeldaDX + 46, $posicion_MulticeldaDY + $aumentar);
                // $ganancia= $this->ganancia_year_month($dato['year'],$dato['mes']);

                $ganancias = ModeloReportes::mdlObtenerGanancias($dato['mes'], $dato['year']);
                // Usamos array_column para extraer la columna 'ganancias'
                $ganancias = array_column($ganancias, 'ganancias');
                // Usamos array_sum para sumar los valores de la columna 'ganancias'
                $ganancia = array_sum($ganancias);


                $pdf->Cell(23, 5, number_format($ganancia, 2, '.', ','), 0, 1, 'C', 0);
                $pdf->SetXY($posicion_MulticeldaDX + 69, $posicion_MulticeldaDY + $aumentar);
                $pdf->Cell(23, 5, $dato['meseros'], 0, 1, 'C', 0);
                $sum1 = $sum1 + $dato['ventas'];
                $sum2 = $sum2 + $ganancia;
                $sum3 = $sum3 + $dato['meseros'];
                $aumentar += 5;

                if ($dato['mes'] == '12') {
                    $pdf->SetFont('', 'B', 8);
                    $pdf->SetXY($posicion_MulticeldaDX, $posicion_MulticeldaDY + $aumentar);
                    $pdf->Cell(23, 5, utf8_decode('TOTAL ' . $dato['year'] . ' :'), 0, 1, 'C');
                    $pdf->SetXY($posicion_MulticeldaDX + 23, $posicion_MulticeldaDY + $aumentar);
                    $pdf->Cell(23, 5, number_format($sum1, 2, '.', ','), 0, 1, 'C', 0);
                    $pdf->SetXY($posicion_MulticeldaDX + 46, $posicion_MulticeldaDY + $aumentar);
                    $pdf->Cell(23, 5, number_format($sum2, 2, '.', ','), 0, 1, 'C', 0);
                    $pdf->SetXY($posicion_MulticeldaDX + 69, $posicion_MulticeldaDY + $aumentar);
                    $pdf->Cell(23, 5, $sum3, 0, 1, 'C', 0);

                    $years = $dato['year'];
                    $sum1 = 0;
                    $sum2 = 0;
                    $sum3 = 0;
                    $aumentar = 10;
                    $primeravez = true;
                    $nrotabla++;

                    if ($nrotabla % 2 == 0) { //reestablecer parametros de x y para pocisionar tabla
                        $x = 110;
                        $y = $y;
                    } else {
                        $x = 13;
                        $y = $y + 85;
                    }
                    if (($nrotabla - 1) == 4  || ($nrotabla - 5) % 6 == 0) { //estrategia para pasar a otra pagina
                        $pdf->AddPage();
                        $y = 16;
                    }
                }
            } else {

                $pdf->SetFont('', 'B', 8);
                $pdf->SetXY($posicion_MulticeldaDX, $posicion_MulticeldaDY + $aumentar);
                $pdf->Cell(23, 5, utf8_decode('TOTAL ' . $years . ' :'), 0, 1, 'C');
                $pdf->SetXY($posicion_MulticeldaDX + 23, $posicion_MulticeldaDY + $aumentar);
                $pdf->Cell(23, 5, number_format($sum1, 2, '.', ','), 0, 1, 'C', 0);
                $pdf->SetXY($posicion_MulticeldaDX + 46, $posicion_MulticeldaDY + $aumentar);
                $pdf->Cell(23, 5, number_format($sum2, 2, '.', ','), 0, 1, 'C', 0);
                $pdf->SetXY($posicion_MulticeldaDX + 69, $posicion_MulticeldaDY + $aumentar);
                $pdf->Cell(23, 5, $sum3, 0, 1, 'C', 0);

                $sum1 = 0;
                $sum2 = 0;
                $sum3 = 0;
                $aumentar = 10;
                $primeravez = true;
                $nrotabla++;



                if ($nrotabla % 2 == 0) { //reestablecer parametros de x y para pocisionar tabla
                    $x = 110;
                    $y = $y;

                    switch ($dato['mes']) {
                        case 1:
                            $mes = "Enero";
                            break;
                        case 2:
                            $mes = "Febrero";
                            break;
                        case 3:
                            $mes = "Marzo";
                            break;
                        case 4:
                            $mes = "Abril";
                            break;
                        case 5:
                            $mes = "Mayo";
                            break;
                        case 6:
                            $mes = "Junio";
                            break;
                        case 7:
                            $mes = "Julio";
                            break;
                        case 8:
                            $mes = "Agosto";
                            break;
                        case 9:
                            $mes = "Septiembre";
                            break;
                        case 10:
                            $mes = "Octubre";
                            break;
                        case 11:
                            $mes = "Noviembre";
                            break;
                        case 12:
                            $mes = "Diciembre";
                            break;
                        default:
                            $mes = 'mes sin ventas';
                    }

                    $pdf->SetXY($x, $y);
                    $posicion_MulticeldaDX = $pdf->GetX();
                    $posicion_MulticeldaDY = $pdf->GetY();


                    $pdf->SetXY($posicion_MulticeldaDX, $posicion_MulticeldaDY); //Aquí le indicas la posición de la esquina superior izquierda para el primer multicell que envuelve toda la tabla o recuadro
                    $pdf->MultiCell(92, 75, '', 1);
                    $pdf->SetXY($posicion_MulticeldaDX, $posicion_MulticeldaDY); // Esto posiciona cada etiqueta en base a la posición de la esquina 
                    $pdf->Cell(92, 5, utf8_decode('GESTIÓN ') . $dato['year'], 1, 1, 'C', $fill);

                    $pdf->SetXY($posicion_MulticeldaDX, $posicion_MulticeldaDY + 5);
                    $pdf->Cell(23, 5, 'Meses', 0, 1, 'C');
                    $pdf->SetXY($posicion_MulticeldaDX + 23, $posicion_MulticeldaDY + 5);
                    $pdf->Cell(23, 5, utf8_decode('Ventas'), 0, 1, 'C', 0);
                    $pdf->SetXY($posicion_MulticeldaDX + 46, $posicion_MulticeldaDY + 5);
                    $pdf->Cell(23, 5, 'Ganancias', 0, 1, 'C');
                    $pdf->SetXY($posicion_MulticeldaDX + 69, $posicion_MulticeldaDY + 5);
                    $pdf->Cell(23, 5, utf8_decode('Meseros'), 0, 1, 'C', 0);

                    $primeravez = false;
                    $years = $dato['year'];
                    $x = 110;
                    $pdf->SetFont('', '', 8);
                    $pdf->SetXY($posicion_MulticeldaDX, $posicion_MulticeldaDY + $aumentar);
                    $pdf->Cell(23, 5, utf8_decode($mes), 0, 1, 'C');
                    $pdf->SetXY($posicion_MulticeldaDX + 23, $posicion_MulticeldaDY + $aumentar);
                    $pdf->Cell(23, 5, number_format($dato['ventas'], 2, '.', ','), 0, 1, 'C', 0);
                    $pdf->SetXY($posicion_MulticeldaDX + 46, $posicion_MulticeldaDY + $aumentar);
                    // $ganancia= $this->ganancia_year_month($dato['year'],$dato['mes']);

                    $ganancias = ModeloReportes::mdlObtenerGanancias($dato['mes'], $dato['year']);
                    // Usamos array_column para extraer la columna 'ganancias'
                    $ganancias = array_column($ganancias, 'ganancias');
                    // Usamos array_sum para sumar los valores de la columna 'ganancias'
                    $ganancia = array_sum($ganancias);


                    $pdf->Cell(23, 5, number_format($ganancia, 2, '.', ','), 0, 1, 'C', 0);
                    $pdf->SetXY($posicion_MulticeldaDX + 69, $posicion_MulticeldaDY + $aumentar);
                    $pdf->Cell(23, 5, $dato['meseros'], 0, 1, 'C', 0);

                    $sum1 = $sum1 + $dato['ventas'];
                    $sum2 = $sum2 + $ganancia;
                    $sum3 = $sum3 + $dato['meseros'];
                    $aumentar += 5;
                } else {
                    $x = 13;
                    $y = $y + 85;

                    switch ($dato['mes']) {
                        case 1:
                            $mes = "Enero";
                            break;
                        case 2:
                            $mes = "Febrero";
                            break;
                        case 3:
                            $mes = "Marzo";
                            break;
                        case 4:
                            $mes = "Abril";
                            break;
                        case 5:
                            $mes = "Mayo";
                            break;
                        case 6:
                            $mes = "Junio";
                            break;
                        case 7:
                            $mes = "Julio";
                            break;
                        case 8:
                            $mes = "Agosto";
                            break;
                        case 9:
                            $mes = "Septiembre";
                            break;
                        case 10:
                            $mes = "Octubre";
                            break;
                        case 11:
                            $mes = "Noviembre";
                            break;
                        case 12:
                            $mes = "Diciembre";
                            break;
                        default:
                            $mes = 'mes sin ventas';
                    }

                    $pdf->SetXY($x, $y);
                    $posicion_MulticeldaDX = $pdf->GetX();
                    $posicion_MulticeldaDY = $pdf->GetY();


                    $pdf->SetXY($posicion_MulticeldaDX, $posicion_MulticeldaDY); //Aquí le indicas la posición de la esquina superior izquierda para el primer multicell que envuelve toda la tabla o recuadro
                    $pdf->MultiCell(92, 75, '', 1);
                    $pdf->SetXY($posicion_MulticeldaDX, $posicion_MulticeldaDY); // Esto posiciona cada etiqueta en base a la posición de la esquina 
                    $pdf->Cell(92, 5, utf8_decode('GESTIÓN ') . $dato['year'], 1, 1, 'C', $fill);
                    $pdf->SetXY($posicion_MulticeldaDX, $posicion_MulticeldaDY + 5);
                    $pdf->Cell(23, 5, 'Meses', 0, 1, 'C');
                    $pdf->SetXY($posicion_MulticeldaDX + 23, $posicion_MulticeldaDY + 5);
                    $pdf->Cell(23, 5, utf8_decode('Ventas'), 0, 1, 'C', 0);
                    $pdf->SetXY($posicion_MulticeldaDX + 46, $posicion_MulticeldaDY + 5);
                    $pdf->Cell(23, 5, 'Ganancias', 0, 1, 'C');
                    $pdf->SetXY($posicion_MulticeldaDX + 69, $posicion_MulticeldaDY + 5);
                    $pdf->Cell(23, 5, utf8_decode('Meseros'), 0, 1, 'C', 0);
                    $primeravez = false;
                    $years = $dato['year'];
                    $x = 110;

                    $pdf->SetFont('', '', 8);
                    $pdf->SetXY($posicion_MulticeldaDX, $posicion_MulticeldaDY + $aumentar);
                    $pdf->Cell(23, 5, utf8_decode($mes), 0, 1, 'C');
                    $pdf->SetXY($posicion_MulticeldaDX + 23, $posicion_MulticeldaDY + $aumentar);
                    $pdf->Cell(23, 5, number_format($dato['ventas'], 2, '.', ','), 0, 1, 'C', 0);
                    $pdf->SetXY($posicion_MulticeldaDX + 46, $posicion_MulticeldaDY + $aumentar);
                    // $ganancia= $this->ganancia_year_month($dato['year'],$dato['mes']);
                    $ganancias = ModeloReportes::mdlObtenerGanancias($dato['mes'], $dato['year']);
                    // Usamos array_column para extraer la columna 'ganancias'
                    $ganancias = array_column($ganancias, 'ganancias');
                    // Usamos array_sum para sumar los valores de la columna 'ganancias'
                    $ganancia = array_sum($ganancias);

                    $pdf->Cell(23, 5, number_format($ganancia, 2, '.', ','), 0, 1, 'C', 0);
                    $pdf->SetXY($posicion_MulticeldaDX + 69, $posicion_MulticeldaDY + $aumentar);
                    $pdf->Cell(23, 5, $dato['meseros'], 0, 1, 'C', 0);


                    $sum1 = $sum1 + $dato['ventas'];
                    $sum2 = $sum2 + $ganancia;
                    $sum3 = $sum3 + $dato['meseros'];
                    $aumentar += 5;
                }

                if (($nrotabla - 1) == 4 || ($nrotabla - 5) % 6 == 0) { //estrategia para pasar a otra pagina
                    $pdf->AddPage();
                    $y = 16;
                }
            }
        }
        if ($years!='12') {
            $pdf->SetFont('','B',8);
            $pdf->SetXY($posicion_MulticeldaDX,$posicion_MulticeldaDY+$aumentar);
            $pdf->Cell(23,5,utf8_decode('TOTAL '.$years.' :'), 0,1,'C');
            $pdf->SetXY($posicion_MulticeldaDX+23,$posicion_MulticeldaDY+$aumentar);
            $pdf->Cell(23,5,number_format($sum1,2,'.',','),0,1,'C',0);
            $pdf->SetXY($posicion_MulticeldaDX+46,$posicion_MulticeldaDY+$aumentar);
            $pdf->Cell(23,5,number_format($sum2,2,'.',','),0,1,'C',0);
            $pdf->SetXY($posicion_MulticeldaDX+69,$posicion_MulticeldaDY+$aumentar);
            $pdf->Cell(23,5,$sum3,0,1,'C',0);
        }
    
        $pdf->Output('gananciaspormes.pdf', 'I');
    }



    
}

$reporte = new PdfGananciasYear();

$reporte->yearini = $_GET["yearini"];
$reporte->yearfin = $_GET["yearfin"];
$reporte->idUsuario = $_GET["idUsuario"];

$reporte->generarpdfgananciaYear();

?>
