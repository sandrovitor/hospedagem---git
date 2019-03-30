<?php include('--header.php');?>
<style>
dd {
	padding-left: 30px;
}
dt.cidade {
	color: 	#3884c7;
}
</style>
<!-- INICIO DO NAVBAR -->
<nav class="navbar navbar-default navbar-fixed-top">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span> 
			</button>
			<a class="navbar-brand" href="./"><strong>Hospedagem LS-03</strong></a>
		</div>
		<div class="collapse navbar-collapse" id="myNavbar">
			<ul class="nav navbar-nav">
				<li class="active"><a href="./"><span class="glyphicon glyphicon-home"></span> Início</a></li>
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-list-alt"></span> Formulários
					<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="formulario.php">Novo</a></li>
						<li><a href="formulario.consulta.php">Consultar</a></li>
						<?php if($_SESSION['nivel'] >= 10) {?>
						<li class="divider"></li>
						<li><a href="gerenciar.php">Gerenciar</a></li>
						<?php }?>
					</ul>
						
				</li>
				<?php if($_SESSION['nivel'] >= 10){?>
				<li><a href="atendimento.php"><span class="glyphicon glyphicon-headphones"></span> Atendimento</a></li>
				<?php } ?>
				<li><a href="ajuda.php"><span class="glyphicon glyphicon-question-sign"></span> Ajuda</a></li>
				<?php if($_SESSION['nivel'] >= 20){?>
				<li><a href="admin.php"><span class="glyphicon glyphicon-cog"></span> Administração</a></li>
				<?php }?>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span> <?php echo $_SESSION['nome'].' '.$_SESSION['sobrenome']; ?> 
					<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="perfil.php"><span class="glyphicon glyphicon-edit"></span> Meu perfil</a></li>
						<li role="separator" class="divider"></li>
						<li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Sair</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</nav>
<!-- FIM DO NAVBAR -->

