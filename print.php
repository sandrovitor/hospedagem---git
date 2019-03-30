<?php include('classes/autoload.php'); $sistema = new Sistema();?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Hospedagem LS-03</title>
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
<link href="css/estilo.css" rel="stylesheet">
<link href="css/glyphicon.css" rel="stylesheet">
<link href="css/estilo_index.css" rel="stylesheet">
<!-- <link href="css/chosen.css" rel="stylesheet"> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> <!-- Font Awesome -->
<link rel="icon" href="images/favicon.png" />
</head>

<body>
<div id="overlay"><div class="loader1"></div><div class="loader-icon"><span class="glyphicon glyphicon-lamp"></span></div></div>
<!-- INICIO DO NAVBAR -->
<nav class="navbar navbar-expand-lg bg-bzs fixed-top">
		<div class="container-fluid">
			<a class="navbar-brand" href="./"><strong>Hospedagem LS-03</strong></a>
			<button class="navbar-toggler bg-primary" type="button" data-toggle="collapse" data-target="#menuPrincipal">
            	<i class="fa fa-bars" style="font-size:24px; color: #fff;"></i>
            </button>
            
            <div class="collapse navbar-collapse" id="menuPrincipal">
    			<ul class="navbar-nav mr-auto">
    				<li class="nav-item"><a href="./" class="nav-link scrollSuave"><span class="glyphicon glyphicon-home"></span> Início</a></li>
    				<li class="nav-item dropdown">
    					<a class="nav-link active dropdown-toggle" href="#" id="navbardrop1" data-toggle="dropdown">
							<span class="glyphicon glyphicon-list-alt"></span> Formulários
						</a>
						<div class="dropdown-menu">
                        	<a class="dropdown-item scrollSuave" href="formulario.php"><span class="glyphicon glyphicon-plus"></span> Novo</a>
                        	<a class="dropdown-item scrollSuave" href="consulta.php"><span class="glyphicon glyphicon-search"></span> Consultar</a>
                        	<div class="dropdown-divider"></div> <?php if($_SESSION['nivel'] >= 10) {?>
                        	<a class="dropdown-item scrollSuave" href="gerenciar.php"><span class="glyphicon glyphicon-link"></span> Gerenciar</a>
                        	<a class="dropdown-item scrollSuave" href="atendimento.php"><span class="glyphicon glyphicon-headphones"></span> Atendimento</a>
                        	<div class="dropdown-divider"></div> <?php }?>
                        	<a class="dropdown-item active scrollSuave" href="print.php"><span class="glyphicon glyphicon-print"></span> Imprimir</a>
                        </div>
    				</li>
    				<li class="nav-item"><a href="ajuda.php" class="nav-link scrollSuave"><span class="glyphicon glyphicon-question-sign"></span> Ajuda</a></li>
    				<?php if($_SESSION['nivel'] == 20) {?>
    				<li class="nav-item"><a href="admin.php" class="nav-link scrollSuave"><span class="glyphicon glyphicon-cog"></span> Administração</a></li>
    				<?php }?>
    			</ul>
    			<ul class="navbar-nav ml-auto">
    				<li class="nav-item dropdown">
    					<a class="nav-link dropdown-toggle" href="#" id="navbardrop2" data-toggle="dropdown">
							<span class="glyphicon glyphicon-user"></span> <?php echo $_SESSION['nome'].' '.$_SESSION['sobrenome']; ?>
						</a>
                        <div class="dropdown-menu">
                        	<a class="dropdown-item scrollSuave" href="perfil.php"><span class="glyphicon glyphicon-edit"></span> Meu perfil</a>
                        	<div class="dropdown-divider"></div>
                        	<a class="dropdown-item scrollSuave" href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Sair</a>
                        </div>
					</li>
    			</ul>
    		</div>
		</div>
</nav>
<!-- FIM NAVBAR -->


