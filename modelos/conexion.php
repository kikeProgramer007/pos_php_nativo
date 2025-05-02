<?php

class Conexion{

	static public function conectar(){
	
		$link = new PDO("mysql:host=localhost;dbname=pos_php_nativo-main",
			            "root",
			            "");

		$link->exec("set names utf8");
		date_default_timezone_set('America/La_Paz');
		return $link;
		
	}
	
}