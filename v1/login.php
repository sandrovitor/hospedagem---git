<?php 
// Verifica se já existe sessão.
session_start();
if(isset($_SESSION['logado']) && $_SESSION['logado'] == TRUE) {
	header('Location: ./');
	exit();
}?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Hospedagem LS-03 :: Login</title>
<link rel="icon" href="images/favicon.png" />
<style>
@import url(https://fonts.googleapis.com/css?family=Roboto:300);

.title-page {
	width: 360px;
	margin:auto;
	text-align: center;
	padding: 15% 0 0;
	font-size: 36px;
	font-weight:bold;
}
div.message {
	margin: 15px auto 0;
	font-size: 14px;
	font-weight:bold;
	text-align: left;
	color: #cc2900;
}

.login-page {
  width: 360px;
  padding: 10% 0 0;
  margin: auto;
}
.form {
  position: relative;
  z-index: 1;
  background: #FFFFFF;
  max-width: 360px;
  margin: 0 auto 100px;
  padding: 45px;
  text-align: center;
  box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
}
.form input {
  font-family: "Roboto", sans-serif;
  outline: 0;
  background: #f2f2f2;
  width: 100%;
  border: 0;
  margin: 0 0 15px;
  padding: 15px;
  box-sizing: border-box;
  font-size: 14px;
}
.form button {
  font-family: "Roboto", sans-serif;
  text-transform: uppercase;
  outline: 0;
  background: #bc6c10;
  width: 100%;
  border: 0;
  padding: 15px;
  color: #FFFFFF;
  font-size: 14px;
  -webkit-transition: all 0.3 ease;
  transition: all 0.3 ease;
  cursor: pointer;
}
.form button:hover,.form button:active,.form button:focus {
  background: #ef9f43;
}
.container {
  position: relative;
  z-index: 1;
  max-width: 380px;
  margin: 0 auto;
}
.container:before, .container:after {
  content: "";
  display: block;
  clear: both;
}
.container .info {
  margin: 50px auto;
  text-align: center;
}
.container .info h1 {
  margin: 0 0 15px;
  padding: 0;
  font-size: 36px;
  font-weight: 300;
  color: #1a1a1a;
}
.container .info span {
  color: #4d4d4d;
  font-size: 12px;
}
.container .info span a {
  color: #000000;
  text-decoration: none;
}
.container .info span .fa {
  color: #EF3B3A;
}
body {
  background: #f7cfa1; /* fallback for old browsers */
  background: -webkit-linear-gradient(right, #f7cfa1, #f7cfa1);
  background: -moz-linear-gradient(right, #f7cfa1, #f7cfa1);
  background: -o-linear-gradient(right, #f7cfa1, #f7cfa1);
  background: linear-gradient(to left, #f7cfa1, #f7cfa1);
  font-family: "Roboto", sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;      
}

</style>
<!-- jQuery -->
<script src="js/jquery.js"></script> <!-- jQuery -->
<script>
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
				$('#message').html('<span style="color: green">Sucesso! Redirecionando...</span>');
				var delay = 500;
				setTimeout(function(){ location.reload(); }, delay);
			} else if(isNaN(data) == false) {
				var res = '';
				switch(data) {
				case '1':
					res = '<strong>>ERRO 403:</strong> Acesso negado!';
					break;

				case '2':
					res = '<strong>ERRO:</strong> Usuário não encontrado';
					break;

				case '3':
					res = '<strong>ERRO:</strong> Senha errada';
					break;

				case '4':
					res = '<strong>ERRO:</strong> Você teve 3 tentativas de login sem sucesso...';
					break;

				default:
					res = 'Servidor retornou algo desconhecido <br>';
					res += data;
					break;
				}
				$('#message').html(res);
			}
		});
		return false;
	});
});

$(document).ajaxStart(function(){
	$("#message").html('<img src="images/Preloader.gif" width="50" heigth="50">');
});

</script>
</head>

<body>
	<div class="container">
		<div class="title-page">
			<span style="font-size:60%">DEPARTAMENTO DE HOSPEDAGEM</span><br><span>LS-03</span>
		</div>
		<div class="login-page">
			<div class="form">
				<form class="login-form" id="formulario" action="#" method="post">
					<input name="usuario" type="text" placeholder="usuário" autofocus="autofocus" />
					<input name="senha" type="password" placeholder="senha"/>
					<button type="submit">login</button>
					<div id="message" class="message" style="text-align:center">
					
					</div>
				</form>
			</div>
		</div>
	</div>
</body>
</html>