<?php
require_once("clases/Usuario.php");
session_start();
$u = new Usuario();
$u->usuario = $_POST["usuario"];
$u->password = $_POST["password"];
$u = $u->login();
$res;
if(is_null($u)){
	$res = array("respuesta" => 0);
}else{
	$_SESSION["usuario"] = $u;
	$res = array("respuesta" => 1);
}
echo json_encode($res);
?>