<?php 
if(isset($_POST['nome']) && $_POST['nome'] <> '') {
	$erro = 0;
	// Verifica se todos os dados foram enviados
	if(!isset($_POST['sobrenome']) || $_POST['sobrenome'] == '') {
		$erro++;
	}
	if(!isset($_POST['tel_cel']) || $_POST['tel_cel'] == '') {
		$erro++;
	}
	if(!isset($_POST['email']) || $_POST['email'] == '') {
		$erro++;
	}
	
	// Verifica se a variavel de erros está vazia
	if($erro == 0) {
		include_once('conecta.php');
		$abc = $pdo->prepare('UPDATE login SET nome = :nome, sobrenome = :sobrenome, tel_res = :tel_res, tel_cel = :tel_cel, email = :email WHERE id = :id');
		$abc->bindValue(":nome", addslashes($_POST['nome']), PDO::PARAM_STR);
		$abc->bindValue(":sobrenome", addslashes($_POST['sobrenome']), PDO::PARAM_STR);
		$abc->bindValue(":tel_res", $_POST['tel_res'], PDO::PARAM_STR);
		$abc->bindValue(":tel_cel", $_POST['tel_cel'], PDO::PARAM_STR);
		$abc->bindValue(":email", addslashes($_POST['email']), PDO::PARAM_STR);
		$abc->bindValue(":id", $_SESSION['id'], PDO::PARAM_INT);
		
		try{
			$abc->execute();
			echo <<<DADOS
				
<div class="alert alert-success">
	<strong>Agora sim!</strong> Dados atualizados.
</div>
DADOS;
			$abc = $pdo->query('SELECT * FROM login WHERE id = '.$_SESSION['id']);
			$reg = $abc->fetch(PDO::FETCH_OBJ);
			
			$_SESSION['nome'] = $reg->nome;
			$_SESSION['sobrenome'] = $reg->sobrenome;
			$_SESSION['tel_res'] = $reg->tel_res;
			$_SESSION['tel_cel'] = $reg->tel_cel;
			$_SESSION['email'] = $reg->email;
			
		} catch(PDOException $e) {
				echo<<<DADOS
<div class="alert alert-warning">
	<strong>Aconteceu um erro!</strong> <i>$e->getMessage()</i>		
</div>
DADOS;
		}
	} else {
		// Retorna aviso de erro.
		echo <<<DADOS
		
<div class="alert alert-success">
	<strong>Espera um pouco!</strong> Nem todas as informações foram enviadas...
</div>
DADOS;
	}
	
}
?>