<?php
if(!isset($_REQUEST['act'])) {
    exit('Erro 403.');
}
// Carrega classes automaticamente
function __autoload($classname) {
    $filename = "../classes/". $classname .".php";
    if(file_exists($filename)) {
        include_once($filename);
    }
}

//#####################################################
function carListaPeh() { // Carrega lista de pedidos de hospedagem
	$sistema = new Sistema();
	echo $sistema->getG_PEHSelect();
}

function carListaFac() { // Carrega lista de acomodações
    $sistema = new Sistema();
    echo $sistema->getG_FACSelect();
}

function vincularForms($pehid, $facid) {
	$sistema = new Sistema();
	echo $sistema->setVinculo($pehid, $facid);
	
}

function desvincularForms($pehid, $facid) {
	$sistema = new Sistema();
	echo $sistema->setDesvinculo($pehid, $facid);
}

function desvAction($tipo, $id) {
	$sistema = new Sistema();
	echo $sistema->setDesvinculo_Sozinho($tipo, $id);
}

function gRevisarForms($tipo, $id, $token) {
    $sistema = new Sistema();
    echo $sistema->setRevisar($tipo, $id, $token);
}



if(isset($_POST['act'])) {
	switch($_POST['act']) {
		case 'carListaPeh':
			carListaPeh();
			break;
			
		case 'carListaFac':
			carListaFac();
			break;
			
		case 'vincularForms':
			vincularForms($_POST['pehid'], $_POST['facid']);
			break;
			
		case 'desvincularForms':
			desvincularForms($_POST['pehid'], $_POST['facid']);
			break;
			
		case 'desvAction':
			desvAction($_POST['tipo'], $_POST['id']);
			break;
			
		case 'gRevisarForms':
		    gRevisarForms($_POST['tipo'], $_POST['id'], $_POST['token']);
		    break;
		
	}
}