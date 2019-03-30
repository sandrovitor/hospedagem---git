<?php include('--header.php');?>
<style>
.green {
	color: #00cc66;
}
.red {
	color:red;
}
.yellow {
	color: #ffb84d;
}
kbd {
	padding: 7px;
}
</style>
<script>
function pehSelect() {
	if($('#peh_select').val() == 0) {
		$('[id^=peh_btn]').attr('disabled', true);
	} else {
		$('[id^=peh_btn]').attr('disabled', false);
	}
	zeraBtnAction(); // Desativa botões na parte inferior
}

function zeraPehSelect() {
	$('#peh_select').val(0);
	$('[id^=peh_btn]').attr('disabled', true);
	btnAction();
}

function facSelect() {
	if($('#fac_select').val() == 0) {
		$('[id^=fac_btn]').attr('disabled', true);
	} else {
		$('[id^=fac_btn]').attr('disabled', false);
	}
	zeraBtnAction(); // Desativa botões na parte inferior
}

function zeraFacSelect() {
	$('#fac_select').val(0);
	$('[id^=fac_btn]').attr('disabled', true);
	btnAction();
}

function zeraBtnAction() {
	$("#panel5 :button").attr('disabled', true);
	$("#panel5").fadeOut();
}

function btnAction() {
	// Verifica se ambos formulários foram escolhidos.
	if($('#peh_select').val() != 0 && $('#fac_select').val() != 0 && $('#panel3').html() != '' && $('#panel4').html() != '') {
		$("#panel5 :button").attr('disabled', false);

		/*
		Verifica se o PEH e o FAC possuem algum tipo de vinculo.
		*/
		if($('#peh_select').find(":selected").data('fac') == 0) { // PEDIDO DE HOSPEDAGEM EM ABERTO
			if($('#fac_select').find(':selected').text().indexOf('[OK!]') >= 0) { // PEH sem vinculo, FAC vinculado outro
				$('#btn_act_desv1').hide();
				$('#btn_act_desv2').show();
				$('#btn_act_vincular').html('<strong><span class="glyphicon glyphicon-random"></span> &nbsp;Vincular</strong>').removeClass('btn-danger').addClass('btn-primary').hide();
				
			} else { // PEH e FAC sem vínculo
				$('#btn_act_vincular').html('<strong><span class="glyphicon glyphicon-random"></span> &nbsp;Vincular</strong>').removeClass('btn-danger').addClass('btn-primary').show();
				$('#btn_act_desv1, #btn_act_desv2').hide();
				
			}
		} else { // PEDIDO DE HOSPEDAGEM JÁ VINCULADO COM ALGUMA ACOMODAÇÃO
			if($('#peh_select').find(":selected").data('fac') == $('#fac_select').val()) { // PEH e FAC vinculo igual
				$('#btn_act_vincular').html('<strong><span class="glyphicon glyphicon-random"></span> &nbsp;Desvincular formulários</strong>').removeClass('btn-primary').addClass('btn-danger').show();
				$('#btn_act_desv1, #btn_act_desv2').hide();
				
			} else if($('#fac_select').find(':selected').text().indexOf('[OK!]') >= 0) { // PEH e FAC vinculo diferente
				$('#btn_act_vincular').html('<strong><span class="glyphicon glyphicon-random"></span> &nbsp;Vincular</strong>').removeClass('btn-danger').addClass('btn-primary').hide();
				$('#btn_act_desv1, #btn_act_desv2').show();
			} else { // PEH vinculado, FAC sem vínculo
				$('#btn_act_vincular').html('<strong><span class="glyphicon glyphicon-random"></span> &nbsp;Vincular</strong>').removeClass('btn-danger').addClass('btn-primary').hide();
				$('#btn_act_desv1').show();
				$('#btn_act_desv2').hide();
			}

		}

		
		// Verifica se o formulário está pendente de revisão.
		if($('#peh_select').find(":selected").data('revisar') == 0) {
			$('#btn_act_revisar').attr('disabled', false).show();
		} else {
			$('#btn_act_revisar').attr('disabled', true).show();
		}

		
		// Ativa os tooltips
		$('[data-toggle="tooltip"]').tooltip();
		$('[data-toggle="popover"]').popover({html:true});
		
		$("#panel5").fadeIn();
	} else {
		$("#panel5 :button").attr('disabled', true);
		$("#panel5").fadeOut();
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
						<li><a href="formulario.consulta.php">Consultar</a></li>
						<?php if($_SESSION['nivel'] >= 10) {?>
						<li class="divider"></li>
						<li class="active"><a href="gerenciar.php">Gerenciar</a></li>
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
		<li><a href="#">Formulários</a></li>
		<li class="active">Gerenciar</li> 
	</ul>
	<!-- FIM DO BREADCRUMB -->
	
	
	<div class="row visible-xs">
		<div class="col-xs-12">
			<div class="alert alert-danger">
				<strong><span class="glyphicon glyphicon-alert"></span> &nbsp; Algo não está certo aqui!</strong><br><br>
				Esta página <strong>não</strong> deve ser acessada via dispositivo móvel, pois há risco de algumas informações ficarem distorcidas (ou talvez, escondidas). <strong>Acesse de um computador ou dispositivo com tela maior</strong>.
			</div>
		</div>
	</div>
	
	<div class="row hidden-xs">
		<div class="col-xs-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h5 class="panel-title"><strong>Pedido de Hospedagem</strong></h5>
				</div>
				<div class="panel-body">
					<form id="form1">
						<div class="form-group row">
							<div class="col-xs-6">
								<select name="peh" class="form-control" id="peh_select" onchange="pehSelect();">
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
									?>
								</select>
							</div>
							<div class="col-xs-6">
								<button type="button" id="peh_btn_visu" class="btn btn-sm btn-primary" onclick="pehCarrega($('#peh_select').val(), 1)" disabled>Visualizar</button>
								<button type="button" class="btn btn-sm btn-warning" onclick="$('#panel3').html(''); zeraBtnAction();"><span class="glyphicon glyphicon-erase"></span> Limpar tela</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-xs-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h5 class="panel-title"><strong>Formulário de Acomodação</strong></h5>
				</div>
				<div class="panel-body">
					<form>
						<div class="form-group row">
							<div class="col-xs-6">
								<select name="fac" class="form-control" id="fac_select" onchange="facSelect()">
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
									?>
								</select>
							</div>
							<div class="col-xs-6">
								<button type="button" id="fac_btn_visu" class="btn btn-sm btn-primary" onclick="facCarrega($('#fac_select').val(),1)" disabled>Visualizar</button>
								<button type="button" class="btn btn-sm btn-warning" onclick="$('#panel4').html(''); zeraBtnAction();"><span class="glyphicon glyphicon-erase"></span> Limpar tela</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	
	<div class="row hidden-xs">
		<div class="col-xs-6">
			<div class="panel panel-primary">
				<div class="panel-body" style="min-height: 300px; max-height: 300px; overflow:auto;">
					<div id="panel3">
					
					</div>
				</div>
			</div>
		</div>
		<div class="col-xs-6">
			<div class="panel panel-primary">
				<div class="panel-body" style="min-height: 300px; max-height: 300px; overflow:auto">
					<div id="panel4">
					
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="row hidden-xs">
		<div class="col-xs-12">
			<div class="panel panel-info">
				<div class="panel-body row">
					<div class="col-xs-2">
						<h5 class="pull-right"><strong>Ações:</strong></h5>
					</div>
					<div id="panel5" class="col-xs-10" style="display:none">
						<div class="">
							<button type="button" class="btn btn-primary" id="btn_act_vincular" data-toggle="tooltip" title="Vincula (ou desvincula) este Pedido de Hospedagem com esta Acomodação" onclick="vincularForms($('#peh_select').val(), $('#fac_select').val())" style="display:none;"><strong><span class="glyphicon glyphicon-random"></span> &nbsp;Vincular</strong></button>
							<button type="button" class="btn btn-danger" id="btn_act_desv1" data-toggle="tooltip" title="Desvincular Pedido de Hospedagem" style="display:none" onclick="desvAction('peh', $('#peh_select').val())"><strong><span class="glyphicon glyphicon-remove"></span> Liberar Ped. Hospedagem</strong></button>
							<button type="button" class="btn btn-danger" id="btn_act_desv2" data-toggle="tooltip" title="Desvincular Acomodação" style="display:none" onclick="desvAction('fac', $('#fac_select').val())"><strong><span class="glyphicon glyphicon-remove"></span> Liberar Acomodação</strong></button>
							<button type="button" class="btn btn-warning" id="btn_act_revisar" data-toggle="tooltip" title="Solicitar revisão do Pedido de Hospedagem" style="display:none" onclick="revisarForms('peh', $('#peh_select').val(), $('#peh_select').find(':selected').data('token'))"><strong><span class="glyphicon glyphicon-comment"></span> Solicitar revisão do pedido</strong></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>
<!-- FIM CONTEUDO -->
<?php include('--footer.php');?>