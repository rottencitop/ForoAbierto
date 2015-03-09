$(document).on("ready",function(){
	
	$("#btnLogin").on("click",function(){
		$("#registro").slideUp("fast",function(){
			$("#login").slideToggle("slow");
		});
	});
	
	$("#btnRegistrarse").on("click",function(){
		$("#login").slideUp("fast",function(){
			$("#registro").slideToggle("slow");
		});
	});
	
	$(".eliminar").on("click",function(){
		var parent = $(this).parent(".publicacion");
		var id = $(this).attr("data-id");
		$.ajax({
			data: "opcion=5&id="+id,
			dataType:"json",
			url:"add.php",
			type:"post",
			success: function(data){
				if(data.respuesta == 1){
					parent.fadeOut("slow",function(){
						parent.remove();
						if($(".eliminar").length == 0){
							$("#nopost").slideDown(400);
						}
					});
				}else{
					alert("Error en el sistema.");
				}
			}
		});
		
		
	});
	
	$("#escribirPublicacion").dialog({
		autoOpen: false,
		modal:true,
		hide: "fade",
		show: "fade"
	});
	
	$("#editarUsuario").dialog({
		autoOpen: false,
		modal:true,
		hide: "fade",
		show: "fade"
	});
	
	$(".btnEditar").on("click",function(){
		var usuario = $(this).attr("data-usuario");
		var pass = $(this).attr("data-pass");
		$('#formEditarUsuario input[name="usuario"]').val(usuario);
		$('#formEditarUsuario input[name="password"]').val(pass);
		$("#editarUsuario").dialog('open');
	});
	
	$("#formEditarUsuario").on("submit",function(e){
		e.preventDefault();
		var data = $(this).serialize();
		$.ajax({
			data: data+"&opcion=3",
			url:"add.php",
			type:"post",
			dataType:"json",
			success: function(data){
				if(data.respuesta == 1){
					alert("Usuario modificado.");
					window.location = "index.php";
				}else{
					alert("Error en el sistema.");
				}
			}
		});
	});
	
	$("#publicar").on("click", function(){
		$("#escribirPublicacion").dialog('open');
	});
	
	$("#btnVerUsuarios").on("click",function(){
		$("#usuarios").slideToggle(400);
	});
	
	$(".btnEliminar").on("click",function(){
		var usuario = $(this).attr("data-usuario");
		var padre = $(this).parent(".usuario");
		$.ajax({
			data: "opcion=4&usuario="+usuario,
			type:"post",
			url: "add.php",
			dataType:"json",
			success: function(data){
				if(data.respuesta == 1){
					padre.fadeIn("slow",function(){
						padre.remove();
						var mensajes = $('.publicacion[data-usuario="'+usuario+'"]');
						$.each(mensajes,function(){
							var mensaje = $(this);
							mensaje.fadeIn(2000,function(){
								mensaje.remove();
							});
						});
					});
					
				}else{
					alert("Error en el sistema.");
				}
			}
		});
	});
	
	$("#formRegistro").on("submit",function(e){
		e.preventDefault();
		var user = $(this).find('input[name="usuario"]');
		var pass = $(this).find('input[name="password"]');
		var pass2 = $(this).find('input[name="rpassword"]');
		if(user.hasClass("borderojo")){
			user.removeClass("borderojo");
		}
		if(pass.val() == pass2.val()){
			var data = $(this).serialize();
			$.ajax({
				url: "add.php",
				data:data+"&opcion=1",
				type:"post",
				dataType:"json",
				success: function(data){
					if(data.respuesta == 1){
						alert("Registro Exitoso.");
						window.location = "index.php";
					}else if(data.respuesta == -1){
						alert("El Usuario ya existe.");
						user.addClass("borderojo");
						user.focus();
					}else{
						alert("Error en el sistema.");
						console.log(data);
					}
				}
			});
		}else{
			alert("Las contraseñas no coinciden.");
			pass.val("");
			pass2.val("");
			pass.focus();
		}
	});
	
	$("#formLogin").on("submit",function(e){
		e.preventDefault();
		var data = $(this).serialize();
		var user = $(this).find('input[name="usuario"]');
		$.ajax({
			data: data+"&opcion=login",
			url:"login.php",
			type:"post",
			dataType:"json",
			success: function(data){
				if(data.respuesta == 1){
					window.location = "index.php";
				}else{
					alert("El Usuario no existe o la contraseña no coincide con el usuario.");
					user.focus();
				}

			}
		});
	});
	
	$("#mensajepost").on("keyup",function(){
		var cantidad = 255;
		var length = $(this).val().length;
		$("#caracteres").html((cantidad-length) + " caracteres restantes.");
	});
	
	$("#formPublicacion").on("submit",function(e){
		e.preventDefault();
		var data = $(this).serialize();
		$.ajax({
			url:"add.php",
			type:"post",
			data: data+"&opcion=2",
			dataType:"json",
			success: function(data){
				if(data.respuesta == 1){
					$("#escribirPublicacion").dialog('close');
					if($("#nopost").length){
						$("#nopost").slideUp(300);
					}
					$('<article class="publicacion"><div class="quote"><img src="images/quote.png" alt=""></div><div class="titulo">'+data.post.titulo+'</div><div>'+data.post.mensaje+'</div><div class="autor">Publicado por '+data.post.usuario+' | '+data.post.fecha+'</div></article>').hide().prependTo("#publicaciones").fadeIn(500);
				}else{
					alert("Error en el sistema.");
				}
			}
		});
	});
});