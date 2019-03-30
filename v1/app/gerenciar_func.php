<?php


function carListaPeh() { // Carrega lista de pedidos de hospedagem
	include_once('session.php');
	include_once('conecta.php');
	echo <<<DADOS

									<option value="0" data-fac="0">Escolha um pedido de hospedagem:</option>
DADOS;
	if($_SESSION['nivel'] == 10) {
		$def = $pdo->query('SELECT DISTINCT peh.congregacao_cidade_id, cidade.cidade, cidade.estado FROM peh LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE cidade.resp_id = '.$_SESSION['id'].' ORDER BY cidade.estado ASC, cidade.cidade ASC');
		
	} else if($_SESSION['nivel'] == 20) {
		$def = $pdo->query('SELECT DISTINCT peh.congregacao_cidade_id, cidade.cidade, cidade.estado FROM peh LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE 1 ORDER BY cidade.estado ASC, cidade.cidade ASC');
		
	}
	
	$cidade = ''; $estado = '';
	if($def->rowCount() > 0) {
		while($lin = $def->fetch(PDO::FETCH_OBJ)) {
			if($cidade == '') {
				echo '<optgroup label="'.$lin->cidade.'/'.$lin->estado.'">';
				$cidade = $lin->cidade;
				$estado = $lin->estado;
			} else if($cidade <> $lin->cidade) {
				echo '</optgroup>
													<optgroup label="'.$lin->cidade.'/'.$lin->estado.'">';
				$cidade = $lin->cidade;
				$estado = $lin->estado;
			}
			
			$abc = $pdo->query('SELECT peh.id, peh.fac_id, peh.revisar FROM peh WHERE congregacao_cidade_id = '.$lin->congregacao_cidade_id.' ORDER BY data ASC');
			if($abc->rowCount() > 0) {
				while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
					$token = md5($reg->id.session_id());
					if($reg->fac_id == 0) {
						echo '<option value="'.$reg->id.'" data-fac="'.$reg->fac_id.'" data-revisar="'.$reg->revisar.'" data-token="'.$token.'">Pedido - Nº '.$reg->id.'</option>';
					} else {
						echo '<option value="'.$reg->id.'" data-fac="'.$reg->fac_id.'" data-revisar="'.$reg->revisar.'">Pedido - Nº '.$reg->id.' &nbsp; [OK!]</option>';
					}
				}
			}
		}
		echo '</optgroup>';
	}
}

function carListaFac() { // Carrega lista de acomodações
	include_once('session.php');
	include_once('conecta.php');
	echo <<<DADOS
	
									<option value="0">Escolha uma acomodação:</option>
DADOS;
	if($_SESSION['nivel'] == 10) {
		$def = $pdo->query('SELECT DISTINCT fac.cidade AS cidade_id, cidade.cidade, cidade.estado FROM fac LEFT JOIN cidade ON fac.cidade = cidade.id WHERE cidade.hospedeiro = 1 AND cidade.resp_id = '.$_SESSION['id'].' ORDER BY cidade.estado ASC, cidade.cidade ASC');
		
	} else if($_SESSION['nivel'] == 20) {
		$def = $pdo->query('SELECT DISTINCT fac.cidade AS cidade_id, cidade.cidade, cidade.estado FROM fac LEFT JOIN cidade ON fac.cidade = cidade.id WHERE cidade.hospedeiro = 1 ORDER BY cidade.estado ASC, cidade.cidade ASC');
		
	}
	
	$cidade = ''; $estado = '';
	if($def->rowCount() > 0) {
		while($lin = $def->fetch(PDO::FETCH_OBJ)) {
			if($cidade == '') {
				echo '<optgroup label="'.$lin->cidade.'/'.$lin->estado.'">';
				$cidade = $lin->cidade;
				$estado = $lin->estado;
			} else if($cidade <> $lin->cidade) {
				echo '</optgroup>
													<optgroup label="'.$lin->cidade.'/'.$lin->estado.'">';
				$cidade = $lin->cidade;
				$estado = $lin->estado;
			}
			
			$vinculado = '';
			
			$abc = $pdo->query('SELECT fac.id FROM fac WHERE cidade = '.$lin->cidade_id);
			if($abc->rowCount() > 0) {
				while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
					
					$xyz = $pdo->query('SELECT peh.id FROM peh WHERE peh.fac_id = '.$reg->id);
					if($xyz->rowCount() > 0) {
						$vinculado = ' &nbsp; [OK!]';
					} else {
						$vinculado = '';
					}
					
					echo '<option value="'.$reg->id.'">Acomodação - Nº '.$reg->id.$vinculado.'</option>';
				}
			}
		}
		echo '</optgroup>';
	}
	unset($cidade, $estado, $abc, $def, $xyz, $vinculado, $reg, $lin);
}

