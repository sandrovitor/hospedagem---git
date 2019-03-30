$(document).ajaxStart(function(){
	$("#overlay").fadeIn('fast');
	$('[data-toggle="tooltip"]').tooltip('disable');
	$('[data-toggle="popover"]').popover('disable');
});
$(document).ajaxStop(function(){
	$("#overlay").delay(100).fadeOut('fast');
	setTimeout(function(){$('[data-toggle="tooltip"]').tooltip(); $('[data-toggle="popover"]').popover({html:true});}, 700);
});


var funcPage = 'app/functions.php';
var admFunc = 'app/adm_functions.php';
var codQuart = 2;


/*

function formularioCarregaCons(form_tipo, id) {
	
	$.post(funcPage, {
		funcao: 'formularioCarregaCons',
		id: id,
		form: form_tipo
	}, function(data){
		$("#panel3").html(data);
	})
}

*/



function apagaPEH(id, token) {
	var c = confirm('Excluir esse Pedido de Hospedagem?');
	if(c == true) {
		$.post(funcPage, {
			funcao: 'apagarForms',
			tipo: 'PEH',
			id: id,
			token: token
		}, function(data){
			if(data == 'success') {
				alert('Pedido excluído com sucesso. Clique em [OK] para atualizar a página.');
				location.reload();
			} else {
				alert(data);
			}
		});
	} else {
		alert('Exclusão cancelada!');
	}
}

function apagaFAC(id, token) {
	var c = confirm('Excluir essa Acomodação?');
	if(c == true) {
		$.post(funcPage, {
			funcao: 'apagarForms',
			tipo: 'FAC',
			id: id,
			token: token
		}, function(data){
			if(data == 'success') {
				alert('Acomodação excluída com sucesso. Clique em [OK] para atualizar a página.');
				location.reload();
			} else {
				alert(data);
			}
		});
	} else {
		alert('Exclusão cancelada!');
	}
}

function admReseta() {
	var arr = '';
	if($('#funcao1').prop('checked') == true) {arr += 'DESV;';}
	if($('#funcao2').prop('checked') == true) {arr += 'PEH_REV;';}
	if($('#funcao3').prop('checked') == true) {arr += 'FAC_REV;';}
	if($('#funcao4').prop('checked') == true) {arr += 'DATA_DEL;';}
	if($('#funcao5').prop('checked') == true) {arr += 'CIDADE_DEL;';}
	
	$.post(funcPage,{
		funcao: 'adm_reseta',
		arr: arr
	},function(data){
		$('#mensagem_retorno').html(data);
	},'html')
}
/*
 *  NOVAS FUNÇÕES
 */

function MostraAlerta(titulo, texto, tipo = 'info', botao_fechar = true) {
	// Formata itens individuais
	var alerta_titulo, alerta_tipo, alerta_fechar, alerta_fechar_class;
	if(titulo == '') { // Sem titulo
		alerta_titulo = '';
	} else { // Com titulo
		alerta_titulo = '<strong>'+titulo+'</strong> ';
	}
	
	switch(tipo) {
		case 'success':
			alerta_tipo = 'success';
			break;
			
		case 'danger':
			alerta_tipo = 'danger';
			break;
			
		case 'warning':
			alerta_tipo = 'warning';
			break;
			
		case 'primary':
			alerta_tipo = 'primary';
			break;
			
		case 'secondary':
			alerta_tipo = 'secondary';
			break;
			
		case 'light':
			alerta_tipo = 'light';
			break;
			
		case 'dark':
			alerta_tipo = 'dark';
			break;
			
		case 'info':
		default:
			alerta_tipo = 'info';
			break;
	}
	
	if(botao_fechar == false || botao_fechar == 0 || botao_fechar == '0' || botao_fechar == 'false') {
		alerta_fechar = '';
		alerta_fechar_class = '';
	} else {
		alerta_fechar = '<button type="button" class="close" data-dismiss="alert">&times;</button>';
		alerta_fechar_class = 'alert-dismissible fade show';
	}
	
	var alerta = '<div class="alert alert-'+alerta_tipo+' '+alerta_fechar_class+'">'+alerta_fechar+alerta_titulo+texto+'</div>';

	$('#frame_msg').append(alerta);
	return true;
}

function mostraQuarto() {
	if(codQuart <= 4) {
		if($("#quarto"+codQuart).css("display") == 'none') {
			$("#quarto"+codQuart).fadeIn();
			$("input[name='quarto"+codQuart+"'").val('yes');
		}
		codQuart++;
	} else { // Se a contagem passar de 4, mostra alerta.
		alert('Limite máximo de quartos atingido: '+ (codQuart-1));
	}
}

function LoadNewForm(tipo) {
	$.get(funcPage,{
		funcao: 'NovoFormulario',
		tipo: tipo
	},function(data){
		$('#formulario_div').html(data);
		codQuart = 2; // Zera quantidade do quarto do FAC
		marcaRequired(); // Marca os itens obrigatórios
	});
}

