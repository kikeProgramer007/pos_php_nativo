<?php

require_once "conexion.php";

class ModeloReportes{

	/*=============================================
	MOSTRAR VENTAS
	=============================================*/
	static public function mdlObtenerMesAnioGanancias($year_actual){

		$conexion = Conexion::conectar();

		// Consulta de meses
		$meses = $conexion->prepare("
			SELECT DISTINCT MONTH(fecha) AS mes 
			FROM ventas 
			WHERE estado = 1 
			ORDER BY mes ASC
		");

		// Consulta de años
		$years = $conexion->prepare("
			SELECT DISTINCT YEAR(fecha) AS years 
			FROM ventas 
			WHERE YEAR(fecha) >= 2000 
			AND YEAR(fecha) <= :year_actual 
			AND estado = 1 
			ORDER BY years ASC
		");

		// Usar el parámetro correctamente
		$years->bindParam(':year_actual', $year_actual, PDO::PARAM_INT);

		// Ejecutar
		$meses->execute();
		$years->execute();

		// Resultados
		$datos = [
			'meses' => $meses->fetchAll(PDO::FETCH_ASSOC),
			'years' => $years->fetchAll(PDO::FETCH_ASSOC)
		];

		// Liberar
		$meses->closeCursor();
		$years->closeCursor();

		return $datos;
	}
/*=============================================
	RANGO FECHAS
	=============================================*/	

	static public function mdlRangoFechasVentas($tabla, $fechaInicial, $fechaFinal, $estado = 1){

		if($fechaInicial == null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $tabla.estado=$estado ORDER BY id ASC");

			$stmt -> execute();

			return $stmt -> fetchAll();	


		}else if($fechaInicial == $fechaFinal){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE fecha like '%$fechaFinal%' AND $tabla.estado=$estado");

			/* $stmt -> bindParam(":fecha", $fechaFinal, PDO::PARAM_STR); */

			$stmt -> execute();

			return $stmt -> fetchAll();

		}else{

			$fechaActual = new DateTime();
			$fechaActual ->add(new DateInterval("P1D"));
			$fechaActualMasUno = $fechaActual->format("Y-m-d");

			$fechaFinal2 = new DateTime($fechaFinal);
			$fechaFinal2 ->add(new DateInterval("P1D"));
			$fechaFinalMasUno = $fechaFinal2->format("Y-m-d");

			if($fechaFinalMasUno == $fechaActualMasUno){
				$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE fecha BETWEEN '$fechaInicial' AND '$fechaFinalMasUno' AND $tabla.estado=$estado");
			}else{
				$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE fecha BETWEEN '$fechaInicial' AND '$fechaFinal' AND $tabla.estado=$estado");
			}
		
			$stmt -> execute();

			return $stmt -> fetchAll();

		}

	}

/*=============================================
	OBTENER GANANCIAS DE LAS VENTAS
	=============================================*/
    
    static public function mdlObtenerGanancias($mes,$anio){

		// Consulta SQL
		$sql = "SELECT dv.id_venta,v.codigo,DATE(v.fecha) AS fecha,u.usuario as vendedor,c.nombre as mesero,v.total,SUM((p.precio_venta-p.precio_compra)*dv.cantidad) as ganancias
				FROM detalle_venta as dv
				JOIN ventas AS v ON (dv.id_venta = v.id AND v.estado=1)
				JOIN productos AS p ON dv.id_producto = p.id
				JOIN meseros AS c ON v.id_mesero = c.id
				JOIN usuarios AS u ON v.id_vendedor = u.id
				WHERE MONTH(v.fecha)= :mes 
				AND YEAR(v.fecha)= :anio 
				GROUP BY (dv.id_venta)
				ORDER BY v.fecha ASC;";

        $ganancias = Conexion::conectar()->prepare($sql);
    
        $ganancias->bindParam(':mes', $mes, PDO::PARAM_INT);
        $ganancias->bindParam(':anio', $anio, PDO::PARAM_INT);
    
        $ganancias->execute();
    
        // Obtener los resultados
        $datos = $ganancias->fetchAll();
    
        // Liberar los recursos
        $ganancias->closeCursor();

        // Retornar los datos
        return $datos;
		
    }

/*=============================================
	SUMAR EL TOTAL DE VENTAS
	=============================================*/

	static public function mdlSumaTotalVentas($tabla){	

		$stmt = Conexion::conectar()->prepare("SELECT SUM(total) as total FROM $tabla WHERE $tabla.estado=1");

		$stmt -> execute();

		return $stmt -> fetch();

		$stmt -> close();

		$stmt = null;

	}
/*=============================================
	SUMAR EL TOTAL DE VENTAS - MES
	=============================================*/

	static public function mdlVentasTotalMes($tabla){	
		$yearactual= date('Y');
		$mesActual= date('m');
		$stmt = Conexion::conectar()->prepare("SELECT SUM(total) as total FROM $tabla WHERE MONTH(fecha)='$mesActual' AND YEAR(fecha) = '$yearactual' $tabla.estado=1");

		$stmt -> execute();

		return $stmt -> fetch();

		$stmt -> close();

		$stmt = null;

	}

/*=============================================
	SUMAR EL TOTAL DE VENTAS - DIA
	=============================================*/

	static public function mdlVentasTotalDia($tabla){	
		$hoy= date('Y-m-d');
		$stmt = Conexion::conectar()->prepare("SELECT SUM(total) as total FROM $tabla WHERE DATE(fecha)='$hoy' AND $tabla.estado=1");

		$stmt -> execute();

		return $stmt -> fetch();

		$stmt -> close();

		$stmt = null;

	}

/*=============================================
	OBTENER GANANCIAS DE LAS VENTAS ENTRE AÑOS
	=============================================*/
    
static public function mdlObtenerGananciasYear($yearIni, $yearFin){

    $conexion = Conexion::conectar();

    $sql = "SELECT 
                YEAR(v.fecha) AS year,
                MONTH(v.fecha) AS mes,
                COUNT(v.id) AS cantventas,
                SUM(v.total) AS ventas,
                COUNT(DISTINCT v.id_mesero) AS meseros
            FROM ventas AS v
            WHERE YEAR(v.fecha) BETWEEN :yearIni AND :yearFin 
            AND v.estado = 1
            GROUP BY YEAR(v.fecha), MONTH(v.fecha)
            ORDER BY year ASC, mes ASC";

    $ganancias = $conexion->prepare($sql);

    $ganancias->bindParam(':yearIni', $yearIni, PDO::PARAM_INT);
    $ganancias->bindParam(':yearFin', $yearFin, PDO::PARAM_INT);

    $ganancias->execute();

    $datos = $ganancias->fetchAll(PDO::FETCH_ASSOC);

    $ganancias->closeCursor();

    return $datos;
}
}