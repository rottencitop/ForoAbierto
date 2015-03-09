<?php
require_once("Conectar.php");
class Post{
	public $id;
	public $titulo;
	public $mensaje;
	public $fecha;
	public $usuario;
	private $conexion;
	
	public function __construct(){
		$this->conexion = new Conectar();
	}
	
	public function verPublicaciones(){
		$publicaciones = null;
		$mysqli = $this->conexion->crearConexion();
		$SQL = "SELECT id,titulo,mensaje, DATE_FORMAT(fecha, '%d-%m-%Y') as fecha, usuario FROM Post ORDER BY id DESC";
		$ps = $mysqli->prepare($SQL);
		$ps->execute();
		$ps->store_result();
		if($ps->num_rows > 0){
			$publicaciones = array();
			$ps->bind_result($nId,$nTitulo,$nMensaje,$nFecha,$nUsuario);
			while($ps->fetch()){
				$p = new Post();
				$p->id = $nId;
				$p->titulo = $nTitulo;
				$p->mensaje = $nMensaje;
				$p->fecha = $nFecha;
				$p->usuario = $nUsuario;
				$publicaciones[] = $p;
			}
		}
		$ps->free_result();
		$ps->close();
		$mysqli->close();
		return $publicaciones;
	}
	
	public function agregarPublicacion(){
		$r = 0;
		$mysqli = $this->conexion->crearConexion();
		$SQL = "INSERT INTO Post(titulo,mensaje,fecha,usuario) VALUES (?,?,?,?)";
		$ps = $mysqli->prepare($SQL);
		$ps->bind_param("ssss",$this->titulo,$this->mensaje,$this->fecha,$this->usuario);
		if($ps->execute()){
			$r = 1;
		}
		$ps->free_result();
		$ps->close();
		$mysqli->close();
		return $r;
	}
	
	public function eliminarPublicacion(){
		$r = 0;
		$mysqli = $this->conexion->crearConexion();
		$SQL = "DELETE FROM Post WHERE id = ?";
		$ps = $mysqli->prepare($SQL);
		$ps->bind_param("i",$this->id);
		$ps->execute();
		$ps->store_result();
		if($ps->affected_rows > 0){
			$r = 1;
		}
		$ps->free_result();
		$ps->close();
		$mysqli->close();
		return $r;
	}
}
?>