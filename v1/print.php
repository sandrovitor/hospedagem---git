<?php include('--header.php');?>
<style>

</style>
<script>
var formTipo = '';
var formQtd = '';
var form3 = '';


function repetir() {
	formTipo = '';
	formQtd = '';
	form3 = '';

	$("[id^='sel']").val(0);
	$(".alert").fadeOut();
	$("#div1, #div2, #div5, #div6, #div10").delay('300').fadeOut('fast', function() {$("#div1").delay('300').fadeIn();});
}
function gerarImpressao () {
	$.post(funcPage,{
		funcao: 'print_imprimeForms',
		formtipo: formTipo,
		formqtd: formQtd,
		form3: form3
	}, function(data){
		//$("#div100").html(data);
		var janelaImpressao = window.open("","","width=800,height=500");
		janelaImpressao.document.write();
		janelaImpressao.document.write('<html><head><meta charset="utf-8"><link href="css/bootstrap.min.css" rel="stylesheet"></head><body>');
		janelaImpressao.document.write(data);
		janelaImpressao.document.write('</body></html>');
	}, 'html');
	var count1 = setTimeout(function() {$(".alert").fadeIn('fast');}, 1500);
}


$(document).ready(function() {
	
	$("#sel1").change(function(){
		if($("#sel1").find(":selected").val() == 0) {
			$("#div2_texto, #div5_texto, #div6_texto, #div1_texto1").html('');
			formTipo = '';
		} else {
			$("#div2_texto, #div5_texto, #div6_texto, #div10_texto1").html($("#sel1").find(":selected").text());
			$("#div1").delay('300').fadeOut('fast', function(){$("#div2").fadeIn();});
			formTipo = $("#sel1").find(":selected").val();
		}
	});

	$("#sel2").change(function() {
		var sel2_val = $("#sel2").find(":selected").val();
		formQtd = sel2_val;
		
		switch(sel2_val) {
			case '0':
				break;

			case '1': // Individual
				$.post(funcPage, {
					funcao: 'print_listaForms',
					formtipo: formTipo,
					formqtd: formQtd
				}, function(data){
					$("#sel5").html(data);
					$("#div2").delay('300').fadeOut('fast', function(){$("#div10_texto2").html('<span class="label label-danger">INDIVIDUAL</span>'); $("#div5").fadeIn();});
				}, 'html');
				break;

			case '2': // Cidade Região
				$.post(funcPage, {
					funcao: 'print_listaForms',
					formtipo: formTipo,
					formqtd: formQtd
				}, function(data){
					$("#sel6").html(data);
					$("#div2").delay('300').fadeOut('fast', function(){$("#div10_texto2").html('<span class="label label-danger">CIDADE/REGIÃO</span>'); $("#div6").fadeIn();});
				}, 'html');
				break;

			case '5': // Tudo
				$("#div2").delay('300').fadeOut('fast', function(){$("#div10_texto2").html('<span class="label label-danger">TUDO</span>'); $("#div10").fadeIn();});
				break;
		}
	});

	$("#sel5").change(function() {
		var sel5_val = $("#sel5").find(':selected').val();
		form3 = sel5_val;

		$("#div5").delay('300').fadeOut('fast', function(){$("#div10").fadeIn();});
	});

	$("#sel6").change(function() {
		var sel6_val = $("#sel6").find(':selected').val();
		form3 = sel6_val;

		$("#div6").delay('300').fadeOut('fast', function(){$("#div10").fadeIn();});
	});
});
	
