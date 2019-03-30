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
						<li class="active"><a href="formulario.php"><span class="glyphicon glyphicon-plus"></span> &nbsp; Novo</a></li>
						<li><a href="formulario.consulta.php"><span class="glyphicon glyphicon-search"></span> &nbsp; Consultar</a></li>
						<?php if($_SESSION['nivel'] >= 10) {?>
						<li class="divider"></li>
						<li><a href="gerenciar.php"><span class="glyphicon glyphicon-link"></span> &nbsp; Gerenciar</a></li>
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
		<li class="active">Formulários</li> 
	</ul>
	<!-- FIM DO BREADCRUMB -->


	<div class="row">
		<div class="col-md-12">
			<h4>Escolha um formulário:</h4>
			<div class="btn-group">
				<button type="button" class="btn btn-info" onclick="formChoose('PEH');" data-id="PEH" <?php if($_SESSION['nivel'] == 10) {echo 'disabled';}?>>Pedido Especial de Hospedagem</button>
				<button type="button" class="btn btn-info" onclick="formChoose('FAc');" data-id="FAc" <?php if($_SESSION['nivel'] == 1) {echo 'disabled';}?>>Formulário de Acomodações</button>
			</div>
			
			<div id="page_formulario" style="margin-top: 15px">
				<?php 
				if(isset($_POST) && isset($_POST['formulario_tipo']) && $_POST['formulario_tipo'] <> '') {
					switch($_POST['formulario_tipo']) {
						case 'PEH':
							// Se não for enviado o tipo de acomodação, seta como 'casa'
							if(!isset($_POST['tipo_hospedagem']) || $_POST['tipo_hospedagem'] == '') {
								$_POST['tipo_hospedagem'] = 'casa';
							}
							
							
							$query = "INSERT INTO `peh` (`id`, `nome`, `endereco`, `cidade`, `estado`, `tel_res`, `tel_cel`, `email`, `congregacao`, `congregacao_cidade_id`, `congresso_cidade`, `check_in`, `check_out`, `tipo_hospedagem`, `pagamento`, `transporte`, `oc1_nome`, `oc1_idade`, `oc1_sexo`, `oc1_parente`, `oc1_etnia`, `oc1_privilegio`, `oc2_nome`, `oc2_idade`, `oc2_sexo`, `oc2_parente`, `oc2_etnia`, `oc2_privilegio`, `oc3_nome`, `oc3_idade`, `oc3_sexo`, `oc3_parente`, `oc3_etnia`, `oc3_privilegio`, `oc4_nome`, `oc4_idade`, `oc4_sexo`, `oc4_parente`, `oc4_etnia`, `oc4_privilegio`, `motivo`, `secretario_nome`, `secretario_tel`, `secretario_email`, `data`) VALUES ";
							$query .= "(NULL, :nome, :endereco, :cidade, :estado, :tel_res, :tel_cel, :email, :congregacao, :congregacao_cidade_id, :congresso_cidade, :check_in, :check_out, :tipo_hospedagem, :pagamento, :transporte, :oc1_nome, :oc1_idade, :oc1_sexo, :oc1_parente, :oc1_etnia, :oc1_privilegio, :oc2_nome, :oc2_idade, :oc2_sexo, :oc2_parente, :oc2_etnia, :oc2_privilegio, :oc3_nome, :oc3_idade, :oc3_sexo, :oc3_parente, :oc3_etnia, :oc3_privilegio, :oc4_nome, :oc4_idade, :oc4_sexo, :oc4_parente, :oc4_etnia, :oc4_privilegio, :motivo, :secretario_nome, :secretario_tel, :secretario_email, :data)";
							
							
							
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
							
							
							$abc->execute();
							
							echo '<h2><span class="glyphicon glyphicon-ok"></span> Fomulário enviado</h2>';
							break;
							
						/*
						 * ##########################################################
						 */
							
						case 'FAc':
							
							
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
							
							// Conta quantidade de dias.
							if(array_search('todos', $_POST['dias']) != FALSE) {
								$dias = 'todos';
							} else {
								$dias = implode(';',$_POST['dias']);
							}
							
							
							$query = "INSERT INTO `fac` (`id`, `quartos_qtd`, `quarto1_sol_qtd`, `quarto1_cas_qtd`, `quarto1_valor1`, `quarto1_valor2`, `quarto2_sol_qtd`, `quarto2_cas_qtd`, `quarto2_valor1`, `quarto2_valor2`, `quarto3_sol_qtd`, `quarto3_cas_qtd`, `quarto3_valor1`, `quarto3_valor2`, `quarto4_sol_qtd`, `quarto4_cas_qtd`, `quarto4_valor1`, `quarto4_valor2`, `dias`, `andar`, `transporte`, `casa_tj`, `obs1`, `nome`, `endereco`, `cidade`, `telefone`, `publicador_nome`, `publicador_tel`, `cong_nome`, `cong_cidade`, `cong_sec`, `cong_tel`, `condicao`, `obs2`) VALUES ";
							$query .= "(NULL, :quartos_qtd, :quarto1_sol_qtd, :quarto1_cas_qtd, :quarto1_valor1, :quarto1_valor2, :quarto2_sol_qtd, :quarto2_cas_qtd, :quarto2_valor1, :quarto2_valor2, :quarto3_sol_qtd, :quarto3_cas_qtd, :quarto3_valor1, :quarto3_valor2, :quarto4_sol_qtd, :quarto4_cas_qtd, :quarto4_valor1, :quarto4_valor2, :dias, :andar, :transporte, :casa_tj, :obs1, :nome, :endereco, :cidade, :telefone, :publicador_nome, :publicador_tel, :cong_nome, :cong_cidade, :cong_sec, :cong_tel, :condicao, :obs2)";
							
							
							$abc = $pdo->prepare($query);
							
							$abc->bindValue(':quartos_qtd', $conta_quartos, PDO::PARAM_INT);
							$abc->bindValue(':quarto1_sol_qtd', $_POST['quarto1__sol_qtd'], PDO::PARAM_INT);
							$abc->bindValue(':quarto1_cas_qtd', $_POST['quarto1__cas_qtd'], PDO::PARAM_INT);
							$abc->bindValue(':quarto1_valor1', $_POST['quarto1_valor1'], PDO::PARAM_INT);
							$abc->bindValue(':quarto1_valor2', $_POST['quarto1_valor2'], PDO::PARAM_INT);
							
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
							$abc->bindValue(':cidade', $_POST['cidade'], PDO::PARAM_INT);
							$abc->bindValue(':telefone', $_POST['telefone'], PDO::PARAM_STR);
							$abc->bindValue(':publicador_nome', addslashes($_POST['publicador_nome']), PDO::PARAM_STR);
							$abc->bindValue(':publicador_tel', addslashes($_POST['publicador_tel']), PDO::PARAM_STR);
							$abc->bindValue(':cong_nome', addslashes($_POST['cong_nome']), PDO::PARAM_STR);
							$abc->bindValue(':cong_cidade', $_POST['cong_cidade'], PDO::PARAM_INT);
							$abc->bindValue(':cong_sec', addslashes($_POST['cong_sec']), PDO::PARAM_STR);
							$abc->bindValue(':cong_tel', $_POST['cong_tel'], PDO::PARAM_STR);
							$abc->bindValue(':condicao', $_POST['condicao'], PDO::PARAM_STR);
							$abc->bindValue(':obs2', addslashes($_POST['obs2']), PDO::PARAM_STR);
							
							
							$abc->execute();
							
							
							echo '<h2><span class="glyphicon glyphicon-ok"></span> Fomulário enviado</h2>';
							break;
					}
				}
				?>
			</div>
		</div>
	</div>
</div>
<!-- FIM CONTEUDO -->
<?php include('--footer.php');?>