function pehNovo() {
	$.post(funcPage,{
		funcao: 'PEHNovo',
		nome: $('#nome').val(),
		endereco: $('#endereco').val(),
		cidade: $('#cidade').val(),
		estado: $('#estado').find(':selected').val(),
		tel_res: $('#tel_res').val(),
		tel_cel: $('#tel_cel').val(),
		email: $('#email').val(),
		congregacao: $('#congregacao').val(),
		congregacao_cidade_id: $('#congregacao_cidade_id').val(),
		congresso_cidade: $('#congresso_cidade').val(),
		check_in: $('#check_in').val(),
		check_out: $('#check_out').val(),
		tipo_hospedagem: $('#tipo_hospedagem').val(),
		pagamento: $('#pagamento').val(),
		transporte: $('#transporte').val(),
		oc1_nome: $('#oc1_nome').val(),
		oc1_idade: $('#oc1_idade').val(),
		oc1_sexo: $('#oc1_sexo').val(),
		oc1_parente: $('#oc1_parente').val(),
		oc1_etnia: $('#oc1_etnia').val(),
		oc1_privilegio: $('#oc1_privilegio').val(),
		
		oc2_nome: $('#oc2_nome').val(),
		oc2_idade: $('#oc2_idade').val(),
		oc2_sexo: $('#oc2_sexo').val(),
		oc2_parente: $('#oc2_parente').val(),
		oc2_etnia: $('#oc2_etnia').val(),
		oc2_privilegio: $('#oc2_privilegio').val(),
		
		oc3_nome: $('#oc3_nome').val(),
		oc3_idade: $('#oc3_idade').val(),
		oc3_sexo: $('#oc3_sexo').val(),
		oc3_parente: $('#oc3_parente').val(),
		oc3_etnia: $('#oc3_etnia').val(),
		oc3_privilegio: $('#oc3_privilegio').val(),
		
		oc4_nome: $('#oc4_nome').val(),
		oc4_idade: $('#oc4_idade').val(),
		oc4_sexo: $('#oc4_sexo').val(),
		oc4_parente: $('#oc4_parente').val(),
		oc4_etnia: $('#oc4_etnia').val(),
		oc4_privilegio: $('#oc4_privilegio').val(),
		
		motivo: $('#motivo').val(),
		secretario_nome: $('#secretario_nome').val(),
		secretario_tel: $('#secretario_tel').val(),
		secretario_email: $('#secretario_email').val(),
	},function(data){
		if(data == 'success') {
			MostraAlerta('Salvo!', 'Seu Pedido Especial de Hospedagem (PEH) foi salvo com sucesso! Tudo pronto para o próximo...', 'success');
			$(document).scrollTop(0);
			LoadNewForm('PEH');
		} else {
			MostraAlerta('Ops!', data, 'warning');
			$(document).scrollTop(0);
		}
	},'html');
}

function pehEdit() {
	$.post(funcPage,{
		funcao: 'PEHEdit',
		pehid: $('#pehid').val(),
		nome: $('#nome').val(),
		endereco: $('#endereco').val(),
		cidade: $('#cidade').val(),
		estado: $('#estado').find(':selected').val(),
		tel_res: $('#tel_res').val(),
		tel_cel: $('#tel_cel').val(),
		email: $('#email').val(),
		congregacao: $('#congregacao').val(),
		congregacao_cidade_id: $('#congregacao_cidade_id').val(),
		congresso_cidade: $('#congresso_cidade').val(),
		check_in: $('#check_in').val(),
		check_out: $('#check_out').val(),
		tipo_hospedagem: $('#tipo_hospedagem').val(),
		pagamento: $('#pagamento').val(),
		transporte: $('#transporte').val(),
		oc1_nome: $('#oc1_nome').val(),
		oc1_idade: $('#oc1_idade').val(),
		oc1_sexo: $('#oc1_sexo').val(),
		oc1_parente: $('#oc1_parente').val(),
		oc1_etnia: $('#oc1_etnia').val(),
		oc1_privilegio: $('#oc1_privilegio').val(),
		
		oc2_nome: $('#oc2_nome').val(),
		oc2_idade: $('#oc2_idade').val(),
		oc2_sexo: $('#oc2_sexo').val(),
		oc2_parente: $('#oc2_parente').val(),
		oc2_etnia: $('#oc2_etnia').val(),
		oc2_privilegio: $('#oc2_privilegio').val(),
		
		oc3_nome: $('#oc3_nome').val(),
		oc3_idade: $('#oc3_idade').val(),
		oc3_sexo: $('#oc3_sexo').val(),
		oc3_parente: $('#oc3_parente').val(),
		oc3_etnia: $('#oc3_etnia').val(),
		oc3_privilegio: $('#oc3_privilegio').val(),
		
		oc4_nome: $('#oc4_nome').val(),
		oc4_idade: $('#oc4_idade').val(),
		oc4_sexo: $('#oc4_sexo').val(),
		oc4_parente: $('#oc4_parente').val(),
		oc4_etnia: $('#oc4_etnia').val(),
		oc4_privilegio: $('#oc4_privilegio').val(),
		
		motivo: $('#motivo').val(),
		secretario_nome: $('#secretario_nome').val(),
		secretario_tel: $('#secretario_tel').val(),
		secretario_email: $('#secretario_email').val(),
	},function(data){
		if(data == 'success') {
			alert('Alterações salvas. Fechando página...');
			window.close();
		} else {
			MostraAlerta('Ops!', data, 'warning');
		}
	},'html');
}

