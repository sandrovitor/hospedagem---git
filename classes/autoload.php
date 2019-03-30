<?php
// Carrega classes automaticamente
function __autoload($classname) {
    $filename = "classes/". $classname .".php";
    if(file_exists($filename)) {
        include_once($filename);
    }
}

// Redireciona para https
if($_SERVER['REQUEST_SCHEME'] == 'http' || $_SERVER['SERVER_PORT'] == '80') {
    header('Location: https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
}

session_start();
if(!isset($_SESSION['logado'])) {
    header('Location: login.php');
    exit();
}
/*
if(!isset($_SESSION[''])) {
    session_unset();
    session_destroy();
    
    $url_retorno = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $url_retorno = urlencode($url_retorno);
    
    header('Location: login.php?return='.$url_retorno);
    exit();
}*/
// Configura timezone.
date_default_timezone_set('America/Bahia');
$gmtTimeZone = new DateTimeZone('America/Bahia');
