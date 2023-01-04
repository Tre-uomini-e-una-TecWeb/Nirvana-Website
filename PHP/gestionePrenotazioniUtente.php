<?php

session_start();
if($_SESSION["privilegi"] != 1){
    header("Location: ../HTML/AMMINISTRAZIONE/403.html");
    die();
}

$pagina_HTML = file_get_contents("../HTML/AMMINISTRAZIONE/gestionePrenotazioniUtente.html");

require_once "connessione.php";
use DB\DBAccess;
$connessione = new DBAccess();
$connOk = $connessione->openDBConnection();

$esitoInserimento="";
$prenotazioni = "";               

function pulisciInput($value){
    $value=trim($value);
    $value=strip_tags($value);
    $value=htmlentities($value);
    return $value;
}

if($connOk){
    $prenotazioni = "";
    $query_result = $connessione->getPrenotazioni();
    if($query_result != null){
        $dataOggi = new DateTime(date("Y-m-d"));
        foreach($query_result as $prenotazione){
            $prenotazioni .= "<tr>";
            list($dataPrenotazione,$oraPrenotazione)=explode(" ",$prenotazione['DataOra']);
            $prenotazioni .= "<td scope=\"row\">".$dataPrenotazione."</td>"
                          .  "<td scope=\"row\">".$oraPrenotazione."</td>"
                          .  "<td scope=\"row\">".$prenotazione['Trattamento']."</td>"
                          .  "<td scope=\"row\">".$prenotazione['Stato']."</td>";
            $prenotazione .= "</tr>";
        }
    }
    else{
        $prenotazioni .= "<tr><td scope=\"row\">Non ci sono prenotazioni da visualizzare.</td></tr>";
    }
} else {
    $prenotazioni="<div id=\"erroreInserimento\"><p>I nostri sistemi sono al momento non funzionanti, ci scusiamo per il disagio.</p></div>";
}

if(isset($_POST['submit'])){
    $clinente = $_SESSION["username"];
    $data = pulisciInput($_POST['data']);
    $ora = pulisciInput($_POST['ora']);
    $servizio = pulisciInput($_POST['servizio']);

    if($connOk){
        $queryOk=$connessione->insertNewReservation($cliente,$data,$ora,$servizio);
        if($queryOk){
            // Prenotazione inserita!
            $esitoInserimento="<div id=\"confermaInserimento\"><p>Inserimento avvenuto con successo!</p></div>";
        } else {
            // Prenotazione non inserita: cliente ha una prenotazione per ora e data scelti!
            $esitoInserimento="<div id=\"erroreInserimento\"><p>Impossibile inserire la prenotazione, esiste giá una prenotazione per il cliente all'orario selezionato!</p></div>";
        }
    } else {
        $esitoInserimento="<div id=\"erroreInserimento\"><p>I nostri sistemi sono al momento non funzionanti, ci scusiamo per il disagio.</p></div>";
    }


}

$pagina_HTML=str_replace("<esitoForm />", $esitoInserimento, $pagina_HTML);
$pagina_HTML=str_replace("<prenotazioniEsistenti />", $prenotazioni, $pagina_HTML);
echo $pagina_HTML;


?>