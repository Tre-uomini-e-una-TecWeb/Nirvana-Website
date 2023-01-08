<?php
session_start();
if($_SESSION["privilegi"]!="admin"){
    header("Location: 403.php");
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
            $clienti .= "<td scope=\"row\">".$cliente['Nome']."</td>";
            $clienti .= "<td scope=\"row\">".$cliente['Cognome']."</td>";
            $clienti .= "<td scope=\"row\">".$cliente['DataNascita']."</td>";
            $clienti .= "<td scope=\"row\">".$cliente['Email']."</td>";
            $clienti .= "<td scope=\"row\">".$cliente['Telefono']."</td>";
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