function facNovo() {
	$.post(funcPage,{
		funcao: 'FACNovo',
		quarto1_sol_qtd: $('#quarto1_sol_qtd').val(),
		quarto1_cas_qtd: $('#quarto1_cas_qtd').val(),
		quarto1_valor1: $('#quarto1_valor1').val(),
		quarto1_valor2: $('#quarto1_valor2').val(),
		
		quarto2_sol_qtd: $('#quarto2_sol_qtd').val(),
		quarto2_cas_qtd: $('#quarto2_cas_qtd').val(),
		quarto2_valor1: $('#quarto2_valor1').val(),
		quarto2_valor2: $('#quarto2_valor2').val(),
		
		quarto3_sol_qtd: $('#quarto3_sol_qtd').val(),
		quarto3_cas_qtd: $('#quarto3_cas_qtd').val(),
		quarto3_valor1: $('#quarto3_valor1').val(),
		quarto3_valor2: $('#quarto3_valor2').val(),
		
		quarto4_sol_qtd: $('#quarto4_sol_qtd').val(),
		quarto4_cas_qtd: $('#quarto4_cas_qtd').val(),
		quarto4_valor1: $('#quarto4_valor1').val(),
		quarto4_valor2: $('#quarto4_valor2').val(),
		
		dias: $('#dias_individuais').val().split(','),
		andar: $('#andar').val(),
		transporte: $('#transporte').val(),
		casa_tj: $('#casa_tj').val(),
		obs1: $('#obs1').val(),
		nome: $('#nome').val(),
		endereco: $('#endereco').val(),
		telefone: $('#telefone').val(),
		cidade: $('#cidade').val(),
		publicador_nome: $('#publicador_nome').val(),
		publicador_tel: $('#publicador_tel').val(),
		cong_nome: $('#cong_nome').val(),
		cong_cidade: $('#cong_cidade').val(),
		cong_sec: $('#cong_sec').val(),
		cong_tel: $('#cong_tel').val(),
		obs2: $('#obs2').val(),
		condicao: $('#condicao').val(),
		quarto1: $('#quarto1_ocupado').val(),
		quarto2: $('#quarto2_ocupado').val(),
		quarto3: $('#quarto3_ocupado').val(),
		quarto4: $('#quarto4_ocupado').val()
	},function(data){
		if(data == 'success') {
			MostraAlerta('Salvo!', 'Seu Formulário de Acomodação (FAC) foi salvo com sucesso! Tudo pronto para o próximo...', 'success');
			$(document).scrollTop(0);
			LoadNewForm('FAC');
		} else {
			MostraAlerta('Ops!', data, 'warning');
			$(document).scrollTop(0);
		}
	},'html');
}

function facEdit(){
	$.post(funcPage,{
		funcao: 'FACEdit',
		facid: $('#facid').val(),
		quarto1_sol_qtd: $('#quarto1_sol_qtd').val(),
		quarto1_cas_qtd: $('#quarto1_cas_qtd').val(),
		quarto1_valor1: $('#quarto1_valor1').val(),
		quarto1_valor2: $('#quarto1_valor2').val(),
		
		quarto2_sol_qtd: $('#quarto2_sol_qtd').val(),
		quarto2_cas_qtd: $('#quarto2_cas_qtd').val(),
		quarto2_valor1: $('#quarto2_valor1').val(),
		quarto2_valor2: $('#quarto2_valor2').val(),
		
		quarto3_sol_qtd: $('#quarto3_sol_qtd').val(),
		quarto3_cas_qtd: $('#quarto3_cas_qtd').val(),
		quarto3_valor1: $('#quarto3_valor1').val(),
		quarto3_valor2: $('#quarto3_valor2').val(),
		
		quarto4_sol_qtd: $('#quarto4_sol_qtd').val(),
		quarto4_cas_qtd: $('#quarto4_cas_qtd').val(),
		quarto4_valor1: $('#quarto4_valor1').val(),
		quarto4_valor2: $('#quarto4_valor2').val(),
		
		dias: $('#dias_individuais').val().split(','),
		andar: $('#andar').val(),
		transporte: $('#transporte').val(),
		casa_tj: $('#casa_tj').val(),
		obs1: $('#obs1').val(),
		nome: $('#nome').val(),
		endereco: $('#endereco').val(),
		telefone: $('#telefone').val(),
		cidade: $('#cidade').val(),
		publicador_nome: $('#publicador_nome').val(),
		publicador_tel: $('#publicador_tel').val(),
		cong_nome: $('#cong_nome').val(),
		cong_cidade: $('#cong_cidade').val(),
		cong_sec: $('#cong_sec').val(),
		cong_tel: $('#cong_tel').val(),
		obs2: $('#obs2').val(),
		condicao: $('#condicao').val(),
		quarto1: $('#quarto1_ocupado').val(),
		quarto2: $('#quarto2_ocupado').val(),
		quarto3: $('#quarto3_ocupado').val(),
		quarto4: $('#quarto4_ocupado').val()
	},function(data){
		if(data == 'success') {
			alert('Alterações salvas. Fechando página...');
			window.close();
		} else {
			$('#frame_msg').html(data);
		}
	},'html');
}