<!-- CONTEUDO -->
<div class="container-fluid">

	<!-- BREADCRUMB -->
	<ul class="breadcrumb">
		<li class="breadcrumb-item">Formulários</li>
		<li class="breadcrumb-item active">Imprimir</li>
	</ul>
	<!-- FIM DO BREADCRUMB -->
	
	<div class="row">
		<div class="col-12 col-md-10 offset-md-1 col-lg-8 offset-lg-2">
			<form method="post">
				<div class="form-group" id="div1">
					<h3 class="text-center"><strong>O que deseja imprimir?</strong></h3>
					<select name="sel1" id="sel1" class="form-control">
						<option value="0" selected="selected" disabled="disabled">Escolha:</option>
						<option value="1">Pedido de Hospedagem</option>
						<?php if($_SESSION['nivel'] >= 10) {?><option value="2">Formulário de Acomodação</option><?php }?>
					</select>
					
					<div class="card bg-info text-white">
						<div class="card-body">
        					* Caso a página congele (trave), basta atualizar a página e tentar novamente.<br>
        					* Não use botões de <strong>Voltar</strong> ou <strong>Avançar</strong> nesta página.
        				</div>
					</div>
				</div>
				
				<div class="form-group" id="div2" style="display:none">
					<h3 class="text-center"><strong>Certo! Agora me diga, como você deseja o <i><span class="badge badge-primary" id="div2_texto"></span></i>?</strong></h3>
					<select name="sel2" id="sel2" class="form-control">
						<option value="0">Escolha:</option>
						<option value="1">Individual</option>
						<option value="2">Por cidade/região</option>
						
						<option value="5">TUDO</option>
					</select>
					
					<div class="card bg-info text-white">
						<div class="card-body">
        					* Caso a página congele (trave), basta atualizar a página e tentar novamente.<br>
        					* Não use botões de <strong>Voltar</strong> ou <strong>Avançar</strong> nesta página.
        				</div>
					</div>
				</div>
				
				<div class="form-group" id="div5" style="display:none">
					<h3 class="text-center"><strong>Muito bem! Falta só mais um coisa...<br><br> <span class="glyphicon glyphicon-arrow-down hidden-sm hidden-xs"></span> Escolha qual <i><span class="badge badge-primary" id="div5_texto"></span></i> <span style="color: #b30000">individual</span> você quer... </strong> <span class="glyphicon glyphicon-arrow-down"></span></h3>
					<select name="sel5" id="sel5" class="form-control">
					
					</select>
					
					<div class="card bg-info text-white">
						<div class="card-body">
        					* Caso a página congele (trave), basta atualizar a página e tentar novamente.<br>
        					* Não use botões de <strong>Voltar</strong> ou <strong>Avançar</strong> nesta página.
        				</div>
					</div>
				</div>
				
				<div class="form-group" id="div6" style="display:none">
					<h3 class="text-center"><strong>Muito bem! Falta só mais um coisa...<br><br> <span class="glyphicon glyphicon-arrow-down hidden-sm hidden-xs"></span> Qual <span style="color: #b30000">cidade/região</span> do <i><span class="badge badge-primary" id="div6_texto"></span></i> você quer... </strong> <span class="glyphicon glyphicon-arrow-down"></span></h3>
					<select name="sel6" id="sel6" class="form-control">
					
					</select>
					
					<div class="card bg-info text-white">
						<div class="card-body">
        					* Caso a página congele (trave), basta atualizar a página e tentar novamente.<br>
        					* Não use botões de <strong>Voltar</strong> ou <strong>Avançar</strong> nesta página.
        				</div>
					</div>
				</div>
				
				<div class="form-group" id="div10" style="display:none">
					<h4 class="text-center">Obrigado, já entendi o que você precisa!</h4><br>
					<h3 class="text-center">
						<small><strong>Você pediu >>> &nbsp;[<span class="badge badge-primary" id="div10_texto1"></span> <span class="badge badge-secondary" id="div10_texto2"></span>]</strong></small><br>
						Clique no botão abaixo para imprimir:
					</h3>
					<br>
					<div class="text-center">
						<button type="button" class="btn btn-lg btn-success" onclick="gerarImpressao()"><span class="glyphicon glyphicon-print"></span> &nbsp; &nbsp; IMPRIMIR</button>
						<button type="button" class="btn btn-lg btn-warning" onclick="repetir()"><span class="glyphicon glyphicon-refresh"></span> Repetir</button>
					</div>
					
					<br><br><br>
					<div class="alert alert-info" style="display:none">
						<strong><span class="glyphicon glyphicon-info-sign"></span> DESATIVE SEU BLOQUEADOR DE POP-UPS</strong><br>
						Se um bloqueador de pop-ups estiver sendo utilizado, você não conseguirá ver a impressão. Marque a opção <strong>"Sempre mostrar"</strong>.<br>
						<img class="img-responsive img-thumbnail" src="images/pop-up.png"><br><br>
						<strong>Em caso de dúvidas, solicite ajuda.</strong>
					</div>
					<hr>
				</div>
				
				
				
			</form>
			<div id="div100">
			
			</div>
		</div>
	</div>
	
	
	
	
	
</div>
<!-- FIM CONTEUDO -->

<!-- jQuery (necessario para os plugins Javascript do Bootstrap) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<!-- Popper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
<script src="js/functions.js"></script><!-- FUNÇÕES -->
<script src="js/functions_print.js"></script><!-- FUNÇÕES PRINT -->
<?php include('footer.php');?>