<?php include('--header.php');
/*
 * ####################################
 * SCRIPT DE LIVE SEARCH
 * 
 * FORNECIDO POR:
 * 		Chosen 1.7.0
 * 
 * https://harvesthq.github.io/chosen/
 * ####################################
 */

?>
<script>
function atendCarrega() {
	var ocupante = $('#ocupante').find(':selected').val();
	var nome = $('#ocupante').find(':selected').text();
	
	if(ocupante != 0) {
		$.post(funcPage,{
			funcao: 'atend_Carrega',
			id: ocupante,
			usuario: nome
		},function(data){
			$('#panel_carrega').html(data);
		}, 'html');
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
						<li><a href="formulario.php"><span class="glyphicon glyphicon-plus"></span> &nbsp; Novo</a></li>
						<li><a href="formulario.consulta.php"><span class="glyphicon glyphicon-search"></span> &nbsp; Consultar</a></li>
						<?php if($_SESSION['nivel'] >= 10) {?>
						<li class="divider"></li>
						<li><a href="gerenciar.php"><span class="glyphicon glyphicon-link"></span> &nbsp; Gerenciar</a></li>
						<li class="active"><a href="atendimento.php"><span class="glyphicon glyphicon-headphones"></span> &nbsp; Atendimento</a></li>
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
		<li class="active">Atendimento</li>
	</ul>
	<!-- FIM DO BREADCRUMB -->
	
	<div class="row">
		<div class="col-sm-12 col-md-4">
			<div class="well well-sm">
				<form class="form-inline">
					<div class="form-group">
						<label for="ocupante">Nome do ocupante:</label>
						<select name="ocupante" id="ocupante" class="form-control chosen-select" data-placeholder="Buscar pessoas..." onchange="atendCarrega()">
							<option value="0"></option>
							<?php 
							$abc = $pdo->query('SELECT peh.`id`, peh.`oc1_nome`, peh.`oc2_nome`, peh.`oc3_nome`, peh.`oc4_nome`, cidade.`cidade`, cidade.`estado` FROM `peh` LEFT JOIN cidade ON peh.`congregacao_cidade_id` = cidade.id WHERE 1');
							if($abc->rowCount() > 0) {
								$x = 0;
								$array = '';
								while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
									if($reg->oc1_nome != '') {
										$array[$x]['nome'] = $reg->oc1_nome;
										if(strlen($array[$x]['nome']) > 20) { // Se for maior que 20 caracteres
											$y = strripos(substr($array[$x]['nome'], 0, 20), ' ');
											if($y != FALSE) { // Se encontrou um 'ESPAÇO', corta no espaço.
												$array[$x]['nome'] = substr($array[$x]['nome'], 0, $y);
											} else { // Se não encontrou um 'ESPAÇO', corta no limite de 20.
												$array[$x]['nome'] = substr($array[$x]['nome'], 0, 20);
											}
										}
										$array[$x]['id'] = $reg->id;
										$array[$x]['cidade'] = $reg->cidade.'/'.$reg->estado;
										$x++;
									}
									if($reg->oc2_nome != '') {
										$array[$x]['nome'] = $reg->oc2_nome;
										if(strlen($array[$x]['nome']) > 20) { // Se for maior que 20 caracteres
											$y = strripos(substr($array[$x]['nome'], 0, 20), ' ');
											if($y != FALSE) { // Se encontrou um 'ESPAÇO', corta no espaço.
												$array[$x]['nome'] = substr($array[$x]['nome'], 0, $y);
											} else { // Se não encontrou um 'ESPAÇO', corta no limite de 20.
												$array[$x]['nome'] = substr($array[$x]['nome'], 0, 20);
											}
										}
										$array[$x]['id'] = $reg->id;
										$array[$x]['cidade'] = $reg->cidade.'/'.$reg->estado;
										$x++;
									}
									if($reg->oc3_nome != '') {
										$array[$x]['nome'] = $reg->oc3_nome;
										if(strlen($array[$x]['nome']) > 20) { // Se for maior que 20 caracteres
											$y = strripos(substr($array[$x]['nome'], 0, 20), ' ');
											if($y != FALSE) { // Se encontrou um 'ESPAÇO', corta no espaço.
												$array[$x]['nome'] = substr($array[$x]['nome'], 0, $y);
											} else { // Se não encontrou um 'ESPAÇO', corta no limite de 20.
												$array[$x]['nome'] = substr($array[$x]['nome'], 0, 20);
											}
										}
										$array[$x]['id'] = $reg->id;
										$array[$x]['cidade'] = $reg->cidade.'/'.$reg->estado;
										$x++;
									}
									if($reg->oc4_nome != '') {
										$array[$x]['nome'] = $reg->oc4_nome;
										if(strlen($array[$x]['nome']) > 20) { // Se for maior que 20 caracteres
											$y = strripos(substr($array[$x]['nome'], 0, 20), ' ');
											if($y != FALSE) { // Se encontrou um 'ESPAÇO', corta no espaço.
												$array[$x]['nome'] = substr($array[$x]['nome'], 0, $y);
											} else { // Se não encontrou um 'ESPAÇO', corta no limite de 20.
												$array[$x]['nome'] = substr($array[$x]['nome'], 0, 20);
											}
										}
										$array[$x]['id'] = $reg->id;
										$array[$x]['cidade'] = $reg->cidade.'/'.$reg->estado;
										$x++;
									}
								}
								
								array_multisort($array, SORT_ASC);
								
								
								foreach($array as $reg) {
									echo <<<DADOS
									
						<option value="$reg[id]">$reg[nome] &nbsp; [$reg[cidade]]</option>
DADOS;
								}
							} else {
								
							}
							
							?>
						</select>
					</div>
				</form>
			</div>
		</div>
		
		<div class="col-sm-12 col-md-8">
			<div class="panel panel-default">
				<div class="panel-body">
					<div id="panel_carrega">
						
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
	
	</div>
	
</div>
<!-- FIM CONTEUDO -->
<?php include('--footer.php');?>