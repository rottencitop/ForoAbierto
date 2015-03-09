<?php
require_once("Conectar.php");
class Usuario{
	public $usuario;
	public $password;
	public $tipo;
	private $conexion;
	
	public function __construct(){
		$this->conexion = new Conectar();
	}
	
	//Registrar usuario
	public function registrarUsuario(){
		$u = $this->login();
		//Vemos si existe el usuario
		if($u == null){
			//Valor a retornar
			$r = 0;
			//Retornamos la conexión a la Base de Datos
			$mysqli = $this->conexion->crearConexion();
			//Preparamos la Consulta SQL
			//Los ? son donde iran las variables a insertar
			$SQL = "INSERT INTO Usuario VALUES(?,?,?)";
			//Preparamos la SQL a ejecutar
			$ps = $mysqli->prepare($SQL);
			//Enlazamos las variables a la consulta SQL
			//s -> string, en este caso las 3 son string y enlazamos las variables
			$ps->bind_param("sss",$this->usuario,$this->password,$this->tipo);
			//Preguntamos si se ejecuta la consulta a la BD
			if($ps->execute()){
				//Si se ejecuta cambiamos el valor de la variable a true
				$r = 1;
			}
			//Liberamos la memoria de la consulta
			$ps->free_result();
			//Cerramos la consulta
			$ps->close();
			//Cerramos la Base de Datos
			$mysqli->close();
			//Devolvemos el valor
			return $r;
		}
		return -1;
	}
	
	//Login
	public function login(){
		//Valor a retornar
		$u = null;
		//Abrimos una nueva conexion y la retornamos
		$mysqli = $this->conexion->crearConexion();
		//Preparamos la consulta SQL
		$SQL = "SELECT * FROM Usuario WHERE usuario LIKE ? AND password LIKE ?";
		//Preparamos la consulta SQL a ejecutar
		$ps = $mysqli->prepare($SQL);
		//Enlazamos las variables al SQL, en este caso son 2 y las 2 string
		$ps->bind_param("ss",$this->usuario,$this->password);
		//Ejecutamos la consulta
		$ps->execute();
		//Almacenamos los valores en la misma mariable
		$ps->store_result();
		//Preguntamos si hay registros 
		if($ps->num_rows > 0){
			$u = new Usuario();
			//Enlazamos los valores a variables
			$ps->bind_result($nUsuario,$nPassword,$nTipo);
			//Ejecutamos la consulta
			$ps->fetch();
			//Ahora la pasamos al objeto
			$u->usuario = $nUsuario;
			$u->password = $nPassword;
			$u->tipo = $nTipo;
		}
		//Liberamos la memoria de la ejecucion
		$ps->free_result();
		//Cerramos la consulta
		$ps->close();
		//Cerramos la conexión a la BD
		$mysqli->close();
		//Retornamos
		return $u;
	}
	
	//Actualizamos los datos del Usuario
	public function actualizarUsuario(){
		//Valor a retornar
		$r =  0;
		//Abrimos la conexion y la retornamos
		$mysqli = $this->conexion->crearConexion();
		//Preparamos la SQL
		$SQL = "UPDATE Usuario SET password = ?, tipo = ? WHERE usuario LIKE ?";
		//Preparamos la consulta
		$ps = $mysqli->prepare($SQL);
		//Enlazamos las variables a la consulta
		$ps->bind_param("sss",$this->password,$this->tipo,$this->usuario);
		//Ejecutamos la consulta
		$ps->execute();
		//Almacenamos el valor en la misma variable
		$ps->store_result();
		//Preguntamos si hubieron filas afectadas por el UPDATE
		if($ps->affected_rows > 0){
			//Si hubieron le pasamos true al valor a  retornar
			$r = 1;
		}
		//Liberamos la memoria de la ejecucion
		$ps->free_result();
		//Cerramos la ejecucion
		$ps->close();
		//Cerramos la Base de Datos
		$mysqli->close();
		//Retornamos el valor
		return $r;
	}
	
	public function verUsuarios(){
		$usuarios = null;
		$mysqli = $this->conexion->crearConexion();
		$SQL = "SELECT * FROM Usuario ORDER BY usuario ASC";
		$ps = $mysqli->prepare($SQL);
		$ps->execute();
		$ps->store_result();
		if($ps->num_rows > 0){
			$usuarios = array();
			$ps->bind_result($nUsuario,$nPassword,$nTipo);
			while($ps->fetch()){
				$u = new Usuario();
				$u->usuario = $nUsuario;
				$u->password = $nPassword;
				$u->tipo = $nTipo;
				$usuarios[] = $u;
			}
		}
		$ps->free_result();
		$ps->close();
		$mysqli->close();
		return $usuarios;
	}
	
	public function eliminarUsuario(){
		$r = 0;
		$mysqli = $this->conexion->crearConexion();
		$SQL = "DELETE FROM Usuario WHERE usuario LIKE ?";
		$ps = $mysqli->prepare($SQL);
		$ps->bind_param("s",$this->usuario);
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