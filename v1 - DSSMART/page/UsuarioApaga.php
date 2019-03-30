<?php 
if((isset($_POST['usu_id']) && $_POST['usu_id'] <> '') || (isset($_GET['usu_id']) && $_GET['usu_id'] <> '') ) {
	// Se enviar id do usuário para exclusão (via POST ou GET).
	
	// Verifica se o usuário enviou confirmação.
	if(isset($_POST['confirma']) && isset($_POST['usuario']) && isset($_POST['senha'])) {
		// Enviou confirmação. Valida operação.
		
		// Verifica se o usuário é o mesmo que está logado
		if($_POST['usuario'] == $_SESSION['usuario']) {
			$abc = $pdo->prepare('SELECT * FROM login WHERE usuario = :usuario');
			$abc->bindValue(":usuario", $_POST['usuario'], PDO::PARAM_STR);
			$abc->execute();
		} else {
			$abc = $pdo->prepare('SELECT * FROM login WHERE usuario = :usuario');
			$abc->bindValue(":usuario", $_POST['usuario'], PDO::PARAM_STR);
			$abc->execute();
			
			if($abc->rowCount() == 0) {
				header('Location: admin.php?page=3&r=2&usu_id='.$_POST['usu_id']);
				exit();
			}
		}
		
		$reg = $abc->fetch(PDO::FETCH_OBJ);
		
		// Verifica confirmação da operação
		if(!isset($_POST['confirma']) || $_POST['confirma'] <> 'Y') {
			header('Location: admin.php?page=3');
		}
		
		// Verifica senhas
		if(hash("sha256", $_POST['senha']) == $reg->senha) {
			// Senha correta. Continua com a exclusão.
			$abc = $pdo->prepare('DELETE FROM login WHERE id = :id');
			$abc->bindValue(":id", (int)$_POST['usu_id'], PDO::PARAM_INT);
			$abc->execute();
			
			if($abc->rowCount() > 0) {
				header('Location: admin.php?page=3&r=1'); // Sucesso
				exit();
			} else {
				header('Location: admin.php?page=3&r=2'); // Sem sucesso
				exit();
			}
			
		} else {
			// Senha não são iguais. Retorna à página de confirmação.
			header('Location: admin.php?page=3&r=2&usu_id='.$_POST['usu_id']);
			exit();
		}
		
	} else {
		// Solicitação confirmação de identidade.
		
		?>
<style>
#box-main {
	margin:20px auto 0;
	padding:25px;
	width: 300px;
	background: #FFF;
	box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
	box-sizing: border-box;
}

#box-main input {
	width: 100%;
	margin: 10px 0;
	box-sizing: border-box;
	background: #e0e0d1;
	border: 0;
	padding: 15px;
	outline: #006622 2px solid;
}

#box-main button {
	text-align: center;
	margin: 10px 0;
	width: 100%;
	padding: 10px;
	font-weight: bold;
	box-sizing: border-box;
	background: #006622;
	color: #EEE;
	font-size: 120%;
	border: 0;
}
</style>
<h4><strong><span class="glyphicon glyphicon-erase"></span> APAGAR USUÁRIO </strong></h4><hr>

<?php 
if(isset($_GET['r'])) {
	switch($_GET['r']) {
		case 1:
			echo<<<DADOS

<div class="alert alert-success">
	<strong>Sucesso</strong> Operação foi realizada com sucesso.
</div>
DADOS;
	break;
	
		case 2:
			echo<<<DADOS
			
<div class="alert alert-danger">
	<strong>Revise!</strong> Usuário e/ou senha inválidos.
</div>
DADOS;
			break;
			
		default:
			echo '';
			break;
	}
}
?>

<div class="row">
	<div class="col-xs-12">
		<form method="post">
			<div id="box-main">
				<h5 class="text-center"><strong>Confirme sua identidade:</strong></h5>
				<input type="text" name="usuario" placeholder="usuario" autocomplete="off" autofocus>
				<input type="password" name="senha" placeholder="senha" autocomplete="off">
				<input type="hidden" name="usu_id" value="<?php if(isset($_POST['usu_id']) &&$_POST['usu_id']<>'') {echo $_POST['usu_id']; } else {echo $_GET['usu_id'];} ?>">
				<input type="hidden" name="confirma" value="Y">
				<button type="submit">Continuar &nbsp;&nbsp; <span class="glyphicon glyphicon-arrow-right"></span></button>
				<div style="text-align: right;">
					ou <a href="admin.php?page=3"> Cancelar </a>
				</div>
			</div>
		</form>
	</div>
</div>
		
		<?php
	}
	exit();
} 

?>

<h4><strong><span class="glyphicon glyphicon-erase"></span> APAGAR USUÁRIO</strong></h4><hr>

<?php 
if(isset($_GET['r'])) {
	switch($_GET['r']) {
		case 1:
			echo<<<DADOS

<div class="alert alert-success">
	<strong>Sucesso</strong> Operação foi realizada com sucesso.
</div>
DADOS;
	break;
	
		case 2:
			echo<<<DADOS
			
<div class="alert alert-danger">
	<strong>STOP!</strong> Operação encontrou problemas.
</div>
DADOS;
			break;
			
		default:
			echo '';
			break;
	}
}
?>

<div class="row">
	<div class="col-xs-12">
		<form method="post" action="">
			<table class="table table-striped table-condensed table-hover">
				<thead>
					<tr>
						<th></th>
						<th>Nome</th>
						<th>Usuário</th>
						<th>Nível de Acesso</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$abc = $pdo->query('SELECT * FROM login WHERE 1 ORDER BY nome ASC, sobrenome ASC');
					while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
						if($reg->nivel == 1) {
							$nivel = '<span class="label label-primary">Solicitante de Hospedagem</span>';
						} else if($reg->nivel == 10) {
							$nivel = '<span class="label label-warning">Responsável da Hospedagem na Cidade</span>';
						} else if($reg->nivel == 20) {
							$nivel = '<span class="label label-danger">Administrador do Sistema</span>';
						}
						
						
						if($reg->id == $_SESSION['id']) {
							echo <<<DADOS
							
					<tr>
						<td class="text-center"><input type="radio" name="usu_id" value="$reg->id" disabled></td>
						<td>$reg->nome $reg->sobrenome</td>
						<td>$reg->usuario</td>
						<td>$nivel</td>
					<tr>
DADOS;
						} else {
							echo <<<DADOS
							
					<tr>
						<td class="text-center"><input type="radio" name="usu_id" value="$reg->id"></td>
						<td>$reg->nome $reg->sobrenome</td>
						<td>$reg->usuario</td>
						<td>$nivel</td>
					<tr>
DADOS;
						}
						
					}
					?>
				</tbody>
			</table>
			<button type="submit" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span> <strong>REMOVER USUÁRIO</strong></button>
			<button type="reset" class="btn btn-primary"><span class="glyphicon glyphicon-refresh"></span> <strong>Desmarcar</strong></button>
		</form>
	</div>
</div>