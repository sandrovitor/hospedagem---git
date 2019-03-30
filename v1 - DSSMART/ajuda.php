<?php include('--header.php');?>
<style>
dd {
	padding-left: 20px;
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
				<li class="active"><a href="ajuda.php"><span class="glyphicon glyphicon-question-sign"></span> Ajuda</a></li>
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
		<li class="active">Ajuda</li>
	</ul>
	<!-- FIM DO BREADCRUMB -->
	
	<div class="row">
		<div class="col-xs-12">
			<h3><span class="glyphicon glyphicon-question-sign"></span> <strong>Precisa de ajuda?</strong></h3><hr>
			
			<dl>
				<dt><span class="glyphicon glyphicon-chevron-right"></span> Como fazer login:</dt>
				<dd><span class="glyphicon glyphicon-facetime-video"></span> <a href="https://youtu.be/eoVwF837iqc" target="_blank">https://youtu.be/eoVwF837iqc</a></dd>
			</dl>
			<div class="hidden-xs">
				<iframe width="560" height="315" src="https://www.youtube.com/embed/eoVwF837iqc?rel=0" style="border: none;"></iframe>
				<br><br>
			</div>
			<hr>
			<?php
			if($_SESSION['nivel'] == 1 || $_SESSION['nivel'] == 20) {
				?>
			<dl>
				<dt><span class="glyphicon glyphicon-chevron-right"></span> Primeiros passos para Solicitante de Hospedagem:</dt>
				<dd><span class="glyphicon glyphicon-facetime-video"></span> <a href="https://youtu.be/leWVGWUh08c" target="_blank">https://youtu.be/leWVGWUh08c</a></dd>
			</dl>
			
			<div class="hidden-xs">
				<iframe width="560" height="315" src="https://www.youtube.com/embed/leWVGWUh08c?rel=0" style="border: none;"></iframe>
				<br><br>
			</div>
			<hr>
				<?php 
			}
			if($_SESSION['nivel'] == 10 || $_SESSION['nivel'] == 20) {
				?>
			<dl>
				<dt><span class="glyphicon glyphicon-chevron-right"></span> Primeiros passos para Responsável da Hospedagem:</dt>
				<dd><span class="glyphicon glyphicon-facetime-video"></span> <a href="https://youtu.be/mPVd57gLPXk" target="_blank">https://youtu.be/mPVd57gLPXk</a></dd>
			</dl>
			
			<div class="hidden-xs">
				<iframe width="560" height="315" src="https://www.youtube.com/embed/mPVd57gLPXk?rel=0" style="border: none;"></iframe>
				<br><br>
			</div><hr>
				<?php 
			}
			?>
			
		</div>
	</div>
	
</div>
<!-- FIM CONTEUDO -->
<?php include('--footer.php');?>