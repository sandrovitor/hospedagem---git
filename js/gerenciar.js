var funcGerencia = 'app/gerenciar_func.php';

function gPehSelect() {
	if($("#peh_select").find(":selected").val() > 0) {
		$.post(funcPage, {
			funcao: 'pehConsulta',
			id: $("#peh_select").find(":selected").val(),
			links: 0
		},function(data){
			$('[data-toggle="tooltip"]').tooltip('disable');
			$('[data-toggle="popover"]').popover('disable');
			
			$("#panel3").html(data);
			
			// Verifica se o PANEL5 existe.
			if($("#panel5").length != 0) { // SE EXISTIR
				btnAction();
			}
			
			$('[data-toggle="tooltip"]').tooltip();
			$('[data-toggle="popover"]').popover({html:true});
		});
		
	} else {
		$("#panel3").html('');
		btnAction();
	}
}

function gFacSelect() {
	if($("#fac_select").find(":selected").val() > 0) {
		$.post(funcPage,{
			funcao: 'facConsulta',
			id: $("#fac_select").find(":selected").val(),
			links: 0
		}, function(data){
			$('[data-toggle="tooltip"]').tooltip('disable');
			$('[data-toggle="popover"]').popover('disable');
			
			
			$("#panel4").html(data); // Se existir, exibe no panel4
			
			// Verifica se o PANEL5 existe.
			if($("#panel5").length != 0) { // SE EXISTIR
				btnAction();
			}
			
			
			$('[data-toggle="tooltip"]').tooltip();
			$('[data-toggle="popover"]').popover({html:true});
		});
	} else {
		$("#panel4").html('');
		btnAction();
	}
}

function carListaPeh(op_selected = '') {
	$.post(funcGerencia, {
		act: 'carListaPeh'
	}, function(data){
		$("#peh_select").find('option').remove().end().html(data);
		if(op_selected != '') {
			$('#peh_select').val(op_selected);
		}
	});
}

function carListaFac(op_selected = '') {
	$.post(funcGerencia, {
		act: 'carListaFac'
	}, function(data){
		$("#fac_select").find('option').remove().end().html(data);
		if(op_selected != '') {
			$('#fac_select').val(op_selected);
		}
	});
}

function btnAction() { // CONTROLAR OS BOTÕES DE AÇÃO
	// ESCONDE TODOS BOTÕES
	$("#panel5 [id^='btn_act']").hide(); $("#panel5").hide();
	
	
	
	// Verifica se ambos formulários foram selecionados
	if($("#peh_select").find(":selected").val() > 0 && $("#fac_select").find(":selected").val() > 0) { // AMBOS SELECIONADOS
		// Verifica os vínculos
		if($("#peh_select").find(":selected").data("fac") == 0 && $('#fac_select').find(':selected').text().indexOf('[OK!]') == -1) { // PEH E FAC SEM VINCULO
			$("#btn_act_vincular").show();
		} else if($("#peh_select").find(":selected").data("fac") == 0 && $('#fac_select').find(':selected').text().indexOf('[OK!]') >= 0) { // PEH SEM VINCULO; FAC COM VINCULO
			$("#btn_act_desv2").show();
		} else if($("#peh_select").find(":selected").data("fac") > 0 && $('#fac_select').find(':selected').text().indexOf('[OK!]') == -1) { // PEH COM VINCULO; FAC SEM VINCULO
			$("#btn_act_desv1").show();
		} else if($("#peh_select").find(":selected").data("fac") == $('#fac_select').find(':selected').val()) { // PEH E FAC COM VINCULO IGUAL
			$("#btn_act_desvincular").show();
		} else { // PEH E FAC COM VINCULO DIFERENTE
			$("#btn_act_desv1, #btn_act_desv2").show();
		}
		
		$("#panel5").fadeIn('fast');
	} else if($("#peh_select").find(":selected").val() == 0 && $("#fac_select").find(":selected").val() == 0) { // AMBOS NÃO SELECIONADOS
		
	}
	
	
	if($("#peh_select").find(":selected").val() > 0) { // Só se o PEH estiver selecionado
		// Verifica se o pedido de hospedagem precisa de revisão
		if($('#peh_select').find(":selected").data('revisar') == 0) {
			$('#btn_act_revisar_peh').attr('disabled', false).show();
		} else {
			$('#btn_act_revisar_peh').attr('disabled', true).show();
		}
	} else if($("#peh_select").find(":selected").val() == 0) {
		$('#btn_act_revisar_peh').attr('disabled', true).hide();
	}
	
	if($("#fac_select").find(":selected").val() > 0) { // Só se o FAC estiver selecionado
		// Verifica se o formulário de acomodação precisa de revisão
		if($('#fac_select').find(":selected").data('revisar') == 0) {
			$('#btn_act_revisar_fac').attr('disabled', false).show();
		} else {
			$('#btn_act_revisar_fac').attr('disabled', true).show();
		}
	} else if($("#fac_select").find(":selected").val() == 0) {
		$('#btn_act_revisar_fac').attr('disabled', true).hide();
	}
	
	setTimeout(function(){$('[data-toggle="tooltip"]').tooltip();}, 400);
}

