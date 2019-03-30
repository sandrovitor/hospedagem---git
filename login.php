<?php 
// Verifica se já existe sessão.
session_start();
if(isset($_SESSION['logado']) && $_SESSION['logado'] == TRUE) {
	header('Location: ./');
	exit();
}
if($_SERVER['REQUEST_SCHEME'] == 'http' || $_SERVER['SERVER_PORT'] == '80') {
    header('Location: https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login | Hospedagem LS-03</title>
<!-- jQuery (necessario para os plugins Javascript do Bootstrap) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> <!-- jQuery -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> <!-- Font Awesome -->
<script src="js/bootstrap.min.js"></script>
<script src="js/functions.js"></script><!-- FUNÇÕES -->
<!-- Bootstrap CSS -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/glyphicon.css" rel="stylesheet">
<link href="css/chosen.css" rel="stylesheet">
<link rel="icon" href="images/favicon.png" />
<link rel="stylesheet" href="css/estilo_login.css">

<script>
function validate(input) {
	if($(input).val().trim() != '') {
		$(input).removeClass('no-val').addClass('has-val');
	} else {
		$(input).removeClass('has-val').addClass('no-val');
	}
}


$(document).ready(function(){
	$('#formulario').submit(function(){
		if($('[name="usuario"]').val() == '') {
			$('[name="usuario"]').focus();
			return false;
		}
		if($('[name="senha"]').val() == '') {
			$('[name="senha"]').focus();
			return false;
		}
		
		$.post('app/loga.php',{
			usuario: $('[name="usuario"]').val(),
			senha: $('[name="senha"]').val()
		}, function(data){
			if(data == 'OK') {
				$('#message_text').hide().html('<span class="text-success">Sucesso! Redirecionando...</span>').fadeIn('fast', function(){
					$('#message_icon > .glyphicon').removeClass('text-danger text-info text-primary text-warning text-success').addClass('text-success');
				});
				var delay = 500;
				setTimeout(function(){ location.reload(); }, delay);
			} else if(isNaN(data) == false) {
				var res = '';
				var icon_cor = '';
				switch(data) {
				case '1':
					icon_cor = 'text-danger';
					res = '<strong>>ERRO 403:</strong> Acesso negado!';
					break;

				case '2':
					icon_cor = 'text-warning';
					res = '<strong>ERRO:</strong> Usuário não encontrado';
					break;

				case '3':
					icon_cor = 'text-warning';
					res = '<strong>ERRO:</strong> Senha errada';
					break;

				case '4':
					icon_cor = 'text-danger';
					res = '<strong>ERRO:</strong> Você teve 3 tentativas de login sem sucesso...';
					break;

				default:
					icon_cor = 'text-info';
					res = 'Servidor retornou algo desconhecido <br>';
					res += data;
					break;
				}
				$('#message_text').hide().html(res).delay(200).fadeIn('fast', function(){
					$('#message_icon > .glyphicon').removeClass('text-danger text-info text-primary text-warning text-success').addClass(icon_cor);
				});
			}
		});
		return false;
	});

	$("input[name='usuario'], input[name='senha']").change(function(){
		$('#message_text').fadeOut('fast',function(){$('#message_text').html('');});
		$('#message_icon > .glyphicon').removeClass('text-danger text-info text-primary text-warning text-success');
	});
});

$(document).ajaxStart(function(){
	$("#message_icon").html('<img src="images/Preloader.gif" width="50" heigth="50">');
});
$(document).ajaxStop(function(){
	$("#message_icon").html('<span class="glyphicon glyphicon-lamp" style="font-size: 24px;"></span>');
});

</script>
</head>

<body>
	<div class="body_total">
		<div class="container">
    		<div class="title-page">
    			<span style="font-size:60%">DEPARTAMENTO DE HOSPEDAGEM</span><br>
    			<span> CIRCUITO LS-03</span>
    		</div>
    		<div class="login-page">
    			<div class="form">
    				<form class="login-form" id="formulario" action="#" method="post">
    					<input name="usuario" class="no-val" type="text" placeholder="Nome de usuário" autofocus="autofocus" onblur="validate(this)" />
    					<input name="senha" class="no-val" type="password" placeholder="Sua senha" onblur="validate(this)"/>
    					<button type="submit">login</button>
    					<div id="message" class="message" style="text-align:center">
    						<div id="message_icon"><span class="glyphicon glyphicon-lamp" style="font-size: 24px;"></span></div>
    						<div id="message_text"></div>
    					</div>
    				</form>
    			</div>
    		</div>
    	</div>
	</div>
	
</body>
</html>