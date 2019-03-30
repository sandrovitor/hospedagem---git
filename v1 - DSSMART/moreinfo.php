<?php include_once('app/conecta.php');?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Hospedagem LS-03</title>
<!-- Bootstrap CSS -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<link rel="icon" href="images/favicon.png" />
<link href="css/style.css" rel="stylesheet">
<link href="css/chosen.css" rel="stylesheet">
<style>
body {
	padding-top: 10px;
}
.container-fluid {
	padding: 0 10px;
}
</style>

</head>
<body>


<?php
/*
 * LINK PARA ACESSO EXTERNO!
 * 
 * VALIDATION = Código criptografado em MD5. O código é: 'hospedagem' + codigo do pedido de hospedagem (enviado no TARGET) + 'ls3'.
 * TARGET = Número do pedido de hospedagem em encriptação BASE64.
 * 
 * Em QR Codes, o link vai ficar um pouco diferente. O padrão é: TARGET + 'qrcode' + VALIDATION.
 * Isso permite que várias variáveis viajem juntas na mesma QueryString.
 */

function acessoNegado() {
	echo <<<DADOS

<!-- CONTEUDO -->
<div class="container-fluid">
	<div class="row">
		<div class="col-xs-12">
			<div style="margin: 100px auto; width:300px; text-align:center;">
				<h4><strong>Acesso não autorizado!</strong></h4>
			</div>
		</div>
	</div>
</div>
<!--  FIM DO CONTEUDO -->
DADOS;
}

$source = '';

if(isset($_GET['validation']) && !isset($_GET['target']))  {
	// Verifica se foi o validation tem o termo 'qrcode';
	$y = strpos($_GET['validation'], 'qrcode');
	if($y != FALSE) {
		$y = explode('-qrcode-', $_GET['validation']);
		$_GET['target'] = $y[0];
		$_GET['validation'] = $y[1];
		$source = '<span class="glyphicon glyphicon-qrcode"></span> Acessado via QR-Code.';
	}
	
} 

