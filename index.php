<?php
require_once("clases/Usuario.php");
require_once("clases/Post.php");
session_start();
if(isset($_SESSION["usuario"])){
	$u = $_SESSION["usuario"];
}
$post = new Post();
$cuser = new Usuario();
$publicaciones = $post->verPublicaciones();
$usuarios = $cuser->verUsuarios();
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Foro Abierto</title>
<link href="css/style.css" rel="stylesheet" type="text/css">
<link href="css/smoothness/jquery-ui-1.10.3.custom.min.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="js/scripts.js"></script>
</head>

<body>
	<div id="container">
    	<header>
        	<div id="logo"><img src="images/logo.png" alt=""></div>
            <?php if($u != null){ ?>
            <div style="text-align:center;">Bienvenido <?php echo $u->usuario; ?></div>
            <?php } ?>
        	<nav>
            	<ul>
                	<a href="index.php"><li>Inicio</li></a>
                    <?php 
					if($u != null){
						if($u->tipo == "Admin"){
					?>
                    <li id="btnVerUsuarios">Ver Usuarios</li>
                    <?php } ?>
                    <a href="logout.php"><li>Cerrar sesión</li></a>
                    <?php }else{ ?>
                    <li id="btnLogin">Iniciar sesión</li>
                    <li id="btnRegistrarse">Registrarse</li>
                    <?php } ?>
                    
                </ul>
            </nav>
        </header>
        
        <section id="login">
        	<form id="formLogin">
            	<label>Usuario:</label>
                <input type="text" name="usuario" required>
                <label>Password:</label>
                <input type="password" name="password" required>
                <input type="submit" value="Ingresar">
            </form>
        </section>
        
        <section id="registro">
        	<form id="formRegistro">
            	<label>Usuario:</label>
                <input type="text" name="usuario" required>
                <label>Contraseña:</label>
                <input type="password" name="password" required>
                <label>Repita la contraseña:</label>
                <input type="password" name="rpassword" required>
                <input type="submit" value="Registrarme">
            </form>
        </section>
        <?php if($u != null && $u->tipo == "Admin"){ ?>
        <section id="usuarios">
        	<?php
				if($usuarios != null){
					foreach($usuarios as $us){
						echo '<div class="usuario">'.$us->usuario.' | <button class="btnEditar" data-usuario="'.$us->usuario.'" data-pass="'.$us->password.'">Editar</button> | <button class="btnEliminar" data-usuario="'.$us->usuario.'">Eliminar</button></div>';
					}
				}
			?>	
        </section>
        <?php } ?>
        
        <div id="editarUsuario" title="Editar Usuario">
        	<form id="formEditarUsuario">
            	<label>Nombre:</label>
                <input type="text" name="usuario" readonly>
                <label>Password:</label>
                <input type="password" name="password" required>
                <label>Tipo de Usuario:</label>
                <select name="tipo">
                	<option value="Normal">Normal</option>
                    <option value="Admin">Administrador</option>
                </select>
                <input type="submit" value="Guardar Cambios">
            </form>
        </div>
        
        <section id="publicaciones">
        	<?php
			$hay = 'style="display:block;"';
			if($publicaciones != null){
				$hay = '';
				foreach($publicaciones as $p){
					echo '<article class="publicacion" data-usuario="'.$p->usuario.'">';
            		if($u->tipo == "Admin"){
					echo '<div class="eliminar" data-id="'.$p->id.'" title="Eliminar Publicación"></div>';
					}
            		echo'<div class="quote"><img src="images/quote.png" alt=""></div>
                		<div class="titulo">'.$p->titulo.'</div>
                		<div>'.$p->mensaje.'</div>
                		<div class="autor">Publicado por '.$p->usuario.' | 03-07-2014</div>
            			</article>';
				}
			}
				echo '<div id="nopost" '.$hay.'>No hay publicaciones.</div>';
			
			
			?>
            
        </section>
        <?php if($u != null){ ?>
        <div id="publicar"><img src="images/escribir.png" alt="">Escribir una publicación</div>
        <div title="Escribir una publicación" id="escribirPublicacion">
        	<form id="formPublicacion">
            	<label>Título:</label>
                <input type="text" name="titulo" placeholder="Escriba el título..." required>
                <input type="hidden" name="usuario" value="<?php echo $u->usuario; ?>">
                <label>Mensaje:</label>
                <textarea style="height:100px;" id="mensajepost" name="mensaje" maxlength="255" required placeholder="Escriba su mensaje..."></textarea>
                <div id="caracteres" style="color:red;">255 caracteres restantes.</div>
                <input type="submit" value="Publicar">
            </form>
        </div>
        <?php } ?>
        <footer>
        Taller de Herramientas de Desarrollo Web<br>
        INACAP Santiago Sur &copy; 2014
        </footer>
    </div>
</body>
</html>