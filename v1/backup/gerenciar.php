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
</style>
<script>
function pehSelect() {
	if($('#peh_select').val() == 0) {
		$('[id^=peh_btn]').attr('disabled', true);
		$('#form1_row2').hide();
	} else {
		$('[id^=peh_btn]').attr('disabled', false);
		$('#form1_row2').show();
	}
	$('#fac_vinculo').val($('#peh_select').find(':selected').data('fac'));
	if($('#peh_select').find(':selected').data('fac') == 0) {
		$('#form1_row2_btn1').attr('disabled', true);
	} else {
		$('#form1_row2_btn1').attr('disabled', false);
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
						<li class="active"><a href="formulario.php">Novo</a></li>
						<li><a href="formulario.consulta.php">Consultar</a></li>
						<li class="divider"></li>
						<li><a href="gerenciar.php">Gerenciar</a></li>
					</ul>
						
				</li>
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
	
	<div class="alert alert-info alert-dismissable fade in">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		De preferência, acesse esta seção do sistema por meio de um desktop (computador de mesa ou notebook). Abra a página <a class="btn btn-info btn-sm" href="formulario.consulta.php" target="_blank" title="Clique aqui para abrir esta página em nova aba..." data-toggle="tooltip">Formulários > Consulta</a> em outra aba, para que possa consultar as informações sobre cada formulário separadamente.
	</div>
	
	<div class="row">
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
											
											$abc = $pdo->query('SELECT peh.id, peh.fac_id FROM peh WHERE congregacao_cidade_id = '.$lin->congregacao_cidade_id.' ORDER BY data ASC');
											if($abc->rowCount() > 0) {
												while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
													echo '<option value="'.$reg->id.'" data-fac="'.$reg->fac_id.'">Pedido - Nº '.$reg->id.'</option>';
												}
											}
										}
										echo '</optgroup>';
									}
									?>
								</select>
							</div>
							<div class="col-xs-6">
								<button type="button" id="peh_btn_visu" class="btn btn-sm btn-primary" onclick="pehCarrega($('#peh_select').val())" disabled>Visualizar</button>
								<button type="button" id="peh_btn_analise_parcial" class="btn btn-sm btn-primary" onclick="pehAnalise($('#peh_select').val())" disabled>Análise</button>
							</div>
						</div>
						<div class="form-group row" id="form1_row2" style="display:none">
							<div class="col-xs-6">
								<select name="fac_vinculo" id="fac_vinculo" class="form-control" onchange="javascript: if($('#fac_vinculo').val() == 0) {$('#form1_row2_btn1').attr('disabled', true);} else {$('#form1_row2_btn1').attr('disabled', false);}">
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
											
											$abc = $pdo->query('SELECT fac.id FROM fac WHERE cidade = '.$lin->cidade_id);
											if($abc->rowCount() > 0) {
												while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
													echo '<option value="'.$reg->id.'">Acomodação - Nº '.$reg->id.'</option>';
												}
											}
										}
										echo '</optgroup>';
									}
									?>
								</select>
							</div>
							<div class="col-xs-6">
								<button type="button" id="form1_row2_btn1" class="btn btn-sm btn-info" onclick="analiseCruzada($('#peh_select').val(), $('#fac_vinculo').val())"><span class="glyphicon glyphicon-random"></span> ANÁLISE CRUZADA</button>
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
								<select name="fac" class="form-control" id="fac_select" onchange="javascript: if($('#fac_select').val() == 0) {$('[id^=fac_btn]').attr('disabled', true);} else {$('[id^=fac_btn]').attr('disabled', false);}">
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
											
											$abc = $pdo->query('SELECT fac.id FROM fac WHERE cidade = '.$lin->cidade_id);
											if($abc->rowCount() > 0) {
												while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
													echo '<option value="'.$reg->id.'">Acomodação - Nº '.$reg->id.'</option>';
												}
											}
										}
										echo '</optgroup>';
									}
									?>
								</select>
							</div>
							<div class="col-xs-6">
								<button type="button" id="fac_btn_visu" class="btn btn-sm btn-primary" onclick="facCarrega($('#fac_select').val())" disabled>Visualizar</button>
								<button type="button" id="fac_btn_analise_parcial" class="btn btn-sm btn-primary" onclick="facAnalise($('#fac_select').val())" disabled>Análise</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-8">
			<div class="panel panel-default">
				<div class="panel-body" style="min-height: 300px">
					<div class="well sm-well">
						<button type="button" class="btn btn-warning btn-sm" onclick="$('#panel3').html('');"><span class="glyphicon glyphicon-erase"></span> <strong>Limpar tela</strong></button>
					</div>
					<hr>
					<div id="panel3">
					
					</div>
				</div>
			</div>
		</div>
		<div class="col-xs-4">
			<div class="panel panel-info">
				<div class="panel-heading">
					<h5 class="panel-title"><strong>Análise</strong></h5>
					
				</div>
				<div class="panel-body" style="min-height: 260px; max-height: 260px; overflow: auto;">
					<div id="panel4" style="font-family:'Courier New', Courier, monospace;">
					
					</div>
				</div>
				<div class="panel-footer">
					<button type="button" class="btn btn-sm btn-warning" onclick="$('#panel4').html('');"><span class="glyphicon glyphicon-erase"></span> <strong>Limpar tela</strong></button>
				</div>
			</div>
		</div>
	</div>

</div>
<!-- FIM CONTEUDO -->
<?php include('--footer.php');?>