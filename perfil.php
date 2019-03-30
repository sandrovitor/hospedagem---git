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
    				<li class="nav-item"><a href="ajuda.php" class="nav-link scrollSuave"><span class="glyphicon glyphicon-question-sign"></span> Ajuda</a></li>
    				<?php if($_SESSION['nivel'] == 20) {?>
    				<li class="nav-item"><a href="admin.php" class="nav-link scrollSuave"><span class="glyphicon glyphicon-cog"></span> Administração</a></li>
    				<?php }?>
    			</ul>
    			<ul class="navbar-nav ml-auto">
    				<li class="nav-item dropdown">
    					<a class="nav-link active dropdown-toggle" href="#" id="navbardrop2" data-toggle="dropdown">
							<span class="glyphicon glyphicon-user"></span> <?php echo $_SESSION['nome'].' '.$_SESSION['sobrenome']; ?>
						</a>
                        <div class="dropdown-menu">
                        	<a class="dropdown-item active scrollSuave" href="perfil.php"><span class="glyphicon glyphicon-edit"></span> Meu perfil</a>
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
		<li class="breadcrumb-item active">Perfil</li>
	</ul>
	<!-- FIM DO BREADCRUMB -->
	
	<div class="row">
		<div class="col-12" id="frame_msg"></div>
	</div>
	<div class="row">
		<div class="col-12 col-md-4">
			<div class="card">
				<div class="card-header">
					<strong>Minhas informações</strong>
				</div>
				<div class="card-body">
					<?php echo $sistema->pageMeuPerfil();?>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-4">
			<div class="card">
				<div class="card-header">
					<strong><a href="#" onclick="$('#card-edit').fadeToggle();">Atualizar informações</a></strong>
				</div>
				<div class="card-body" id="card-edit" style="display:none">
					<form action="#" method="post" onsubmit="salvaPerfil(); return false;">
						<div class="form-group">
							<label><strong>Nome:<span class="text-danger">*</span></strong></label>
							<input type="text" class="form-control" name="nome" id="nome" maxlength="25" placeholder="Nome" value="<?php echo $_SESSION['nome'];?>" required>
						</div>
						<div class="form-group">
							<label><strong>Sobrenome:<span class="text-danger">*</span></strong></label>
							<input type="text" class="form-control" name="sobrenome" id="sobrenome" maxlength="25" placeholder="Sobrenome" value="<?php echo $_SESSION['sobrenome'];?>" required>
						</div>
						<div class="form-group">
							<label><strong>Usuário:<span class="text-danger">*</span></strong></label>
							<input type="text" class="form-control" name="usuario" id="usuario" maxlength="25" placeholder="Usuário" value="<?php echo $_SESSION['usuario'];?>" required disabled>
						</div>
						<h6><strong>Informações de contato</strong><br><small>Irmãos podem entrar em contato com você sobre a hospedagem. Por favor, informe no mínimo um número de telefone e seu e-mail.</small></h6>
						<div class="form-group">
							<label><strong>Telefone Residencial:<span class="text-danger">*</span></strong></label>
							<input type="text" class="form-control" name="tel_res" id="tel_res" maxlength="15" placeholder="Telefone residencial (somente números)" value="<?php echo $_SESSION['tel_res'];?>" pattern="[0-9]{8,}" title="Somente números. Verifique se o número tem 8 digitos ou mais." required onchange="if($(this).val().length >= 8){$('#tel_cel').attr('required',false);} else {$('#tel_cel').attr('required',true);}">
						</div>
						<div class="form-group">
							<label><strong>Telefone Celular:<span class="text-danger">*</span></strong></label>
							<input type="text" class="form-control" name="tel_cel" id="tel_cel" maxlength="15" placeholder="Telefone celular (somente números)" value="<?php echo $_SESSION['tel_cel'];?>" pattern="[0-9]{8,}" title="Somente números. Verifique se o número tem 8 digitos ou mais." required onchange="if($(this).val().length >= 8){$('#tel_res').attr('required',false);} else {$('#tel_res').attr('required',true);}">
						</div>
						<div class="form-group">
							<label><strong>E-mail:<span class="text-danger">*</span></strong></label>
							<input type="email" class="form-control" name="email" id="email" maxlength="35" placeholder="Endereço de e-mail" value="<?php echo $_SESSION['email'];?>" required>
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-save"></span> Salvar</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-4">
			<div class="card">
				<div class="card-header">
					<strong><a href="#" onclick="$('#card-senha').fadeToggle();">Trocar senha</a></strong>
				</div>
				<div class="card-body" id="card-senha" style="display:none">
					<form action="#" method="post" onsubmit="mudaSenha(); return false;">
						<div class="form-group">
							<label><strong>Senha atual:<span class="text-danger">*</span></strong></label>
							<input type="password" class="form-control" name="senha_atual" id="senha_atual" maxlength="16" required>
						</div>
						<div class="form-group">
							<label><strong>Nova senha:<span class="text-danger">*</span></strong></label>
							<input type="password" class="form-control" name="senha1" id="senha1" maxlength="16" required>
						</div>
						<div class="form-group">
							<label><strong>Repita nova senha:<span class="text-danger">*</span></strong></label>
							<input type="password" class="form-control" name="senha2" id="senha2" maxlength="16" required>
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-save"></span> Trocar senha</button>
						</div>
					</form>
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