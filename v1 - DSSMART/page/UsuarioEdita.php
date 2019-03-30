<?php 

?>
<script>
function usuCarrega(id) {
	$.post(funcPage,{
		funcao: 'adm_usuCarrega',
		id: id
	},function(data){
		$('#page_carrega').html(data);
	},'html');
}

function msgRetornoApaga() {
	$('#msg_retorno').html('').fadeOut('fast');
}

function usuSalva() {
	var reset_senha = '';
	var reset_tentativas = '';
	if($('#form_usu [name="reset_senha"]').is(':checked') == true) {
		reset_senha = 'Y';
	} else {
		reset_senha = 'N';
	}
	if($('#form_usu [name="reset_tentativas"]').is(':checked') == true) {
		reset_tentativas = 'Y';
	} else {
		reset_tentativas = 'N';
	}
	
	
	$.post(funcPage, {
		funcao: 'adm_usuSalva',
		id: $('#form_usu [name="usu_id"]').val(),
		nome: $('#form_usu [name="nome"]').val(),
		sobrenome: $('#form_usu [name="sobrenome"]').val(),
		usuario: $('#form_usu [name="usuario"]').val(),
		nivel: $('#form_usu [name="nivel"]').val(),
		tel_res: $('#form_usu [name="tel_res"]').val(),
		tel_cel: $('#form_usu [name="tel_cel"]').val(),
		email: $('#form_usu [name="email"]').val(),
		reset_senha: reset_senha,
		reset_tentativas: reset_tentativas
	},function(data){
		$('#msg_retorno').html(data);
		$('#msg_retorno').fadeIn('slow').delay('6000').fadeOut('fast');
		usuCarrega($('#usuario_select').val());
	}, 'html');
}
</script>


<h4><strong><span class="glyphicon glyphicon-edit"></span> EDITAR USUÁRIO</strong></h4><hr>


<div class="row">
	<div class="col-xs-12">
		<div class="well sm-well">
			<form class="form-inline">
				<div class="form-group">
					<label for="usuario_select">Usuário: </label>
					<select id="usuario_select" class="form-control" onchange="usuCarrega($(this).val()); msgRetornoApaga()">
						<option value="0">Escolha:</option>
						<?php 
						$abc = $pdo->query('SELECT login.id, login.nome, login.sobrenome FROM login WHERE 1 ORDER BY nome ASC, sobrenome ASC');
						while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
							echo <<<DADOS

						<option value="$reg->id">$reg->nome $reg->sobrenome</option>

DADOS;
						}
						?>
					</select>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="row"><div class="col-xs-12"><div id="msg_retorno" style="display:none"></div></div></div>

<div class="row">
	<div class="col-xs-12" id="page_carrega">
		
		
		
	</div>
</div>