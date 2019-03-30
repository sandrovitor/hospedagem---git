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
    				<li class="nav-item"><a href="./" class="nav-link active scrollSuave"><span class="glyphicon glyphicon-home"></span> Início</a></li>
    				<li class="nav-item dropdown">
    					<a class="nav-link dropdown-toggle" href="#" id="navbardrop1" data-toggle="dropdown">
							<span class="glyphicon glyphicon-list-alt"></span> Formulários
						</a>
						<div class="dropdown-menu">
                        	<a class="dropdown-item scrollSuave" href="formulario.php"><span class="glyphicon glyphicon-plus"></span> Novo</a>
                        	<a class="dropdown-item scrollSuave" href="consulta.php"><span class="glyphicon glyphicon-search"></span> Consultar</a>
                        	<div class="dropdown-divider"></div> <?php if($_SESSION['nivel'] >= 10) {?>
                        	<a class="dropdown-item scrollSuave" href="gerenciar.php"><span class="glyphicon glyphicon-link"></span> Gerenciar</a>
                        	<a class="dropdown-item scrollSuave" href="atendimento.php"><span class="glyphicon glyphicon-headphones"></span> Atendimento</a>
                        	<div class="dropdown-divider"></div> <?php }?>
                        	<a class="dropdown-item scrollSuave" href="print.php"><span class="glyphicon glyphicon-print"></span> Imprimir</a>
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

<div class="container">
<!-- CONTEUDO -->

	<!-- BREADCRUMB -->
	<ul class="breadcrumb">
		<li class="breadcrumb-item active">Início</li>
	</ul>
	<!-- FIM DO BREADCRUMB -->
	
	<div class="alert-cookie">
		<strong>Por que os cookies?</strong><br><br> <small>Este sistema utiliza cookies para melhorar a experiência do usuário. Os cookies armazenam informações úteis no seu computador, para personalizar o sistema e até carregá-lo mais rapidamente. Ao acessar este sistema, você está autorizando o uso de cookies. Nenhuma informação pessoal sua será compartilhada com terceiros.</small>
		<br><br>
		<ul>
			<li><a href="javascript:void(0)" onclick="confirmaCookies()">Eu entendi</a></li>
		</ul>
		
	</div>
	
	
	<div class="row">
		<div class="col-12">
			<h3><strong>Bem vindo, <?php echo $_SESSION['nome'].' '.$_SESSION['sobrenome']; ?>.</strong></h3>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-12 col-md-6 col-lg-6">
			<div class="card">
				<div class="card-header text-center text-white bg-primary"><strong>ACOMODAÇÕES</strong></div>
				<div class="card-body">
					<table class="table table-bordered" style="font-size: 0.9rem">
						<tbody>
							<tr>
								<td>TOTAL DE ACOMODAÇÕES NO SISTEMA:</td>
								<td><span class="badge badge-primary" style="font-size: 1rem"><?php echo $sistema->getTotalAcomod(Sistema::ACO_TOTAL);?></span></td>
							</tr>
							<tr>
								<td>MINHAS CASAS P/ ACOMOD.:</td>
								<td><span class="badge badge-primary" style="font-size: 1rem"><?php echo $sistema->getParcialAcomod();?></span></td>
							</tr>
							<tr>
								<td>ACOMODAÇÕES EM REVISÃO:</td>
								<td><span class="badge badge-warning" style="font-size: 1rem"><?php echo $sistema->getTotalAcomod(Sistema::ACO_DESATIVADO);?></span></td>
							</tr>
							<tr>
								<td>ACOMODAÇÕES NÃO UTILIZADAS:</td>
								<td><span class="badge badge-success" style="font-size: 1rem"><?php echo $sistema->getTotalAcomod(Sistema::ACO_PENDENTE);?></span></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-6 col-lg-6">
			<div class="card">
				<div class="card-header text-center text-white bg-primary"><strong>PEDIDOS</strong></div>
				<div class="card-body">
					<table class="table table-bordered" style="font-size: 0.9rem">
						<tbody>
							<tr>
								<td>TOTAL DE PEDIDOS NO SISTEMA:</td>
								<td><span class="badge badge-primary" style="font-size: 1rem"><?php echo $sistema->getTotalPedidos(Sistema::PED_TOTAL);?></span></td>
							</tr>
							<?php if($_SESSION['nivel'] < 20) {?>
							<tr>
								<td>PEDIDOS SOB MINHA SUPERVISÃO (QTD. PARCIAL DO TOTAL ACIMA):</td>
								<td><span class="badge badge-primary" style="font-size: 1rem"><?php echo $sistema->getParcialPedidos();?></span></td>
							</tr>
							<?php }?>
							<tr>
								<td>PEDIDOS EM REVISÃO:</td>
								<td><span class="badge badge-warning" style="font-size: 1rem"><?php echo $sistema->getTotalPedidos(Sistema::PED_DESATIVADO);?></span></td>
							</tr>
							<tr>
								<td>PEDIDOS NÃO ATENDIDOS:</td>
								<td><span class="badge badge-danger" style="font-size: 1rem"><?php echo $sistema->getTotalPedidos(Sistema::PED_PENDENTE);?></span></td>
							</tr>
						</tbody>
					</table>
					<hr>
					<strong>RELATÓRIO DETALHADO
                    <a class="btn btn-link link_collapse" data-toggle="collapse" data-target="#ped_rel_simp">[+] Mostrar</a></strong>
                    <div id="ped_rel_simp" class="collapse" style="transition-duration:.5s">
    					<table class="table table-sm table-bordered">
                        	<tbody>
    							<?php echo $sistema->getRelatorioSimplesPedidos(TRUE);?>
    						</tbody>
    					</table>
    				</div>
				</div>
			</div>
		</div>
	</div>
	
	
    <!-- Modal -->
    <div id="modal01" class="modal fade" role="dialog">
    	<div class="modal-dialog">
    
    	   <!-- Modal content-->
    		<div class="modal-content">
    			<div class="modal-header">
    				<button type="button" class="close" data-dismiss="modal">&times;</button>
    				<h4 class="modal-title">Informações</h4>
    			</div>
    			<div class="modal-body">
    				
    			</div>
    			<div class="modal-footer">
    				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    			</div>
    		</div>
    	</div>
    </div>
</div>


<!-- jQuery (necessario para os plugins Javascript do Bootstrap) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<!-- Popper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
<script src="js/functions.js"></script><!-- FUNÇÕES -->
<?php include('footer.php');?>