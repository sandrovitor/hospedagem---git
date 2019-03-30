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
		janelaImpressao.document.write('<meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width, initial-scale=1">');
		janelaImpressao.document.write('<html><head><meta charset="utf-8">');
		//janelaImpressao.document.write('<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css"><link href="css/glyphicon.css" rel="stylesheet">');
		//janelaImpressao.document.write('<link rel="stylesheet" href="css/bootstrap-grid.css">');
		//janelaImpressao.document.write('<link rel="stylesheet" href="css/bootstrap-reboot.css">');
		janelaImpressao.document.write('<link rel="stylesheet" href="css/print.css">');
		janelaImpressao.document.write('</head><body>');
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