$(document).ready(function(){
	// Ativa os tooltips
	$('[data-toggle="tooltip"]').tooltip();
	$('[data-toggle="popover"]').popover({html:true});
	
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

function pehCarrega(id) {
	if(id != 0) {
		$.post(funcPage,{
			funcao: 'pehConsulta',
			id: id
		}, function(data){
			$("#panel3").html(data);
		});
	}
}

function facCarrega(id) {
	if(id != 0) {
		$.post(funcPage,{
			funcao: 'facConsulta',
			id: id
		}, function(data){
			$("#panel3").html(data);
		});
	}
}

function pehAnalise(id) {
	if(id != 0) {
		$.post(funcPage,{
			funcao: 'pehAnalise',
			id: id
		}, function(data){
			$('[data-toggle="popover"]').popover('disable');
			$("#panel4").html(data);
			$('[data-toggle="popover"]').popover({html:true});
		});
	}
}

function facAnalise(id) {
	if(id != 0) {
		$.post(funcPage,{
			funcao: 'facAnalise',
			id: id
		}, function(data){
			$('[data-toggle="popover"]').popover('disable');
			$("#panel4").html(data);
			$('[data-toggle="popover"]').popover({html:true});
		});
	}
}

function analiseCruzada(pehid, facid) {
	if(pehid != 0 && facid != 0) {
		$.post(funcPage,{
			funcao: 'analiseCruzada',
			pehid: pehid,
			facid: facid
		}, function(data){
			$('[data-toggle="popover"]').popover('disable');
			$("#panel4").html(data);
			$('[data-toggle="popover"]').popover({html:true});
		});
	}
	
}