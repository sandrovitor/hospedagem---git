<?php
ini_set('session.use_cookies', '1'); // Ativa o uso de cookies
ini_set('session.use_only_cookies', '1'); // Ativa SOMENTE o uso de cookies, sem trans_id
ini_set('session.use_trans_sid', '0'); // Desliga o trans_id


session_start(); // Inicio da sessão

// Verifica se existe algum valor na sessão
if(!$_SESSION['logado'] || $_SESSION['logado'] == FALSE) {
	header('Location: login.php');
}
// Configura timezone.
date_default_timezone_set("Brazil/East");
