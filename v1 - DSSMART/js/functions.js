$(document).ready(function(){
	// Ativa os tooltips
	$('[data-toggle="tooltip"]').tooltip();
	$('[data-toggle="popover"]').popover({html:true});
	$(".chosen-select").chosen();
	
});

$(document).ajaxStart(function(){
	$("#ajax_load_img").fadeIn();
});
$(document).ajaxStop(function(){
	$("#ajax_load_img").fadeOut();
});


var funcPage = 'app/functions.php';

function formChoose(nome) {
	if(nome == 'PEH') {
		$.get('page/PedidoEspecialHospedagem.php',function(data) {
			$('#page_formulario').html(data);
			$('#page_formulario').css({border:"1px solid #333", padding:"5px", borderRadius: "5px"});
		});
	} else if(nome == 'FAc') {
		$.get('page/FormularioAcomodacao.php',function(data) {
			$('#page_formulario').html(data);
			$('#page_formulario').css({border:"1px solid #333", padding:"5px", borderRadius: "5px"});
		});
	}
	
	$("button[data-id!="+nome+"]").removeClass("active");
	$("button[data-id="+nome+"]").addClass("active");
}

function formularioCarregaCons(form_tipo, id) {
	
	$.post(funcPage, {
		funcao: 'formularioCarregaCons',
		id: id,
		form: form_tipo
	}, function(data){
		$("#panel3").html(data);
	})
}

function pehCarrega(id, links) {
	if(id != 0) {
		$.post(funcPage,{
			funcao: 'pehConsulta',
			id: id,
			links: links
		}, function(data){
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
	}
}

function facCarrega(id, links) {
	if(id != 0) {
		$.post(funcPage,{
			funcao: 'facConsulta',
			id: id,
			links: links
		}, function(data){
			$('[data-toggle="tooltip"]').tooltip('disable');
			$('[data-toggle="popover"]').popover('disable');
			
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
			
			$('[data-toggle="tooltip"]').tooltip();
			$('[data-toggle="popover"]').popover({html:true});
		});
	}
}

function vincularForms(pehid, facid) {
	if(pehid != 0 && facid != 0) {
		$.post(funcPage,{
			funcao: 'vincularForms',
			pehid: pehid,
			facid: facid
		}, function(data){
			if(data == 'OK') {
				location.reload();
			} else {
				alert(data);
			}
		});
	}
	
}

function revisarForms(tipo, id, token) {
	if(tipo == 'peh') {
		$.post(funcPage,{
			funcao: 'revisarForms',
			tipo: tipo,
			id: id,
			token: token
		},function(data){
			if(data == 'OK') {
				location.reload();
			} else {
				alert(data);
			}
		});
	} else if(tipo == 'fac') {
		alert('Em desenvolvimento...');
	}
}

function apagaPEH(id, token) {
	var c = confirm('Excluir esse Pedido de Hospedagem?');
	if(c == true) {
		$.post(funcPage, {
			funcao: 'apagaPEH',
			fid: id,
			token: token
		}, function(data){
			if(data == 'OK') {
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
			funcao: 'apagaFAC',
			fid: id,
			token: token
		}, function(data){
			if(data == 'OK') {
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

function desvAction(tipo, id) {
	if(tipo == 'peh' || tipo == 'fac') {
		
		$.post(funcPage, {
			funcao: 'desvAction',
			tipo: tipo,
			id: id
		}, function(data){
			if(data == 'OK') {
				location.reload();
			} else {
				alert(data);
			}
		});
		
	}
}