function vincularForms($pehid, $facid) {
	if((int)$pehid == 0 || (int)$facid == 0) { // Não aceita valores nulos e que não sejam números
		exit('Dados enviados estão incorretos!');
	}
	include('conecta.php');
	
	$abc = $pdo->prepare('SELECT * FROM peh WHERE id = :id');
	$abc->bindValue(":id", $pehid, PDO::PARAM_INT);
	$abc->execute();
	
	if($abc->rowCount() == 0) { // Nada encontrado, encerra.
		exit('Pedido de hospedagem não encontrado.');
	}
	$peh = $abc->fetch(PDO::FETCH_OBJ); // ############### LINHA DO PEH!
	
	$abc = $pdo->prepare('SELECT * FROM fac WHERE id = :id');
	$abc->bindValue(":id", $facid, PDO::PARAM_INT);
	$abc->execute();
	
	if($abc->rowCount() == 0) { // Nada encontrado, encerra.
		exit('Formulário de acomodação não encontrado');
	}
	$fac = $abc->fetch(PDO::FETCH_OBJ); // ################ LINHA DO FAC!
	
	
	// VERIFICO SE O PEDIDO DE HOSPEDAGEM JÁ FOI VINCULADO A UMA ACOMODAÇÃO.
	if($peh->fac_id == 0) {
		// PEDIDO EM ABERTO. INICIA PROCESSO DE VINCULAÇÃO.
		
		// Antes, verifica se a acomodação já etá vinculada a outra hospedagem.
		$abc = $pdo->prepare('SELECT peh.id FROM peh WHERE fac_id = :id');
		$abc->bindValue(":id", $facid, PDO::PARAM_INT);
		$abc->execute();
		
		if($abc->rowCount() == 0) { // Não há vinculos! Continua vinculação!
			$abc = $pdo->prepare('UPDATE peh SET fac_id = :facid WHERE id = :pehid');
			$abc->bindValue(":facid", $facid, PDO::PARAM_INT);
			$abc->bindValue(":pehid", $pehid, PDO::PARAM_INT);
			
			$abc->execute();
		} else { // Há vinculos! Para!!
			exit('Acomodação vinculada com outra hospedagem.');
		}
	} else {
		// PEDIDO NÃO ESTÁ EM ABERTO
		exit('Pedido de hospedagem vinculado a outra acomodação. Não é possivel continuar...');
	}
	
	echo 'OK';
	
}


function desvincularForms($pehid, $facid) {
	if((int)$pehid == 0 || (int)$facid == 0) { // Não aceita valores nulos e que não sejam números
		exit('Dados enviados estão incorretos!');
	}
	include('conecta.php');
	
	$abc = $pdo->prepare('SELECT * FROM peh WHERE id = :id');
	$abc->bindValue(":id", $pehid, PDO::PARAM_INT);
	$abc->execute();
	
	if($abc->rowCount() == 0) { // Nada encontrado, encerra.
		exit('Pedido de hospedagem não encontrado.');
	}
	$peh = $abc->fetch(PDO::FETCH_OBJ); // ############### LINHA DO PEH!
	
	$abc = $pdo->prepare('SELECT * FROM fac WHERE id = :id');
	$abc->bindValue(":id", $facid, PDO::PARAM_INT);
	$abc->execute();
	
	if($abc->rowCount() == 0) { // Nada encontrado, encerra.
		exit('Formulário de acomodação não encontrado');
	}
	$fac = $abc->fetch(PDO::FETCH_OBJ); // ################ LINHA DO FAC!
	
	
	// VERIFICO SE O PEDIDO DE HOSPEDAGEM JÁ FOI VINCULADO A ESTAACOMODAÇÃO.
	if($peh->fac_id == $fac->id) {
		// PEDIDO JÁ FOI VINCULADO. ENTÃO, INICIA PROCESSO DE DESVINCULAÇÃO.
		
		$abc = $pdo->prepare('UPDATE peh SET fac_id = 0 WHERE id = :pehid');
		$abc->bindValue(":pehid", $pehid, PDO::PARAM_INT);
		
		$abc->execute();
		
	} else {
		// PEDIDOS NÃO ESTÃO VINCULADOS
		exit('Pedido de Hospedagem e a Acomodação não estão vinculados.');
	}
	
	echo 'OK';
	
}

function desvAction($tipo, $id) {
	include('conecta.php');
	if($tipo == 'peh') {
		$abc = $pdo->prepare('UPDATE `peh` SET `fac_id` = 0 WHERE `id` = :id');
		$abc->bindValue(':id', $id, PDO::PARAM_INT);
		$abc->execute();
		exit('OK');
	} else if($tipo == 'fac') {
		$abc = $pdo->prepare('UPDATE `peh` SET `fac_id` = 0 WHERE `fac_id` = :id');
		$abc->bindValue(':id', $id, PDO::PARAM_INT);
		$abc->execute();
		exit('OK');
	}
	
	// Se chegou até aqui, retorna erro.
	exit('Erro! Contate desenvolvedor.');
}

function revisarForms($tipo, $id, $token) {
	include_once('conecta.php');
	include_once('session.php');
	if($tipo == 'peh') {
		$token_novo = md5($id.session_id());
		if($token == $token_novo) {
			$abc = $pdo->query('UPDATE `peh` SET `revisar` = 1 WHERE `id` = '.$id);
			echo 'OK';
		} else {
			exit('Erro 403: Houve algo de errado na confirmação da sua identidade. Tente novamente, ou contate desenvolvedor.');
		}
	} else if($tipo == 'fac') {
		
	} else {
		exit('Erro 404: Operação não pode ser concluída, pois o resultado foi inesperado!');
	}
}



if(isset($_POST['act'])) {
	switch($_POST['act']) {
		case 'carListaPeh':
			carListaPeh();
			break;
			
		case 'carListaFac':
			carListaFac();
			break;
			
		case 'vincularForms':
			vincularForms($_POST['pehid'], $_POST['facid']);
			break;
			
		case 'desvincularForms':
			desvincularForms($_POST['pehid'], $_POST['facid']);
			break;
			
		case 'desvAction':
			desvAction($_POST['tipo'], $_POST['id']);
			break;
			
		case 'revisarForms':
			revisarForms($_REQUEST['tipo'], $_REQUEST['id'], $_REQUEST['token']);
			break;
	}
}