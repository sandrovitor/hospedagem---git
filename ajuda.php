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
    				<li class="nav-item"><a href="ajuda.php" class="nav-link active scrollSuave"><span class="glyphicon glyphicon-question-sign"></span> Ajuda</a></li>
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
		<li class="breadcrumb-item active">Ajuda</li>
	</ul>
	<!-- FIM DO BREADCRUMB -->
	
	<div id="voltar_ao_topo" title="Voltar ao topo"></div>
	
	<div class="row">
		<div class="col-xs-12">
			<h3><span class="glyphicon glyphicon-question-sign"></span> <strong>Ajuda e Tutoriais</strong></h3>
			O que não for encontrado aqui, por favor, entre em contato com um dos administradores do sistema ou o desenvolvedor para esclarecer sua dúvida.<hr>
			
			<div class="card">
				<div class="card-header">
					<a href="javascript:void(0)" data-toggle="collapse" data-target="#cardbody-1"><strong>1. Como logar?</strong></a>
				</div>
				<div class="collapse" id="cardbody-1">
					<div class="card-body">
						<iframe width="560" height="315" src="https://www.youtube.com/embed/_g78-to0-9A?rel=0&amp;showinfo=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
					</div>
					<div class="card-footer">
						Link externo do vídeo: <a target="_blank" href="https://youtu.be/_g78-to0-9A">https://youtu.be/_g78-to0-9A</a>
					</div>
				</div>
			</div><br>
			
			<div class="card">
				<div class="card-header">
					<a href="javascript:void(0)" data-toggle="collapse" data-target="#cardbody-2"><strong>2. Como alterar suas informações?</strong></a>
				</div>
				<div class="collapse" id="cardbody-2">
					<div class="card-body">
						<iframe width="560" height="315" src="https://www.youtube.com/embed/CR7N4TrMBQA?rel=0&amp;showinfo=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
					</div>
					<div class="card-footer">
						Link externo do vídeo: <a target="_blank" href="https://youtu.be/CR7N4TrMBQA">https://youtu.be/CR7N4TrMBQA</a>
					</div>
				</div>
			</div><br>
			
			<div class="card">
				<div class="card-header">
					<a href="javascript:void(0)" data-toggle="collapse" data-target="#cardbody-3"><strong>3. Como trocar senha?</strong></a>
				</div>
				<div class="collapse" id="cardbody-3">
					<div class="card-body">
						<iframe width="560" height="315" src="https://www.youtube.com/embed/BjCSV-ukPfo?rel=0&amp;showinfo=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
					</div>
					<div class="card-footer">
						Link externo do vídeo: <a target="_blank" href="https://youtu.be/BjCSV-ukPfo">https://youtu.be/BjCSV-ukPfo</a>
					</div>
				</div>
			</div><br>
			
			<div class="card">
				<div class="card-header">
					<a href="javascript:void(0)" data-toggle="collapse" data-target="#cardbody-4"><strong>4. Como criar um Pedido Especial de Hospedagem (PEH)?</strong></a>
				</div>
				<div class="collapse" id="cardbody-4">
					<div class="card-body">
						<iframe width="560" height="315" src="https://www.youtube.com/embed/RXyTjg_e5Pg?rel=0&amp;showinfo=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
					</div>
					<div class="card-footer">
						Link externo do vídeo: <a target="_blank" href="https://youtu.be/RXyTjg_e5Pg">https://youtu.be/RXyTjg_e5Pg</a>
					</div>
				</div>
			</div><br>
			
			<div class="card">
				<div class="card-header">
					<a href="javascript:void(0)" data-toggle="collapse" data-target="#cardbody-5"><strong>5. Como criar um Formulário de Acomodação (FAC)?</strong></a>
				</div>
				<div class="collapse" id="cardbody-5">
					<div class="card-body">
						<iframe width="560" height="315" src="https://www.youtube.com/embed/l1ov_wY2c6Y?rel=0&amp;showinfo=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
					</div>
					<div class="card-footer">
						Link externo do vídeo: <a target="_blank" href="https://youtu.be/l1ov_wY2c6Y">https://youtu.be/l1ov_wY2c6Y</a>
					</div>
				</div>
			</div><br>
			
			<div class="card">
				<div class="card-header">
					<a href="javascript:void(0)" data-toggle="collapse" data-target="#cardbody-6"><strong>6. Como fazer consultas?</strong></a>
				</div>
				<div class="collapse" id="cardbody-6">
					<div class="card-body">
						<iframe width="560" height="315" src="https://www.youtube.com/embed/MdAkBrYUPXg?rel=0&amp;showinfo=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
					</div>
					<div class="card-footer">
						Link externo do vídeo: <a target="_blank" href="https://youtu.be/MdAkBrYUPXg">https://youtu.be/MdAkBrYUPXg</a>
					</div>
				</div>
			</div><br>
			
			<div class="card">
				<div class="card-header">
					<a href="javascript:void(0)" data-toggle="collapse" data-target="#cardbody-7"><strong>7. Como Gerenciar Pedidos e Acomodações?</strong></a>
				</div>
				<div class="collapse" id="cardbody-7">
					<div class="card-body">
						<iframe width="560" height="315" src="https://www.youtube.com/embed/R_NMlUZMkt4?rel=0&amp;showinfo=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
					</div>
					<div class="card-footer">
						Link externo do vídeo: <a target="_blank" href="https://youtu.be/R_NMlUZMkt4">https://youtu.be/R_NMlUZMkt4</a>
					</div>
				</div>
			</div><br>
			
			<div class="card">
				<div class="card-header">
					<a href="javascript:void(0)" data-toggle="collapse" data-target="#cardbody-8"><strong>8. Como fazer um atendimento?</strong></a>
				</div>
				<div class="collapse" id="cardbody-8">
					<div class="card-body">
						<iframe width="560" height="315" src="https://www.youtube.com/embed/Y78OeCd5gPk?rel=0&amp;showinfo=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
					</div>
					<div class="card-footer">
						Link externo do vídeo: <a target="_blank" href="https://youtu.be/Y78OeCd5gPk">https://youtu.be/Y78OeCd5gPk</a>
					</div>
				</div>
			</div><br>
			
			<div class="card">
				<div class="card-header">
					<a href="javascript:void(0)" data-toggle="collapse" data-target="#cardbody-9"><strong>9. Como imprimir?</strong></a>
				</div>
				<div class="collapse" id="cardbody-9">
					<div class="card-body">
						<iframe width="560" height="315" src="https://www.youtube.com/embed/6w5zP_kZWC0?rel=0&amp;showinfo=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
					</div>
					<div class="card-footer">
						Link externo do vídeo: <a target="_blank" href="https://youtu.be/6w5zP_kZWC0">https://youtu.be/6w5zP_kZWC0</a>
					</div>
				</div>
			</div><br>
			
			<div class="card">
				<div class="card-header">
					<a href="javascript:void(0)" data-toggle="collapse" data-target="#cardbody-10"><strong>10. Como editar um PEH ou FAC?</strong></a>
				</div>
				<div class="collapse" id="cardbody-10">
					<div class="card-body">
						<iframe width="560" height="315" src="https://www.youtube.com/embed/vJ4ZbZczO78?rel=0&amp;showinfo=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
					</div>
					<div class="card-footer">
						Link externo do vídeo: <a target="_blank" href="https://youtu.be/vJ4ZbZczO78">https://youtu.be/vJ4ZbZczO78</a>
					</div>
				</div>
			</div><br>
			
			
			
			<?php 
			if($_SESSION['nivel'] == 20) {
			?>
			<hr>
			<h4><strong>Administradores</strong></h4>
			
			<div class="card">
				<div class="card-header">
					<a href="javascript:void(0)" data-toggle="collapse" data-target="#cardbody-11"><strong>11. Como criar novo usuário?</strong></a>
				</div>
				<div class="collapse" id="cardbody-11">
					<div class="card-body">
						<iframe width="560" height="315" src="https://www.youtube.com/embed/wa1p2ZRhPlE?rel=0&amp;showinfo=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
					</div>
					<div class="card-footer">
						Link externo do vídeo: <a target="_blank" href="https://youtu.be/wa1p2ZRhPlE">https://youtu.be/wa1p2ZRhPlE</a>
					</div>
				</div>
			</div><br>
			
			<div class="card">
				<div class="card-header">
					<a href="javascript:void(0)" data-toggle="collapse" data-target="#cardbody-12"><strong>12. Como editar um usuário?</strong></a>
				</div>
				<div class="collapse" id="cardbody-12">
					<div class="card-body">
						<iframe width="560" height="315" src="https://www.youtube.com/embed/5jJgCNoFcjw?rel=0&amp;showinfo=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
					</div>
					<div class="card-footer">
						Link externo do vídeo: <a target="_blank" href="https://youtu.be/5jJgCNoFcjw">https://youtu.be/5jJgCNoFcjw</a>
					</div>
				</div>
			</div><br>
			
			<div class="card">
				<div class="card-header">
					<a href="javascript:void(0)" data-toggle="collapse" data-target="#cardbody-13"><strong>13. Como ver informações de outros usuários?</strong></a>
				</div>
				<div class="collapse" id="cardbody-13">
					<div class="card-body">
						<iframe width="560" height="315" src="https://www.youtube.com/embed/GzpjMSq3EEU?rel=0&amp;showinfo=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
					</div>
					<div class="card-footer">
						Link externo do vídeo: <a target="_blank" href="https://youtu.be/GzpjMSq3EEU">https://youtu.be/GzpjMSq3EEU</a>
					</div>
				</div>
			</div><br>
			
			<div class="card">
				<div class="card-header">
					<a href="javascript:void(0)" data-toggle="collapse" data-target="#cardbody-14"><strong>14. Como apagar um usuário?</strong></a>
				</div>
				<div class="collapse" id="cardbody-14">
					<div class="card-body">
						<iframe width="560" height="315" src="https://www.youtube.com/embed/1Pxd-ZbC1WE?rel=0&amp;showinfo=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
					</div>
					<div class="card-footer">
						Link externo do vídeo: <a target="_blank" href="https://youtu.be/1Pxd-ZbC1WE">https://youtu.be/1Pxd-ZbC1WE</a>
					</div>
				</div>
			</div><br>
			
			<div class="card">
				<div class="card-header">
					<a href="javascript:void(0)" data-toggle="collapse" data-target="#cardbody-15"><strong>15. Como criar uma nova cidade?</strong></a>
				</div>
				<div class="collapse" id="cardbody-15">
					<div class="card-body">
						<iframe width="560" height="315" src="https://www.youtube.com/embed/xyUeNuMcIfI?rel=0&amp;showinfo=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
					</div>
					<div class="card-footer">
						Link externo do vídeo: <a target="_blank" href="https://youtu.be/xyUeNuMcIfI">https://youtu.be/xyUeNuMcIfI</a>
					</div>
				</div>
			</div><br>
			
			<div class="card">
				<div class="card-header">
					<a href="javascript:void(0)" data-toggle="collapse" data-target="#cardbody-16"><strong>16. Como fazer designações?</strong></a>
				</div>
				<div class="collapse" id="cardbody-16">
					<div class="card-body">
						<iframe width="560" height="315" src="https://www.youtube.com/embed/danUfT5bcp8?rel=0&amp;showinfo=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
					</div>
					<div class="card-footer">
						Link externo do vídeo: <a target="_blank" href="https://youtu.be/danUfT5bcp8">https://youtu.be/danUfT5bcp8</a>
					</div>
				</div>
			</div><br>
			
			<div class="card">
				<div class="card-header">
					<a href="javascript:void(0)" data-toggle="collapse" data-target="#cardbody-17"><strong>17. Como redefinir o sistema?</strong></a>
				</div>
				<div class="collapse" id="cardbody-17">
					<div class="card-body">
						<iframe width="560" height="315" src="https://www.youtube.com/embed/QQ5FQU6aBng?rel=0&amp;showinfo=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
					</div>
					<div class="card-footer">
						Link externo do vídeo: <a target="_blank" href="https://youtu.be/QQ5FQU6aBng">https://youtu.be/QQ5FQU6aBng</a>
					</div>
				</div>
			</div><br>
			
			<div class="card">
				<div class="card-header">
					<a href="javascript:void(0)" data-toggle="collapse" data-target="#cardbody-18"><strong>18. Como gerar e visualizar um relatório geral?</strong></a>
				</div>
				<div class="collapse" id="cardbody-18">
					<div class="card-body">
						<iframe width="560" height="315" src="https://www.youtube.com/embed/-gzTONcQ3HY?rel=0&amp;showinfo=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
					</div>
					<div class="card-footer">
						Link externo do vídeo: <a target="_blank" href="https://youtu.be/-gzTONcQ3HY">https://youtu.be/-gzTONcQ3HY</a>
					</div>
				</div>
			</div><br>
			
			<?php
			}
			?>
			
			<br>
			Todas as ajudas fornecidas até aqui são em vídeo. Eventuais problemas ou perguntas frequentes que possam aparecer no uso diário do sistema, aparecerão em forma de ajuda em texto na seção a seguir.
			<hr>
			
			<h4><strong>OUTRAS AJUDAS e FAQ</strong></h4>
			<small class="text-secondary">Ainda não há itens aqui</small>
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