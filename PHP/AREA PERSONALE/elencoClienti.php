<?php
session_start();
if($_SESSION["privilegi"]!="admin"){
    header("Location: ../AMMINISTRAZIONE/403.php");
    die();
}
require_once "../connessione.php";
use DB\DBAccess;
$pagina_HTML=file_get_contents("../../HTML/AREA PERSONALE/elencoClienti.html");
$connessione=new DBAccess();
$connOk=$connessione->openDBConnection();
$clienti="";
if($connOk){
    $query_result = $connessione->getUtenti();
    if($query_result != null){
        foreach($query_result as $cliente){
            $clienti.="<tr>";
            $clienti .= "<td data-title='' class='header'>".$cliente['Nome']." ".$cliente['Cognome']."</td>";
            $clienti .= "<td data-title='Data di Nascita: '>".$cliente['DataNascita']."</td>";
            $clienti .= "<td data-title='Email'><a href=\"mailto:".$cliente['Email']."\">".$cliente['Email']."</a></td>";
            $clienti .= "<td data-title='Telefono'><a href=\"tel:".$cliente['Telefono']."\">".$cliente['Telefono']."</a></td>";
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