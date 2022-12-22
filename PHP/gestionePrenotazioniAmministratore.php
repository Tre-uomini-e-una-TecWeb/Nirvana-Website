<?php
require_once "connessione.php";
use DB\DBAccess;
$pagina_HTML=file_get_contents("../HTML/PRENOTAZIONI/gestionePrenotazioniAmministratore.html");
$connessione=new DBAccess();
$connOk=$connessione->openDBConnection();
$clienti="";
if($connOk){
    $query_result = $connessione->getUtenti();
    $clienti="<select id=\"customers\" name=\"customers\">";
    $clienti.="<option value=\"Nope\" disabled selected>Selezionare un cliente</option>";
    if($query_result != null){
        foreach($query_result as $cliente){
            $clienti.="<option value=\"".$cliente['Username']."\">".$cliente['Nome']." ".$cliente['Cognome']." ".$cliente['DataNascita']."</option>";
        }
    }
    /*else{
        $clienti="<p>Non ci sono clienti iscritti</p>";
    }*/
    $clienti.="</select>";
} 
else {
    $clienti = "<p>Errore: impossibile contattare il server</p>";
}
$pagina_HTML=str_replace("<elencoClienti />", $clienti, $pagina_HTML);
echo $pagina_HTML;
?>