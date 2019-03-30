<?php include('--header.php');?>
<script src="js/gerenciar.js"></script>
<div id="topo"></div> <!-- ANCORA DE TOPO -->

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
						<li><a href="formulario.php"><span class="glyphicon glyphicon-plus"></span> &nbsp; Novo</a></li>
						<li><a href="formulario.consulta.php"><span class="glyphicon glyphicon-search"></span> &nbsp; Consultar</a></li>
						<?php if($_SESSION['nivel'] >= 10) {?>
						<li class="divider"></li>
						<li class="active"><a href="gerenciar.php"><span class="glyphicon glyphicon-link"></span> &nbsp; Gerenciar</a></li>
						<li><a href="atendimento.php"><span class="glyphicon glyphicon-headphones"></span> &nbsp; Atendimento</a></li>
						<?php }?>
						<li class="divider"></li>
						<li><a href="print.php"><span class="glyphicon glyphicon-print"></span> &nbsp; Imprimir</a></li>
					</ul>
						
				</li>
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
		<li><a href="#">Formulários</a></li>
		<li class="active">Gerenciar</li> 
	</ul>
	<!-- FIM DO BREADCRUMB -->
	
	
	<!-- 
	<div class="row visible-xs">
		<div class="col-xs-12">
			<div class="alert alert-danger">
				<strong><span class="glyphicon glyphicon-alert"></span> &nbsp; Algo não está certo aqui!</strong><br><br>
				Esta página <strong>não</strong> deve ser acessada via dispositivo móvel, pois há risco de algumas informações ficarem distorcidas (ou talvez, escondidas). <strong>Acesse de um computador ou dispositivo com tela maior</strong>.
			</div>
		</div>
	</div>
	-->
	
	<!-- SOMENTE EM CELULARES, BOTÃO DE TOPO -->
	<div class="botao_topo visible-xs"><a href="#topo" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-chevron-up"></span> TOPO</a></div>
	<!-- SOMENTE EM CELULARES, BOTÃO DE TOPO -->
	
	<div class="row">
		<div class="col-xs-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<button type="button" class="btn btn-info btn-sm pull-right hidden-xs" style="top: -5px; position:relative" onclick="$('#modal2').modal()"><span class="glyphicon glyphicon-info-sign"></span> Ajuda</button>
					<h5 class="panel-title"><strong>Pedido de Hospedagem</strong></h5>
				</div>
				<div class="panel-body">
					<form id="form1">
						<div class="form-group row">
							<div class="col-xs-12 col-sm-6">
								<select name="peh" class="form-control" id="peh_select" onchange="gPehSelect();">
									<option value="0" data-fac="0">Escolha um pedido de hospedagem:</option>
									<?php 
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
											
											$abc = $pdo->query('SELECT peh.id, peh.fac_id, peh.revisar FROM peh WHERE peh.revisar = 0 AND congregacao_cidade_id = '.$lin->congregacao_cidade_id.' ORDER BY id ASC');
											if($abc->rowCount() > 0) {
												while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
													$token = md5($reg->id.session_id());
													if($reg->fac_id == 0) {
														echo '<option value="'.$reg->id.'" data-fac="'.$reg->fac_id.'" data-revisar="'.$reg->revisar.'" data-token="'.$token.'">Pedido - Nº '.$reg->id.'</option>';
													} else {
														echo '<option value="'.$reg->id.'" data-fac="'.$reg->fac_id.'" data-revisar="'.$reg->revisar.'">Pedido - Nº '.$reg->id.' &nbsp; [OK!]</option>';
													}
												}
											} else {
											    echo '<option value="" disabled>Sem Pedidos</option>';
											}
										}
										echo '</optgroup>';
									}
									?>
								</select>
							</div>
							<div class="col-xs-12 col-sm-6">
								<!-- BOTÕES -->
								<button type="button" class="btn btn-warning" id="btn_act_revisar" data-toggle="tooltip" title="Pedir revisão do Pedido de Hospedagem" style="display:none" onclick="revisarForms('peh', $('#peh_select').val(), $('#peh_select').find(':selected').data('token'))"><strong><span class="glyphicon glyphicon-comment"></span> Pedir revisão</strong></button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-xs-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<button type="button" class="btn btn-info btn-sm pull-right hidden-xs" style="top: -5px; position:relative" onclick="$('#modal3').modal()"><span class="glyphicon glyphicon-info-sign"></span> Ajuda</button>
					<h5 class="panel-title"><strong>Formulário de Acomodação</strong></h5>
				</div>
				<div class="panel-body">
					<form>
						<div class="form-group row">
							<div class="col-xs-12 col-sm-6">
								<select name="fac" class="form-control" id="fac_select" onchange="gFacSelect()">
									<option value="0">Escolha uma acomodação:</option>
									<?php 
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
											
											$abc = $pdo->query('SELECT fac.id FROM fac WHERE fac.revisar = 0 AND cidade = '.$lin->cidade_id);
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
											} else {
											    echo '<option value="" disabled>Sem Pedidos</option>';
											}
										}
										echo '</optgroup>';
									}
									unset($cidade, $estado, $abc, $def, $xyz, $vinculado, $reg, $lin);
									?>
								</select>
							</div>
							<div class="col-xs-12 col-sm-6">
								<!-- BOTÕES -->
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	
	<!-- MENU DE AÇÕES -->
	<div class="row">
		<div class="col-xs-12">
			<div class="panel panel-info">
				<div class="panel-body row">
					<div class="col-xs-3 col-sm-2">
						<h5 class="pull-right"><strong>Ações:</strong></h5>
					</div>
					<div id="panel5" class="col-xs-7 col-sm-8 text-center" style="display:none">
						<div class="">
							<button type="button" class="btn btn-primary" id="btn_act_vincular" data-toggle="tooltip" title="Vincular este Pedido de Hospedagem com esta Acomodação" onclick="vincularForms()" style="display:none;"><strong><span class="glyphicon glyphicon-random"></span> &nbsp;Vincular</strong></button>
							<button type="button" class="btn btn-danger" id="btn_act_desvincular" data-toggle="tooltip" title="Desvincular este Pedido de Hospedagem com esta Acomodação" onclick="desvincularForms()" style="display:none;"><strong><span class="glyphicon glyphicon-scissors"></span> &nbsp;Desvincular</strong></button>
							<button type="button" class="btn btn-danger" id="btn_act_desv1" data-toggle="tooltip" title="Desvincular Pedido de Hospedagem" style="display:none" onclick="desvAction('peh', $('#peh_select').val())"><strong><span class="glyphicon glyphicon-remove"></span> Liberar Ped. Hospedagem</strong></button>
							<button type="button" class="btn btn-danger" id="btn_act_desv2" data-toggle="tooltip" title="Desvincular Acomodação" style="display:none" onclick="desvAction('fac', $('#fac_select').val())"><strong><span class="glyphicon glyphicon-remove"></span> Liberar Acomodação</strong></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- FIM MENU DE AÇÕES -->
	
	<!-- TELA DE PEDIDOS / FORMULÁRIOS -->
	<div class="row">
		<div class="col-xs-12 col-sm-6">
			<h5 class="visible-xs text-center"><strong>PEH</strong></h5>
			<div class="panel panel-primary">
				<div class="panel-body" style="min-height: 300px; max-height: 300px; overflow:auto;">
					<div id="panel3">
					
					</div>
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-sm-6">
			<h5 class="visible-xs text-center"><strong>FAC</strong></h5>
			<div class="panel panel-primary">
				<div class="panel-body" style="min-height: 300px; max-height: 300px; overflow:auto">
					<div id="panel4">
					
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- FIM TELA DE PEDIDOS / FORMULÁRIOS -->
	
	<div id="modal1" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="modal1_titulo"><strong><span class="glyphicon glyphicon-bell"></span> NOTIFICAÇÃO DO SISTEMA</strong></h4>
				</div>
				
				<div class="modal-body" id="modal1_body">
					<div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span> <strong>OPERAÇÃO REALIZADA COM SUCESSO</strong></div>
					<div class="alert alert-danger"><span class="glyphicon glyphicon-exclamation-sign"></span> <strong>OPERAÇÃO RETORNOU ERRO</strong><br> A seguinte mensagem retornou do sistema: <br><br><strong><i>"'+data+'"</i></strong><br><br> Atualize a página e tente de novo, ou contate administrador.</div>
				</div>
				
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
				</div>
			</div>
		</div>
	</div>
	
	<div id="modal2" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="modal2_titulo"><strong><span class="glyphicon glyphicon-info-sign"></span> AJUDA PEDIDOS DE HOSPEDAGEM</strong></h4>
				</div>
				<div class="modal-body">
					<ul>
						<li>Escolha um pedido de hospedagem na lista, para visualizá-lo;</li>
						<li>Pedidos com marcação <strong>[OK!]</strong> na lista, já foram atendidos por acomodações;</li>
						<li>Fique atento à quantidade de pessoas em cada pedido de hospedagem;
						<div class="alert alert-warning">
							<strong>ATENÇÃO</strong> O sistema não notificará se o número de camas for incompatível (para menos ou para mais) com o número de pessoas no pedido.
						</div>
						</li>
						<li>Em casos de erros, tire um print ou foto da tela e envie para um administrador do sistema.</li>
					</ul>
					<hr>
					<h4 class="text-center"><strong>Como atender a um pedido de hospedagem?</strong></h4>
					<ol>
						<li>Escolha o pedido de hospedagem que irá ser atendido.</li>
						<li>Verifique o número de ocupantes na lista.</li>
						<li>Procure uma acomodação livre que atenda à quantidade de ocupantes e aos demais critérios no pedido.</li>
						<li>Clique no botão <button type="button" class="btn btn-default"><strong><span class="glyphicon glyphicon-random"></span> &nbsp;Vincular</strong></button> para vincular o pedido com a acomodação.</li>
					</ol>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
				</div>
			</div>
		</div>
	</div>
	
	<div id="modal3" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="modal3_titulo"><strong><span class="glyphicon glyphicon-info-sign"></span> AJUDA FORMULÁRIOS DE ACOMODAÇÃO</strong></h4>
				</div>
				<div class="modal-body">
					<ul>
						<li>Escolha um formulário de acomodação na lista, para visualizá-lo;</li>
						<li>Acomodações com marcação <strong>[OK!]</strong> na lista, já atenderam hospedagens;
						<div class="alert alert-warning">
							<strong>ATENÇÃO:</strong> Não é possível atender mais de um pedido por acomodação.<br>
							Se a acomodação possui diversos quartos com diversas camas, talvez seja melhor cadastrar cada quarto como uma acomodação diferente.<br><br>
							Essa prática visa organizar da melhor forma os recursos disponíveis, facilitar a organização e a compartimentalização de informações.
						</div>
						</li>
						<li>Fique atento à quantidade de camas em cada acomodação;
						<div class="alert alert-warning">
							<strong>ATENÇÃO</strong> O sistema não notificará se o número de camas for incompatível (para menos ou para mais) com o número de pessoas no pedido.
						</div>
						</li>
						<li>Em casos de erros, tire um print ou foto da tela e envie para um administrador do sistema.</li>
					</ul>
					<hr>
					<h4 class="text-center"><strong>Como atender a um pedido de hospedagem?</strong></h4>
					<ol>
						<li>Escolha o pedido de hospedagem que irá ser atendido.</li>
						<li>Verifique o número de ocupantes na lista.</li>
						<li>Procure uma acomodação livre que atenda à quantidade de ocupantes e aos demais critérios no pedido.</li>
						<li>Clique no botão <button type="button" class="btn btn-default"><strong><span class="glyphicon glyphicon-random"></span> &nbsp;Vincular</strong></button> para vincular o pedido com a acomodação.</li>
					</ol>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
				</div>
			</div>
		</div>
	</div>
	

</div>
<!-- FIM CONTEUDO -->
<?php include('--footer.php');?>