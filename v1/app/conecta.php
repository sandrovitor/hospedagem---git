<?php
$username = 'root';
$pass = '';
$dbname = 'hospedagemls3';
$host = 'localhost';
// Conexão via PDO
$pdo = new PDO ( "mysql:host=".$host.";dbname=".$dbname, $username, $pass );
if (! $pdo) {
	die ( 'Erro ao criar a conexão' );
}

$pdo -> setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

// Configura timezone.
date_default_timezone_set("Brazil/East");
?>