function validatePEH() {
	$('#oc1_privilegio').val($('#oc1_privilegio').val().toUpperCase());
	$('#oc2_privilegio').val($('#oc2_privilegio').val().toUpperCase());
	$('#oc3_privilegio').val($('#oc3_privilegio').val().toUpperCase());
	$('#oc4_privilegio').val($('#oc4_privilegio').val().toUpperCase());
	
	
	if($('#congregacao_cidade_id').val() == null) {
		alert('Houve um erro com respeito a sua designação. Leia mais informações no inicio da página.');
		MostraAlerta('Houve um problema com sua designação!', 'Para enviar um PEH é necessário que você seja designado como solicitante de alguma Cidade Visitante. Se a lista de cidades está vazia, significa que você não tem essa designação.<br><br> Contate um dos administradores do sistema, para que eles possam corrigir isso.', 'warning');
		$(document).scrollTop(0);
	} else if($('#tel_res').val().trim() == '' && $('#tel_cel').val().trim() == '') {
		alert('Informe pelo menos um número de telefone (residencial ou celular).');
		$('#tel_res').focus();
	} else {
		if($('#formulario_action').length > 0 && $('#formulario_action').val() == 'edit') {
			pehEdit();
		} else {
			if(getCookie('cookies_accept') != null) {
				setCookie('congresso_cidade', $('#congresso_cidade').val());
				setCookie('congregacao', $('#congregacao').val());
				setCookie('secretario_nome', $('#secretario_nome').val());
				setCookie('secretario_tel', $('#secretario_tel').val());
				setCookie('secretario_email', $('#secretario_email').val());
			}
			pehNovo();
		}
	}
	return false;
}

function validateFAC() {
	
	
	if($('#quarto2_sol_qtd').val() != '' || $('#quarto2_cas_qtd').val() != '') {
		$('#quarto2').val('yes');
	} else {
		$('#quarto2').val('not');
	}
	
	if($('#quarto3_sol_qtd').val() != '' || $('#quarto3_cas_qtd').val() != '') {
		$('#quarto3').val('yes');
	} else {
		$('#quarto3').val('not');
	}
	
	if($('#quarto4_sol_qtd').val() != '' || $('#quarto4_cas_qtd').val() != '') {
		$('#quarto4').val('yes');
	} else {
		$('#quarto4').val('not');
	}
	var dias_individuais = [];
	$('[name="dias[]"]').each(function(){
		if($(this).prop('checked') == true) {
			dias_individuais.push($(this).val());
		} 
	});
	$('#dias_individuais').val(dias_individuais.toString());
	
	console.log($('#dias_individuais').val());
	
	if($('#cidade').val() == null) {
		alert('Houve um erro com respeito a sua designação. Leia mais informações no inicio da página.');
		MostraAlerta('Houve um problema com sua designação!', 'Para enviar um FAC é necessário que você seja designado como responsável de alguma Cidade com Hospedagem. Se a lista de cidades está vazia, significa que você não tem essa designação.<br><br> Contate um dos administradores do sistema, para que eles possam corrigir isso.', 'warning');
		$(document).scrollTop(0);
	} else if($('#quarto1_sol_qtd').val() == '' && $('#quarto1_cas_qtd').val() == '') {
		// Alerta de que as camas dos quartos não foram informados
		alert('Informe a quantidade de camas que há disponível para acomodação.');
		$('#quarto1_sol_qtd').focus();
	} else if($('#dias_individuais').val() == ''){
		alert('Não foi informado os dias disponíveis. Verifique isso antes de salvar o Formulário de Acomodação.');
	} else if($('#condicao').val() == '') {
		alert('Informe as condições do quarto.');
	} else {
		if($('#formulario_action').length > 0 && $('#formulario_action').val() == 'edit') {
			facEdit();
		} else {
			if(getCookie('cookies_accept') != null) {
				setCookie('cong_nome', $('#cong_nome').val());
				setCookie('cong_sec', $('#cong_sec').val());
				setCookie('cong_tel', $('#cong_tel').val());
			}
			facNovo();
		}
	}
	return false;
}

