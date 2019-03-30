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
<div id="topo"></div> <!-- ANCORA DE TOPO -->
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
                        	<a class="dropdown-item active scrollSuave" href="gerenciar.php"><span class="glyphicon glyphicon-link"></span> Gerenciar</a>
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
		<li class="breadcrumb-item">Formulários</li>
		<li class="breadcrumb-item active">Gerenciar</li>
	</ul>
	<!-- FIM DO BREADCRUMB -->
	
	<!-- 
	<div class="row visible-xs visible-sm">
		<div class="col-xs-12">
			<div class="alert alert-danger">
				<strong><span class="glyphicon glyphicon-alert"></span> &nbsp; Algo não está certo aqui!</strong><br><br>
				Esta página <strong>não</strong> deve ser acessada via dispositivo móvel, pois há risco de algumas informações ficarem distorcidas (ou talvez, escondidas). <strong>Acesse de um computador ou dispositivo com tela maior</strong>.
			</div>
		</div>
	</div>
	-->
	
	<!-- SOMENTE EM CELULARES, BOTÃO DE TOPO -->
	<div class="botao_topo visible-xs"><a href="#topo" class="btn btn-sm btn-dark"><span class="glyphicon glyphicon-chevron-up"></span> TOPO</a></div>
	<!-- SOMENTE EM CELULARES, BOTÃO DE TOPO -->
	
	<div class="row">
		<div class="col-12">
			<div class="frame_msg"></div>
		</div>
	</div>
	
	<div class="row"> <!--  ###################### PEH -->
		<div class="col-6">
			<div class="card">
				<div class="card-header">
					<h6 class="float-left"><strong>Pedidos de Hospedagem</strong></h6>
					<button type="button" class="btn btn-info btn-sm float-right hidden-xs hidden-sm" onclick="$('#modal2').modal()"><span class="glyphicon glyphicon-info-sign"></span> Ajuda</button>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-12 col-sm-6">
							<select name="peh" class="form-control" id="peh_select" onchange="gPehSelect();">
        						<?php echo $sistema->getG_PEHSelect();?>
        					</select>
						</div>
						<div class="col-12 col-sm-6">
							<!-- BOTÕES -->
							<button type="button" class="btn btn-warning" id="btn_act_revisar_peh" data-toggle="tooltip" title="Pedir revisão do Pedido de Hospedagem" style="display:none" onclick="gRevisarForms('peh', $('#peh_select').find(':selected').val(), $('#peh_select').find(':selected').data('token'))"><strong><span class="glyphicon glyphicon-comment"></span> Pedir revisão</strong></button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-6">
			<div class="card">
				<div class="card-header"> <!--  ###################### FAC -->
					<h6 class="float-left"><strong>Formulários de Acomodação</strong></h6>
					<button type="button" class="btn btn-info btn-sm float-right hidden-xs hidden-sm" onclick="$('#modal3').modal()"><span class="glyphicon glyphicon-info-sign"></span> Ajuda</button>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-12 col-sm-6">
							<select name="fac" class="form-control" id="fac_select" onchange="gFacSelect()">
        						<?php echo $sistema->getG_FACSelect();?>
        					</select>
						</div>
						<div class="col-12 col-sm-6">
							<!-- BOTÕES -->
							<button type="button" class="btn btn-warning" id="btn_act_revisar_fac" data-toggle="tooltip" title="Pedir revisão do Formulário de Acomodação" style="display:none" onclick="gRevisarForms('fac', $('#fac_select').find(':selected').val(), $('#fac_select').find(':selected').data('token'))"><strong><span class="glyphicon glyphicon-comment"></span> Pedir revisão</strong></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<br>
	
	<!-- MENU DE AÇÕES -->
	<div class="row">
		<div class="col-12">
			<div class="card border border-info">
				<div class="card-body">
					<div class="row">
						<div class="col-3 col-sm-2">
    						<h6 class="float-right"><strong>Ações:</strong></h6>
    					</div>
    					<div id="panel5" class="col-7 col-sm-8 text-center" style="display:none">
    						<div class="">
    							<button type="button" class="btn btn-primary" id="btn_act_vincular" data-toggle="tooltip" title="Vincular este Pedido de Hospedagem com esta Acomodação" onclick="vincularForms()" style="display:none;"><strong><span class="glyphicon glyphicon-random"></span> &nbsp;Vincular</strong></button>
    							<button type="button" class="btn btn-danger" id="btn_act_desvincular" data-toggle="tooltip" title="Desvincular este Pedido de Hospedagem com esta Acomodação" onclick="desvincularForms()" style="display:none;"><strong><span class="glyphicon glyphicon-scissors"></span> &nbsp;Desvincular</strong></button>
    							<button type="button" class="btn btn-danger" id="btn_act_desv1" data-toggle="tooltip" title="Desvincular Pedido de Hospedagem" style="display:none" onclick="desvAction('peh', $('#peh_select').val())"><strong><span class="glyphicon glyphicon-remove"></span> Liberar Ped. Hospedagem</strong></button>
    							<button type="button" class="btn btn-danger" id="btn_act_desv2" data-toggle="tooltip" title="Desvincular Acomodação" style="display:none" onclick="desvAction('fac', $('#fac_select').val())"><strong><span class="glyphicon glyphicon-remove"></span> Liberar Acomodação</strong></button>
    						</div>
    					</div>
					</div>
				</div>
			</div>
		</div>
	</div><br>
	<!-- FIM MENU DE AÇÕES -->
	
	<!-- TELA DE PEDIDOS / FORMULÁRIOS -->
	<div class="row">
		<div class="col-12 col-sm-6">
			<h5 class="text-center visible-xs"><strong>PEH</strong></h5>
			<div class="card border border-primary">
				<div class="card-body" style="min-height: 300px; max-height: 300px; overflow:auto;">
					<div id="panel3">
					
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-sm-6">
			<hr class="visible-xs">
			<h5 class="text-center visible-xs"><strong>FAC</strong></h5>
			<div class="card border border-primary">
				<div class="card-body" style="min-height: 300px; max-height: 300px; overflow:auto;">
					<div id="panel4">
					
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- FIM TELA DE PEDIDOS / FORMULÁRIOS -->
	
	
	
	
	<div id="modal1" class="modal fade" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="modal1_titulo"><strong><span class="glyphicon glyphicon-bell"></span> NOTIFICAÇÃO DO SISTEMA</strong></h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				
				<div class="modal-body" id="modal1_body">
					<div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span> <strong>OPERAÇÃO REALIZADA COM SUCESSO</strong></div>
					<div class="alert alert-danger"><span class="glyphicon glyphicon-exclamation-sign"></span> <strong>OPERAÇÃO RETORNOU ERRO</strong><br> A seguinte mensagem retornou do sistema: <br><br><strong><i>"'+data+'"</i></strong><br><br> Atualize a página e tente de novo, ou contate administrador.</div>
				</div>
				
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
				</div>
			</div>
		</div>
	</div>
	
	<div id="modal2" class="modal fade" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="modal2_titulo"><strong><span class="glyphicon glyphicon-info-sign"></span> AJUDA PEDIDOS DE HOSPEDAGEM</strong></h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<ul>
						<li>Escolha um pedido de hospedagem na lista, para visualizá-lo;</li>
						<li>Pedidos com marcação <strong>[OK!]</strong> na lista, já foram atendidos por acomodações;</li>
						<li>Fique atento à quantidade de pessoas em cada pedido de hospedagem;
						<div class="alert alert-warning">
							<strong>ATENÇÃO</strong> O sistema não notificará se o número de camas for incompatível (para menos ou para mais) com o número de pessoas no pedido.
						</div>
						</li>
						<li>Em casos de erros, tire um print ou foto da tela e envie para um administrador do sistema.</li>
					</ul>
					<hr>
					<h4 class="text-center"><strong>Como atender a um pedido de hospedagem?</strong></h4>
					<ol>
						<li>Escolha o pedido de hospedagem que irá ser atendido.</li>
						<li>Verifique o número de ocupantes na lista.</li>
						<li>Procure uma acomodação livre que atenda à quantidade de ocupantes e aos demais critérios no pedido.</li>
						<li>Clique no botão <button type="button" class="btn btn-secondary"><strong><span class="glyphicon glyphicon-random"></span> &nbsp;Vincular</strong></button> para vincular o pedido com a acomodação.</li>
					</ol>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
				</div>
			</div>
		</div>
	</div>
	
	<div id="modal3" class="modal fade" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="modal3_titulo"><strong><span class="glyphicon glyphicon-info-sign"></span> AJUDA FORMULÁRIOS DE ACOMODAÇÃO</strong></h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<ul>
						<li>Escolha um formulário de acomodação na lista, para visualizá-lo;</li>
						<li>Acomodações com marcação <strong>[OK!]</strong> na lista, já atenderam hospedagens;
						<div class="alert alert-warning">
							<strong>ATENÇÃO:</strong> Não é possível atender mais de um pedido com a mesma acomodação.<br>
							Se a acomodação possui diversos quartos com diversas camas, talvez seja melhor cadastrar cada quarto como uma acomodação diferente.<br><br>
							Essa prática visa organizar da melhor forma os recursos disponíveis, facilitar a organização e a compartimentalização de informações.
						</div>
						</li>
						<li>Fique atento à quantidade de camas em cada acomodação;
						<div class="alert alert-warning">
							<strong>ATENÇÃO</strong> O sistema não notificará se o número de camas for incompatível (para menos ou para mais) com o número de pessoas no pedido.
						</div>
						</li>
						<li>Em casos de erros, tire um print ou foto da tela e envie para um administrador do sistema.</li>
					</ul>
					<hr>
					<h4 class="text-center"><strong>Como atender a um pedido de hospedagem?</strong></h4>
					<ol>
						<li>Escolha o pedido de hospedagem que irá ser atendido.</li>
						<li>Verifique o número de ocupantes na lista.</li>
						<li>Procure uma acomodação livre que atenda à quantidade de ocupantes e aos demais critérios no pedido.</li>
						<li>Clique no botão <button type="button" class="btn btn-secondary"><strong><span class="glyphicon glyphicon-random"></span> &nbsp;Vincular</strong></button> para vincular o pedido com a acomodação.</li>
					</ol>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
				</div>
			</div>
		</div>
	</div>
	
	
</div>
<!-- FIM CONTEÚDO -->


<!-- jQuery (necessario para os plugins Javascript do Bootstrap) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<!-- Popper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
<script src="js/functions.js"></script><!-- FUNÇÕES -->
<script src="js/gerenciar.js"></script><!-- FUNÇÕES GERENCIAMENTO -->
<?php include('footer.php');?>