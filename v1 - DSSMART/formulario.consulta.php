<?php include('--header.php');?>
<style>
#panel_pedidos_body {
	max-height: 580px;
	overflow:auto;
}
kbd {
	padding: 7px;
}
</style>
<script>
function carAcomod(id) {
	if(id != 0) {
		$.post(funcPage,{
			funcao: 'facConsulta',
			id: id,
			links: 0
		}, function(data){
			$('[data-toggle="tooltip"]').tooltip('disable');
			$('[data-toggle="popover"]').popover('disable');
			
			$('#panel_acomodacao').html(data);
			$('#Modal1').modal();
			
			$('[data-toggle="tooltip"]').tooltip();
			$('[data-toggle="popover"]').popover({html:true});
		});
	}
}
</script>

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
				<li><a href="./"><span class="glyphicon glyphicon-home"></span> Início</a></li>
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-list-alt"></span> Formulários
					<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="formulario.php">Novo</a></li>
						<li class="active"><a href="formulario.consulta.php">Consultar</a></li>
						<?php if($_SESSION['nivel'] >= 10) {?>
						<li class="divider"></li>
						<li><a href="gerenciar.php">Gerenciar</a></li>
						<?php } ?>
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
		<li class="active">Formulários</li> 
	</ul>
	<!-- FIM DO BREADCRUMB -->
	
	<div class="row">
		<div class="col-md-4 col-sm-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<a data-toggle="collapse" data-target="#panel_pedidos"><strong>FORMULÁRIOS/PEDIDOS</strong></a>
				</div>
				<div class="collapse in" id="panel_pedidos">
					<div class="panel-body" id="panel_pedidos_body">
						<div class="panel-group">
							<div class="panel panel-default">
								<div class="panel-heading">
									<strong>Pedidos de Hospedagem</strong>
								</div>
								<div id="panel1">
									<div class="panel-body">
										<table class="table table-condensed">
											<tbody>
												
												<?php 
												if($_SESSION['nivel'] == 1) { // Solicitante de hospedagem
													$def = $pdo->query('SELECT DISTINCT peh.congregacao_cidade_id, cidade.cidade, cidade.estado FROM peh LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE cidade.solicitante_id = '.$_SESSION['id'].' ORDER BY cidade.estado ASC, cidade.cidade ASC');
													
												} else if ($_SESSION['nivel'] == 10) { // Responsável pela hospedagem
													$def = $pdo->query('SELECT DISTINCT peh.congregacao_cidade_id, cidade.cidade, cidade.estado FROM peh LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE cidade.resp_id = '.$_SESSION['id'].' ORDER BY cidade.estado ASC, cidade.cidade ASC');
														
												} else if ($_SESSION['nivel'] == 20) { // Administrador
													$def = $pdo->query('SELECT DISTINCT peh.congregacao_cidade_id, cidade.cidade, cidade.estado FROM peh LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE 1 ORDER BY cidade.estado ASC, cidade.cidade ASC');
													
												}
												
												if($def->rowCount() > 0) { // Linhas encontradas no banco
													while($reg = $def->fetch(PDO::FETCH_OBJ)) {
														
														// Cabeçalho da cidade
														echo <<<DADOS
												<tr>
													<th colspan="3" class="text-center bg-info"><span class="glyphicon glyphicon-map-marker"></span> $reg->cidade/$reg->estado</th>
												</tr>
												
DADOS;
														
														$abc = $pdo->query('SELECT * FROM peh WHERE congregacao_cidade_id = '.$reg->congregacao_cidade_id.' ORDER BY id ASC');
														if($abc->rowCount() > 0) { // Linhas encontradas no banco
															while($lin = $abc->fetch(PDO::FETCH_OBJ)) {
																// TRATA BOTÕES E INFORMAÇÕES
																$info = ''; $bot = '';
																if($lin->fac_id != 0) {
																	$info .= '<span class="label label-success" data-toggle="tooltip" title="OK!"><span class="glyphicon glyphicon-ok"></span> OK !</span>';
																} else {
																	$info .= '<span class="label label-warning" data-toggle="tooltip" title="PENDENTE!"><span class="glyphicon glyphicon-question-sign"></span> PEN</span>';
																}
																
																if($lin->revisar == 0) {
																	$info .= '<br><span class="label label-default" data-toggle="tooltip" title="TUDO OK"><span class="glyphicon glyphicon-alert"></span> REV</span>';
																} else {
																	$info .= '<br><span class="label label-danger" data-toggle="tooltip" title="REVISAR!"><span class="glyphicon glyphicon-alert"></span> REV</span>';
																}
																
	
																$token = md5($lin->id.session_id());
																if($_SESSION['nivel'] == 1 || $_SESSION['nivel'] == 20) {
																	$bot .= '<a href="formulario.edita.php?tipo=peh&pehid='.$lin->id.'&token='.$token.'" target="_blank" class="btn btn-sm btn-success" title="Editar" data-toggle="tooltip"><span class="glyphicon glyphicon-edit"></span></a> ';
																	$bot .= <<<DADOS
	<button class="btn btn-sm btn-danger" title="Apagar pedido" data-toggle="tooltip" onclick="apagaPEH($lin->id, '$token')"><span class="glyphicon glyphicon-erase"></span></button>
DADOS;
																}
																if(($_SESSION['nivel'] == 10 || $_SESSION['nivel'] == 20)) {
																	if($lin->revisar == 0) {
																		$dis = '';
																	} else if($lin->revisar == 1) {
																		$dis = 'disabled';
																	}
																	$bot .= <<<DADOS
	<button onclick="revisarForms('peh', $lin->id, '$token')" class="btn btn-sm btn-warning" title="Pedir revisão" data-toggle="tooltip" $dis><span class="glyphicon glyphicon-comment"></span></button>
DADOS;
																} else { // Se for nivel 1
																	$bot .= '<button onclick="carAcomod('.$lin->fac_id.')" class="btn btn-sm btn-default" title="Informação da Acomodação" data-toggle="tooltip"><span class="glyphicon glyphicon-lamp"></span></button>';
																}
																
																
																// ESCREVE LINHA DA TABELA
																echo <<<DADOS
												<tr>
													<td style="vertical-align: middle"><a onclick="pehCarrega($lin->id,0)" data-toggle="collapse" data-target="#panel_pedidos">Pedido - Nº.: $lin->id</a></td>
													<td>$info</td>
													<td>$bot</td>
												</tr>
DADOS;
															}
														} else { // Nehuma linha encontrada no banco
															echo '<i>Nenhum pedido registrado desta cidade</i>';
														}
													}
												} else { // Nenhuma linha encontrada no banco
													echo '<i>Nenhum pedido disponível ainda</i>';
												}
												?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<?php if($_SESSION['nivel'] != 1) {?>
							<div class="panel panel-default">
								<div class="panel-heading">
									<strong>Acomodações</strong>
								</div>
								<div id="panel2">
									<div class="panel-body">
										<table class="table table-condensed">
											<tbody>
												
												<?php 
												if ($_SESSION['nivel'] == 10) { // Responsável pela hospedagem
													$abc = $pdo->query('SELECT DISTINCT fac.id, fac.cidade AS cidade_id, cidade.cidade, cidade.estado FROM fac LEFT JOIN cidade ON fac.cidade = cidade.id WHERE cidade.hospedeiro = 1 AND cidade.resp_id = '.$_SESSION['id'].' ORDER BY cidade.estado ASC, cidade.cidade ASC');
													
												} else if ($_SESSION['nivel'] == 20) { // Administrador
													$abc = $pdo->query('SELECT DISTINCT fac.id, fac.cidade AS cidade_id, cidade.cidade, cidade.estado FROM fac LEFT JOIN cidade ON fac.cidade = cidade.id WHERE cidade.hospedeiro = 1 ORDER BY cidade.estado ASC, cidade.cidade ASC, fac.id ASC');
													
												}
												
												if($abc->rowCount() > 0) { // Linhas encontradas no banco
													$cidade = '';
													while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
														// CABEÇALHO DA CIDADE
														if($cidade == '' || $cidade != $reg->cidade_id) {
															echo <<<DADOS
												<tr>
													<th colspan="3" class="text-center bg-info"><span class="glyphicon glyphicon-map-marker"></span> $reg->cidade/$reg->estado</th>
												</tr>
							
DADOS;
															$cidade = $reg->cidade_id;
														}
														
														// TRATA BOTÕES E INFORMAÇÕES
														$info = ''; $bot = '';
														// verifica se já foi vinculado.
														$def = $pdo->query('SELECT peh.id FROM peh WHERE peh.fac_id = '.$reg->id);
														
														if($def->rowCount() > 0) {
															$info .= '<span class="label label-success" data-toggle="tooltip" title="Acomodação já em vinculada!"><span class="glyphicon glyphicon-ok"></span> JÁ !</span>';
														} else {
															$info .= '<span class="label label-warning" data-toggle="tooltip" title="ACOMODAÇÃO LIVRE!"><span class="glyphicon glyphicon-question-sign"></span> LIV</span>';
														}
														
														$token = md5($reg->id.session_id());
														$bot .= '<a href="formulario.edita.php?tipo=fac&facid='.$reg->id.'&token='.$token.'" target="_blank" class="btn btn-sm btn-success" title="Editar" data-toggle="tooltip"><span class="glyphicon glyphicon-edit"></span></a> ';
														$bot .= <<<DADOS
														<button class="btn btn-sm btn-danger" title="Apagar acomodação" data-toggle="tooltip" onclick="apagaFAC($reg->id, '$token')"><span class="glyphicon glyphicon-erase"></span></button>
DADOS;
														
														
														
														// ESCREVE LINHA DA TABELA
														echo <<<DADOS
												<tr>
													<td style="vertical-align: middle"><a onclick="facCarrega($reg->id,0)" data-toggle="collapse" data-target="#panel_pedidos" class="btn btn-link">Acomodação Nº.: $reg->id</a></td>
													<td>$info</td>
													<td>$bot</td>
												</tr>
DADOS;
													}
												}
												?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<?php }?>
						</div>
					</div>
					<div class="panel-footer">
						<button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#Modal2"><span class="glyphicon glyphicon-info-sign"></span> <strong>Legenda</strong></button>
					</div>
				</div>
			</div>
			
		</div>
		<div class="col-md-8 col-sm-12">
			<div class="panel panel-default">
				<div class="panel-body" id="panel3">
					
				</div>
			</div>
		</div>
		<div id="Modal1" class="modal fade" role="dialog">
			<div class="modal-dialog modal-lg">
				<!-- CONTEUDO DO MODAL -->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title"><strong><span class="glyphicon glyphicon-lamp"></span> INFORMAÇÕES DA ACOMODAÇÃO</strong></h4>
					</div>
					<div class="modal-body" id="panel_acomodacao">
					
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
					</div>
				</div>
				<!-- FIM DO CONTEUDO DO MODAL -->
			</div>
		</div>
		<div id="Modal2" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<!-- CONTEUDO DO MODAL -->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title"><strong><span class="glyphicon glyphicon-info-sign"></span> LEGENDA DOS ÍCONES</strong></h4>
					</div>
					<div class="modal-body">
						<table class="table">
							<thead>
								<tr>
									<th>Ícone</th>
									<th>Descrição</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><span class="label label-success" data-toggle="tooltip" title="OK!"><span class="glyphicon glyphicon-ok"></span> OK !</span></td>
									<td>Pedido de Hospedagem já foi atendido com uma Acomodação.</td>
								</tr>
								<tr>
									<td><span class="label label-warning" data-toggle="tooltip" title="PENDENTE!"><span class="glyphicon glyphicon-question-sign"></span> PEN</span></td>
									<td>Pedido de Hospedagem ainda em aberto. Aguardando ser atendido...</td>
								</tr>
								<tr>
									<td><span class="label label-danger" data-toggle="tooltip" title="REVISAR!"><span class="glyphicon glyphicon-alert"></span> REV</span><br>
									<br><span class="label label-default" data-toggle="tooltip" title="TUDO OK"><span class="glyphicon glyphicon-alert"></span> REV</span></td>
									<td>Quando <span class="label label-danger">VERMELHO</span>: Pedido sinalizado com algum problema. Necessário revisão das informações pelo responsável.<br>Quando <span class="label label-default">CINZA</span>: Tudo Ok.</td>
								</tr>
								<tr>
									<td><span class="label label-success" data-toggle="tooltip" title="Acomodação já em vinculada!"><span class="glyphicon glyphicon-ok"></span> JÁ !</span></td>
									<td>Acomodação já está atendendo um Pedido de Hospedagem. Não pode ser vinculado a outro Pedido.</td>
								</tr>
								<tr>
									<td><span class="label label-warning" data-toggle="tooltip" title="ACOMODAÇÃO LIVRE!"><span class="glyphicon glyphicon-question-sign"></span> LIV</span></td>
									<td>Acomodação está livre para atender algum Pedido de Hospedagem.</td>
								</tr>
								<tr>
									<th class="text-center" colspan="2">BOTÕES</th>
								</tr>
								<tr>
									<td><button class="btn btn-sm btn-success" title="Editar" data-toggle="tooltip"><span class="glyphicon glyphicon-edit"></span></button></td>
									<td>Editar pedido de hospedagem ou formulário de acomodação.</td>
								</tr>
								<tr>
									<td><button class="btn btn-sm btn-danger" title="Apagar acomodação" data-toggle="tooltip"><span class="glyphicon glyphicon-erase"></span></button></td>
									<td>Apaga formulário de acomodação.</td>
								</tr>
								<tr>
									<td><button class="btn btn-sm btn-warning" title="Pedir revisão" data-toggle="tooltip" ><span class="glyphicon glyphicon-comment"></span></button></td>
									<td>Solicitar ao usuário que preencheu <strong><i>Pedido de Hospedagem</i></strong> que faça uma revisão nas informações. O botão de revisão ficará assim: <span class="label label-danger" data-toggle="tooltip" title="REVISAR!"><span class="glyphicon glyphicon-alert"></span> REV</span>.</td>
								</tr>
								<tr>
									<td><button class="btn btn-sm btn-default" title="Informação da Acomodação" data-toggle="tooltip"><span class="glyphicon glyphicon-lamp"></span></button></td>
									<td>Exibe informação da acomodação que atendeu a este pedido de hospedagem.<br>
									<strong>OBS.:</strong> Se o pedido ainda não foi atendido, esse botão não terá nenhuma ação.</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
					</div>
				</div>
				<!-- FIM DO CONTEUDO DO MODAL -->
			</div>
		</div>
		
	</div>
</div>
<!-- FIM CONTEUDO -->
<?php include('--footer.php');?>