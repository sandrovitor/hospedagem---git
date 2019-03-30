<?php include('--header.php');
if($_SESSION['nivel'] < 20) {
header('Location: ./');
exit();
}?>
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
						<li><a href="gerenciar.php">Gerenciar</a></li>
						<?php }?>					</ul>
						
				</li>
				<li><a href="atendimento.php"><span class="glyphicon glyphicon-headphones"></span> Atendimento</a></li>
				<li><a href="ajuda.php"><span class="glyphicon glyphicon-question-sign"></span> Ajuda</a></li>
				<li class="active"><a href="admin.php"><span class="glyphicon glyphicon-cog"></span> Administração</a></li>
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
		<li class="active">Administração</li>
	</ul>
	<!-- FIM DO BREADCRUMB -->
	
	
	<div class="row">
		<div class="col-xs-12">
			<?php 
			/*
			 * 
			 * CARREGA PÁGINAS DENTRO DA PÁGINA PRINCIPAL.
			 * CASO CONTRÁRIO, EXIBE MENU PRINCIPAL DA SEÇÃO ADMINISTRAÇÃO
			 * 
			 */
			if(isset($_GET['page']) && $_GET['page'] <> '') {
				switch($_GET['page']) {
					// USUÁRIOS
					case 1:
						include('page/UsuarioNovo.php');
						break;
						
					case 2:
						include('page/UsuarioEdita.php');
						break;
						
					case 3:
						include('page/UsuarioApaga.php');
						break;
						
					// SISTEMA
					case 20:
						include('page/CidadesEstado.php');
						break;
						
					case 21:
						include('page/Designacao.php');
						break;
						
					default:
						echo 'Em desenvolvimento..';
						break;
						
				}
			} else {
			// Se não enviar GET, mostra menu.
			?>
			<div class="row">
				<div class="col-xs-12 col-sm-4">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4><strong>USUÁRIOS</strong></h4>
						</div>
						<div class="panel-body">
							<a class="btn btn-link" href="admin.php?page=1"><span class="glyphicon glyphicon-plus"></span> Novo usuário</a><br>
							<a class="btn btn-link" href="admin.php?page=2"><span class="glyphicon glyphicon-edit"></span> Editar usuário</a><br>
							<a class="btn btn-link" href="admin.php?page=3"><span class="glyphicon glyphicon-erase"></span> Apagar usuário</a><br>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-4">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4><strong>SISTEMA</strong></h4>
						</div>
						<div class="panel-body">
							<h5><strong>CIDADES</strong></h5>
							<a class="btn btn-link" href="admin.php?page=20"><span class="glyphicon glyphicon-globe"></span> Cidades/Estado</a><br>
							<a class="btn btn-link" href="admin.php?page=21"><span class="glyphicon glyphicon-check"></span> Designações</a><br>
							
						 </div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-4">
					<?php include('page/status.php');?>
				</div>
			</div>
			<?php 
			}
			?>
		</div>
	</div>
</div>
<!-- FIM CONTEUDO -->
<?php include('--footer.php');?>