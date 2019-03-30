<?php include('--header.php');?>
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
		<li><a href="#">Formulários</a></li>
		<li class="active">Editar</li> 
	</ul>
	<!-- FIM DO BREADCRUMB -->
	
	<?php 
	if(isset($_POST['formulario_tipo']) && $_POST['formulario_tipo'] != '') {
		// Processa formulário e salva no banco de dados
		if($_POST['formulario_tipo'] == 'PEH') {
			// ############################ SALVA FORMULÁRIO PEH
			
			$query = 'UPDATE `peh` SET `nome` = :nome, `endereco` = :endereco, `cidade` = :cidade, `estado` = :estado, `tel_res` = :tel_res, `tel_cel` = :tel_cel, `email` = :email, `congregacao` = :congregacao, `congregacao_cidade_id` = :congregacao_cidade_id, `congresso_cidade` = :congresso_cidade, `check_in` = :check_in, `check_out` = :check_out, `tipo_hospedagem` = :tipo_hospedagem, `pagamento` = :pagamento, `transporte` = :transporte, `oc1_nome` = :oc1_nome, `oc1_idade` = :oc1_idade, `oc1_sexo` = :oc1_sexo, `oc1_parente` = :oc1_parente, `oc1_etnia` = :oc1_etnia, `oc1_privilegio` = :oc1_privilegio, `oc2_nome` = :oc2_nome, `oc2_idade` = :oc2_idade, `oc2_sexo` = :oc2_sexo, `oc2_parente` = :oc2_parente, `oc2_etnia` = :oc2_etnia, `oc2_privilegio` = :oc2_privilegio, `oc3_nome` = :oc3_nome, `oc3_idade` = :oc3_idade, `oc3_sexo` = :oc3_sexo, `oc3_parente` = :oc3_parente, `oc3_etnia` = :oc3_etnia, `oc3_privilegio` = :oc3_privilegio, `oc4_nome` = :oc4_nome, `oc4_idade` = :oc4_idade, `oc4_sexo` = :oc4_sexo, `oc4_parente` = :oc4_parente, `oc4_etnia` = :oc4_etnia, `oc4_privilegio` = :oc4_privilegio, `motivo` = :motivo, `secretario_nome` = :secretario_nome, `secretario_tel` = :secretario_tel, `secretario_email` = :secretario_email, `data` = :data,`revisar` = 0 WHERE id = :fid';
			$abc = $pdo->prepare($query);
			
			$abc->bindValue(':nome', $_POST['nome'], PDO::PARAM_STR);
			$abc->bindValue(':endereco', $_POST['endereco'], PDO::PARAM_STR);
			$abc->bindValue(':cidade', $_POST['cidade'], PDO::PARAM_STR);
			$abc->bindValue(':estado', $_POST['estado'], PDO::PARAM_STR);
			$abc->bindValue(':tel_res', $_POST['tel_res'], PDO::PARAM_STR);
			$abc->bindValue(':tel_cel', $_POST['tel_cel'], PDO::PARAM_STR);
			$abc->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
			$abc->bindValue(':congregacao', $_POST['congregacao'], PDO::PARAM_STR);
			$abc->bindValue(':congregacao_cidade_id', $_POST['congregacao_cidade_id'], PDO::PARAM_STR);
			$abc->bindValue(':congresso_cidade', $_POST['congresso_cidade'], PDO::PARAM_STR);
			$abc->bindValue(':check_in', $_POST['check_in'], PDO::PARAM_STR);
			$abc->bindValue(':check_out', $_POST['check_out'], PDO::PARAM_STR);
			$abc->bindValue(':tipo_hospedagem', $_POST['tipo_hospedagem'], PDO::PARAM_STR);
			$abc->bindValue(':pagamento', $_POST['pagamento'], PDO::PARAM_INT);
			$abc->bindValue(':transporte', $_POST['transporte'], PDO::PARAM_STR);
				
			$abc->bindValue(':oc1_nome', $_POST['oc1_nome'], PDO::PARAM_STR);
			$abc->bindValue(':oc1_idade', $_POST['oc1_idade'], PDO::PARAM_INT);
			$abc->bindValue(':oc1_sexo', $_POST['oc1_sexo'], PDO::PARAM_STR);
			$abc->bindValue(':oc1_parente', $_POST['oc1_parente'], PDO::PARAM_STR);
			$abc->bindValue(':oc1_etnia', $_POST['oc1_etnia'], PDO::PARAM_STR);
			$abc->bindValue(':oc1_privilegio', $_POST['oc1_privilegio'], PDO::PARAM_STR);
				
			// Se não houver nome no Ocupante 2, não cadastra nada
			if($_POST['oc2_nome'] == '') {
				$abc->bindValue(':oc2_nome', '', PDO::PARAM_STR);
				$abc->bindValue(':oc2_idade', 0, PDO::PARAM_INT);
				$abc->bindValue(':oc2_sexo', '', PDO::PARAM_STR);
				$abc->bindValue(':oc2_parente', '', PDO::PARAM_STR);
				$abc->bindValue(':oc2_etnia', '', PDO::PARAM_STR);
				$abc->bindValue(':oc2_privilegio', '', PDO::PARAM_STR);
			} else {
				$abc->bindValue(':oc2_nome', $_POST['oc2_nome'], PDO::PARAM_STR);
				$abc->bindValue(':oc2_idade', $_POST['oc2_idade'], PDO::PARAM_INT);
				$abc->bindValue(':oc2_sexo', $_POST['oc2_sexo'], PDO::PARAM_STR);
				$abc->bindValue(':oc2_parente', $_POST['oc2_parente'], PDO::PARAM_STR);
				$abc->bindValue(':oc2_etnia', $_POST['oc2_etnia'], PDO::PARAM_STR);
				$abc->bindValue(':oc2_privilegio', $_POST['oc2_privilegio'], PDO::PARAM_STR);
			}
				
			// Se não houver nome no Ocupante 3, não cadastra nada
			if($_POST['oc3_nome'] == '') {
				$abc->bindValue(':oc3_nome', '', PDO::PARAM_STR);
				$abc->bindValue(':oc3_idade', 0, PDO::PARAM_INT);
				$abc->bindValue(':oc3_sexo', '', PDO::PARAM_STR);
				$abc->bindValue(':oc3_parente', '', PDO::PARAM_STR);
				$abc->bindValue(':oc3_etnia', '', PDO::PARAM_STR);
				$abc->bindValue(':oc3_privilegio', '', PDO::PARAM_STR);
			} else {
				$abc->bindValue(':oc3_nome', $_POST['oc3_nome'], PDO::PARAM_STR);
				$abc->bindValue(':oc3_idade', $_POST['oc3_idade'], PDO::PARAM_INT);
				$abc->bindValue(':oc3_sexo', $_POST['oc3_sexo'], PDO::PARAM_STR);
				$abc->bindValue(':oc3_parente', $_POST['oc3_parente'], PDO::PARAM_STR);
				$abc->bindValue(':oc3_etnia', $_POST['oc3_etnia'], PDO::PARAM_STR);
				$abc->bindValue(':oc3_privilegio', $_POST['oc3_privilegio'], PDO::PARAM_STR);
			}
				
			// Se não houver nome no Ocupante 4, não cadastra nada
			if($_POST['oc4_nome'] == '') {
				$abc->bindValue(':oc4_nome', '', PDO::PARAM_STR);
				$abc->bindValue(':oc4_idade', 0, PDO::PARAM_INT);
				$abc->bindValue(':oc4_sexo', '', PDO::PARAM_STR);
				$abc->bindValue(':oc4_parente', '', PDO::PARAM_STR);
				$abc->bindValue(':oc4_etnia', '', PDO::PARAM_STR);
				$abc->bindValue(':oc4_privilegio', '', PDO::PARAM_STR);
			} else {
				$abc->bindValue(':oc4_nome', $_POST['oc4_nome'], PDO::PARAM_STR);
				$abc->bindValue(':oc4_idade', $_POST['oc4_idade'], PDO::PARAM_INT);
				$abc->bindValue(':oc4_sexo', $_POST['oc4_sexo'], PDO::PARAM_STR);
				$abc->bindValue(':oc4_parente', $_POST['oc4_parente'], PDO::PARAM_STR);
				$abc->bindValue(':oc4_etnia', $_POST['oc4_etnia'], PDO::PARAM_STR);
				$abc->bindValue(':oc4_privilegio', $_POST['oc4_privilegio'], PDO::PARAM_STR);
			}
				
				
			$abc->bindValue(':motivo', $_POST['motivo'], PDO::PARAM_STR);
			$abc->bindValue(':secretario_nome', $_POST['secretario_nome'], PDO::PARAM_STR);
			$abc->bindValue(':secretario_tel', $_POST['secretario_tel'], PDO::PARAM_STR);
			$abc->bindValue(':secretario_email', $_POST['secretario_email'], PDO::PARAM_STR);
			$abc->bindValue(':data', date('Y:m:d H:i:s'), PDO::PARAM_STR);
			
			$abc->bindValue(':fid', $_POST['fid'], PDO::PARAM_INT);
				
			$abc->execute();
			

			echo '<h2><span class="glyphicon glyphicon-ok"></span> Fomulário atualizado <br><small><a class="btn btn-link" onclick="window.close()"><span class="glyphicon glyphicon-remove"></span> Fechar janela</a></small></h2>';
			
		} else if ($_POST['formulario_tipo'] == 'FAc') {
			// ############################ SALVA FORMULÁRIO FAC
			
			// Conta quantidade de quartos.
			$conta_quartos = 0; $x = 1;
			while(isset($_POST['quarto'.$x])) {
				if($_POST['quarto'.$x] == 'yes') {
					// Confirma se a quantidade de camas foi informada.
					if($_POST['quarto'.$x.'__sol_qtd'] > 0 || $_POST['quarto'.$x.'__cas_qtd'] > 0) {
						$conta_quartos++;
					}
				}
				$x++;
			}
			
			// Varre variáveis dos quartos
			for($x=1; $x <= 4; $x++) {
				if($_POST['quarto'.$x.'__sol_qtd'] == '') {
					$_POST['quarto'.$x.'__sol_qtd'] = 0;
				}
				if($_POST['quarto'.$x.'__cas_qtd'] == '') {
					$_POST['quarto'.$x.'__cas_qtd'] = 0;
				}
				if($_POST['quarto'.$x.'_valor1'] == '') {
					$_POST['quarto'.$x.'_valor1'] = 0;
				}
				if($_POST['quarto'.$x.'_valor2'] == '') {
					$_POST['quarto'.$x.'_valor2'] = 0;
				}
			}
			
			
			// Conta quantidade de dias.
			if(array_search('todos', $_POST['dias']) != FALSE) {
				$dias = 'todos';
			} else {
				$dias = implode(';',$_POST['dias']);
			}
			
			
			$query = 'UPDATE `fac` SET `quartos_qtd` = :quartos_qtd, `quarto1_sol_qtd` = :quarto1_sol_qtd, `quarto1_cas_qtd` = :quarto1_cas_qtd, `quarto1_valor1` = :quarto1_valor1, `quarto1_valor2` = :quarto1_valor2, `quarto2_sol_qtd` = :quarto2_sol_qtd, `quarto2_cas_qtd` = :quarto2_cas_qtd, `quarto2_valor1` = :quarto2_valor1, `quarto2_valor2` = :quarto2_valor2, `quarto3_sol_qtd` = :quarto3_sol_qtd, `quarto3_cas_qtd` = :quarto3_cas_qtd, `quarto3_valor1` = :quarto3_valor1, `quarto3_valor2` = :quarto3_valor2, `quarto4_sol_qtd` = :quarto4_sol_qtd, `quarto4_cas_qtd` = :quarto4_cas_qtd, `quarto4_valor1` = :quarto4_valor1, `quarto4_valor2` = :quarto4_valor2, `dias` = :dias, `andar` = :andar, `transporte` = :transporte, `casa_tj` = :casa_tj, `obs1` = :obs1, `nome` = :nome, `endereco` = :endereco, `telefone` = :telefone, `cidade` = :cidade, `publicador_nome` = :publicador_nome, `publicador_tel` = :publicador_tel, `cong_nome` = :cong_nome, `cong_cidade` = :cong_cidade, `cong_sec` = :cong_sec, `cong_tel` = :cong_tel, `condicao` = :condicao, `obs2` = :obs2 WHERE `id` = :fid';
			$abc = $pdo->prepare($query);
			

			$abc->bindValue(':quartos_qtd', $conta_quartos, PDO::PARAM_INT);
			$abc->bindValue(':quarto1_sol_qtd', (int)$_POST['quarto1__sol_qtd'], PDO::PARAM_INT);
			$abc->bindValue(':quarto1_cas_qtd', (int)$_POST['quarto1__cas_qtd'], PDO::PARAM_INT);
			$abc->bindValue(':quarto1_valor1', (int)$_POST['quarto1_valor1'], PDO::PARAM_INT);
			$abc->bindValue(':quarto1_valor2', (int)$_POST['quarto1_valor2'], PDO::PARAM_INT);
			
			$abc->bindValue(':quarto2_sol_qtd', $_POST['quarto2__sol_qtd'], PDO::PARAM_INT);
			$abc->bindValue(':quarto2_cas_qtd', $_POST['quarto2__cas_qtd'], PDO::PARAM_INT);
			$abc->bindValue(':quarto2_valor1', $_POST['quarto2_valor1'], PDO::PARAM_INT);
			$abc->bindValue(':quarto2_valor2', $_POST['quarto2_valor2'], PDO::PARAM_INT);
			
			$abc->bindValue(':quarto3_sol_qtd', $_POST['quarto3__sol_qtd'], PDO::PARAM_INT);
			$abc->bindValue(':quarto3_cas_qtd', $_POST['quarto3__cas_qtd'], PDO::PARAM_INT);
			$abc->bindValue(':quarto3_valor1', $_POST['quarto3_valor1'], PDO::PARAM_INT);
			$abc->bindValue(':quarto3_valor2', $_POST['quarto3_valor2'], PDO::PARAM_INT);
			
			$abc->bindValue(':quarto4_sol_qtd', $_POST['quarto4__sol_qtd'], PDO::PARAM_INT);
			$abc->bindValue(':quarto4_cas_qtd', $_POST['quarto4__cas_qtd'], PDO::PARAM_INT);
			$abc->bindValue(':quarto4_valor1', $_POST['quarto4_valor1'], PDO::PARAM_INT);
			$abc->bindValue(':quarto4_valor2', $_POST['quarto4_valor2'], PDO::PARAM_INT);
			
			$abc->bindValue(':dias', $dias, PDO::PARAM_STR);
			$abc->bindValue(':andar', $_POST['andar'], PDO::PARAM_INT);
			$abc->bindValue(':transporte', $_POST['transporte'], PDO::PARAM_BOOL);
			$abc->bindValue(':casa_tj', $_POST['casa_tj'], PDO::PARAM_BOOL);
			$abc->bindValue(':obs1', addslashes($_POST['obs1']), PDO::PARAM_STR);
			$abc->bindValue(':nome', addslashes($_POST['nome']), PDO::PARAM_STR);
			$abc->bindValue(':endereco', addslashes($_POST['endereco']), PDO::PARAM_STR);
			$abc->bindValue(':telefone', $_POST['telefone'], PDO::PARAM_STR);
			$abc->bindValue(':cidade', $_POST['cidade'], PDO::PARAM_INT);
			$abc->bindValue(':publicador_nome', addslashes($_POST['publicador_nome']), PDO::PARAM_STR);
			$abc->bindValue(':publicador_tel', addslashes($_POST['publicador_tel']), PDO::PARAM_STR);
			$abc->bindValue(':cong_nome', addslashes($_POST['cong_nome']), PDO::PARAM_STR);
			$abc->bindValue(':cong_cidade', $_POST['cong_cidade'], PDO::PARAM_INT);
			$abc->bindValue(':cong_sec', addslashes($_POST['cong_sec']), PDO::PARAM_STR);
			$abc->bindValue(':cong_tel', $_POST['cong_tel'], PDO::PARAM_STR);
			$abc->bindValue(':condicao', $_POST['condicao'], PDO::PARAM_STR);
			$abc->bindValue(':obs2', addslashes($_POST['obs2']), PDO::PARAM_STR);
			
			$abc->bindValue(':fid', (int)$_POST['fid'], PDO::PARAM_INT);
				
		
			$abc->execute();
			
			echo '<h2><span class="glyphicon glyphicon-ok"></span> Fomulário atualizado <br><small><a class="btn btn-link" onclick="window.close()"><span class="glyphicon glyphicon-remove"></span> Fechar janela</a></small></h2>';
			
			
			
		}
		
		
	} else if(!isset($_GET['tipo']) || $_GET['tipo'] == '') {
		echo '<h4>ERRO 404: Nada encontrado</h4>';
	} else {
		if($_GET['tipo'] == 'fac') { // ############## FORMULÁRIO DE ACOMODAÇÃO
			if(!isset($_GET['facid']) || $_GET['facid'] == '' || $_GET['facid'] == 0 || !isset($_GET['token']) || $_GET['token'] == '') {
				// Se nao enviar o ID do PEH e o Token de autorização, cancela.
				echo '<h4>ERRO 404: Nada encontrado</h4>';
				exit();
			} else {
				// calcula token de autorização: MD5( PEHID + session_id() );
				$token = md5((int)$_GET['facid'].session_id());
					
				if($token != $_GET['token']) {
					echo '<h4>ERRO 403: Acesso proibido</h4>';
					exit();
				}
			}
			
			
			// SE CHEGOU ATÉ AQUI ESTÁ TUDO OK
			// Procura PEH no banco de dados
			$abc = $pdo->prepare('SELECT fac.*, cidade.cidade AS cidade_nome, cidade.estado FROM fac LEFT JOIN cidade ON fac.cidade = cidade.id WHERE fac.id = :facid');
			$abc->bindValue(':facid', $_GET['facid'], PDO::PARAM_INT);
			$abc->execute();
			
			if($abc->rowCount() == 0) {
				echo '<h4>Erro 404: Pedido não encontrado</h4>';
				exit();
			}
			
			$reg = $abc->fetch(PDO::FETCH_OBJ);
			
			
			?>
			
			<script>
				var codQuart = 2;
				function mostraQuarto(codigo) {
					if(codQuart <= 4) {
						if($("#quarto"+codQuart).css("display") == 'none') {
							$("#quarto"+codQuart).fadeIn();
							$("input[name='quarto"+codQuart+"'").val('yes');
						}
						codQuart++;
					} else { // Se a contagem passar de 4, mostra alerta.
						alert('Limite máximo de quartos atingido: '+ (codQuart-1));
					}
				}
				
				</script>
				<h3 class="text-center"><strong>FORMULÁRIO DE ACOMODAÇÃO</strong><br></h3>
			
				<div class="alert alert-danger visible-xs">
					<strong>Sinal vermelho!</strong><br>
					Esse formulário não pode ser preenchido/exibido em dispositivos móveis. <i><strong>Utilize um computador ou equipamento de tela maior.</strong></i>
				</div>
				<form action="formulario.edita.php" method="post" class="hidden-xs">
					<div class="form-group row" id="quarto1">
						<div class="col-xs-12 col-sm-2">
							<h5 class="pull-right hidden-xs" style="text-align:right;"><strong>QUARTO 1</strong></h5>
						</div>
						<div class="col-xs-6 col-sm-5">
							<div class="well sm-well">
								<div class="row text-center">
									<label>Quantidade de camas</label>
								</div>
								<div class="row">
									<div class="col-xs-6">
										<input type="number" name="quarto1__sol_qtd" class="form-control"  placeholder="Solteiro" value="<?php if($reg->quarto1_sol_qtd <> 0) {echo $reg->quarto1_sol_qtd;}?>">
									</div>
									<div class="col-xs-6">
										<input type="number" name="quarto1__cas_qtd" class="form-control"  placeholder="Casal" value="<?php if($reg->quarto1_cas_qtd <> 0) {echo $reg->quarto1_cas_qtd;}?>">
									</div>
								</div>
							</div>
						</div>
						<div class="col-xs-6 col-sm-5">
							<div class="well sm-well">
								<div class="row text-center">
									<label>Preço do quarto por dia</label>
								</div>
								<div class="row">
									<div class="col-xs-6">
										<input type="number" name="quarto1_valor1" class="form-control" placeholder="Um no quarto" value="<?php if($reg->quarto1_valor1 <> 0) {echo $reg->quarto1_valor1;}?>">
									</div>
									<div class="col-xs-6">
										<input type="number" name="quarto1_valor2" class="form-control" placeholder="Dois ou mais no quarto" value="<?php if($reg->quarto1_valor2 <> 0) {echo $reg->quarto1_valor2;}?>">
									</div>
								</div>
							</div>
						</div>
						
					</div>
					<div class="form-group row" id="quarto2">
						<div class="col-xs-12 col-sm-2">
							<h5 class="pull-right hidden-xs" style="text-align:right;"><strong>QUARTO 2</strong></h5>
						</div>
						<div class="col-xs-6 col-sm-5">
							<div class="well sm-well">
								<div class="row text-center">
									<label>Quantidade de camas</label>
								</div>
								<div class="row">
									<div class="col-xs-6">
										<input type="number" name="quarto2__sol_qtd" class="form-control"  placeholder="Solteiro" value="<?php if($reg->quarto2_sol_qtd <> 0) {echo $reg->quarto2_sol_qtd;}?>">
									</div>
									<div class="col-xs-6">
										<input type="number" name="quarto2__cas_qtd" class="form-control"  placeholder="Casal" value="<?php if($reg->quarto2_cas_qtd <> 0) {echo $reg->quarto2_cas_qtd;}?>">
									</div>
								</div>
							</div>
						</div>
						<div class="col-xs-6 col-sm-5">
							<div class="well sm-well">
								<div class="row text-center">
									<label>Preço do quarto por dia</label>
								</div>
								<div class="row">
									<div class="col-xs-6">
										<input type="number" name="quarto2_valor1" class="form-control" placeholder="Um no quarto" value="<?php if($reg->quarto2_valor1 <> 0) { $reg->quarto2_valor1;}?>">
									</div>
									<div class="col-xs-6">
										<input type="number" name="quarto2_valor2" class="form-control" placeholder="Dois ou mais no quarto" value="<?php if($reg->quarto2_valor2 <> 0) {echo $reg->quarto2_valor2;}?>">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group row" id="quarto3">
						<div class="col-xs-12 col-sm-2">
							<h5 class="pull-right hidden-xs" style="text-align:right;"><strong>QUARTO 3</strong></h5>
						</div>
						<div class="col-xs-6 col-sm-5">
							<div class="well sm-well">
								<div class="row text-center">
									<label>Quantidade de camas</label>
								</div>
								<div class="row">
									<div class="col-xs-6">
										<input type="number" name="quarto3__sol_qtd" class="form-control"  placeholder="Solteiro" value="<?php if($reg->quarto3_sol_qtd <> 0) {echo $reg->quarto3_sol_qtd;}?>">
									</div>
									<div class="col-xs-6">
										<input type="number" name="quarto3__cas_qtd" class="form-control"  placeholder="Casal" value="<?php if($reg->quarto3_cas_qtd <> 0) {echo $reg->quarto3_cas_qtd;}?>">
									</div>
								</div>
							</div>
						</div>
						<div class="col-xs-6 col-sm-5">
							<div class="well sm-well">
								<div class="row text-center">
									<label>Preço do quarto por dia</label>
								</div>
								<div class="row">
									<div class="col-xs-6">
										<input type="number" name="quarto3_valor1" class="form-control" placeholder="Um no quarto" value="<?php if($reg->quarto3_valor1 <> 0) {echo $reg->quarto3_valor1;}?>">
									</div>
									<div class="col-xs-6">
										<input type="number" name="quarto3_valor2" class="form-control" placeholder="Dois ou mais no quarto" value="<?php if($reg->quarto3_valor2 <> 0) {echo $reg->quarto3_valor2;}?>">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group row" id="quarto4">
						<div class="col-xs-12 col-sm-2">
							<h5 class="pull-right hidden-xs" style="text-align:right;"><strong>QUARTO 4</strong></h5>
						</div>
						<div class="col-xs-6 col-sm-5">
							<div class="well sm-well">
								<div class="row text-center">
									<label>Quantidade de camas</label>
								</div>
								<div class="row">
									<div class="col-xs-6">
										<input type="number" name="quarto4__sol_qtd" class="form-control"  placeholder="Solteiro" value="<?php if($reg->quarto4_sol_qtd <> 0) {echo $reg->quarto4_sol_qtd;}?>">
									</div>
									<div class="col-xs-6">
										<input type="number" name="quarto4__cas_qtd" class="form-control"  placeholder="Casal" value="<?php if($reg->quarto4_cas_qtd <> 0) {echo $reg->quarto4_cas_qtd;}?>">
									</div>
								</div>
							</div>
						</div>
						<div class="col-xs-6 col-sm-5">
							<div class="well sm-well">
								<div class="row text-center">
									<label>Preço do quarto por dia</label>
								</div>
								<div class="row">
									<div class="col-xs-6">
										<input type="number" name="quarto4_valor1" class="form-control" placeholder="Um no quarto" value="<?php if($reg->quarto4_valor1 <> 0) {echo $reg->quarto4_valor1;}?>">
									</div>
									<div class="col-xs-6">
										<input type="number" name="quarto4_valor2" class="form-control" placeholder="Dois ou mais no quarto" value="<?php if($reg->quarto4_valor2 <> 0) {echo $reg->quarto4_valor2;}?>">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-10 col-sm-offset-2">
							<div class="form-group">
								<label>Os quartos estão disponíveis nos dias:</label>
								<?php 
								if($reg->dias == 'todos') {
									echo <<<DADOS
						<label class="checkbox-inline"><input type="checkbox" name="dias[]" checked value="1">Domingo</label>
						<label class="checkbox-inline"><input type="checkbox" name="dias[]" checked value="2">Segunda</label>
						<label class="checkbox-inline"><input type="checkbox" name="dias[]" checked value="3">Terça</label>
						<label class="checkbox-inline"><input type="checkbox" name="dias[]" checked value="4">Quarta</label>
						<label class="checkbox-inline"><input type="checkbox" name="dias[]" checked value="5">Quinta</label>
						<label class="checkbox-inline"><input type="checkbox" name="dias[]" checked value="6">Sexta</label>
						<label class="checkbox-inline"><input type="checkbox" name="dias[]" checked value="7">Sábado</label>
						<label class="checkbox-inline"><input type="checkbox" name="dias[]" checked value="todos"><strong>Todos</strong></label>
DADOS;
								} else {
									$dias = $reg->dias;
									$dias = explode(';',$dias);
								
									$dom = '';
									$seg = '';
									$ter = '';
									$qua = '';
									$qui = '';
									$sex = '';
									$sab = '';
									foreach($dias as $dia) {
										switch($dia) {
											case 1: $dom = 'checked'; break;
											case 2: $seg = 'checked'; break;
											case 3: $ter = 'checked'; break;
											case 4: $qua = 'checked'; break;
											case 5: $qui = 'checked'; break;
											case 6: $sex = 'checked'; break;
											case 7: $sab = 'checked'; break;
										}
									}
									echo <<<DADOS
						<label class="checkbox-inline"><input type="checkbox" name="dias[]" $dom value="1">Domingo</label>
						<label class="checkbox-inline"><input type="checkbox" name="dias[]" $seg value="2">Segunda</label>
						<label class="checkbox-inline"><input type="checkbox" name="dias[]" $ter value="3">Terça</label>
						<label class="checkbox-inline"><input type="checkbox" name="dias[]" $qua value="4">Quarta</label>
						<label class="checkbox-inline"><input type="checkbox" name="dias[]" $qui value="5">Quinta</label>
						<label class="checkbox-inline"><input type="checkbox" name="dias[]" $sex value="6">Sexta</label>
						<label class="checkbox-inline"><input type="checkbox" name="dias[]" $sab value="7">Sábado</label>
						<label class="checkbox-inline"><input type="checkbox" name="dias[]" value="todos"><strong>Todos</strong></label>
DADOS;
								}
								?>
								
								
							</div>
						</div>
					</div>
					<div class="form-group row">
						<div class="col-sm-3 col-sm-offset-2">
							<label>Em que andar ficam os quartos?<br> <small>(Assuma térreo como 0)</small></label>
							<input type="number" name="andar" value="<?php echo $reg->andar;?>" class="form-control">
						</div>
						<div class="col-sm-3">
							<label>Poderá prover condução?</label>
							<select name="transporte" class="form-control">
								<option value="1" <?php if($reg->transporte == 1) {echo 'selected';}?>>Sim</option>
								<option value="0" <?php if($reg->transporte == 0) {echo 'selected';}?>>Não</option>
							</select>
						</div>
						<div class="col-sm-4">
							<label>É o lar de Testemunhas de Jeová?</label>
							<select name="casa_tj" class="form-control">
								<option value="1" <?php if($reg->casa_tj == 1) {echo 'selected';}?>>Sim</option>
								<option value="0" <?php if($reg->casa_tj == 0) {echo 'selected';}?>>Não</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label>Observações:</label>
								<textarea rows="2" name="obs1" class="form-control"><?php if($reg->obs1 <> '') {echo $reg->obs1;}?></textarea>
							</div>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-sm-6">
							<h5 CLASS="text-center"><strong>ENDEREÇO DO HOSPEDEIRO</strong></h5>
							<div class="form-group">
								<label>Nome:</label>
								<input type="text" name="nome" class="form-control" value="<?php echo $reg->nome;?>">
							</div>
							<div class="form-group">
								<label>Endereço <small>(incluir bairro)</small>:</label>
								<input type="text" name="endereco" class="form-control" value="<?php echo $reg->endereco;?>">
							</div>
							<div class="row">
								<div class="col-sm-6">
									<label>Telefone:</label>
									<input type="text" name="telefone" class="form-control" value="<?php echo $reg->telefone;?>">
								</div>
								<div class="col-sm-6">
									<label>Cidade:</label>
									<select name="cidade" class="form-control">
										<?php 
										$def = $pdo->query('SELECT * FROM cidade WHERE hospedeiro = 1');
										if($def->rowCount() > 0) {
											while($lin = $def->fetch(PDO::FETCH_OBJ)) {
												if($lin->id == $reg->cidade) {
													$sel = ' selected';
												} else {
													$sel = '';
												}
												echo <<<DADOS
										<option value="$lin->id"$sel>$lin->cidade/$lin->estado</option>

DADOS;
											}
												unset($sel);
										}
										?>
									</select>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<h5 CLASS="text-center"><strong>PUBLICADOR QUE INDICOU</strong><br>
							<small>(Publicador que conseguiu a hospedagem, se não for o hospedeiro)</small></h5>
							<div class="form-group">
								<label>Publicador:</label>
								<input type="text" name="publicador_nome" class="form-control" value="<?php echo $reg->publicador_nome;?>">
							</div>
							<div class="form-group">
								<label>Telefone</label>
								<input type="text" name="publicador_tel" class="form-control" value="<?php echo $reg->publicador_tel;?>">
							</div>
						</div>
					</div>
					
					<hr>
					<div class="well sm-well">
						<h4><strong>SECRETÁRIO DA CONGREGAÇÃO</strong></h4>
						<div class="form-group row">
							<div class="col-sm-4">
								<label>Nome da Congregação:</label>
								<input type="text" name="cong_nome" class="form-control" value="<?php echo $reg->cong_nome;?>">
								<br>
								<label>Cidade:</label>
								<select name="cong_cidade" class="form-control">
									<?php 
									$def = $pdo->query('SELECT * FROM cidade WHERE hospedeiro = 1');
									if($def->rowCount() > 0) {
										while($lin = $def->fetch(PDO::FETCH_OBJ)) {
											if($lin->id == $reg->cong_cidade) {
												$sel = ' selected';
											} else {
												$sel = '';
											}
											echo <<<DADOS
									<option value="$lin->id"$sel>$lin->cidade/$lin->estado</option>

DADOS;
										}
										unset($sel);
									}
									?>
								</select>
							</div>
							<div class="col-sm-4">
								<label>Nome do Secretário:</label>
								<input type="text" name="cong_sec" class="form-control" value="<?php echo $reg->cong_sec;?>">
								<br>
								<label>Telefone do Secretário:</label>
								<input type="text" name="cong_tel" class="form-control" value="<?php echo $reg->cong_tel;?>">
							</div>
							<div class="col-sm-4">
								<label>Condição do(s) quarto(s):</label><br>
								<label class="radio-inline"><input type="radio" name="condicao" value="excelente" <?php if($reg->condicao == 'excelente') {echo 'checked';}?>>Excelente</label>
								<label class="radio-inline"><input type="radio" name="condicao" value="boa" <?php if($reg->condicao == 'boa') {echo 'checked';}?>>Boa</label>
								<label class="radio-inline"><input type="radio" name="condicao" value="razoavel" <?php if($reg->condicao == 'razoavel') {echo 'checked';}?>>Razoável</label>
								<br>
								<label>Observações</label>
								<textarea rows="2" name="obs2" class="form-control"><?php if($reg->obs2 <> '') { echo $reg->obs2;}?></textarea>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<input type="hidden" name="formulario_tipo" value="FAc">
								<input type="hidden" name="fid" value="<?php echo $reg->id;?>">
								<input type="hidden" name="quarto1" value="yes">
								<input type="hidden" name="quarto2" value="yes">
								<input type="hidden" name="quarto3" value="yes">
								<input type="hidden" name="quarto4" value="yes">
								<button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-send"></span> Confirmar alterações</button>
							</div>
						</div>
					</div>
					
					
				</form>
			
			
			<?php
		} else if($_GET['tipo'] == 'peh') { // ########### PEDIDO ESPECIAL DE HOSPEDAGEM
			if(!isset($_GET['pehid']) || $_GET['pehid'] == '' || $_GET['pehid'] == 0 || !isset($_GET['token']) || $_GET['token'] == '') {
				// Se nao enviar o ID do PEH e o Token de autorização, cancela.
				echo '<h4>ERRO 404: Nada encontrado</h4>';
				exit();
			} else {
				// calcula token de autorização: MD5( PEHID + session_id() );
				$token = md5((int)$_GET['pehid'].session_id());
			
				if($token != $_GET['token']) {
					echo '<h4>ERRO 403: Acesso proibido</h4>';
					exit();
				}
			}
			
			// SE CHEGOU ATÉ AQUI ESTÁ TUDO OK
			// Procura PEH no banco de dados
			$abc = $pdo->prepare('SELECT peh.*, cidade.cidade AS cong_cidade, cidade.estado AS cong_estado FROM `peh` LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE peh.id = :pehid');
			$abc->bindValue(":pehid", $_GET['pehid'], PDO::PARAM_INT);
			$abc->execute();
			
			if($abc->rowCount() == 0) {
				echo '<h4>Erro 404: Pedido não encontrado</h4>';
				exit();
			}
			
			$reg = $abc->fetch(PDO::FETCH_OBJ);
			?>
			
				<script>
				function nameChange(){
						$("[name='oc1_nome']").val($("[name='nome']").val());
					};
					
					
				</script>
				<form action="formulario.edita.php" method="POST">
					<h3 class="text-center"><strong>PEDIDO ESPECIAL DE HOSPEDAGEM <small>(Nº.: <?php echo $reg->id;?>)</small></strong></h3>
					
					<h4><strong>PUBLICADOR</strong></h4>
					<hr>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="nome">Nome:</label>
								<input type="text" name="nome" id="nome" class="form-control" value="<?php echo $reg->nome;?>" autofocus onchange="nameChange()">
								<span class="help-block">O mesmo que no campo ocupante 1 abaixo</span>
							</div>
							
							<div class="form-group">
								<label for="endereco">Endereço:</label>
								<input type="text" name="endereco" id="endereco" class="form-control" value="<?php echo $reg->endereco;?>">
							</div>
							
							<div class="form-group row">
								<div class="col-xs-6">
									<label for="cidade">Cidade:</label>
									<input type="text" name="cidade" id="cidade" class="form-control" value="<?php echo $reg->cidade;?>">
								</div>
								<div class="col-xs-3">
									<label for="estado">Estado:</label>
									<select name="estado" id="estado" class="form-control">
										<option value="AC" <?php if($reg->estado == 'AC') { echo 'selected';}?>>Acre</option>
										<option value="AL" <?php if($reg->estado == 'AL') { echo 'selected';}?>>Alagoas</option>
										<option value="AP" <?php if($reg->estado == 'AP') { echo 'selected';}?>>Amapá</option>
										<option value="AM" <?php if($reg->estado == 'AM') { echo 'selected';}?>>Amazonas</option>
										<option value="BA" <?php if($reg->estado == 'BA') { echo 'selected';}?>>Bahia</option>
										<option value="CE" <?php if($reg->estado == 'CE') { echo 'selected';}?>>Ceará</option>
										<option value="DF" <?php if($reg->estado == 'DF') { echo 'selected';}?>>Distrito Federal</option>
										<option value="ES" <?php if($reg->estado == 'ES') { echo 'selected';}?>>Espírito Santo</option>
										<option value="GO" <?php if($reg->estado == 'GO') { echo 'selected';}?>>Goiás</option>
										<option value="MA" <?php if($reg->estado == 'MA') { echo 'selected';}?>>Maranhão</option>
										<option value="MT" <?php if($reg->estado == 'MT') { echo 'selected';}?>>Mato Grosso</option>
										<option value="MS" <?php if($reg->estado == 'MS') { echo 'selected';}?>>Mato Grosso do Sul</option>
										<option value="MG" <?php if($reg->estado == 'MG') { echo 'selected';}?>>Minas Gerais</option>
										<option value="PA" <?php if($reg->estado == 'PA') { echo 'selected';}?>>Pará</option>
										<option value="PB" <?php if($reg->estado == 'PB') { echo 'selected';}?>>Paraíba</option>
										<option value="PR" <?php if($reg->estado == 'PR') { echo 'selected';}?>>Paraná</option>
										<option value="PE" <?php if($reg->estado == 'PE') { echo 'selected';}?>>Pernambuco</option>
										<option value="PI" <?php if($reg->estado == 'PI') { echo 'selected';}?>>Piauí</option>
										<option value="RJ" <?php if($reg->estado == 'RJ') { echo 'selected';}?>>Rio de Janeiro</option>
										<option value="RN" <?php if($reg->estado == 'RN') { echo 'selected';}?>>Rio Grande do Norte</option>
										<option value="RS" <?php if($reg->estado == 'RS') { echo 'selected';}?>>Rio Grande do Sul</option>
										<option value="RO" <?php if($reg->estado == 'RO') { echo 'selected';}?>>Rondônia</option>
										<option value="RR" <?php if($reg->estado == 'RR') { echo 'selected';}?>>Roraima</option>
										<option value="SC" <?php if($reg->estado == 'SC') { echo 'selected';}?>>Santa Catarina</option>
										<option value="SP" <?php if($reg->estado == 'SP') { echo 'selected';}?>>São Paulo</option>
										<option value="SE" <?php if($reg->estado == 'SE') { echo 'selected';}?>>Sergipe</option>
										<option value="TO" <?php if($reg->estado == 'TO') { echo 'selected';}?>>Tocantins</option>
									</select>
								</div>
								<div class="col-xs-3">
									<label for="pais">País:</label>
									<select disabled name="pais" id="pais" class="form-control">
										<option value="BRA">Brasil</option>
									</select>
								</div>
							</div>
							
							<div class="form-group row">
								<div class="col-xs-6">
									<label for="tel_res">Telefone Residencial:</label>
									<input type="number" name="tel_res" id="tel-res" class="form-control" value="<?php echo $reg->tel_res;?>">
									<span class="help-block">Formato: (xx) xxxx-xxxx [Só números]</span>
								</div>
								<div class="col-xs-6">
									<label for="tel_cel">Telefone Celular:</label>
									<input type="number" name="tel_cel" id="tel_cel" class="form-control" value="<?php echo $reg->tel_cel;?>">
									<span class="help-block">Formato: (xx) x xxxx-xxxx [Só números]</span>
								</div>
							</div>
							
							<div class="form-group">
								<label for="email">E-mail:</label>
								<input type="email" name="email" id="email" class="form-control" value="<?php echo $reg->email;?>">
							</div>
							
							<div class="form-group row">
								<div class="col-xs-6">
									<label for="congregacao">Congregação:</label>
									<input type="text" name="congregacao" id="congregacao" class="form-control" value="<?php echo $reg->congregacao;?>">
								</div>
								<div class="col-xs-6">
									<label for="congregacao_cidade_id">Cidade:</label>
									<select id="congregacao_cidade_id" name="congregacao_cidade_id" class="form-control">
									<?php 
									if($_SESSION['nivel'] == 20) { // Se for administrador, exibe todas as cidades
										$def = $pdo->query('SELECT * FROM cidade WHERE hospedeiro = 0 ORDER BY cidade ASC');
									} else { // Só exibe as cidades que o usuário é solicitante
										$def = $pdo->query('SELECT * FROM cidade WHERE hospedeiro = 0 AND solicitante_id = '.$_SESSION['id'].' ORDER BY cidade ASC');
									}
									if($def->rowCount() > 0) {
										while($lin = $def->fetch(PDO::FETCH_OBJ)) {
											if($reg->congregacao_cidade_id == $lin->id) {
												$selecionado = 'selected';
											} else {
												$selecionado = '';
											}
											
											echo <<<DADOS

										<option value="$lin->id" $selecionado>$lin->cidade/$lin->estado</option>
DADOS;
										}
											unset($selecionado);
									}
									?>
									</select>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="congresso_cidade">Cidade do congresso:</label>
								<input type="text" name="congresso_cidade" id="congresso_cidade" class="form-control" value="<?php echo $reg->congresso_cidade;?>">
							</div>
							
							<div class="form-group">
								<label for="check_in">Primeira noite que precisará do quarto:</label>
								<input type="date" name="check_in" id="check_in" class="form-control" value="<?php echo $reg->check_in?>">
							</div>
							
							<div class="form-group">
								<label for="check_out">Última noite que precisará do quarto:</label>
								<input type="date" name="check_out" id="check_out" class="form-control" value="<?php echo $reg->check_out;?>">
							</div>
							
							<div class="form-group">
								<label for="tipo_hospedagem">Tipo de acomodação:</label>
								<div class="radio" id="tipo_hospedagem">
									<label><input type="radio" name="tipo_hospedagem" value="casa" <?php if($reg->tipo_hospedagem == 'casa'){echo 'checked';}?>> Casa particular</label>
									<label><input type="radio" name="tipo_hospedagem" value="hotel" <?php if($reg->tipo_hospedagem == 'hotel'){echo 'checked';}?>> Hotel</label>
								</div>
							</div>
							
							<div class="form-group">
								<label for="pagamento">Quanto pode pagar por esse quarto, <span style="font-weight:bold; text-decoration: underline;">por noite (em reais)?</span></label>
								<input type="number" name="pagamento" id="pagamento" class="form-control" value="<?php echo $reg->pagamento;?>">
								<span class="help-block">Veja a seção "Quartos de Hotel" no fim do formulário</span>
							</div>
							
							<div class="form-group">
								<label for="transporte">Terá transporte próprio enquanto estiver na cidade do congresso?</label>
								<div class="radio" id="transporte">
									<label><input type="radio" name="transporte" value="SIM" <?php if($reg->transporte == 'SIM') {echo 'checked';}?>> Sim</label>
									<label><input type="radio" name="transporte" value="NÃO" <?php if($reg->transporte == 'NÃO') {echo 'checked';}?>> Não</label>
								</div>
							</div>
						</div>
					</div>
					
					<br>
					<h4><strong>OCUPANTES DO QUARTO <small>(É recomendado o máximo de 2 pessoas por casa.)</small></strong></h4>
					
					<table class="table table-responsive table-hover table-striped">
						<tbody>
							<tr><td>
								<h5><strong>Ocupante 1</strong></h5>
								<div class="form-group row">
									<div class="col-sm-3">
										<label for="oc1_nome">Nome:</label>
										<input type="text" name="oc1_nome" id="oc1_nome" class="form-control" value="<?php echo $reg->oc1_nome;?>">
									</div>
									<div class="col-sm-1">
										<label for="oc1_idade">Idade:</label>
										<input type="number" name="oc1_idade" id="oc1_idade" class="form-control" value="<?php echo $reg->oc1_idade;?>">
									</div>
									<div class="col-sm-1">
										<label for="oc1_sexo">Sexo:</label>
										<select name="oc1_sexo" id="oc1_sexo" class="form-control">
											<option value="M" <?php if($reg->oc1_sexo == 'M') {echo 'checked';}?>>Masculino</option>
											<option value="F" <?php if($reg->oc1_sexo == 'F') {echo 'checked';}?>>Feminino</option>
										</select>
									</div>
									<div class="col-sm-3">
										<label for="oc1_parente">Parentesco entre os ocupantes:</label>
										<input type="text" name="oc1_parente" id="oc1_parente" class="form-control" value="<?php echo $reg->oc1_parente;?>">
									</div>
									<div class="col-sm-2">
										<label for="oc1_etnia">Etnia:</label>
										<input type="text" name="oc1_etnia" id="oc1_etnia" class="form-control" value="<?php echo $reg->oc1_etnia;?>">
									</div>
									<div class="col-sm-2">
										<label for="oc1_privilegio" style="font-size: 80%">Publicador, pioneiro, etc...</label>
										<input type="text" name="oc1_privilegio" id="oc1_privilegio" class="form-control" value="<?php echo $reg->oc1_privilegio;?>">
									</div>
								</div>
							</td></tr>
							<tr><td>
								<h5><strong>Ocupante 2</strong></h5>
								<div class="form-group row">
									<div class="col-sm-3">
										<label for="oc2_nome">Nome:</label>
										<input type="text" name="oc2_nome" id="oc2_nome" class="form-control" value="<?php echo $reg->oc2_nome;?>">
									</div>
									<div class="col-sm-1">
										<label for="oc2_idade">Idade:</label>
										<input type="number" name="oc2_idade" id="oc2_idade" class="form-control" value="<?php echo $reg->oc2_idade;?>">
									</div>
									<div class="col-sm-1">
										<label for="oc2_sexo">Sexo:</label>
										<select name="oc2_sexo" id="oc2_sexo" class="form-control">
											<option value="M" <?php if($reg->oc2_sexo == 'M') {echo 'checked';}?>>Masculino</option>
											<option value="F" <?php if($reg->oc2_sexo == 'F') {echo 'checked';}?>>Feminino</option>
										</select>
									</div>
									<div class="col-sm-3">
										<label for="oc2_parente">Parentesco entre os ocupantes:</label>
										<input type="text" name="oc2_parente" id="oc2_parente" class="form-control" value="<?php echo $reg->oc2_parente;?>">
									</div>
									<div class="col-sm-2">
										<label for="oc2_etnia">Etnia:</label>
										<input type="text" name="oc2_etnia" id="oc2_etnia" class="form-control" value="<?php echo $reg->oc2_etnia;?>">
									</div>
									<div class="col-sm-2">
										<label for="oc2_privilegio" style="font-size: 80%">Publicador, pioneiro, etc...</label>
										<input type="text" name="oc2_privilegio" id="oc2_privilegio" class="form-control" value="<?php echo $reg->oc2_privilegio;?>">
									</div>
								</div>
							</td></tr>
							<tr><td>
								<h5><strong>Ocupante 3</strong></h5>
								<div class="form-group row">
									<div class="col-sm-3">
										<label for="oc3_nome">Nome:</label>
										<input type="text" name="oc3_nome" id="oc3_nome" class="form-control" value="<?php echo $reg->oc3_nome;?>">
									</div>
									<div class="col-sm-1">
										<label for="oc3_idade">Idade:</label>
										<input type="number" name="oc3_idade" id="oc3_idade" class="form-control" value="<?php echo $reg->oc3_idade;?>">
									</div>
									<div class="col-sm-1">
										<label for="oc3_sexo">Sexo:</label>
										<select name="oc3_sexo" id="oc3_sexo" class="form-control">
											<option value="M" <?php if($reg->oc3_sexo == 'M') {echo 'checked';}?>>Masculino</option>
											<option value="F" <?php if($reg->oc3_sexo == 'F') {echo 'checked';}?>>Feminino</option>
										</select>
									</div>
									<div class="col-sm-3">
										<label for="oc3_parente">Parentesco entre os ocupantes:</label>
										<input type="text" name="oc3_parente" id="oc3_parente" class="form-control" value="<?php echo $reg->oc3_parente;?>">
									</div>
									<div class="col-sm-2">
										<label for="oc3_etnia">Etnia:</label>
										<input type="text" name="oc3_etnia" id="oc3_etnia" class="form-control">
									</div>
									<div class="col-sm-2">
										<label for="oc3_privilegio" style="font-size: 80%">Publicador, pioneiro, etc...</label>
										<input type="text" name="oc3_privilegio" id="oc3_privilegio" class="form-control" value="<?php echo $reg->oc3_privilegio;?>">
									</div>
								</div>
							</td></tr>
							<tr><td>
								<h5><strong>Ocupante 4</strong></h5>
								<div class="form-group row">
									<div class="col-sm-3">
										<label for="oc4_nome">Nome:</label>
										<input type="text" name="oc4_nome" id="oc4_nome" class="form-control" value="<?php echo $reg->oc4_nome;?>">
									</div>
									<div class="col-sm-1">
										<label for="oc4_idade">Idade:</label>
										<input type="number" name="oc4_idade" id="oc4_idade" class="form-control" value="<?php echo $reg->oc4_idade;?>">
									</div>
									<div class="col-sm-1">
										<label for="oc4_sexo">Sexo:</label>
										<select name="oc4_sexo" id="oc4_sexo" class="form-control">
											<option value="M" <?php if($reg->oc4_sexo == 'M') {echo 'checked';}?>>Masculino</option>
											<option value="F" <?php if($reg->oc4_sexo == 'F') {echo 'checked';}?>>Feminino</option>
										</select>
									</div>
									<div class="col-sm-3">
										<label for="oc4_parente">Parentesco entre os ocupantes:</label>
										<input type="text" name="oc4_parente" id="oc4_parente" class="form-control" value="<?php echo $reg->oc4_parente;?>">
									</div>
									<div class="col-sm-2">
										<label for="oc4_etnia">Etnia:</label>
										<input type="text" name="oc4_etnia" id="oc4_etnia" class="form-control">
									</div>
									<div class="col-sm-2">
										<label for="oc4_privilegio" style="font-size: 80%">Publicador, pioneiro, etc...</label>
										<input type="text" name="oc4_privilegio" id="oc4_privilegio" class="form-control" value="<?php echo $reg->oc4_privilegio;?>">
									</div>
								</div>
							</td></tr>
						</tbody>
					</table>
					
					
					
					
					<br>
					
					
					<br>
					
					
					<br>
					
					
					<br>
					<div class="well sm-well">
						<h4><strong>SECRETÁRIO DA CONGREGAÇÃO</strong></h4>
						<div class="form-group">
							<label for="motivo">Explique por que esta é uma necessidade especial:</label>
							<textarea rows="2" class="form-control" name="motivo" id="motivo"><?php echo $reg->motivo;?></textarea>
						</div>
						
						<div class="form-group row">
							<div class="col-xs-4">
								<label for="secretario_nome">Nome do Secretário:</label>
								<input type="text" name="secretario_nome" id="secretario_nome" class="form-control" value="<?php echo $reg->secretario_nome;?>">
							</div>
							<div class="col-xs-4">
								<label for="secretario_tel">Telefone do Secretário:</label>
								<input type="number" name="secretario_tel" id="secretario_tel" class="form-control" value="<?php echo $reg->secretario_tel;?>">
							</div>
							<div class="col-xs-4">
								<label for="secretario_email">E-mail do Secretário:</label>
								<input type="text" name="secretario_email" id="secretario_email" class="form-control" value="<?php echo $reg->secretario_email;?>">
							</div>
						</div>
					</div>
					
					<br>
					<hr>
					<h4 class="text-center"><strong>LEIA COM ATENÇÃO AS INFORMAÇÕES ABAIXO ANTES DE PREENCHER ESTE FORMULÁRIO</strong></h4>
					
					<div style="column-count:2">
						Se você tem boa reputação na congregação e tem necessidades especiais que nem você nem a congregação têm condições de cuidar, você pode pedir hospedagem por meio do Departamento de Hospedagem do congresso. Não espere chegará cidade do congresso para fazer isso.
						<br>
						Deve-se preencher um Pedido Especial de Hospedagem (CO-5a) para cada quarto. Devem constar neste formulário somente os nomes das pessoas que ocuparão o mesmo quarto. Se por causa do transporte ou de outros motivos você precisar ficar num local perto de outro grupo, grampeie ou prenda com um clipe os formulários de Pedido Especial de Hospedagem, para que fiquem juntos.
						<br>
						Digite ou escreva as informações em letras de fôrma no formulário e o entregue ao secretário da congregação. Ele fará a verificação, assinará e enviará o formulário para o Departamento de Hospedagem do congresso a que você assistirá.
						<br>
						O Departamento de Hospedagem se esforçará para atender seu pedido. Pedimos que aceite as acomodações que forem escolhidas para você, visto que muito trabalho está envolvido nesses preparativos.
						<br>
						<strong>Quartos de hotel:</strong> Para obter os precos atualizados, consulte a Lista de Estabelecimentos Recomendados. Em geral é exigido o depósito de uma diária, creditado ao hotel, para garantir sua reserva. Lembre-se de que sua conduta no hotel deve ser irrepreensível, pois queremos glorificar o nome de Jeová.
						<br>
						<strong>Quartos em casas particulares:</strong> Esses são apenas para aqueles que não têm condições de pagar diárias de hotel. Geralmente, acomodar grupos grandes num único lugar não é fácil. Portanto, é melhor planejar grupos menores, de duas a quatro pessoas. Isso diminuirá o número de acomodações necessárias e tornará mais fácil encontrar um lugar para seu grupo. Se receber hospedagem numa casa particular, seria amoroso de sua parte contatar o hospedeiro para confirmar a data e a hora aproximada de sua chegada. Visto que você será hóspede na casa dele, tenha bom critério ao programar sua chegada, para que não seja numa hora inconveniente para o hospedeiro. Sua conduta como hóspede deve refletir excelentes princípios e qualidades cristãs.
					</div>
					
					<br>
					<div class="form-group">
						<input type="hidden" name="formulario_tipo" value="PEH">
						<input type="hidden" name="fid" value="<?php echo $reg->id;?>">
						<button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-send"></span> <strong>Confirmar alterações</strong></button>
					</div>
					
				</form>
			
			
			<?php
		}
	}
	
	?>
	
	

</div>
<!-- FIM CONTEUDO -->
<?php include('--footer.php');?>