function marcaRequired() {
	$('form input, form select, form textarea').each(function() {
		if($(this).attr('required') == 'required') {
			// Verifica se já tem asterisco
			if($(this).siblings('label').find('span.text-danger').length == 0) {
				$(this).siblings('label').append('<span class="text-danger" title="Esse item precisa ser preenchido!" data-toggle="tooltip">*</span>');
			}
		} else if(!$(this).attr('required')) {
			// Verifica se tem asterisco
			if($(this).prev('label').find('span.text-danger').length > 0) {
				$(this).prev('label').find('span.text-danger').remove('span.text-danger');
			}
		}
	});
}

function pehCarrega(id, links) {
	if(id != 0) {
		$.post(funcPage,{
			funcao: 'pehConsulta',
			id: id,
			links: links
		}, function(data){
			
			$("#panel3").html(data);
			
			// Verifica se o PANEL5 existe.
			if($("#panel5").length != 0) { // SE EXISTIR
				btnAction();
			}
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
}

function facCarrega(id, links) {
	if(id != 0) {
		$.post(funcPage,{
			funcao: 'facConsulta',
			id: id,
			links: links
		}, function(data){
			
			// Verifica se o PANEL4 existe.
			if($("#panel4").length == 0) {
				$("#panel3").html(data); // Se não existir, exibe no panel3
			} else {
				$("#panel4").html(data); // Se existir, exibe no panel4
			}
			
			// Verifica se o PANEL5 existe.
			if($("#panel5").length != 0) { // SE EXISTIR
				btnAction();
			}
			
			
		});
	}
}

function revisarForms(tipo, id, token, obj) {
	if(id != 0) {
		$.post(funcPage,{
			funcao: 'revisarForms',
			tipo: tipo,
			id: id,
			token: token
		},function(data){
			if(data == 'success') {
				if($('#'+tipo+'_rev-'+id).hasClass('badge-secondary') == true) {
					$('#'+tipo+'_rev-'+id).removeClass('badge-secondary').addClass('badge-danger');
				}
				$(obj).prop('disabled', true);
			} else {
				alert(data);
			}
		},'html');
	}
}

function carAcomod(id) {
	if(id != 0) {
		$.post(funcPage,{
			funcao: 'facConsulta',
			id: id,
			links: 0
		}, function(data){
			$('[data-toggle="tooltip"]').tooltip('disable');
			$('[data-toggle="popover"]').popover('disable');
			
			$('#panel_acomodacao').html(data);
			$('#Modal1').modal();
			
			$('[data-toggle="tooltip"]').tooltip();
			$('[data-toggle="popover"]').popover({html:true});
		});
	}
}

function filterAtendimento() {
	var input, tabela, tr, td1, td2, conta;
	
	input = $('#input_filter').val().toUpperCase();
	tabela = document.getElementById('atendimento_tabela').getElementsByTagName('tbody')[0];
	tr = tabela.getElementsByTagName('tr');
	conta = 0;
	
	for (i = 0; i < tr.length; i++) {
		td1 = tr[i].getElementsByTagName('td')[0];
		td2 = tr[i].getElementsByTagName('td')[2];
		
		if(td1 != '' || td2 != '') {
			// Verifica se dentro dessas colunas há o valor pesquisado
			if (td1.innerHTML.toUpperCase().indexOf(input) >= 0 || td2.innerHTML.toUpperCase().indexOf(input) >= 0) {
				tr[i].style.display = '';
				conta++;
			} else {
				tr[i].style.display = 'none';
			}
		}
		
	}
	if(conta == 0 && input != '') {
		$('#atendimento_frame_msg').html('Nada encontrado');
	} else {
		$('#atendimento_frame_msg').html('');
	}
	
}

function atendCarrega(id) {
	$.post(funcPage,{
		funcao: 'atendCarrega',
		id: id
	},function(data){
		$('#atendimento_modal').html(data);
		$('#modal01').modal('show');
	}, 'html');
}

function salvaPerfil() {
	$.post(funcPage,{
		funcao: 'salvaPerfil',
		id: $('#usu_id_1').val(),
		nome: $('#nome').val(),
		sobrenome: $('#sobrenome').val(),
		tel_res: $('#tel_res').val(),
		tel_cel: $('#tel_cel').val(),
		email: $('#email').val()
	},function(data){
		if(data == 'success') {
			$('#card-edit').fadeOut();
			MostraAlerta('Sucesso!', 'Seus dados foram salvos. Atualize a página para ver as alterações.', 'success');
		} else {
			MostraAlerta('Atenção!', data, 'warning');
		}
	},'html');
	
	return false;
}

function mudaSenha() {
	if($('#senha1').val() == $('#senha2').val()) {
		$.post(funcPage,{
			funcao: 'mudaSenha',
			senha_atual: $('#senha_atual').val(),
			senha1: $('#senha1').val(),
			senha2: $('#senha2').val()
		},function(data){
			if(data == 'success') {
				MostraAlerta('Parabéns!', 'Nova senha foi salva e já está valendo.', 'success');
				$('#card-senha > form')[0].reset();
				$('#card-senha').fadeOut();
			} else {
				MostraAlerta('Ops!', data, 'warning');
			}
		},'html');
	} else {
		MostraAlerta('Hey!', 'As senhas não são iguais.', 'warning');
	}
	
	return false;
}

function showSmalltip(id) {
	if(id != '') {
		$('small[for="'+id+'"]').fadeIn();
	}
}

function hideSmalltip(id) {
	if(id != '') {
		$('small[for="'+id+'"]').fadeOut();
	}
}

function adm_usuNovo() {
	var senha = '';
	if($('#senha_pad').prop('checked') == false) {
		// Senha criada manualmente
		if($('#form1_senha1').val() != $('#form1_senha2').val()) {
			alert('Senhas não são iguais');
			$('#form1_senha1').focus();
			return false;
		}
		senha = $('#form1_senha1').val();
	} else {
		// Senha padrão
		senha = '123';
	}
	
	$.post(admFunc,{
		funcao: 'UsuarioNovo',
		nome: $('#nome').val(),
		sobrenome: $('#sobrenome').val(),
		usuario: $('#usuario').val(),
		senha: senha,
		tel_res: $('#tel_res').val(),
		tel_cel: $('#tel_cel').val(),
		email: $('#email').val(),
		nivel: $('#nivel').find(':selected').val(),
		designacao: $('#designacao_1').find(':selected').val()
	},function(data){
		if(data == 'success') {
			MostraAlerta('Pronto, criado!','Usuário <i>'+$('#usuario').val()+'</i> foi criado com sucesso e já pode logar no sistema.','success');
			$('form')[0].reset();
		} else {
			MostraAlerta('Erro:',data,'danger');
		}
	},'html');
	
	return false;
}

function adm_usuCarrega() {
	var usuid = $('#sel_usu').find(':selected').val();
	
	if(usuid == 0) {
		$('#frame_info').html('');
	} else{
		$.post(admFunc,{
			funcao: 'CarregaUsu',
			usu_id: $('#sel_usu').find(':selected').val()
		},function(data){
			$('#frame_info').html(data);
			marcaRequired();
		},'html');
	}
	
}

function adm_usuSalva() {
	var tentativas, reset;
	
	if($('#senha_reset').prop('checked') == true) {
		reset = 'Y';
	} else {
		reset = 'N';
	}
	
	if($('#login_tentativa').prop('checked') == true){
		tentativas = 'Y';
	} else {
		tentativas = 'N';
	}
	
	$.post(admFunc,{
		funcao: 'UsuarioSalva',
		usuid: $('#usu_id').val(),
		nome: $('#nome').val(),
		sobrenome: $('#sobrenome').val(),
		usuario: $('#usuario').val(),
		tel_res: $('#tel_res').val(),
		tel_cel: $('#tel_cel').val(),
		email: $('#email').val(),
		nivel: $('#nivel').find(':selected').val(),
		senha_reset: reset,
		tentativas: tentativas
	},function(data){
		if(data == 'success') {
			MostraAlerta('Pronto, atualizamos!','Usuário <i>'+$('#usuario').val()+'</i> foi atualizado.','success');
			adm_usuCarrega();
		} else {
			MostraAlerta('Erro:',data,'danger');
		}
	},'html');
	
	return false;
}

function adm_cidadeNova() {
	if($('#form1_cidade').val() == '') {
		MostraAlerta('Atenção!', 'Campo CIDADE não pode ficar em branco.','warning');
	} else if($('#form1_hospedeiro').val() == '') {
		MostraAlerta('Atenção!', 'Escolha uma opção no campo FUNÇÃO DA CIDADE.','warning');
	} else {
		var cidade = $('#form1_cidade').val();
		var estado = $('#form1_estado').find(':selected').val();
		$.post(admFunc,{
			funcao: 'CidadeNova',
			cidade: $('#form1_cidade').val(),
			estado: $('#form1_estado').find(':selected').val(),
			hospedeiro: $('#form1_hospedeiro').find(':selected').val()
		},function(data){
			if(data == 'success') {
				MostraAlerta('Sucesso!', 'Cidade <strong>'+cidade+'/'+estado+'</strong> criada com sucesso. Lista de cidades foi atualizada...','success');
				adm_listaCidade();
				$('#form1')[0].reset();
			} else {
				MostraAlerta('Erro!',data,'danger');
			}
		},'html');
	}
}

function adm_listaCidade() {
	var show;
	if($('#lista_cidade').hasClass('show')) {
		show = true;
		$('#lista_cidade').removeClass('show');
	} else { 
		show = false;
	}
	$.get(admFunc,{funcao: 'ListaCidade'},function(data){
		$('#lista_cidade').html(data);
		if(show == true) {
			$('#lista_cidade').addClass('show');
		}
	},'html');
}

function adm_apagaUsuario() {
	if($('#usuid').val() == '') {
		MostraAlerta('Falha!', 'Usuário não foi escolhido. Tente novamente', 'warning');
		$('#ol_confirm').fadeOut('fast', function(){$('#escolhe_usu').fadeIn('fast')});
	} else if($('#usuario_confirm').val() == '') {
		MostraAlerta('Falha!', 'Você não confirmou seu login!', 'warning');
	} else if($('#senha_confirm').val() == '') {
		MostraAlerta('Falha!', 'Você não confirmou sua senha', 'warning');
	} else {
		$.post(admFunc,{
			funcao: 'UsuarioApaga',
			login: $('#usuario_confirm').val(),
			senha: $('#senha_confirm').val(),
			usuid: $('#usuid').val()
		},function(data){
			if(data == 'success') {
				MostraAlerta('Sucesso!', 'Usuário <i>'+$('input[name="usu_id"][value="'+$('#usuid').val()+'"]').parent().next().text()+'</i> foi excluído com sucesso. Atualize a página para ver as alterações.', 'success', false);
				$('input[name="usu_id"][value="'+$('#usuid').val()+'"]').parent().parent().remove();
				$('#ol_confirm').fadeOut('fast', function(){$('#escolhe_usu').fadeIn('fast')});
				$('button[type="reset"]').click();
				$('#formulario')[0].reset();
			} else {
				MostraAlerta('Falha!', data, 'warning');
			}
		},'html');
	}
	return false;
}

function adm_apagaCidade() {
	if($('#button_confirm_exc').data('id') == '0' || $('#button_confirm_exc').data('id') == 0) {
		alert('Desculpa... Mas você me pediu para apagar uma cidade inválida.[0]');
	} else {
		$.post(admFunc,{
			funcao: 'CidadeApaga',
			id: $('#button_confirm_exc').data('id')
		},function(data){
			if(data == 'success'){
				//$('#ModalConfirm').modal("toggle");
				$('#ModalConfirm button[data-dismiss="modal"]').click();
				setTimeout(function(){
					MostraAlerta('Sucesso!', 'Cidade removida com sucesso. Lista de cidade atualizada.', 'success');
					adm_listaCidade();
				}, 400);
			} else {
				MostraAlerta('Erro:', data, 'danger');
				$('#ModalConfirm').modal('hide');
			}
		},'html');
	}
	
}

function adm_designaResp(cidade, usuid, item) {
	$.post(admFunc,{
		funcao: 'DesignaResponsavel',
		cidade: cidade,
		usuid: usuid
	},function(data){
		var retorno = JSON.parse(data);
		if(retorno.tipo == 'success') {
			MostraAlerta('Sucesso!', retorno.mensagem, 'success');
			var sol_texto = $(item).parent().prev().find('select').find(':selected').text();
			if(sol_texto == 'Escolha') {
				sol_texto = '<span class="badge badge-warning"><i>VAZIO</i></span>';
			}
			$(item).parent().parent().parent().prev().children('span').html(sol_texto);
			$(item).parent().parent().parent().slideUp();
			$(item).parent().parent().parent().prev().slideDown();
		} else {
			MostraAlerta('', retorno.mensagem, retorno.tipo);
		}
	},'html');
}

function adm_designaSol(cidade, usuid, item) {
	$.post(admFunc,{
		funcao: 'DesignaSolicitante',
		cidade: cidade,
		usuid: usuid
	},function(data){
		var retorno = JSON.parse(data);
		if(retorno.tipo == 'success') {
			MostraAlerta('Sucesso!', retorno.mensagem, 'success');
			var sol_texto = $(item).parent().prev().find('select').find(':selected').text();
			if(sol_texto == 'Escolha') {
				sol_texto = '<span class="badge badge-warning"><i>VAZIO</i></span>';
			}
			$(item).parent().parent().parent().prev().children('span').html(sol_texto);
			$(item).parent().parent().parent().slideUp();
			$(item).parent().parent().parent().prev().slideDown();
		} else {
			MostraAlerta('', retorno.mensagem, retorno.tipo);
		}
	},'html');
}

function adm_redefinir() {
	var x = confirm('Tem certeza que deseja continuar?');
	if(x == true) {
		var opt1, opt2, opt3, opt4, opt5;
		
		opt1 = $('#opt1').prop('checked'); // Desvincular PEH e FAC
		opt2 = $('#opt2').prop('checked'); // PEH em revisão
		opt3 = $('#opt3').prop('checked'); // FAC em revisão
		opt4 = $('#opt4').prop('checked'); // Apagar datas PEH
		opt5 = $('#opt5').prop('checked'); // Apagar cidade do congresso
		
		var str = '{"opt1": '+opt1+', "opt2": '+opt2+', "opt3": '+opt3+', "opt4": '+opt4+', "opt5": '+opt5+'}';
		
		$('#frame_msg').html('<strong>Sistema de Hospedagem LS-03</strong><br><small>Redefinição do sistema em operação. Aguarde...</small> ');
		
		$.post(admFunc,{
			funcao: 'RedefinirSistema',
			json: str
		},function(data){
			console.log(data);
			var retorno = JSON.parse(data);
			//console.log(retorno);
			var retorno_str = '<strong>Sistema de Hospedagem LS-03</strong><br><small>Redefinição do sistema em operação. Retorno do sistema:</small><br><br> ';
			
			for(i = 0; i < retorno.length; i++) {
				//console.log(retorno[i]);
				retorno_str += '<strong>'+retorno[i].titulo+'</strong> &nbsp; ';
				
				if(retorno[i].mensagem == 'OK !') {
					retorno_str += '<span class="badge badge-success" style="font-size:100%;">Concluído.</span> <br>';
				} else if(retorno[i].mensagem == 'Ignorado') {
					retorno_str += '<span class="badge badge-light" style="font-size:100%;">Ignorado...</span> <br>';
				} else if(retorno[i].mensagem == ''){
					retorno_str += '<br>';
				} else {
					retorno_str += 'Erro: '+retorno[i].mensagem+' <br>';
				}
				
			}
			
			retorno_str += '<hr> Em caso de erro, por favor capture a tela (print) e comunique o desenvolvedor.';
			
			$('#frame_msg').html('<div class="alert alert-info">'+retorno_str+'</div>');
		}, 'html');
	} else {
		alert('Cancelado.');
	}
	
	return false;
}

/*
 * COOKIES
 */

function setCookie(nome, valor, duracao) {
	var cookie = nome + '=' + valor;
	if(duracao != '' && duracao != null) {
		cookie += '; duration=' + duracao.toGMTString(); 
	}
	
	document.cookie = cookie;
}

function getCookie(nome) {
	var cookies = document.cookie;
	
	// Procura o NOME do valor.
	var begin = cookies.indexOf('; '+nome);
	if(begin == -1) {
		// Não encontrado, busca sem ';'
		begin = cookies.indexOf(nome);
		
		if(begin != 0) {
			// Não encontrado de novo.
			return null;
		}
	} else {
		// Incrementa 2, para excluir o ';' e o espaço do inicio
		begin+=2;
	}
	
	// Busca o final do valor, a partir do ponto de inicio encontrado.
	var final = cookies.indexOf(';', begin);
	if(final == -1) {
		final = cookies.length;
	}
	
	
	
	// retorna valor do cookie
	return cookies.substring(begin + nome.length + 1, final);
}

function delCookie(nome) {
	if (getCookie(nome)) {
        document.cookie = nome + "=" + "; expires=Thu, 01-Jan-70 00:00:01 GMT";
	}
}

function confirmaCookies() {
	setCookie('cookies_accept','true');
	$('.alert-cookie').fadeOut();
}

function checkCookies() {
	if(getCookie('cookies_accept') == null) {
		$('.alert-cookie').fadeIn();
	}
}




$(document).ready(function(){
	
	marcaRequired();
	checkCookies();
	
	// Ativa os tooltips
	$('[data-toggle="tooltip"]').tooltip();
	$('[data-toggle="popover"]').popover({html:true});
	
	$(document).on('click', '.link_collapse', function(){
		if($(this).text() == '[+] Mostrar') {
			$(this).text('[-] Esconder');
		} else {
			$(this).text('[+] Mostrar');
		}
	});
	
	$(document).on('change', '#peh_form #nome',function(){
		if($(this).val().trim() != '') {
			if($('#oc1_nome').length > 0) {
				$('#oc1_nome').val($(this).val().trim());
			}
		}
	});
	
	$(document).on('change','#oc2_nome',function(){
		if($(this).val().trim() != '') {
			$('#oc2_idade, #oc2_parente, #oc2_privilegio').attr('required', true);
			marcaRequired();
		} else {
			$('#oc2_idade, #oc2_parente, #oc2_privilegio').attr('required', false);
			marcaRequired();
		}
	});
	$(document).on('change','#oc3_nome',function(){
		if($(this).val().trim() != '') {
			$('#oc3_idade, #oc3_parente, #oc3_privilegio').attr('required', true);
			marcaRequired();
		} else {
			$('#oc3_idade, #oc3_parente, #oc3_privilegio').attr('required', false);
			marcaRequired();
		}
	});
	$(document).on('change','#oc4_nome',function(){
		if($(this).val().trim() != '') {
			$('#oc4_idade, #oc4_parente, #oc4_privilegio').attr('required', true);
			marcaRequired();
		} else {
			$('#oc4_idade, #oc4_parente, #oc4_privilegio').attr('required', false);
			marcaRequired();
		}
	});
	
	$(document).on('click','#panel_peh .card-header a, #panel_fac .card-header a',function(){
		if($(this).text() == '[+] Mostrar') {
			$(this).text('[-] Esconder');
		} else {
			$(this).text('[+] Mostrar');
		}
	});
	
	$(document).on('click', '#voltar_ao_topo', function() {
		$(document).scrollTop(0);
	});
	
	
	
	
});