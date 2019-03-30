<?php
if(!isset($_REQUEST['funcao'])) {
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

function NovoFormulario($tipo)
{
    if($tipo == 'PEH' || $tipo == '') {
        $sistema = new Sistema();
        echo $sistema->pageNovoPEH();
    } else if($tipo == 'FAC') {
        $sistema = new Sistema();
        echo $sistema->pageNovoFAC();
    } else {
        echo 'Desculpa... Parâmetro inválido.';
    }
}

function PEHNovo()
{
    $sistema = new Sistema();
    echo $sistema->setPEHNovo();
}

function FACNovo()
{
    $sistema = new Sistema();
    echo $sistema->setFACNovo();
}

function PEHConsulta($fid, $links)
{
    $sistema = new Sistema();
    echo $sistema->getPEH($fid, $links);
}

function FACConsulta($fid, $links)
{
    $sistema = new Sistema();
    echo $sistema->getFAC($fid, $links);
}

function PEHEdit()
{
    $sistema = new Sistema();
    echo $sistema->setPEHEdit();
}

function FACEdit()
{
    $sistema = new Sistema();
    echo $sistema->setFACEdit();
}

function revisarForms($tipo, $id, $token)
{
    $sistema = new Sistema();
    echo $sistema->setRevisar($tipo, $id, $token);
}

function apagarForms($tipo, $id, $token)
{
    $sistema = new Sistema();
    echo $sistema->setApagar($tipo, $id, $token);
}

function atendCarrega($id)
{
    if($id == 0 || $id == '') {
        echo 'Operação inválida!';
    } else {
        $sistema = new Sistema();
        echo $sistema->getAtendimento($id);
    }
}

function salvaPerfil()
{
    session_start();
    $_POST['id'] = $_SESSION['id'];
    $sistema = new Sistema();
    echo $sistema->setPerfilSalva();
}

function mudaSenha()
{
    $sistema = new Sistema();
    echo $sistema->setMudaSenha();
}




/*
 * ###################################################### PRINT
 */
function print_listaForms($formtipo, $formqtd) {
    $impressao = new Impressao();
    $impressao->listaForms($formtipo, $formqtd);
}

function print_imprimeForms($formtipo, $formqtd, $form3) {
    $impressao = new Impressao();
    $impressao->imprimeForms($formtipo, $formqtd, $form3);
}




//######################################################
switch($_REQUEST['funcao']){
    case 'NovoFormulario':
        NovoFormulario($_REQUEST['tipo']);
        break;
        
    case 'PEHNovo':
        PEHNovo();
        break;
        
    case 'FACNovo':
        FACNovo();
        break;
        
    case 'pehConsulta':
        PEHConsulta($_POST['id'], $_POST['links']);
        break;
        
    case 'facConsulta':
        FACConsulta($_POST['id'], $_POST['links']);
        break;
        
    case 'PEHEdit':
        PEHEdit();
        break;
        
    case 'FACEdit':
        FACEdit();
        break;
        
    case 'revisarForms':
        revisarForms($_REQUEST['tipo'], $_REQUEST['id'], $_REQUEST['token']);
        break;
        
    case 'apagarForms':
        apagarForms($_POST['tipo'], $_POST['id'], $_POST['token']);
        break;
        
    case'atendCarrega':
        atendCarrega($_POST['id']);
        break;
        
    case 'salvaPerfil':
        salvaPerfil();
        break;
        
    case 'mudaSenha':
        mudaSenha();
        break;
        
        
    case 'print_listaForms':
        print_listaForms($_POST['formtipo'], $_POST['formqtd']);
        break;
        
    case 'print_imprimeForms':
        print_imprimeForms($_POST['formtipo'], $_POST['formqtd'], $_POST['form3']);
        break;
    
    default:
        echo 'Erro 404: Função não encontrada.';
        break;
}