function msgDialogo(tit, msg_1, tipo){
	/*	TITULO: Titulo do Modal
	 * 	MENSAGEM: Conteúdo do alert no MODAL.
	 * 	TIPO: Tipo de mensagem. 'sucesso', 'alerta', 'perigo', 'info'
	 * 
	*/
	
	// Checa parametros
	if(arguments[0] == null || arguments[0] == undefined || arguments[0] == '') { // TITULO
		tit = '<strong><span class="glyphicon glyphicon-bell"></span> NOTIFICAÇÃO DO SISTEMA</strong>';
	} else {tit = arguments[0];}
	
	if(arguments[1] == null || arguments[1] == undefined || arguments[1] == '') { // MENSAGEM
		msg_1 = '<strong>NADA PRA MOSTRAR AQUI!</strong>';
	} else {mensagem = arguments[1];}
	if(arguments[2] == null || arguments[2] == undefined || arguments[2] == '') { // TIPO
		tipo = 'info';
	} else {tipo = arguments[2];}
	
	$("#modal1_titulo").html(tit);
	
	if(tipo == 'sucesso') {
		$("#modal1_body").html('<div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span> '+msg_1+'</div>');
	} else if(tipo == 'alerta') {
		$("#modal1_body").html('<div class="alert alert-warning"><span class="glyphicon glyphicon-exclamation-sign"></span> '+msg_1+'</div>');
	} else if(tipo == 'perigo') {
		$("#modal1_body").html('<div class="alert alert-danger"><span class="glyphicon glyphicon-remove"></span> '+msg_1+'</div>');
	} else if(tipo == 'info') {
		$("#modal1_body").html('<div class="alert alert-info"><span class="glyphicon glyphicon-info-sign"></span> '+msg_1+'</div>');
	} else {
		$("#modal1_body").html('<div class="alert alert-info"><span class="glyphicon glyphicon-info-sign"></span> '+msg_1+'</div>');
	}
	
	$("#modal1").modal();
}

function vincularForms() { // Vincula PEH e FAC
	var pehid = $("#peh_select").find(":selected").val();
	var facid = $("#fac_select").find(":selected").val();
	
	if(pehid != 0 && facid != 0) {
		$.post(funcGerencia,{
			act: 'vincularForms',
			pehid: pehid,
			facid: facid
		}, function(data){
			if(data == 'success') {
				var titulo = '';
				var msg = '<strong>OPERAÇÃO REALIZADA COM SUCESSO</strong><br><br> <span class="label label-primary" style="font-size: 14px"><span class="glyphicon glyphicon-calendar"></span> Pedido de Hospedagem nº <strong>'+pehid+'</strong></span> <strong><i>vinculado</i></strong> com a <span class="label label-info" style="font-size: 14px"><span class="glyphicon glyphicon-lamp"></span> Acomodação nº <strong>'+facid+'</strong></span>.';
				var tipo = 'sucesso';
				msgDialogo(titulo, msg, tipo);
			} else {
				var titulo = '';
				var msg = '<strong>OPERAÇÃO RETORNOU ERRO</strong><br> A seguinte mensagem retornou do sistema: <br><br><strong><i>"'+data+'"</i></strong><br><br> Atualize a página e tente de novo, ou contate administrador.';
				var tipo = 'perigo';
				msgDialogo(titulo, msg, tipo);
			}
			
			// Atualiza lista de pedidos e acomodações;
			carListaPeh(pehid);
			carListaFac(facid);
			gPehSelect();
			gFacSelect();
			
			// Atualiza botões
			btnAction();
		});
	}
}



function desvincularForms() { // Desvincula PEH e FAC
	var pehid = $("#peh_select").find(":selected").val();
	var facid = $("#fac_select").find(":selected").val();
	
	if(pehid != 0 && facid != 0) {
		$.post(funcGerencia,{
			act: 'desvincularForms',
			pehid: pehid,
			facid: facid
		}, function(data){
			if(data == 'success') {
				var titulo = '';
				var msg = '<strong>OPERAÇÃO REALIZADA COM SUCESSO</strong><br><br> <span class="label label-primary" style="font-size: 14px"><span class="glyphicon glyphicon-calendar"></span> Pedido de Hospedagem nº <strong>'+pehid+'</strong></span> <strong><i>desvinculado</i></strong> da <span class="label label-info" style="font-size: 14px"><span class="glyphicon glyphicon-lamp"></span> Acomodação nº <strong>'+facid+'</strong>.</span>';
				var tipo = 'sucesso';
				msgDialogo(titulo, msg, tipo);
			} else {
				var titulo = '';
				var msg = '<strong>OPERAÇÃO RETORNOU ERRO</strong><br> A seguinte mensagem retornou do sistema: <br><br><strong><i>"'+data+'"</i></strong><br><br> Atualize a página e tente de novo, ou contate administrador.';
				var tipo = 'perigo';
				msgDialogo(titulo, msg, tipo);
			}
			
			// Atualiza lista de pedidos e acomodações;
			carListaPeh(pehid);
			carListaFac(facid);
			gPehSelect();
			gFacSelect();
			
			// Atualiza botões
			btnAction();
		});
	}
	
}

