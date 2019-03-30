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
<style>
</style>

</head>
<body>

<div class="container-fluid">
<!-- CONTEUDO -->
	
	<div class="row">
		<div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2">
			<h4 class="text-center"><strong>MINHA HOSPEDAGEM</strong></h4><hr>
		</div>
	</div>
	
	<div class="row">
		<div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2">
			<?php echo $sistema->getAtendimentoExt();?>
		</div>
	</div>

</div>
<!--  FIM CONTEÚDO -->


<?php include('footer.php');?>