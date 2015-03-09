<?php
class Conectar{
	private $servidor;
	private $usuario;
	private $password;
	private $database;
	
	public function __construct(){
		$this->servidor = "localhost";
		$this->usuario = "root";
		$this->password = "";
		$this->database = "foroabierto";
	}
	
	public function crearConexion(){
		//Creamos una instancia del objeto para Conectar a Base de Datos
		$conexion = new MySQLi($this->servidor,$this->usuario,$this->password,$this->database);
		//Seteamos los tipos de caracter
		$conexion->query("SET NAMES utf8");
		//Retornamos la conexión
		return $conexion;
	}
	
	
}
?>