if(isset($_GET['validation']) && isset($_GET['target'])) {
	$target = base64_decode($_GET['target']);
	if($target == FALSE) {
		acessoNegado();
	} else {
		$validation = md5('hospedagem'.$target.'ls3');
		if($validation == $_GET['validation']) { // AUTORIZADO!!
			?>

<!-- CONTEUDO -->
<div class="container-fluid">
	<div class="row">
		<div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
		 <?php 
		 $id = $target;
		 // Busca pedido de hospedagem no banco de dados
		 $abc = $pdo->prepare('SELECT peh.*, cidade.cidade AS congregacao_cidade, cidade.estado FROM peh LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE peh.id = :id');
		 $abc->bindValue(":id", $id, PDO::PARAM_INT);
		 $abc->execute();
		 
		 if($abc->rowCount() > 0) {
		 	$reg = $abc->fetch(PDO::FETCH_OBJ);
		 	
		 	echo <<<DADOS
		 	
						<h4 class="text-center"><strong>INFORMAÇÕES DA HOSPEDAGEM</strong></h4>
						<table class="table table-bordered table-responsive">
							<tbody>
								<tr>
									<td><strong>Pedido nº:</strong> $reg->id</td>
									<td><strong>Congregação:</strong> $reg->congregacao</td>
									<td><strong>Cidade:</strong> $reg->congregacao_cidade/$reg->estado</td>
								</tr>
								<tr>
									<td colspan="3">
										<strong>Ocupantes:</strong>
									</td>
								</tr>
DADOS;
		 	
		 	
		 	if($reg->oc1_nome <> '') { // OCUPANTE 1
		 		echo <<<DADOS
		 		
								<tr>
									<td>$reg->oc1_nome</td>
									<td><strong>Idade:</strong> $reg->oc1_idade</td>
									<td><strong>Privilégio:</strong> $reg->oc1_privilegio</td>
								</tr>
DADOS;
		 	}
		 	if($reg->oc2_nome <> '') { // OCUPANTE 2
		 		echo <<<DADOS
		 		
								<tr>
									<td>$reg->oc2_nome</td>
									<td><strong>Idade:</strong> $reg->oc2_idade</td>
									<td><strong>Privilégio:</strong> $reg->oc2_privilegio</td>
								</tr>
DADOS;
		 	}
		 	if($reg->oc3_nome <> '') { // OCUPANTE 3
		 		echo <<<DADOS
		 		
								<tr>
									<td>$reg->oc3_nome</td>
									<td><strong>Idade:</strong> $reg->oc3_idade</td>
									<td><strong>Privilégio:</strong> $reg->oc3_privilegio</td>
								</tr>
DADOS;
		 	}
		 	if($reg->oc4_nome <> '') { // OCUPANTE 4
		 		echo <<<DADOS
		 		
								<tr>
									<td>$reg->oc4_nome</td>
									<td><strong>Idade:</strong> $reg->oc4_idade</td>
									<td><strong>Privilégio:</strong> $reg->oc4_privilegio</td>
								</tr>
DADOS;
		 	}
		 	
		 	
		 	
		 	echo <<<DADOS
		 	
							</tbody>
						</table>
						<br>
DADOS;
		 	
		 	
		 	// Verifica se já foi atendido por uma acomodação
		 	if($reg->fac_id != 0) { // PEDIDO ATENDIDO
		 		$def = $pdo->query('SELECT fac.*, cidade.cidade, cidade.estado FROM fac LEFT JOIN cidade ON fac.cidade = cidade.id WHERE fac.id = '.$reg->fac_id);
		 		
		 		if($def->rowCount() > 0) {
		 			$lin = $def->fetch(PDO::FETCH_OBJ);
		 			
		 			// Trata telefone do hospedeiro
		 			if($lin->telefone <> '') {
		 				$tel1 = $lin->telefone;
		 				$tel1 = '('.substr($tel1, 0,2).') '.substr($tel1, 2, -4).'-'.substr($tel1, -4, 4);
		 			} else {
		 				$tel1 = '-';
		 			}
		 			
		 			
		 			
		 			// CASA DE TJ
		 			if($lin->casa_tj == 0) {
		 				$casa_tj = '<span style="color:#ff0000; margin-left: 10px; font-weight: bold;"><span class="glyphicon glyphicon-remove"></span> NÃO</span>';
		 			} else {
		 				$casa_tj = '<span style="color:#33cc33; margin-left: 10px; font-weight: bold;"><span class="glyphicon glyphicon-ok"></span> SIM</span>';
		 			}
		 			
		 			// CAMAS
		 			$cama_sol = $lin->quarto1_sol_qtd + $lin->quarto2_sol_qtd + $lin->quarto3_sol_qtd + $lin->quarto4_sol_qtd;
		 			$cama_cas = $lin->quarto1_cas_qtd + $lin->quarto2_cas_qtd + $lin->quarto3_cas_qtd + $lin->quarto4_cas_qtd;
		 			$camas = $cama_cas + $cama_sol;
		 			
		 			
		 			
		 			
		 			echo <<<DADOS
		 			
						<table class="table table-bordered table-responsive">
							<tbody>
								<tr>
									<td colspan="3"><strong>Acomodação nº:</strong> $lin->id</td>
								</tr>
								<tr>
									<td><strong>Hospedeiro:</strong> $lin->nome</td>
									<td><strong>Endereço:</strong> $lin->endereco - $lin->cidade/$lin->estado</td>
									<td><strong>Telefone:</strong> $tel1</td>
								</tr>
								<tr>
									<td><strong>Qtd. de camas?</strong> <span class="badge" style="font-size:14px; margin-left:10px;">$camas</span></td>
									<td colspan="2"><strong>Lar de Testemunha de Jeová?</strong> $casa_tj</td>
								</tr>
							</tbody>
						</table>
DADOS;
		 		} else { // PEDIDO NÃO ENCONTRADO
		 			echo <<<DADOS
		 			
						<table class="table table-bordered table-responsive">
							<tbody>
								<tr>
									<td colspan="3"><strong>Acomodação nº:</strong> <span class="label label-danger" style="font-size:16px"> PEDIDO NÃO ENCONTRADO </span></td>
								</tr>
							</tbody>
						</table>
DADOS;
		 			}
		 			
		 			
		 	} else { // PEDIDO ABERTO!
		 		echo <<<DADOS
		 		
						<table class="table table-bordered table-responsive">
							<tbody>
								<tr>
									<td colspan="3"><strong>Acomodação nº:</strong> <span class="label label-warning" style="font-size:16px"> EM ABERTO </span></td>
								</tr>
							</tbody>
						</table>
DADOS;
		 	}
		 	
		 	// RESPONSÁVEL PELA ACOMODAÇÃO
		 	
		 	// Procura cidade no banco de dados
		 	$def = $pdo->query('SELECT cidade.*, login.nome, login.sobrenome, login.tel_res, login.tel_cel, login.email FROM cidade LEFT JOIN login ON cidade.resp_id = login.id WHERE cidade.id = '.$reg->congregacao_cidade_id);
		 	$lin = $def->fetch(PDO::FETCH_OBJ);
		 	
		 	if($lin->tel_res != '' || $lin->tel_cel != '') {
		 		// Trata telefone do hospedeiro
		 		if($lin->tel_res <> '') {
		 			$tel1 = $lin->tel_res;
		 			$tel1 = '('.substr($tel1, 0,2).') '.substr($tel1, 2, -4).'-'.substr($tel1, -4, 4);
		 		} else {
		 			$tel1 = '';
		 		}
		 		
		 		// Trata telefone do publicador que indicou
		 		if($lin->tel_cel <> '') {
		 			$tel2 = $lin->tel_cel;
		 			$tel2 = '('.substr($tel2, 0,2).') '.substr($tel2, 2, -4).'-'.substr($tel2, -4, 4);
		 		} else {
		 			$tel2 = '';
		 		}
		 		
		 		if($tel1 != '' && $tel2 != '') {
		 			$telefone = $tel1.'; '.$tel2;
		 		} else {
		 			$telefone = $tel1.$tel2;
		 		}
		 		
		 	} else {
		 		$telefone = '-';
		 	}
		 	
		 	
		 	echo <<<DADOS
		 	
						<table class="table table-bordered table-responsive">
							<tbody>
								<tr>
									<td colspan="3" class="text-center"><strong>RESPONSÁVEL PELA ACOMODAÇÃO</strong></td>
								</tr>
								<tr>
									<td><strong>Nome:</strong> $lin->nome $lin->sobrenome</td>
									<td><strong>Telefone(s):</strong> $telefone</td>
									<td><strong>E-mail:</strong> $lin->email</td>
								</tr>
							</tbody>
						</table>

						<div style="width:100%; margin:0; text-align:center; font-weight:bold; color: #aaa; font-family: 'Trebuchet MS', 'Lucida Sans', sans-serif;">
							$source
						</div>
DADOS;
		 	
		 	
		 }
		 ?>
		 
		
		 
		 
		</div>
	</div>
</div>
<!--  FIM DO CONTEUDO -->

			<?php
		} else { // NÃO AUTORIZADO
			acessoNegado();
		}
	}
} else {
	acessoNegado();
}?>

<?php include('--footer.php');?>