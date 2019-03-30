<?php include('--header.php');?>
<style>
dd {
	color: #804d00;
	padding-left: 15px;
}
dt {
	margin-top: 12px;
}
dt:FIRST-CHILD {
	margin-top: 0px;
}
dl a {
	margin-left: 15px;
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
						<li class="active"><a href="perfil.php"><span class="glyphicon glyphicon-edit"></span> Meu perfil</a></li>
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
		<li class="active">Meu perfil</li>
	</ul>
	<!-- FIM DO BREADCRUMB -->
	
	<?php 
	// Se enviado algum dado via $_POST, executa scripts aqui.
	// Qualquer retorno dos scripts, será escrito nesse trecho de código, para evitar erros no HTML.
	if(isset($_POST['funcao']) && $_POST['funcao'] <> '') {
		switch($_POST['funcao']) {
			case 'atualiza_info':
				include('app/perfil_atualiza.php');
				break;
				
			case 'troca_senha':
				include('app/perfil_senha_atualiza.php');
				break;
		}
		
		
	}
	?>
	
	<div class="row">
		<div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<strong>Minhas informações</strong>
				</div>
				<div class="panel-body">
					<?php 
					function nivelAcesso() {
						switch($_SESSION['nivel']) {
							case 1:
								echo 'Nivel 1 - {<small>Solicitante de Hospedagem</small>}';
								break;
								
							case 10:
								echo 'Nivel 10 - {<small>Responsável da Hospedagem</small>}';
								break;
								
							case 20:
								echo 'Nivel 20 - {<small>Administrador</small>}';
								break;
						}
					}
					?>
					<dl>
						<dt>Nome:</dt>
						<dd><?php echo $_SESSION['nome'].' '.$_SESSION['sobrenome'];?>
						<a href="#Panel1" data-toggle="tooltip" title="Editar" onclick="if(!$('#Panel1').hasClass('in')){$('#Panel1_a').click();} $('#form1_nome').focus();"><span class="glyphicon glyphicon-edit"></span></a></dd>
						
						<dt>Nome de usuário:</dt>
						<dd><?php echo $_SESSION['usuario'];?>
						
						<dt>Nível de acesso:</dt>
						<dd><?php nivelAcesso();?></dd>
						
						<dt>Telefone Residencial:</dt>
						<dd><?php
						echo '('.substr($_SESSION['tel_res'], 0, 2).') '.substr($_SESSION['tel_res'], 2, 4).'-'.substr($_SESSION['tel_res'], 6);
						?>
						<a href="#Panel1" data-toggle="tooltip" title="Editar" onclick="if(!$('#Panel1').hasClass('in')){$('#Panel1_a').click();} $('#form1_tel_res').focus();"><span class="glyphicon glyphicon-edit"></span></a></dd>
						
						<dt>Telefone Celular:</dt>
						<dd><?php 
						echo $telefone = '('.substr($_SESSION['tel_cel'], 0, 2).') '.substr($_SESSION['tel_cel'], 2, 1).' '.substr($_SESSION['tel_cel'], 3, 4).'-'.substr($_SESSION['tel_cel'], 7);
						?>
						<a href="#Panel1" data-toggle="tooltip" title="Editar" onclick="if(!$('#Panel1').hasClass('in')){$('#Panel1_a').click();} $('#form1_tel_cel').focus();"><span class="glyphicon glyphicon-edit"></span></a></dd>
						
						<dt>E-mail:</dt>
						<dd><?php echo $_SESSION['email'];?>
						<a href="#Panel1" data-toggle="tooltip" title="Editar" onclick="if(!$('#Panel1').hasClass('in')){$('#Panel1_a').click();} $('#form1_email').focus();"><span class="glyphicon glyphicon-edit"></span></a></dd>
						
						<?php 
						if($_SESSION['nivel'] == 1) {
							?>
						<dt>Cidade(s) Designada(s): </dt>
							<?php 
							$abc = $pdo->query('SELECT * FROM `cidade` WHERE `solicitante_id` = '.$_SESSION['id']);
							if($abc->rowCount() > 0) {
								while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
									echo '<dd>'.$reg->cidade.'/'.$reg->estado.'</dd>';
								}
							} else {
								echo '<dd><i>Ainda sem cidade designada</i></dd>';	
							}
						} else if($_SESSION['nivel'] == 10) {
							?>
						<dt>Cidade(s) Designada(s): </dt>
							<?php 
							$abc = $pdo->query('SELECT * FROM `cidade` WHERE `resp_id` = '.$_SESSION['id']);
							if($abc->rowCount() > 0) {
								while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
									echo '<dd>'.$reg->cidade.'/'.$reg->estado.'</dd>';
								}
							} else {
								echo '<dd><i>Ainda sem cidade designada</i></dd>';
							}
						}
						?>
					</dl>
				</div>
			</div>
		</div>
		<div class="col-md-8">
			<div class="row">
				<div class="col-md-6">
					<div class="panel panel-default">
						<div class="panel-heading">
							<a data-toggle="collapse" data-target="#Panel1" id="Panel1_a"><strong>Atualizar informações</strong></a>
						</div>
						<div id="Panel1" class="panel-collapse collapse">
							<div class="panel-body">
								<form id="form1" method="post">
									<div class="form-group">
										<label for="form1_nome">Nome:<span class="red">*</span></label>
										<input type="text" id="form1_nome" name="nome" class="form-control" value="<?php echo $_SESSION['nome'];?>" required>
									</div>
									<div class="form-group">
										<label for="form1_sobrenome">Sobrenome:<span class="red">*</span></label>
										<input type="text" id="form1_sobrenome" name="sobrenome" class="form-control" value="<?php echo $_SESSION['sobrenome'];?>" required>
									</div>
									<div class="form-group">
										<label for="form1_usuario">Nome de usuário:</label>
										<input type="text" id="form1_usuario" name="usuario" class="form-control" value="<?php echo $_SESSION['usuario'];?>" disabled>
									</div>
									<div class="form-group">
										<label for="form1_tel_res">Telefone Residencial:</label>
										<input type="text" id="form1_tel_res" name="tel_res" class="form-control" value="<?php echo $_SESSION['tel_res'];?>">
									</div>
									<div class="form-group">
										<label for="form1_tel_cel">Telefone Celular:<span class="red">*</span></label>
										<input type="text" id="form1_tel_cel" name="tel_cel" class="form-control" value="<?php echo $_SESSION['tel_cel'];?>" required>
									</div>
									<div class="form-group">
										<label for="form1_email">Email:<span class="red">*</span></label>
										<input type="text" id="form1_email" name="email" class="form-control" value="<?php echo $_SESSION['email'];?>" required>
									</div>
									<div class="form-group">
										<input type="hidden" name="funcao" value="atualiza_info">
										<button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-send"></span> Atualizar</button>
									</div>
								</form>
							</div>
							<div class="panel-footer">
								<span class="help-block">
									Itens marcados com (<span class="red">*</span>) são de preenchimento obrigatório, não podendo ficar vazios.
								</span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="panel panel-default">
						<div class="panel-heading">
							<a data-toggle="collapse" data-target="#Panel2" id="Panel2_a"><strong>Trocar Senha</strong></a>
						</div>
						<div id="Panel2" class="panel-collapse collapse">
							<div class="panel-body">
								<form id="form2" method="post">
									<div class="form-group">
										<label for="form2_senha_atual">Senha atual:<span class="red">*</span></label>
										<input type="password" id="form2_senha_atual" name="senha_atual" class="form-control" required>
									</div>
									<div class="form-group">
										<label for="form2_nova_senha1">Nova senha:<span class="red">*</span></label>
										<input type="password" id="form2_nova_senha1" name="nova_senha1" class="form-control" required>
									</div>
									<div class="form-group">
										<label for="form2_nova_senha2">Repita nova senha:<span class="red">*</span></label>
										<input type="password" id="form2_nova_senha2" name="nova_senha2" class="form-control" required>
									</div>
									<div class="form-group">
										<input type="hidden" name="funcao" value="troca_senha">
										<button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-send"></span> Atualizar</button>
									</div>
								</form>
							</div>
							<div class="panel-footer">
								<span class="help-block">
									Itens marcados com (<span class="red">*</span>) são de preenchimento obrigatório, não podendo ficar vazios.
								</span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
				
				</div>
			</div>
		</div>
	</div>
</div>
<!-- FIM CONTEUDO -->
<?php include('--footer.php');?>