<?php
require_once("clases/Usuario.php");
require_once("clases/Post.php");
$opcion = $_POST["opcion"];
switch($opcion){
	//En caso que $opcion sea 1 registraremos un usuario
	case 1: 
		$u = new Usuario();
		$u->usuario = $_POST["usuario"];
		$u->password = $_POST["password"];
		$u->tipo = "Normal";
		$respuesta = $u->registrarUsuario();
		$res = array("respuesta" => $respuesta);
		echo json_encode($res);
		break;
	case 2:
		$p = new Post();
		$p->titulo = $_POST["titulo"];
		$p->mensaje = $_POST["mensaje"];
		$p->fecha = date("Y-m-d");
		$p->usuario = $_POST["usuario"];
		$respuesta = $p->agregarPublicacion();
		$p->fecha = date("d-m-Y");
		$res = array("respuesta" => $respuesta, "post" => $p);
		echo json_encode($res);
		break;
	case 3:
		$u = new Usuario();
		$u->usuario = $_POST["usuario"];
		$u->password = $_POST["password"];
		$u->tipo = $_POST["tipo"];
		$respuesta = $u->actualizarUsuario();
		$res = array("respuesta" => $respuesta);
		echo json_encode($res);
		break;
	case 4:
		$u = new Usuario();
		$u->usuario = $_POST["usuario"];
		$respuesta = $u->eliminarUsuario();
		$res = array("respuesta" => $respuesta);
		echo json_encode($res);
		break;
	case 5:
		$p = new Post();
		$p->id = $_POST["id"];
		$respuesta = $p->eliminarPublicacion();
		$res = array("respuesta" => $respuesta);
		echo json_encode($res);
		break;
}
?>