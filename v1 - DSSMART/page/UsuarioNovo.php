<?php 
if(isset($_POST['nome']) && $_POST['nome'] <> '') {
	//Verifica se todos as variáveis foram enviadas
	if($_POST['usuario'] <> '' && (($_POST['senha1'] <> '' && $_POST['senha2'] <> '') || isset($_POST['senha_pad']))) {
		
		// ###### VERIFICAÇÃO DE SEGURANÇA REDUNDANTE
		if(isset($_POST['senha_pad']) && $_POST['senha_pad'] == 'Y') { // Se enviado senha padrão
			// Escreve senha padrão no $_POST
			$_POST['senha1'] = '123';
			$_POST['senha2'] = '123';
		} else if($_POST['senha1'] <> '' && $_POST['senha2'] <> '') { // Se enviado senha diferente da padrão
			// Não faz nada, deixa passar
		} else { // Erro, nenhum senha enviada.
			echo <<<DADOS
			
<div class="alert alert-warning">
	<strong>Opa!</strong> Há algo de errado com a senha! Tente novamente <a href="javascript: window.history.go(-1)">clicando aqui</a>.
</div>
DADOS;

			exit();
		}
		// ###### FIM DA VERIFICAÇÃO DE SEGURANÇA REDUNDANTE
		
		
		$erro = 0; // Variavel conta erros
		// Verifica nome de usuário
		$abc = $pdo->prepare('SELECT * FROM login WHERE usuario LIKE :usuario');
		$abc->bindValue(":usuario", $_POST['usuario'], PDO::PARAM_STR);
		$abc->execute();
		
		if($abc->rowCount() > 0) {
			$erro++;
			echo <<<DADOS

<div class="alert alert-warning">
	<strong>Opa!</strong> Esse nome de usuário já existe.	
</div>
DADOS;
		}
		
		// Verifica senhas
		if($_POST['senha1'] != $_POST['senha2']) {
			$erro++;
			echo <<<DADOS
			
<div class="alert alert-warning">
	<strong>Opa!</strong> Senhas não conferem. Verifique se o Caps Lock não está ligado.
</div>
DADOS;
		}
		
		// Verifica se há erros. Se houver erros, não continua a inserir informações no banco
		if($erro == 0) {
			if(!isset($_POST['sobrenome'])) { // Se não for enviado, configura como vazio
				$_POST['sobrenome'] = '';
			}
			if(!isset($_POST['tel_res'])) { // Se não for enviado, configura como vazio
				$_POST['tel_res'] = '';
			}
			if(!isset($_POST['tel_cel'])) { // Se não for enviado, configura como vazio
				$_POST['tel_cel'] = '';
			}
			if(!isset($_POST['email'])) { // Se não for enviado, configura como vazio
				$_POST['email'] = '';
			}
			
			$abc = $pdo->prepare('INSERT INTO login (id, nome, sobrenome, usuario, senha, nivel, criado, tel_res, tel_cel, email) VALUES (NULL, :nome, :sobrenome, :usuario, :senha, :nivel, :criado, :tel_res, :tel_cel, :email)');
			$abc->bindValue(":nome", addslashes($_POST['nome']), PDO::PARAM_STR);
			$abc->bindValue(":sobrenome", addslashes($_POST['sobrenome']), PDO::PARAM_STR);
			$abc->bindValue(":usuario", $_POST['usuario'], PDO::PARAM_STR);
			$abc->bindValue(":senha", hash("sha256", $_POST['senha1']), PDO::PARAM_STR);
			$abc->bindValue(":nivel", (int)$_POST['nivel'], PDO::PARAM_INT);
			$abc->bindValue(":criado", date('Y-m-d H:i:s'), PDO::PARAM_STR);
			$abc->bindValue(":tel_res", $_POST['tel_res'], PDO::PARAM_STR);
			$abc->bindValue(":tel_cel", $_POST['tel_cel'], PDO::PARAM_STR);
			$abc->bindValue(":email", addslashes($_POST['email']), PDO::PARAM_STR);
			
			try {
				$abc->execute();
				echo <<<DADOS
				
<div class="alert alert-success">
	<strong>Agora sim!</strong> Esse novo usuário, <i>$_POST[nome] $_POST[sobrenome]</i>, já pode fazer login com o usuário e senha dele.
</div>
DADOS;
				
			} catch(PDOException $e) {
				echo<<<DADOS
<div class="alert alert-warning">
	<strong>Aconteceu um erro!</strong> <i>$e->getMessage()</i>		
</div>
DADOS;
			}
		}
		
	} else {
		
		echo <<<DADOS
		
<div class="alert alert-danger">
	<strong>Espere um pouco!</strong> Você não forneceu tudo que a gente precisa. Preencha todos os dados do formulário abaixo.
</div>
DADOS;
	}
}
?>
<script>
	function senhaCheck(x) {
		if(x.checked == true) {
			$("#senha_box").fadeOut('fast');
		} else {
			$("#senha_box").fadeIn('fast');
		}
	}