function desvAction(tipo, id) {
	
	if(tipo == 'peh' || tipo == 'fac') {
		if(tipo == 'peh') {
			var msg2 = '<br><br> <span class="label label-primary" style="font-size: 14px"><span class="glyphicon glyphicon-calendar"></span> Pedido de Hospedagem nº <strong>'+id+'</strong></span> foi liberado da acomodação...';
		} else if(tipo == 'fac') {
			var msg2 = '<br><br> <span class="label label-primary" style="font-size: 14px"><span class="glyphicon glyphicon-lamp"></span> Acomodação nº <strong>'+id+'</strong></span> foi liberada do pedido de hospedagem...';
		}
		
		$.post(funcGerencia, {
			act: 'desvAction',
			tipo: tipo,
			id: id
		}, function(data){
			if(data == 'success') {
				var titulo = '';
				var msg = '<strong>OPERAÇÃO REALIZADA COM SUCESSO</strong> '+msg2;
				var tipo = 'sucesso';
				msgDialogo(titulo, msg, tipo);
				
			} else {
				var titulo = '';
				var msg = '<strong>OPERAÇÃO RETORNOU ERRO</strong><br> A seguinte mensagem retornou do sistema: <br><br><strong><i>"'+data+'"</i></strong><br><br> Atualize a página e tente de novo, ou contate administrador.';
				var tipo = 'perigo';
				msgDialogo(titulo, msg, tipo);
			}
			
			// Atualiza lista de pedidos e acomodações;
			carListaPeh();
			carListaFac();
			gPehSelect();
			gFacSelect();
			
			// Atualiza botões
			btnAction();
		});
		
	}
}

function gRevisarForms(tipo, id, token) {
	var pehid = $("#peh_select").find(":selected").val();
	var facid = $("#fac_select").find(":selected").val();
	
	if(tipo == 'peh') {
		$.post(funcGerencia,{
			act: 'gRevisarForms',
			tipo: tipo,
			id: id,
			token: token
		},function(data){
			if(data == 'success') {
				var titulo = '';
				var msg2 = '<br><br> <span class="label label-primary" style="font-size: 14px"><span class="glyphicon glyphicon-calendar"></span> Pedido de Hospedagem nº <strong>'+id+'</strong></span> foi adicionado à lista de revisão.';
				var msg = '<strong>OPERAÇÃO REALIZADA COM SUCESSO</strong> '+msg2;
				var tipo = 'sucesso';
				msgDialogo(titulo, msg, tipo);
			} else {
				var titulo = '';
				var msg = '<strong>OPERAÇÃO RETORNOU ERRO</strong><br> A seguinte mensagem retornou do sistema: <br><br><strong><i>"'+data+'"</i></strong><br><br> Atualize a página e tente de novo, ou contate administrador.';
				var tipo = 'perigo';
				msgDialogo(titulo, msg, tipo);
			}
			
			// Remove PEH do panel 3.
			$('#panel3').html('');
			
			// Atualiza lista de pedidos e acomodações;
			carListaPeh();
			carListaFac(facid);
			gPehSelect();
			gFacSelect();
			
			// Atualiza botões
			btnAction();
		});
	} else if(tipo == 'fac') {
		$.post(funcGerencia,{
			act: 'gRevisarForms',
			tipo: tipo,
			id: id,
			token: token
		},function(data){
			if(data == 'success') {
				var titulo = '';
				var msg2 = '<br><br> <span class="label label-primary" style="font-size: 14px"><span class="glyphicon glyphicon-calendar"></span> Formulário de Acomodação nº <strong>'+id+'</strong></span> foi adicionado à lista de revisão.';
				var msg = '<strong>OPERAÇÃO REALIZADA COM SUCESSO</strong> '+msg2;
				var tipo = 'sucesso';
				msgDialogo(titulo, msg, tipo);
			} else {
				var titulo = '';
				var msg = '<strong>OPERAÇÃO RETORNOU ERRO</strong><br> A seguinte mensagem retornou do sistema: <br><br><strong><i>"'+data+'"</i></strong><br><br> Atualize a página e tente de novo, ou contate administrador.';
				var tipo = 'perigo';
				msgDialogo(titulo, msg, tipo);
			}
			
			// Remove FAC do panel 4.
			$('#panel4').html('');
			
			// Atualiza lista de pedidos e acomodações;
			carListaPeh(pehid);
			carListaFac();
			gPehSelect();
			gFacSelect();
			
			// Atualiza botões
			btnAction();
		});
	}
}


