<?php
session_start();
if(!array_key_exists("username", $_SESSION) || $_SESSION["username"] == ""){//l'utente deve effettuare l'autenticazione
    header("HTTP/1.1 401 Unauthenticated");
    header("Location: ../HTML/AMMINISTRAZIONE/401.html");
    die();
}
require_once "../connessione.php";
use DB\DBAccess;
$pagina_HTML=file_get_contents("../../HTML/AREA PERSONALE/areaPersonale.html"); 
$connessione=new DBAccess();
$connOk=$connessione->openDBConnection();
$nome="";
$cognome="";
$dataNascita="";
$mail="";
$telefono="";
if($connOk){
    $query_result=$connessione->checkUtente($_SESSION["username"]);
    if($query_result != null){
        foreach($query_result as $infoUtente){
            $nome = $infoUtente['Nome'];
            $cognome = $infoUtente['Cognome'];
            $dataNascita = $infoUtente['DataNascita'];
            $mail = $infoUtente['Email'];
            $telefono = $infoUtente['Telefono'];
        }
    }
} 
else {
}

$pagina_HTML = str_replace("<usernameUtente />", $_SESSION["username"], $pagina_HTML);
$pagina_HTML = str_replace("<nomeUtente />", $nome, $pagina_HTML);
$pagina_HTML = str_replace("<cognomeUtente />", $cognome, $pagina_HTML);
$pagina_HTML = str_replace("<dataNascitaUtente />", $dataNascita, $pagina_HTML);
$pagina_HTML = str_replace("<mailUtente />", $mail, $pagina_HTML);
$pagina_HTML = str_replace("<telefonoUtente />", $telefono, $pagina_HTML);
echo $pagina_HTML;
?>