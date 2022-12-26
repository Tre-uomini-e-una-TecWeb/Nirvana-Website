<?php
function pulisciInput($value){
    $value=trim($value);
    $value=strip_tags($value);
    $value=htmlentities($value);
    return $value;
}
require_once "connessione.php";
use DB\DBAccess;
$pagina_HTML=file_get_contents("../HTML/PRENOTAZIONI/gestionePrenotazioniAmministratore.html");
$connessione=new DBAccess();
$connOk=$connessione->openDBConnection();
$clienti="";
$esitoInserimento="";
$prenotazioni = "";
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
    $query_result = $connessione->getPrenotazioni();
    if($query_result != null){
        foreach($query_result as $prenotazione){
            //stuff
        }
    }
} 
else {
    $clienti = "<p>Non è possbile caricare la lista dei clienti.</p>";
    $prenotazioni = "<tr><td scope=\"row\">Non è possbile caricare la lista delle prenotazioni.</td></tr>";
}
/*
<tr>
    <form class="reservations">
    <td scope="row">Maria</td>
    <td scope="row">Giacomelli</td>
    <td scope="row">43</td>
    <td scope="row"><input placeholder="12/03/2023" class="textbox-n" type="text" onfocus="(this.type='date')" id="date"></td>
    <td scope="row"><input placeholder="09:25" class="textbox-n" type="text" onfocus="(this.type='time')" id="time"></td>
    <td scope="row">Epilazione con ceretta</td>
    <td scope="row">
        <select>
            <option value="" disabled selected>Da confermare</option>
            <option value="accepted">Accettato</option>
            <option value="refused">Rifiutato</option>
        </select>
    </td>
    </form>
</tr>
*/

if(isset($_POST['submit'])){
    $cliente=pulisciInput($_POST['customers']);
    $data=pulisciInput($_POST['date']);
    $ora=pulisciInput($_POST['hour']);
    $trattamento=pulisciInput($_POST['service']);
    
    /*Inserisco i dati nel DB, se non ci sono errori*/
    if($connOk){
        $queryOk=$connessione->insertNewReservation($cliente,$data,$ora,$trattamento);
        if($queryOk){//prenotazione inserita
            $esitoInserimento="<div id=\"confermaInserimento\"><p>Inserimento avvenuto con successo!</p></div>";
        }
        else{//prenotazione non inserita: cliente ha una prenotazione per ora e data scelti!
            $esitoInserimento="<div id=\"erroreInserimento\"><p>Impossibile inserire la prenotazione, esiste giá una prenotazione per il cliente all'orario selezionato!</p></div>";
        }
    }
    else{
        $esitoInserimento="<div id=\"erroreInserimento\"><p>I nostri sistemi sono al momento non funzionanti, ci scusiamo per il disagio.</p></div>";
    }
    
}
$pagina_HTML = str_replace("<elencoClienti />", $clienti, $pagina_HTML);
$pagina_HTML=str_replace("<esitoForm />", $esitoInserimento, $pagina_HTML);
$pagina_HTML=str_replace("<prenotazioniEsistenti />", $esitoInserimento, $pagina_HTML);
echo $pagina_HTML;
?>