<?php
if(isset($_POST['senha_atual']) && $_POST['senha_atual'] <> '') {
	$erro = 0;
	// Verifica se todos os dados foram enviados
	if(!isset($_POST['nova_senha1']) || $_POST['nova_senha1'] == '') {
		$erro++;
		echo <<<DADOS
		
<div class="alert alert-warning">
	<strong>Calma!</strong> Você esqueceu de enviar sua senha nova.
</div>
DADOS;
	}
	if(!isset($_POST['nova_senha2']) || $_POST['nova_senha2'] == '') {
		$erro++;
		echo <<<DADOS
		
<div class="alert alert-warning">
	<strong>Calma!</strong> Você esqueceu de repetir sua senha nova.
</div>
DADOS;
	}
	
	if($_POST['nova_senha1'] <> $_POST['nova_senha2']) {
		$erro++;
		echo <<<DADOS

<div class="alert alert-warning">
	<strong>Espera um pouco!</strong> As senhas informadas não são iguais.
</div>
DADOS;
	}
		
	// Verifica se houve algum erro.
	if($erro == 0) {
		include_once('conecta.php');
		// Verifica se a senha atual é igual a senha informada.
		$abc = $pdo->query('SELECT * FROM login WHERE id = '.$_SESSION['id']);
		$reg = $abc->fetch(PDO::FETCH_OBJ);
		
		if(hash("sha256", $_POST['senha_atual']) == $reg->senha) {
			// Inicia processo de atualização
			$senha_nova = hash("sha256", $_POST['nova_senha1']);
			
			// verifica se a senha nova é igual a senha atual
			if($senha_nova != $reg->senha) {
				$abc = $pdo->query('UPDATE login SET senha = "'.$senha_nova.'" WHERE id = '.$_SESSION['id']);
				
				// SUCESSO! Senha trocada
				echo <<<DADOS
				
<div class="alert alert-success">
	<strong>Deu certo!</strong> A senha foi trocada. Já poderá usar no próximo login. <a href="logout.php">Clique aqui</a> para testá-la.
</div>
DADOS;
			} else {
				// Senha nova é igual senha atual
				echo <<<DADOS
				
<div class="alert alert-warning">
	<strong>Você não trocou a senha!</strong> A senha atual e a senha nova são iguais.
</div>
DADOS;
			}
			
		} else {
			// Senha atual diferente.
			echo <<<DADOS
			
<div class="alert alert-warning">
	<strong>Será que você esqueceu?</strong> A sua senha atual está errada. Tente de novo...
</div>
DADOS;
		}
	}
}