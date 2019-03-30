<script>
/* CIDADE COM HOSPEDAGEM */
function formRespHosp(codigo) {
	$("#div"+codigo).hide();
	$("#form"+codigo).fadeToggle();
	var spanText = $("#div"+codigo).find('span').text();
	var valorSel = $("#form"+codigo+" select").find('option:contains("'+spanText+'")').val();
	if(valorSel != null) {
		$("#form"+codigo+" select").val(valorSel);
	} 
}
function formRespHospCancel(codigo) {
	$("#form"+codigo).hide();
	$("#div"+codigo).fadeToggle();
}
function formRespHospSalva(codigo) {
	$.post("app/designacao_resp_hosp.php", {
		funcao: 'SalvaResponsavel',
		id: codigo,
		resp_id: $("#form"+codigo+" select").val()
	}, function(data){
		$("#Panel1_body").html(data);
	});
}
/* FIM CIDADE COM HOSPEDAGEM */ 

/* CIDADE VISITANTE */
function formSolHosp(tipo, codigo) {
	if(tipo == 'sol') {
		$("#div_sol"+codigo).hide();
		$("#form_sol"+codigo).fadeToggle();
		var spanText = $("#div_sol"+codigo).find('span').text();
		var valorSel = $("#form_sol"+codigo+" select").find('option:contains("'+spanText+'")').val();
		if(valorSel != null) {
			$("#form_sol"+codigo+" select").val(valorSel);
		} 
	} else if(tipo == 'resp') {
		$("#div_resp"+codigo).hide();
		$("#form_resp"+codigo).fadeToggle();
		var spanText = $("#div_resp"+codigo).find('span').text();
		var valorSel = $("#form_resp"+codigo+" select").find('option:contains("'+spanText+'")').val();
		if(valorSel != null) {
			$("#form_resp"+codigo+" select").val(valorSel);
		} 
	}
}

function formSolHospCancel(tipo, codigo) {
	if(tipo == 'sol') {
		$("#form_sol"+codigo).hide();
		$("#div_sol"+codigo).fadeToggle();
	} else if(tipo == 'resp') {
		$("#form_resp"+codigo).hide();
		$("#div_resp"+codigo).fadeToggle();
	}
}

function formSolHospSalva(tipo, codigo) {
	if(tipo == 'sol') { // Alterar Solicitante
		$.post("app/designacao_sol_resp_hosp.php", {
			funcao: 'SalvaSolicitante',
			id: codigo,
			sol_id: $("#form_sol"+codigo+" select").val()
		}, function(data){
			$("#Panel2_body").html(data);
		});
		
	} else if(tipo == 'resp') { // Alterar responsável por hospedar
		$.post("app/designacao_sol_resp_hosp.php", {
			funcao: 'SalvaResponsavel',
			id: codigo,
			resp_id: $("#form_resp"+codigo+" select").val()
		}, function(data){
			$("#Panel2_body").html(data);
		});
	}
}
/* FIM CIDADE VISITANTE */

</script>

<h4><strong><span class="glyphicon glyphicon-check"></span> DESIGNAÇÕES</strong></h4><hr>

<div class="row">
	<div class="col-md-6">
		<div class="panel panel-primary">
			<div class="panel-heading text-center">
				<h4><strong>CIDADES COM HOSPEDAGEM</strong></h4>
			</div>
			<div id="Panel1" class="panel-collapse collapse in">
				<div class="panel-body" id="Panel1_body">
					<?php include('app/designacao_resp_hosp.php');?>
				</div>
			</div>
		</div>
		
	</div>
	<div class="col-md-6">
		<div class="panel panel-primary">
			<div class="panel-heading text-center">
				<h4><strong>CIDADES VISITANTES</strong></h4>
			</div>
			<div id="Panel2" class="panel-collapse collapse in">
				<div class="panel-body" id="Panel2_body">
					<?php include('app/designacao_sol_resp_hosp.php');?>
				</div>
			</div>
		</div>
	</div>
</div>