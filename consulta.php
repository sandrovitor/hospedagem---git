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
<link href="css/estilo_consulta.css" rel="stylesheet">
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
                        	<a class="dropdown-item active scrollSuave" href="consulta.php"><span class="glyphicon glyphicon-search"></span> Consultar</a>
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

<div class="container-fluid">
<!-- CONTEUDO -->

	<!-- BREADCRUMB -->
	<ul class="breadcrumb">
		<li class="breadcrumb-item active">Formulários</li>
	</ul>
	<!-- FIM DO BREADCRUMB -->

	<div class="row">
		<div class="col-12 col-md-4">
			<div class="card">
				<div class="card-header">
					<strong><a href="javascript:void(0)" data-toggle="collapse" data-target="#panel1">FORMULÁRIOS/PEDIDOS</a></strong>
				</div>
				<div id="panel1" class="collapse show">
    				<div class="card-body">
    					<div class="card" id="panel_peh">
    						<div class="card-header">
    							Pedidos Hospedagem <span class="badge badge-primary"><?php echo $sistema->getPEHQtd();?></span> <?php echo $sistema->getPEHRevisarQtd();?>
    							<span class="float-right"><a href="javascript:void(0)" data-toggle="collapse" data-target="#panel_body_peh">[+] Mostrar</a></span>
    						</div>
    						<div class="card-body collapse fade" id="panel_body_peh">
    							<?php echo $sistema->getConsultaListaPEH();?>
    						</div>
    					</div>
    					<br>
    					<?php // Não exibe essa seção para Solicitantes de hospedagem.
                        if($_SESSION['nivel'] >= 10){?>
    					<div class="card" id="panel_fac">
    						<div class="card-header">
    							Acomodações <span class="badge badge-primary"><?php echo $sistema->getFACQtd();?></span> <?php echo $sistema->getFACRevisarQtd();?>
    							<span class="float-right"><a href="javascript:void(0)" data-toggle="collapse" data-target="#panel_body_fac">[+] Mostrar</a></span>
    						</div>
    						<div class="card-body collapse fade" id="panel_body_fac">
    							<?php  echo $sistema->getConsultaListaFAC();?>
    						</div>
    					</div>
    					<?php }?>
    				</div>
    				<div class="card-footer">
    					<button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#Modal2"><span class="glyphicon glyphicon-info-sign"></span> <strong>Legenda</strong></button>
    				</div>
    			</div>
			</div>
		</div>
		<div class="col-12 col-md-8">
			<div class="card">
				<div class="card-body" id="panel3">
				
				</div>
			</div>
		</div>
	</div>


</div>
<!-- FIM CONTEUDO -->

<div id="Modal1" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- CONTEUDO DO MODAL -->
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><strong><span class="glyphicon glyphicon-lamp"></span> INFORMAÇÕES DA ACOMODAÇÃO</strong></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body" id="panel_acomodacao">
			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
			</div>
		</div>
		<!-- FIM DO CONTEUDO DO MODAL -->
	</div>
</div>

<div id="Modal2" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- CONTEUDO DO MODAL -->
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><strong><span class="glyphicon glyphicon-info-sign"></span> LEGENDA DOS ÍCONES</strong></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<table class="table table-condensed">
					<thead>
						<tr>
							<th>Ícone</th>
							<th>Descrição</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><span class="badge badge-success" data-toggle="tooltip" title="OK!"><span class="glyphicon glyphicon-ok"></span> OK !</span></td>
							<td>Pedido de Hospedagem já foi atendido com uma Acomodação.</td>
						</tr>
						<tr>
							<td><span class="badge badge-warning" data-toggle="tooltip" title="PENDENTE!"><span class="glyphicon glyphicon-question-sign"></span> PEN</span></td>
							<td>Pedido de Hospedagem ainda em aberto. Aguardando ser atendido...</td>
						</tr>
						<tr>
							<td><span class="badge badge-danger" data-toggle="tooltip" title="REVISAR!"><span class="glyphicon glyphicon-alert"></span> REV</span><br>
							<br><span class="badge badge-secondary" data-toggle="tooltip" title="TUDO OK"><span class="glyphicon glyphicon-alert"></span> REV</span></td>
							<td>Quando <span class="badge badge-danger">VERMELHO</span>: Pedido sinalizado com algum problema. Necessário revisão das informações pelo responsável.<br>Quando <span class="badge badge-secondary">CINZA</span>: Tudo Ok.</td>
						</tr>
						<tr>
							<td><span class="badge badge-success" data-toggle="tooltip" title="Acomodação já em vinculada!"><span class="glyphicon glyphicon-ok"></span> JÁ !</span></td>
							<td>Acomodação já está atendendo um Pedido de Hospedagem. Não pode ser vinculado a outro Pedido.</td>
						</tr>
						<tr>
							<td><span class="badge badge-warning" data-toggle="tooltip" title="ACOMODAÇÃO LIVRE!"><span class="glyphicon glyphicon-question-sign"></span> LIV</span></td>
							<td>Acomodação está livre para atender algum Pedido de Hospedagem.</td>
						</tr>
						<tr>
							<th class="text-center" colspan="2">BOTÕES</th>
						</tr>
						<tr>
							<td><button class="btn btn-sm btn-success" title="Editar" data-toggle="tooltip"><span class="glyphicon glyphicon-edit"></span></button></td>
							<td>Editar pedido de hospedagem ou formulário de acomodação.</td>
						</tr>
						<tr>
							<td><button class="btn btn-sm btn-danger" title="Apagar acomodação" data-toggle="tooltip"><span class="glyphicon glyphicon-erase"></span></button></td>
							<td>Apaga formulário de acomodação.</td>
						</tr>
						<tr>
							<td><button class="btn btn-sm btn-warning" title="Pedir revisão" data-toggle="tooltip" ><span class="glyphicon glyphicon-comment"></span></button></td>
							<td>Solicitar ao usuário que preencheu <strong><i>Pedido de Hospedagem</i></strong> que faça uma revisão nas informações. O botão de revisão ficará assim: <span class="badge badge-danger" data-toggle="tooltip" title="REVISAR!"><span class="glyphicon glyphicon-alert"></span> REV</span>.</td>
						</tr>
						<tr>
							<td><button class="btn btn-sm btn-secondary" title="Informação da Acomodação" data-toggle="tooltip"><span class="glyphicon glyphicon-lamp"></span></button></td>
							<td>Exibe informação da acomodação que atendeu a este pedido de hospedagem.<br>
							<strong>OBS.:</strong> Se o pedido ainda não foi atendido, esse botão não terá nenhuma ação.</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
			</div>
		</div>
		<!-- FIM DO CONTEUDO DO MODAL -->
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
