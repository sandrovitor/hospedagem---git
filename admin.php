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
<link href="css/estilo_adm.css" rel="stylesheet">
<!-- <link href="css/chosen.css" rel="stylesheet"> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> <!-- Font Awesome -->
<link rel="icon" href="images/favicon.png" />
<style>
label {
    font-weight:bold;
}
</style>
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
    				<li class="nav-item"><a href="admin.php" class="nav-link active scrollSuave"><span class="glyphicon glyphicon-cog"></span> Administração</a></li>
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



<?php 
if(!isset($_GET['page']) || $_GET['page'] == '') {
?>

<!-- CONTEUDO -->
<div class="container">


	<!-- BREADCRUMB -->
	<ul class="breadcrumb">
		<li class="breadcrumb-item active">Administração</li>
	</ul>
	<!-- FIM DO BREADCRUMB -->
	
	<div class="row">
		<div class="col-12" id="frame_msg"></div>
	</div>
	
	<div class="row">
		<div class="col-12 col-md-6 col-lg-4">
			<div class="card">
				<div class="card-header">
					<strong>USUÁRIOS</strong>
				</div>
				<div class="card-body" style="font-size: 120%">
					<a href="?page=1"><i class="fa fa-user-plus"></i> &nbsp; Novo</a>
					<br>
					<a href="?page=2"><i class="fa fa-edit"></i> &nbsp; Editar</a>
					<br>
					<a href="?page=3"><i class="fa fa-address-book"></i> &nbsp; Listar/Ver</a>
					<br>
					<a href="?page=4"><i class="fa fa-user-times"></i> &nbsp; Apagar</a>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-6 col-lg-4">
			<div class="card">
				<div class="card-header">
					<strong>SISTEMA</strong>
				</div>
				<div class="card-body" style="font-size: 120%">
					<a href="?page=11"><i class="fa fa-globe"></i> &nbsp; Cidades</a>
					<br>
					<a href="?page=12"><i class="fa fa-group"></i> &nbsp; Designações</a>
					<br>
					<a href="?page=13"><i class="fa fa-refresh"></i> &nbsp; Redefinir</a>
					<br>
					<a href="?page=14"><i class="fa fa-line-chart"></i> &nbsp; RELATÓRIO GERAL</a>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-4">
			
		</div>
	</div>
	
</div>
	<?php 
	} else {
	    switch($_GET['page']) {
	        case 1:
	           $pagina = new Paginas();
	           echo $pagina->NovoUsuario();
	           break;
	           
	        case 2:
	            $pagina = new Paginas();
	            echo $pagina->EditarUsuario();
	            break;
	            
	        case 3:
	            $pagina = new Paginas();
	            echo $pagina->ListarUsuario();
	            break;
	            
	        case 4:
	            $pagina = new Paginas();
	            echo $pagina->ApagarUsuario();
	            break;
	            
	        case 11:
	            $pagina = new Paginas();
	            echo $pagina->Cidades();
	            break;
	            
	        case 12:
	            $pagina = new Paginas();
	            echo $pagina->Designacoes();
	            break;
	            
	        case 13:
	            $pagina = new Paginas();
	            echo $pagina->Redefinir();
	            break;
	            
	        case 14:
	            $pagina = new Paginas();
	            echo $pagina->RelatorioGeral();
	            break;
	    }
	}
	?>



<!-- jQuery (necessario para os plugins Javascript do Bootstrap) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<!-- Popper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
<script src="js/functions.js"></script><!-- FUNÇÕES -->
<?php 

// Carrega scripts individuais a depender da página
if(isset($_GET['page']) && $_GET['page'] == 14) {
    echo <<<DADOS
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.js"></script> <!-- ChartJS -->
<script type="text/javascript" src="js/graficos_relatorio.js"></script> <!-- GRAFICOS SISTEMA -->
DADOS;
}
?>
<?php include('footer.php');?>