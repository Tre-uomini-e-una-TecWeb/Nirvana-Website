<?php
require_once "connessione.php";
use DB\DBAccess;
$pagina_HTML=file_get_contents("../HTML/PRENOTAZIONI/gestionePrenotazioniAmministratore.html");
$connessione=new DBAccess();
$connOk=$connessione->openDBConnection();
$clienti="";
if($connOk){
    $query_result = $connessione->getUtenti();
    if($query_result != null){
        $clienti="<select id=\"customers\" name=\"customers\">";
        foreach($query_result as $cliente){
            $clienti.="<option value=\"".$cliente['Username']."\">".$cliente['Nome']." ".$cliente['Cognome']."</option>";
        }
        $clienti.="</select>";
    }
    else{
        $clienti="<p>Non ci sono clienti iscritti</p>";
    }
} 
else {
    $clienti = "<p>Errore: impossibile contattare il server</p>";
}
$pagina_HTML=str_replace("<elencoClienti />", $clienti, $pagina_HTML);
echo $pagina_HTML;
?>