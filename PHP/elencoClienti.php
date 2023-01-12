<?php
session_start();
if($_SESSION["privilegi"]!="admin"){
    header("HTTP/1.1 403 Unauthorized");
    header("Location: ../HTML/AMMINISTRAZIONE/403.html");
    die();
}
require_once "connessione.php";
use DB\DBAccess;
$pagina_HTML=file_get_contents("../HTML/AREA PERSONALE/elencoClienti.html");
$connessione=new DBAccess();
$connOk=$connessione->openDBConnection();
$clienti="";
if($connOk){
    $query_result = $connessione->getUtenti();
    if($query_result != null){
        foreach($query_result as $cliente){
            $clienti.="<tr>";
            $clienti .= "<td>".$cliente['Nome']."</td>";
            $clienti .= "<td>".$cliente['Cognome']."</td>";
            $clienti .= "<td>".$cliente['DataNascita']."</td>";
            $clienti .= "<td><a href=\"mailto:".$cliente['Email']."\">".$cliente['Email']."</a></td>";
            $clienti .= "<td><a href=\"tel:".$cliente['Telefono']."\">".$cliente['Telefono']."</a></td>";
            $clienti .= "</tr>";
        }
    }
    else{
        $clienti="<p>Non ci sono clienti iscritti</p>";
    }
} 
else {
    $clienti = "<p>Non è possbile caricare la lista dei clienti.</p>";
}
$pagina_HTML = str_replace("<clientiIscritti />", $clienti, $pagina_HTML);
echo $pagina_HTML;
?>