<?php

session_start();
if($_SESSION["privilegi"] != "cliente"){
    header("HTTP/1.1 403 Unauthorized");
    header("Location: ../../HTML/AMMINISTRAZIONE/403.html");
    die();
}
require_once "../connessione.php";
use DB\DBAccess;

$pagina_HTML = file_get_contents("../../HTML/PRENOTAZIONI/gestionePrenotazioniUtente.html");

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
    if(isset($_POST['submit'])){
        $cliente = $_SESSION["username"];
        $data = pulisciInput($_POST['date']);
        $ora = pulisciInput($_POST['hour']);
        $servizio = pulisciInput($_POST['service']);
    
        if($connOk){
            $queryOk=$connessione->insertNewReservationUser($cliente,$data,$ora,$servizio);
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


    $query_result = $connessione->getPrenotazioniUtente($_SESSION["username"]);
    if($query_result != null){
        foreach($query_result as $prenotazione){
            $prenotazioni .= "<tr>";
            list($dataPrenotazione,$oraPrenotazione)=explode(" ",$prenotazione['DataOra']);
            $prenotazioni .= "<td data-title='' class='header'>".$prenotazione['Trattamento']."</td>"
                        .    "<td data-title='Data: '>".$dataPrenotazione."</td>"
                          .  "<td data-title='Ora: '>".$oraPrenotazione."</td>"
                          .  "<td data-title='Stato richiesta: '>".$prenotazione['Stato']."</td>"
                          . "</tr>";
        }
    }
    else{
        $prenotazioni .= "<tr><td>Non ci sono prenotazioni da visualizzare.</td></tr>";
    }
} else {
    $prenotazioni="<div id=\"erroreInserimento\"><p>I nostri sistemi sono al momento non funzionanti, ci scusiamo per il disagio.</p></div>";
}

$pagina_HTML=str_replace("<esitoForm />", $esitoInserimento, $pagina_HTML);
$pagina_HTML=str_replace("<prenotazioniEsistenti />", $prenotazioni, $pagina_HTML);
echo $pagina_HTML;

?>