</script>
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
						<li><a href="formulario.php"><span class="glyphicon glyphicon-plus"></span> &nbsp; Novo</a></li>
						<li><a href="formulario.consulta.php"><span class="glyphicon glyphicon-search"></span> &nbsp; Consultar</a></li>
						<?php if($_SESSION['nivel'] >= 10) {?>
						<li class="divider"></li>
						<li><a href="gerenciar.php"><span class="glyphicon glyphicon-link"></span> &nbsp; Gerenciar</a></li>
						<li><a href="atendimento.php"><span class="glyphicon glyphicon-headphones"></span> &nbsp; Atendimento</a></li>
						<?php }?>
						<li class="divider"></li>
						<li class="active"><a href="print.php"><span class="glyphicon glyphicon-print"></span> Imprimir</a></li>
					</ul>
						
				</li>
				<li><a href="ajuda.php"><span class="glyphicon glyphicon-question-sign"></span> Ajuda</a></li>
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
		<li class="active">Imprimir/Exportar</li>
	</ul>
	<!-- FIM DO BREADCRUMB -->
	
	<div class="row">
		<div class="col-xs-10 col-xs-offset-1 col-md-8 col-md-offset-2">
			<form method="post">
				<div class="form-group" id="div1">
					<h3 class="text-center"><strong>O que deseja imprimir?</strong></h3>
					<select name="sel1" id="sel1" class="form-control">
						<option value="0" selected="selected" disabled="disabled">Escolha:</option>
						<option value="1">Pedido de Hospedagem</option>
						<?php if($_SESSION['nivel'] >= 10) {?><option value="2">Formulário de Acomodação</option><?php }?>
					</select>
					
					<div class="well sm-well"><span class="help-block">* Caso a página congele (trave), basta atualizar a página e tentar novamente.<br>
					* Não use botões de <strong>Voltar</strong> ou <strong>Avançar</strong> nesta página.</span></div>
				</div>
				
				<div class="form-group" id="div2" style="display:none">
					<h3 class="text-center"><strong>Certo! Agora me diga, como você deseja o <i><span class="label label-primary" id="div2_texto"></span></i>?</strong></h3>
					<select name="sel2" id="sel2" class="form-control">
						<option value="0">Escolha:</option>
						<option value="1">Individual</option>
						<option value="2">Por cidade/região</option>
						
						<option value="5">TUDO</option>
					</select>
					
					<div class="well sm-well"><span class="help-block">* Caso a página congele (trave), basta atualizar a página e tentar novamente.<br>
					* Não use botões de <strong>Voltar</strong> ou <strong>Avançar</strong> nesta página.</span></div>
				</div>
				
				<div class="form-group" id="div5" style="display:none">
					<h3 class="text-center"><strong>Muito bem! Falta só mais um coisa...<br><br> <span class="glyphicon glyphicon-arrow-down hidden-sm hidden-xs"></span> Escolha qual <i><span class="label label-primary" id="div5_texto"></span></i> <span style="color: #b30000">individual</span> você quer... </strong> <span class="glyphicon glyphicon-arrow-down"></span></h3>
					<select name="sel5" id="sel5" class="form-control">
					
					</select>
					
					<div class="well sm-well"><span class="help-block">* Caso a página congele (trave), basta atualizar a página e tentar novamente.<br>
					* Não use botões de <strong>Voltar</strong> ou <strong>Avançar</strong> nesta página.</span></div>
				</div>
				
				<div class="form-group" id="div6" style="display:none">
					<h3 class="text-center"><strong>Muito bem! Falta só mais um coisa...<br><br> <span class="glyphicon glyphicon-arrow-down hidden-sm hidden-xs"></span> Qual <span style="color: #b30000">cidade/região</span> do <i><span class="label label-primary" id="div6_texto"></span></i> você quer... </strong> <span class="glyphicon glyphicon-arrow-down"></span></h3>
					<select name="sel6" id="sel6" class="form-control">
					
					</select>
					
					<div class="well sm-well"><span class="help-block">* Caso a página congele (trave), basta atualizar a página e tentar novamente.<br>
					* Não use botões de <strong>Voltar</strong> ou <strong>Avançar</strong> nesta página.</span></div>
				</div>
				
				<div class="form-group" id="div10" style="display:none">
					<h4 class="text-center">Obrigado, já entendi o que você precisa!</h4><br>
					<h3 class="text-center">
						<small><strong>Você pediu >>> &nbsp;[<span class="label label-primary" id="div10_texto1"></span> <span style="color: #b30000" id="div10_texto2"></span>]</strong></small><br>
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
		</div><!-- ./COL -->
	</div><!-- ./ROW -->
</div>
<!-- FIM CONTEUDO -->
<?php include('--footer.php');?>