<!-- CONTEUDO -->
<div class="container-fluid">

	<!-- BREADCRUMB -->
	<ul class="breadcrumb">
		<li class="active">Início</li>
	</ul>
	<!-- FIM DO BREADCRUMB -->
	
	<div class="row">
		<div class="col-xs-12">
			<div class="jumbotron" style="padding: 20px 30px;">
				<h3>Bem-vindo, <?php echo $_SESSION['nome'].' '.$_SESSION['sobrenome'];?>!</h3>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-sm-4">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h4 class="panel-title">Relatório de Acomodações</h4>
				</div>
				<div class="panel-body">
					<?php 
					// ######################################### ACOMODAÇÃO
					if($_SESSION['nivel'] == 1) {
						
						// ################# RELATÓRIO PARCIAL
						echo '<h4 class="text-center"><strong>RELATÓRIO PARCIAL</strong></h4>';
						
						$abc = $pdo->query('SELECT DISTINCT cidade.id, cidade.cidade, cidade.estado FROM cidade WHERE cidade.solicitante_id = '.$_SESSION['id'].' ORDER BY estado ASC, cidade ASC');
						if($abc->rowCount() > 0) {
							echo <<<DADOS
						<dl>
DADOS;
							while($reg = $abc->fetch(PDO::FETCH_OBJ)){
								$def = $pdo->query('SELECT DISTINCT peh.id FROM peh WHERE peh.congregacao_cidade_id = '.$reg->id.' AND peh.fac_id <> 0');
								$atendidos = $def->rowCount();
								
								$def = $pdo->query('SELECT DISTINCT peh.id FROM peh WHERE peh.congregacao_cidade_id = '.$reg->id.' AND peh.fac_id = 0');
								$pendentes = $def->rowCount();
								
								echo <<<DADOS
							<dt class="cidade"><span class="glyphicon glyphicon-map-marker"></span> $reg->cidade/$reg->estado</dt>
							<dd>PEDIDOS ATENDIDOS: <span class="label label-success">$atendidos</span></dd>
							<dd>PEDIDOS PENDENTES: <span class="label label-warning">$pendentes</span></dd>

DADOS;
							}
							
							echo <<<DADOS
						</dl>
DADOS;
						} else {
							echo 'Não há cidades designadas';
						}
						
						unset($atendidos, $pendentes, $abc, $def, $reg);
					} else if($_SESSION['nivel'] == 10) {
						
						// ################# RELATÓRIO PARCIAL
						echo '<h4 class="text-center"><strong>RELATÓRIO PARCIAL</strong></h4>';
						
						// Relatório da área do usuário
						$abc = $pdo->query('SELECT DISTINCT fac.cidade AS cidade_id, cidade.cidade, cidade.estado FROM fac LEFT JOIN cidade ON fac.cidade = cidade.id WHERE cidade.resp_id = '.$_SESSION['id'].' ORDER BY estado ASC, cidade ASC');
						if($abc->rowCount() > 0) {
							$cidade = '';
							$cama_sol = 0;
							$cama_cas = 0;
							echo '<dl>';
							while($lin = $abc->fetch(PDO::FETCH_OBJ)) {
								if($cidade == '') {
									echo '<dt class="cidade"><span class="glyphicon glyphicon-map-marker"></span> '.$lin->cidade.'/'.$lin->estado.'</dt>';
									$cidade = $lin->cidade;
								} else if($cidade <> $lin->cidade) {
									// Primeiro escreve resultado dos calculos
									echo '<dd>Camas de Solteiro: <span class="badge">'.$cama_sol.'</span></dd>';
									echo '<dd>Camas de Casal: <span class="badge">'.$cama_cas.'</span></dd>';
									
									// zera variáveis
									$cama_sol = 0;
									$cama_cas = 0;
									
									echo '<dt class="cidade"><span class="glyphicon glyphicon-map-marker"></span> '.$lin->cidade.'/'.$lin->estado.'</dt>';
									$cidade = $lin->cidade;
								}
								
								$def = $pdo->query('SELECT * FROM fac WHERE fac.cidade = '.$lin->cidade_id);
								while($reg = $def->fetch(PDO::FETCH_OBJ)) {
									$cama_sol = $reg->quarto1_sol_qtd + $reg->quarto2_sol_qtd + $reg->quarto3_sol_qtd + $reg->quarto4_sol_qtd;
									$cama_cas = $reg->quarto1_cas_qtd + $reg->quarto2_cas_qtd + $reg->quarto3_cas_qtd + $reg->quarto4_cas_qtd;
									
								}
								
								
							}
							// Aantes de fechar a tag, escreve valores dos calculos.
							echo '<dd>Camas de Solteiro: <span class="badge">'.$cama_sol.'</span></dd>';
							echo '<dd>Camas de Casal: <span class="badge">'.$cama_cas.'</span></dd>';
							
							echo '</dl>';
						} else {
							echo '<i>Ainda sem acomodações...</i>';
						}
					} else if($_SESSION['nivel'] == 20) {
						// ################# RELATÓRIO GERAL
						echo '<h4 class="text-center"><strong>RELATÓRIO GERAL</strong></h4>';
						
						$abc = $pdo->query('SELECT DISTINCT fac.cidade AS cidade_id, cidade.cidade, cidade.estado FROM fac LEFT JOIN cidade ON fac.cidade = cidade.id WHERE 1 ORDER BY estado ASC, cidade ASC');
						if($abc->rowCount() > 0) {
							$cidade = '';
							$cama_sol = 0;
							$cama_cas = 0;
							echo '<dl>';
							while($lin = $abc->fetch(PDO::FETCH_OBJ)) {
								if($cidade == '') {
									echo '<dt class="cidade"><span class="glyphicon glyphicon-map-marker"></span> '.$lin->cidade.'/'.$lin->estado.'</dt>';
									$cidade = $lin->cidade;
								} else if($cidade <> $lin->cidade) {
									// Primeiro escreve resultado dos calculos
									echo '<dd>Camas de Solteiro: <span class="badge">'.$cama_sol.'</span></dd>';
									echo '<dd>Camas de Casal: <span class="badge">'.$cama_cas.'</span></dd>';
										
									// zera variáveis
									$cama_sol = 0;
									$cama_cas = 0;
										
									echo '<dt class="cidade"><span class="glyphicon glyphicon-map-marker"></span> '.$lin->cidade.'/'.$lin->estado.'</dt>';
									$cidade = $lin->cidade;
								}
						
								$def = $pdo->query('SELECT * FROM fac WHERE fac.cidade = '.$lin->cidade_id);
								while($reg = $def->fetch(PDO::FETCH_OBJ)) {
									$cama_sol = $reg->quarto1_sol_qtd + $reg->quarto2_sol_qtd + $reg->quarto3_sol_qtd + $reg->quarto4_sol_qtd;
									$cama_cas = $reg->quarto1_cas_qtd + $reg->quarto2_sol_qtd + $reg->quarto3_sol_qtd + $reg->quarto4_sol_qtd;
										
								}
						
						
							}
							// Aantes de fechar a tag, escreve valores dos calculos.
							echo '<dd>Camas de Solteiro: <span class="badge">'.$cama_sol.'</span></dd>';
							echo '<dd>Camas de Casal: <span class="badge">'.$cama_cas.'</span></dd>';
								
							echo '</dl>';
						} else {
							echo '<i>Ainda sem acomodações...</i>';
						}
					}
					
					
					?>
				</div>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h4 class="panel-title">Relatório de Pedidos</h4>
				</div>
				<div class="panel-body">
					<?php 
					// ######################################### PEDIDOS HOSPEDAGEM
					if($_SESSION['nivel'] == 1) {
						
						// ################# RELATÓRIO PARCIAL
						echo '<h4 class="text-center"><strong>RELATÓRIO PARCIAL</strong></h4>';
						
						$abc = $pdo->query('SELECT DISTINCT peh.congregacao_cidade_id AS cidade_id, cidade.cidade, cidade.estado FROM `peh` LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE cidade.solicitante_id = '.$_SESSION['id'].' ORDER BY estado ASC, cidade ASC');
						if($abc->rowCount() > 0) {
							$cidade = '';
							$pessoas = 0;
							$pedidos = 0;
							$casa = 0;
							$hotel = 0;
							echo '<dl>';
							while($lin = $abc->fetch(PDO::FETCH_OBJ)) {
								if($cidade == '') {
									echo '<dt class="cidade"><span class="glyphicon glyphicon-map-marker"></span> '.$lin->cidade.'/'.$lin->estado.'</dt>';
									$cidade = $lin->cidade;
								} else if($cidade != $lin->cidade) {
									// Escreve resultado antes de trocar a cidade
									echo <<<DADOS
							<dd>Pedidos: <span class="badge">$pedidos</span></dd>
							<dd>Pessoas: <span class="badge">$pessoas</span></dd>
							<dd><ul>
								<li>Querem casa: <span class="badge">$casa</span></li>
								<li>Querem hotel: <span class="badge">$hotel</span></li>
							</ul></dd>
DADOS;
									
									//Zera variaveis
									$pessoas = 0;
									$pedidos = 0;
									$casa = 0;
									$hotel = 0;
									
									echo '<dt class="cidade"><span class="glyphicon glyphicon-map-marker"></span> '.$lin->cidade.'/'.$lin->estado.'</dt>';
									$cidade = $lin->cidade;
								}
								
								$def = $pdo->query('SELECT * FROM peh WHERE peh.congregacao_cidade_id = '.$lin->cidade_id);
								while($reg = $def->fetch(PDO::FETCH_OBJ)) {
									if($reg->oc1_nome <> '') {
										$pessoas++;
										if($reg->tipo_hospedagem == 'casa') {$casa++;} else {$hotel++;}
									}
									if($reg->oc2_nome <> '') {
										$pessoas++;
										if($reg->tipo_hospedagem == 'casa') {$casa++;} else {$hotel++;}
									}
									if($reg->oc3_nome <> '') {
										$pessoas++;
										if($reg->tipo_hospedagem == 'casa') {$casa++;} else {$hotel++;}
									}
									if($reg->oc4_nome <> '') {
										$pessoas++;
										if($reg->tipo_hospedagem == 'casa') {$casa++;} else {$hotel++;}
									}
									
									$pedidos++;
								}
							}
							// antes de fechar a tag HTML, escreve resultado
							echo <<<DADOS
							<dd>Pedidos: <span class="badge">$pedidos</span></dd>
							<dd>Pessoas: <span class="badge">$pessoas</span></dd>
							<dd><ul>
								<li>Querem casa: <span class="badge">$casa</span></li>
								<li>Querem hotel: <span class="badge">$hotel</span></li>
							</ul></dd>
DADOS;
							
							echo '</dl>';
							
						} else {
							echo '<i>Ainda sem pedidos...</i>';
						}
					} else if($_SESSION['nivel'] == 10) {
						
						// ################# RELATÓRIO PARCIAL
						echo '<h4 class="text-center"><strong>RELATÓRIO PARCIAL</strong></h4>';
						
						// Relatório da área do usuário
						$abc = $pdo->query('SELECT DISTINCT peh.congregacao_cidade_id AS cidade_id, cidade.cidade, cidade.estado FROM `peh` LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE cidade.resp_id = '.$_SESSION['id'].' ORDER BY estado ASC, cidade ASC');
						if($abc->rowCount() > 0) {
							$cidade = '';
							$pessoas = 0;
							$pedidos = 0;
							$casa = 0;
							$hotel = 0;
							echo '<dl>';
							while($lin = $abc->fetch(PDO::FETCH_OBJ)) {
								if($cidade == '') {
									echo '<dt class="cidade"><span class="glyphicon glyphicon-map-marker"></span> '.$lin->cidade.'/'.$lin->estado.'</dt>';
									$cidade = $lin->cidade;
								} else if($cidade != $lin->cidade) {
									// Escreve resultado antes de trocar a cidade
									echo <<<DADOS
							<dd>Pedidos: <span class="badge">$pedidos</span></dd>
							<dd>Pessoas: <span class="badge">$pessoas</span></dd>
							<dd><ul>
								<li>Querem casa: <span class="badge">$casa</span></li>
								<li>Querem hotel: <span class="badge">$hotel</span></li>
							</ul></dd>
DADOS;
									
									//Zera variaveis
									$pessoas = 0;
									$pedidos = 0;
									$casa = 0;
									$hotel = 0;
									
									echo '<dt class="cidade"><span class="glyphicon glyphicon-map-marker"></span> '.$lin->cidade.'/'.$lin->estado.'</dt>';
									$cidade = $lin->cidade;
								}
								
								$def = $pdo->query('SELECT * FROM peh WHERE peh.congregacao_cidade_id = '.$lin->cidade_id);
								while($reg = $def->fetch(PDO::FETCH_OBJ)) {
									if($reg->oc1_nome <> '') {
										$pessoas++;
										if($reg->tipo_hospedagem == 'casa') {$casa++;} else {$hotel++;}
									}
									if($reg->oc2_nome <> '') {
										$pessoas++;
										if($reg->tipo_hospedagem == 'casa') {$casa++;} else {$hotel++;}
									}
									if($reg->oc3_nome <> '') {
										$pessoas++;
										if($reg->tipo_hospedagem == 'casa') {$casa++;} else {$hotel++;}
									}
									if($reg->oc4_nome <> '') {
										$pessoas++;
										if($reg->tipo_hospedagem == 'casa') {$casa++;} else {$hotel++;}
									}
									$pedidos++;
								}
							}
							// antes de fechar a tag HTML, escreve resultado
							echo <<<DADOS
							<dd>Pedidos: <span class="badge">$pedidos</span></dd>
							<dd>Pessoas: <span class="badge">$pessoas</span></dd>
							<dd><ul>
								<li>Querem casa: <span class="badge">$casa</span></li>
								<li>Querem hotel: <span class="badge">$hotel</span></li>
							</ul></dd>
DADOS;
							
							echo '</dl>';
							
						} else {
							echo '<i>Ainda sem pedidos...</i>';
						}
					} else if($_SESSION['nivel'] == 20) {
					
						// ################# RELATÓRIO GERAL
						echo '<h4 class="text-center"><strong>RELATÓRIO GERAL</strong></h4>';
						
						$abc = $pdo->query('SELECT DISTINCT peh.congregacao_cidade_id AS cidade_id, cidade.cidade, cidade.estado FROM `peh` LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE 1 ORDER BY estado ASC, cidade ASC');
						if($abc->rowCount() > 0) {
							$cidade = '';
							$pessoas = 0;
							$pessoas_total = 0;
							$pedidos = 0;
							$pedidos_total = 0;
							$casa = 0;
							$hotel = 0;
							echo '<dl>';
							while($lin = $abc->fetch(PDO::FETCH_OBJ)) {
								if($cidade == '') {
									echo '<dt class="cidade"><span class="glyphicon glyphicon-map-marker"></span> '.$lin->cidade.'/'.$lin->estado.'</dt>';
									$cidade = $lin->cidade;
								} else if($cidade != $lin->cidade) {
									// Escreve resultado antes de trocar a cidade
									echo <<<DADOS
							<dd>Pedidos: <span class="badge">$pedidos</span></dd>
							<dd>Pessoas: <span class="badge">$pessoas</span></dd>
							<dd><ul>
								<li>Querem casa: <span class="badge">$casa</span></li>
								<li>Querem hotel: <span class="badge">$hotel</span></li>
							</ul></dd>
DADOS;
									
									// Incrementa variável geral.
									$pessoas_total = $pessoas_total + $pessoas;
									$pedidos_total = $pedidos_total + $pedidos;
									
									//Zera variaveis
									$pessoas = 0;
									$pedidos = 0;
									$casa = 0;
									$hotel = 0;
										
									echo '<dt class="cidade"><span class="glyphicon glyphicon-map-marker"></span> '.$lin->cidade.'/'.$lin->estado.'</dt>';
									$cidade = $lin->cidade;
								}
						
								$def = $pdo->query('SELECT * FROM peh WHERE peh.congregacao_cidade_id = '.$lin->cidade_id);
								while($reg = $def->fetch(PDO::FETCH_OBJ)) {
									if($reg->oc1_nome <> '') {
										$pessoas++;
										if($reg->tipo_hospedagem == 'casa') {$casa++;} else {$hotel++;}
									}
									if($reg->oc2_nome <> '') {
										$pessoas++;
										if($reg->tipo_hospedagem == 'casa') {$casa++;} else {$hotel++;}
									}
									if($reg->oc3_nome <> '') {
										$pessoas++;
										if($reg->tipo_hospedagem == 'casa') {$casa++;} else {$hotel++;}
									}
									if($reg->oc4_nome <> '') {
										$pessoas++;
										if($reg->tipo_hospedagem == 'casa') {$casa++;} else {$hotel++;}
									}
									
									$pedidos++;
								}
							}
							// antes de fechar a tag HTML, escreve resultado
							echo <<<DADOS
							<dd>Pedidos: <span class="badge">$pedidos</span></dd>
							<dd>Pessoas: <span class="badge">$pessoas</span></dd>
							<dd><ul>
								<li>Querem casa: <span class="badge">$casa</span></li>
								<li>Querem hotel: <span class="badge">$hotel</span></li>
							</ul></dd>
DADOS;

							echo '</dl>';
							
							// Incrementa variável geral
							$pessoas_total = $pessoas_total + $pessoas;
							$pedidos_total = $pedidos_total + $pedidos;
							
							
							echo <<<DADOS

							<hr>
							<table class="table table-hover table-bordered table-condensed">
								<thead>
									<tr>
										<th colspan="2" class="text-center">RESULTADO GERAL</th>
									</tr>
								</thead>
								<tbody class="bg-info">
									<tr>
										<th>TOTAL DE PEDIDOS</th>
										<td class="text-center"><span class="badge">$pedidos_total</span></td>
									</tr>
									<tr>
										<th>TOTAL DE PESSOAS</th>
										<td class="text-center"><span class="badge">$pessoas_total</span></td>
									</tr>
								</tbody>
							</table>
DADOS;
								
						} else {
							echo '<i>Ainda sem pedidos...</i>';
						}
					}
					
					?>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- FIM CONTEUDO -->
<?php include('--footer.php');?>