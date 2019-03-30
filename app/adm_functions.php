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

function UsuarioNovo() {
    $admin = new Admin();
    echo $admin->UsuarioNovo($_POST);
}

function UsuarioSalva() {
    $admin = new Admin();
    echo $admin->UsuarioSalva($_POST);
}

function CarregaUsu($usuid) {
    if($usuid == 0) {
        echo '';
    } else {
        $admin = new Admin();
        $array_info = $admin->UsuarioCarrega(intval($usuid));
        unset($admin);
        $pagina = new Paginas();
        echo $pagina->EditarUsuario_form($array_info);
    }
}

function UsuarioApaga() {
    $admin = new Admin();
    echo $admin->UsuarioApagar($_POST['usuid'], $_POST['login'], $_POST['senha']);
}

function CidadeNova() {
    if(!isset($_POST['cidade']) || $_POST['cidade'] == '') {
        echo 'Erro! Reveja o campo <strong>CIDADE</strong>. Não deixe em branco.';
    } else if(!isset($_POST['estado']) || $_POST['estado'] == '') {
        echo 'Erro! Reveja o campo <strong>ESTADO</strong>. Não deixe em branco.';
    } else if(!isset($_POST['hospedeiro']) || $_POST['hospedeiro'] == '') {
        echo 'Erro! Reveja o campo <strong>FUNÇÃO DA CIDADE</strong>. Não deixe em branco.';
    } else {
        $admin = new Admin();
        echo $admin->CidadeNova($_POST['cidade'], $_POST['estado'], $_POST['hospedeiro']);
    }
}

function CidadeLista() {
    $pagina = new Paginas();
    echo $pagina->ListaCidades();
}

function CidadeApaga(int $id) {
    if($id == 0) {
        echo 'Código de cidade inválido. [0]';
        exit();
    } else {
        $admin = new Admin();
        echo $admin->CidadeApaga($id);
    }
}

function DesignaResponsavel($cidade, $usuid) {
    $admin = new Admin();
    echo $admin->Designacao('resp', $cidade, $usuid);
}

function DesignaSolicitante($cidade, $usuid) {
    $admin = new Admin();
    echo $admin->Designacao('sol', $cidade, $usuid);
}

function Redefinir($json) {
    $admin = new Admin();
    echo $admin->SistemaRedefinir($json);
}





//######################################################
switch($_REQUEST['funcao']){
    case 'UsuarioNovo':
        UsuarioNovo();
        break;
        
    case 'UsuarioSalva':
        UsuarioSalva();
        break;
    
    case 'CarregaUsu':
        CarregaUsu($_POST['usu_id']);
        break;
        
    case 'UsuarioApaga':
        UsuarioApaga();
        break;
        
    case 'CidadeNova':
        CidadeNova();
        break;
        
    case 'ListaCidade':
        CidadeLista();
        break;
        
    case 'CidadeApaga':
        CidadeApaga($_POST['id']);
        break;
        
    case 'DesignaResponsavel':
        DesignaResponsavel($_POST['cidade'], $_POST['usuid']);
        break;
        
    case 'DesignaSolicitante':
        DesignaSolicitante($_POST['cidade'], $_POST['usuid']);
        break;
        
    case 'RedefinirSistema':
        Redefinir($_POST['json']);
        break;
        
    default:
        echo 'Operação inválida.';
        break;
        
}