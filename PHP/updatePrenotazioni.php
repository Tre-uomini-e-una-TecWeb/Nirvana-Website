<?php
require_once "connessione.php";
use DB\DBAccess;
$pagina_HTML=file_get_contents("../HTML/PRENOTAZIONI/gestionePrenotazioniAmministratore.html");
$connessione=new DBAccess();
$connOk=$connessione->openDBConnection();
$stringReplace="";
if($connOk){
    $query_result = $connessione->getUtenti();
    if($query_result != null){
        foreach($query_result as $utente){
            $stringReplace = "<h1>".$utente['Username']."</h1>";
            $stringReplace .= "<h1>".$utente['Nome']."</h1>";
            $stringReplace .= "<h1>".$utente['Cognome']."</h1>";
            $stringReplace .= "<h1>".$utente['DataNascita']."</h1>";
            $stringReplace .= "<h1>".$utente['Email']."</h1>";
            $stringReplace .= "<h1>".$utente['Telefono']."</h1>";
            $stringReplace .= "<h1>".$utente['Password']."</h1>";
            $stringReplace .= "<h1>".$utente['Privilegi']."</h1>";
        }
    }
    else{
        $stringReplace = "<h1>Non ci sono utenti iscritti.</h1>";
    }
} 
else {
    $stringReplace = "<p>Error</p>";
}
$pagina_HTML=str_replace("<numeroUtenti />", $stringReplace, $pagina_HTML);
echo $pagina_HTML;
?>