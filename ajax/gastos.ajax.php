<?php

require_once "../controladores/gastos.controlador.php";
require_once "../modelos/gastos.modelo.php";

class AjaxGastos{

	/*=============================================
	EDITAR MESERO
	=============================================*/	

	public $idGasto;

	public function ajaxEditarGasto(){

		$item = "id";
		$valor = $this->idGasto;

		$respuesta = ControladorGastos::ctrMostrarGastos($item, $valor);

		echo json_encode($respuesta);


	}

/*=============================================
VALIDAR NO REPETIR MESERO
=============================================*/

public $validarGasto;

public function ajaxValidarGasto()
{
    $item = "mesero";
    $valor = $this->validarGasto;

    $respuesta = ControladorGastos::ctrMostrarGastos($item, $valor);

    echo json_encode($respuesta);
}
}
/*=============================================
EDITAR MESERO
=============================================*/	

if(isset($_POST["idGasto"])){

	$mesero = new AjaxGastos();
	$mesero -> idGasto = $_POST["idGasto"];
	$mesero -> ajaxEditarGasto();

}



/*=============================================
VALIDAR NO REPETIR MESERO
=============================================*/

if (isset($_POST["validarGasto"])) {

    $valMesero = new AjaxGastos();
    $valMesero->validarGasto = $_POST["validarGasto"];
    $valMesero->ajaxValidarGasto();

}


