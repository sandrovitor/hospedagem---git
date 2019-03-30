<?php
include_once('conecta.php');
// Configura timezone.
date_default_timezone_set("Brazil/East");

// Verifica se já existe sessão.
session_start();
if(isset($_SESSION['logado']) && $_SESSION['logado'] == TRUE) {
	//header('Location: ./');
	exit('OK');
}


if(isset($_POST['usuario']) && isset($_POST['senha']) && $_POST['usuario'] <> '' && $_POST['senha'] <> ''){
	$erro = ''; // Variavel de erros
	$abc = $pdo->prepare('SELECT * FROM login WHERE usuario = :usuario');
	$abc->bindValue(":usuario", $_POST['usuario'], PDO::PARAM_STR);
	$abc->execute();

	if($abc->rowCount() > 0) {
		$reg = $abc->fetch(PDO::FETCH_OBJ);
		
		//Verifica quantidade de tentativas.
		if($reg->tentativas >= 3) {
			// Excedeu número de tentativas
			exit('4');
		}

		// Verifica senhas
		if(hash("sha256", $_POST['senha']) === $reg->senha) {
			// Autoriza login!
			// Encerra sessão antes de modificar o INI_SET()
			session_unset();
			session_destroy();
			
			ini_set('session.use_cookies', '1'); // Ativa o uso de cookies
			ini_set('session.use_only_cookies', '1'); // Ativa SOMENTE o uso de cookies, sem trans_id
			ini_set('session.use_trans_sid', '0'); // Desliga o trans_id
				
			//Inicia a sessão
			session_start();
			$_SESSION['id'] = $reg->id;
			$_SESSION['nome'] = $reg->nome;
			$_SESSION['sobrenome'] = $reg->sobrenome;
			$_SESSION['usuario'] = $reg->usuario;
			$_SESSION['nivel'] = $reg->nivel;
			$_SESSION['tel_res'] = $reg->tel_res;
			$_SESSION['tel_cel'] = $reg->tel_cel;
			$_SESSION['email'] = $reg->email;
			$_SESSION['hora_login'] = date('Y-m-d H:i:s');
			$_SESSION['logado'] = TRUE;
			
			$login_qtd = (int)$reg->login_qtd + 1;
				
			$abc = $pdo->prepare('UPDATE login SET tentativas = 0, login_qtd = :qtd, login_data = :data WHERE id = :id');
			$abc->bindValue(":qtd", $login_qtd, PDO::PARAM_INT);
			$abc->bindValue(":data", date('Y-m-d H:i'), PDO::PARAM_STR);
			$abc->bindValue(":id", $reg->id, PDO::PARAM_INT);
			$abc->execute();
			
			unset($reg, $abc);
			//header('Location: ./');
			exit('OK');
		} else {
			// SENHA ERRADAS
			$erro++;
			$tentativas = $reg->tentativas + 1;
			$abc = $pdo->prepare('UPDATE login SET tentativas = '.$tentativas.' WHERE id = :id');
			$abc->bindValue(":id", $reg->id, PDO::PARAM_INT);
			$abc->execute();
			
			session_unset();
			session_destroy();
			exit('3');
		}
	} else {
		$erro++;
		session_unset();
		session_abort();
		exit('2');
	}
} else {
	exit('1');
}
?>