</script>

<h4><strong><span class="glyphicon glyphicon-plus"></span> NOVO USUÁRIO</strong></h4><hr>

<div class="row">
	<form id="form1" method="post" action="#">
		<div class="col-md-4 col-sm-6">
			<div class="form-group">
				<label for="form1_nome">Nome:</label>
				<input type="text" id="form1_nome" name="nome" class="form-control" autofocus="autofocus">
				<span class="help-block">Somente o primeiro nome. <strong>Ex.:</strong> <i>André, João...</i></span>
			</div>
			<div class="form-group">
				<label for="form1_sobrenome">Sobrenome:</label>
				<input type="text" id="form1_sobrenome" name="sobrenome" class="form-control">
				<span class="help-block">Somente o último nome. <strong>Ex.:</strong> <i>Oliveira, Silva...</i></span>
			</div>
			<div class="form-group">
				<label for="form1_usuario">Usuário</label>
				<input type="text" id="form1_usuario" name="usuario" class="form-control">
				<span class="help-block">Nome para fazer login no sistema. <strong>Ex.:</strong> <i>AndreO, JoaoS...</i></span>
			</div>
			<div class="form-group">
				<div class="checkbox">
					<label><input type="checkbox" name="senha_pad" id="senha_pad" value="Y" onclick="javascript: senhaCheck(this)" checked="checked"><strong>Usar senha padrão (123)</strong></label>
					<span class="help-block">Desmarque para criar uma senha. Se marcada, senha padrão do sistema será criada.</span>
				</div>
			</div>
			<div id="senha_box" style="display:none;" class="well sm-well">
				<div class="form-group">
					<label for="form1_senha1">Senha:</label>
					<input type="password" id="form1_senha1" name="senha1" class="form-control">
					<span class="help-block">Letras e números. Mínimo 6 caracteres, máximo 15 caracteres.</span>
				</div>
				<div class="form-group">
					<label for="form1_senha2">Repita a senha:</label>
					<input type="password" id="form1_senha2" name="senha2" class="form-control">
					<span class="help-block">Repita a senha criada no campo anterior.</span>
				</div>
			</div>
		</div>
		<div class="col-md-4 col-sm-6">
			<div class="form-group">
				<label for="form1_tel_res">Telefone Residencial:</label>
				<input type="text" id="form1_tel_res" name="tel_res" class="form-control">
				<span class="help-block">Número de telefone residencial com o DDD. Somente números. <strong>Ex.:</strong> <i>7133123456...</i></span>
			</div>
			<div class="form-group">
				<label for="form1_tel_cel">Telefone Celular:</label>
				<input type="text" id="form1_tel_cel" name="tel_cel" class="form-control">
				<span class="help-block">Número de telefone celular com o DDD e nono dígito. Somente números <strong>Ex.:</strong> <i>71988881234...</i></span>
			</div>
			<div class="form-group">
				<label for="form1_email">E-mail:</label>
				<input type="text" id="form1_email" name="email" class="form-control">
			</div>
			<div class="form-group">
				<label for="form1_nivel">Nível de acesso:</label>
				<select id="form1_nivel" name="nivel" class="form-control">
					<option value="1">Solicitante de Hospedagem</option>
					<option value="10">Responsável da Hospedagem na Cidade</option>
					<option value="20">Administrador do Sistema</option>
				</select>
			</div>
			<div class="form-group pull-right">
				<button type="submit" class="btn btn-success"><strong>Criar</strong></button>
				<button type="reset" class="btn btn-danger"><strong>Resetar</strong></button>
			</div>
		</div>
	</form>
</div><hr>

<div class="row">
	<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<a data-toggle="collapse" data-target="#Panel1"><strong>LISTA COMPLETA DE USUÁRIOS</strong></a>
				</div>
				<div id="Panel1" class="panel-collapse collapse">
					<div class="panel-body">
						<?php include('app/usuarios_lista.php');?>
					</div>
				</div>
			</div>
		</div>
</div>